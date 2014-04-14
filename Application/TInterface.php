<?php

global $HtmlMessages; //global and common for all classes
$HtmlMessages=array();

class TInterface extends Help
{
    var $HtmlSetupHash,$CompanyHash; 
    var $Modules=array();
    var $PreTextMethod="";
    var $InterfacePeriods=array();
    var $NoTail=1;
    var $HeadersSend=0;
    var $DocHeadSend=0;
    var $HeadSend=0;
    var $HTML=FALSE;
    var $TInterfaceDataMessages="TInterface.php";

    var $HtmlStatusMessages=array();
    var $HtmlStatus=array();
    var $EmailMessage=array();
    var $TInterfaceTitles=array();
    var $TInterfaceLatexTitles=array();
    var $TInterfaceIcons=array();
    var $TInterfaceLatexIcons=array();

    //*
    //* sub InitTInterfaceTitles, Parameter list:
    //*
    //* Takes default titles from CompanyHash.
    //*
    //*

    function InitTInterfaceTitles()
    {
        $this->TInterfaceTitles=array
        (
           $this->CompanyHash[ "Institution" ],
           $this->CompanyHash[ "Department" ],
           $this->CompanyHash[ "Address" ],
           $this->CompanyHash[ "Area" ].", ".
           $this->CompanyHash[ "City" ]."-".
           $this->CompanyHash[ "State" ].", CEP: ".
           $this->CompanyHash[ "ZIP" ],
           $this->CompanyHash[ "Url" ]." - ".
           $this->CompanyHash[ "Phone" ]." - ".
           $this->CompanyHash[ "Fax" ]." - ".
           $this->CompanyHash[ "Email" ],
        );

        $this->TInterfaceLatexTitles=$this->TInterfaceTitles;

        $this->TInterfaceIcons=array
        (
           1 => array
           (
              "Icon"   => $this->CompanyHash[ "HtmlIcon1" ],
              "Height" => "",
              "Width"  => "",
           ),
           2 => array
           (
              "Icon"   => $this->CompanyHash[ "HtmlIcon2" ],
              "Height" => "",
              "Width"  => "",
           ),
        );

        $this->TInterfaceLatexIcons=array
        (
           1 => array
           (
              "Icon"   => $this->CompanyHash[ "LatexIcon1" ],
              "Height" => "",
              "Width"  => "",
           ),
           2 => array
           (
              "Icon"   => $this->CompanyHash[ "LatexIcon2" ],
              "Height" => "",
              "Width"  => "",
           ),
        );

    }


    //*
    //* sub ApplicationWindowTitle, Parameter list:
    //*
    //* Simply returns application window title. 
    //* Supposed to be overwritten!
    //*
    //*

    function ApplicationWindowTitle()
    {
        $id=$this->GetGET("ID");

        $vals=array();
        if ($this->Module)
        {
            if ($id!="" && $id>0)
            {
                array_push($vals,$this->Module->ItemName);
            }
            else
            {
                array_push($vals,$this->Module->ItemsName);
            }
        }

        foreach ($this->ExtraPathVars as $id => $var)
        {
            if ($this->$var!="")
            {
                array_push($vals,$this->$var);
            }
        }

        $title=$this->HtmlSetupHash[ "WindowTitle" ]."::";
        $action=$this->DetectAction();
        if ($this->Module)
        {
            if (!empty($action) && isset($this->Module->Actions[ $action ]))
            {
                $action=$this->GetRealNameKey($this->Module->Actions[ $action ],"Name");

                $action=preg_replace('/#ItemsName/',$this->Module->ItemsName,$action);
                $action=preg_replace('/#ItemName/',$this->Module->ItemsName,$action);
                $id=$this->GetGET("ID");
                if ($id!="" && $id>0)
                {
                    $name=$this->Module->GetItemName($this->Module->ItemHash);
                    array_push($vals,$name);
                }
            }
        }
        else
        {
            array_push($vals,$action);
        }

        return 
            $title.
            join("::",$vals).
            "";
    }


    //*
    //* sub InitTInterface, Parameter list:
    //*
    //* Intializes TInterface setup.
    //*
    //*

