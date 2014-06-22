<?php


class ClassDiscMarksCells extends ClassDiscMarksCell
{
    //************** Student Data cells **************//


    //*
    //* function DaylyMarksStudentCells, Parameter list: $n,$student
    //*
    //* Generates Absence row for student.
    //*

    function DaylyMarksStudentCells($n,$student)
    {
        $row=array($this->B(sprintf("%02d",$n)));

        if (!$this->LatexMode)
        {
            $action="";
            if (preg_match('/^(Admin|Clerk|Secretary)$/',$this->ApplicationObj->Profile))
            {
                $action="Edit";
            }
            elseif (preg_match('/^(Teacher)$/',$this->ApplicationObj->Profile))
            {
                $action="Show";
            }

            if (!empty($action))
            {
                array_push
                (
                   $row,
                   $this->ApplicationObj->StudentsObject->ActionEntry($action,$student[ "StudentHash" ])
                );
            }
        }

        $this->ApplicationObj->StudentsObject->ItemData[ "Teacher" ]=1;
        foreach ($this->StudentData as $data)
        {
            $this->ApplicationObj->StudentsObject->ItemData[ $data ][ "Teacher" ]=1;
            array_push
            (
               $row,
               $this->ApplicationObj->StudentsObject->MakeShowField($data,$student[ "StudentHash" ])
            );
        }

        return $row;
    }


    //************** Student Semester cells **************//


     //*
    //* function EditTrimesterCells, Parameter list: $edit,$student,$trimester,$type="End"
    //*
    //* Checks if month cell may be edited.
    //*

    function EditTrimesterCells($edit,$student,$trimester,$type="End")
    {
        if ($edit!=1) { return 0; }

        $method="GetDaylyPeriod".$type."DateKey";
        $datekey=$this->ApplicationObj->PeriodsObject->$method($this->ApplicationObj->Period,$trimester);

        $status=$this->ApplicationObj->ClassDiscAbsencesObject->GetStudentStatusType($student,"",$datekey);

        return $status;
     }


    //*
    //* function DaylyMarksStudentSemesterCells, Parameter list: $edit,$trimester,$student,&$m,$studmarks
    //*
    //* Generates Absence row for student.
    //*

    function DaylyMarksStudentSemesterCells($edit,$trimester,$student,&$m,$studmarks)
    {
        $row=array();

        $status1=$this->EditTrimesterCells($edit,$student,$trimester,"End");
        $status2=$this->EditTrimesterCells($edit,$student,$trimester,"End");

        if ($status2>0)
        {
            $nfields=count($this->Assessments[ $trimester ]);
            if ($nfields>1) { $nfields++; }

            return array
            (
               $this->MultiCell
               (
                  $this->ApplicationObj->StudentsObject->GetEnumValue("Status",$student[ "StudentHash" ])." ".
                  $this->SortTime2Date($student[ "StudentHash" ][ "StatusDate1" ]),
                  $nfields
               )
            );
        }

        if ($status1>0)
        {
            $nfields=count($this->Assessments[ $trimester ]);
            if ($nfields>1) { $nfields++; }

            return array
            (
               $this->MultiCell
               (
                  "Ingresso ".$this->SortTime2Date($student[ "StudentHash" ][ "MatriculaDate" ]),
                  $nfields
               )
            );
        }


        $n=1;
        foreach ($this->Assessments[ $trimester ] as $assessment)
        {
            $mark="";
            if (!empty($studmarks[ "Semester" ][ $trimester ][ $n ]))
            {
                $mark=$studmarks[ "Semester" ][ $trimester ][ $n ][ "Mark" ];
            }

            array_push
            (
               $row,
               $this->DaylyMarksStudentMarkCell($edit,$student,$m,$assessment,$mark)
            );

            $n++;
            $m++;
        }

        //Only show media, if we have more than one submark.
        if (count($this->Assessments[ $trimester ])>1)
        {
            array_push
            (
               $row,
               $this->SemesterStudentMark($student,$this->Assessments[ $trimester ])
            );
        }

        return $row;
    }


    //*
    //* function DaylyMarksStudentSemestersCells, Parameter list: $edit,$student,$assess,$studmarks,$include
    //*
    //* Generates Absence row for student.
    //*

    function DaylyMarksStudentSemestersCells($edit,$student,$assess,$studmarks,$include)
    {
        $sem=$this->GetGET("Semester");

        $row=array();
        $m=1;
        for ($trimester=1;$trimester<=$this->ApplicationObj->Disc[ "NAssessments" ];$trimester++)
        {
            //Latex paging
            if (empty($include[ "Semesters" ][ $trimester ])) { continue; }

            $redit=$edit;
            if (
                  $this->ApplicationObj->PeriodsObject->TrimesterEditable
                  (
                     $trimester,
                     $this->ApplicationObj->Disc
                  )
               )
            {
                $redit=0;
            }

            if (!empty($sem) && $sem!=$trimester) { continue; }
            $row=array_merge
            (
               $row,
               $this->DaylyMarksStudentSemesterCells($redit,$trimester,$student,$m,$studmarks)
            );
        }

        return $row;
    }


