<?php


class ClassDiscMarksUpdate extends ClassDiscMarksCalc
{
    //*
    //* function UpdateStudentMark, Parameter list: $student,$assessment,$mark
    //*
    //* Updates student mark cell.
    //*

    function UpdateStudentMark($student,$assessment,&$mark)
    {
        $oldmark="";
        if (!empty($mark))
        {
            $oldmark=$mark[ "Mark" ];
        }

        $newmark=$this->GetPOST($this->StudentMarkCGIName($student,$assessment));

        $newmark=preg_replace('/[^0-9.,-]/',"",$newmark);
        $newmark=preg_replace('/,/',".",$newmark);

        if (preg_match('/\d/',$newmark))
        {
            if ($newmark>10.0) { $newmark/=10.0; }
            elseif ($newmark>100.0) { $newmark=""; }
        }

        $updated=FALSE;
        if (!empty($oldmark))
        {
            //We have an item in DB, update or delete
            if (empty($newmark))
            {
                //Remove
                $this->MySqlDeleteItem
                (
                   "",
                   $mark[ "ID" ]
                );

                $mark=array();
                $updated=TRUE;
            }
            elseif ($newmark!=$oldmark)
            {
                //Update
                $this->MySqlSetItemValue
                (
                   "",
                   "ID",
                   $mark[ "ID" ],
                   "Mark",
                   $newmark
                );

                $mark[ "Mark" ]=$newmark;
                $updated=TRUE;
            }
        }
        elseif (!empty($newmark) || preg_match('/^0\.?0*$/',$newmark))
        {
            //$oldmark empty, add since $newmark nonempty
            $mark=$this->StudentMarkSqlWhere($student,$assessment);
            $mark[ "Mark" ]=$newmark;

            $this->MySqlInsertItem("",$mark);
            $updated=TRUE;
        }

        return $updated;
    }

    //*
    //* function UpdateStudentTrimesterMarks, Parameter list: $student,$trimester,&$studmarks
    //*
    //* Updates student semester cells.
    //*

    function UpdateStudentTrimesterMarks($student,$trimester,&$studmarks)
    {
        $updateds=0;

        $n=1;
        foreach ($this->Assessments[ $trimester ] as $assessment)
        {
            $mark=array();
            if (!empty($studmarks[ "Semester" ][ $trimester ][ $n ]))
            {
                $mark=$studmarks[ "Semester" ][ $trimester ][ $n ];
            }

            $updated=$this->UpdateStudentMark($student,$assessment,$mark);
            if ($updated)
            {
                $studmarks[ "Semester" ][ $trimester ][ $n ]=$mark;
                $updateds++;
            }
            $n++;
        }

        if ($updateds>0)
        {
            $studmarks[ "Marks" ][ $trimester ]=$this->TrimesterStudentMark($student, $trimester);
        }

        return $updateds;
    }

    //*
    //* function UpdateStudentTrimestersMarks, Parameter list: $student,&$studmarks
    //*
    //* Updates student semesters cells.
    //*

    function UpdateStudentTrimestersMarks($student,&$studmarks)
    {
        $sem=$this->GetGET("Semester");

        $updateds=0;
        for ($trimester=1;$trimester<=$this->ApplicationObj->Disc[ "NAssessments" ];$trimester++)
        {
            if (
                  $this->ApplicationObj->PeriodsObject->TrimesterEditable
                  (
                     $trimester,
                     $this->ApplicationObj->Disc
                  )
               )
            {
                continue;
            }
            if (!empty($sem) && $sem!=$trimester) { continue; }

            $updateds+=$this->UpdateStudentTrimesterMarks($student,$trimester,$studmarks);
        }

        return $updateds;
    }


    //*
    //* function UpdateStudentRecoveryMarks, Parameter list: $student,$recovery,&$studmarks
    //*
    //* Updates student recovery cells.
    //*

    function UpdateStudentRecoveryMarks($student,$recovery,&$studmarks)
    {
        $updateds=0;

        $trimester=$recovery+$this->ApplicationObj->Disc[ "NAssessments" ];

        $n=1;
        foreach ($this->Assessments[ $trimester ] as $assessment)
        {
            $mark=array();
            if (!empty($studmarks[ "Semester" ][ $trimester ][ $n ]))
            {
                $mark=$studmarks[ "Semester" ][ $trimester ][ $n ];
            }

            $updated=$this->UpdateStudentMark($student,$assessment,$mark);
            if ($updated)
            {
                $studmarks[ "Semester" ][ $trimester ][ $n ]=$mark;
                $updateds++;
            }
            $n++;
        }

        if ($updateds>0)
        {
            $studmarks[ "Marks" ][ $trimester ]=$this->TrimesterStudentMark($student,$this->Assessments[ $trimester ]);
        }

        return $updateds;
    }

