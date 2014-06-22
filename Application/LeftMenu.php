<?php


class LeftMenu extends MySql
{
    var $NoLeftMenu=FALSE;
    var $Menu=array();
    var $MenuMessages="LeftMenu.php";
    var $LeftMenuPostTextMethod="";
    var $PeriodLinks=NULL;


    //*
    //* function DoWeHaveAccess, Parameter list: $hash
    //*
    //* Checks whether current login/profile has acces via hash.
    //*

    function DoWeHaveAccess($hash)
    {
        $res=FALSE;
        if (
            (
               isset($hash[ $this->LoginType ])
               &&
               $hash[ $this->LoginType ]>0
             )
            ||
            (
               isset($hash[ $this->Profile ])
               &&
               $hash[ $this->Profile ]>0
             )
            )
         {
             $res=TRUE;
         }

        return $res;
    }

    //*
    //* function ReadProfileMenu, Parameter list: 
    //*
    //* Reads the menus pertaining to profile $this->Profile.
    //* If $this->Profile is empty, return Public menus.
    //*

    function ReadProfileMenu()
    {
        //Read menus
        if ($this->Profile=="") { $this->Profile="Public"; }

        $rmenues=array();
        foreach ($this->ReadPHPArray($this->LeftMenuFile()) as $name => $submenu)
        {
            $inc=FALSE;
            $rsubmenu=array();
            if ($this->DoWeHaveAccess($submenu))
            {
                $inc=FALSE;

                $rsubmenu=array();
                foreach ($submenu as $sname => $item)
                {
                    if (!is_array($submenu[ $sname ]))
                    {
                        $rsubmenu[ $sname ]=$item;
                    }
                    else
                    {
                        if (
                              (
                                 isset($item[ $this->LoginType ])
                                 &&
                                 $item[ $this->LoginType ]>0
                              )
                              ||
                              (
                                 isset($item[ $this->Profile ])
                                 &&
                                 $item[ $this->Profile ]>0
                              )
                           )
                        {
                            $rsubmenu[ $sname ]=$item;
                            $inc=TRUE;
                        }
                    }
                }

                if ($inc || !empty($submenu[ "Method" ]))
                {
                   $rmenues[ $name ]=$rsubmenu;
                }
            }
        }

        $this->LeftMenu=$rmenues;
    }


    //*
    //* function GenerateSubMenuList, Parameter list:
    //*
    //* Generates (returns) Left submenu.
    //*

    function GenerateSubMenuList($submenu,$item=array())
    {
            $list=array();
            if (isset($submenu[ "Method" ]))
            {
                $method=$submenu[ "Method" ];
                $list=$this->$method();
            }
            else
            {
                $menuids=array_keys($submenu);
                sort($menuids);

                foreach ($menuids as $menuid)
                {
                    if (!is_array($submenu[ $menuid ])) { continue; }
                    if (!$this->CheckHashAccess($submenu[ $menuid ],1)) { continue; }

                    $url=$submenu[ $menuid ][ "Href" ];
                    if (preg_match('/#/',$url))
                    {
                        $url=$this->Filter($url,$_GET);
                    }

                    $href=$this->GetRealNameKey($submenu[ $menuid ],"Name");
                    if (TRUE)
                    {
                        $href="+ ".$this->Href
                        (
                           $url,
                           $this->GetRealNameKey($submenu[ $menuid ],"Name"),
                           $this->GetRealNameKey($submenu[ $menuid ],"Title"),
                           $this->GetRealNameKey($submenu[ $menuid ],"Target"),
                           "leftmenulinks"
                        );
                    }
                    else
                    {
                        $href="- ".$this->GetRealNameKey($submenu[ $menuid ],"Name");
                    }

                    array_push($list,$href);
                }
            }

            return $list;
    }

    //*
    //* function GenerateSubMenu, Parameter list:
    //*
    //* Generates (returns) the Left menu.
    //*

