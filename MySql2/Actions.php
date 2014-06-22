<?php

class Actions extends Profile
{
    //General menus
    var $Plural=TRUE;
    var $Singular=FALSE;
    var $ReadOnly=FALSE;
    var $ActionsSingular=array("Show","Edit","Copy","Delete");
    var $ActionsSingularPlural=array("Add","Search","EditList");
    var $ActionsPlural=array("Add","Search","EditList","Export","Zip");

    //Specific menus, normally not shown
    var $ActionsActionsSingular=array();
    var $ActionsActionsPlural=array();
    var $ActualAction=""; //remove me later
    var $NextAction="";
    var $DefaultAction="Search";
    var $MySqlActions=TRUE;

    var $Actions=array();
    var $DefaultActionDef=array
    (
        "Href"          => "",
        "HrefArgs"      => "",
        "Title"         => "",
        "Title_UK"      => "",
        "Name"          => "",
        "Name_UK"       => "",
        "Icon"          => "",
        "Public"        => FALSE,
        "Person"        => FALSE,
        "Admin"         => FALSE,
        "AccessMethod"  => "",
        "Edits"         => FALSE,
        "Handler"       => "",
        "Singular"      => FALSE,
        "NoHeads"       => FALSE,
        "AddIDArg"      => FALSE,
        "Target"        => FALSE,
        "GenMethod"     => FALSE,
        "NonPostVars"   => array(),
        "AltAction"     => FALSE, //alternator to inlcude in menues, when action is current
    );

    //Vars to filter (from $this->varname) in ActionEntry.
    var $ActionArgVars=array();


    //*
    //* function AddDefaultActionKeys, Parameter list: &$action
    //*
    //* Adds all keys in $this->DefaultActionDef, unless already defined.
    //* Guaranteeing all keys present, prevents warning messages about
    //* accessing nondefined keys in action definitions.
    //*

    function AddDefaultActionKeys(&$action)
    {
        foreach ($this->DefaultActionDef as $key => $value)
        {
            if (!isset($action[ $key ]))
            {
                $action[ $key ]=$value;
            }
        }
    }

    //*
    //* function GlobalActions, Parameter list:
    //*
    //* Returns the global actions, as set in general (for whatever type of module).
    //* Uses SearchForFile (include_path) to locate this file,
    //* physically located in subdir below Actions.php (this file).
    //*

    function GlobalActions()
    {
        foreach ($this->ApplicationObj->Actions as $action => $def)
        {
            $this->Actions[ $action ]=$def;
        }

        $actionpaths=array($this->ModuleName);
        if ($this->MySqlActions)
        {
            array_unshift($actionpaths,"../MySql2");
        }

        $this->ApplicationObj->ConfigFiles2Hash
        (
           $this->ApplicationObj->ActionsPath,
           "Actions.php",
           $this->Actions,
           $actionpaths
        );

        return $this->Actions;
    }

    //*
    //* function GetModuleActions, Parameter list:
    //*
    //* Returns list of module specific actions.
    //*

    function GetModuleActions()
    {
        $actionpaths=array($this->ModuleName);
        if ($this->MySqlActions)
        {
            array_unshift($actionpaths,"../MySql2");
        }

        $actions=array();
        $this->ApplicationObj->ConfigFiles2Hash
        (
           $this->ApplicationObj->ActionsPath,
           "Actions.php",
           $actions,
           $actionpaths
        );

        return array_keys($actions);
    }

    //*
    //* function InitActions, Parameter list:
    //*
    //* Initilizes Actions class, ie:
    //* Reads actions from independent file: Actions/Actions.php.
    //* Uses SearchForFile (include_path) to locate this file,
    //* phisically located in subdir below Actions.php (this file).
    //*
    //* Also adds module specific action; in $this->ModuleName."/Actions.php".
    //*

