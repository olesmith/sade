<?php


class ClassDiscContentsUpdate extends ClassDiscContentsCGI
{
    //*
    //* function UpdateContentDateDatas, Parameter list: &$content,$data,$newvalue
    //*
    //* Updates $content Month, Year, Weekday, etc when $content[ "Date" ]
    //* changes - called by UpdateDaylyContent below.
    //*

    function UpdateContentDateDatas(&$content,$data,$newvalue,&$updatedatas)
    {
        $newvalue=$this->GetPOST($content[ "ID" ]."_".$data);

        if ($newvalue==$content[ $data ])
        {
            return;
        }

        $newdate=$this->ApplicationObj->DatesObject->SelectUniqueHash
        (
           "",
           array("ID" => $newvalue),
           FALSE,
           $this->Date2ContentsData
        );

        if (empty($newdate[ "SortKey" ])) { return; }

        $this->ApplicationObj->PeriodsObject->Date2Trimester($this->ApplicationObj->Period,$newdate);

        $rdatas=array
        (
           "SortKey" => "DateKey",
        );

        foreach ($this->Date2ContentsData as $rdata)
        {
            $rrdata=$rdata;
            if (isset($rdatas[ $rdata ]))
            {
                $rrdata=$rdatas[ $rdata ];
            }

            if ($content[ $rrdata ]!=$newdate[ $rdata ])
            {
                $content[ $rrdata ]=$newdate[ $rdata ];
                array_push($updatedatas,$rrdata);
            }
        }
    }

    //*
    //* function UpdateDaylyContents, Parameter list: $date,&$content
    //*
    //* Updates Dayly Contents pages.
    //*

    function UpdateDaylyContent($date,&$content)
    {
        $updatesdatas=array();

        foreach ($this->DaylyContentDatas as $data)
        {
            $newvalue=$this->GetPOST($content[ "ID" ]."_".$data);

            if ($newvalue!=$content[ $data ])
            {
                if ($data=="Date")
                {
                    if (empty($newvalue)) { continue; }
                    $this->UpdateContentDateDatas($content,$data,$newvalue,$updatesdatas); 
                }

                $content[ $data ]=$newvalue;
                array_push($updatesdatas,$data);
            }
        }

        if (count($updatesdatas)>0)
        {
            $this->MySqlSetItemValues("",$updatesdatas,$content);
        }

        if (
              $this->GetPOST("Delete_".$content[ "ID" ])==1
              &&
              $this->MayDelete($content)
           )
        {
            $this->MySqlDeleteItem("",$content[ "ID" ]);

            return array();
        }

        return $content;
    }

    //*
    //* function UpdateDaylyContents, Parameter list: $dates,$contents
    //*
    //* Updates Dayly Contents pages.
    //*

    function UpdateDaylyContents($dates,$contents)
    {
        if ($this->GetPOST("Save")!=1) { return; }

        $n=1;
        foreach (array_keys($contents) as $id)
        {
            if (
                  $this->ApplicationObj->PeriodsObject->TrimesterEditable
                  (
                     $contents[ $id ][ "Semester" ],
                     $this->ApplicationObj->Disc
                  )
               )
            {
                continue;
            }
            $date=$dates[ $contents[ $id ][ "Date" ] ];
            $contents[ $id ]=$this->UpdateDaylyContent
            (
               $date,
               $contents[ $id ]
            );

            if (empty($contents[ $id ]))
            {
                unset($contents[ $id ]);
            }
        }

        $this->UpdateDiscNLessons();

        return $contents;
    }

    //*
    //* function UpdateDiscNLessons, Parameter list: $disc=array(),$class=array()
    //*
    //* Updates Disc NLessons tables.
    //* Does SQL sum queries, per semester.
    //*

    function UpdateDiscNLessons($disc=array(),$class=array())
    {
        if (empty($disc))  { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        for ($semester=1;$semester<=$disc[ "NAssessments" ];$semester++)
        {
            $where=array
            (
               "Class"     => $class[ "ID" ],
               "Disc"      => $disc[ "ID" ],
               "Semester"  => $semester,
            );

            $chs=$this->RowSum("",$where,"Weight");
            if (empty($chs)) { $chs=0; }


            $rwhere=array
            (
               "Class"     => $class[ "ID" ],
               "ClassDisc" => $disc[ "ID" ],
               "Assessment"  => $semester,
            );

            $nlessons=$rwhere;
            $nlessons["NLessons" ]=$chs;
            $nlessons["SecEdit" ]=1;

            $this->ApplicationObj->ClassDiscNLessonsObject->AddOrUpdate("",$rwhere,$nlessons);

        }
    }


    //*
    //* function UpdateAddContent, Parameter list: $disc=array(),$class=array()
    //*
    //* Adds content from AddContent form, if adequate.
    //*

    function UpdateAddContent($disc=array(),$class=array())
    {
        if (empty($disc))  { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $month=$this->CGI2Month();

        if (empty($month) && empty($semester)) { $month=$this->CurrentMonth(); }

        $dates=$this->ApplicationObj->PeriodsObject->GetPeriodMonthDates($month);

        $adddate=intval($this->GetPOST("Date"));
        $radddate=0;

        $datehash=array();
        foreach ($dates as $date)
        {
            if ($date[ "ID" ]==$adddate)
            {
                $radddate=$adddate;
                $datehash=$date;
            }
        }

        $query=$this->ScriptQueryHash();

        $created=0;
        if ($this->GetPOST("Add")==1 && $radddate>0)
        {
            $weight=intval($this->GetPOST("Weight"));
            if ($weight>0)
            {
                $this->ApplicationObj->PeriodsObject->Date2Trimester($this->ApplicationObj->Period,$datehash);
                $newcontent=array
                (
                   "Class" => $class[ "ID" ],
                   "Disc" => $disc[ "ID" ],
                   "Date" => $datehash[ "ID" ],
                   "DateKey" => $datehash[ "SortKey" ],
                   "Semester" => $datehash[ "Semester" ],
                   "Month" => $datehash[ "Month" ],
                   "Weight" => $weight,
                   "Content" => "",
                   "CTime" => time(),
                   "MTime" => time(),
                   "ATime" => time(),
                );

                $this->MySqlInsertItem("",$newcontent);
                $query[ "Created" ]=$newcontent[ "ID" ];
                //print $this->H(5,"Criado: ".$date[ "Name" ].", Aula com Peso ".$weight);
                //exit();
            }
        }

        $query=$this->Hash2Query($query);
        $url=preg_replace('/DaylyAddContents/',"DaylyContents",$this->ScriptExec($query));
        $url=preg_replace('/index.php/',"",$url);

        header( 'Location: '.$url);
    }
}

?>