    //************** Student Recoveries cells **************//



    //*
    //* function DaylyMarksStudentRecoveryCells, Parameter list: $edit,$recovery,$student,&$m,$studmarks
    //*
    //* Generates Absence row for student.
    //*

    function DaylyMarksStudentRecoveryCells($edit,$recovery,$student,&$m,$studmarks)
    {
        $row=array();

        $trimester=$recovery+$this->ApplicationObj->Disc[ "NAssessments" ];

        if (
              $studmarks[ "MarksHash" ][ "MarkResult" ][ $recovery-1 ]==2
              ||
              !isset($studmarks[ "MarksHash" ][ "RecoveryResults" ][ $recovery-1 ])
              ||
              $studmarks[ "MarksHash" ][ "RecoveryResults" ][ $recovery-1 ]!=1
           )
        {
            //already passed, add empties

            $ncols=2;
            if (count($this->Assessments[ $trimester ])>1)
            {
                $ncols=3;
            }
            return array($this->MultiCell("",$ncols));
        }

        $n=1;
        foreach ($this->Assessments[ $trimester ] as $assessment)
        {
            $mark="";
            if (!empty($studmarks[ "Semester" ][ $trimester ][ $n ]))
            {
                $mark=$studmarks[ "Semester" ][ $trimester ][ $n ][ "Mark" ];
            }

            array_push
            (
               $row,
               $this->DaylyMarksStudentMarkCell($edit,$student,$m++,$assessment,$mark)
            );

            $n++;
            $m++;
        }

        if (count($this->Assessments[ $trimester ])>1)
        {
            array_push
            (
               $row,
               $this->SemesterStudentMark($student,$this->Assessments[ $trimester ])
            );
        }

        $rmark="";
        if (!empty($studmarks[ "MarksHash" ][ "RecoveryMedias" ][ $recovery ]))
        {
            $rmark=$this->B($studmarks[ "MarksHash" ][ "RecoveryMedias" ][ $recovery ]);
        }

        array_push($row,$rmark);

        return $row;
    }

    //*
    //* function DaylyMarksStudentRecoveriesCells, Parameter list: $edit,$student,$assess,$studmarks,$include
    //*
    //* Generates Absence row for student.
    //*

    function DaylyMarksStudentRecoveriesCells($edit,$student,$assess,$studmarks,$include)
    {
        $sem=$this->GetGET("Semester");
        if (!empty($sem) && $sem!=$this->ApplicationObj->Disc[ "NAssessments" ]+1) { return array(); }

        $row=array();
        $m=25;
        for ($recovery=1;$recovery<=$this->ApplicationObj->Disc[ "NRecoveries" ];$recovery++)
        {
            //Latex paging
            if (empty($include[ "Recoveries" ][ $recovery ])) { continue; }

            $redit=$edit;
            if (
                  $this->ApplicationObj->PeriodsObject->TrimesterEditable
                  (
                     $this->ApplicationObj->Disc[ "NAssessments" ],
                     $this->ApplicationObj->Disc
                  )
               )
            {
                $redit=0;
            }

            $row=array_merge
            (
               $row,
               $this->DaylyMarksStudentRecoveryCells($redit,$recovery,$student,$m,$studmarks)
            );
        }

        return $row;
    }




    //************** Student Semester Results cells **************//




    //*
    //* function DaylyMarksStudentResultCells, Parameter list: $student,$assess,$studmarks
    //*
    //* Generates Absence row for student.
    //*

    function DaylyMarksStudentSemesterResultCells($student,$assess,$studmarks)
    {
        $results=array("","RN","AN");
        $media="-";
        $res="-";
        if ($studmarks[ "MarksHash" ][ "MediaResult" ]>0)
        {
            $res=$studmarks[ "MarksHash" ][ "MediaResult" ];
            $res=$this->ResultNames[ $res ];
            $media=$studmarks[ "MarksHash" ][ "Media" ];
        }

        return $this->B(array($media,$res));
    }



    //************** Student Final Results cells **************//




    //*
    //* function DaylyMarksStudentResultCells, Parameter list: $student,$assess,$studmarks
    //*
    //* Generates Absence row for student.
    //*

    function DaylyMarksStudentFinalResultCells($student,$assess,$studmarks)
    {
        $results=array("","RN","AN");
        $media="-";
        $res="-";
        if ($studmarks[ "MarksHash" ][ "MarkResult" ]>0)
        {
            $res=$studmarks[ "MarksHash" ][ "MarkResult" ];
            $res=$this->ResultNames[ $res ];
            $media=$studmarks[ "MarksHash" ][ "MediaFinal" ];
        }

        return $this->B(array($media,$res));
    }
}

?>