    function InitActions()
    {
        $this->GlobalActions();

        $actionsfile=$this->ApplicationObj->ModuleActionsFile($this->ModuleName);
        if (file_exists($actionsfile))
        {
            $this->AddActions($actionsfile);
        }
        $this->ApplicationObj->AddHelpAction();

        $actions=array();
        if ($this->Actions)
        {
            $actions=array_keys($this->Actions);
        }

        //Make sure all actions should have permissions set
        $actionsdefined=array();
        foreach ($actions as $id => $action)
        {
            foreach ($this->DefaultActionDef as $key => $value)
            {
                if (!isset($this->Actions[ $action ][ $key ]))
                {
                    $this->Actions[ $action ][ $key ]=$value;
                }
            }

            if (!isset($this->Actions[ $action ][ "Href" ]))
            {
                $this->Actions[ $action ][ "Href" ]=$this->ModuleName.".php";
            }

            //Action should have permissions set
            $actionsdefined[ $action ]=1;
        }

        
        if (isset($this->ProfileHash[ "Actions" ]))
        {
            //Take Actions permissions defined in Profile
            foreach ($this->ProfileHash[ "Actions" ] as $action => $val)
            {
                if (is_array($val))
                {
                    $this->Actions[ $action ][ $this->LoginType ]=$val[ "Access" ];
                    $this->Actions[ $action ][ $this->Profile ]=$val[ "Access" ];
                    foreach ($val[ "Attributes" ] as $key => $value)
                    {
                        $this->Actions[ $action ][ $key ]=$value;
                    }
                }
                else
                {
                    $this->Actions[ $action ][ $this->LoginType ]=$val;
                    $this->Actions[ $action ][ $this->Profile ]=$val;
                }

                //Permissions set on action, delete key
                unset($actionsdefined[ $action ]);
            }
        }

        //Permissions from ApplicationObj, remove as well.
        foreach (array_keys($this->ApplicationObj->Actions) as $action)
        {
            unset($actionsdefined[ $action ]);
        }

        //If we are read only, prevent access to Actions with Edits==1.
        if ($this->ReadOnly)
        {
            foreach ($this->Actions as $id => $action)
            {
                if (
                      !isset($this->Actions[ $id ][ "Edits" ])
                      ||
                      $this->Actions[ $id ][ "Edits" ]==1
                   )
                {
                    $this->Actions[ $id ][ $this->LoginType ]=0;
                    $this->Actions[ $id ][ $this->Profile ]=0;
                }
            }
        }
    }

    //*
    //* function AddActions, Parameter list: $actions=array()
    //*
    //* Adds actions $action s - if this is a file name, read the actions in  this file.
    //*

    function AddActions($actions=array())
    {
        if (!is_array($actions))
        {
            $actions=$this->ReadPHPArray($actions);
        }

        foreach ($actions as $action => $def)
        {
            if (!is_array($def))
            {
                $this->Actions[ $action ]=array();
            }

            foreach ($def as $key => $value)
            {
                $this->Actions[ $action ][ $key ]=preg_replace('/#Module/',$this->ModuleName,$value);
            }
        }
    }

    //*
    //* function GetActionHref, Parameter list: $action,$idfields
    //*
    //* Generates link, based on $this->Actions[ $action ].
    //* If $idfield is set, gets $idfield from POST/GET and includes $idfield=$id.
    //*

    function GetActionHref($action,$idfields=array("ID"))
    {
        $href=$this->ModuleName.".php?Action=".$action;
        if ($withid)
        {
            foreach ($idfields as $key => $idfield)
            {
                $href.="&".$idfield."=".$this->GetPOSTOrGet($idfield);
            }
        }
    }

    //*
    //* function GetActionLink, Parameter list: $action,$idfields
    //*
    //* Generates link, based on $this->Actions[ $action ].
    //* If $idfield is set, gets $idfield from POST/GET and includes $idfield=$id.
    //*

    function GetActionLink($action,$name,$idfields=array("ID"))
    {
        return $this->Href
        (
           $this->GetActionHref($action,$idfields),
           $name
        );

    }

    //*
    //* function ItemActionLink, Parameter list: $action,$item
    //*
    //* Generates link, based on $this->Actions[ $action ].
    //* Filters $this->Actions[ $action ][ "Href" ] over #ID.
    //*

