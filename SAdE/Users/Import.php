<?php

include_once("People.php");


class UsersImport extends People
{
    var $Grades=array();
    var $ImportTeacherData=array
    (
       "UniqueID",
       "Name",
       "Father",
       "Mother",
       "BirthDay",
       "Sex",
       "PRN",
       "PRN1",
       "PRN1Org",
       "Street",
       "Area",
       "City",
       "ZIP",
       "State",
       "Phone",
       "Cell",
       "Email",
       "WorkPhone",
    );

    var $ImportTeacherShowData=array
    (
       "UniqueID",
       "School",
       "Name",
       "Father",
       "Mother",
       "BirthDay",
       "Sex",
       "PRN",
       "PRN1",
       "PRN1Org",
       "Street",
       "Area",
       "City",
       "ZIP",
       "State",
       "Phone",
       "Cell",
       "Email",
       "WorkPhone",
    );


    //*
    //* function ImportTeacher, Parameter list: &$item,$line
    //*
    //* Imports one student.
    //* 
    //*

    function ImportTeacher(&$item,$line)
    {
        foreach ($item as $data => $value)
        {
            $rdata=$data;

            if (is_array($data))
            {
                foreach ($data as $rkey => $rvalue)
                {
                    $rdata=$rvalue;
                    $data=$rkey;

                    break;
                }
            }


            if ($this->ItemData[ $rdata ][ "IsDate" ])
            {
                if (preg_match('/\d/',$item[ $data ]))
                {
                    $item[ $data ]=$this->Date2Sort($item[ $data ]);
                }
                else
                {
                    //$item[ $data ]="0";
                }
            }
            elseif ($this->ItemData[ $rdata ][ "Sql" ]=="ENUM")
            {
                $value=$this->Html2Text($value);
                $value=$this->Text2Sort($value);
                $value=strtolower($value);
                $val=0;

               $text=$data.", ".$value.": ";

                $k=1;
                foreach ($this->ItemData[ $rdata ][ "Values" ] as $rvalue)
                {
                    $rvalue=$this->Html2Text($rvalue);
                    $rvalue=$this->Text2Sort($rvalue);
                    $rvalue=strtolower($rvalue);

                    if ($value==$rvalue)
                    {
                        $val=$k;
                    }

                    $k++;
                }

                if ($val>0)
                {
                    //print "OK, ".$this->ItemData[ $rdata ][ "Values" ][ $val-1 ];
                }
                else
                {
                    if (!empty($this->ItemData[ $rdata ][ "Default" ]))
                    {
                        $val=$this->ItemData[ $rdata ][ "Default" ];
                    }
                    else
                    {
                        print $text."NOT! ".join(", ",$this->ItemData[ $rdata ][ "Values" ])."<BR>";
                        print $line."<BR>";
                    }
                }

                $item[ $data ]=$val;
            }
        }

        $item[ "Profile_Teacher" ]=2;
        $item[ "School" ]=$this->ApplicationObj->School[ "ID" ];
        $ritem=$this->SelectUniqueHash
        (
            "",
            array("UniqueID" => $item[ "UniqueID" ],"School" => $item[ "School" ]),
            TRUE,
            array("ID")
        );

        if (!empty($ritem))
        {
            $item[ "ID" ]=$ritem[ "ID" ];
            $this->MySqlUpdateItem
            (
                "",
                $item,
                "ID='".$item[ "ID" ]."'"
             );
        }
        else
        {
            $msg="";

            $res=$this->Add($msg,$item);
            print $msg;
        }

        if (isset($item[ "ID" ]))
        {
            return NULL;
        }
        else
        {
            return $item;
        }
    }

    //*
    //* function ImportTeacherFile, Parameter list: $file,&$table
    //*
    //* 
    //* 
    //*

    function ImportTeacherFile($file,&$table,&$invalidtable)
    {
        $lines=$this->MyReadFile($file);

        $l=0;
        foreach ($lines as $line)
        {
            $line=preg_replace('/#.*/',"",$line);
            if (!preg_match('/\S/',$line)) { continue; }
            $line=rtrim($line,"\n");
            $comps=preg_split('/\t/',$line);

            $n=0;
            $item=array();
            foreach ($this->ImportTeacherData as $data)
            {
                $rdata=$data;
                if (is_array($data))
                {

                    foreach ($data as $key => $rvalue)
                    {
                        $rdata=$rvalue;
                        $data=$key;

                        break;
                    }
                }

                $item[ $rdata ]="";
                if (isset($comps[ $n ]))
                {
                    $item[ $rdata ]=preg_replace('/\'/','&#39;',$comps[ $n ]);
                }
                $n++;
            }

            $resitem=$this->ImportTeacher($item,$line);

            if (!$resitem && isset($item[ "ID" ]))
            {
                $row=array($this->B($l+1));
                foreach ($this->ImportTeacherShowData as $data)
                {
                    array_push
                    (
                       $row,
                       $this->MakeShowField($data,$item,TRUE)
                     );
                }
                array_push($table,$row);
                $l++;
            }
            elseif (isset($item[ "UniqueID" ]))
            {
                $row=array($this->B($item[ "UniqueID" ]));
                foreach ($this->ImportTeacherShowData as $data)
                {
                    $val="";
                    if (isset($item[ $data ])) { $val=$item[ $data ]; }

                    array_push($row,$val);
                }
                array_push($invalidtable,$row);
                $l++;
            }
        }

        return $l;
        
    }
    //*
    //* function ImportForm, Parameter list:
    //*
    //* Creates form for importing file NAME from SAdE, as does the importing.
    //* 
    //*

    function ImportForm()
    {
        print
            $this->H(2,"Importar ".$this->ItemsName).
            $this->StartForm().
            $this->H(3,"Path to Teacher File:").
            $this->Center
            (
               $this->MakeInput("Path",$this->GetPOST("Path"),50).
               $this->Button("submit","Importar").
               $this->MakeHidden("Process",1)
            ).
            $this->EndForm().
            "";

        if ($this->GetPOST("Process")==1)
        {
            $this->ItemData[ "Email" ][ "Compulsory" ]=FALSE;
            unset($this->ItemData[ "Email" ][ "Regexp" ]);


            $files=array($this->GetPOST("Path"));

            $titles=$this->B($this->ImportTeacherShowData);
            array_unshift($titles,"&nbsp;");

            sort($files);
            $text="";
            foreach ($files as $file)
            {
                $table=array(array($file,""),$titles);
                $invalidtable=$table;
                $this->ImportTeacherFile($file,$table,$invalidtable);
                print
                    $this->H(4,"Users NOT OK - ignorados").
                    $this->HtmlTable("",$invalidtable);

                $text.=
                    $this->HtmlTable("",$table);
                 //break;
            }

            print $text;

        }
    }

}

?>