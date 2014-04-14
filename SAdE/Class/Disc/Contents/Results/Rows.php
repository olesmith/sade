<?php

class ClassDiscContentsResultsRows extends ClassDiscContentsWheres
{
    //*
    //*
    //* function DiscLessonDates, Parameter list: $disc
    //*
    //* Returns the days of the weeks $disc has lessons.
    //*

    function DiscLessonDates($disc)
    {
        $wdays=array();
        foreach ($disc[ "Lessons" ] as $lesson)
        {
            if (!empty($lesson[ "WeekDay" ]))
            {
                $wdays[ $lesson[ "WeekDay" ] ]=1;
            }
        }

        return array_keys($wdays);
    }

    //*
    //*
    //* function DiscDate2NProgrammedLessons, Parameter list: $disc,$date
    //*
    //* Adds contents cells to $row, and adds additional rows, if necessary.
    //*

    function DiscDate2NProgrammedLessons($disc,$date)
    {
        $nchprev=0;
        foreach ($disc[ "Lessons" ] as $lesson)
        {
            if ($lesson[ "WeekDay" ]==$date[ "WeekDay" ])
            {
                $nchprev+=$this->LessonTimeLoad($lesson);
            }
        }

        return $nchprev;
    }

    //*
    //*
    //* function LessonTimeLoad, Parameter list: $lesson
    //*
    //* Calculates lesson timeload.
    //*

    function LessonTimeLoad($lesson)
    {
        $hours=array();
        $mins=array();
        foreach (array("Start","End") as $key)
        {
            if (!empty($lesson[ $key]))
            {
                $lesson[ $key ]=preg_match('/(\d\d):(\d\d)$/',$lesson[ $key ],$matches);
                array_push($hours,$matches[1]);
                array_push($mins,$matches[2]);
            }
        }

        $nmins=$mins[1]-$mins[0];
        $nhours=$hours[1]-$hours[0];
        if ($nmins>0) { $nhours++; }

        return $nhours;
    }

    //*
    //*
    //* function DateProgrammedLessonsRows, Parameter list: $period,$disc,$date,&$nchprev
    //*
    //* Adds contents cells to $row, and adds additional rows, if necessary.
    //*

    function DateProgrammedLessonsRows($period,$disc,$date,&$nchprev)
    {
        $nchprev=0;

        $n=1;
        $rows=array($this->B(array("No.","Horário","CH")));
        foreach ($disc[ "Lessons" ] as $lesson)
        {
            if ($lesson[ "WeekDay" ]==$date[ "WeekDay" ])
            {
                $texts=array();
                foreach (array("Start","End") as $key)
                {
                    if (!empty($lesson[ $key]))
                    {
                        array_push($texts,$lesson[ $key ]);
                   }
                }
                $nhours=$this->LessonTimeLoad($lesson);

                $nchprev+=$nhours;
                array_push($rows,array($n,join("-",$texts),$nhours."h"));
            }
        }

        return $rows;
    }

    //*
    //*
    //* function DiscDate2NWeightRegistered, Parameter list: $disc,$date
    //*
    //* Counts CH 
    //*

    function DiscDate2NWeightRegistered($disc,$date)
    {
        $contents=$this->SelectHashesFromTable
        (
           "",
           array
           (
              "Class" => $this->ApplicationObj->Class[ "ID" ],
              "Disc"  => $disc[ "ID" ],
              "Date"  => $date[ "ID" ],
           ),
           array("Weight"),
           FALSE,
           "DateKey"
        );

        $nchpreg=0;
        foreach ($contents as $content)
        {
            $nchpreg+=$content[ "Weight" ];
        }

        return $nchpreg;
     }

    //*
    //*
    //* function DateContentsLessonsRows, Parameter list: $period,$disc,$date,&$nchpreg
    //*
    //* Adds contents cells to $row, and adds additional rows, if necessary.
    //*

    function DateContentsLessonsRows($period,$disc,$date,&$nchpreg)
    {
        $contentsdata=array("Weight","Content");
        $contents=$this->SelectHashesFromTable
        (
           "",
           array
           (
              "Class" => $this->ApplicationObj->Class[ "ID" ],
              "Disc"  => $disc[ "ID" ],
              "Date"  => $date[ "ID" ],
           ),
           $contentsdata,
           FALSE,
           "DateKey"
        );

        $titles=$this->GetDataTitles($contentsdata);
        array_unshift($titles,"No.");
        $rows=array($this->B($titles));
        $n=1;
        foreach ($contents as $content)
        {
            $row=array($this->B($n++));
            foreach ($contentsdata as $data)
            {
                $words=preg_split('/\s+/',$content[ $data ]);
                if (count($words)>5)
                {
                    $words=array_splice($words,0,5);
                    array_push($words,"...");
                }

                array_push($row,$this->MakeShowField($data,$content,join(" ",$words)));
            }

            $nchpreg+=$content[ "Weight" ];

            array_push($rows,$row);
        }

        return $rows;
    }

