<?php


class ClassDiscAbsencesLatexTitles extends ClassDiscAbsencesLatexRow
{
    //*
    //* function AbsencesLatexStudentTitles, Parameter list: $chs,$page,&$cht,$lastpage
    //*
    //* Generates the latex title row.
    //*

    function AbsencesLatexStudentTitles($chs,$page,&$cht,$lastpage)
    {
        $table=array
        (
         array($this->MultiCell("",count($this->StudentData)+1)),
         array($this->MultiCell("Acadêmico",count($this->StudentData)+1)),
           $this->B($this->DaylyAbsencesStudentDataTitles()),
        );

        $ch=0;
        $n=0;
        foreach ($page as $month => $mcontents)
        {
            array_push
            (
               $table[0],
               $this->MultiCell($this->Months_Short[ $month-1 ],count($mcontents))
            );

            $mdcontents=array();
            foreach ($mcontents as $content)
            {
                $date=$this->ApplicationObj->DatesObject->MySqlItemValue
                (
                   "",
                   "ID",
                   $content[ "Date" ],
                   "Day"
                );

                if (!isset($mdcontents[ $date ])) { $mdcontents[ $date ]=array(); }

                array_push($mdcontents[ $date ],$content);
                $ch+=$content[ "Weight" ];
                $n++;
            }

            foreach (array_keys($mdcontents) as $date)
            {
                array_push
                (
                   $table[1],
                   $this->MultiCell
                   (
                      sprintf("%02d",$date),
                      count($mdcontents[ $date ])
                   )
                );

                foreach ($mdcontents[ $date ] as $content)
                {
                    array_push($table[2],sprintf("%02d",$content[ "Weight" ]));
                }
            }
        }

        array_push($table[0],$this->MultiCell("",3));
        array_push
        (
           $table[1],
           $this->B("\$\\mathbf \\Sigma\$"),
           $this->B("\$\\mathbf \\rightarrow\$"),
           $this->B("\$\\mathbf{\\Sigma\\Sigma}\$")
        );

        $sigma="-";
        if ($cht>0)
        {
            $sigma=sprintf("%02d",$cht);
        }

        array_push
        (
            $table[2],
            $this->B(sprintf("%02d",$ch)),
            $this->B($sigma),
            $this->B(sprintf("%02d",$ch+$cht))
        );

        if ($lastpage)
        {
            array_push($table[0],$this->MultiCell("",2));
            array_push($table[1],$this->MultiCell("",2));
            array_push
            (
               $table[2],
               $this->B("\\%"),
               $this->B("R")
            );
            
        }

        $cht+=$ch;

        return $table;
    }
}

?>