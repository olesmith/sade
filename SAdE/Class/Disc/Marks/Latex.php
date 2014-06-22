<?php


class ClassDiscMarksLatex extends ClassDiscMarksTables
{
    //*
    //* function MarksLatex, Parameter list: $class,$disc,&$pageno
    //*
    //* Handles printing of Dayly Marks.
    //*

    function MarksLatex($class,$disc,$pageno=0)
    {
        $tables=$this->DaylyMarksTables($this->ApplicationObj->ClassDiscAbsencesObject->NStudentsPerPage);

        $head=
            "\\vspace{-1cm}\n\n".
            "\\LARGE{\\textbf{Relatório de Notas}}\n\n".
            $this->ApplicationObj->ClassesObject->LatexTable
            (
               "",
               $this->ApplicationObj->ClassesObject->ClassDaylyInfoTable($class,$disc),
               0,
               FALSE,
               TRUE,
               0,
               FALSE //no grey rows
            ).
            "\n\n\\vspace{0.25cm}\n";

        $tail=
            //"\\vspace{0.25cm}\n".
            $this->ApplicationObj->ClassesObject->LatexSignatureLine().
            //"\\vspace{0.25cm}\n".
            $this->ApplicationObj->ClassMarksObject->DiscMarkLatexLegend($disc).
            "";

        $latex="";
        foreach (array_keys($tables) as $id)
        {
            $latex.=
                $this->LatexOnePage
                (
                 //"\\cfoot{".$pageno."}\n".
                   $head.
                   $this->LatexTable
                   (
                      "",
                      $tables[ $id ]
                   ).
                   $tail
                ).
                "\n\\clearpage\n\n";

            $pageno++;
        }

        return $latex;
    }

    //*
    //* function PrintMarksLatex, Parameter list: $class=array(),$disc=array(),$month=""
    //*
    //* Prints and generates Contents Latex page(s).
    //*

    function PrintMarksLatex($class=array(),$disc=array())
    {
        $this->ApplicationObj->ClassStudentsObject->LatexStudents();

        $this->InitLatexData();
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        if (empty($disc))  { $disc =$this->ApplicationObj->Disc; }

        $page=1;
        $latex=
            $this->LatexHeadLand().
            $this->MarksLatex($class,$disc,$page).
            $this->LatexTail().
            "";

        //$this->ShowLatexCode($latex);exit();

        $texfilename=
            "Marks.".
            $this->CurrentYear().".".
            sprintf("%02d",$this->CurrentMonth()).".".
            sprintf("%02d",$this->CurrentDate()).".".
            time().".".
            ".tex";

        $this->RunLatexPrint($texfilename,$latex);
        exit();
    }

}

?>