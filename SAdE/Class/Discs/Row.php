<?php

class ClassDiscsRow extends ClassDiscsEdit
{ 

    //*
    //* function PaintStudentResult, Parameter list: $res
    //*
    //* Creates green AP, if $res is TRUE, red RE otherwise
    //*

    function PaintStudentResult($res)
    {
        $results=array
        (
           0 => "",
           1 => "RPN",
           2 => "RPF",
           3 => "RPNF",
           4 => "AP",
        );

        if (isset($results[ $res ]))
        {
            $res=$results[ $res ];
        }

        return $res;
    }

     //*
    //*
    //* function CalcFinalResult, Parameter list: $edit,$classid,$disc,$studentid,&$markshash,&$absenceshash
    //*
    //* Generates final result.
    //*

    function CalcFinalResult($edit,$classid,$disc,$studentid,&$markshash,&$absenceshash)
    {
        $res=0;
        if ($markshash[ "MarkResult" ]>0 && $absenceshash[ "AbsencesResult" ]>0)
        {
            if ($markshash[ "MarkResult" ]==1)
            {
                    if ($absenceshash[ "AbsencesResult" ]==1) { $res=3; }
                elseif ($absenceshash[ "AbsencesResult" ]==2) { $res=1; }
            }
            elseif ($markshash[ "MarkResult" ]==2)
            {
                    if ($absenceshash[ "AbsencesResult" ]==1) { $res=2; }
                elseif ($absenceshash[ "AbsencesResult" ]==2) { $res=4; }
            }
        }

        $markshash[ "Result" ]=$res;
        $absenceshash[ "Result" ]=$res;

        return $res;
    }


    //*
    //* function StudentActionsEntries, Parameter list: $disc,$actions,$print=TRUE
    //*
    //* Creates Student disc data cells.
    //*

    function StudentActionsEntries($disc,$dactions,$print=FALSE)
    {
        $class=$this->ApplicationObj->Class;
        $class[ "Student" ]=0;
        if (!empty($this->ApplicationObj->Student[ "ID" ]))
        {
            $class[ "Student" ]=$this->ApplicationObj->Student[ "ID" ];
        }
        else
        {
            $class[ "Student" ]=$this->GetGET("Student");
        }

        if (isset($disc[ "ID" ]))
        {
            $class[ "Disc" ]=$disc[ "ID" ];
        }

        $curraction=$this->GetGET("Action");
        $action=$dactions[ count($dactions)-1 ];
        $actions="";
        foreach ($dactions as $raction)
        {
            if (!empty($disc[ "ID" ]))
            {
                $actions.=$this->ApplicationObj->ClassDiscsObject->ActionEntry($raction,$disc);

                if ($raction==$curraction) { $action=$raction; }
            }
        }

        /* /\* if ($print) *\/ */
        /* /\* { *\/ */
        /* /\*     $actions.=$this->ApplicationObj->ClassesObject->ActionPrintEntry *\/ */
        /* /\*     ( *\/ */
        /* /\*        $action, *\/ */
        /* /\*        $class, *\/ */
        /* /\*        0, *\/ */
        /* /\*        "Classes", *\/ */
        /* /\*        array("Latex" => 1) *\/ */
        /* /\*     ); *\/ */

        /* /\* } *\/ */

        return $actions;
    }

    //*
    //* function ClassDiscData, Parameter list: $no,$disc,$datas,$actions
    //*
    //* Creates Student disc data cells.
    //*

    function ClassDiscData($no,$disc,$datas,$dactions)
    {
        $row=array($this->B(sprintf("%02d",$no)));

        if (!$this->LatexMode)
        {
            array_push($row,$this->StudentActionsEntries($disc,$dactions));
        }

        foreach ($datas as $data)
        {
            $val="";
            if (!empty($disc[ $data ])) { $val=$disc[ $data ]; }
            array_push($row,$val);
        }

        return $row;
    }


    //*
    //* function DiscActionsEntries, Parameter list: $student,$actions,$print=FALSE
    //*
    //* Creates Student disc data cells.
    //*

