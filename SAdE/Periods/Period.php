<?php


class PeriodsPeriod extends PeriodsRead
{
    var $PeriodIDs=array();
    var $PeriodNames=array();

    //*
    //* function PeriodSubPeriodsTitle, Parameter list: $period=array()
    //*
    //* Returns name of sub periods, Trimestre.
    //*

    function PeriodSubPeriodsTitle($period=array())
    {
        //if (empty($period)) { $period=$this->ApplicationObj->Period; }
        return "Trimestre";
    }

    //*
    //* function PeriodSubPeriodsLetter, Parameter list: $period=array()
    //*
    //* Returns name of sub periods, Trimestre.
    //*

    function PeriodSubPeriodsLetter($period=array())
    {
        //if (empty($period)) { $period=$this->ApplicationObj->Period; }
        return "T";
    }

    //*
    //* function SetPeriodName, Parameter list: &$period
    //*
    //* Creates a new period item in SQL Table.
    //*

    function SetPeriodName(&$period)
    {
        $name=$period[ "Year" ].".".sprintf("%02d",$period[ "Semester" ]);
        if ($name!=$period[ "Period" ])
        {
            $period[ "Name" ]=$name;
            $this->MySqlSetItemValue
            (
               "",
               "ID",
               $period[ "ID" ],
               "Name" ,
               $name
            );
        }

        return $name;
    }


    //*
    //* function NewPeriod, Parameter list: $year,$type,$trimester
    //*
    //* Creates a new period item in SQL Table.
    //*

    function NewPeriod($year,$type,$trimester)
    {
        $period=array
        (
           "Year" => $year,
           "Type" => $type+1,
           "Semester" =>$trimester ,
        );

        $startmonth=1;
        $dm=12/$this->ItemData[ "Type" ][ "NSemesters" ][ $type ];

        for ($n=1;$n<$trimester;$n++)
        {
            $startmonth+=$dm;
        }
        $startdate=$this->ApplicationObj->DatesObject->SelectUniqueHash
        (
           "",
           array
           (
              "SortKey" => $year.sprintf("%02d",$startmonth)."01",
           ),
           TRUE
        );


        $endmonth=$startmonth+$dm-1;
        $ndatesinmonth=cal_days_in_month(CAL_GREGORIAN,$endmonth, $year);
        $enddate=$this->ApplicationObj->DatesObject->SelectUniqueHash
        (
           "",
           array
           (
              "SortKey" => $year.sprintf("%02d",$endmonth).$ndatesinmonth,
           ),
           TRUE
        );



        $period[ "StartDate" ]=$startdate[ "ID" ];
        $period[ "EndDate"   ]=$enddate[ "ID" ];
        $period[ "Period"   ]=$year.".".$type.".".$trimester;
        $period[ "Name"   ]=$this->ItemData[ "Type" ][ "Names" ][ $type ]." de ".$year;
        if ($type>0)
        {
            $period[ "Name"   ]=$trimester."º ".$this->ItemData[ "Type" ][ "Names" ][ $type ]." de ".$year;
        }

        return $period;
    }


    //*
    //* function PeriodKey, Parameter list: $period
    //*
    //* Returns period key.
    //*

    function PeriodKey($period)
    {
        if (!is_array($period))
        {
            return $period;
        }

        if ($period[ "Type" ]==1)
        {
            return $period[ "Year" ]; //anual
        }
        elseif ($period[ "Type" ]<5)
        {
            return $period[ "Year" ]."_".$period[ "Semester" ]; //not monthly (<10)
        }
        else
        {
            return $period[ "Year" ]."_".sprintf("%02d",$period[ "Semester" ]);
        }
    }

    //*
    //* function FindNextPeriod, Parameter list: $period,$datas=array()
    //*
    //* Returns the period after $period. Being a hash - returning hash.
    //*

    function FindNextPeriod($period,$datas=array())
    {
        $where=array();
        if ($period[ "Type" ]==1) //anual
        {
            $where=array
            (
               "Year" => $period[ "Year" ]+1,
               "Type" => 1,
            );
        }
        elseif ($period[ "Type" ]==2) //semestral
        {
            $trimester=$period[ "Semester" ];
            $year=$period[ "Year" ];
            if ($trimester==1) { $trimester=2; }
            else
            {
                $trimester=1;
                $year++;
            }

            $where=array
            (
               "Year" => $year,
               "Semester" => $trimester,
               "Type" => 2,
            );
        }

        return $this->SelectUniqueHash("",$where,$datas);
    }