    function ItemActionLink($action,$item,$name,$target="")
    {
        if ($target=="")
        {
            if ($this->Actions[ $action ][ "Target" ]!="")
            {
                $target=$this->Actions[ $action ][ "Target" ];
            }
            else
            {
                $target=$this->ModuleName;
            }
        }

        $module=$this->Actions[ $action ][ "Href" ];
        if ($module=="") { $module=$this->ModuleName.".php"; }

        $href=
            $module."?".
            "Action=".$action.$this->URL_Args_Separator.
            $this->Actions[ $action ][ "HrefArgs" ];

        $title=$this->GetRealNameKey($this->Actions[ $action ],"Title");
        if ($title=="") { $title=$this->ItemName.": ".$name; }
        $title=$this->FilterHash($title,$item);
        $title=preg_replace('/#Name/',$name,$title);

        return 
            $this->FilterHash
            (
               $this->Href
               (
                  $href,
                  $name,
                  $title,
                  $target
               ),
               $item
            );

    }

    //*
    //* function GetSaveAction, Parameter list: $action
    //*
    //* Returns some action that is permitted.
    //*

    function GetSaveAction($action)
    {
        if (!$this->Actions) { return ""; }

        if (isset($this->Actions[ $action ][ "AltAction" ]))
        {
             $raction=$this->Actions[ $action ][ "AltAction" ];
        }
        else
        {
            $raction=$this->DefaultAction;
        }
        
        if ($raction=="" || (!$this->ActionAllowed($raction)) )
        {
            foreach ($this->Actions as $taction => $actiondef)
            {
                if ($this->ActionAllowed($taction))
                {
                    $raction=$action;
                }
            }
        }

        if ($raction=="")
        {
            print "Actions exhausted...";
            exit();
        }

        //$this->Actions[ $action ][ "Name" ]=$this->Actions[ $raction ][ "Name" ];

        return $raction;
    }

    //*
    //* function DetectAction, Parameter list: $action,$withid=FALSE
    //*
    //* Detects current Action, which should be readable from the POST Action.
    //*

    function DetectAction()
    {
        $args=$this->Query2Hash();

        if ($this->NextAction!="")
        {
            $raction=$this->NextAction;
        }
        else
        {
            $raction=$this->GetGETOrPOST("Action");
        }

        if ($raction=="") { $raction=$this->DefaultAction; }
        if ($raction=="")
        {
            $raction=$args[ "Action" ];
        }

        $res=$this->ActionAllowed($raction);
 
        if (!$res)
        {
           $raction=$this->GetSaveAction($raction);
        }
        $this->Action=$raction;

        $this->Plural=FALSE;
        $this->Singular=FALSE;

        if ($this->Actions)
        {
            if (
                (
                 isset($this->Actions[ $raction ][ "Singular" ])
                 &&
                 $this->Actions[ $raction ][ "Singular" ]==1
                )
                /* || */
                /* preg_grep('/^'.$raction.'$/',$this->ActionsSingular) */
                /* || */
                /* preg_grep('/^'.$raction.'$/',$this->ActionsActionsSingular) */
               )
            {
                $this->Singular=TRUE;
            }
            else
            {
                $this->Plural=TRUE;
            }
        }

        return $raction;
    }

    //*
    //* function ActualAction, Parameter list:
    //*
    //* Returns the actual  action, as specifieid by the CGI.
    //* Should consider POST vars??
    //*

    function ActualAction()
    {
        foreach ($this->Actions as $action => $def)
        {
            if ($this->GetGET($action))
            {
                return $action;
            }
        }
    }

    //*
    //* function ActionName, Parameter list: $action
    //*
    //* Returns the name ("Name" key) of action $action.
    //*

    function ActionName($action)
    {
        if (!empty($this->Actions[ $action ][ "Name" ]))
        {
            $action=$this->Actions[ $action ][ "Name" ];
        }

        $action=preg_replace('/#ItemsName/',$this->ItemsName,$action);
        $action=preg_replace('/#ItemName/',$this->ItemName,$action);

        return $action;
    }

    //*
    //* function GetActionNames, Parameter list: $actions
    //*
    //* Returns the names ("Name" key) of action $actions.
    //*

