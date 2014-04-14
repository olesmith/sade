<?php


class ClassDiscAbsencesRow extends ClassDiscAbsencesUpdate
{
     //*
    //* function CalcPercent, Parameter list: $n,$m
    //*
    //* Returns percent, rounded to 1 decimal.
    //*

    function CalcPercent($n,$m)
    {
        $per="-";
        if ($m>0)
        {
            $per=(100.0*$n)/(1.0*$m);
        }

        $per=sprintf("%.1f",$per);
        if ($per<10.0) { $per="0".$per; }
        if ($per<100.0) { $per="&nbsp;".$per; }

        return $per;
    }


    //************** Student Data columns **************//


     //*
    //* function DaylyAbsencesStudentCells, Parameter list: $n,$student
    //*
    //* Generates Absence row for student.
    //*

    function DaylyAbsencesStudentCells($n,$student)
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


    //************** Monthly Data columns **************//


    //*
    //* function AbsencesStudentSplitPageCells, Parameter list: $student,&$nleading,&$ntrailing
    //*
    //* Generates the month cells for $student.
    //*

    function AbsencesStudentSplitPageCells($student,&$nleading,&$ntrailing)
    {
        foreach ($this->ApplicationObj->Contents as $trimester => $contents)
        {
            foreach ($contents as $id => $content)
            {
                $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($content[ "Date" ]);
                $status=$this->GetStudentStatusType($student,"",$datekey);

                if ($status==0)
                {
                    continue;
                }
                elseif ($status==1) { $nleading++; }
                elseif ($status==2) { $ntrailing++; }

                //Make sure passive items are deleted.
                $this->MySqlSetFieldWhere
                (
                   "",
                   $this->StudentAbsenceSqlWhere($student,$content),
                   "Weight='0'"
                );
            }
        }
    }


     //*
    //* function EditMonthCell, Parameter list: $edit,$student,$content
    //*
    //* Checks if month cell may be edited.
    //*

    function EditMonthCell($edit,$student,$content)
    {
        if ($edit!=1) { return 0; }

        $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($content[ "Date" ]);
        $status=$this->GetStudentStatusType($student,"",$datekey);

        if ($status==1) { return -1; }
        if ($status==2) { return -1; }

        $redit=$edit;
        $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($content[ "Date" ]);

        if (
              $this->ApplicationObj->PeriodsObject->TrimesterEditable
              (
                 $content[ "Semester" ],
                 $this->ApplicationObj->Disc
              )
           )
        {
            $redit=0;
        }

        return $redit;
     }

     //*
    //* function DaylyAbsencesStudentMonthCells, Parameter list: $edit,$n,$month,$student,$date,$chs,&$studchs
    //*
    //* Generates Absence row for student.
    //*

    function DaylyAbsencesStudentMonthCells($edit,$n,$month,$student,$date,$chs,&$studchs)
    {
        $row=array();

        $nleading=0;
        $ntrailing=0;
        $this->AbsencesStudentSplitPageCells($student,$nleading,$ntrailing);

        if ($nleading>0)
        {
            array_push
            (
               $row,
               $this->MultiCell
               (
                  "Ingresso ".$this->SortTime2Date($student[ "StudentHash" ][ "MatriculaDate" ]),
                  $nleading
               )
            );
        }

        $date=$this->GetGET("Date");

        $m=0;
        $ch=0;
        foreach ($this->ApplicationObj->Contents as $trimester => $tcontents)
        {
            foreach ($tcontents as $content)
            {
                $redit=$this->EditMonthCell($edit,$student,$content);
                if ($redit>=0)
                {
                    if (!empty($date) && $date!=$content[ "DateKey" ]) { $redit=0; }
                    array_push
                    (
                       $row,
                       $this->DaylyAbsencesStudentAbsenceCell($redit,$n,$month,$student,$m++,$content,$ch,$studchs)
                    );
                }
            }
        }

        if ($ntrailing>0)
        {
            array_push
            (
               $row,
               $this->MultiCell
               (
                  $this->ApplicationObj->StudentsObject->GetEnumValue("Status",$student[ "StudentHash" ])." ".
                  $this->SortTime2Date($student[ "StudentHash" ][ "StatusDate1" ]),
                  $ntrailing
               )
            );
        }
 
        return $row;
    }


    //*
    //* function DaylyAbsencesStudentMonthTotalCells, Parameter list: $month,$student,$chs,$studchs
    //*
    //* Generates Absence row for student.
    //*

    function DaylyAbsencesStudentMonthTotalCells($month,$student,$chs,$studchs)
    {
        $ch=$per="-";
        if ($this->GetStudentStatus($student,$month))
        {
            if (isset($studchs[ "Month" ][ $month ]))
            {
                $ch=sprintf("%02d",$studchs[ "Month" ][ $month ]);

                $per=$this->CalcPercent
                (
                   $studchs[ "Month" ][ $month ],
                   $chs[ "Month" ][ $month ]
                );
            }
        }

        return $this->B(array($ch,$per));
    }

    //*
    //* function DaylyAbsencesStudentMonthsTotalCells, Parameter list: $month,$student,$chs,$studchs
    //*
    //* Generates Absence row for student.
    //*