    //*
    //* function SetNextPeriod, Parameter list: &$period
    //*
    //* Sets the NextPeriod key and update item/db.
    //*

    function SetNextPeriod(&$period)
    {
        $nextperiod=$this->FindNextPeriod($period,array("ID"));
        if (!empty($nextperiod))
        {
            if ($period[ "NextPeriod" ]!=$nextperiod[ "ID" ])
            {
                $periody[ "NextPeriod" ]=$nextperiod[ "ID" ];
                $this->MySqlSetItemValue
                (
                   "",
                   "ID",
                   $period[ "ID" ],
                   "NextPeriod" ,
                   $nextperiod[ "ID" ]
                );
            }
        }

        return $nextperiod[ "ID" ];
    }


    //*
    //* function PreviousPeriod, Parameter list: $periodid
    //*
    //* Returns period before this one, if any.
    //*

    function PreviousPeriod($periodid)
    {
        
        $thisperiod=$this->MySqlItemValues("","ID",$periodid,array("Type","Year","Semester"));
        if ($thisperiod[ "Semester" ]>1)
        {
            $thisperiod[ "Semester" ]--;
        }
        else
        {
            $thisperiod[ "Year" ]--;
            if ($thisperiod[ "Type" ]==1)
            {
                $thisperiod[ "Semester" ]=1;
            }
            elseif ($thisperiod[ "Type" ]==2)
            {
                $thisperiod[ "Semester" ]=2;
            }
            elseif ($thisperiod[ "Type" ]==3)
            {
                $thisperiod[ "Semester" ]=4;
            }
            elseif ($thisperiod[ "Type" ]==4)
            {
                $thisperiod[ "Semester" ]=6;
            }
            elseif ($thisperiod[ "Type" ]==5)
            {
                $thisperiod[ "Semester" ]=12;
            }
        }

        $thisperiod=$this->SelectUniqueHash
        (
           "",
           array("Year" => $thisperiod[ "Year" ],"Semester" => $thisperiod[ "Semester" ]),
           TRUE
        );

        return $thisperiod;
    }

    //*
    //* function MakePeriodDaySelect, Parameter list: $data,$period,$value
    //*
    //* Makes select field for Date, includes only dates of period.
    //*

    function MakePeriodDaySelect($data,$period,$value)
    {
        if (empty($this->PeriodIDs))
        {
            $startkey=$this->ApplicationObj->DatesObject->ID2SortKey($period[ "StartDate" ]);
            $endkey=$this->ApplicationObj->DatesObject->ID2SortKey($period[ "EndDate" ]);

            $rendid  =$this->ApplicationObj->DatesObject->AddNDays($period[ "EndDate"   ],7);
            if (!empty($rendid))
            {
                $endkey=$this->ApplicationObj->DatesObject->ID2SortKey($rendid);
            }

            $where="SortKey>=".$startkey." AND "."SortKey<=".$endkey."";

            $dates=$this->ApplicationObj->DatesObject->SelectHashesFromTable
            (
               "",
               "SortKey>=".$startkey." AND "."SortKey<=".$endkey."",
               array("ID","Name"),
               FALSE,
               "SortKey"
            );

            $this->PeriodIDs=array(0);
            $this->PeriodNames=array("");
            foreach ($dates as $date)
            {
                array_push($this->PeriodIDs,$date[ "ID" ]);
                array_push
                (
                   $this->PeriodNames,
                   $this->ApplicationObj->DatesObject->DateID2Name($date[ "ID" ])
                );
            }
        }


        return $this->MakeSelectField
        (
           $data,
           $this->PeriodIDs,
           $this->PeriodNames,
           $value
        );
    }



    //*
    //* function GetDayliesLimitDate, Parameter list: $period,$trimester
    //*
    //* Returns id of start date period (semester) $trimester.
    //*

    function GetDayliesLimitDate($period,$trimester)
    {
        return $period[ "DayliesLimit".$trimester ];
    }

