<?php


class ClassDiscContentsRow extends ClassDiscContentsCalendar
{
    //*
    //*
    //* function DatesSqlWhere, Parameter list: $period
    //*
    //* Generates dates sql where, from Search form.
    //*

    function DatesSqlWhere($period)
    {
        $wdays=$this->ApplicationObj->SchoolsObject->SchoolWeekDays(FALSE);

        $wheres=array
        (
           "WeekDay IN ('".join("','",$wdays)."')",
           "Type IN ('1','2','3')",
        );

        array_push
        (
           $wheres,
           $this->PeriodSearchSqlWhere($period)
        );

        return join(" AND ",$wheres);
    }



    //*
    //* function MakeDatesSelect, Parameter list: $data,$content,$edit
    //*
    //* Generates dates selectfield for Date, include only days active
    //* to school and non holiday/recess.
    //*

    function MakeDatesSelect($data,$content,$edit)
    {
        if ($edit==0)
        {
            return $this->ApplicationObj->DatesObject->DateID2Name($content[ $data ]);
        }

        if (empty($this->ContentsDateIDs))
        {
            $where=$this->DatesSqlWhere($this->ApplicationObj->Period);

            $this->ContentsDateIDs=array();
            $this->ContentsDateNames=array();
            foreach (
                       $this->ApplicationObj->DatesObject->SelectHashesFromTable
                       (
                          "",
                          $where,
                          array("ID","WeekDay","SortKey"),
                          FALSE,
                          "SortKey"
                       )
                       as $date
                    )
            {
                array_push($this->ContentsDateIDs,$date[ "ID" ]);
                array_push
                (
                   $this->ContentsDateNames,
                   preg_replace('/^(\d\d\d\d)(\d\d)(\d\d)$/',"$3/$2/$1",$date[ "SortKey" ]).", ".
                   $this->WeekDays[ $date[ "WeekDay" ]-1 ]
                );
            }

            
        }

        return $this->MakeSelectField
        (
           "Date",
           $this->ContentsDateIDs,
           $this->ContentsDateNames,
           $content[ "Date"]
        );
    }

    //*
    //* function DaylyContentsContentRow, Parameter list: $edit,$n,$content,$dates,&$lastdates
    //*
    //* Generates content row for $content.
    //*

    function DaylyContentsContentRow($edit,$n,&$content,$dates,&$lastdates)
    {
        if (empty($dates[ $content[ "Date" ] ]))
        {
            $dates[ $content[ "Date" ] ]=$this->ApplicationObj->DatesObject->SelectUniqueHash
            (
               "",
               array("ID" => $content[ "Date" ]),
               FALSE,
               $this->ReadDateDatas
             );
        }

        $row=array($this->B($n));

        if (empty($content[ "Date" ]))
        {
            $content=$this->MakeSureWeHaveRead("",$content,array("Date"));
        }

        if (empty($content[ "Date" ]))
        {
            $month=$this->GetGET("Month");
            if (empty($month)) { $month=$this->CurrentMonth(); }

            $content[ "Date" ]=$this->ApplicationObj->DatesObject->GetFirstDateInMonth
            (
               $this->ApplicationObj->Period[ "Year" ],
               $month
            );

            $this->MySqlSetItemValue("","ID",$content[ "ID" ],"Date",$content[ "Date" ]);
        }


        if (empty($dates[ $content[ "Date" ] ]))
        {
            $dates[ $content[ "Date" ] ]=$this->ApplicationObj->DatesObject->SelectUniqueHash
            (
               "",
               array("ID" => $content[ "Date" ]),
               FALSE
            );
        }
        $date=$dates[ $content[ "Date" ] ];

       //First Date data
        foreach ($this->DaylyDateDatas as $data)
        {
            $cell="";
            if ($data=="Date" && $edit==1)
            {
                $cell=$this->MakeDatesSelect($data,$content,$edit);
            }
            elseif (
                      !isset($lastdates[ $data ])
                      ||
                      $date[ $data ]!=$lastdates[ $data ]
                   )
            {
                $cell=$this->ApplicationObj->DatesObject->MakeShowField($data,$date);
            }

            array_push($row,$cell);
        }


        //Now contents data
        $tab=1;
        foreach ($this->DaylyContentDatas as $data)
        {
            array_push
            (
               $row,
               $this->MakeField($edit,$content,$data,TRUE,$tab++)
            );
        }

        //Update $lastdates, to supress repeated Year, Trimester,.. values
        foreach (array_keys($lastdates) as $key)
        {
            $lastdates[ $key ]=$date[ $key ];
        }

        //Deletebox - if allowed
        $deletebox="";
        if ($edit==1)
        {
            if ($this->MayDelete($content))
            {
                $deletebox=$this->MakeCheckBox("Delete_".$content[ "ID" ],1,FALSE,FALSE,array("TABINDEX" => '100'));
            }
            else
            {
                if (preg_match('/\S/',$content[ "Content" ]))
                {
                    $deletebox="Conteúdo Lançado - Indeletável";
                }
                else
                {
                    $deletebox="Faltas Lançadas - Indeletável";
                }

                $deletebox=$this->B($deletebox);
            }
        }

        array_push($row,$deletebox);

        return $row;
    }
}

?>