<?php

class ClassesPrintsDaylies extends ClassesPrintsPrint
{
    //*
    //* function StudentsDaylyTables, Parameter list: $class,$disc,$currentdate,$month
    //*
    //* Generates student Daily tables for $class,$disc and $month.
    //* Paginate for students, itemspp $class[ "DayliesNStudentsPP" ].
    //*

    function StudentsDaylyTables($class,$disc,$currentdate,$month)
    {
        $no=$this->B("N&ordm;");
        $name=$this->B("NOME DO ACADÊMICO");
        if ($this->LatexMode())
        {
            $no="\\small{\\textbf{N$^o$}}";
            $name= "\\hspace{1.4cm} ".$name." \\hspace{1.4cm}";         
        }

        $row2=array($no,$name);
        $nfirst=2;
        if (!$this->LatexMode())
        {
            array_push($row2,"Matr. Data","Status","Status Data");     
            $nfirst+=3;
        }

        $row2=$this->B($row2);

        for ($n=1;$n<=$this->NFields;$n++)
        {
            array_push($row2,"");
        }

        array_push($row2,$this->B("F"),$this->B("M"));
        $titles=array
        (
           array
           (
              $this->MultiCell("",$nfirst),
              $this->MultiCell
              (
                 "FREQÜENCIA/DIAS LETIVOS",
                 $this->NFields
              ),
              "","",
           ),
           $row2,
        );

        if (
              $class[ "LastStudentsLast" ]==2
              &&
              preg_match('/^\d\d\d\d\d\d\d\d$/',$class[ "LastStudentsLastDate" ])
           )
        {
            $limdate=$class[ "LastStudentsLastDate" ];

            $this->ApplicationObj->ClassStudentsObject->ItemHashes=
                $this->ApplicationObj->ClassStudentsObject->StudentsDailyOrder($limdate);
        }

        $tables=array();
        $table=$titles;

        $nstudentspp=$this->GetDayliesNStudentsPP($class);

        $n=1;
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            $this->ClassDaylyStudentRow
            (
               $class,
               $disc,
               $this->GetMonthFirstDate($month),
               $this->GetNextMonthFirstDate($month),
               $student,
               $n,
               $table
            );

            if ($n==$nstudentspp+1)
            {
                array_push($tables,$table);
                $table=$titles;
            }

            //$n++; incremented by ClassDaylyStudentRow 
        }

        if (count($table)>count($titles))
        {
            array_push($tables,$table);
        }

        return $tables;
    }
}

?>