    function InitTInterface()
    {
        $this->HtmlSetupHash=$this->ReadPHPArray($this->SetupPath."/Defs/Html.Data.php");
        $this->CompanyHash=$this->ReadPHPArray($this->SetupPath."/Defs/Company.Data.php");
 
        $this->InitTInterfaceTitles();
        if ($this->HtmlSetupHash[ "CharSet" ]=="")
        {
            $this->HtmlSetupHash[ "CharSet"  ]="utf-8";
        }
        if ($this->HtmlSetupHash[ "WindowTitle" ]=="")
        {
            $this->HtmlSetupHash[ "WindowTitle"  ]="Yes I am a Mother Nature Son...)";
        }
        if ($this->HtmlSetupHash[ "DocTitle" ]=="")
        {
            $this->HtmlSetupHash[ "DocTitle"  ]="Please give me a title (HtmlSetupHash->DocTitle)";
        }
        if ($this->HtmlSetupHash[ "Author" ]=="")
        {
            $this->HtmlSetupHash[ "Author"  ]="Prof. Dr. Ole Peter Smith, IME, UFG, ole'at'mat'dot'ufg'dot'br";
        }

        $this->ApplicationName=$this->HtmlSetupHash[ "ApplicationName"  ];
    }

    //*
    //* sub ReadCSS, Parameter list:
    //*
    //* Reads CSS.
    //*
    //*

    function ReadCSS()
    {
        $css=join("",$this->MyReadFile("../MySql2/wooid.css"));

        if (is_file($this->HtmlSetupHash[ "CssUrl"  ]))
        {
            $css.=
                join("",$this->MyReadFile($this->HtmlSetupHash[ "CssUrl"  ]));
        }
        else
        {
             $this->AddMsg("Warning! No CSS file: ".$this->HtmlSetupHash[ "CssUrl"  ]);
             $this->AddMsg($this->HtmlSetupHash);
        }
        if (is_file($this->ModuleName.".css"))
        {
            $css.=join("",$this->MyReadFile($this->ModuleName.".css"));
        }

        foreach ($this->Layout as $key => $value)
        {
            $css=preg_replace('/\$'.$key.'\b/',$value,$css);
        }


        return $css;
    }

    //*
    //* sub HtmlHead, Parameter list:
    //*
    //* Sends the HTML header part.
    //*
    //*

    function HtmlHead()
    {
        if ($this->HeadersSend!=0)
        {
            $this->AddMsg("Warning! Double header (not) send",2);
            return;
        }

        if ($this->Module)
        {
            $this->Module->AddSearchVars2Cookies();
            foreach ($this->Module->CookieVars as $cid => $cookievar)
            {
                array_push($this->CookieVars,$cookievar);
            }
        }


        $vars=array
        (
           "Profile" => $this->Profile,
        );

        if ($this->LoginType=="Admin") { $vars[ "Admin" ]=1; }

        header('Content-type: text/html;charset='.$this->HtmlSetupHash[ "CharSet"  ]);
        $this->HTML=TRUE;

        print
            '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'.
            "<HTML>\n".
            $this->HtmlTags
            (
               "HEAD",
               $this->HtmlTags
               (
                  "TITLE",
                  $this->ApplicationWindowTitle()
               )."\n".
               "    ".
               $this->HtmlTag
               (
                  "LINK",
                  "",
                  array
                  (
                     "REL"  => 'shortcut icon',
                     "HREF" => $this->FindIconsPath()."/SAdE.owl.jpg",
                  )
               )."\n".
               "    ".
               $this->HtmlTag
               (
                  "META",
                  "",
                  array
                  (
                     "HTTP-EQUIV" => 'Content-type',
                     "CONTENT"    => "text/html; charset=".$this->HtmlSetupHash[ "CharSet"  ],
                  )
               )."\n".
               "    ".
               $this->HtmlTag
               (
                  "META",
                  "",
                  array
                  (
                     "NAME"    => 'Autor',
                     "CONTENT" => $this->HtmlSetupHash[ "Author"  ],
                  )
               )."\n".
               "    ".
               $this->HtmlTags
               (
                  "SCRIPT",
                  "   function setFocus()\n".
                  "   {\n".
                  "      var form=document.getElementById('Form1');\n".
                  "      if (form)\n".
                  "      {\n".
                  "         if (form['Primary']) { form['Input1'].focus(); }\n".
                  "         if (form['Login']) { form['Login'].focus(); }\n".
                  "      }\n".
                  "   }\n",
                  array("TYPE" => 'text/javascript')
               ).
               $this->HtmlTags
               (
                 "STYLE",
                 $this->ReadCSS(),
                 array("TYPE" => 'text/css')
               ).
               "\n"
            ).
            $this->HtmlTag
            (
               "BODY",
               "",
               array
               (
                  " " => $this->HtmlSetupHash[ "BodyArgs"  ],
                  "ONLOAD" => "setFocus()",
               )
            )."\n".
            "    ".
            $this->HtmlTag
            (
               "DIV",
               "",
               array
               (
                  "ALIGN" => "center",
               )
            )."\n".
            "";   

        $this->HeadersSend=1;  
    }