    //*
    //*
    //* function GenDatesRegisterRow, Parameter list: $period,$disc,$date,$ch
    //*
    //* Generates rows, being only one, with the add input html controls.
    //*

    function GenDatesRegisterRow($period,$disc,$date,$ch)
    {
        $values=array("",1,2,3,4,5,6,7,8,9,10);

        $row=array
        (
            $this->MakeSelectField
            (
               "N_".$date[ "ID" ],
               $values,
               $values,
               1
            ),
            $this->MakeSelectField
            (
               "W_".$date[ "ID" ],
               $values,
               $values,
               $ch
               
            ),
            $this->MakeCheckBox
            (
               "I_".$date[ "ID" ],
               1,
               FALSE,
               FALSE,
               array
               (
                  "TITLE" => "Selecionar para Lançar!",
                  "TABINDEX" => 3,
               )
            )
        );

        return $row;
    }

     //*
    //*
    //* function GenDatesTotalsRow, Parameter list: $nchprev,$nchreg
    //*
    //* Generates Date summed totals row.
    //*

    function GenDatesTotalsRow($nchprev,$nchreg)
    {
        $row=array
        (
           $this->MultiCell("",$this->NDateCols),
           "",
           $this->B($this->ApplicationObj->Sigma),
           $nchprev,
           "",
           $this->B($this->ApplicationObj->Sigma),
           $nchreg,
        );

        return $row;
    }


    //*
    //*
    //* function GenDatesResultsRows, Parameter list: $period,$disc,$n,$date,&$rdate,&$table
    //*
    //* Generates search results rows for $date.
    //*

    function GenDatesResultsRows($period,$disc,$n,$date,&$names,&$table)
    {
        $rdate=$date;
        foreach (array_keys($names) as $data)
        {
            if ($names[ $data ]!=$date[ $data ]) { $rdate[ $data ]=$date[ $data ]; }
            else                                 { $rdate[ $data ]=""; }

            $names[ $data ]=$date[ $data ];
        }

        $row=array($this->B($n++));

        foreach ($this->ResultsDatesData as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->DatesObject->MakeShowField($data,$rdate)
            );
        }

        $empties=array($this->MultiCell("",count($row)));

        $nchprev=0;
        $rows=$this->DateProgrammedLessonsRows($period,$disc,$date,$nchprev);

        $nchpreg=0;
        $rrows=$this->DateContentsLessonsRows($period,$disc,$date,$nchpreg);

        $max=$this->Max(count($rows),count($rrows));
        for ($m=0;$m<$max;$m++)
        {
            if (!empty($rows[ $m ]))
            {
                $row=array_merge($row,$rows[ $m ]);
            }
            else
            {
                array_push($row,$this->MultiCell("",$this->NPreviewCols));
            }

            array_push($row,$this->MultiCell("",$this->NRegisterCols));

            if (!empty($rrows[ $m ]))
            {
                $row=array_merge($row,$rrows[ $m ]);
            }
            else
            {
                array_push($row,$this->MultiCell("",$this->NRegisteredCols));
            }



            array_push($table,$row);

            $row=$empties;
        }


        array_push
        (
           $table,
           array_merge
           (
              array
              (
                 $this->MultiCell("",$this->NDateCols),
                 "",
                 $this->B($this->ApplicationObj->Sigma."CH"),
                 $nchprev,
                 $this->B("Aulas"),
                 $this->B("CH"),
                 $this->B("Lançar"),
                 $this->B($this->ApplicationObj->Sigma."CH"),
                 $nchpreg,
                 ""
              )
           ),
           array_merge
           (
              array
              (
                 $this->MultiCell("",$this->NDateCols),
                 $this->MultiCell("",$this->NPreviewCols),
              ),
              $this->GenDatesRegisterRow($period,$disc,$date,$nchprev-$nchpreg),
              array
              (
                 $this->MultiCell("",$this->NRegisterCols),
              )
           )
        );

        return $table;
    }
}

?>