<?php


class PeriodsDates extends Common
{
    //*
    //* function DateID2Name, Parameter list: $id,$key="Name"
    //*
    //* Returns name of date with ID $id.
    //*

    function DateID2Name($id,$key="Name")
    {
        return $this->ApplicationObj->DatesObject->DateID2Name($id,$key);
    }

    //*
    //* function SetDateData2SortKey, Parameter list: &$period,$data,$datekey
    //*
    //* For $period data $data, fromk sortkey $datekey, retrieves
    //* ID from Dates db. Updates $period $data key, and DB if necessary. 
    //*

    function SetDateData2SortKey(&$period,$data,$datekey)
    {
        $dateid=0;
        if ($datekey>0)
        {
            $dateid=$this->ApplicationObj->DatesObject->SortKey2ID($datekey);
            if (!empty($dateid))
            {
                if (empty($period[ $data ]) || $period[ $data ]!=$dateid)
                {
                    $this->SetAndUpdateDataValue("",$period,$data,$dateid);
                }
            }
        }

        return $dateid;
    }


    //*
    //* function StartDateSortKey, Parameter list: $period,
    //*
    //* Sets first day of $period according to Year, Type and Semester.
    //* Returns sortkey date encountered: YYYYMMDD.
    //*

    function StartDateSortKey($period)
    {
        $date=0;
        if ($period[ "Type" ]==1)
        {
            $date=$period[ "Year" ]."0101";
        }
        elseif ($period[ "Type" ]==2)
        {
            $month="01";
            if ($period[ "Semester" ]>1)
            {
                $month="07";
            }
            $date=$period[ "Year" ].$month."01";
        }

        return $date;
    }

    //*
    //* function EndDateSortKey, Parameter list: $period,
    //*
    //* Sets last day of $period according to Year, Type and Semester.
    //* Returns sortkey date encountered: YYYYMMDD.
    //*

    function EndDateSortKey($period)
    {
        $date=0;
        if ($period[ "Type" ]==1)
        {
            $date=$period[ "Year" ]."1231";
        }
        elseif ($period[ "Type" ]==2)
        {
            $month="06";
            $date="30";
            if ($period[ "Semester" ]>1)
            {
                $month="12";
                $date="31";
            }
            $date=$period[ "Year" ].$month.$date;
        }

        return $date;
   }


    //*
    //* function SetStartDate, Parameter list: &$period,
    //*
    //* Sets first day of $period according to Year, Type and Semester.
    //* Returns date encountered.
    //*

    function SetStartDate(&$period)
    {
        $datekey=$this->StartDateSortKey($period);
        return $this->SetDateData2SortKey($period,"StartDate",$datekey);
    }

    //*
    //* function SetEndDate, Parameter list: &$period,
    //*
    //* Sets first day of $period according to Year, Type and Semester.
    //* Returns date encountered.
    //*

    function SetEndDate(&$period)
    {
        $datekey=$this->EndDateSortKey($period);
        return $this->SetDateData2SortKey($period,"EndDate",$datekey);
    }





    //*
    //* function DayliesStartDateSortKey, Parameter list: $period,
    //*
    //* Sets first day of $period according to Year, Type and Semester.
    //* Returns sortkey date encountered: YYYYMMDD.
    //*

    function DayliesStartDateSortKey($period)
    {
        $datekey=0;

        $day=15;
        if ($period[ "Type" ]==1)
        {
            $datekey=$period[ "Year" ]."01".$day;
        }
        elseif ($period[ "Type" ]==2)
        {
            $month="01";
            if ($period[ "Semester" ]>1)
            {
                $month="07";
            }
            $datekey=$period[ "Year" ].$month.$day;
        }

        return $datekey;
    }

    //*
    //* function DayliesEndDateSortKey, Parameter list: $period,
    //*
    //* Sets last day of $period according to Year, Type and Semester.
    //* Returns sortkey date encountered: YYYYMMDD.
    //*

    function DayliesEndDateSortKey($period)
    {
        return $this->EndDateSortKey($period);
    }


    //*
    //* function SetDayliesStartDate, Parameter list: &$period,
    //*
    //* Sets first day of lecture period of $period according to Year, Type and Semester.
    //* Returns date encountered.
    //*

    function SetDayliesStartDate(&$period)
    {
        $key="DayliesStart";
        if (!empty($period[ $key ])) { return $period[ $key ]; }

        $datekey=$this->DayliesStartDateSortKey($period);
        return $this->SetDateData2SortKey($period,$key,$datekey);
    }


    //*
    //* function SetDayliesEndDate, Parameter list: &$period,
    //*
    //* Sets last day of lecture period of $period according to Year, Type and Semester.
    //* Returns date encountered.
    //*

    function SetDayliesEndDate(&$period)
    {
        $key="DayliesEnd";
       if (!empty($period[ $key ])) { return $period[ $key ]; }

        $datekey=$this->DayliesEndDateSortKey($period);
        return $this->SetDateData2SortKey($period,$key,$datekey);
    }




    //*
    //* function SetDayliesDate, Parameter list: &$period,$n
    //*
    //* Sets initial value to last date in 3rd, 6th,.. month.
    //*

    function SetDayliesDate(&$period,$n)
    {
        $key="Daylies".$n;
        $nmonths=3;
        if (empty($period[ $key ]))
        {
            $period[ $key ]=0;

            $datekey=0;

            $nextdateid=$this->ApplicationObj->DatesObject->GetLastDateInMonth
            (
               $period[ "Year" ],
               $nmonths*$n
            );

            if ($nextdateid>0)
            {
                $this->SetAndUpdateDataValue("",$period,$key,$nextdateid);
            }
         }
    }