    //*
    //* sub GetHtmlIcon, Parameter list: $n
    //*
    //* 
    //*
    //*

    function GetHtmlIcon($n)
    {
        $key='HtmlIcon'.$n;

        $icon="";
        if (!empty($this->UnitHash[ $key ]))
        {
            $icon=$this->UnitHash[ $key ];
        }

        if (empty($icon) && !empty($this->CompanyHash[ $key ]))
        {
            $icon=$this->CompanyHash[ $key ];
        }

        $height=$this->TInterfaceIcons[ $n ][ "Height" ];
        $width=$this->TInterfaceIcons[ $n ][ "Width" ];
        $options=array
        (
           "BORDER" => 0,
           "ALT" => 'Logo',
        );

        return $this->Center
        (
           $this->Img
           (
              $this->TInterfaceIcons[ $n ][ "Icon" ],
              "",
              $this->TInterfaceIcons[ $n ][ "Height" ],
              $this->TInterfaceIcons[ $n ][ "Width" ],
              $options
           )
        );
    }

    //*
    //* sub DocTopLeft, Parameter list:
    //*
    //* 
    //*
    //*

    function DocTopLeft()
    {
        return $this->HtmlTags
        (
           "TD",
           $this->GetHtmlIcon(1),
           array("WIDTH" => '20%')
        ).
        "\n";
    }


    //*
    //* sub DocTopLeft, Parameter list:
    //*
    //* Generates upper right table TD.
    //*

    function DocTopRight()
    {
        return $this->HtmlTags
        (
           "TD",
           $this->GetHtmlIcon(2)
        );
    }

    //*
    //* sub DocTopLeft, Parameter list:
    //*
    //* Generates upper left table TD.
    //*

    function DocTopCenter()
    {
        $classes=array("headinst","headdept","headaddress","headcity","headcontacts","headcontacts");
        $html="";
        $h=0;
        foreach ($this->TInterfaceTitles as $title)
        {
            if (!empty($title))
            {
                $html.=$this->DIV
                (
                   $title,
                   array("ALIGN" => 'center',"CLASS" => $classes[ $h ])
                 )."\n";

                $h++;
            }
        }

        return $this->HtmlTags("TD",$html);
    }



    //*
    //* sub DocumentHeadRow, Parameter list:
    //*
    //* Print Document Header row.
    //*
    //*

    function DocumentHeadRow()
    {
        print
            $this->HtmlTag
            (
               "TABLE",
               "",
               array
               (
                  "WIDTH" => "100%",
               )
            )."\n".
            $this->HtmlTags
            (
               "TR",
               $this->DocTopLeft().
               $this->DocTopCenter().
               $this->DocTopRight()
             );
 
    }

    //*
    //* sub HtmlDocHead, Parameter list:
    //*
    //* Sends the HTML doc header.
    //*
    //*

    function HtmlDocHead()
    {
        if ($this->DocHeadSend!=0) { return; }

        $noheads=$this->GetCookieOrGET("NoHeads");
        if ($noheads!=1)
        {
            print
                $this->DocumentHeadRow();
        }
        else
        {
            print $this->HtmlTag
            (
               "TABLE",
               "",
               array
               (
                  "WIDTH" => "100%",
                  "BORDER" => "1",
               )
            )."\n";
        }

        print 
            "   <TR>\n".
            $this->HtmlTag
            (
               "TD",
               $this->HtmlLeftMenu(),
               array("CLASS" => 'leftmenu')
            )."\n".
            $this->HtmlTag
            (
               "TD",
               "",
               array
               (
                  "VALIGN" => 'top',
                  "CLASS" => 'ModuleCell',
               )
            )."\n".
            "";

        if ($this->Module && $this->Module->PreTextMethod!="")
        {
            $method=$this->Module->PreTextMethod;
            if (method_exists($this->Module,$method))
            {
                $this->Module->$method();
            }
            else { print "No such Module 'PreTextMethod': ".$method."<BR>"; }
        }
        elseif ($this->PreTextMethod!="")
        {
            $method=$this->PreTextMethod;
            if (method_exists($this,$method))
            {
                $this->$method();
            }
            else { print "No such Application 'PreTextMethod': ".$method."<BR>"; }
        }

        $this->DocHeadSend=1;  
        $this->NoTail=0;
   }

