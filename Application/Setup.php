<?php

class Setup extends Modules
{

    //*
    //* Variables of Setup class:
    //*

    var $SetupFileDefs=array();
    var $ProfilesFile="#Setup/Profiles.php";
    var $AccessFile="#Setup/Access.php";
    var $LeftMenuFile="#Setup/LeftMenu.php";
    var $ModulesFile="#Setup/#Module/Profiles.php";
    var $ModuleDataFile="#Setup/#Module/Data.php";
    var $ModuleLatexDataFile="#Setup/#Module/Latex.Data.php";

    //*
    //* Initializer
    //*

    function InitSetup($hash=array())
    {
        if (count($hash)>0)
        {
            $this->SetupFileDefs=$hash;
        }
    }

    //*
    //* function ModuleSetupDataPath, Parameter list:  $module
    //*
    //* Returns SetupDataPath.
    //*

    function ModuleSetupDataPath($module="")
    {
        if ($module=="") { $module=$this->ModuleName; }

        if (!empty($this->SubModulesVars[ $module ][ "SqlFile" ]))
        {
            return
                $this->SetupPath.
                "/".
                preg_replace
                (
                   '/\.php$/',
                   "/",
                   $this->SubModulesVars[ $module ][ "SqlFile" ]
                );
        }

        return "";
    }

    //*
    //* function ParseSystemFileName, Parameter list: $file,$module=""
    //*
    //* Substitutes $this->ModuleName for #ModuleName and
    //* $this->SetupPath for #Setup in $file.
    //*

    function ParseSystemFileName($file,$module="")
    {
        if (empty($module)) { $module=$this->ModuleName; }

        return preg_replace
        (
           '/#Module/',
           $module,
           preg_replace
           (
              '/#Setup/',
              $this->SetupPath,
              $file
           )
        );
    }


    //*
    //* function ProfileFile, Parameter list: 
    //*
    //* Returns name of the overall Profile file, defining data for Profiles.
    //*

    function ProfileFile()
    {
        return $this->ParseSystemFileName($this->ProfilesFile);
    }

    //*
    //* function ModuleProfileFile, Parameter list: $module=""
    //*
    //* Returns name of Profile file specific to $this->ModuleName.
    //* Overrides MySql2 version, in order to branch between
    //* Classes & Daylies interfaces.
    //*

    function ModuleProfileFile($module="")
    {
        $modfile=$this->ModuleSetupDataPath($module)."/Profiles.php";
        if (!file_exists($modfile))
        {
            print "No Profiles file: ".$modfile."<BR>";exit();
        }

        return $modfile;
    }

    //*
    //* function ModuleDataFile, Parameter list: $module=""
    //*
    //* Returns name of Data file specific to $this->ModuleName
    //*

    function ModuleDataFile($module="")
    {
         return $this->ModuleSetupDataPath($module)."/Data.php";
         //return $this->ParseSystemFileName($this->ModuleDataFile,$module);
    }

    //*
    //* function ModuleLatexDataFile, Parameter list: $module=""
    //*
    //* Returns name of LatexData file specific to $this->ModuleName
    //*

    function ModuleLatexDataFile($module="")
    {
        return $this->ModuleSetupDataPath($module)."/Latex.Data.php";
        //return $this->ParseSystemFileName($this->ModuleLatexDataFile,$module);
    }

    //*
    //* function ModuleActionsFile, Parameter list: $module=""
    //*
    //* Returns name of Actions file specific to $this->ModuleName
    //*

    function ModuleActionsFile($module="")
    {
        return $this->ModuleSetupDataPath($module)."/Actions.php";
        //return $this->ParseSystemFileName($this->ModuleLatexDataFile,$module);
    }

    //*
    //* function AccessProfilesFile, Parameter list:
    //*
    //* Returns name of file with Permissions and Accesses to Modules. 
    //*

    function AccessProfilesFile()
    {
        return $this->ParseSystemFileName($this->AccessFile);
    }

    //*
    //* function LeftMenuFile, Parameter list:
    //*
    //* Returns name of file with Left Menu. 
    //*

    function LeftMenuFile()
    {
        return $this->ParseSystemFileName($this->LeftMenuFile);
    }

    //*
    //* Initializer
    //*

