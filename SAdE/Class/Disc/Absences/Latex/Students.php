<?php


class ClassDiscAbsencesLatexStudents extends ClassDiscAbsencesLatexTitles
{
    //*
    //* function AbsencesLatexStudentPage, Parameter list: $disc,$chs,$page,$students,$firstn,&$rch
    //*
    //* Generates one Absences Latex page for $students.
    //*

    function AbsencesLatexStudentsPage($disc,$chs,$page,$students,$firstn,&$rch,$lastpage)
    {
        $table=$this->AbsencesLatexStudentTitles($chs,$page,$rch,$lastpage);

        $n=$firstn;
        foreach ($students as $student)
        {
            array_push($table,$this->AbsencesLatexStudentRow($disc,$chs,$page,$student,$n++,$lastpage));
        }

        return $this->LatexTable("",$table);
    }

    //*
    //* function AbsencesLatexStudents, Parameter list: $class,$disc,$chs,$page,&$rch,&$pageno,$lastpage
    //*
    //* Generates Absences Latex page for all students (again, may be several pages).
    //*

    function AbsencesLatexStudents($class,$disc,$chs,$page,&$rch,&$pageno,$lastpage)
    {
        $months=array_keys($page);
        foreach (array_keys($months) as $id)
        {
            $months[ $id ]=$this->Months_Short[ $months[ $id ]-1 ];
        }

        $head=
            "\\hspace{0.5cm}\\vspace{-1.25cm}\n\n".
            "\\LARGE{\\textbf{Relatório de Freqüências}}\n\n".
            $this->ApplicationObj->ClassesObject->LatexTable
            (
               "",
               $this->ApplicationObj->ClassesObject->ClassDaylyInfoTable
               (
                  $class,
                  $disc,
                  join(", ",$months)
               ),
               0,
               FALSE,
               TRUE,
               0,
               FALSE //no grey rows
            ).
            "\n\n\\vspace{0.25cm}\n";

        $tail=
            "\\vspace{0.05cm}\n".
            $this->ApplicationObj->ClassesObject->LatexSignatureLine();

        $n=1;
        $rpageno=0;
        $students=array(0 => array());
        foreach ($this->ApplicationObj->Students as $student)
        {
            if ($n>$this->NStudentsPerPage)
            {
                $n=1;
                $rpageno++;
                $students[ $rpageno ]=array();
            }

            array_push($students[ $rpageno ],$student);
            $n++;
        }

        $latex="";
        $nstud=1;

        foreach ($students as $spage => $pstudents)
        {
            $latex.=
                $this->LatexOnePage
                (
                 // "\\cfoot{".($pageno++)."}\n".
                   $head.
                   $this->AbsencesLatexStudentsPage($disc,$chs,$page,$pstudents,$nstud,$rch,$lastpage).
                   $tail
                ).
                "\n\\clearpage\n\n";


            $nstud+=count($pstudents);
        }

        return $latex;
    }
}

?>