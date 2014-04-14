<?php


class StudentsHistoryRead extends StudentsRemanage
{
    //*
    //* function ReadClassStudent, Parameter list: $student,$period
    //*
    //* Retrieves student entries.
    //* 
    //*

    function ReadClassStudent($student,$period)
    {   
        $classstudent=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
        (
           $this->ApplicationObj->School[ "ID" ]. "_".
           $period."_ClassStudents",
           array("Student" => $student[ "ID" ]),
           TRUE,
           array("ID","Class","Grade","GradePeriod","Student")
        );

        if (!empty($classstudent))
        {
            $grade=$this->ApplicationObj->GradeObject->SelectUniqueHash
            (
               "",
               array("ID" => $classstudent[ "Grade" ]),
               TRUE
            );

            $this->SemesterMode=$grade[ "Mode" ];
        }

        return $classstudent;
    }

    //*
    //* function ReadClassStudentData, Parameter list: $classstudent
    //*
    //* Reads class hash, period, grade and gradeperiod hashes for $classstudent.
    //* 
    //*

    function ReadClassStudentData($classstudent)
    {
        $hash= array
        (
            "Class" => $this->ApplicationObj->ClassesObject->SelectUniqueHash
            (
               "",
               array("ID" => $classstudent[ "Class" ]),
               TRUE
            ),
            "Grade" => $this->ApplicationObj->GradeObject->SelectUniqueHash
            (
               "",
               array("ID" => $classstudent[ "Grade" ]),
               TRUE
            ),
            "GradePeriod" => $this->ApplicationObj->GradePeriodsObject->SelectUniqueHash
            (
               "",
               array("ID" => $classstudent[ "GradePeriod" ]),
               TRUE
            ),
        );

        $hash[ "Period" ]= $this->ApplicationObj->PeriodsObject->SelectUniqueHash
        (
           "",
           array("ID" => $hash[ "Class" ][ "Period" ]),
           TRUE
        );

        $hash[ "ClassStudent" ]=$classstudent;

        return $hash;
    }

    //*
    //* function ReadStudentEntries, Parameter list: $student
    //*
    //* Retrieves student entries.
    //* 
    //*

    function ReadStudentEntries($student)
    {   
        $classtables=$this->DBTables
        (
           $this->ApplicationObj->School[ "ID" ].
           "_%_ClassStudents"
        );

        $periods=array();
        foreach ($classtables as $table)
        {
            $table=preg_replace('/^\d_/',"",$table);
            $table=preg_replace('/_[^_]+$/',"",$table);

            array_push($periods,$table);
        }

        sort($periods);

        $periodsentries=array();
        foreach ($periods as $period)
        {
            $classstudent=$this->ReadClassStudent($student,$period);

            $prekey=$this->ApplicationObj->School[ "ID" ]."_".$period."_Class";

            $nentries=$this->MySqlNEntries
            (
               $prekey."Students",
               array("Student" => $student[ "ID" ])
            );


            if ($nentries>=1)
            {

                $grade=$classstudent[ "Grade" ];
                $gradeperiod=$classstudent[ "GradePeriod" ];
                if (empty($periodsentries[ $grade ]))
                {
                    $periodsentries[ $grade ]=array();
                }

                $periodsentries[ $grade ][ $gradeperiod ]=$classstudent;

                if ($nentries>1) { print "Double student entry!!!"; }
            }
        }


        return $periodsentries;
   }

    //*
    //* function StudentHasRecords, Parameter list: $student,$periodname
    //*
    //* Checks if student has any records.
    //* 
    //*

    function StudentHasRecords($student,$periodname)
    {
        $nentries=
            $this->ApplicationObj->ClassMarksObject->MySqlNEntries
            (
               $this->ApplicationObj->School[ "ID" ]."_".$periodname."_ClassMarks",
               array("Student" => $student[ "StudentHash" ][ "ID" ])
            )
            +
            $this->ApplicationObj->ClassAbsencesObject->MySqlSumNEntries
            (
               $this->ApplicationObj->School[ "ID" ]."_".$periodname."_ClassAbsences",
               array("Student" => $student[ "StudentHash" ][ "ID" ]),
               "Absences"
            );

        if ($nentries>0) { return TRUE; }
        else             { return FALSE; }
    }