    function AddLocalSetupHash($name,$hash=array())
    {
        $hash[ "Local" ]=1;
        $this->SetupFileDefs[ $name  ]=$hash;

    }

    //*
    //* Read include key from cgi (local/global/..)
    //*

    function ReadSetupIncludeKey()
    {
        $global=$this->GetGET("Global");
        $includekey="Local";
        if ($global==1)
        {
            $includekey="Global";
        }

        return $includekey;
    }

    //*
    //* Returns name of file containing setup data
    //*

    function SetupDefFileName($fid)
    {
        if (is_array($this->SetupFileDefs[ $fid ]))
        {
            return preg_replace
            (
               '/#Setup/',
               $this->SetupPath,
               $this->SetupFileDefs[ $fid ][ "DefFile" ]
            );
        }
    }
    //*
    //* Returns name of file containing setup data
    //*

    function SetupDataFileName($fid,$module="")
    {
        if (empty($module)) { $module=$this->ModuleName; }

        $file="";
        if (!empty($this->SetupFileDefs[ $fid ][ "File" ]))
        {
            $file=$this->SetupFileDefs[ $fid ][ "File" ];
        }

        if (empty($file) || !is_file($file))
        {
            $file=$this->SetupDefFileName($fid);
            $file=preg_replace('/\.php$/',".Data.php",$file);
            $file=preg_replace
            (
               '/^'.$this->SetupPath.'/',
               $this->SetupPath."/Defs",
               $file
            );
        }

        if (empty($file) || !is_file($file))
        {
            $file=$this->SetupPath."/".$module."/Vars.php";
        }

        if (empty($file) || !is_file($file))
        {
            $file=$this->Module->SetupDataPath()."/Vars.php";
        }

        if (!is_file($file))
        {
            print "Setup Definition Vars file: $file not found";
            var_dump($this->SetupFileDefs[ $fid ]);
            exit();
        }

        $this->SetupFileDefs[ $fid ][ "File" ]=$file;
        return $file;
    }

    //*
    //* Set object ($this) values given setup hash
    //*

    function SetupHash2Object($fid,$hash,$moduleobj=NULL,$tothis=TRUE)
    {
        $filedef=$this->SetupFileDefs[ $fid ];
        if ($filedef[ "Destination" ]!="")
        {
            $dest=$filedef[ "Destination" ];
            if ($tothis) { $this->$dest=$hash; }
            if ($moduleobj)
            {
                $moduleobj->$dest=$hash;
            }
        }
        else
        {
            foreach ($hash as $key => $value)
            {
                if ($tothis) { $this->$key=$value; }
                if ($moduleobj)
                {
                    $moduleobj->$key=$value;
                }
            }
        }
    }

    //*
    //* Gather setup hash from actual object $this
    //*

    function Object2SetupHash($fid)
    {
        $filedef=$this->SetupFileDefs[ $fid ];
        $dest=$this->SetupFileDefs[ $fid ][ "Destination" ];

        $ohash=array();

        if ($dest!="" && is_array($this->$dest))
        {
            $ohash=$this->$dest;
        }

        $hash=array();
        foreach ($this->SetupFileDefs[ $fid ][ "Keys" ] as $id => $key)
        {
            if ($dest!="")
            {
                $dest=$filedef[ "Destination" ];
                $hash[ $key ]=$ohash[ $key ];
            }
            else
            {
                $hash[ $key ]=$this->$key;
            }
        }

        return $hash;
    }


    //*
    //* Read setup file $fid
    //*

    function ReadSetupFile($fid,$moduleobj=NULL,$tothis=TRUE)
    {
        $deffile=$this->SetupDefFileName($fid);

        $module="";
        if ($moduleobj) { $module=$moduleobj->ModuleName; }

        $file=$this->SetupDataFileName($fid,$module);

        if (!is_file($deffile))
        {
            print "Setup Definition file: $deffile not found";
            var_dump($this->SetupFileDefs[ $fid ]);
            exit();
        }

        $defhash=$this->ReadPHPArray($deffile);
        $allowedkeys=array_keys($defhash);
        $this->SetupFileDefs[ $fid ][ "Def" ]=$defhash;
        $this->SetupFileDefs[ $fid ][ "Keys" ]=$allowedkeys;


        $hash=$this->ReadPHPArray($file);
        foreach ($hash as $key => $value)
        {
            if (!preg_grep('/^'.$key.'$/',$allowedkeys))
            {
                unset($hash[ $key ]);
                $this->AddMsg
                (
                   "Unset $fid ($deffile) setup key: $key:<BR>".
                   join(", ",$allowedkeys)
                );
            }
        }

        $this->SetupHash2Object($fid,$hash,$moduleobj,$tothis);
    }