    function AddHtmlMessage($msg)
    {
        array_push($this->HtmlStatus,$msg);
    }

    //*
    //* sub HtmlDocTail, Parameter list:
    //*
    //* Sends the HTML doc tail.
    //*
    //*


    function HtmlDocTail()
    {
        if ($this->NoTail>0) { return; }

        if (!empty($this->Module) && method_exists($this->Module,"SendGMails"))
        {
            $this->HtmlStatus=$this->Module->HtmlStatus;
            $this->HtmlStatusMessages=$this->Module->HtmlStatusMessages;
            $this->EmailMessage=$this->Module->EmailMessage;
        }

        if (is_array($this->HtmlStatus))
        {
            //$this->HtmlStatus=preg_grep('/\S/',$this->HtmlStatus);
            if (count($this->HtmlStatus)>0)
            {
                $this->HtmlStatus=$this->HtmlList($this->HtmlStatus)."<BR>";
            }
            else
            {
                $this->HtmlStatus="";
            }
        }

        if (is_array($this->EmailMessage))
        {
           if (count($this->EmailMessage)>0)
            {
                $this->EmailMessage=$this->HtmlList($this->EmailMessage)."<BR>";
            }
            else
            {
                $this->EmailMessage="";
            }
        }

        $status="";
        $msg="";
        $options=array();
        if ($this->HtmlStatus!="")
        {
            $class="success";
            $status="Status: ";
            if ($this->HtmlError)
            {
                $class="errors";
                $status.="Erro!";
            }
            else
            {
                $status.="OK!";
            }
            $options=array("CLASS" => $class);

            $msg.=$this->HtmlStatus."<BR>";
        }

        if ($this->EmailMessage!="")
        {
            if ($this->EmailMessage!="") { $msg.=$this->EmailMessage; }

            $status=$this->DIV("<B>".$status."</B><BR><BR>\n".$msg,$options);
        }

        print
            "      </TD>".
            "      <TD VALIGN='top'>";

        if (isset($this->ExecMTime))
        {
            print
                $this->B("Module Exec Time: ").
                 $this->ExecMTime."<BR>";
        }

        print
            $msg.
            $status.
            $this->SponsorsTable().
            "      </TD>".
            "   </TR>".
            "   <TR>";
    }

    //*
    //* sub ThanksTable, Parameter list:
    //*
    //* Generates thanks table.
    //*
    //*

    function ThanksTable()
    {
        $table=array();
        $this->ConfigFiles2Hash($this->SetupPath,"Thanks.php",$table);

        if (count($table)>0)
        {
            array_unshift($table,array($this->U("Collaborators (in alfabetical order):")));
        }

        return  $this->Html_Table
        (
           "",
           $table,
           array("ALIGN" => 'center')
        );
    }


    //*
    //* sub SponsorsTable, Parameter list:
    //*
    //* Generates sponsors table.
    //*
    //*

    function SponsorsTable()
    {
        return "";
        $file=$this->SetupPath."/"."Sponsors.php";

        $table=array();
        if (file_exists($file))
        {
            $sponsors=$this->ReadPHPArray($file);
            foreach ($sponsors as $sponsor)
            {
                $link=
                    $this->Center
                    (
                       $this->Href
                       (
                          $sponsor[ "URL" ],
                          $this->IMG
                          (
                             "Uploads/Sponsors/".$sponsor[ "Icon" ],
                             "Logo ".$sponsor[ "Name" ],
                             $sponsor[ "Height" ],
                             $sponsor[ "Width" ]
                          ),
                          $sponsor[ "Name" ].": ".$sponsor[ "URL" ]
                       )
                    );

                array_push($table,array($link));
            }
        }

        if (count($table)>0)
        {
            array_unshift($table,array($this->B($this->U("Patrocínios:"))));
        }

        return  $this->Html_Table
        (
           "",
           $table,
           array("ALIGN" => 'center')
        );
    }

    //*
    //* sub SupportsTable, Parameter list:
    //*
    //* Generates supports table (Latex & friends).
    //*
    //*