    //*
    //* function UpdateStudentRecoveriesMarks, Parameter list: $student,&$studmarks
    //*
    //* Updates student recoveries cells.
    //*

    function UpdateStudentRecoveriesMarks($student,&$studmarks)
    {
        $sem=$this->GetGET("Semester");
        if (!empty($sem) && $sem!=$this->ApplicationObj->Disc[ "NAssessments" ]+1) { return 0; }

        $updateds=0;
        for ($recovery=1;$recovery<=$this->ApplicationObj->Disc[ "NRecoveries" ];$recovery++)
        {
            if (
                  $this->ApplicationObj->PeriodsObject->TrimesterEditable
                  (
                     $this->ApplicationObj->Disc[ "NAssessments" ],
                     $this->ApplicationObj->Disc
                  )
               )
            {
                continue;
            }

            $updateds+=$this->UpdateStudentRecoveryMarks($student,$recovery,$studmarks);
        }

        return $updateds;
    }





    //*
    //* function UpdateStudentMark, Parameter list: $student,$assessment,&$studmarks
    //*
    //* Updates all student marks cells.
    //*

    function UpdateStudentMarks($student,&$studmarks)
    {
        $updateds=$this->UpdateStudentTrimestersMarks($student,$studmarks);
        $updateds+=$this->UpdateStudentRecoveriesMarks($student,$studmarks);

        if ($updateds>0)
        {
            $studmarks[ "MarksHash" ]=$this->ApplicationObj->ClassMarksObject->CalcStudentDiscMarks
            (
               $this->ApplicationObj->Disc,
               $studmarks[ "Marks" ]
            );
        }

        $this->UpdateStudentDiscMarks($student,$studmarks);
    }

    //*
    //* function UpdateStudentDiscMarks, Parameter list: $student,$studmarks,$disc=array(),$class=array()
    //*
    //* Update student class marks.
    //*

    function UpdateStudentDiscMarks($student,$studmarks,$disc=array(),$class=array())
    {
        if (empty($disc))  { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $sem=$this->GetGET("Semester");

        for ($trimester=1;$trimester<=$disc[ "NAssessments" ];$trimester++)
        {
            if (!empty($sem) && $sem!=$trimester) { continue; }
            $where=array
            (
               "Class"      => $class[ "ID" ],
               "ClassDisc"  => $disc[ "ID" ],
               "Student"    => $student[ "StudentHash" ][ "ID" ],
               "Assessment" => $trimester,
               "SecEdit"    => 1,
            );

            $mark=$where;
            $mark[ "Mark" ]="";
            if (!empty($studmarks[ "Marks" ][ $trimester ]))
            {
                $mark[ "Mark" ]=$studmarks[ "Marks" ][ $trimester ];
            }

            $mark[ "SecEdit" ]=1;
            $this->ApplicationObj->ClassMarksObject->AddOrUpdate("",$where,$mark);
        }

        if (!empty($sem) && $sem!=$this->ApplicationObj->Disc[ "NAssessments" ]+1) { return; }

        for ($recovery=1;$recovery<=$disc[ "NRecoveries" ];$recovery++)
        {
            $trimester=$recovery+$disc[ "NAssessments" ];
            $where=array
            (
               "Class"      => $class[ "ID" ],
               "ClassDisc"  => $disc[ "ID" ],
               "Student"    => $student[ "StudentHash" ][ "ID" ],
               "Assessment" => $trimester,
               "SecEdit"    => 1,
            );

            $mark=$where;
            $mark[ "Mark" ]="";
            if (!empty($studmarks[ "Marks" ][ $trimester ]))
            {
                $mark[ "Mark" ]=$studmarks[ "Marks" ][ $trimester ];
            }

            $mark[ "SecEdit" ]=1;
            $this->ApplicationObj->ClassMarksObject->AddOrUpdate("",$where,$mark);
        }
    }

    //*
    //* function UpdateAllStudentsMarks, Parameter list: $disc=array(),$class=array()
    //*
    //* Updates all students marks, supposed to be invoked after updating of MaxVals has occurred.
    //* That is, by: ClassDiscAssessments::UpdateDaylyAssessments.
    //*

    function UpdateAllStudentsMarks($disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $this->ReadDaylyAssessments();

        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($class[ "ID" ]);
        foreach ($this->ApplicationObj->Students as $student)
        {
            $studmarks=$this->ReadDaylyStudent(123,$student);
            $this->UpdateStudentDiscMarks($student,$studmarks,$disc,$class);
        }
    }

 }

?>