    //*
    //* Read setup files
    //*

    function ReadSetupFiles($setupdefs=array(),$moduleobj=NULL,$tothis=TRUE)
    {
        if (count($setupdefs)==0) { $setupdefs=$this->SetupFileDefs; }

        $this->SetupFileDefs=$setupdefs;
        foreach ($this->SetupFileDefs as $fid => $filedef)
        {
           $this->ReadSetupFile($fid,$moduleobj,$tothis);
        }
    }

    //*
    //* Write setup file $fid
    //*

    function WriteSetupFile($fid)
    {
        $filedef=$this->SetupFileDefs[ $fid ];

        $hash=$this->Object2SetupHash($fid);

        $text=array("array\n","(\n");
        foreach ($hash as $key => $value)
        {
            if ($filedef[ "Def" ][ $key ][ "Type" ]=="scalar")
            {
                //scalar
                array_push($text,"   '".$key."' => '".$value."',\n");
            }
            elseif ($filedef[ "Def" ][ $key ][ "Type" ]=="list")
            {
                //list
                array_push($text,"   '".$key."' => array\n","   (\n");

                $max=$filedef[ "Def" ][ $key ][ "Length" ];
                if ($max=="") { $max=count($hash[ $key ]); }

                for ($n=0;$n<$max;$n++)
                {
                    array_push($text,"      '".$hash[ $key ][ $n ]."',\n");                    
                }
                array_push($text,"   ),\n");
            }
            elseif ($filedef[ "Def" ][ $key ][ "Type" ]=="hash")
            {
                //Hashes
                array_push($text,"   '".$key."' => array\n","   (\n");

                foreach ($value as $id => $val)
                {
                    $hash[ $key ][ $id ]=preg_replace('/\s+$/',"",$hash[ $key ][ $id ]);

                    array_push($text,"      '".$id."' => '".$hash[ $key ][ $id ]."',\n");                    
                }
                array_push($text,"   ),\n");
            }
            elseif ($filedef[ "Def" ][ $key ][ "Type" ]=="hash2")
            {
                //2 level hashes (hash of hashes)
                $keys=$filedef[ "Def" ][ $key ][ "Keys" ];
                array_push($text,"   '".$key."' => array\n","   (\n");

                if (!is_array($value)) { $value=array(); }

                foreach ($value as $id => $val)
                {
                    array_push($text,"      '".$id."' => array\n","      (\n");


                    foreach ($val as $rid => $rval)
                    {
                        if (!is_array($keys) || preg_grep('/^'.$rid.'$/',$keys))
                        {
                            $rval=$hash[ $key ][ $id ][ $rid ];
                            array_push($text,"            '".$rid."' => '".$rval."',\n");
                        }
                    }


                    array_push($text,"      ),\n");                    
                }
                array_push($text,"   ),\n");
            }
        }

        $file=$this->SetupDataFileName($fid);
        array_push($text,");\n");

        $this->MyWriteFile($file,$text);
    }


    //*
    //* Create setup files menu
    //*

    function SetupFilesMenu($global=FALSE)
    {
        $includekey=$this->ReadSetupIncludeKey();

        $hrefs=array();
        $titles=array();
        $btitles=array();
        foreach ($this->SetupFileDefs as $fid => $filedef)
        {
            if ($filedef[ $includekey ]==1)
            {
                $args="Action=Setup&".$includekey."=1";

                array_push
                (
                 $hrefs,
                 "?".$args."&FID=".$fid
                );
                array_push
                (
                 $titles,
                 $filedef[ "Name" ]
                );
                array_push
                (
                 $btitles,
                 $filedef[ "Title" ]
                );
            }
        }

        
        return $this->HrefMenu("Parâmetros",$hrefs,$titles,$btitles,4);
    }

 
    //*
    //* Runs through setup files and figures out which one to edit.
    //* Call SetupFileForm for the selected one.
    //*

