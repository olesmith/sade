<?php

class ClassesPrintsLatex extends ClassesPrintsTable
{
    //*
    //* function LatexSignatureLine, Parameter list: $space=1.5
    //*
    //* Generates latex line Lessons previewed, given,etc.
    //*

    function LatexSignatureLineShort($width1=5,$width2=1,$space=0.5,$space1=1.0,$space2=1.5)
    {
        return
            "\\vfill\n".
            "\\begin{small}\n".
            "   \\begin{tabular}{ccc}\n".
            "      \\underline{\\hspace{".$width1."cm}} & ".
            "      \\hspace{".$space."cm} & ".
            "      \\underline{\\hspace{".$width1."cm}} \\\\\n".
            "   Assinatura do(as) Professor(as) & & Assinatura da Coordena\\c{c}\\~a\n".
            "   \\end{tabular}\n".
            "\\end{small}\n".
            "\n\\clearpage\n\n".
            "";
     }

    //*
    //* function LatexSignatureLine, Parameter list: $space=1.5
    //*
    //* Generates latex line Lessons previewed, given,etc.
    //*

    function LatexSignatureLine($width1=5,$width2=1,$space=0.5,$space1=1.0,$space2=1.5)
    {
        return
            "\\hspace{1cm}\\vspace{1cm}\n".
            "\\begin{small}\n".
            "   \\begin{tabular}{ccccc}\n".
            "     \\underline{\\hspace{".$space."cm}}/".
            "     \\underline{\\hspace{".$space."cm}}/".
            "     \\underline{\\hspace{".$space1."cm}} &\n".
            "     \\hspace{".$space."cm} &\n".
            "     \\underline{\\hspace{".$width1."cm}}".
            "     \\hspace{".$space."cm}".
            "     \\underline{\\hspace{".$width1."cm}}".
            "     \\hspace{".$space."cm}".
            "     \\underline{\\hspace{".$width1."cm}} &\n".
            "     \\hspace{".$space."cm} &\n".
            "      \\underline{\\hspace{".$width1."cm}} \\\\\n".
            "   Data & & Assinatura do(as) Professor(as) & & Assinatura da Coordena\\c{c}\\~ao\n".
            "   \\end{tabular}\n".
            "\\end{small}\n\n".
            //"\n\\clearpage\n\n".
            "";
     }

    //*
    //* function LatexResponsibleSignatureLine, Parameter list: 
    //*
    //* Generates latex line Lessons previewed, given,etc.
    //*

    function LatexResponsibleSignatureLine($width1=2,$width2=0.5,$width3=1.0,$width4=5.0)
    {
        $table=array
        (
           array("","","",),
           array("","","",),
           array("","","",),
           array
           (
              "\\underline{\\hspace{".$width1."cm}},",
              "\\underline{\\hspace{".$width2."cm}}/"."\\underline{\\hspace{".$width2."cm}}/"."\\underline{\\hspace{".$width3."cm}}",
              "\\underline{\\hspace{".$width4."cm}}"
           ),
           array
           (
              "Local",
              "Data",
              "Assinatura do Responsável",
           ),
        );
        return
            "\n\n".
            "\\begin{small}\n".
            $this->LatexTable("",$table,"ccc",FALSE,FALSE).
            /* "\\begin{tabular}{ccc}\n". */
            /* "&\\\\\n". */
            /* "&\\\\\n". */
            /* "\\underline{\\hspace{".$width1."cm}},&". */
            /* "\\underline{\\hspace{".$width2."cm}}/". */
            /* "\\underline{\\hspace{".$width2."cm}}/". */
            /* "\\underline{\\hspace{".$width3."cm}} &\n". */
            /* "\\underline{\\hspace{".$width4."cm}}\\\\\n". */
            /* "Local e Data & Assinatura do Responsável\\\\\n". */
            /* "\\end{tabular}". */
            "\\end{small}".
            "";
     }


    //*
    //* function LessonsStatusLatexLine, Parameter list: $space=1.5
    //*
    //* Generates latex line Lessons previewed, given,etc.
    //*

    function LessonsStatusLatexLine($space=1.5)
    {
        return
            "\\begin{flushleft}\n".
            "   \\begin{small}\n".
            "      AULAS PREVISTAS: ".
                   "\\underline{\\hspace{".$space."cm}} ".
                   "\\hspace{".$space."cm}\n".
            "      AULAS DADAS: ".
                  "\\underline{\\hspace{".$space."cm}} ".
                   "\\hspace{".$space."cm} \n".
            "      ENCERRADO EM: ".
                   "\\underline{\\hspace{".$space."cm}}/".
                   "\\underline{\\hspace{".$space."cm}}/".
                   "\\underline{\\hspace{".$space."cm}} ".
                   "\\\\\n".
            "   \\end{small}\n".
            "\\end{flushleft}\n";
     }

