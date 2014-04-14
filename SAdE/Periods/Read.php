<?php


class PeriodsRead extends PeriodsTrimester
{
    //*
    //* function ReadAllPeriods, Parameter list:
    //*
    //* Reads all periods.
    //* 
    //*

    function ReadAllPeriods()
    {
        return $this->SelectHashesFromTable
        (
           "Periods",
           array("Daylies" => 2)
        );
    }

    //*
    //* function ReadPeriods, Parameter list:
    //*
    //* Reads periods referenced by $this->ApplicationObj->School.
    //* 
    //*

    function ReadPeriods($all=FALSE)
    {
        //if (empty($this->ApplicationObj->School)) { return; }
        if (preg_match('/Teacher/',$this->Profile))
        {
            $this->ReadTeacherPeriods();
        }
        else
        {
            $this->ReadNonTeacherPeriods($all);
        }


        if (!empty($this->ApplicationObj->Periods) && empty($this->ApplicationObj->Period))
        {
            $periodid=$this->GetGET("Period");
            if (!empty($periodid))
            {
                foreach ($this->ApplicationObj->Periods as $period)
                {
                    if ($periodid==$period[ "ID" ])
                    {
                        $this->ApplicationObj->Period=$period;

                        break;
                    }
                }
            }

            if (!empty($this->ApplicationObj->Periods) && empty($this->ApplicationObj->Period))
            {
                $this->ApplicationObj->Period=$this->ApplicationObj->Periods[0];
            }

            $this->ApplicationObj->UpdateTablesStructure($this->ApplicationObj->PeriodModules);

            $this->Period[ "NClasses" ]=$this->ApplicationObj->ClassesObject->MySqlNEntries
            (
               $this->ApplicationObj->School[ "ID" ]."_Classes",
               array
               (
                  "School" => $this->ApplicationObj->School[ "ID" ],
                  "Period" => $this->ApplicationObj->Period[ "ID" ],
               )
            );

            $this->MySqlSetItemValue
            (
               "",
               "ID",$this->ApplicationObj->Period[ "ID" ],
               "NClasses",$this->ApplicationObj->Period[ "NClasses" ]
            );
        }

        if (empty($this->ApplicationObj->Period))
        {
            //die("Invalid Period: '".$this->GetGET("Period")."'");
        }
    }


    //*
    //* function ReadNonTeacherPeriods, Parameter list:
    //*
    //* Reads periods referenced by school.
    //* 
    //*

    function ReadNonTeacherPeriods($all=FALSE)
    {
        if (!$this->MySqlIsTable($this->ApplicationObj->School[ "ID" ]."_Classes"))
        {
            $this->ApplicationObj->Periods=array();
            return;
        }

        if (empty($this->ApplicationObj->Periods))
        {
            $where="";
            if (!$all && isset($this->ApplicationObj->ClassesObject))
            {
                $periodids=$this->ApplicationObj->ClassesObject->MySqlUniqueColValues
                (
                   $this->ApplicationObj->School[ "ID" ]."_Classes",
                   "Period",
                   array
                   (
                      "School" => $this->ApplicationObj->School[ "ID" ]
                   ),
                   "",
                   "Name"
                );

                $where="ID IN ('".join("','",$periodids)."')";
            }

            $this->ApplicationObj->Periods=$this->SelectHashesFromTable
            (
               "",
               $where,
               array(),
               FALSE,
               "Year,Name","NClasses"
            );

            $this->ApplicationObj->Periods=array_reverse($this->ApplicationObj->Periods);
        }
    }

     //*
    //* function ReadTeacherPeriods, Parameter list:
    //*
    //* Reads periods referenced by school.
    //* 
    //*