    function DaylyAbsencesStudentMonthsTotalCells($student,$chs,$studchs)
    {
        $row=array();
        foreach ($this->ApplicationObj->DaylyMonths as $month)
        {
            $row=array_merge
            (
               $row,
               $this->DaylyAbsencesStudentMonthTotalCells($month,$student,$chs,$studchs)
             );
        }

        return $row;
    }

    //************** Semestral Data columns **************//


    //*
    //* function DaylyAbsencesStudentTrimesterTotalCells, Parameter list: $trimester,$student,$chs,$studchs
    //*
    //* Generates Absence row for student.
    //*

    function DaylyAbsencesStudentTrimesterTotalCells($trimester,$student,$chs,$studchs)
    {
        $date=$this->ApplicationObj->PeriodsObject->GetTrimesterEndDate($this->ApplicationObj->Period,$trimester);
        $date=$this->ApplicationObj->DatesObject->ID2SortKey($date);

        $ch=$per="-";
        if ($this->GetStudentStatus($student,"",$date))
        {
            if (isset($studchs[ "Semester" ][ $trimester ]))
            {
                $ch=sprintf("%02d",$studchs[ "Semester" ][ $trimester ]);
                $per=$this->CalcPercent
                (
                   $studchs[ "Semester" ][ $trimester ],
                   $chs[ "Semester" ][ $trimester ]
                );
            }
        }

        return $this->B(array($ch,$per));
    }

    //*
    //* function DaylyAbsencesStudentTrimestersTotalCells, Parameter list: $month,$student,$chs,$studchs
    //*
    //* Generates Absence row for student.
    //*

    function DaylyAbsencesStudentTrimestersTotalCells($student,$chs,$studchs)
    {
        $row=array();
        for ($trimester=1;$trimester<=$this->ApplicationObj->Disc[ "NAssessments" ];$trimester++)
        {
            $row=array_merge
            (
               $row,
               $this->DaylyAbsencesStudentTrimesterTotalCells($trimester,$student,$chs,$studchs)
            );
        }

        return $row;
    }

    //************** Period Data with final result **************//

     //*
    //* function DaylyAbsencesStudentResultCells, Parameter list: $student,$chs,$studchs
    //*
    //* Generates Absence row for student.
    //*

    function DaylyAbsencesStudentResultCells($student,$chs,$studchs)
    {
        $row=array();

        $ch=$per=$res="-";

        $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($this->ApplicationObj->Period[ "DayliesEnd" ]);
        if ($this->GetStudentStatus($student,"",$datekey))
        {
            $ch=$this->B(sprintf("%02d",$studchs[ "Period" ]));
            $rper="-";
            if ($chs[ "Period" ]>0)
            {
                $rper=100.0*$studchs[ "Period" ]/(1.0*$chs[ "Period" ]);
            }

            $per=$this->CalcPercent
            (
               $studchs[ "Period" ],
               $chs[ "Period" ]
            );

            $res="AP";
            if ($rper>$this->ApplicationObj->Disc[ "AbsencesLimit" ])
            {
                $res="RE";
            }

            $per=$this->B($per);
        }

        array_push($row,$ch,$per,$res);
        return $row;
    }



    //************** Whole student row, call emthods above**************//



      //*
    //* function DaylyAbsencesStudentRow, Parameter list: $edit,$n,$month,$student,$date,$chs
    //*
    //* Generates Absence row for student.
    //* $date, if not empty, is the day to maintain as edit.
    //*

    function DaylyAbsencesStudentRow($edit,$n,$month,$student,$date,$chs)
    {
        $studchs=$this->ReadDaylyStudent($chs,$student);
        $row=$this->DaylyAbsencesStudentCells($n,$student);
        $row=array_merge
        (
           $row,
           $this->DaylyAbsencesStudentMonthCells($edit,$n,$month,$student,$date,$chs,$studchs)
        );

        if ($edit==1 && $this->GetPOST("Save")==1)
        {
            $studchs=$this->ReadDaylyStudent($chs,$student);
        }

 
        $row=array_merge
        (
           $row,
           $this->DaylyAbsencesStudentMonthTotalCells($month,$student,$chs,$studchs)
        );

        $row=array_merge
        (
           $row,
           $this->DaylyAbsencesStudentTrimestersTotalCells($student,$chs,$studchs)
        );

        $row=array_merge
        (
           $row,
           $this->DaylyAbsencesStudentResultCells($student,$chs,$studchs)
        );

        return $row;
    }

     //*
    //* function DaylyAbsencesStudentStatsRow, Parameter list: $type,$n,$student,$cht,$chms
    //*
    //* Generates Absence row for student.
    //*

    function DaylyAbsencesStudentStatsRow($type,$n,$student,$chs)
    {
        $row=$this->DaylyAbsencesStudentCells($n,$student);

        $studchs=$this->ReadDaylyStudent($chs,$student);

        $row=$this->DaylyAbsencesStudentCells($n,$student);
        if ($type==0 || $type==1)
        {
            $row=array_merge
            (
               $row,
               $this->DaylyAbsencesStudentMonthsTotalCells($student,$chs,$studchs)
            );
        }

        if ($type==0 || $type==2)
        {
            $row=array_merge
            (
               $row,
               $this->DaylyAbsencesStudentTrimestersTotalCells($student,$chs,$studchs)
            );
        }

        $row=array_merge
        (
           $row,
           $this->DaylyAbsencesStudentResultCells($student,$chs,$studchs)
        );

        return $row;
    }
}

?>