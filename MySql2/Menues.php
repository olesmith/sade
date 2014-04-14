<?php


class Menues extends Export
{
    var $TInterfaceMenuSend=0;
    var $Menu=array();
    var $MenuMessages="Menues.php";
    var $LeftMenuPostTextMethod="";
    var $DataGroupMenuWritten=FALSE;
    var $NoInterfaceMenu=FALSE;
    var $NoLogonMenu=TRUE;

    var $HorisontalMenues=array("Singular","Plural","SingularPlural","ActionsSingular","ActionsPlural",);

    //*
    //* function InitMenues, Parameter list:
    //*
    //* Initilizes Menus class, that is herits horisontal menues from ProfileHash;.
    //*

    function InitMenues()
    {
        if (empty($this->ProfileHash[ "Menues" ])) { return; }

        foreach (array_keys($this->ProfileHash[ "Menues" ]) as $id => $menu)
        {
            $varname="Actions".$menu;
            $this->$varname=$this->ProfileHash[ "Menues" ][ $menu ];
       }
    }


    //*
    //* function MakeActionMenu, Parameter list: $pactions,$cssclass,$id=""
    //*
    //* Generates and prints menu of actions as in $pactions,
    //* using $cssclass as CSS class parameter to ActionEntry.
    //*

    function MakeActionMenu($pactions,$cssclass,$id="",$item=array(),$title="")
    {
        $caction=$this->DetectAction();

        if (empty($item)) { $item=$this->ItemHash; }

        $args=$_SERVER[ "QUERY_STRING" ];
        $hrefs=array();

        $included=array();
        foreach ($pactions as $action)
        {
            $raction=$action;
            if (!empty($this->Actions[ $action ][ "AltAction" ]))
            {
                $raction=$this->Actions[ $action ][ "AltAction" ];
            }

            if (!empty($included[ $raction ])) { continue; }

            //Exlude both - or just one
            $included[ $raction ]=1;
            $included[ $action ]=1;

            if ($this-> ActionAllowed($action,$item))
            {
                if ($caction!=$action)
                {
                    array_push
                    (
                       $hrefs,
                       $this->ActionEntry($action,$item,1,$cssclass)
                    );
                }
                elseif (
                          $raction
                          &&
                          $raction!=$action
                          &&
                          $this-> ActionAllowed($raction,$item)
                          &&
                          !empty($this->Actions[ $raction ])
                       )
                {
                     array_push
                    (
                       $hrefs,
                       $this->ActionEntry($raction,$item,1,$cssclass)
                    );
                }
                else
                {
                    $itemname=$this->GetItemName();

                    $name=$this->Actions[ $action ][ "Name" ];
                    $name=preg_replace('/#ID/',$id,$name);
                    $name=preg_replace('/#ItemName/',$this->ItemName,$name);
                    $name=preg_replace('/#ItemsName/',$this->ItemsName,$name);

                    array_push($hrefs,$this->SPAN($name,array("CLASS" => 'inactivemenuitem')));
                }
            }
       }

        return preg_replace('/#ID/',$id,$this->HRefMenu($title,$hrefs));
    }

    //*
    //* function SystemMenu, Parameter list: 
    //*
    //* Prints horisontal system menu.
    //*

    function SystemMenu()
    {
        print $this->MakeActionMenu(array("Backup","SysInfo","Process","Profiles","Zip"),"atablemenu","");
    }


    //*
    //* function TInterfaceMenu, Parameter list: $plural=FALSE,$id=""
    //*
    //* Prints horisontal menu of Singular and Plural actions.
    //*

    function TInterfaceMenu($plural=FALSE,$id="")
    {
        if ($this->TInterfaceMenuSend!=0)
        {
            return;
        }

        $action=$this->DetectAction();

        if ($this->Actions[ $action ][ "Singular" ]) { $plural=FALSE; }

        $this->InitMenues();
        $this->ApplicationObj->AddHelp2Menues();

        $aactions=$this->ActionsActionsPlural;
        $pactions=$this->ActionsPlural;
        $sactions=array();

        if ($this->Singular)
        {
            $sactions=$this->ActionsSingular;
            $pactions=$this->ActionsSingularPlural;
            $aactions=$this->ActionsActionsSingular;
        }

        $modules=$pactions;

        $menues=array();
        if (count($pactions)>0) { array_push($menues,$this->MakeActionMenu($pactions,"ptablemenu",$id)); }
        if (count($aactions)>0) { array_push($menues,$this->MakeActionMenu($aactions,"atablemenu",$id)); }
        if (count($sactions)>0) { array_push($menues,$this->MakeActionMenu($sactions,"stablemenu",$id)); }

        print join("<BR>",$menues)."<BR>";

        $this->Modules=$modules;
        $this->TInterfaceMenuSend=1;
    }    
}
?>