    function SetupFilesForm()
    {
        $includekey=$this->ReadSetupIncludeKey();

        $fids=array_keys($this->SetupFileDefs);

        $rfid=$this->GetGETOrPOST("FID");
        if ($rfid=="")
        {
             foreach ($fids as $id => $fid)
             {
                 if ($rfid=="" && $this->SetupFileDefs[ $fid ][ $includekey ]==1)
                 {
                     $rfid=$fid;
                 }
             }
        }

        foreach ($fids as $id => $fid)
        {
            if ($fid==$rfid && $this->SetupFileDefs[ $fid ][ $includekey ]==1)
            {
                $this->SetupFileForm($fid);
            }
        }
    }

    //*
    //* Edit all setup file $fid
    //*

    function SetupFileForm($fid)
    {
        if ($this->GetGET("DumpSetup")==1)
        {
            $this->DumpSetupFile($fid);
            exit();
        }

        if ($this->GetPOST("Update")==1)
        {
            $this->UpdateSetupFile($fid);
        }

        $filedef=$this->SetupFileDefs[ $fid ];

        $hash=$this->Object2SetupHash($fid);
        $table=array();
        foreach ($hash as $key => $value)
        {
            $title=
                "<B>".$filedef[ "Def" ][ $key ][ "Name" ].
                " ['".$key."']:</B>";

            $len=$filedef[ "Def" ][ $key ][ "Size" ];
            if ($len=="")
            {
                $len=strlen($value);
            }

            if ($filedef[ "Def" ][ $key ][ "Type" ]=="scalar")
            {
                $input=$this->MakeInput($fid."_".$key,$value,$len);
                array_push
                (
                   $table,
                   array($title,$input)
                );
            }
            elseif ($filedef[ "Def" ][ $key ][ "Type" ]=="list")
            {
                $max=$filedef[ "Def" ][ $key ][ "Length" ];
                if ($filedef[ "Def" ][ $key ][ "Length" ]=="")
                {
                    $max=count($hash[ $key ]);
                }

                $len=$filedef[ "Def" ][ $key ][ "Size" ];
                if ($len=="")
                {
                    $len=0;
                    for ($n=0;$n<$max;$n++)
                    {
                        if (strlen($value[$n])>$len) { $len=strlen($value[$n]); }
                    }
                }

                $convert=FALSE;
                if ($filedef[ "Def" ][ $key ][ "Sort" ]==1)
                {
                    $sorthash=array();
                    for ($n=0;$n<count($value);$n++)
                    {
                        $sorthash[ $value[ $n ] ]=$n;
                    }

                    $keys=array_keys($sorthash);
                    sort($keys);

                    $n=0;
                    foreach ($keys as $rkey)
                    {
                        $val=$sorthash[ $rkey ];
                        $convert[ $n ]=$val;
                        $n++;
                    }
                }

                if ($filedef[ "Def" ][ $key ][ "Length" ]=="")
                {
                    $max=count($hash[ $key ])+1;
                }

                for ($n=0;$n<$max;$n++)
                {
                    $nn=$n;
                    if (is_array($convert))
                    {
                        $nn=$convert[$n];
                    }

                    array_push
                    (
                        $table,
                        array
                        (
                           $title,
                           "<B>".($n+1).":</B>",
                           $this->MakeInput($fid."_".$key."_".$nn,$value[$nn],$len)
                        )
                    );

                    $title="";
                }
            }
            elseif ($filedef[ "Def" ][ $key ][ "Type" ]=="hash")
            {
                $len=$filedef[ "Def" ][ $key ][ "Size" ];
                if ($len=="")
                {
                    $len=0;
                    foreach ($value as $id => $val)
                    {
                        if (strlen($val)>$len) { $len=strlen($val); }
                    }
                }

                foreach ($value as $id => $val)
                {
                    $field="";
                    if ($filedef[ "Def" ][ $key ][ "AreaField" ]!="")
                    {
                        $field=$this->MakeTextArea($fid."_".$key."_".$id,
                                                         $filedef[ "Def" ][ $key ][ "Height" ],
                                                         $filedef[ "Def" ][ $key ][ "Width" ],
                                                         $val);
                    }
                    else
                    {
                        $field=$this->MakeInput($fid."_".$key."_".$id,$val,$len);
                    }

                    array_push
                    (
                        $table,
                        array
                        (
                           $title,
                           "<B>".$id.":</B>",
                           $field
                        )
                    );

                    $title="";
                }

                array_push
                (
                   $table,
                   array
                   (
                      $title,
                      $this->MakeInput($fid."_".$key."_".$id."__Name__","",10),
                      $this->MakeInput($fid."_".$key."_".$id."__Value__","",$len),
                   )
                );
            }
            elseif ($filedef[ "Def" ][ $key ][ "Type" ]=="hash2")
            {
                $len=$filedef[ "Def" ][ $key ][ "Size" ];
                if ($len=="")
                {
                    $len=0;
                    foreach ($value as $id => $val)
                    {
                        foreach ($val as $rid => $rval)
                        {
                            if (strlen($rval)>$len) { $len=strlen($rval); }
                        }
                    }
                }

                if (!is_array($value)) { $value=array(); }
                foreach ($value as $id => $val)
                {
                    $keys=$filedef[ "Def" ][ $key ][ "Keys" ];

                    $rtitle="<B>".$id.":</B>";
                    foreach ($val as $rid => $rval)
                    {
                        if (!is_array($keys) || preg_grep('/^'.$rid.'$/',$keys))
                        {
                            $rkey=$fid."_".$key."_".$id."_".$rid;
                            array_push
                            (
                             $table,
                             array
                             (
                              $title,
                              $rtitle,
                              "<B>".$rid.":</B>",
                              $this->MakeInput($rkey,$rval,$len)
                             )
                            );

                            $rtitle="";
                            $title="";

                            $keys=preg_grep('/^'.$rid.'$/',$keys,PREG_GREP_INVERT);
                        }
                    }

                    if (count($keys)>0)
                    {
                        $var=$fid."_".$key."_".$id;
                        $varname=$var."__Name__";

                        if (is_array($keys))
                        {
                            array_unshift($keys,"");
                            $select=$this->MakeSelectField($varname,$keys,$keys,"");
                        }
                        else
                        {
                            $select=$this->$this->MakeInput($varname,"",10);
                        }

                        array_push
                        (
                         $table,
                         array
                         (
                          $title,
                          $rtitle,
                          $select,
                          $this->MakeInput($var,"",$len),
                         )
                        );
                    }

                }
                        array_push
                        (
                         $table,
                         array
                         (
                          $title,
                          $this->MakeInput($fid."_".$key,"",$len),
                         )
                        );
            }
           
        } 

        print
            $this->H(4,
                     "&gt;&gt; Altere aqui se - <U>E SOMENTE SE</U> - ".
                     "você sabe o que está fazendo!! &lt;&lt").
            $this->SetupFilesMenu().
            $this->UploadSetupFileForm($fid,FALSE).
            $this->H(2,"Editar: ".$filedef[ "Title" ]).
            $this->StartForm().
            $this->Buttons().
            $this->HtmlTable("",$table).
            $this->MakeHidden("Update",1).
            $this->MakeHidden("FID",$fid).
            $this->Buttons().
            $this->EndForm();
    }

