<?php


class ClassDiscAbsencesTitles extends ClassDiscAbsencesRow
{
    var $StudentDataActions=array();

    //*
    //* function DaylyAbsencesStudentDataTitles, Parameter list:
    //*
    //* Generates titles of student data.
    //*

    function DaylyAbsencesStudentDataTitles()
    {
        $row=array("No.");

        if (!$this->LatexMode)
        {
            $action="";
            if (preg_match('/^(Admin|Clerk|Secretary)$/',$this->ApplicationObj->Profile))
            {
                $action="Edit";
            }
            elseif (preg_match('/^(Teacher|Coordinator)$/',$this->ApplicationObj->Profile))
            {
                $action="Show";
            }

            if (!empty($action))
            {
                array_push($row,"");
                array_push($this->StudentDataActions,$action);
            }
        }

        $n=1;
        foreach ($this->StudentData as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->StudentsObject->GetDataTitle($data)
            );
        }

        return $this->B($row);
    }

    //*
    //* function DaylyAbsencesStudentTitleRows, Parameter list: 
    //*
    //* Generates student datatitle row of Absences table.
    //*

    function DaylyAbsencesStudentTitleRows(&$table)
    {
        //Must be first - updates $this->StudentDataActions
        $table[3]=array_merge
        (
           $table[3],
           $this->DaylyAbsencesStudentDataTitles()
        );

        array_push
        (
           $table[0],
           $this->MultiCell
           (
               "",
               count($this->StudentData)+
               count($this->StudentDataActions)+
               1
           )
        );

       array_push
        (
           $table[1],
           $this->MultiCell
           (
               "",
               count($this->StudentData)+
               count($this->StudentDataActions)+
               1
           )
        );

        array_push
        (
           $table[2],
           $this->MultiCell
           (
               "",
               count($this->StudentData)
           ),
           $this->B("Pesos"),
           $this->MultiCell
           (
               "",
               count($this->StudentDataActions)
           )
        );

    }


    //*
    //* function DaylyAbsencesMonthTitleRows, Parameter list: $month,&$table
    //*
    //* Generates dates title row of Absences table.
    //*

    function DaylyAbsencesMonthTitleRows($month,&$table)
    {
        $ncontents=0;
        $cht=0;
        foreach ($this->ApplicationObj->Contents as $semestre => $contents)
        {
            $dates=array();
            $sdates=array();
            foreach ($contents as $content)
            {
                $datekey=$content[ "DateKey" ];
                $trimester=$content[ "Semester" ];

                if (empty($dates[ $datekey ])) { $dates[ $datekey ]=0; }
                $dates[ $datekey ]++;
            }

            array_push
            (
               $table[0],
               $this->MultiCell
               (
                  $this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle()." ".$trimester,
                  count($contents)
               )
            );

            foreach ($contents as $content)
            {
                array_push
                (
                   $table[2],
                   $this->B($content[ "Weight" ])
                );

                $cht+=$content[ "Weight" ];
                $ncontents++;
            }

            foreach ($dates as $date => $ndates)
            {
                array_push
                (
                   $table[3],
                   $this->MultiCell
                   (
                      preg_replace('/^\d\d\d\d\d\d/',"",$date),
                      $ndates
                   )
                );
            }
        }

        array_push
        (
           $table[1],
           $this->MultiCell
           (
              $this->Months[ $month-1 ],
              $ncontents+2 //2 cells for month totals
           )
        );
    }


    //*
    //* function DaylyAbsencesStudentMonthTotalRows, Parameter list: $month,$chs,&$table
    //*
    //* Generates dates title row of Absences table.
    //*

    function DaylyAbsencesStudentMonthTotalRows($month,$chs,&$table)
    {
        array_push
        (
           $table[0],
           $this->MultiCell
           (
               "",
               2
           )
        );

        $ch="-";
        if (isset($chs[ "Month" ][ $month ]))
        {
            $ch=sprintf("%02d",$chs[ "Month" ][ $month ]);
        }

        array_push
        (
           $table[2],
           $this->MultiCell
           (
             $this->B($ch),
             2
           )
        );

        array_push
        (
           $table[3],
           $this->B($this->ApplicationObj->Sigma),
           $this->B($this->ApplicationObj->Percent)
        );
    }


    //*
    //* function DaylyAbsencesStudentResultRows, Parameter list: $chs,&$table
    //*
    //* Generates dates title row of Absences table.
    //*

    function DaylyAbsencesStudentResultRows($chs,&$table)
    {
        array_push
        (
           $table[0],
           $this->MultiCell
           (
               "",
               3
           )
        );
        array_push
        (
           $table[1],
           $this->MultiCell
           (
               "",
               3
           )
        );

        array_push
        (
           $table[2],
           $this->MultiCell
           (
               $this->B(sprintf("%02d",$chs[ "Period" ])),
               3
           )
        );

        array_push
        (
           $table[3],
           $this->B($this->ApplicationObj->Sigma),
           $this->B($this->ApplicationObj->Percent),
           $this->B("R")
        );

    }


    //*
    //* function DaylyAbsencesTableTitleRows, Parameter list: $month,$chs
    //*
    //* Creates 4 row header for Absence Table.
    //*

    function DaylyAbsencesTableTableTitleRows($month,$chs)
    {
        $table=array
        (
           array(),
           array(),
           array(),
           array(),
        );

        $this->DaylyAbsencesStudentTitleRows($table);
        $this->DaylyAbsencesMonthTitleRows($month,$table);
        $this->DaylyAbsencesStudentMonthTotalRows($month,$chs,$table);
        $this->DaylyAbsencesStudentStatsTrimestersRows($chs,$table);
        $this->DaylyAbsencesStudentResultRows($chs,$table);

        return $table;
    }


    //**************** Stats tables ****************//


    //************** Student Data columns **************//

    //*
    //* function DaylyAbsencesStudentStatsTitleRows, Parameter list: 
    //*
    //* Generates dates title row of Absences table.
    //*

    function DaylyAbsencesStudentStatsTitleRows($type,&$table)
    {
        $title="";
        if ($type==1) { $title="Meses"; }
        if ($type==2) { $title=$this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle()."s"; }

        array_push
        (
           $table[0],
           $this->MultiCell
           (
               "",
               count($this->StudentData)
           ),
           $this->B("Aulas Dadas:")
        );

       array_push
        (
           $table[1],
           $this->MultiCell
           (
               "",
               count($this->StudentData)
           ),
           $this->B($title)
        );

        $table[2]=array_merge
        (
           $table[2],
           $this->DaylyAbsencesStudentDataTitles()
        );
    }


    //************** Month Data columns **************//


    //*
    //* function DaylyAbsencesStudentStatsMonthRows, Parameter list: $month,$chs,&$table
    //*
    //* Generates dates title row of Absences table.
    //*

    function DaylyAbsencesStudentStatsMonthRows($month,$chs,&$table)
    {
        $ch="-";
        if (isset($chs[ "Month" ][ $month ]))
        {
            $ch=sprintf("%02d",$chs[ "Month" ][ $month ]);
        }

        array_push
        (
           $table[0],
           $this->MultiCell
           (
               $this->B($ch),
               2
           )
        );

        array_push
        (
           $table[1],
           $this->MultiCell
           (
               $this->Months[ $month-1 ],
               2
           )
        );

        array_push
        (
           $table[2],
           $this->B($this->ApplicationObj->Sigma),
           $this->B($this->ApplicationObj->Percent)
        );
    }


    //*
    //* function DaylyAbsencesStudentStatsMonthsRows, Parameter list: $chs,&$table
    //*
    //* Generates dates title row of Absences table.
    //*

    function DaylyAbsencesStudentStatsMonthsRows($chs,&$table)
    {
        foreach ($this->ApplicationObj->DaylyMonths as $month)
        {
            $this->DaylyAbsencesStudentStatsMonthRows($month,$chs,$table);
        }
    }


    //************** Semestral Data columns **************//


    //*
    //* function DaylyAbsencesStudentStatsTrimesterRows, Parameter list: $trimester,$chs,&$table
    //*
    //* Generates dates title row of Absences table.
    //*

    function DaylyAbsencesStudentStatsTrimesterRows($trimester,$chs,&$table)
    {
        $ch="-";
        if (isset($chs[ "Semester" ][ $trimester ]))
        {
            $ch=sprintf("%02d",$chs[ "Semester" ][ $trimester ]);
        }

        array_push
        (
           $table[0],
           $this->MultiCell
           (
               $this->B($ch),
               2
           )
        );

        array_push
        (
           $table[1],
           $this->MultiCell
           (
               $this->B
               (
                  $this->SUB
                  (
                   $this->ApplicationObj->PeriodsObject->PeriodSubPeriodsLetter(),
                     $trimester
                  )
               ),
               2
           )
        );

        array_push
        (
           $table[2],
           $this->B($this->ApplicationObj->Sigma."F"),
           $this->B($this->ApplicationObj->Percent)
        );

        if (!empty($table[3]))
        {
            array_push
            (
               $table[3],
               $this->MultiCell("",2)
            );
        }
    }


    //*
    //* function DaylyAbsencesStudentStatsTrimestersRows, Parameter list: $chs,&$table
    //*
    //* Generates dates title row of Absences table.
    //*

    function DaylyAbsencesStudentStatsTrimestersRows($chs,&$table)
    {
        for ($trimester=1;$trimester<=$this->ApplicationObj->Disc[ "NAssessments" ];$trimester++)
        {
            $this->DaylyAbsencesStudentStatsTrimesterRows($trimester,$chs,$table);
        }
    }

    //*
    //* function DaylyAbsencesStudentStatsResultRows, Parameter list: $chs,&$table
    //*
    //* Generates dates title row of Absences table.
    //*

    function DaylyAbsencesStudentStatsResultRows($chs,&$table)
    {
        array_push
        (
           $table[0],
           $this->MultiCell
           (
               $this->B(sprintf("%02d",$chs[ "Period" ])),
               3
           )
        );

        array_push
        (
           $table[1],
           $this->MultiCell
           (
               $this->B($this->ApplicationObj->Sigma."S"),
               3
           )
        );

        array_push
        (
           $table[2],
           $this->B($this->ApplicationObj->Sigma."F"),
           $this->B($this->ApplicationObj->Percent),
           $this->B("R")
        );
    }

    //*
    //* function DaylyAbsencesStatsTitleRows, Parameter list: $type,$chs
    //*
    //* Creates title rows of Stats table.
    //*

    function DaylyAbsencesStatsTitleRows($type,$chs)
    {
        $table=array
        (
           array(),
           array(),
           array(),
        );

        $this->DaylyAbsencesStudentStatsTitleRows($type,$table);
        if ($type==0 || $type==1)
        {
            $this->DaylyAbsencesStudentStatsMonthsRows($chs,$table);
        }

        if ($type==0 || $type==2)
        {
            $this->DaylyAbsencesStudentStatsTrimestersRows($chs,$table);
        }

        $this->DaylyAbsencesStudentStatsResultRows($chs,$table);

        return $table;
    }
}

?>