    function GetActionNames($actions)
    {
        $names=array();
        foreach ($actions as $action)
        {
            array_push($names,$this->ActionName($action));
        }

        return $names;
    }

    //*
    //* function ActualActionName, Parameter list: 
    //*
    //* Returns the name ("Name" key) of the actual action.
    //*

    function ActualActionName()
    {
        return $this->ActionName($this->ActualAction());
    }

    //*
    //* function UnSUAccess, Parameter list: 
    //*
    //* Does what's necessary to allow action UnSU.
    //*

    function UnSUAccess()
    {
         if (
              isset($this->SessionData[ "SULoginID" ])
              &&
              $this->SessionData[ "SULoginID" ]>0
           )
        {
            return TRUE;
        }

        return FALSE;
    }

    //*
    //* function CheckHashAccess, Parameter list: $hash,$value=1
    //*
    //* Tests if $hash has $this->LoginType and $this->Profile keys with value $value.
    //*

    function CheckHashAccess($hash,$value=1)
    {
        $logintype=$this->LoginType;
        if (!empty($this->ApplicationObj)) { $logintype=$this->ApplicationObj->LoginType; }

        if (empty($logintype)) { $logintype="Public"; }

        $profile=$this->Profile;
        if (!empty($this->ApplicationObj->Profile)) { $profile=$this->ApplicationObj->Profile; }

        if (empty($profile)) { $profile="Public"; }


        $res=FALSE;
        if ($logintype=="Admin")
        {
            if ($hash[ "Admin" ]==$value)
            {
                $res=TRUE;
            }
        }
        elseif ($logintype=="Person")
        {
            if (isset($hash[ $profile ]))
            {
                if ($hash[ $profile ]==$value)
                {
                    $res=TRUE;
                }
            }

            if (isset($hash[ "Person" ]))
            {
                if ($hash[ "Person" ]==$value)
                {
                    $res=TRUE;
                }
            }
            else
            {
                if ($this->ApplicationObj && $this->ApplicationObj->MayBecomeAdmin())
                { 
                    if ($hash[ "ConditionalAdmin" ]==$value)
                    {
                        $res=TRUE;
                    }
                }
            }
        }
        elseif ($logintype=="Public")
        {
            if ($hash[ "Public" ]==$value)
            {
                $res=TRUE;
            }
        }

        return $res;
    }


   //*
    //* function ActionAllowed, Parameter list: $action,$item=array()
    //*
    //* Detects whether action $action is allowed or not.
    //* If $this->Actions[ $action ][ "AccessMethod" ] is set, calls
    //* this method, in order to decide whether access is permitted or not.
    //* In absence of $item parameter (which is passed to this routine),
    //* $this->ItemHash is used.
    //* If no AccessMethod is defined, uses $this->Actions[ $action ]
    //* Admin, Person and Public keys.
    //*

    function ActionAllowed($action,$item=array())
    {
        if (!$this->Actions) { $this->InitActions(); }

        if (count($item)==0 && isset($this->ItemHash)) { $item=$this->ItemHash; }

        $logintype=$this->LoginType;
        if ($logintype=="") { $logintype="Public"; }

        if (isset($this->Actions[ $action ]))
        {
            if (
                !empty($this->Actions[ $action ][ "AccessMethod" ])
               )
            {
                $accessmethod=$this->Actions[ $action ][ "AccessMethod" ];
                if (method_exists($this,$accessmethod))
                {
                     return $this->$accessmethod($item);
                }
                else
                {
                    $this->Debug=1;
                    $this->AddMsg("Warning: Invalid access method (action: $action): ".
                                  $accessmethod.", ignored");
                }
            }
        }
        else { return FALSE; }

        return $this->CheckHashAccess($this->Actions[ $action ],1);
    }

    //*
    //* function ActionEntry, Parameter list: $data,$item=array(),$noicons=0,$class="",$args=array()
    //*
    //* Generates action menu entry, subject to specifications in
    //* $this->Actions [ $data ].
    //*