    function DiscActionsEntries($student,$sactions,$print=TRUE)
    {
        $class=$this->ApplicationObj->Class;
        $class[ "Student" ]=$student[ "ID" ];
        $class[ "Disc" ]=$this->ApplicationObj->Disc[ "ID" ];

        $curraction=$this->GetGET("Action");
        $action=$sactions[ count($sactions)-1 ];

        $actions="";
        foreach ($sactions as $raction)
        {
            $actions.=$this->ApplicationObj->ClassesObject->ActionEntry($raction,$class);

            if ($raction==$curraction) { $action=$raction; }
        }

        return $actions;
    }
    //*
    //* function ClassStudentData, Parameter list: $edit,$update,$classid,$discid,$teacherid,$no,$student,$sdatas,$sactions
    //*
    //* Makes Marks HTML table for discid $discid.
    //*

    function ClassStudentData($no,$student,$sdatas,$sactions)
    {
        $student[ "StudentHash" ][ "Status" ]=
            $this->ApplicationObj->StudentsObject-> GetEnumValue("Status",$student[ "StudentHash" ]);

        $row=array($this->B(sprintf("%02d",$no)));

        if (!$this->LatexMode)
        {
            array_push($row,$this->DiscActionsEntries($student,$sactions));
        }

        foreach ($sdatas as $data)
        {
            array_push($row,$student[ "StudentHash" ][ $data ]);
        }

        return $row;
    }


    //* function FirstsCells, Parameter list: $no,$student,$disc
    //*
    //* Creates cells for disc or student.
    //*

    function FirstsCells($edit,$no,$class,$student,$disc)
    {
        $row=array();
        if ($this->PerDisc)
        {
            $row=$this->ClassStudentData
            (
               $no,
               $student,
               $this->StudentsData,
               $this->StudentsActions
            );
        }
        else
        {
            $row=$this->ClassDiscData
            (
               $no,
               $disc,
               $this->DiscsData ,
               $this->DiscsActions
            );
        }

        if ($this->EmptyTableColumns) { array_push($row,""); }


        return $row;
    }

    //* function StatusCells, Parameter list: $no,$student,$disc
    //*
    //* Creates celss for disc or student.
    //*

    function StatusCells($edit,$class,$disc,$student)
    {
        $row=array();
        if ($this->ShowStatus)
        {
            $row=array
            (
               $this->ApplicationObj->ClassStatusObject->MakeStatusField
               (
                  $edit,
                  $class[ "ID" ],
                  $disc[ "ID" ],
                  $student[ "StudentHash" ][ "ID" ]
               )
            );

            if ($this->EmptyTableColumns) { array_push($row,""); }
        }

        return $row;
    }


    //*
    //* function FinalCells, Parameter list: $disc
    //*
    //* Generates final titles.
    //*

    function FinalCells($edit,$classid,$disc,$studentid,$markshash,$absenceshash)
    {
        return array
        (
           $this->PaintStudentResult
           (
              $this->CalcFinalResult($edit,$classid,$disc,$studentid,$markshash,$absenceshash)
           )       
        );
    }

    //*
    //* function MakeStudentNlessonCells, Parameter list: $edit,$tedit,$no,$student,$disc
    //*
    //* Creates cells for nlessons, if called for.
    //*

    function MakeStudentNlessonCells($edit,$tedit,$no,$class,$student,$disc)
    {
        //Disc no of lessons
        $row=array();
        if (!$this->PerDisc && $this->ShowNLessons)
        {
             $redit=$tedit;
            /* if ($disc[ "AbsencesType" ]==$this->ApplicationObj->OnlyTotals) */
            /* { */
            /*     //Only absence totals -reset $edit */
            /*     if (!$this->PerDisc) */
            /*     { */
            /*         $redit=0; */
            /*     } */
            /* } */

            $row=array_merge
            (
               $row,
               $this->ApplicationObj->ClassDiscNLessonsObject->NLessonsRow
               (
                  $redit,
                  $this->ApplicationObj->Class,
                  $disc
               )
            );

            if ($this->EmptyTableColumns) { array_push($row,""); }
        }

        return $row;
    }

