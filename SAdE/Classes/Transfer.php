<?php

class ClassesTransfer extends ClassesHandle
{
    //*
    //* function DoTransfer, Parameter list: 
    //*
    //* Does the transfer of students to new class.
    //*

    function DoTransfer($per)
    {
        $newclass=$this->GetPOST("NewClass");
        if (preg_match('/^\d+$/',$newclass) && $newclass>0)
        {
            //Test if new class exists
            $newclasshash=$this->SelectUniqueHash
            (
               $this->ApplicationObj->School[ "ID" ]."_".$per."_Classes",
               array("ID" => $newclass),
               TRUE
             );

            if (!empty($newclasshash))
            {
                foreach ($this->ApplicationObj->Students as $student)
                {
                    $transfer=$this->GetPOST("Transfer_".$student[ "ID" ]);
                    if ($transfer==1)
                    {
                        $classstudent=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
                        (
                           $this->ApplicationObj->School[ "ID" ]."_".$per."_ClassStudents",
                           array("Student" => $student[ "StudentHash" ][ "ID" ]),
                           TRUE,
                           array("ID","Class")
                        );

                        if (!empty($classstudent))
                        {
                            //Already in other class, move
                            $this->ApplicationObj->ClassStudentsObject->MySqlSetItemValue
                            (
                               $this->ApplicationObj->School[ "ID" ]."_".$per."_ClassStudents",
                               "ID",$classstudent[ "ID" ],
                               "Class",$newclass 
                            );
                        }
                        else
                        {
                            $newitem=array
                            (
                               "Student" => $student[ "StudentHash" ][ "ID" ],
                               "UniqueID" => $student[ "StudentHash" ][ "UniqueID" ],
                               "School" => $this->ApplicationObj->School[ "ID" ],
                               "Class" => $newclass,
                               "Grade" => $newclasshash[ "Grade" ],
                               "GradePeriod" => $newclasshash[ "GradePeriod" ],
                            );

                            //Not in other class, add
                            $this->ApplicationObj->ClassStudentsObject->MySqlInsertItem
                            (
                               $this->ApplicationObj->School[ "ID" ]."_".$per."_ClassStudents",
                               $newitem
                            );

                            array_push
                            (
                               $this->HtmlStatus,
                               "Aluno(a) ".$student[ "StudentHash" ][ "Name" ].",".
                               $this->BR().
                               "Transferido para Turma ".
                               $this->ApplicationObj->ClassesObject->ClassName($newclasshash)
                            );
                        }

                    }
                }
            }
        }
        else
        {
            print $this->H(2,"Turma Destinário não Escolhido...");
        }
    }


    //*
    //* function HandleTransfer, Parameter list: $title=""
    //*
    //* Handles action Transfer.
    //*

