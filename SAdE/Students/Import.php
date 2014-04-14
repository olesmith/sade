<?php


class StudentsImport extends Common
{
    var $ImportData=array
    (
       "UniqueID",
       "Matricula",
       "Name",
       "Status",
       "Father",
       "Mother",
       "BirthDay",
       "Sex",
       "PRN",
       "PRN1",
       "PRN1Org",
       array("Address" => "Street"),
       "Area",
       "City",
       "ZIP",
       "State",
       "Phone",
       "Cell",
       "Email",
       "WorkPhone",
       "BirthCity",
       "BirthState",
       "Nationality",
       "PRN3",
       "PRN2",
       "PRN2Zone",
       "PRN2Section",
       "PRN2State",
       "Ingresso",
       "Financiamento",
       "Mensalidade",
       "Civil",
       "StatusDate1",
       "StatusDate2",
       "MatriculaDate",
       "SegGrau",
       "SegGrauCidade",
       "SegGrauEstado",
       "SegGrauConclusao",
       "Vestibular",
       "ConclusionDate",
       "ConclusionDate1",
       "ExpeditionDate",
       "ACurso",
       "Contratado",
       "Convenio",
       "BirthCertNo",
       "BirthCertPage",
       "BirthCertBook",
       "MotherProfession",
       "FatherProfession",
       "Race",
       "Map",
       "BirthCertDate",
       "BirthCertCity",
       "BirthCertState",
       "GovernmentProgram",
       "SchoolTransportation",
       "Disabled"
    );

    var $ImportShowData=array
    (
       "ID",
       "UniqueID",
       "Name",
       "Matricula",
       "MatriculaDate",
       "Status",
       "StatusDate1",
       "StatusDate2",
       "Street",
       "Area",
       "City",
       "ZIP",
       "State",
   );

    //*
    //* function ImportStudent, Parameter list: &$item,$file
    //*
    //* Imnports one student.
    //* 
    //*

    function ImportStudent(&$item,$file)
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
                    }
                }

                $item[ $data ]=$val;
            }
        }

        $item[ "School" ]=$this->ApplicationObj->School[ "ID" ];
        $item[ "OrigFile" ]=$file;
        $ritem=$this->SelectUniqueHash
        (
            "",
            array("UniqueID" => $item[ "UniqueID" ]),
            TRUE,
            array("ID")
        );

        if (!empty($ritem))
        {
            $item[ "ID" ]=$ritem[ "ID" ];

            $item=$this->UpdateItem($item);
        }
        else
        {
            $msg="";

            $res=$this->Add($msg,$item);
            print $msg;
        }

        if (isset($item[ "ID" ]))
        {
            $this->MySqlSetItemValues("",array("School","OrigFile"),$item);
            return NULL;
        }
        else
        {
            return $item;
        }
    }

    //*
    //* function ImportStudentFile, Parameter list: $file,&$table
    //*
    //* 
    //* 
    //*

    function ImportStudentFile($file,&$table,&$invalidtable)
    {
        $lines=$this->MyReadFile($file);
        array_push
        (
           $table,
           array
           (
              $this->MultiCell
              (
                 $file.": ".count($lines)." lines",
                 count($this->ImportShowData)+2
              ),
              ""
           )
        );

        $l=0;
        foreach ($lines as $line)
        {
            if (!preg_match('/\S/',$line)) { continue; }
            $line=rtrim($line,"\n");
            $comps=preg_split('/\t/',$line);

            $n=0;
            $item=array();
            foreach ($this->ImportData as $data)
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

            $resitem=$this->ImportStudent($item,$file);

            if (!$resitem && isset($item[ "ID" ]))
            {
                $row=array($this->B($l+1));
                foreach ($this->ImportShowData as $data)
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
                foreach ($this->ImportShowData as $data)
                {
                    $val="";
                    if (isset($item[ $data ])) { $val=$item[ $data ]; }

                    array_push($row,$val);
                }
                array_push($table,$row);
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
            $this->H(3,"Path to Student Files:").
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
            $files=array($this->GetPOST("Path"));

            if (!preg_match('/\.txt$/',$files[0]))
            {
                $files=$this->DirFiles($this->GetPOST("Path"),'\d\d\d\d\.\d\d\.\d\d\.txt$');
            }

            $titles=$this->B($this->ImportShowData);
            array_unshift($titles,"&nbsp;");

            sort($files);
            $text="";
            foreach ($files as $file)
            {
                $table=array($titles);

                $invalidtable=$table;
                $this->ImportStudentFile($file,$table,$invalidtable);

                if (count($invalidtable)>1)
                {
                    $text.=
                        $this->H(4,"Alunos NOT OK - ignorados").
                        $this->HtmlTable("",$invalidtable)."<BR>";
                }

                $text.=
                    $this->HtmlTable("",$table);
                 //break;
            }

            print $text;

        }
    }

}

?>