    function GenerateSubMenu($submenu,$item=array())
    {
        $list=$this->GenerateSubMenuList($submenu);
        if (count($list)==0) { return ""; }

        return 
           $this->FilterHashes
           (
              $this->HTMLList
              (
                 $list,
                 "UL",
                 array
                 (
                    "CLASS" => 'leftmenulist',
                 )
               ),
              array($item,$this->LoginData,$this->CompanyHash),
              TRUE
           );        
    }

    //*
    //* function GenerateLeftMenu, Parameter list:
    //*
    //* Generates (returns) the Left menu.
    //*

    function GenerateLeftMenu()
    {
        $this->CompanyHash[ "Language" ]=$this->GetLanguage();
        $this->CompanyHash[ "Path" ]=$this->ScriptPath();

        $html="";
        foreach ($this->LeftMenu as $submenuname => $submenu)
        {
            if (!is_array($submenu))
            {
                $html.=$submenu;
            }
            else
            {
                $menu=$this->GenerateSubMenu($submenu);

                if (is_array($menu)) { $menu=join("",$menu); }
                $html.=
                    $this->DIV
                    (
                       $this->GetRealNameKey($submenu,"Title"),
                       array("CLASS" => 'leftmenutitle')
                    ).
                    $menu;
            }
        }

        return $html;
    }

    //*
    //* function HtmlLeftMenu, Parameter list: $menudefs=array()
    //*
    //* Creates the left menu (modules/system navigation)
    //*

    function HtmlLeftMenu($menudefs=array())
    {
        $this->ReadProfileMenu();

        $html=$this->TimeStamp2Text()."<BR>";

        $html= 
            $this->DIV
            (
               $this->GetMessage($this->TInterfaceDataMessages,"LMWelcomeMessage").": ".
               $this->HtmlSetupHash[ "ApplicationName"  ].", Ver. ".$this->HtmlSetupHash[ "ApplicationVersion"  ].
               "<BR>".
               $this->TimeStamp2Text(),
               array("CLASS" => "userinfotable")
            );

        if (!empty($this->Period))
        {
            $per="";
            if (is_array($this->Period))
            {
                $per="Período Atual: ".$this->Period[ "Name" ];
            }
            else
            {
                $per="Período Atual: ".$this->Period;
            }
            $html.=$this->DIV($per,array("CLASS" => "periodtitle"));
        }

        if (is_array($this->LoginData) && $this->LoginData[ "Name" ]!="")
        {
            $file=$this->LoginData[ "ID" ].".png";
            $name=$this->IconText($file,$this->LoginData[ "Email" ]);


            $table=array
            (
               array
               (
                  "Login: ",
                  $this->LoginData[ "Email" ]
               ),
               array
               (
                  "Alias: ",
                  $this->LoginData[ "Name" ]
               ),
               array
               (
                  "Perfil:",
                  $this->GetProfileName()
               ),
            );

            $html.=
                "<BR>".
                $this->HtmlTable("",$table,array(),TRUE,"userinfotable").
                "";

            if ($this->ReadOnly && $this->LoginType!="Public")
            {
                $html.=$this->H(5,"Somente Leitura!");
            }
            else
            {
                $html.=$this->BR();
            }

        }
        else
        {
            $html.= $this->GetMessage($this->TInterfaceDataMessages,"LMAnonymousAccessMessage")."<BR>";
        }


        if ($this->LoginType=="Admin")
        {
            $html.=$this->DIV
            (
               " >> Admin << ",
               array("CLASS" => 'adminnotice')
            ).
            "<BR>";
        }

        if (is_array($this->LeftMenu) && !$this->NoLeftMenu)
        {
            $html.=$this->GenerateLeftMenu();
        }

        if (is_array($this->PeriodLinks) && count($this->PeriodLinks)>0)
        {
            if ($this->CPeriod!="")
            {
                $html.= 
                    $this->SPAN
                    (
                       $this->GetMessage($this->TInterfaceDataMessages,"PeriodPhrase").": ",
                       array("CLASS" => 'periodphrase')
                    ).
                    "<BR>\n".
                    $this->Center($this->SPAN($this->CPeriod,array("CLASS" => 'leftmenuperiod'))).
                    "<BR>\n";

                $this->PeriodLinks=preg_grep('/'.$this->CPeriod.'/',$this->PeriodLinks,PREG_GREP_INVERT);
            }

            $html.=
                $this->HRefVerticalMenu
                (
                   $this->GetMessage($this->TInterfaceDataMessages,"PeriodPhrasePlural").":",
                   array_reverse($this->PeriodLinks)
                );

        }

        return $html;
    }