    function HandleTransfer($title="")
    {
        $nextper=$this->ApplicationObj->PeriodsObject->SelectUniqueHash
        (
           "",
           array("ID" => $this->ApplicationObj->Period[ "NextPeriod" ])
        );

        $nextgradeper=$this->ApplicationObj->GradePeriodsObject->ClassNextGradePeriod($this->ApplicationObj->Class);

        $this->ItemHashes=array();
        $classes=array();
        if (!empty($nextgradeper))
        {
            $classes=$this->SelectHashesFromTable
            (
               "",
               array
               (
                  "Period" =>$this->ApplicationObj->Period[ "NextPeriod" ],
                  "GradePeriod" => $nextgradeper[ "ID" ],
               ),
               array("ID","Name","Year","Period","Grade","GradePeriod")
            );
       }



        $rclasses=array();
        foreach ($classes as $id => $class)
        {
            $class[ "GradePeriod_Name" ]=$this->ApplicationObj->GradePeriodsObject->MySqlItemValue
            (
               "",
               "ID",
               $class[ "GradePeriod" ],
               "Name"
            );

            $class[ "Grade_Name" ]=$this->ApplicationObj->GradeObject->MySqlItemValue
            (
               "",
               "ID",
               $class[ "Grade" ],
               "Name"
            );
            $rclasses[ $class[ "ID" ] ]=$class;
        }


        $classnames=array();

        $ids=array(0);
        $names=array("");
        foreach ($rclasses as $id => $class)
        {
            array_push($ids,$class[ "ID" ]);
            array_push
            (
               $names,
               $class[ "GradePeriod_Name" ].", ".
               $class[ "Name" ].
               " (".
               $class[ "Year" ]."/".
               $class[ "Grade_Name" ].
               ")"
            );

            $classnames[ $class[ "ID" ] ]=$class[ "GradePeriod_Name" ].", ".$class[ "Name" ];
        }

        if (count($rclasses)==0)
        {
            print 
                $this->H(2,"Nenhuma Turma Receptor Adequado").
                $this->H(3,"Talvez seja necessário Adicionar uma Turma ao Período: ").
                $this->H(4,$nextper[ "Name" ]).
                $this->H(5,"Para esse Fim, Utilize o Link: 'Adicionar Turma'!").
                "";
            exit();
        }

        $this->ApplicationObj->ClassStudentsObject->SqlTable=
            $this->SchoolAndPeriod2SqlTable($this->ApplicationObj->Class,"ClassStudents");

        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($this->ApplicationObj->Class[ "ID" ]);

        $per=$nextper[ "Year" ];
        if ($nextper[ "Type" ]>1)
        {
            $per.="_".$nextper[ "Semester" ];
        }

        if ($this->GetPOST("Transfer")==1)
        {
            $this->DoTransfer($per);
        }


        $titles=array("No.","Matricula","Data de Matricula","Nome","Status","Data","Em Turma","Transferir");

        $table=array($this->B($titles));
        $n=1;

        foreach ($this->ApplicationObj->Students as $student)
        {
            $include=FALSE;
            if ($student[ "StudentHash" ][ "Status" ]==1)
            {
                $include=TRUE;
            }

            $classstudent=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
            (
               $this->ApplicationObj->School[ "ID" ]."_".$per."_ClassStudents",
               array("Student" => $student[ "StudentHash" ][ "ID" ]),
               TRUE,
               array("ID","Class")
            );

            $newclass="-";
            if (!empty($classstudent))
            {
                if (!isset($classnames[ $classstudent[ "Class" ] ]))
                {
                    $newclass=$this->SelectUniqueHash
                    (
                       $this->ApplicationObj->School[ "ID" ]."_".$per."_Classes",
                       array("ID" => $classstudent[ "Class" ]),
                       TRUE,
                       array("ID","Name","GradePeriod")
                    );

                    $newclass[ "GradePeriod_Name" ]=$this->ApplicationObj->GradePeriodsObject->MySqlItemValue
                    (
                       "",
                       "ID",
                       $newclass[ "GradePeriod" ],
                       "Name"
                    );

                    $classnames[ $classstudent[ "Class" ] ]=
                        $newclass[ "GradePeriod_Name" ].", ".
                        $newclass[ "Name" ];
                }

                $newclass=$classnames[ $classstudent[ "Class" ] ];
                $include=FALSE;
            }


            $row=array
            (
               $n,
               $student[ "StudentHash" ][ "ID" ],
               $this->SortTime2Date($student[ "StudentHash" ][ "MatriculaDate" ]),
               $student[ "StudentHash" ][ "Name" ],
               $this->ApplicationObj->StudentsObject->GetEnumValue("Status",$student[ "StudentHash" ]),
               $this->SortTime2Date($student[ "StudentHash" ][ "StatusDate1" ]),
               $newclass,
               $this->MakeCheckBox("Transfer_".$student[ "ID" ],1,$include)
            );


            array_push($table,$row);

            $n++;
        }


        print 
            $this->H(2,"Transferir Turma & Alunos").
            $this->StartForm().
            $this->Center
            (
               $this->B("Escolher Turma: ").
               $this->MakeSelectField("NewClass",$ids,$names).
               $this->BR().
               $this->H(3,"Incluir Alunos na Transferencia").
               $this->Button("submit","Transferir").
               $this->Button("reset","Resetar").
               $this->Html_Table
               (
                  "",
                  $table,
                  array("ALIGN" => 'center'),
                  array(),
                  array()
               ).
               $this->Button("submit","Transferir").
               $this->Button("reset","Resetar").
               $this->MakeHidden("Transfer",1)
            ).
            $this->EndForm().
            "";
    }
}

?>