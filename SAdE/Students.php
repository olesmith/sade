<?php

include_once("Students/Import.php");
include_once("Students/Prints.php");
include_once("Students/Add.php");
include_once("Students/Matriculate.php");
include_once("Students/Remanage.php");
include_once("Students/History.php");
include_once("Students/Access.php");



class Students extends StudentsAccess
{

    //*
    //* Variables of Students class:
    //*

    var $StatusesImport=array
    (
       "Ativo","Inativo",
       "Transferido","Cancelado","Trancado",
       "Licenca","Desistente","NaoMatriculado"
    );
    var $Statuses=array
    (
       "Ativo","Inativo",
       "Transferido","Cancelado","Trancado",
       "Licença","Desistente","Não Matriculado",
       "Terminado"
    );

    //*
    //*
    //* Constructor.
    //*

    function Students($args=array())
    {
        $this->Hash2Object($args);
        $this->Sort=array("Name");
        $this->AlwaysReadData=array("Name","Photo","MatriculaDate","StatusDate1");
        array_unshift($this->ItemDataFiles,"../People/Data.php");
        $this->AddReloadAction="Matriculate";
    }

    //*
    //* function UpdateDBFields, Parameter list: $table="",$datas=array(),$datadefs=array(),$maycreate=TRUE
    //*
    //* Overrides UpdateDBFields, checks if school id is valid,
    //* in case it is, calls parent. 
    //*

    function UpdateDBFields($table="",$datas=array(),$datadefs=array(),$maycreate=TRUE)
    {
        $table=$this->SqlTableName($table);
        if (preg_match('/^(\d+)_/',$table,$matches))
        {
            $school=$matches[1];
            if ($this->IsSchool($school))
            {
                parent::UpdateDBFields($table,$datas,$datadefs,$maycreate);
            }
        }
    }

    //*
    //* function GetUploadPath, Parameter list:
    //*
    //* Overrides MySql2::GetUploadPath. Returns:
    //*
    //* Uploads/#Unit/#School/Students
    //*

    function GetUploadPath($args=array())
    {
        $comps=array
        (
           "Uploads",
           $this->ApplicationObj->Unit[ "ID" ],
           $this->ApplicationObj->School[ "ID" ],
           "Students"
        );

        $path=join("/",$comps);

        $this->CreateDirAllPaths($path);
        touch($path."/index.php");

        return $path;

    }

    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        $this->SqlTable= 
            intval($this->GetGET("School"))."_".
            $this->SqlTable;
        $this->ItemData[ "Status" ][ "GETSearchVarName" ]="Status";

        $action=$this->DetectAction();
        if ($action=="Import")
        {
            $this->ItemData[ "Status" ][ "Values" ]=$this->StatusesImport;
        }
        else
        {
             $this->ItemData[ "Status" ][ "Values" ]=$this->Statuses;
       }

        $this->ItemData[ "Status" ][ "Default" ]=1;
        $this->ItemData[ "Status" ][ "SearchDefault" ]=1;
        $this->ItemData[ "Status" ][ "TriggerFunction" ]="UpdateStudentStatus";

        foreach (
                   array
                   (
                      "State","BirthState",
                      "PRN1State","PRN2State","PRN3State",
                      "BirthCertState","FatherState","MotherState"
                   ) as $data
                )
        {
            $this->ItemData[ $data ][ "Values" ]=$this->ApplicationObj->States_Short;
            $this->ItemData[ $data ][ "Default" ]=$this->ApplicationObj->Unit[ "State" ];
        }

