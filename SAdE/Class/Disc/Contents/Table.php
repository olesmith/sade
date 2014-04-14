<?php


class ClassDiscContentsTable extends ClassDiscContentsTitles
{
    //*
    //* function PeriodContentsTableMonths, Parameter list: $contents,$dates
    //*
    //* Generates resume table with Contents per months.
    //*

    function PeriodContentsTableMonths($contents,$dates)
    {
        $table=array
        (
           $this->B
           (
              array("Aulas Lançadas:")
           ),
           $this->B
           (
              array("Mês:")
           ),
           $this->B
           (
              array("Encontros/CH:")
           )
        );

        $n=0;
        $rentries=0;
        $rweights=0;
        foreach ($this->ApplicationObj->PeriodsObject->GetMonths() as $month)
        {
            $n++;
            $where=array
            (
               "Class" => $this->ApplicationObj->Class[ "ID" ],
               "Disc" => $this->ApplicationObj->Disc[ "ID" ],
               "Month" => $month,
            );

            $entries=$this->MySqlNEntries("",$where);
            $weights="-";
            if (!empty($entries) && $entries!="0")
            {
                $weights=$this->RowSum("",$where,"Weight");
                $rentries+=$entries;
                $rweights+=$weights;
            }
            else
            {
                $entries="-";
            }

            array_push
            (
               $table[1],
               $this->B($this->Months_Short[ $month-1 ])
            );
            array_push
            (
               $table[2],
               sprintf("%02d",$entries)."/".sprintf("%02d",$weights)
            );
        }

        array_push
        (
           $table[1],
           $this->B($this->ApplicationObj->Sigma)
        );
        array_push
        (
           $table[2],
           $this->B(sprintf("%02d",$rentries))."/".$this->B(sprintf("%02d",$rweights))
        );

        return $table;
    }

    //*
    //* function PeriodContentsTableSemesters, Parameter list: $contents,$dates
    //*
    //* Generates resume table with Contents per months.
    //*

    function PeriodContentsTableSemesters($contents,$dates)
    {
        $table=array
        (
           $this->B
           (
              array("Aulas Lançadas:")
           ),
           $this->B
           (
              array($this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle().":")
           ),
           $this->B
           (
              array("Encontros/CH:")
           )
        );

        $rentries=0;
        $rweights=0;
        for ($trimester=1;$trimester<=$this->ApplicationObj->Disc[ "NAssessments" ];$trimester++)
        {
            $where=array
            (
               "Class" => $this->ApplicationObj->Class[ "ID" ],
               "Disc" => $this->ApplicationObj->Disc[ "ID" ],
               "Semester" => $trimester,
            );

            $entries=$this->MySqlNEntries("",$where);
            $weights="-";
            if (!empty($entries) && $entries!="0")
            {
                $weights=$this->RowSum("",$where,"Weight");
                $rentries+=$entries;
                $rweights+=$weights;
            }
            else
            {
                $entries="-";
            }

            $trimester=intval($trimester);

            array_push
            (
               $table[1],
               $this->B($trimester)
            );
            array_push
            (
               $table[2],
               sprintf("%02d",$entries)."/".sprintf("%02d",$weights)
            );
        }

        array_push
        (
           $table[1],
           $this->B($this->ApplicationObj->Sigma)
        );
        array_push
        (
           $table[2],
           $this->B(sprintf("%02d",$rentries)."/".sprintf("%02d",$rweights))
        );

        return $table;
    }


    //*
    //* function PeriodContentsTable, Parameter list: $contents,$dates,$type=1
    //*
    //* Generates resume table with Contents per months.
    //*

    function PeriodContentsTable($contents,$dates,$type=1)
    {
        if ($type==1)
        {
            return $this->PeriodContentsTableMonths($contents,$dates);
        }
        elseif ($type==2)
        {
            return $this->PeriodContentsTableSemesters($contents,$dates);
        }

        return array();
    }


     //*
    //* function DaylyContentsTable, Parameter list: $edit,$contents,$dates
    //*
    //* Generates the actual Contents table - as matrix.
    //*

    function DaylyContentsTable($edit,$contents,$dates)
    {
        $table=array($this->DaylyContentsContentTitles($edit));

        $lastdate=array
        (
           "Year"     => -1,
           "Semester" => -1,
           "Month"    => -1,
           //"WeekDay"   => -1,
           "WeekNo"   => -1,
           "Date"     => -1,
        );

        $n=1;
        $ch=0;
        foreach ($contents as $content)
        {
            $redit=$edit;
            if (
                  $this->ApplicationObj->PeriodsObject->TrimesterEditable
                  (
                     $content[ "Semester" ],
                     $this->ApplicationObj->Disc)
               )
            {
                $redit=0;
            }
            
            array_push
            (
               $table,
               $this->DaylyContentsContentRow($redit,$n++,$content,$dates,$lastdate)
            );

            $ch+=$content[ "Weight" ];
        }

        array_push
        (
           $table,
           array
           (
              $this->MultiCell("",7),
              $this->B($this->ApplicationObj->Sigma."CH"),
              $this->B(sprintf("%02d",$ch)),
              ""
           )
        );

        return $table;
    }
}

?>