    function SupportsTable()
    {
        $supports=array();
        $this->ConfigFiles2Hash($this->SetupPath,"Support.php",$supports);

        $row=array();
        foreach ($supports as $id => $supportlist)
        {
            array_push
            (
               $row,
               $this->B
               (
                  $this->U
                  (
                     array_shift($supportlist) //First in $support is title
                  )
               )
            );

            foreach ($supportlist as $rid => $support)
            {
                array_push
                (
                   $row,
                   $this->A
                   (
                      $support[ "Href" ],
                      $this->Img
                      (
                         $support[ "SRC" ],
                         $support[ "ALT" ],0,0,
                         array
                         (
                            "BORDER" => 0,
                            "ALIGN" => 'middle',
                            "WIDTH" => "75px",
                         )
                      ),
                      array("TITLE" => $support[ "Title" ])
                   )
                );
            }

        }

        return  $this->Html_Table
        (
           "",
           array($row),
           array("ALIGN" => 'center'),
           array("CLASS" => 'colaboratortable'),
           array("CLASS" => 'colaboratortable')
        );
    }

    //*
    //* subPhrase , Parameter list:
    //*
    //* Generates our phrase...
    //*
    //*

    function Phrase()
    {
        return $this->DIV
        (
           "Life sure is a Mystery to be Lived<BR>\n".
           "Not a Problem to be Solved<BR>\n",
           array("CLASS" => 'phrase')
        );
    }

    //*
    //* sub Support, Parameter list:
    //*
    //* Generates support info.
    //*
    //*

    function Support()
    {
        $authorlinks=$this->HtmlSetupHash[ "AuthorLinks"  ];
        $authorlinknames=$this->HtmlSetupHash[ "AuthorLinkNames"  ];

        $links=array();
        for ($n=0;$n<count($authorlinks);$n++)
        {
            array_push
            (
               $links,$this->A
               (
                  $authorlinks[$n],
                  $authorlinknames[$n],
                  array
                  (
                     "Target" => "_ext",
                  )
                  
               )
            );
        }

        return $this->Html_Table
        (
           "",
           array
           (
              $this->Center
              (
                 "This system uses ".
                 $this->A('http://www.google.com/search?q=cookies',"Cookies").
                 ", please enable them in you browser!"
              ),
              array
              (
                 $this->U($this->B("Author:")),
                 $this->HtmlSetupHash[ "Author"  ],
                 join(" - ",$links)
              ),
              array
              (
                 $this->U($this->B("Support:")),
                 $this->IconText
                (
                   $this->HtmlSetupHash[ "SupportEmail" ].".png",
                   $this->HtmlSetupHash[ "SupportEmail" ]
                 ),
                 ""
              ),
           ),
           array("ALIGN" => 'center'),
           array("CLASS" => 'supporttable'),
           array("CLASS" => 'supporttable')
        );
    }

    //*
    //* sub HtmlTail, Parameter list:
    //*
    //* Prints toe doc tail.
    //*
    //*

    function HtmlTail()
    {
        if ($this->NoTail>0) { return; }

        //For some reason we have chdir'ed?? 30/06/2012
        chdir(dirname($_SERVER[ "SCRIPT_FILENAME" ]));

        if ($this->GetCookieOrGET("NoHeads")!=1)
        {
            print 
                $this->HtmlTags
                (
                   "TD",
                   $this->Div
                   (
                      $this->Img
                      (
                         $this->HtmlSetupHash[ "TailIcon_Left" ],
                         "Owl",
                         "200"
                      ),
                      array("ALIGN" => 'center')
                   )
                ).
                $this->HtmlTags
                (
                   "TD",            
                   $this->HtmlTag("HR","",array("WIDTH" => '75%'))."\n".
                   $this->Support().
                   $this->HtmlTag("HR","",array("WIDTH" => '75%'))."\n".
                   $this->ThanksTable().
                    $this->HtmlTag("HR","",array("WIDTH" => '75%'))."\n".
                   $this->Phrase().
                   $this->HtmlTag("HR","",array("WIDTH" => '75%'))."\n".
                   $this->SupportsTable().
                   $this->WriteHtmlMessages()
                ).
                $this->HtmlTags
                (
                   "TD",
                   $this->Div
                   (
                      $this->Img
                      (
                         $this->HtmlSetupHash[ "TailIcon_Right" ],
                         "Owl",
                         "200"
                      ),
                      array("ALIGN" => 'center')
                    )
                );
        }

        print
            "</TR></TABLE></DIV></BODY>\n".
            "</HTML>"
            ;
             
    }


    //*
    //* sub AddHtmlStatusMessage, Parameter list: $msg
    //*
    //* Adds a messdage to HtmlStatusMessages.
    //*
    //*

    function AddHtmlStatusMessage($msg)
    {
        if (!is_array($this->HtmlStatusMessages))
        {
            $this->HtmlStatusMessages=array($this->HtmlStatusMessages);
        }
        array_push($this->HtmlStatusMessages,$msg);
    }
}
?>