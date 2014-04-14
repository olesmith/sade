<?php

class PeriodsMonths extends PeriodsDates
{
    //*
    //* function GetMonths, Parameter list: $period=array()
    //*
    //* Returns months in in Period
    //*

    function GetMonths($period=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period; }
        if (empty($period)) { $period=$this->ItemHash; }
        if (empty($period))
        {
            $period=$this->AplicationObj->PeriodsObject->ReadItem($this->GetGET("Period"));
        }

        return $this->ApplicationObj->DatesObject->MySqlUniqueColValues
        (
           "",
           "Month",
           $this->DatesSqlWhere($period),
           "",
           "Month"
        );
    }

    //*
    //* function GetMonthName, Parameter list: $month,$short=FALSE
    //*
    //* Returns name of month.
    //*

    function GetMonthName($month,$short=FALSE)
    {
        if ($short)
        {
            return $this->Months_Short[ $month-1 ];
        }
        else
        {
            return $this->Months[ $month-1 ];
        }
    }

    //*
    //* function MonthNames, Parameter list: $period=array()
    //*
    //* Returns months in Calendar.
    //*

    function MonthNames($period=array())
    {
        if (empty($period)) { $period=$this->ItemHash; }

        $months=$this->GetMonths($period);

        $rmonths=array();
        foreach ($months as $id => $month)
        {
            $rmonths[ $month ]=
                sprintf("%02d",$months[ $id ])."/".
                $period[ "Year" ];
        }

        return $rmonths;
    }


    //*
    //* function GetMonthNames, Parameter list: $ids=array(),$byid=TRUE
    //*
    //* Returns months in Calendar.
    //*

    function GetMonthNames($ids=array(),$byid=TRUE)
    {
        if (count($ids)==0) { $ids=$this->GetMonths(); }

        $names=array();
        foreach ($ids as $id => $month)
        {
            $year=substr($this->ItemHash[ "StartDate_SortKey" ],0,4);

            $name=$this->GetMonthName($month);
            if ($byid)
            {
                $names[ $month ]=$name;
            }
            else
            {
                array_push($names,$name);
            }
        }

        return $names;
    }


    //*
    //* function GetMonthDates, Parameter list: $month
    //*
    //* Returns dates in $month
    //*

    function GetMonthDates($month)
    {
        return $this->ApplicationObj->DatesObject->SelectHashesFromTable
        (
           "",
           array("Month" => $month,"Year" => substr($this->ItemHash[ "StartDate_SortKey" ],0,4)),
           array(),
           FALSE,
           "Date"
        );
    }

    //*
    //* function GetPeriodMonthDates, Parameter list: $month,$period=array()
    //*
    //* Returns dates within period, also in $month
    //*

    function GetPeriodMonthDates($month,$period=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period; }

        $where=
            "Month='".$month."' AND ".
            "SortKey>=".
            $this->ApplicationObj->DatesObject->ID2SortKey($period[ "DayliesStart" ]).
            " AND ".
            "SortKey<=".
            $this->ApplicationObj->DatesObject->ID2SortKey($period[ "DayliesEnd" ]).
            "";

        return $this->ApplicationObj->DatesObject->SelectHashesFromTable
        (
           "",
           $where,
           array(),
           FALSE,
           "Date"
        );
    }

    //*
    //* function MonthsMenu, Parameter list: $args,$period=array(),$month=NULL
    //*
    //* Creates a menu with links to months in period.
    //* Url generated based on $args.
    //*

    function MonthsMenu($args,$period=array(),$month=FALSE)
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period; }
        
        $months=$this->GetMonths($period);

        if (!$month) { $month=$this->GetGET("Month"); }
        foreach (array_keys($months) as $id)
        {
            if ($months[ $id ]!=$month)
            {
                unset($args[ "Semester" ]);
                unset($args[ "Date" ]);
                $args[ "Month" ]=$months[ $id ];

                $months[ $id ]=$this->Href
                (
                   "?".$this->Hash2Query($args),
                   $this->GetMonthName($months[ $id ],TRUE)
                );
            }
            else
            {
                $months[ $id ]=$this->GetMonthName($months[ $id ],TRUE);
             }
        }

        return $this->Center("[ ".join(" | ",$months)." ]");
    }


    //*
    //* function HtmlCalendarMonth, Parameter list: $month
    //*
    //* Generates Html Calendar table for $month.
    //*

    function HtmlCalendarMonth($month)
    {
        $dates=$this->GetMonthDates($month);
        $wno=1;

        $titles=$this->B($this->WeekDays);
        array_unshift($titles,"&nbsp;");

        $table=array
        (
           array
           (
              $this->H(3,$this->GetMonthName($month)),
           ),
           $titles,
        );

        $row=array($this->B($wno));

        $firstdate=$dates[0];

        $sums=array("&nbsp;");
        for ($n=1;$n<=7;$n++)
        {
            $sums[ $n ]=0;
        }

        for ($n=0;$n<$firstdate[ "WeekDay" ]-1;$n++)
        {
            array_push($row,"&nbsp;");
        }

        foreach ($dates as $date)
        {
            array_push
            (
               $row,
               $this->GenDateCell($date)
            );

            $sums[ $date[ "WeekDay" ] ]++;

            if ($date[ "WeekDay" ]==7)
            {
                array_push($table,$row);
                $wno++;
                $row=array($this->B($wno));
            }
        }

        $lastdate=$dates[ count($dates)-1 ];
        for ($n=$lastdate[ "WeekDay" ]+1;$n<=7;$n++)
        {
            array_push($row,"&nbsp;");
        }
        

        if (count($row)>1) { array_push($table,$row); }

        array_push($table,$this->B($sums));

        return $table;
    }


    //*
    //* function SeparateDaysPerMonth, Parameter list: $dates
    //*
    //* Takes a list of dates, and sepeates them per month.
    //*

    function SeparateDaysPerMonth($dates)
    {
        $rdates=array();
        foreach ($dates as $date)
        {
            if (preg_match('/^\d\d\d\d(\d\d)\d\d$/',$date[ "SortKey" ],$matches))
            {
                $month=sprintf("%02d",$matches[1]);

                if (empty($rdates[ $month ])) { $rdates[ $month ]=array(); }

                array_push($rdates[ $month ],$date);
            }
                          
        }

        return $rdates;
    }

    //*
    //* function SeparateDaysPerWeekNos, Parameter list: $dates
    //*
    //* Takes a list of dates, and sepeates them per week date.
    //*

    function SeparateDaysPerWeekNos($dates)
    {
        $empty=array("","","","","","","");
        $rdates=array();
        foreach ($dates as $date)
        {
            $weekno=$date[ "WeekNo" ];
            $weekday=$date[ "WeekDay" ];

            if (empty($rdates[ $weekno ]))
            {
                $rdates[ $weekno ]=$empty;
            }

            $rdates[ $weekno ][ $weekday-1 ]=$date;
        }

        return $rdates;
    }
}

?>