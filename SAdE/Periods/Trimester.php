<?php


class PeriodsTrimester extends PeriodsGroups
{
    //*
    //* function Trimester2DatesSqlWhere, Parameter list: $trimester,$period
    //*
    //* Converts semester limit dates to an sql where (start/end, array).
    //*

    function Trimester2DatesSqlWhere($trimester,$period)
    {
        $endkeys=$this->PeriodEndDates($period);

        $startkey=$endkeys[ $trimester-1 ];
        if ($trimester>1) { $startkey++; }

        $endkey=$endkeys[ $trimester ]+1;

        return 
            "SortKey>=".$startkey." AND SortKey<".$endkey;
    }


    //*
    //* function TrimestersMenu, Parameter list: $args
    //*
    //* Creates a menu with links to semesters in period.
    //* Url generated based on $args.
    //*

    function TrimestersMenu($args,$period=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period; }

        $trimester=$this->GetGET("Semester");
        $nassess=4;
        $hrefs=array();
        for ($n=1;$n<=$period[ "NPeriods" ];$n++)
        {
            if ($n!=$trimester)
            {
                unset($args[ "Month" ]);
                $args[ "Semester" ]=$n;
                $hrefs[ $n ]=$this->Href
                (
                   "?".$this->Hash2Query($args),
                   $n."º ".$this->PeriodSubPeriodsTitle($period)
                );
            }
            else
            {
                $hrefs[ $n ]=$n."º ".$this->PeriodSubPeriodsTitle($period);
            }
        }

        return $this->Center("[ ".join(" | ",$hrefs)." ]");
    }

    //*
    //* function UpdatePeriodTrimesterTable, Parameter list: $period,&$disc
    //*
    //* Makes select field for Date, includes only dates of period.
    //*

    function UpdatePeriodTrimesterTable($period,&$disc)
    {
        $key="DayliesClosed";
        $tkey="DayliesClosedTime";

        $updatedatas=array();
        for ($n=1;$n<=$period[ "NPeriods" ];$n++)
        {
            $nkey=$key.$n;
            $tnkey=$tkey.$n;
            $newvalue=$this->GetPOST($nkey);
            if ($disc[ $nkey ]!=$newvalue)
            {
                $disc[ $nkey ]=$newvalue;

                if ($newvalue==1)
                {
                    $disc[ $tnkey ]=0;
                }
                else
                {
                    $disc[ $tnkey ]=time();
                }

                array_push($updatedatas,$nkey,$tnkey);
            }
        }

        if (count($updatedatas)>0)
        {
            $this->ApplicationObj->ClassDiscsObject->MySqlSetItemValues("",$updatedatas,$disc);
        }


    }

    //*
    //* function PeriodTrimesterTable, Parameter list: $edit,$period=array(),$disc=array()
    //*
    //* Makes select field for Date, includes only dates of period.
    //*

    function PeriodTrimesterTable( $edit,$period=array(),$disc=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period; }
        if (empty($disc))   { $disc=$this->ApplicationObj->Disc; }

        if ($this->GetPOST("Save")==1)
        {
            $this->UpdatePeriodTrimesterTable($period,$disc);
        }


        $lkey="DayliesLimit";
        $ckey="DayliesClosed";
        $tkey="DayliesClosedTime";

        $table=array($this->B(array("Trimester","Início - Fim","Aulas Lançadas","Data Limite","Fechado","Data")));

        for ($trimester=1;$trimester<=$period[ "NPeriods" ];$trimester++)
        {
            $row=array
            (
               $this->B($trimester),
               $this->ApplicationObj->DatesObject->DateID2Name
               (
                  $this->GetDaylyPeriodStartDate($period,$trimester)
               ).
               " - ".
               $this->ApplicationObj->DatesObject->DateID2Name
               (
                  $this->GetDaylyPeriodEndDate($period,$trimester)
               )
            );

            array_push
            (
               $row,
               sprintf
               (
                  "%d",
                  $this->ApplicationObj->ClassDiscContentsObject->RowSum
                  (
                     "",
                     array
                     (
                        "Disc" => $disc[ "ID" ],
                        "Semester" => $trimester,
                     ),
                     "Weight"
                  )
               )
            );
 
            if (!empty($disc[ $lkey.$trimester ]))
            {
                array_push
                (
                   $row,
                   $this->ApplicationObj->DatesObject->DateID2Name($disc[ $lkey.$trimester ])
                );
            }
            else
            {
                array_push($row,"-");
            }

            $values=array(1,2);
            $trimesterames=array("Não","Sim");

            $cell=$trimesterames [ $disc[ $ckey.$trimester ]-1 ];
            if ($edit==1)
            {
                $cell=$this->MakeSelectField
                (
                   $ckey.$trimester,
                   $values,
                   $trimesterames,
                   $disc[ $ckey.$trimester ]
                );
            }
            array_push
            (
               $row,
               $cell,
               $this->ApplicationObj->ClassDiscsObject->MakeShowField($tkey.$trimester,$disc)
            );


           array_push($table,$row);
        }

        return $table;
    }

    //*
    //* function GetTrimesterStartDate, Parameter list: $period,$trimester
    //*
    //* Returns Trimester start date id of $period trimester $trimester.
    //*

    function GetTrimesterStartDate($period,$trimester)
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
        }

        return $this->ApplicationObj->DatesObject->AddNDays($day,1);
    }


    //*
    //* function GetTrimesterStartDateName, Parameter list: $period,$trimester,$key="Name"
    //*
    //* Returns Trimester start date name of $period trimester $trimester.
    //*

    function GetTrimesterStartDateName($period,$trimester,$key="Name")
    {
        return $this->ApplicationObj->DatesObject->DateID2Key
        (
           $this->GetTrimesterStartDate($period,$trimester),
           $key
        );
    }

    //*
    //* function GetTrimesterEndDate, Parameter list: $period,$trimester
    //*
    //* Returns Trimester end date id of $period trimester $trimester.
    //*

    function GetTrimesterEndDate($period,$trimester)
    {
        $key="Daylies";
        $day=0;
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
    //* function GetTrimesterEndDateName, Parameter list: $period,$trimester,$key="Name"
    //*
    //* Returns Trimester start date name of $period trimester $trimester.
    //*

    function GetTrimesterEndDateName($period,$trimester,$key="Name")
    {
        return $this->ApplicationObj->DatesObject->DateID2Key
        (
           $this->GetTrimesterEndDate($period,$trimester),
           $key
        );
    }

    //*
    //* function GetTrimesterDateSpan, Parameter list: $period,$trimester,$key="Name"
    //*
    //* Returns the start date period (semester) $trimester.
    //*

    function GetTrimesterDateSpan($period,$trimester,$key="Name")
    {
        return 
            $this->ApplicationObj->DatesObject->DateID2Key
            (
               $this->GetTrimesterStartDate($period,$trimester),
               $key
            ).
            " - ".
            $this->ApplicationObj->DatesObject->DateID2Key
            (
               $this->GetTrimesterEndDate($period,$trimester),
               $key
            );
    }

    //*
    //* function TrimesterEditable, Parameter list: $trimester,$period=array()
    //*
    //* Returns true if semester is editable, false otherwise.
    //*

    function TrimesterEditable($trimester,$period=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period; }

        $today=$this->TimeStamp2DateSort();
        $limitdate=$this->GetDayliesLimitDateKey($period,$trimester);

        if ($today>$limitdate) { return TRUE; }
        else                   { return FALSE; }
    }
}
?>