    //*
    //* Update setup file $fid
    //*

    function UpdateSetupFile($fid)
    {
        $filedef=$this->SetupFileDefs[ $fid ];

        $hash=$this->Object2SetupHash($fid);

        $rhash=array();
        foreach ($hash as $key => $value)
        {
            $regex=$filedef[ "Def" ][ $key ][ "Regex" ];
            if ($filedef[ "Def" ][ $key ][ "Type" ]=="scalar")
            {
                $newvalue=$this->GetPOST($fid."_".$key);
                if ($regex!="")
                {
                    if (!preg_match('/'.$regex.'/',$newvalue))
                    {
                        $this->HtmlStatus.=$newvalue." não conforma ao regexp. ".$regex."<BR>";
                        $newvalue=$value;
                    }
                }

                $rhash[ $key ]=$newvalue;
            }
            elseif ($filedef[ "Def" ][ $key ][ "Type" ]=="list")
            {
                $max=$filedef[ "Def" ][ $key ][ "Length" ];
                if ($filedef[ "Def" ][ $key ][ "Length" ]=="")
                {
                    $max=count($hash[ $key ]);
                }

                for ($n=0;$n<=$max;$n++)
                {
                    $rkey=$fid."_".$key."_".$n;
                    $value=$this->GetPOST($rkey);
                    if ($value!="")
                    {
                        $rhash[ $key ][ $n ]=$this->GetPOST($rkey);
                    }
                }
            }
            elseif ($filedef[ "Def" ][ $key ][ "Type" ]=="hash")
            {
                foreach ($value as $id => $val)
                {
                    $rkey=$fid."_".$key."_".$id;
                    $rhash[ $key ][ $id ]=$this->GetPOST($rkey);  
                }
            }
            elseif ($filedef[ "Def" ][ $key ][ "Type" ]=="hash2")
            {
                if (!is_array($value)) { $value=array(); }
                foreach ($value as $id => $val)
                {
                    $keys=$filedef[ "Def" ][ $key ][ "Keys" ];

                    foreach ($val as $rid => $rval)
                    {
                        $rkey=$fid."_".$key."_".$id."_".$rid;
                        if (!is_array($keys) || preg_grep('/^'.$rid.'$/',$keys))
                        {
                            if (!is_array($rhash[ $key ]))
                            {
                                $rhash[ $key ]=array();
                            }

                            if (!is_array($rhash[ $key ][ $id ]))
                            {
                                $rhash[ $key ][ $id ]=array();
                            }

                            $newvalue=$this->GetPOST($rkey);
                            $rhash[ $key ][ $id ][ $rid ]=$this->GetPOST($rkey);
                        }
                        else
                        {
                            $this->AddMsg("Invalid field: $rid, not in: ".join(", ",$keys));
                        }
                    }

                    $var=$this->GetPOST($fid."_".$key."_".$id);
                    $varname=$this->GetPOST($fid."_".$key."_".$id."__Name__");
                    if ($varname!="")
                    {
                        $rhash[ $key ][ $id ][ $varname ]=$var;
                    }
                }

                $var=$this->GetPOST($fid."_".$key);
                if ($var!="")
                {
                    $rhash[ $key ][ $var ]=array();
                }

            }
        } 

        $this->SetupHash2Object($fid,$rhash);

        $this->WriteSetupFile($fid);

        return $rhash;
    }