    //*
    //* function GetDayliesLimitDateKey, Parameter list: $period,$trimester
    //*
    //* Returns the start date period (semester) $trimester.
    //*

    function GetDayliesLimitDateKey($period,$trimester)
    {
        return $this->ApplicationObj->DatesObject->ID2SortKey
        (
           $this->GetDayliesLimitDate($period,$trimester)
        );
    }


    //*
    //* function GetDaylyPeriodStartDate, Parameter list: $period,$trimester
    //*
    //* Returns the start date period (semester) $trimester.
    //*

    function GetDaylyPeriodStartDate($period,$trimester)
    {
        $key="Daylies";
        $day=0;
        if ($trimester==1)
        {
            $day=$period[ $key."Start" ];
        }
        else
        {
            $day=$period[ $key.($trimester-1) ];
            $day=$this->ApplicationObj->DatesObject->AddNDays($day,1);
        }

        return $day;
    }

    //*
    //* function GetDaylyPeriodStartDateKey, Parameter list: $period,$trimester
    //*
    //* Returns the end date period (semester) $trimester.
    //*

    function GetDaylyPeriodStartDateKey($period,$trimester)
    {
        return $this->ApplicationObj->DatesObject->DateID2SortKey
        (
           $this->GetDaylyPeriodStartDate($period,$trimester)
        );
    }
    //*
    //* function GetDaylyPeriodEndDate, Parameter list: $period,$trimester
    //*
    //* Returns the end date period (semester) $trimester.
    //*

    function GetDaylyPeriodEndDate($period,$trimester)
    {
        $key="Daylies";
        $trimester=$this->Min($trimester,$period[ "NPeriods" ]);
        $day=$period[ $key.$trimester ];
        if ($trimester<$period[ "NPeriods" ])
        {
            $day=$period[ $key.$trimester ];
        }
        else
        {
            $day=$period[ $key."End" ];
        }

        return $day;
    }

    //*
    //* function GetDaylyPeriodEndDateKey, Parameter list: $period,$trimester
    //*
    //* Returns the end date period (semester) $trimester.
    //*

    function GetDaylyPeriodEndDateKey($period,$trimester)
    {
        return $this->ApplicationObj->DatesObject->DateID2SortKey
        (
           $this->GetDaylyPeriodEndDate($period,$trimester)
        );
    }

    //*
    //* function PeriodEndDates, Parameter list: $period
    //*
    //* Reads and sorts period Dates.
    //*

    function PeriodEndDates($period)
    {
        $endkeys=array();
        $endkeys[0]=$this->ApplicationObj->DatesObject->ID2SortKey($period[ "DayliesStart" ]);

        for ($trimester=1;$trimester<=$period[ "NPeriods" ];$trimester++)
        {
            $endkeys[$trimester]=$this->ApplicationObj->DatesObject->ID2SortKey
            (
               $this->GetDaylyPeriodEndDate($period,$trimester)
            );
        }

        return $endkeys;
     }




    //*
    //* function PeriodDayliesDates, Parameter list: $period,$datas=array()
    //*
    //* Reads and sorts period Dates.
    //*

    function PeriodDayliesDates($period,$datas=array())
    {
        $dates=$this->ApplicationObj->DatesObject->GetDatesInRangeNoEnd
        (
           $period[ "DayliesStart" ],
           $period[ "DayliesEnd" ],
           $datas
        );

        $endkeys=$this->PeriodEndDates($period);

        $rdates=array();
        foreach ($dates as $date)
        {
            $per=0;
            for ($trimester=1;$trimester<=$period[ "NPeriods" ];$trimester++ && $per=0)
            {
                if (
                      $date[ "SortKey" ]>=$endkeys[ $trimester-1 ]
                      &&
                      $date[ "SortKey" ]<=$endkeys[ $trimester ]
                   )
                {
                    $per=$trimester;
                    break;
                }
            }

            if ($per==0)
            {
                var_dump("Funny Date: ");var_dump($date);
                continue;
            }

            if (empty($rdates[ $per ])) { $rdates[ $per ]=array(); }

            array_push($rdates[ $per ],$date);
        }

        return $rdates;
    }


}
?>