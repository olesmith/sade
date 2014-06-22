<?php



class SchoolsLatexSettings extends Places
{
    //*
    //* Variables of Schools LatexSettings class:
    //*

    var $UpdateAction="Save";
    var $MaxColumns=5;
    var $MaxRows=3;
    var $TitleData=array
    (
       "Name" => "Texto",
       "Title" => "Texto",
       "Size" => "10",
       "Sql" => "VARCHAR(255)",
       "Search" => FALSE,
       "Compulsory" => FALSE,

       "Public"    => 0,
       "Person"    => 0,
       "Admin"     => 2,
       "Clerk"     => 2,
       "Teacher"   => 1,
       "Secretary" => 2,
       "Coordinator" => 1,
    );
    var $VarData=array
    (
       "Name" => "Campo",
       "Title" => "Campo",
       "Size" => "15",
       "Sql" => "ENUM",
       "Search" => FALSE,
       "Compulsory" => FALSE,
       "NoSelectSort" => TRUE,

       "Public"    => 0,
       "Person"    => 0,
       "Admin"     => 2,
       "Clerk"     => 2,
       "Teacher"   => 1,
       "Secretary" => 2,
       "Coordinator" => 1,
   );

    var $VarTypes=array
    (
       "School","Class","Teacher","Disc","Month","Period",
       "Grade","GradePeriod","Teacher1","Teacher2",
       "Unit","Shift",
       "Year","Semester",
    );
    var $VarNames=array
    (
       "Escola","Turma","Professor(a)","Disciplina","Mes","Ano/Sem",
       "Grade","Periodo","Prof. Apoio","Prof. Recursos",
       "Entidade","Turno",
       "Ano","Semester",
    );



    var $PrintTable=array
    (
       array
       (
          array
          (
             "Turma:",
             "Class"
          ),
          array
          (
             "Turno:",
             "Shift"
          ),
          array
          (
             "Professor(a):",
             "Teacher"
          ),
          array(),
          array(),
       ),
       array
       (
          array
          (
             "Disciplina:",
             "Disc"
          ),
          array
          (
             "Mês:",
             "Month"
          ),
          array
          (
             "Período:",
             "Period"
          ),
          array(),
          array(),
       ),
       array
       (
          array(),
          array(),
          array(),
          array(),
          array(),
       ),
    );

    
    //*
    //* function AddPrintTableData, Parameter list:
    //*
    //* Add PrintTable data to $this->ItemData.
    //*

    function AddPrintTableData()
    {
        $rvarids=array();
        for ($n=0;$n<count($this->VarTypes);$n++)
        {
            $rvarids[ $this->VarTypes[ $n ] ]=$n+1; //select no +1
        }

        for ($n=1;$n<=$this->MaxColumns;$n++)
        {
            for ($m=1;$m<=$this->MaxRows;$m++)
            {
                $default=FALSE;
                if (
                      !empty($this->PrintTable[ $m-1 ])
                      &&
                      !empty($this->PrintTable[ $m-1 ][ $n-1 ])
                   )
                {
                    $default=$this->PrintTable[ $m-1 ][ $n-1 ];
                }

                $data="PrintTable_".$n."_".$m;

                $key=$data."_Name";
                $this->ItemData[ $key ]=$this->TitleData;
                $this->GetDefaultItemData($key,$this->ItemData[ $key ]);


                if ($default)
                {
                    $this->ItemData[ $key ][ "Default" ]=$default[0];
                }
 
                $key=$data."_Var";
                $this->ItemData[ $key ]=$this->VarData;
                $this->ItemData[ $key ][ "Values" ]=$this->VarNames;
                $this->GetDefaultItemData($key,$this->ItemData[ $key ]);
 
                if ($default)
                {
                    $varno=$rvarids[ $default[1] ];
                    $this->ItemData[ $key ][ "Default" ]=$varno;

               }
            }
        }
     }

    //*
    //* function UpdatePrintHeadTableForm, Parameter list: &$scool
    //*
    //* Updates data from print spec form to $scool.
    //*

    function UpdatePrintHeadTableForm(&$school)
    {
        if ($this->GetPOST($this->UpdateAction)==1)
        {
            $updatedata=array();
            for ($n=1;$n<=$this->MaxColumns;$n++)
            {
                for ($m=1;$m<=$this->MaxRows;$m++)
                {
                    $key="PrintTable_".$n."_".$m;

                    foreach (array("Name","Var") as $type)
                    {
                        $data=$key."_".$type;
                        $value=$this->GetPOST($data);

                        if ($school[ $data ]!=$value)
                        {
                            $school[ $data ]=$value;
                            array_push($updatedata,$data);
                        }
                    }
                }
            }

            if (count($updatedata)>0)
            {
                $this->MySqlSetItemValues("",$updatedata,$school);
            }
        }
    }


    //*
    //* function PrintHeadTableForm, Parameter list: $edit=0,$school=array()
    //*
    //* Prints table for setting Daylies (and others) leading infotable.
    //*