    //*
    //* function MakeStudentWeightCells, Parameter list: $edit,$tedit,$no,$student,$disc
    //*
    //* Creates cells for weights, if called for.
    //*

    function MakeStudentWeightCells($edit,$tedit,$no,$class,$student,$disc)
    {
        $row=array();
        if (!$this->PerDisc && $this->ShowMarkWeights)
        {
            $row=array_merge
            (
               $row,
               $this->ApplicationObj->ClassDiscWeightsObject->WeightsInputs(0,$disc,$this->ShowMarkWeightsTotals)
            );

            if ($this->EmptyTableColumns) { array_push($row,""); }
        }

        return $row;
    }

    //*
    //* function MakeStudentAbsencesCells, Parameter list: $edit,$tedit,$no,$student,$disc
    //*
    //* Creates cells for absences, if called for.
    //*

    function MakeStudentAbsencesCells($edit,$tedit,$no,$class,$student,$disc)
    {
        $row=array();
        if ($this->ShowAbsences)
        {
            $redit=$edit;
            $row=array_merge
            (
               $row,
               $this->ApplicationObj->ClassAbsencesObject->AbsencesRow
               (
                  $redit,
                  $class,
                  $disc,
                  $student
               )
            );

            if ($this->EmptyTableColumns) { array_push($row,""); }
        }

        return $row;
    }

    //*
    //* function MakeStudentMarksCells, Parameter list: $edit,$tedit,$no,$student,$disc,$markshash
    //*
    //* Creates cells for marks, if called for.
    //*

    function MakeStudentMarksCells($edit,$tedit,$no,$class,$student,$disc,$markshash)
    {
        $row=array();
        if ($this->ShowMarks)
        {
            $row=array_merge
            (
               $row,
               $this->ApplicationObj->ClassMarksObject->MarksRow
               (
                  $edit,
                  $class,
                  $disc,
                  $student,
                  $markshash
               )
            );

            if ($this->EmptyTableColumns) { array_push($row,""); }
        }

        return $row;
    }

    //*
    //* function MakeStudentRecoveriesCells, Parameter list: $edit,$tedit,$no,$student,$disc,$markshash
    //*
    //* Creates cells for recoveries, if called for.
    //*

    function MakeStudentRecoveriesCells($edit,$tedit,$no,$class,$student,$disc,$markshash)
    {
        $row=array();
        if ($this->ShowRecoveries)
        {
            $row=array_merge
            (
               $row,
               $this->ApplicationObj->ClassMarksObject->RecoveriesRow
               (
                  $edit,
                  $class,
                  $disc,
                  $student,
                  $markshash
               )
            );

            if ($this->EmptyTableColumns) { array_push($row,""); }
        }

        return $row;
    }

    //*
    //* function MakeStudentResultsCells, Parameter list: $edit,$tedit,$no,$student,$disc,$markshash,$absenceshash
    //*
    //* Creates cells for results, if called for.
    //*

    function MakeStudentResultsCells($edit,$tedit,$no,$class,$student,$disc,$markshash,$absenceshash)
    {
        $row=array();
        if (
              $this->ShowMediaFinal
              ||
              $this->ShowAbsenceFinal
              ||
              $this->ShowFinal
           )
        {
           if ($this->ShowAbsenceFinal)
           {
                $row=array_merge
                (
                   $row,
                   $this->ApplicationObj->ClassAbsencesObject->FinalRow
                   (
                      $edit,
                      $class,
                      $disc,
                      $student,
                      $absenceshash
                   )
                );
           }

           if ($this->ShowMediaFinal)
           {
               $row=array_merge
               (
                  $row,
                  $this->ApplicationObj->ClassMarksObject->FinalRow
                  (
                     $edit,
                     $class,
                     $disc,
                     $student,
                     $markshash
                  )
               );
           }

           if ($this->ShowFinal)
           {
               $row=array_merge
               (
                   $row,
                   $this->FinalCells
                   (
                      $edit,
                      $class,
                      $disc,
                      $student,
                      $markshash,
                      $absenceshash
                   )
                );
           }
        }

        return $row;
    }


