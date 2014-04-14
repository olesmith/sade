<?php


class ClassDiscAbsencesLatexRow extends ClassDiscAbsencesTable
{
    //*
    //* function AbsencesLatexStudentMonthCells, Parameter list: $month,$mcontents,$chs,$page,$student,$n,$studchs,&$ch,$ncells,&$rncells
    //*
    //* Generates the month cells for $student.
    //*

    function AbsencesLatexStudentMonthCells($month,$mcontents,$chs,$page,$student,$n,$studchs,&$ch,$ncells,&$rncells)
    {
        $row=array();

        $m=1;
        foreach ($mcontents as $content)
        {
            $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($content[ "Date" ]);
            $status=$this->GetStudentStatusType($student,"",$datekey);

            if ($status==1) { continue; }
            if ($status==2) { break; }

            /* //Limit editing date */
            /* if (!empty($student[ "StudentHash" ][ "StatusDate1" ])) */
            /* { */
            /*     if ($student[ "StudentHash" ][ "StatusDate1" ]<$datekey) */
            /*     { */
            /*         $text=$this->ApplicationObj->StudentsObject->GetEnumValue("Status",$student[ "StudentHash" ]); */

            /*         if (preg_match('/^(\d\d\d\d)(\d\d)(\d\d)/',$student[ "StudentHash" ][ "StatusDate1" ],$matches)) */
            /*         { */
            /*             $text.=" ".$matches[3]."/".$matches[2]; */
            /*         } */

            /*         array_push */
            /*         ( */
            /*            $row, */
            /*            $this->MultiCell($text,$ncells-$rncells) */
            /*         ); */

            /*         return $row; */
            /*     } */
            /* } */

            array_push
            (
               $row,
               $this->DaylyAbsencesStudentAbsenceCell(0,$n,$month,$student,$m++,$content,$ch,$studchs)
            );

            $rncells++;
        }

        return $row;
    }

    //*
    //* function AbsencesStudentLatexSplitPageCells, Parameter list: $student,&$nleading,&$ntrailing
    //*
    //* Generates the month cells for $student.
    //*

    function AbsencesStudentLatexSplitPageCells($student,$page,&$nleading,&$ntrailing)
    {
        foreach ($page as $month => $contents)
        {
            foreach ($contents as $id => $content)
            {
                $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($content[ "Date" ]);
                $status=$this->GetStudentStatusType($student,"",$datekey);

                if ($status==0)
                {
                    continue;
                }
                elseif ($status==1) { $nleading++; }
                elseif ($status==2) { $ntrailing++; }
            }
        }
    }

    //*
    //*
    //* function AbsencesLatexStudentPageCells, Parameter list: $chs,$page,$student,$n,$studchs,&$ch
    //*
    //* Generates the month cells for $student.
    //*

    function AbsencesLatexStudentPageCells($chs,$page,$student,$n,$studchs,&$ch)
    {
        $latexsize="scriptsize";
        $row=array();
        $nleading=0;
        $ntrailing=0;
        $this->AbsencesStudentLatexSplitPageCells($student,$page,$nleading,$ntrailing);

        if ($nleading>0)
        {
            array_push
            (
               $row,
               $this->MultiCell
               (
                  "\\begin{".$latexsize."}".
                  "Ingresso ".$this->SortTime2Date($student[ "StudentHash" ][ "MatriculaDate" ]).
                  "\\end{".$latexsize."}".
                   "",
                   $nleading
               )
            );
        }


        $ch=0;
        $ncells=0;
        foreach ($page as $month => $mcontents)
        {
            $m=1;
            foreach ($mcontents as $content)
            {
                $ncells++;
            }
        }

        $rncells=0;
        foreach ($page as $month => $mcontents)
        {
            $row=array_merge
            (
               $row,
               $this->AbsencesLatexStudentMonthCells($month,$mcontents,$chs,$page,$student,$n,$studchs,$ch,$ncells,$rncells)
            );
        }

        if ($ntrailing>0)
        {
            array_push
            (
               $row,
               $this->MultiCell
               (
                  "\\begin{".$latexsize."}".
                  $this->ApplicationObj->StudentsObject->GetEnumValue("Status",$student[ "StudentHash" ])." ".
                  $this->SortTime2Date($student[ "StudentHash" ][ "StatusDate1" ]).
                  "\\end{".$latexsize."}".
                   "",
                  $ntrailing
               )
            );
        }

        return $row;
    }


    //*
    //* function AbsencesLatexStudentRow, Parameter list: $chs,$page,$student,$n,$lastpage
    //*
    //* Generates the latex row for one student.
    //*

    function AbsencesLatexStudentRow($disc,$chs,$page,$student,$n,$lastpage)
    {
        $studchs=$this->ReadDaylyStudent($chs,$student);

        $row=$this->DaylyAbsencesStudentCells($n,$student);
        //array_pop($row); //remove status date

        if (empty($this->StudentsAcc[ $student[ "ID" ] ]))
        {
            $this->StudentsAcc[ $student[ "ID" ] ]=0;
        }

        $ch=0;
        $row=array_merge
        (
           $row,
           $this->AbsencesLatexStudentPageCells($chs,$page,$student,$n,$studchs,$ch)
        );

        $sigma="-";
        if ($ch>0) { $sigma=sprintf("%02d",$ch); }

        $ssigma="-";
        if ($this->StudentsAcc[ $student[ "ID" ] ]>0) { $ssigma=sprintf("%02d",$this->StudentsAcc[ $student[ "ID" ] ]); }

        $rch=$ch+$this->StudentsAcc[ $student[ "ID" ] ];
        $sssigma="-";
        if ($ch>0) { $sssigma=sprintf("%02d",$rch); }

        array_push
        (
           $row,
           $this->B($sigma),
           $this->B($ssigma),
           $this->B($sssigma)
        );

        $this->StudentsAcc[ $student[ "ID" ] ]+=$ch;

        if ($lastpage)
        {
            $status="-";
            $percent="-";
            if (!empty($chs[ "Period" ]))
            {
                $percent=($this->StudentsAcc[ $student[ "ID" ] ]*100.0)/(1.0*$chs[ "Period" ]);

                $status="AP";
                if ($percent>$disc[ "AbsencesLimit" ])
                {
                    $status="RE";
                }

                if ($percent<10.0) { $percent="0".$percent; }
            }

            array_push
            (
               $row,
               sprintf("%.1f",$percent),
               $status
            );
        }

        return $row;
    }
}

?>