    //*
    //* function HtmlLanguageMenu, Parameter list: 
    //*
    //* Prints menu of images, for user to select language.
    //*

    function HtmlLanguageMenu()
    {
        $rlang=$this->GetLanguage();

        $args=$this->Query2Hash();

        $html="";
        foreach ($this->Languages as $lang => $langdef)
        {
            if ($rlang!=$lang)
            {
                $ipath=$this->FindIconsPath();
                $img=$this->IMG($ipath."/".$langdef[ "Icon" ],$langdef[ "Text" ],50,75);

                $args[ "Lang" ]=$lang;
                $query=$this->Hash2Query($args);

                $html.=$this->Center($this->Href("?".$query,$img,$langdef[ "Text" ]));
            }
        }

        return $html;
    }

    //*
    //* function HtmlProfilesMenu, Parameter list: 
    //*
    //* Prints menu of images, for user to select profile.
    //*

    function HtmlProfilesMenu()
    {
        if ($this->LoginType=="Public") { return; }

        $links=array();

        $action="";
        if ($this->Module)
        {
            $action=$this->Module->DefaultAction;
        }

        if ($action=="") { $action=$this->DefaultAction; }
        foreach ($this->AllowedProfiles as $id => $profile)
        {
            $pname=$this->GetProfileName($profile);
            if ($profile!=$this->Profile)
            {
                $args=array
                (
                   "Profile" => $profile,
                   "Action" => $action,
                );

                if ($this->GetGET("Unit"))
                {
                    $args[ "Unit" ]=$this->GetGET("Unit");
                }

                if ($profile=="Admin")
                {
                    $args[ "Action" ]="Admin";
                    $args[ "Admin" ]=1;
                }
                elseif ($this->LoginType=="Admin")
                {
                    $args[ "Admin" ]=0;
                }

                array_push
                (
                   $links,
                   "+ ".$this->Href
                   (
                      "?".$this->Hash2Query($args),
                      $pname,
                      "Virar ".$pname,
                      "",
                      "leftmenulinks"
                   )
                );
            }
            else
            {
                array_push($links,"&nbsp;- ".$pname);
            }
        }

        return $links;
    }

    //*
    //* function GenerateItemListSubMenu, Parameter list: $menumethod,$items,$activeid,$href,$name,$title,$class="menulinks",$add="+",$sub="-"
    //*
    //* Generates (returns) the Left menu.
    //*

    function GenerateItemListSubMenu($menumethod,$items,$activeid,$href,$name,$title,$class="menulinks",$add="+",$sub="-")
    {
        $list=array();
        foreach ($items as $id => $item)
        {
            $text="";
            if ($item[ "ID" ]==$activeid)
            {
                $text=
                    "&nbsp;".$sub." ".
                    $this->Href
                    (
                       $this->Filter($href,$item),
                       $this->Filter($name,$item),
                       $this->Filter($title,$item),
                       "",
                       $class
                    ).
                    ":".
                    $this->BR().
                    $this->$menumethod();
            }
            else
            {
                $text=
                    $add." ".
                    $this->Href
                    (
                       $this->Filter($href,$item),
                       $this->Filter($name,$item),
                       $this->Filter($title,$item),
                       "",
                       $class
                    );
            }

            array_push($list,$text);
        }

        return $list;
    }

}
?>