    //*
    //* function MakeStudentDiscRow, Parameter list: $edit,$tedit,$no,$student,$disc
    //*
    //* Creates row for disc and student.
    //*

    function MakeStudentDiscRow($edit,$tedit,$no,$class,$student,$disc)
    {
        $row=$this->FirstsCells($edit,$no,$class,$student,$disc);

        //Student name and status
        if ($this->ApplicationObj->ClassDiscsObject->ShowStatus)
        {
            $row=array_merge
            (
               $row,
               $this->StatusRow
               (
                  $edit,
                  $class,
                  $disc,
                  $student
               )
            );

            if ($this->ApplicationObj->ClassDiscsObject->EmptyTableColumns) { array_push($row,""); }
        }

        $status=$this->ApplicationObj->ClassStatusObject->ReadStudentDiscStatus
        (
           $class,
           $disc,
           $student
        );

        if ($status!=1) { $edit=0; }

        $markshash=$this->ApplicationObj->ClassMarksObject->CalcStudentDiscMarks
        (
           $disc,
           $this->ApplicationObj->ClassMarksObject->ReadStudentDiscMarks
           (
              $class,
              $disc,
              $student
           )
        );


        $absenceshash=$this->ApplicationObj->ClassAbsencesObject->CalcStudentDiscAbsences
        (
           $disc,
           $this->ApplicationObj->ClassAbsencesObject->ReadStudentDiscAbsences
           (
              $class,
              $disc,
              $student
           )
        );

        return array_merge
        (
           $this->FirstsCells($edit,$no,$class,$student,$disc),
           $this->StatusCells($edit,$class,$disc,$student),
           $this->MakeStudentWeightCells($edit,$tedit,$no,$class,$student,$disc),
           $this->MakeStudentNlessonCells($edit,$tedit,$no,$class,$student,$disc),
           $this->MakeStudentAbsencesCells($edit,$tedit,$no,$class,$student,$disc),
           $this->MakeStudentMarksCells($edit,$tedit,$no,$class,$student,$disc,$markshash),
           $this->MakeStudentRecoveriesCells($edit,$tedit,$no,$class,$student,$disc,$markshash),
           $this->MakeStudentResultsCells($edit,$tedit,$no,$class,$student,$disc,$markshash,$absenceshash)
        );
    }

    //*
    //* function MakeStudentTotalAbsencesRow, Parameter list: $edit,$student,$disc
    //*
    //* Creates row with total absences for $student.
    //*

    function MakeStudentTotalAbsencesRow($edit,$class,$student,$disc,$firstfields=TRUE)
    {
        //Row with total absences
        $row=array();

        if ($firstfields)
        {
            $this->FirstsCells
            (
               $edit,
               "-",
               $class,
               $student,
               array()
            );
        }

        if (TRUE)
        {
            array_push
            (
               $row,
               $this->MultiCell("",2)
            );
        }

        if (TRUE)
        {
            array_push
            (
               $row,
               $this->B("Faltas Totais:"),
               $this->MultiCell("",2)
            );
        }

        if (!$this->PerDisc && $this->ShowAbsences)
        {
            $row=array_merge
            (
               $row,
               $this->ApplicationObj->ClassDiscNLessonsObject->NLessonsRow
               (
                  $edit,
                  $this->ApplicationObj->Class,
                  $disc
               )
            );

            if ($this->ApplicationObj->ClassDiscsObject->EmptyTableColumns) { array_push($row,""); }
        }


        /* if (!$this->PerDisc && $this->ShowAbsences) */
        /* { */
        /*     array_push */
        /*     ( */
        /*        $row, */
        /*        $this->MultiCell("",$class[ "NAssessments" ]+1) */
        /*     ); */
        /* } */

        $row=array_merge
        (
           $row,
           $this->ApplicationObj->ClassAbsencesObject->AbsencesRow
           (
              $edit,
              $class,
              $disc,
              $student
            )
        );

        return $row;
    }
}

?>