    function ReadTeacherPeriods()
    {
        $this->ApplicationObj->Periods=$this->SelectHashesFromTable
        (
           "Periods",
           array("Daylies" => 2)
        );

        $this->ApplicationObj->Periods=array_reverse($this->ApplicationObj->Periods);

        if (
              !empty($this->ApplicationObj->School)
              &&
              !$this->MySqlIsTable($this->ApplicationObj->School[ "ID" ]."_Classes")
           )
        {
            $this->ApplicationObj->Periods=array();
            return;
        }

        $classestable=$this->ApplicationObj->School[ "ID" ]."_Classes";

        $periods=array();
        if ($this->MySqlIsTable($classestable))
        {
            $datas=array("Teacher","Teacher1","Teacher2");
            $datas=$this->DBFieldsExists($classestable,$datas);

            if (count($datas)>0)
            {
                foreach (array_keys($datas) as $id)
                {
                    $datas[ $id ].="='".$this->LoginData[ "ID" ]."'";
                }

                $rperiods=$this->MySqlUniqueColValues
                (
                   $classestable,
                   "Period",
                   join(" OR ",$datas)
                );

                foreach ($rperiods as $rperiod)
                {
                    $periods[ $rperiod ]=1;
                }
            }
        }

        foreach ($this->ApplicationObj->Periods as $period)
        {
            $periodname=$this->GetPeriodName($period);
            $classdiscstable=$this->ApplicationObj->School[ "ID" ]."_".$periodname."_ClassDiscs";
            if ($this->MySqlIsTable($classdiscstable))
            {
                $datas=array("Teacher","Teacher1","Teacher2");
                $datas=$this->DBFieldsExists($classdiscstable,$datas);

                if (count($datas)>0)
                {
                    foreach (array_keys($datas) as $id)
                    {
                        $datas[ $id ].="='".$this->LoginData[ "ID" ]."'";
                    }

                    $rperiods=$this->MySqlUniqueColValues
                    (
                       $classdiscstable,
                       "Period",
                       join(" OR ",$datas)
                    );

                    foreach ($rperiods as $rperiod)
                    {
                        $periods[ $rperiod ]=1;
                    }
                }
            }
           
        }

        foreach (array_keys($this->ApplicationObj->Periods) as $id)
        {
            $perid=$this->ApplicationObj->Periods[ $id ][ "ID" ];
            if (empty($periods[ $perid ]))
            {
                unset($this->ApplicationObj->Periods[ $id ]);
            }
        }
   }

    //*
    //* function ReadPeriod, Parameter list:
    //*
    //* Reads period referenced by $this->ApplicationObj->School.
    //* 
    //*

    function ReadPeriod()
    {
        $period=$this->GetGET("Period");
        if (preg_match('/^\d+$/',$period) && $period>0)
        {
            $this->ApplicationObj->Period=$this->SelectUniqueHash
            (
               "",
               array("ID" => intval($period))
            );
        }
        elseif (!empty($period))
        {
            die("Invalid period: $period");
        }

        if (empty($this->ApplicationObj->Period))
        {      
            if (empty($this->ApplicationObj->Periods))
            {
                $this->ReadPeriods();
            }
            if (!empty($this->ApplicationObj->Periods))
            {
                $this->ApplicationObj->Period=$this->ApplicationObj->Periods[0];
            }
         }
    }

    //*
    //* function GetPeriodName, Parameter list: $period=array()
    //*
    //* Returns calling name of Period.
    //* 
    //*

    function GetPeriodName($period=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period(); }

        $name=$period[ "Year" ];
        if ($period[ "Type" ]>1)
        {
            $name.="_".$period[ "Semester" ];
        }

        return $name;
    }

    //*
    //* function GetPeriodTitle, Parameter list: $period=array()
    //*
    //* Returns calling name of Period.
    //* 
    //*

    function GetPeriodTitle($period=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period(); }

        return $period[ "Name" ];
    }


     //*
    //* function FindDatePeriod, Parameter list: $date,$mode
    //*
    //* Locates Period that $data belongs to.
    //* 
    //*

    function FindDatePeriod($date,$mode)
    {
        if (empty($this->Periods))
        {
            $this->ReadSchoolPeriods();
        }
        $rperiod=array();
        foreach ($this->ApplicationObj->Periods as $period)
        {
            if ($mode==$period[ "Type" ])
            {
                if (
                      $date>=$this->ApplicationObj->DatesObject->DateID2SortKey($period[ "StartDate" ])
                      &&
                      $date<=$this->ApplicationObj->DatesObject->DateID2SortKey($period[ "EndDate" ])
                    )
                {
                    $rperiod=$period;
                }
            }
        }

        return $rperiod;
    }

    //*
    //* function LocatePeriod, Parameter list: $periodid
    //*
    //* Locates Period with ID $periodid.
    //* 
    //*

    function LocatePeriod($periodid)
    {
        return $this->ApplicationObj->Periods($periodid);
    }
}
?>