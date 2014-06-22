<?php

class Access extends MySql
{
    var $ShowIDCols=array();
    var $EditIDCols=array();
    var $Permissions=array();
    var $SaveLink="";
    var $ReadOnly=FALSE;

    function Access()
    {
    }

    function InitAccess($hash=array())
    {
        if (count($hash)>0)
        {
            $this->Permissions=$hash;
        }
        else
        {
            $this->Permissions=$this->Access[ "Action" ];
        }
    }

    function MayEdit()
    {
        if (
              $this->CheckHashKeyValue($this->Actions[ "Edit" ],$this->LoginType,array(1,2))
              ||
              $this->CheckHashKeyValue($this->Actions[ "Edit" ],$this->Profile,array(1,2))
           )
        {
            return TRUE;
        }

        return FALSE;
    }

    function BelongsToEditGroup()
    {
        $hasgroups=preg_split('/\s*,\s*/',$this->LoginData[ "Groups" ]);
        $needgroups=preg_split('/\s*,\s*/',$this->EditGroup);
        foreach ($hasgroups as $id => $group)
        {
            if (preg_grep('/^'.$group.'$/',$needgroups))
            {
                 return TRUE;
            }
        }

        return FALSE;
    }

    function GrantEditGroupPrivileges($datas=array(),$deleteok=FALSE)
    {
      if ($this->LoginType=="Person")
      {
          if ($this->EditGroup!="")
          {
              if ($this->BelongsToEditGroup())
              {
                  $this->Actions[ "Edit" ][ "Person" ]=1;
                  $this->Actions[ "EditList" ][ "Person" ]=1;
                  $this->Actions[ "Add" ][ "Person" ]=1;
                  $this->Actions[ "Copy" ][ "Person" ]=1;

                  if ($deleteok)
                  {
                      $this->Actions[ "Delete" ][ "Person" ]=1;
                  }

                  if (!is_array($datas) || count($datas)==0)
                  {
                      $datas=array_keys($this->ItemData);
                  }

                  $action=$this->DetectAction();
                  if ($action=="Edit" && is_array($this->ItemData[ $this->CreatorField ]))
                  {
                      foreach ($datas as $id => $data)
                      {
                          $this->AddToList($this->ItemData[ $data ][ "ShowIDCols" ],$this->CreatorField);
                          $this->AddToList($this->ItemData[ $data ][ "EditIDCols" ],$this->CreatorField);
                      }
                  }
                  else
                  {
                      foreach ($datas as $id => $data)
                      {
                         $this->ItemData[ $data ][ "EditAllowed" ]=1; 
                         $this->ItemData[ $data ][ "Person" ]=1; 
                         $this->ItemData[ $data ][ "ShowIDCols" ]=array(); 
                         $this->ItemData[ $data ][ "EditIDCols" ]=array();
                      }
                  }
              }
          }
      }
    }

    //0: No access at all
    //1: Read only
    //2: Edit
    
    function GetDataAccessType($data,$item=array())
    {
        $res=0;
        if (!isset($this->ItemData[ $data ])) { return 0; }

        $lres=$this->GetHashKeyValue($this->ItemData[ $data ],$this->LoginType);
        $pres=$this->GetHashKeyValue($this->ItemData[ $data ],$this->Profile);

        $res=$lres;
        if ($pres>$lres) { $res=$pres; }

        if ($res==0 && preg_match('/_/',$data))
        {
            $vars=preg_split('/_/',$data);
            $varname=array_shift($vars);
            if (isset($this->ItemData[ $varname ][ "SqlObject" ]) && $this->ItemData[ $varname ][ "SqlObject" ])
            {
                $rdata=join("_",$vars);
                $object=$this->ItemData[ $varname ][ "SqlObject" ];
                $res=$this->$object->GetDataAccessType($rdata,$item);
            }
        }
        elseif ($res==2)
        {
            if ($this->ReadOnly) 
            {
                $res=1;
            }
            elseif (!empty($this->ItemData[ $data ][ "ReadOnly" ]))
            {
                $res=1;
            }
            elseif (!empty($this->ItemData[ $data ][ $this->LoginType."ReadOnly" ]))
            {
                $res=1;
            }
            elseif (!empty($item[ $data."_ReadOnly" ]))
            {
                 $res=1;
            }
        }

        return $res;
   }


    //*
    //* function HasMenuAccess, Parameter list: $menu
    //*
    //* Checks if current user has access to menuitem $menu
    //*

    function HasMenuAccess($menudef)
    {
        $access=FALSE;
        if ($menudef[ $this->LoginType ]!=0)
        {
            $access=TRUE;
        }
        elseif ($menudef[ "ConditionalAdmin" ]==1 && $this->MayBecomeAdmin())
        {
            $access=TRUE;
        }

        return $access;
    }

  function TestActionAccess($action="")
  {
      if ($action=="") { $action=$this->DetectAction(); }
      $res=$this->ActionAllowed($action);

      if ($res)
      {
          return TRUE;
      }
      else
      {
          return FALSE;
      }

      exit();
      
  }



  function RedirectAccess($newlink="",$message="")
  {
        $pathinfo=$this->GenExtraPathInfo();
        $savelink=$this->SaveLink;
        $savelink=preg_replace('/\.php[^\?]*/',".php".$pathinfo,$savelink);

        header( 'Location: '.$this->ScriptPath()."/".$savelink);
        exit();
  }

  function Redirect($newlink="")
  {
      $raction="";
      if ($newlink=="")
      {
          $raction=$this->DetectAction();
          foreach (array_keys($this->Actions) as $id => $action)
          {
              if ($raction=="")
              {
                  if ($this->ActionAllowed($action))
                  {
                      $raction=$action;
                  }
              }
          }

          if ($raction!="")
          {
              $newlink=$this->ModuleName.".php?Action=".$raction;
          }
          else
          {
              $newlink=$this->SaveLink;
          }
      }

      $pathinfo=$this->GenExtraPathInfo();
      $newlink=preg_replace('/\.php[^\?]*/',".php".$pathinfo,$newlink);
      $newlink=$this->ScriptPath()."/".$newlink;

      //header( 'Location: '.$newlink);
      print "Action $raction not allowed - ciao...";

      print $this->PrintCallStack();
      exit();
  }

}
?>