        foreach (array("City","BirthCity","BirthCertCity") as $data)
        {
            $this->ItemData[ $data ][ "Default" ]=$this->ApplicationObj->Unit[ "City" ];
        }
        foreach (array("ZIP") as $data)
        {
            $this->ItemData[ $data ][ "Default" ]=$this->ApplicationObj->Unit[ "ZIP" ];
        }
    }

    //*
    //* function SqlTableName, Parameter list:
    //*
    //* Overrides SqlTableName.
    //*

    function SqlTableName($table="")
    {
        if (!empty($table) && !preg_match('/#/',$table)) { return $table; }

        return $this->ApplicationObj->GetSqlTable("Students",TRUE,FALSE,FALSE);
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->MatriculasObject->UpdateTableStructure();

        $this->ApplicationObj->ReadSchool();
        $this->Actions[ "Remove" ][ "AccessMethod" ]="MayRemove";

        $hash=array( "School" => $this->ApplicationObj->School[ "ID" ]);
        if (intval($this->GetGET("Class"))>0)
        {
            
        }
        
        $this->SqlTable= 
            intval($this->GetGET("School"))."_".
            $this->SqlTable;


        $unsearches=array("PRN","PIS","PRN1","SUS");
        if ($this->ApplicationObj->School[ "ID" ]>0)
        {
            $this->SqlWhere="School='".$this->ApplicationObj->School[ "ID" ]."'";
            array_push($unsearches,"Department");
        }

        foreach ($unsearches as $data)
        {
            $this->ItemData[ $data ][ "Search" ]=FALSE;
        }
    }

    //*
    //* function Student2ClassStudent, Parameter list: $item,$classid=0
    //*
    //* Runs right after module has finished initializing.
    //*

    function Student2ClassStudent($item,$classid=0)
    {
        if ($classid==0 && !empty($this->ApplicationObj->Class))
        {
            $classid=$this->ApplicationObj->Class[ "ID" ];
        }

        if ($classid==0) { return 0; }

        $classstudent=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
        (
           "",
           array
           (
              "Class"   => $classid,
              "Student" => $item[ "ID" ],
           ),
           TRUE,
           array("ID")
        );

        return $classstudent[ "ID" ];
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if ($module=="Classes" && !empty($this->ApplicationObj->Class))
        {
            $item[ "ClassStudent" ]=$this->Student2ClassStudent($item);
        }

        if (isset($item[ "Status" ]))
        {
            $updatedatas=array();
            if ($item[ "Status" ]==1 && $item[ "StatusDate1" ]!=0)
            {
                $item[ "StatusDate1" ]=0;
                $item[ "StatusDate2" ]=0;
                array_push($updatedatas,"StatusDate1","StatusDate2");
            }
            elseif ($item[ "Status" ]!=1 && $item[ "StatusDate1" ]==0)
            {
                $item[ "StatusDate1" ]=$this->TimeStamp2DateSort();;
                array_push($updatedatas,"StatusDate1");
            }

            if (count($updatedatas)>0)
            {
                $this->MySqlSetItemValues("",$updatedatas,$item);
            }
        }


        $this->ApplicationObj->MatriculasObject->UpdateStudentMatricula($item);

        if ($module!="Students")
        {
            return $item;
        }

        $udatas=$this->TakeUndefinedListOfKeys
        (
           $item,
           $this->ApplicationObj->Unit,
           array
           (
              array
              (
                 "Key" => "State",
                 "Keys" => array("State","BirthState","PRN2State","BirthCertState"),
              ),
              array
              (
                 "Key" => "City",
                 "Keys" => array("City","BirthCity","BirthCertCity"),
              ),
              array
              (
                 "Key" => "",
                 "Keys" => array("ZIP"),
              ),
           ),
           TRUE
         );

        return $item;
    }

    //*
    //* function UpdateStudentStatus, Parameter list: $item,$data,$newvalue
    //*
    //* Trugger function, when altering status, alter StatusDate. 
    //*

    function UpdateStudentStatus ($item,$data,$newvalue)
    {
        if ($newvalue==1)
        {
            $item[ "StatusDate1" ]=0;
            //$item[ "StatusDate1_ReadOnly" ]=TRUE; //prevent post update!
            $item[ "StatusDate2" ]=0;
            //$item[ "StatusDate2_ReadOnly" ]=TRUE;
            $this->MySqlSetItemValues("",array("StatusDate1","StatusDate2"),$item);
        }
        else
        {
            $item[ "StatusDate1" ]=$this->TimeStamp2DateSort();
            //$item[ "StatusDate1_ReadOnly" ]=TRUE; //prevent post update!
            $this->MySqlSetItemValues("",array("StatusDate1"),$item);
        }

        $item[ "Status" ]=$newvalue;

        return $item;
    }

    //*
    //* function InitPrint, Parameter list: $item
    //*
    //* Pre processor for printing. Reads class, if possible.
    //*

    function  InitPrint($item)
    {
        $this->ApplicationObj->ReadClass(FALSE);

        $item[ "SchoolName" ]=$this->ApplicationObj->School[ "Name" ];
        $item[ "SchoolAddress" ]=
            $this->ApplicationObj->School[ "Street" ].
            " ".
            $this->ApplicationObj->School[ "StreetNumber" ].
            " ".
            $this->ApplicationObj->School[ "StreetCompletion" ].
            ", ".
            $this->ApplicationObj->School[ "City" ].
            ", ".
            $this->ApplicationObj->School[ "Area" ].
            "-".
            $this->ApplicationObj->School[ "State" ].
            ", CEP: ".
            $this->ApplicationObj->School[ "ZIP" ].
            "";

        $class=$this->ApplicationObj->ClassesObject->ApplyAllEnums($this->ApplicationObj->Class);

        $item[ "GradePeriod" ]=$this->ApplicationObj->GradePeriod[ "Name" ];
        $item[ "ClassPeriod" ]=$this->ApplicationObj->Period[ "Name" ];
        $item[ "ClassYear" ]=$this->ApplicationObj->Period[ "Year" ];
        $item[ "ClassShift" ]=$class[ "Shift" ];
        $item[ "TeacherName" ]=$class[ "Teacher" ];

        $phonesdata=array("Phone","Cell","WorkPhone");
        $phones=array();
        foreach ($phonesdata as $data)
        {
            if (preg_match('/\d/',$item[ $data ]))
            {
                array_push($phones,$item[ $data ]);
            }
        }

        $item[ "Phones" ]=join(", ",$phones);

        $prevperiod=$this->ApplicationObj->PeriodsObject->PreviousPeriod($this->ApplicationObj->Class[ "Period" ]);

        $classstudents=$this->ReadStudentClasses($item[ "ID" ]);

        $item[ "PreviousPeriod" ]="-";
        $item[ "StudiedHere" ]="N\~ao";
        $item[ "Repetition" ]="N\~ao";

        $gradeper=$this->ApplicationObj->GradePeriodsObject->SelectUniqueHash
        (
           "",
           array("ID" => $this->ApplicationObj->Class[ "GradePeriod" ])
        );

        $prevclass=NULL;
        foreach ($classstudents as $classstudent)
        {
            if ($classstudent[ "Period" ]==$prevperiod[ "ID" ])
            {
                $prevgradeper=$this->ApplicationObj->GradePeriodsObject->SelectUniqueHash
                (
                   "",
                   array("ID" => $classstudent[ "GradePeriod" ])
                );

                $item[ "PreviousPeriod" ]=$prevgradeper[ "Name" ];
                    

                $item[ "StudiedHere" ]="Sim";
                //$prevclass=$class;

                if ($prevgradeper[ "SortOrder" ]==$gradeper[ "SortOrder" ])
                {
                    $item[ "Repetition" ]="Sim";
                }

                
            }
        }

        return $item;
    }


    //*
    //* function ReadStudent, Parameter list: $studentid=0,$die=TRUE
    //*
    //* Reads student, id being GET Student.
    //* 
    //*

    function ReadStudent($studentid=0,$die=FALSE)
    {
        if (empty($studentid))
        {
            $studentid=$this->GetGET("Student");
        }

        if (empty($studentid))
        {
            $studentid=$this->GetGET("ID");
        }

        if (!empty($studentid) && preg_match('/^\d+$/',$studentid) && $studentid>0)
        {
            $this->ApplicationObj->Student=$this->SelectUniqueHash
            (
                $this->ApplicationObj->GetSqlTable
                (
                   "ClassStudents",
                   TRUE,
                   TRUE,
                   FALSE,
                   $this->ApplicationObj->Class
                ),
                array("ID" => $studentid)
            );

            $this->ApplicationObj->Student[ "StudentHash" ]=$this->SelectUniqueHash
            (
                $this->ApplicationObj->GetSqlTable("Students",TRUE,FALSE,FALSE,$this->ApplicationObj->Class),
                array("ID" => $this->ApplicationObj->Student[ "Student" ])
            );

            $this->ApplicationObj->Student[ "StudentHash" ]=$this->ApplyAllEnums($this->ApplicationObj->Student[ "StudentHash" ]);
        }
        elseif ($die)
        {
            die("Invalid Student: '".$studentid."'");
        }
    }

    //*
    //* function ReadClassStudents, Parameter list:
    //*
    //* Reads student, id being GET Student.
    //* 
    //*

    function ReadClassStudents()
    {
        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($this->ApplicationObj->Class[ "ID" ]);
    }


    //*
    //* function ReadStudentEntries, Parameter list: $studentid 
    //*
    //* Retrieves student entries.
    //* 
    //*

    function ReadStudentClasses($studentid)
    {  
        $classtables=$this->DBTables
        (
           $this->ApplicationObj->School[ "ID" ].
           "_%_ClassStudents"
        );

        $classstudents=array();
        foreach ($classtables as $classtable)
        {
            $classstudent=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
            (
               $classtable,
               array("Student" => $studentid),
               TRUE,
               array("ID","Class","Grade","Grade","GradePeriod","GradePeriod")
            );

            if (!empty($classstudent))
            {
                //Find class period
                $periodid=$this->ApplicationObj->ClassesObject->MySqlItemValue
                (
                   "",
                   "ID",$classstudent[ "Class" ],
                   "Period"
                );

                //Read Period
                $period=$this->ApplicationObj->LocatePeriod($periodid);
                $periodkey=$this->ApplicationObj->GetPeriodName($period);

                //Store
                $classstudent[ "Period" ]=$periodid;
                $classstudents[ $periodkey ]=$classstudent;
            }
        }

        return $classstudents;
    }


    //*
    //* Creates student info table.
    //*

    function InfoTable($student=array())
    {
        if (empty($student)) { $student=$this->ApplicationObj->Student; }

        $table=array();
        array_push
        (
           $table,
           array
           (
              $this->B("Aluno(a):"),
              $this->ApplicationObj->StudentsObject->MakeShowField
              (
                 "Name",
                 $student[ "StudentHash" ]
              ),
              $this->B("Turma:"),
              $this->ApplicationObj->ClassesObject->ClassName($this->ApplicationObj->Class),
           ),
           array
           (
              $this->B("Pai:"),
              $this->ApplicationObj->StudentsObject->MakeShowField
              (
                 "Father",
                 $student[ "StudentHash" ]
              ),
              $this->B("Mãe:"),
              $this->ApplicationObj->StudentsObject->MakeShowField
              (
                 "Mother",
                 $student[ "StudentHash" ]
              ),
           ),
           array
           (
              $this->B("Matricula:"),
              $this->ApplicationObj->StudentsObject->MakeShowField
              (
                 "UniqueID",
                 $student[ "StudentHash" ]
              ),
              $this->B("Matricula Data:"),
              $this->SortTime2Date($student[ "StudentHash" ][ "MatriculaDate" ]),
           ),
           array
           (
              $this->B("Status:"),
              $this->ApplicationObj->StudentsObject->MakeShowField
              (
                 "Status",
                 $student[ "StudentHash" ]
              ),
              $this->B("Status Data:"),
              $this->ApplicationObj->StudentsObject->MakeShowField
              (
                 "StatusDate1",
                 $student[ "StudentHash" ]
              ),
           ),
           array
           (
              $this->B("Nascimento:"),
              $this->ApplicationObj->StudentsObject->MakeShowField
              (
                 "BirthDay",
                 $student[ "StudentHash" ]
              ),
              $this->B("Naturalidade:"),
              $this->ApplicationObj->StudentsObject->MakeShowField
              (
                 "City",
                 $student[ "StudentHash" ]
               )."-".
              $this->ApplicationObj->StudentsObject->MakeShowField
              (
                 "State",
                 $student[ "StudentHash" ]
              ),
           )
        );

        if ($this->LatexMode)
        {
            return
                $this->LatexTable("",$table);
        }

        return $this->FrameIt
        (
           $this->Html_Table
           (
              "",
              $table,
              array(),
              array(),
              array(),
              FALSE,
              FALSE
           )
        ).
        "<P>";
    }

    //*
    //* function StudentSelectForm, Parameter list: $action=""
    //*
    //* Creates student select form.
    //*

    function StudentSelectForm($action="")
    {
        if (empty($this->ApplicationObj->Student)) { return ""; }

        //if ($this->GetGET("Student")=="") { return ""; }
        $this->ReadClassStudents();

        $ids=array();
        $names=array();
        $titles=array();

        $current=$this->GetGET("Student");
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            array_push($ids,$student[ "ID" ]);
            array_push($names,$student[ "StudentHash" ][ "Name" ]);
            array_push($titles,$student[ "StudentHash" ][ "Name" ]);
        }

        $args=$this->Query2Hash();
        $args=$this->Hidden2Hash($args);

        $args[ "ModuleName" ]="Classes";
        if (!empty($action)) { $args[ "Action" ]=$action; }

        $args[ "Student" ]="";
        return
            $this->Center
            (
               $this->StartForm("?".$this->Hash2Query($args)).
               $this->B("Selecionar Aluno(a): ").
               $this->MakeSelectfield
               (
                  "StudentID",
                  $ids,
                  $names,
                  $current,
                  array(),
                  $titles
               ).
               $this->Button("submit","GO")."\n".
               $this->EndForm()
            ).
            "";
    }

    //*
    //* function StudentMenu, Parameter list: $student=array(),$absences=TRUE,$marks=TRUE
    //*
    //* Creates horisontal disc menu, links to the individual actions.
    //*

    function StudentMenu($student=array(),$absences=TRUE,$marks=TRUE)
    {
        if (empty($student)) { $student=$this->ApplicationObj->Student; }

        return $this->ApplicationObj->ClassesObject->MakeActionMenu
        (
            array("StudentMarks","StudentAbsences","StudentTotals","StudentPrint","StudentsPrint","StudentsPrintSimple"),
            "ptablemenu",
            $student[ "StudentHash" ][ "ID" ]
        );
    }

    //*
    //* function StudentClass, Parameter list: $student=array()
    //*
    //* Creates horisontal disc menu, links to the individual actions.
    //*

    function StudentClass($data,$student=array())
    {
        if (empty($student)) { $student=$this->ApplicationObj->Student; }

        if (isset($student[ "StudentHash" ]))
        {
            $student=$student[ "StudentHash" ];
        }

        $classregs=$this->ApplicationObj->ClassStudentsObject->SelectHashesFromTable
        (
           $this->ApplicationObj->GetSqlTable("ClassStudents",TRUE,TRUE),
           array("Student" => $student[ "ID" ]),
           array("Class")
        );

        $values=array();
        foreach ($classregs as $classreg)
        {
            $class=$this->ApplicationObj->Classes($classreg[ "Class" ]);
            array_push
            (
               $values,
               $this->Href
               (
                  "?".
                  $this->Hash2Query
                  (
                     array
                     (
                        "Unit" => $this->ApplicationObj->Unit[ "ID" ],
                        "School" => $this->ApplicationObj->School[ "ID" ],
                        "ModuleName" => "Classes",
                        "Action" => "Students",
                        "Period" => $this->ApplicationObj->Period[ "ID" ],
                        "Class" => $classreg[ "Class" ],
                      ) 
                  ),
                  $class[ "Name" ],
                  "Acessar Turma: ".$class[ "Name" ]
               )
            );
        }

        return join($this->BR(),$values);
    }
}

?>