    //*
    //* Upload setup file $fid
    //*

    function UploadSetupFileForm($fid,$return=TRUE)
    {
        if ($this->GetPOST("Upload")==1)
        {
            $this->UploadSetupFile($fid);
        }

        $includekey=$this->ReadSetupIncludeKey();

        $html=
            "<CENTER>".
            $this->H(2,"Upload do Arquivo:<BR>".$this->SetupFileDefs[ $fid ][ "File" ]).
            $this->StartForm("","post",1).
            $this->MakeFileField("File_".$fid).
            $this->MakeHidden("Upload",1).
            $this->MakeHidden($includekey,1).
            $this->MakeHidden("FID",$fid).
            $this->Button("submit","Enviar").
            $this->Href("?Setup=1&DumpSetup=1&".$includekey."=1&FID=".$fid,"Download")."<BR><BR>".
            $this->EndForm();

        if ($return)
        {
            $html.=$this->Href("?Search=1","Retornar");            
        }

        $html.="</CENTER>";

        return $html;
    }

    //*
    //* Upload setup file $fid
    //*

    function UploadSetupFile($fid)
    {
        $uploadinfo=$_FILES[ "File_".$fid ];
        $extensions=array("php");

        if (is_array($uploadinfo))
        {
            $tmpname=$uploadinfo['tmp_name'];
            $name=$uploadinfo['name'];
            $error=$uploadinfo['error'];

            $comps=preg_split('/\./',$name);
            $ext=array_pop($comps);

            if (preg_grep('/^'.$ext.'$/',$extensions))
            {
                $destfile=$this->SetupFileDefs[ $fid ][ "File" ];
                $res=move_uploaded_file($tmpname,$destfile);

                $this->ReadSetupFile($fid);
                print $tmpname." --> ".$destfile.": ".$res."<BR>";
            }
            else
            {
                print "Error: Invalid extension $ext<BR>";
            }
        }

    }

    //*
    //* Dump (export) setup file $fid
    //*

    function DumpSetupFile($fid)
    {
        $file=$this->SetupDataFileName($fid);
        $php=$this->MyReadFile($file);

        print "OI";
        $this->SendDocHeader("php",$file);
        print join("",$php);
    }
}


?>