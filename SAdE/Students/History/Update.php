<?php



class StudentsHistoryUpdate extends StudentsHistoryClassSelect
{
     //*
    //* function UpdateHistoryShortTable, Parameter list: $edit,$student,&$entries
    //*
    //* Saves results of Class List History form
    //* 
    //*

    function UpdateHistoryShortTable($edit,$student,&$entries)
    {
        foreach ($entries as $gradeid => $gradeentries)
        {
            $grade=$this->ApplicationObj->GradeObject->GetGrade($gradeid);

            foreach ($gradeentries as $grid => $gradeentry)
            {
                $sqltable=
                    $this->ApplicationObj->School[ "ID" ]."_".
                    $this->ApplicationObj->GetPeriodName($gradeentry[ "Period" ]).
                    "_ClassStudents";

               if (!empty($gradeentry))
                {
                    if (!empty($gradeentry[ "ClassStudent" ]))
                    {
                        if (
                              !$this->StudentHasRecords
                              (
                                 $student,
                                 $this->ApplicationObj->GetPeriodName($gradeentry[ "Period" ])
                              )
                           )
                        {
                            $cvalue=$gradeentry[ "Class" ][ "ID" ];
                            $value=$this->GetPOST($this->PeriodClassSelectName($gradeentry));

                            if (preg_match('/^\d+$/',$value) && $value>0 && $cvalue!=$value)
                            {
                                $this->ApplicationObj->ClassStudentsObject->MySqlSetItemValue
                                (
                                   $sqltable,
                                   "ID",
                                   $gradeentry[ "ClassStudent" ][ "ID" ],
                                   "Class",
                                   $value
                                 );

                                $entries[ $gradeid ][ $grid ][ "ClassStudent" ][ "ID" ]=$value;
                                $entries[ $gradeid ][ $grid ][ "Class" ]=
                                    $this->ApplicationObj->ClassesObject->SelectUniqueHash
                                    (
                                       "",
                                       array("ID" => $value)
                                    );;
                           }
                        }
                    }
                    else
                    {
                        $value=$this->GetPOST($this->PeriodClassSelectName($gradeentry));
                        if (preg_match('/^\d+$/',$value) && $value>0)
                        {
                            $where=array
                            (
                               "School" => $this->ApplicationObj->School[ "ID" ],
                               "Student" => $student[ "StudentHash" ][ "ID" ],
                               "Grade" => $gradeentry[ "Grade" ][ "ID" ],
                               "GradePeriod" => $gradeentry[ "GradePeriod" ][ "ID" ],
                               "Class" => $value,
                            );

                            $item=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
                            (
                               $sqltable,
                               $where
                            );

                            if (empty($item))
                            {
                                $classstudent=$where;
                                $classstudent[ "UniqueID" ]=$student[ "StudentHash" ][ "UniqueID" ];
                                $this->ApplicationObj->ClassStudentsObject->MySqlInsertItem
                                (
                                   $sqltable,
                                   $classstudent
                                );

                                //var_dump($sqltable);
                                //var_dump($classstudent);

                                $entries[ $gradeid ][ $grid ][ "ClassStudent" ]=$classstudent;
                                $entries[ $gradeid ][ $grid ][ "Class" ]=
                                    $this->ApplicationObj->ClassesObject->SelectUniqueHash
                                    (
                                       "",
                                       array("ID" => $classstudent[ "Class" ])
                                    );
                            }
                       }
                    }
                }
            }
        }
    }

    

}

?>