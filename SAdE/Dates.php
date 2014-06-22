<?php

include_once("../MySql2/Unicity.php");


class Dates extends Unicity
{

    //*
    //* Variables of Dates class
    //*

    var $DateCellDefs=array
    (
           0 => array
           (
              "Text" => "Dia de Aula",
              "Class" => "Lessonday",
           ),
           4 => array
           (
              "Text" => "Feriado",
              "Class" => "Holiday",
           ),
           5 => array
           (
              "Text" => "Recesso",
              "Class" => "Recessday",
           ),
           6 => array
           (
              "Text" => "Sábado Letivo",
              "Class" => "Saturday",
           ),
           7 => array
           (
              "Text" => "Domingo",
              "Class" => "Sunday",
           ),
    );


    //*
    //*
    //* Constructor.
    //*

    function Dates($args=array())
    {
        $this->Hash2Object($args);
        $this->Sort=array("SortKey");
        $this->AlwaysReadData=array("Day","Month","Year","SortKey");
    }

    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        $this->ItemData[ "Month" ][ "Values" ]=$this->Months;
        $this->ItemData[ "WeekDay" ][ "Values" ]=$this->WeekDays;
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ItemData[ "Year" ][ "SearchDefault" ]=$this->CurrentYear();
    }


    //*
    //* function DateID2Name, Parameter list: $id,$key
    //*
    //* Returns name of date with ID $id.
    //*

    function DateID2Key($id,$key)
    {
        if ($id==0) { return "-"; }

        return $this->MySqlItemValue
        (
           "",
           "ID",$id,
           $key,
           TRUE
        );
    }
    //*
    //* function DateID2Name, Parameter list: $id
    //*
    //* Returns name of date with ID $id.
    //*

    function DateID2Name($id)
    {
        if ($id==0) { return "-"; }

        $comps=preg_split('/\s+/',$this->DateID2Key($id,"Name"));
        return join(", ",array_reverse($comps));
    }

    //*
    //* function DateID2SortKey, Parameter list: $id
    //*
    //* Returns name of date with ID $id.
    //*

    function DateID2SortKey($id)
    {
        if ($id==0) { return 0; }

        return $this-> DateID2Key($id,"SortKey");
    }

    //*
    //* function ReadDate, Parameter list: $id,$period=array(),$datas=array()
    //*
    //* Reads date with $id. If $period (or $this->ApplicationObj->Period)
    //*  is set, calls $this->ApplicationObj->PeriodsObject->
    //*

    function ReadDate($id,$period=array(),$datas=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period; }

        $date=$this->SelectUniqueHash
        (
           "",
           array("ID" => $id),
           FALSE,
           $datas
        );

        if (!empty($period))
        {
            $this->ApplicationObj->PeriodsObject->Date2Trimester($period,$date);
        }

        return $date;
    }

    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        if ($this->GetGET("ModuleName")!="Dates") { return $item; }

        $this->SetWeekDay($item);

        return $item;
    }



    //*
    //* function SetWeekDay, Parameter list: $item
    //*
    //* Updates weekday and julian day.
    //*

    function SetWeekDay(&$item)
    {
        $item[ "JulianDay" ]=cal_to_jd(CAL_GREGORIAN,$item[ "Month" ],$item[ "Day" ],$item[ "Year" ]);
        $item[ "WeekDay" ]=jddayofweek($item[ "JulianDay" ]);

        if ($item[ "WeekDay" ]==0) { $item[ "WeekDay" ]=7; }

        $item[ "Name" ]=sprintf
        (
           "%02d/%02d/%d",
           $item[ "Day" ],$item[ "Month" ],$item[ "Year" ]
        ).", ".
        $this->ItemData[ "WeekDay" ][ "Values" ][ $item[ "WeekDay" ]-1 ];

        $item[ "Date" ]=sprintf
        (
           "%02d/%02d/%d",
           $item[ "Day" ],$item[ "Month" ],$item[ "Year" ]
        );

        if (!isset($item[ "Type" ])) { $item[ "Type" ]=1; }
        if ($item[ "WeekDay" ]==7) { $item[ "Type" ]=3; }
        if ($item[ "WeekDay" ]==6 && $item[ "Type" ]==1) { $item[ "Type" ]=2; }

        $item[ "SortKey" ]=
             $item[ "Year" ].
             sprintf("%02d",$item[ "Month" ]).
             sprintf("%02d",$item[ "Day" ]);

        if (isset($item[ "ID" ]))
        {
            $this->MySqlSetItemValues
            (
               "",
               array("JulianDay","WeekDay","Name","SortKey","Type","Date"),
               $item
            );
        }
    }


    //*
    //* function ID2SortKey, Parameter list: $date
    //*
    //* Returns sorkey of date with ID $date.
    //* If $date is array, use $date[ "ID" ].
    //*

    function ID2SortKey($date)
    {
        $dateid=$date;
        if (is_array($dateid) && !empty($date[ "ID" ])) { $dateid=$date[ "ID" ]; }

        $datehash=$this->ApplicationObj->DatesObject->SelectuniqueHash
        (
           "",
           array("ID" => $dateid),
           TRUE,
           array("SortKey")
        );

        $sortkey=0;
        if (!empty($datehash[ "SortKey" ]))
        {
            $sortkey=$datehash[ "SortKey" ];
        }

        return $sortkey;
    }

    //*
    //* function SortKey2ID, Parameter list: $datekey
    //*
    //* Returns id of date with SortKey $datekey.
    //* If $date is array, use $date[ "SortKey" ].
    //*

    function SortKey2ID($datekey)
    {
        if (!empty($datekey[ "SortKey" ])) { $dateid=$datekey[ "SortKey" ]; }

        $datehash=$this->ApplicationObj->DatesObject->SelectuniqueHash
        (
           "",
           array("SortKey" => $datekey),
           TRUE,
           array("ID")
        );

        $sortkey=0;
        if (!empty($datehash[ "ID" ]))
        {
            $sortkey=$datehash[ "ID" ];
        }

        return $sortkey;
    }

    //*
    //* function GetTodayDatesItem, Parameter list: &$msg,&$item=array()
    //*
    //* Returns today's Date record.
    //*

    function GetTodayDatesItem()
    {
        return $this->SelectUniqueHash
        (
           "",
           array("SortKey" => $this->TimeStamp2DateSort()),
           TRUE
        );
    }

    //*
    //* function GetDatesInRange, Parameter list: $startid,$endid,$datas=array()
    //*
    //* Returns dates within date range: $startid-$endid.
    //* Converts to sortkeys, and generates where clase.
    //*

    function GetDatesInRange($startid,$endid,$datas=array())
    {
        $startkey=$this->ApplicationObj->DatesObject->ID2SortKey($startid);
        $endkey=$this->ApplicationObj->DatesObject->ID2SortKey($endid);

        return $this->ApplicationObj->DatesObject->SelectHashesFromTable
        (
           "",
           "SortKey>='".$startkey."'"." AND "."SortKey<='".$endkey."'",
           $datas,
           FALSE,
           "SortKey"
        );
    }

    //*
    //* function GetDatesInRangeNoEnd, Parameter list: $startid,$endid,$datas=array()
    //*
    //* Returns dates within date range: $startid<$sortkey<$endid.
    //* Converts to sortkeys, and generates where clase.
    //*

    function GetDatesInRangeNoEnd($startid,$endid,$datas=array())
    {
        $startkey=$this->ApplicationObj->DatesObject->ID2SortKey($startid);
        $endkey=$this->ApplicationObj->DatesObject->ID2SortKey($endid);

        return $this->ApplicationObj->DatesObject->SelectHashesFromTable
        (
           "",
           "SortKey>='".$startkey."'"." AND "."SortKey<'".$endkey."'",
           $datas,
           FALSE,
           "SortKey"
        );
    }


    //*
    //* function GetLastDateInMonth, Parameter list: $dateid,$month
    //*
    //* Adds $nmonths to $dateid, finding SortKey, return ID in DB.
    //*

    function GetLastDateInMonth($year,$month)
    {
        $dates=$this->SelectHashesFromTable
        (
           "",
           array
           (
              "Year" => $year,
              "Month" => $month,
           ),
           array("ID","Date"),
           FALSE,
           "SortKey"
        );

        $dateid=0;
        $max=0;
        foreach ($dates as $date)
        {
            if ($date[ "Date" ]>$max)
            {
                $max=$date[ "Date" ];
                $dateid=$date[ "ID" ];
            }
        }

        return $dateid;
        
    }


    //*
    //* function GetFirstDateInMonth, Parameter list: $dateid,$month
    //*
    //* Returns id firsst date in month.
    //*

    function GetFirstDateInMonth($year,$month)
    {
        $dates=$this->SelectHashesFromTable
        (
           "",
           array
           (
              "Year" => $year,
              "Month" => $month,
           ),
           array("ID","Date"),
           FALSE,
           "SortKey"
        );

        $dateid=0;
        $min=-1;
        foreach ($dates as $date)
        {
            if ($min<0 || $date[ "Date" ]<$min)
            {
                $min=$date[ "Date" ];
                $dateid=$date[ "ID" ];
            }
        }

        return $dateid;
        
    }




    //*
    //* function AddNMonhts, Parameter list: $dateid,$nmonths
    //*
    //* Adds $nmonths to $dateid, finding SortKey, return ID in DB.
    //*

    function AddNMonhts($dateid,$nmonths)
    {
        $datekey=$this->ID2SortKey($dateid);

        $dateid=0;
        if (preg_match('/^(\d\d\d\d)(\d\d)(\d\d)/',$datekey,$matches))
        {
            $year=$matches[1];
            $month=$matches[2];
            $day=$matches[3];
            $month+=$nmonths;
            while ($month>12) { $year++; $month-=12; }

            $datekey=$year.sprintf("%02d",$month).sprintf("%02d",$day);
            $dateid=$this->SortKey2ID($datekey);
        }

        return $dateid;
        
    }


    //*
    //* function AddNDays, Parameter list: $dateid,$ndays
    //*
    //* Adds $ndays days to $dateid, finding SortKey, return ID in DB.
    //*

    function AddNDays($dateid,$ndays)
    {
        $datekey=$this->ID2SortKey($dateid);

        $ndates=array
        (
           1 => 31,
           2 => 28,
           3 => 31,
           4 => 30,
           5 => 31,
           6 => 30,
           7 => 31,
           8 => 31,
           9 => 30,
          10 => 31,
          11 => 30,
          12 => 31,
        );

        $dateid=0;
        if (preg_match('/^(\d\d\d\d)(\d\d)(\d\d)/',$datekey,$matches))
        {
            $year=$matches[1];
            $month=intval($matches[2]);
            $day=$matches[3];

            $isleap=date('L', strtotime($year."-01-01"));
            if ($isleap) { $ndates[ 2 ]=29; }

            while ($ndays>$ndates[ $month ])
            {
                $ndays-=$ndates[ $month ];
                $month++;
                if ($month>12)
                {
                  $year++;
                  $month=1;
                }
            }

            $day+=$ndays;
            if ($day>$ndates[ $month ])
            {
                $day-=$ndates[ $month ];
                $month++;
                if ($month>12)
                {
                  $year++;
                  $month=1;
                }
            }

            $datekey=$year.sprintf("%02d",$month).sprintf("%02d",$day);
            $dateid=$this->SortKey2ID($datekey);
        }

        return $dateid;
        
    }





    //*
    //* function GenerateMonthTable, Parameter list: $month,$markwdays=array()
    //*
    //* Generates table showing dates in month $month.
    //* If $markwdays are given, weekdays will be marked.
    //*

    function GenerateMonthTable($month,$markwdays=array())
    {
        $this->ReadPeriodData();
        $rmarkwdays=array();
        foreach ($markwdays as $id => $wday) { $rmarkwdays[ $wday ]=$wday; }

        $dates=$this->SelectHashesFromTable
        (
           "",
           "Month='".$month."'",
           array("Date","Month","WeekDay","Type","SortKey","Text"),
           FALSE,
          "SortKey"
        );

        $titles=array("");
        for ($n=1;$n<=7;$n++)
        {
            if (!empty($markwdays) && !isset($rmarkwdays[ $n ])) { continue; }

            array_push($titles,$this->WeekDays[ $n-1 ]);
        }

        $titles=$this->B($titles);

        $table=array
        (
           $this->H
           (
              5,
              $this->Months[ $month-1 ]." ".
              preg_replace('/\..*$/',"",$this->Period)
           ),
           $titles
        );

        $wday1=$dates[0][ "WeekDay" ];

        $weekno=1;
        $row=array($this->B($weekno.":"));
        $weekno++;

        $rmarkwdays=array();
        foreach ($markwdays as $id => $wday) { $rmarkwdays[ $wday ]=$wday; }

        if (TRUE)
        {
            for ($w=1;$w<$dates[0][ "WeekDay" ];$w++)
            {
                if (!empty($markwdays) && !isset($rmarkwdays[ $w ])) { continue; }
                array_push($row,"&nbsp;");
            }

        }


        //Number of weekdays and lecture days counter for each month
        $counts=array();
        $rcounts=array();
        for ($n=1;$n<=7;$n++)
        {
            $counts[ $n ]=0;
            $rcounts[ $n ]=0;
        }

        $lessondays=array();
        $holidays=array();

        while (count($dates)>0)
        {
            $date=array_shift($dates);

            //Reached monday, new row
            if ($date[ "WeekDay" ]==1 && count($row)>1)
            {
                array_push($table,$row);
                $row=array($this->B($weekno.":"));
                $weekno++;
            }

            if (!empty($markwdays) && !isset($rmarkwdays[ $date[ "WeekDay" ] ])) { continue; }

            $cell=sprintf("%02d",$date[ "Date" ]);
            $lectureday=FALSE;

            if ($date[ "Type" ]==4)
            {
                $cell=$this->GenDateCell($date,$date[ "Type" ]);
                array_push($holidays,$date);
            }
            elseif ($date[ "Type" ]==5)
            {
                $cell=$this->GenDateCell($date,$date[ "Type" ]);
                array_push($holidays,$date);
            }
            elseif (isset($rmarkwdays[ $date[ "WeekDay" ] ]))
            {
                $cell=$this->GenDateCell($date,0);
                $lectureday=TRUE;
            }
            elseif ($date[ "WeekDay" ]==7)
            {
                $cell=$this->GenDateCell($date,7);
            }
            elseif ($date[ "WeekDay" ]==6)
            {
                $cell=$this->GenDateCell($date,6);
                $lectureday=TRUE;
            }
            else
            {
                 $lectureday=TRUE;
            }

            $counts[ $date[ "WeekDay" ] ]+=1;
            if ($lectureday)
            {
                $rcounts[ $date[ "WeekDay" ] ]+=1;
            }

            array_push($row,$cell);
        }

        if (count($row)>0)
        {
            $max=7;
            if (!empty($markwdays)) { $max=count($markwdays)+1; }

            for ($w=count($row);$w<$max;$w++)
            {
                array_push($row,"&nbsp;");
            }

            array_push($table,$row);
        }

        $wdays=array_keys($rcounts);
        sort($wdays);

        $row=array("&Sigma;");
        foreach ($wdays as $wday)
        {
            if (!empty($markwdays) && !isset($rmarkwdays[ $wday ])) { continue; }

            if ($counts[ $wday ]==0) { $counts[ $wday ]="-"; }
            if ($rcounts[ $wday ]==0) { $rcounts[ $wday ]="-"; }
            array_push($row,$rcounts[ $wday ]."/".$counts[ $wday ]);
           
        }
        array_push($table,array(),$this->B($row));

        foreach ($holidays as $id => $date)
        {
            $holidays[ $id ]=array($date[ "Date" ].": ",$date[ "Text" ]);
        }

        array_push
        (
           $table,
           array
           (
              $this->HtmlTable
              (
                 "",
                 $holidays,
                 array
                 (
                    "BORDER" => "0",
                    "ALIGN" => 'left'
                 )
              ),
           )
        );

        return $this->HtmlTable("",$table);
    }

    //*
    //* function ShouldDisableDate, Parameter list: $date
    //*
    //* Returns true if we should disable data, false otherwise.
    //* Registered as FieldMethod in $this->ItemData[ "Date" ].
    //*

    function ShouldDisableDate($date)
    {
        if ($date[ "Type" ]==4 || $date[ "Type" ]==5)
        {
            return TRUE;
        }

        return FALSE;
    }

    //*
    //* function ReadMonthLectureDays, Parameter list: $month
    //*
    //* Reads lecture dates in month $month.
    //*

    function ReadMonthLectureDays($month,$markwdays=array())
    {
        $this->ReadPeriodData();

        $dates=$this->SelectHashesFromTable
        (
           "",
           "Month='".$month."'",
           array("ID","Date","Month","WeekDay","Type","SortKey","Text"),
           FALSE,
          "SortKey"
        );

        $rmarkwdays=array();
        foreach ($markwdays as $id => $wday) { $rmarkwdays[ $wday ]=$wday; }

        $lessondays=array();
        while (count($dates)>0)
        {
            $date=array_shift($dates);

            if (
                (count($rmarkwdays)==0 || isset($rmarkwdays[ $date[ "WeekDay" ] ]))
                &&
                $date[ "Type" ]!=4
                &&
                $date[ "Type" ]!=5
               )
            {
                if (
                    $date[ "SortKey" ]>=$this->LessonsStartDate
                    &&
                    $date[ "SortKey" ]<=$this->LessonsEndDate
                   )
                {
                    if (!$this->ShouldDisableDate($date))
                    {
                        array_push($lessondays,$date);
                    }
                }
            }
        }

        return $lessondays;
    }

    //*
    //* function GenerateMonthLectureDays, Parameter list: $month
    //*
    //* Generates table showing lecture dates in month $month.
    //*

    function GenerateMonthLectureDays($month,$markwdays=array(),&$nlessons)
    {
        $this->ReadPeriodData();

        $dates=$this->SelectHashesFromTable
        (
           "",
           "Month='".$month."'",
           array("Date","Month","WeekDay","Type","SortKey","Text"),
           FALSE,
          "SortKey"
        );

        $rmarkwdays=array();
        foreach ($markwdays as $id => $wday) { $rmarkwdays[ $wday ]=$wday; }

        $lessondays=$this->ReadMonthLectureDays($month,$markwdays);

        $entries=array();
        foreach ($lessondays as $date)
        {
            array_push
            (
               $entries,
               sprintf("%02d. ",$nlessons).
               $this->WeekDays[ $date[ "WeekDay" ]-1 ]." ".
               sprintf
               (
                  "%02d",
                  $date[ "Date" ]
               )."/".
               sprintf
               (
                  "%02d",
                  $date[ "Month" ]
               )
            );

            $nlessons++;
        }

        return $entries;
    }


    //*
    //* function MakeCalendarTable, Parameter list: $edit,$wdays=array()
    //*
    //* Prints calendar, marks weekdays in $wdays array..
    //*

    function MakeCalendarTable($edit=0,$wdays=array())
    {
        $months=$this->MySqlUniqueColValues
        (
           "",
           "Month",
           "",
           "SortKey"
        );

        $tables=array($this->Legend());

        $row1=array();
        $row2=array();
        $nlessons=1;

        $nmonths=1;
        foreach ($months as $id => $month)
        {
            array_push
            (
               $row1,
               $this->GenerateMonthTable($month,$wdays,$nlessons)
            );

            if (count($wdays)>0)
            {
                array_push
                (
                   $row2,
                   join
                   (
                      "<BR>",
                      $this->GenerateMonthLectureDays($month,$wdays,$nlessons)
                   )
                );
            }

            if ($nmonths==6 && empty($wdays))
            {
                array_push($tables,$row1);
                if (count($wdays)>0)
                {
                    array_push($tables,$row2);
                }

                $row1=array();
                $row2=array();
                $nmonths=0;
            }

            $nmonths++;
        }

        for ($n=count($row1);$n<=7;$n++)
        {
            array_push($row1,"");
            array_push($row2,"");
        }

        array_push($tables,$row1);
        if (count($wdays)>0)
        {
            array_push($tables,$row2);
        }

        return array($this->HtmlTable("",$tables));

    }

    //*
    //* function HandleCalendar, Parameter list: $item=array()
    //*
    //* Prints calendar for all dates
    //*

    function HandleCalendar()
    {
        $edit=0;
        print
            $this->H(2,"Calendário Acadêmico, ".$this->Period).
            $this->HtmlTable("",$this->MakeCalendarTable($edit,array()));

    }

    //*
    //* function ReadLessonDates, Parameter list:
    //*
    //* Reads dates within start/end dates of 
    //*

    function ReadLessonDates($datas=array(),$lecturedatesonly=FALSE,$noholidays=FALSE)
    {
        if (count($datas)==0)
        {
            $datas=array("ID","Day","Month","Year","WeekDay","Type","Text","SortKey");
       }

        $wheres=array();
        if ($lecturedatesonly)
        {
            array_push
            (
               $wheres,
               "SortKey>=".
               $this->LessonsStartDate,
               "SortKey<=".
               $this->LessonsEndDate
            );
        }

        if ($noholidays)
        {
            array_push
            (
               $wheres,
               "Type!=4",
               "Type!=5"
            );
        }

        $dates=$this->SelectHashesFromTable
        (
           "",
           join(" AND ",$wheres),
           $datas,
           FALSE,
           "SortKey"
        );

        $rdates=array();
        foreach ($dates as $date)
        {
            $rdates[ $date[ "ID" ] ]=$date;
        }

        return $rdates;
    }


    //*
    //* function InsertDateUnique, Parameter list: $date,$mon,$year,&$weekno
    //*
    //* Inserts date in table.
    //*

    function InsertDateUnique($date,$mon,$year,&$weekno)
    {
        $datekey=$year.sprintf("%02d",$mon).sprintf("%02d",$date);
        $rdate=sprintf("%02d",$date)."/".sprintf("%02d",$mon)."/".$year;

        $hash=array
        (
           "Day" => $date,
           "Month" => $mon,
           "Year" => $year,
           "JulianDay" => gregoriantojd($mon,$date,$year),
           "Date" => $rdate,
           "SortKey" => $datekey,
           "WeekNo" => $weekno,
        );

        $wday=jddayofweek($hash[ "JulianDay" ]);
        if ($wday==0) { $wday=7; $weekno++; }

        $hash[ "WeekDay" ]=$wday;
        $hash[ "Name" ]=$hash[ "Date" ]." ".$this->WeekDays[ $wday-1 ];

        $type=1;
           if ($wday==7) { $type=3; }
        elseif ($wday==6) { $type=2; }

        $hash[ "Type" ]=$type;

        $dateid=$this->SortKey2ID($datekey);
        if (!empty($dateid))
        {
            $hash[ "ID" ]=$dateid;
            $this->MySqlSetItemValues("",array("JulianDay","WeekNo"),$hash);
            $hash[ "Status" ]="Ja!";
        }
        elseif ($this->GetPOST("Add")==1)
        {
            $this->MySqlInsertItem("",$hash);
            $hash[ "Status" ]="Adicionado";
        }
        else
        {
            $hash[ "Status" ]="Adicionar..";
        }

        return $hash;
    }


    //*
    //* function YearDates, Parameter list: $year
    //*
    //* Generates dates of period.
    //*

    function YearDates($year)
    {
        $days=array();
        $table=array
        (
           $this->B
           (
              array("No","Chave","Ano","Mês No.","Mês","Data","Gregorian","No. Semana","Dia","Tipo","Status")
           )
        );

        $n=0;
        $nadded=0;
        $weekno=1;
        for ($mon=1;$mon<=12;$mon++)
        {
            $ndatesinmonth=cal_days_in_month(CAL_GREGORIAN,$mon, $year);

            for ($date=1;$date<=$ndatesinmonth;$date++)
            {
                $hash=$this->InsertDateUnique($date,$mon,$year,$weekno);
                $row=array
                (
                   ++$n,
                   $hash[ "SortKey" ],
                   $hash[ "Year" ],
                   $hash[ "Month" ],
                   $this->Months[ $hash[ "Month" ]-1 ],
                   $hash[ "Day" ],
                   $hash[ "JulianDay" ],
                   $hash[ "WeekNo" ],
                   $this->WeekDays[ $hash[ "WeekDay" ]-1 ],
                   $this->ItemData[ "Type" ][ "Values" ][ $hash[ "Type" ]-1 ],
                   $hash[ "Status" ]
                );

                if ($hash[ "Status" ]=="Adicionado") { $nadded++; }
                array_push($table,$row);
            }
        }

        print
            $this->H(1,"Ano de ".$year).
            $this->H(3,$n." Dias. ".$nadded." Adicionadas").
            $this->HtmlTable("",$table);
    }


    //*
    //* function HandleYearDates, Parameter list:
    //*
    //* Generates dates of period.
    //*

    function HandleYearDates()
    {
        $year=$this->GetPOST("Year");
 
        print
            $this->StartForm().
            $this->H
            (
               2,
               "Gerar Datas do Ano"
            ).
            $this->HtmlTable
            (
               "",
               array
               (
                  array($this->B("Ano:"),$this->MakeInput("Year",$year,3)),
                  array($this->B("Adicionar:"),$this->MakeCheckBox("Add",1,FALSE)),
                  $this->Button("submit","GERAR"),
               )
            ).
            $this->MakeHidden("Generate",1).
            $this->EndForm();


        if ($this->GetPOST("Generate")==1 && preg_match('/^\d\d\d\d$/',$year) && $year>1990)
        {
            $this->YearDates($year);
        }

    }

    //*
    //* function GetYearDates, Parameter list: $year
    //*
    //* Returns all days or year.
    //*

    function GetYearDates($year)
    {
        return $this->SelectHashesFromTable
        (
           "",
           array("Year" => $year),
           array("ID","WeekDay","Name")
        );
    }

    //*
    //* function SetWeekNos, Parameter list: $year
    //*
    //* Calculates dates wek no.
    //*

    function SetWeekNosYear($year)
    {
        $dates=$this->GetYearDates($year);

        $weekno=1;
        foreach ($dates as $m => $date)
        {
            if ($date[ "WeekDay"  ]==1)
            {
                $weekno++;
            }

            $this->MySqlSetItemValue("","ID",$date[ "ID" ],"WeekNo",$weekno);
        }
    }

    //*
    //* function SetWeekNosAll, Parameter list:
    //*
    //* Calculates dates wek no.
    //*

    function SetWeekNosAll()
    {
        for ($year=2004;$year<=2014;$year++)
        {
            $this->SetWeekNosYear($year);
        }
    }
}
?>