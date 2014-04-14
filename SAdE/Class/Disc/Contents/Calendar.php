<?php


class ClassDiscContentsCalendar extends ClassDiscContentsSearch
{
    //Should be moved to Periods!!!!

    //*
    //* function SemesterCalendarWeekTitles, Parameter list: &$table,$period,$maxnmonths,$semester,$months
    //*
    //* Makes calender for $semester.
    //*

    function SemesterCalendarWeekTitles(&$table,$period,$maxnmonths,$semester,$months)
    {
        $titles=$this->WeekDays;
        array_unshift($titles,"Semana");

        $empties=array();
        foreach ($titles as $title) { array_push($empties,""); }

        $row=array($this->B($semester));
        $n=1;
        foreach ($months as $month)
        {
            array_push
            (
               $row,
               $this->MultiCell
               (
                  $this->Months[ intval($month)-1 ]." ".
                  $period[ "Year" ],
                  8
               )
            );
            $n++;
        }

        for (;$n<=$maxnmonths;$n++)
        {
            array_push($row,$this->MultiCell("",8));
        }
        array_push($table,$row);
           
        $row=array("");


        $nmonths=0;
        foreach ($months as $month)
        {
            $row=array_merge($row,$this->B($titles));
            $nmonths++;
        }

            
        for ($n=$nmonths;$n<$maxnmonths;$n++)
        {
            $row=array_merge($row,$empties);
        }

        array_push($table,$row);

        return $empties;
    }

    //*
    //* function SemesterCalendarWeekRow, Parameter list: $period,$maxnmonths,$semester,$w,$months,$empties
    //*
    //* Makes calender for $semeste week.
    //*

    function SemesterCalendarWeekRow($period,$maxnmonths,$semester,$w,$months,$empties)
    {
        $rrow=array("");
        $nmonths=0;
        foreach ($months as $month)
        {
            $rweeks=array_keys($this->Dates[ $semester ][ $month ]);
            if (!empty($rweeks[ $w ]))
            {
                $week=$rweeks[ $w ];

                array_push($rrow,$this->B(sprintf("%02d",$week)));

                foreach ($this->Dates[ $semester ][ $month ][ $week ] as $date)
                {
                    if (is_array($date))
                    {
                        array_push($rrow,sprintf("%02d",$date[ "Day" ]));
                    }
                    else
                    {
                        array_push($rrow,"-");
                    }
                }
            }
            else
            {
                array_push
                (
                   $rrow,
                   $this->B(sprintf("%02d",$w+1)),
                   $this->MultiCell("",7)
                );
            }

            $nmonths++;
        }

        for ($n=$nmonths;$n<$maxnmonths;$n++)
        {
            $rrow=array_merge($rrow,$empties);
        }

        return $rrow;
    }

    //*
    //* function SemesterCalendar, Parameter list: &$table,$period,$maxnmonths,$semester
    //*
    //* Makes calander for $semester.
    //*

    function SemesterCalendar(&$table,$period,$maxnmonths,$semester)
    {
        $months=array_keys($this->Dates[ $semester ]);
        $empties=$this->SemesterCalendarWeekTitles($table,$period,$maxnmonths,$semester,$months);

        $nweeksmax=0;
        foreach ($months as $month)
        {
            $rweeks=array_keys($this->Dates[ $semester ][ $month ]);
            $nweeksmax=$this->Max($nweeksmax,count($rweeks));
        }

        for ($w=0;$w<$nweeksmax;$w++)
        {
            $rrow=$this->SemesterCalendarWeekRow($period,$maxnmonths,$semester,$w,$months,$empties);
            array_push($table,$rrow);
         }
    }

    //*
    //* function DatesCalendar, Parameter list: $period 
    //*
    //* Splits dates.
    //*

    function DatesCalendar($period)
    {
        $maxnmonths=$this->SplitPeriodDates($period);

        $table=array
        (
           $this->B
           (
              array
              (
                 $this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle(),
                 "Meses"
              )
           )
        );

        for ($semester=1;$semester<=$period[ "NPeriods" ];$semester++)
        {
            $this->SemesterCalendar($table,$period,$maxnmonths,$semester);
        }

        print
            $this->H(1,"Calendário").
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

}

?>