    function ActionEntry($data,$item=array(),$noicons=0,$class="",$rargs=array())
    {
        $size=20;
        if ($this->IconsPath=="")
        {
            $this->IconsPath=$this->FindIconsPath();
        }

        if (!empty($this->Actions[ $data ]) && is_array($this->Actions[ $data ]))
        {
            if ($this->ActionAllowed($data,$item))
            {

                if (!isset($this->Actions[ $data ][ "Name" ])) { return ""; }

                $args=$this->Query2Hash("");
                $args=$this->Hidden2Hash($args);

                foreach ($this->Actions[ $data ][ "NonPostVars" ] as $var)
                {
                    unset($args[ $var ]);
                }

                $args[ "ModuleName" ]=$this->ModuleName;
                $args[ "Action" ]=$data;

                unset($args[ "ID" ]);

                foreach ($rargs as $key => $value) { $args[ $key ]=$value; }

                if ($this->Actions[ $data ][ "GenMethod" ])
                {
                    $method=$this->Actions[ $data ][ "GenMethod" ];
                    return $this->$method($data,$item);
                }

                if ($this->Actions[ $data ][ "HrefArgs" ]!="")
                {
                    $args=$this->Query2Hash($this->Actions[ $data ][ "HrefArgs" ],$args);
                }

                $id="";
                if (isset($item[ "ID" ])) { $id=$item[ "ID" ]; }
                if ($id=="") { $id=$this->GetGETOrPOST("ID"); }

                if (
                    $this->Actions[ $data ][ "AddIDArg" ] &&
                    $id!="" && $id>0
                   )
                {
                    $args[ "ID" ]=$id;
                }

                $href=$this->Actions[ $data ][ "Href" ];
                if (empty($href)) { $href="index.php"; }

                $action=
                    $href."?".
                    $this->Hash2Query($args);

                if ($id!="" && $id>0) { $action=preg_replace('/#ID/',$id,$action); }
                else                  { $action=preg_replace('/\&?ID=#ID/',"",$action); }

                foreach ($this->ActionArgVars as $var)
                {
                    $action=preg_replace('/#'.$var.'/',$this->$var,$action);
                }

                $title=$this->GetRealNameKey($this->Actions[ $data ],"Title"); 
                $text=$this->Actions[ $data ][ "Icon" ];

                if ($noicons==1 || $text=="")
                {
                    $text=$this->GetRealNameKey($this->Actions[ $data ],"Name"); 
                }
                else
                {
                    $text=
                       $this->IMG
                       (
                          $this->Icons."/".
                          $text,
                          $text,
                          $size,
                          $size
                       );
                }

                $action=$this->Href($action,$text,$title,$this->Actions[ $data ][ "Target" ],$class);
                $action=$this->Filter($action,$item);
                $action=preg_replace('/#Module(Name)?/',$this->ModuleName,$action);
                $action=preg_replace('/#ItemName/',$this->ItemName,$action);
                $action=preg_replace('/#ItemsName/',$this->ItemsName,$action);

                return $action;
            }
            elseif (isset($this->Actions[ $data ][ "AltAction" ]))
            {
                $rdata=$this->Actions[ $data ][ "AltAction" ];
                return $this->ActionEntry($rdata,$item,$noicons,$class);
            }
            elseif (isset($this->Actions[ $data ][ "AltIcon" ]))
            {
                return
                     $this->Center($this->IMG
                     (
                      //$this->IconsPath."/".
                       $this->Actions[ $data ][ "AltIcon" ],
                       "Não Disponível",$size,$size
                     ));
            }
        }
        else
        {
            $this->AddMsg("Warning: Action $data undefined!");
        }

        return "";
    }

    function ActionPrintEntry($action,$item,$noicons=0,$class="",$rargs=array())
    {
        $this->Actions[ "P" ]=$this->Actions[ $action ];

        $this->Actions[ "P" ][ "Icon" ]=$this->Actions[ "Print" ][ "Icon" ];


        $this->Actions[ "P" ][ "Title" ]=preg_replace
        (
           '/^\S+/',
           "Versão Imprimível:",
           $this->Actions[ "P" ][ "Title" ]
        );

        return $this->ActionEntry
        (
           "P",
           $item,
           0,
           $class,
           array("Latex" => 1)
        );
     }
}
?>