    //*
    //* function DailyBackPage, Parameter list: $class,$disc,$month
    //*
    //* Generates info table for class as Latex and tries to generate the PDF.
    //*

    function DailyBackPage($class,$disc,$month)
    {
        $vspace=0.25;
        $space=2.25;
        $title="MAT\\'ERIA LECIONADA";
        $nlines=50;
        $ncomments=9;
        if ($class[ "DayliesOrientation" ]==1)
        {
            $space=4.5;
            $nlines=30;
            $ncomments=5;
        }

        $table=array
        (
            array
            (
               $this->B("Data"),
               "\\hspace{".$space."cm} ".$this->B($title)." \\hspace{".$space."cm} ",
               $this->B("Data"),
               "\\hspace{".$space."cm} ".$this->B($title)." \\hspace{".$space."cm} ",
            ),
        );

        for ($n=1;$n<=$nlines;$n++)
        {
            array_push($table,array("","","",""));
        } 

        $text=$this->B("OBSERVA\c{C}\~OES:");
        for ($n=1;$n<=$ncomments;$n++)
        {
            array_push($table,array($text));
            $text="";
        }

        $tmp=$this->LatexData[ "NItemsPerPage" ];
        $this->LatexData[ "NItemsPerPage" ]=200;

        $latex=
            $this->LatexTable
            (
               "",
               $this->ClassDaylyInfoTable($class,$disc,$month)
            ).
            "\\vspace{".$vspace."cm}\n\n".
            $this->LatexTable("",$table).
            "\\vspace{".$vspace."cm}\n\n".
            $this->LessonsStatusLatexLine().
            "";

        $this->LatexData[ "NItemsPerPage" ]=$tmp;

        //print preg_replace('/\n/',"<BR>",$latex);exit();
        return $latex;
    }

    //*
    //* function LatexClassDayly, Parameter list: $class,$disc,$month
    //*
    //* Generate Latex pages for $class $disc and $month. 
    //* Paginate for students, itemspp $class[ "DayliesNStudentsPP" ].
    //*

    function LatexClassDayly($class,$disc,$month)
    {
        $head=
            $this->ClassDaylyTitle().
            $this->LatexTable
            (
               "",
               $this->ClassDaylyInfoTable($class,$disc,$month),
               0,
               FALSE,
               TRUE,
               0,
               FALSE //no grey rows
            ).
            "\n\n\\vspace{0.25cm}\n";

        $tail=
            $this->LatexSignatureLine().
            "\n\\clearpage\n\n";

        $currentdate=$this->GetNextMonthFirstDate($month);

        if (count($this->ApplicationObj->ClassStudentsObject->ItemHashes)==0)
        {
            return $head."\n\nNenhum aluno na turma...".$tail;
        }


        $latex="";
        foreach ($this->StudentsDaylyTables($class,$disc,$currentdate,$month) as $table)
        {
            $latex.=
                $head.
                $this->LatexTable("",$table);

            if ($class[ "DayliesBackPage" ]==2)
            {
                $latex.=
                    $tail.
                    $this->DailyBackPage($class,$disc,$month);
                
            }

            $latex.=$tail;
        }

        return $latex;
    }

    //*
    //* function LatexClassMarksSheet, Parameter list: $class,$disc
    //*
    //* Generates info table for class as Latex and tries to generate the PDF.
    //* Paginate for students, itemspp $class[ "DayliesNStudentsPP" ].
    //*

    function LatexClassMarksSheet($class,$disc)
    {
        $head=
            $this->ClassMarksTitle().
            $this->LatexTable
            (
               "",
               $this->ClassDaylyInfoTable($class,$disc)
             ).
            "\n\n\\vspace{0.25cm}\n";

        $tail=$this->LatexSignatureLineShort();

        $latex="";
        foreach ($this->StudentsMarkTables($class,$disc) as $table)
        {
            $latex.=
                $head.
                $this->LatexTable("",$table);

            $latex.=$tail;
        }

        return $latex;
    }

    //*
    //* function LatexSignaturesSheet, Parameter list: $class,$disc
    //*
    //* 
    //*

    function LatexSignaturesSheet($class,$disc)
    {
        $head=
            $this->ClassSignaturesTitle().
            $this->LatexTable
            (
               "",
               $this->ClassDaylyInfoTable($class,$disc)
             ).
            "\n\n\\vspace{0.25cm}\n";

        $tail=$this->LatexSignatureLineShort();

        $latex="";
        foreach ($this->StudentSignaturesTables($class,$disc) as $table)
        {
            $latex.=
                $head.
                $this->LatexTable("",$table);

            $latex.=$tail;
        }

        return $latex;
    }


}

?>