    function PrintHeadTableForm($edit=0,$school=array())
    {
        if (empty($school)) { $school=$this->ApplicationObj->School; }

        $this->AddPrintTableData();

        $titles=array("Linha");
        for ($n=1;$n<=$this->MaxColumns;$n++)
        {
            array_push($titles,"Texto","Campo");
        }        

        $table=array($this->B($titles));

        for ($m=1;$m<=$this->MaxRows;$m++)
        {
            $row=array($m);
            for ($n=1;$n<=$this->MaxColumns;$n++)
            {        
                $key="PrintTable_".$n."_".$m;
                array_push
                (
                   $row,
                   $this->MakeField
                   (
                      $edit,
                      $school,
                      $key."_Name",
                      FALSE,
                      $n
                   ),
                   $this->MakeField
                   (
                      $edit,
                      $school,
                      $key."_Var",
                      FALSE,
                      $n
                   )
                );

                    
             }

            array_push($table,$row);
        }

        return 
            $this->H(3,"Cabeçalho Impresso").
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center',"BORDER" => '1'),
               array(),
               array(),
               FALSE,
               FALSE
            ).
            "";
    }

    //*
    //* function HandleHeadTableForm, Parameter list:
    //*
    //* Handles upodates of School Latex Header Table.
    //*

    function HandleHeadTableForm()
    {
        if ($this->GetPOST($this->UpdateAction)==1)
        {
            $this->UpdatePrintHeadTableForm($this->ApplicationObj->School);
        }

        $edit=0;
        if (preg_match('/(Admin|Secretary)/',$this->ApplicationObj->Profile))
        {
            $edit=1;
        }

        print
            $this->H(1,"Tabela Cabeçalho dos Impressos Gerados pelo Sistema").
            $this->StartForm().
            $this->PrintHeadTableForm($edit,$this->ApplicationObj->School).
            $this->MakeHidden($this->UpdateAction,1).
            $this->Buttons().
            $this->EndForm().
            "";
     }

    //*
    //* function GenerateLatexPageTable, Parameter list: $school
    //*
    //* Prints table for setting Daylies (and others) leading infotable.
    //* Returns table as matrix.
    //*

    function GenerateLatexPageTable($school,$class,$disc,$month="")
    {
        $table=array();
        for ($m=1;$m<=$this->MaxRows;$m++)
        {
            $table[ $m-1 ]=array();
            for ($n=1;$n<=$this->MaxColumns;$n++)
            {        
                $key="PrintTable_".$n."_".$m;

                $varno=$school[ $key."_Var" ];

                $titlecell="";
                $titlevar="";
                if ($varno>0)
                {
                    $title=$school[ $key."_Name" ];
                    $var=$this->VarTypes[ $varno-1 ];

                    if (empty($month) && $var=="Month") { continue; }

                    $titlecell="\\textbf{".$title."}";
                    $titlevar=$this->LatexPageTableCell($var,$school,$class,$disc,$month);
                }

                if (empty($titlevar)) { $titlevar="-"; }
                $table[ $m-1 ][ 2*($n-1) ]=$titlecell;
                $table[ $m-1 ][ 2*$n-1   ]=$titlevar;
            }
        }

        $rtable=array();
        $maxlen=0;
        foreach ($table as $row)
        {
            if (preg_grep('/\S/',$row))
            {
                $row=preg_grep('/\S/',$row);
                array_push($rtable,$row);

                $maxlen=$this->Max($maxlen,count($row));
            }
        }

        foreach (array_keys($rtable) as $id)
        {
            $len=count($rtable[ $id ]);
            if ($len>$maxlen)
            {
                array_splice($rtable[ $id ],$maxlen);
            }
            elseif ($len<$maxlen)
            {
                for ($k=$len;$k<$maxlen;$k++)
                {
                    array_push($rtable[ $id ],"");
                }
            }
        } 

        return $rtable;
    }

    //*
    //* function LatexPageTableCell, Parameter list: $var,$school,$class,$disc,$month=""
    //*
    //* Prints table for setting Daylies (and others) leading infotable.
    //*

    function LatexPageTableCell($var,$school,$class,$disc,$month="")
    {
        $value="";
        if ($var=="School")
        {
             $value=$school[ "Name" ];
        }
        elseif ($var=="Class")
        {
            $value=$class[ "Name" ];
        }
        elseif ($var=="Shift")
        {
            $value=$this->ApplicationObj->ClassesObject->GetEnumValue("Shift",$class);
        }
        elseif ($var=="Year")
        {
            $value=$class[ "Year" ];
        }
        elseif ($var=="Semester")
        {
            $value=$class[ "Semester" ];
        }
        elseif (preg_match('/^Teacher[12]?/',$var))
        {
            $value="";
            if ($disc[ $var ]>0)
            {
                $value=$this->ApplicationObj->UsersObject->MySqlItemValue
                (
                   "",
                   "ID",$disc[ $var ],
                   "Name"
                );
            }
        }
        elseif ($var=="Disc")
        {
            $value=$disc[ "Name" ];
        }
        elseif ($var=="Month")
        {
            $value=$month;
            $value=preg_replace('/\d\d\d\d/',"",$value);
            $value=preg_replace('/\//',"",$value);

            if (preg_match('/d\d?$/',$month) && $month>0)
            {
                $value=$this->Months[ $value-1 ];
            }
        }
        elseif ($var=="Period")
        {
            $value=$this->ApplicationObj->Period[ "Name" ];
        }
        elseif ($var=="Grade")
        {
            $value=$this->ApplicationObj->Grade[ "Name" ];
        }
        elseif ($var=="GradePeriod")
        {
            $value=$this->ApplicationObj->GradePeriod[ "Name" ];
        }
        elseif ($var=="Unit")
        {
            $value=$this->ApplicationObj->Unit[ "Name" ];
        }
 
        return $value;
    }
}

?>