     //*
    //* function ReadStudentClassTable, Parameter list: $edit,$student
    //*
    //* Does initial read necessary to produce student class history table.
    //* 
    //*

    function ReadStudentHistoryEntries($edit,$student,$gradeentries)
    {
        $this->ApplicationObj->GradeObject->ReadGrades();
        $gradeids=array_keys($gradeentries);

        $firstentry=array();
        $lastentry=array();
        foreach ($gradeids as $gradeid)
        {
            $grade=$this->ApplicationObj->GradeObject->GetGrade($gradeid);
            $entries[ $gradeid ]=array();

            $this->ApplicationObj->GradeObject->ReadGradePeriods($gradeid);

            foreach ($this->ApplicationObj->Grades[ $gradeid-1 ][ "Periods" ] as $gradeperiod)
            {
                $entries[ $gradeid ][ $gradeperiod[ "ID" ] ]=array();
                if (!empty($gradeentries[ $gradeid ][ $gradeperiod[ "ID" ] ]))
                {
                    $classstudent=$gradeentries[ $gradeid ][ $gradeperiod[ "ID" ] ];
                    $hash=$this->ReadClassStudentData($classstudent);

                    $entries[ $gradeid ][ $gradeperiod[ "ID" ] ]=$hash;

                    if (empty($firstentry))
                    {
                        //This is the first entry, roll back
                        $firstentry=$hash;
                    }

                    $lastentry=$hash;
                }
            }
        }

        if (!empty($firstentry))
        {
            $gradeid=$firstentry[ "Grade" ][ "ID" ];
            $gradeperiodid=$firstentry[ "GradePeriod" ][ "ID" ];
            $gradeperiodper=$firstentry[ "GradePeriod" ][ "Year" ];
            $periodid=$firstentry[ "Period" ][ "ID" ];
            $period=$firstentry[ "Period" ];

            $rgradeperiods=array();
            foreach ($this->ApplicationObj->Grades[ $gradeid-1 ][ "Periods" ] as $gradeperiod)
            {
                if ($gradeperiod[ "ID" ]!=$gradeperiodid)
                {
                    array_push($rgradeperiods,$gradeperiod);
                }
                else
                {
                    break;
                }
            }

            $rgradeperiods=array_reverse($rgradeperiods);
            foreach ($rgradeperiods as $rgradeperiod)
            {
                $rperiod=$this->ApplicationObj->PeriodsObject->PreviousPeriod($period[ "ID" ]);
                $entries[ $gradeid ][ $rgradeperiod[ "ID" ] ]=array
                (
                   "Period" => $rperiod,
                   "Grade" => $this->ApplicationObj->Grades[ $gradeid-1 ],
                   "GradePeriod" => $rgradeperiod,
                );
                $period=$rperiod;
            }
        }

        if (!empty($lastentry))
        {
            $gradeid=$lastentry[ "Grade" ][ "ID" ];
            $gradeperiodid=$lastentry[ "GradePeriod" ][ "ID" ];
            $periodid=$lastentry[ "Period" ][ "ID" ];
            $period=$lastentry[ "Period" ]; 

            $rgradeperiods=array();
            $include=FALSE;

            foreach ($this->ApplicationObj->Grades[ $gradeid-1 ][ "Periods" ] as $gradeperiod)
            {
                if ($gradeperiod[ "ID" ]==$gradeperiodid)
                {
                    $include=TRUE;
                }
                elseif ($include)
                {
                   array_push($rgradeperiods,$gradeperiod);
                }
            }

            foreach ($rgradeperiods as $rgradeperiod)
            {
                if (!empty($period[ "NextPeriod" ]))
                {
                    $rperiod=$this->ApplicationObj->LocatePeriod($period[ "NextPeriod" ]);

                    $entries[ $gradeid ][ $rgradeperiod[ "ID" ] ]=array
                    (
                       "Period" => $rperiod,
                       "Grade" => $this->ApplicationObj->Grades[ $gradeid-1 ],
                       "GradePeriod" => $rgradeperiod,
                    );

                    $period=$rperiod;
                }
            }
        }

        return $entries;
    }
}

?>