    //*
    //* function SetDayliesLimitDate, Parameter list: &$period,$n
    //*
    //* Sets initial value to last date in limit days, adding 7 days.
    //*

    function SetDayliesLimitDate(&$period,$n)
    {
        $key="DayliesLimit".$n;

        $nmonths=3;
        if (empty($period[ $key ]))
        {
            $period[ $key ]=0;

            $nextdateid=$this->ApplicationObj->DatesObject->GetLastDateInMonth
            (
               $period[ "Year" ],
               $nmonths*$n
            );

            $rendid  =$this->ApplicationObj->DatesObject->AddNDays($nextdateid,7);
            if (!empty($rendid))
            {
                $nextdateid=$rendid;
            }

            $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($nextdateid);

            if ($nextdateid>0)
            {
                $this->SetAndUpdateDataValue("",$period,$key,$nextdateid);
            }
         }
    }


    //*
    //* function SetDayliesDates, Parameter list: &$period,
    //*
    //* Sets inicial dates of daylies period limit date.
    //*

    function SetDayliesDates(&$period)
    {
        $this->SetDayliesStartDate($period);
        $this->SetDayliesEndDate($period);

        for ($n=1;$n<=$this->ApplicationObj->MaxNAssessments;$n++)
        {
            $this->SetDayliesDate($period,$n);
            $this->SetDayliesLimitDate($period,$n);
        }
        
    }


    //*
    //* function DayliesSortKeys, Parameter list: $period
    //*
    //* Figures out the semester of $date.
    //*

    function DayliesSortKeys($period)
    {
        $datekeys=array();
        for ($n=1;$n<=$period[ "NPeriods" ];$n++)
        {
            $datekeys[ $n ]=$this->ApplicationObj->DatesObject->ID2SortKey($period[ "Daylies".$n ]);
        }

        return $datekeys;
    }

    //*
    //* function Date2Trimester, Parameter list: $period,&$date,$datekeys=array()
    //*
    //* Figures out the trimester of $date.
    //*

    function Date2Trimester($period,&$date,$datekeys=array())
    {
        if (empty($datekeys)) { $datekeys=$this->DayliesSortKeys($period); }

        $trimester=1;
        for ($n=1;$n<=$period[ "NPeriods" ];$n++)
        {
            if ($date[ "SortKey" ]<=$datekeys[ $n ])
            {
                $trimester=$n;
                break; //found
            }
        }

        $date[ "Semester" ]=$trimester;
        return $trimester;
    }


    //*
    //* function MakeDateSelect, Parameter list: $data,$item,$edit
    //*
    //* Makes select field for Date, includes only dates of current period.
    //*

    function MakeDayliesDateSelect($data,$item,$edit)
    {
        if ($edit!=1)
        {
            return $this->ApplicationObj->DatesObject->DateID2Name($item[ $data ]);
        }
        else
        {
            return $this->MakePeriodDaySelect
            (
               $data,
               $item,
               $item[ $data ]
            );
        }
    }



    //*
    //* function DatesSqlWhere, Parameter list: $period=array()
    //*
    //* Sql clause for reading all days in $period.
    //*

    function DatesSqlWhere($period=array())
    {
        if (empty($period)) { $period=$this->ItemHash; }

        $startkey=$this->ApplicationObj->DatesObject->MySqlItemValue("","ID",$period[ "StartDate" ],"SortKey");
        $endkey=$this->ApplicationObj->DatesObject->MySqlItemValue("","ID",$period[ "EndDate" ],"SortKey");

        return
            "SortKey>='".$startkey."'".
            " AND ".
            "SortKey<='".$endkey."'";
    }

     //*
    //* function DayliesSqlWhere, Parameter list: $period=array()
    //*
    //* Sql clause for reading all semester days of $period.
    //*

    function DayliesSqlWhere($period=array())
    {
        if (empty($period)) { $period=$this->ItemHash; }

        $startkey=$this->ApplicationObj->DatesObject->MySqlItemValue("","ID",$period[ "DayliesStart" ],"SortKey");
        $endkey=$this->ApplicationObj->DatesObject->MySqlItemValue("","ID",$period[ "DayliesEnd" ],"SortKey");

        return
            "SortKey>='".$startkey."'".
            " AND ".
            "SortKey<='".$endkey."'";
    }

    
   


    //*
    //* function GenDateCell, Parameter list: $date
    //*
    //* Generates Date cell in Calendar.
    //*

    function GenDateCell($date)
    {
        $type=0;
        if ($date[ "Type" ]==4)
        {
            $type=4;
        }
        elseif ($date[ "Type" ]==5)
        {
            $type=5;
        }
        elseif ($date[ "WeekDay" ]==7)
        {
            $type=7;
        }
        elseif ($date[ "WeekDay" ]==6)
        {
            $type=6;
        }

       //save action --> show!!
       return $this->Div
        (
           $this->ApplicationObj->DatesObject->Href
           (
              "?".$this->Hash2Query
              (
                 $this->ScriptQueryHash
                 (
                    array
                    (
                       "Action" => "Show",
                       "ModuleName" => "Dates",
                       "ID" => $date[ "ID" ],
                    )
                 )
              ),
              sprintf("%02d",$date[ "Date" ]),
              "",
              $this->DateCellDefs[ $type ][ "Class" ]
           ),
           array
           (
              "CLASS" => $this->DateCellDefs[ $type ][ "Class" ],
              "TITLE" => $date[ "Date" ]." ".$date[ "Text" ],
           )
        );
    }    
}

?>