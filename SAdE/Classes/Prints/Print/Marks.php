<?php

class ClassesPrintsPrintMarks extends ClassesPrintsPrintDaylies
{   
    //*
    //* function StudentsMarkTables, Parameter list: $class,$disc
    //*
    //* Generates info table for class.
    //*

    function StudentsMarkTables($class,$disc)
    {
        $no=$this->B("N&ordm;");
        $name=$this->B("NOME DO ACADÊMICO");
        if ($this->LatexMode)
        {
            $no="\\small{\\textbf{N$^o$}}";
            $name= "\\hspace{1.4cm} ".$name." \\hspace{1.4cm}";         
        }

        $row=array($no,$name);

        for ($n=1;$n<=$this->NMFields;$n++)
        {
            array_push($row,"N\$_{".$n."}\$");
        }

        $titles=array($row);
        $tables=array();
        $table=$titles;

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

        $startdate = $this->ApplicationObj->DatesObject->DateID2SortKey($this->ApplicationObj->Period[ "StartDate" ]);
        $enddate = $this->ApplicationObj->DatesObject->DateID2SortKey($this->ApplicationObj->Period[ "EndDate" ]);

        $nstudentspp=$this->GetDayliesNStudentsPP($class);
 
        $n=1;
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            $this->ClassMarksStudentRow($class,$disc,$startdate,$enddate,$student,$n,$table);

            if ($n==$nstudentspp+1)
            {
                array_push($tables,$table);
                $table=$titles;
            }
        }

        if (count($table)>count($titles))
        {
            array_push($tables,$table);
        }

        return $tables;
    }


    //*
    //* function StudentSignaturesTables, Parameter list: $class,$disc
    //*
    //* Generates info table for class.
    //*

    function StudentSignaturesTables($class,$disc)
    {
        $nsigs=4;

        $no=$this->B("N&ordm;");
        $name=$this->B("NOME DO ACADÊMICO");
        if ($this->LatexMode)
        {
            $no="\\small{\\textbf{N$^o$}}";
            $name= "\\hspace{1.4cm} ".$name." \\hspace{1.4cm}";         
        }

        $row=array($no,$name);

        for ($n=1;$n<=$nsigs;$n++)
        {
            array_push($row,$this->B(""));
        }

        $titles=array($row);

       /* $month="02/2013"; */
        /* $firstdate=$this->GetMonthFirstDate($month); */
        /* $lastdate=$this->GetNextMonthFirstDate($month); */

        $tables=array();
        $table=$titles;

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

        $startdate = $this->ApplicationObj->DatesObject->DateID2SortKey($this->ApplicationObj->Period[ "StartDate" ]);
        $enddate = $this->ApplicationObj->DatesObject->DateID2SortKey($this->ApplicationObj->Period[ "EndDate" ]);

        $nstudentspp=$this->GetDayliesNStudentsPP($class);
 
        $n=1;
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            $this->ClassSignaturesStudentRow($class,$disc,$startdate,$enddate,$student,$nsigs,$n,$table);

            if ($n==$nstudentspp+1)
            {
                array_push($tables,$table);
                $table=$titles;
            }

            $n++;
        }

        if (count($table)>count($titles))
        {
            array_push($tables,$table);
        }

        return $tables;
    }


}

?>