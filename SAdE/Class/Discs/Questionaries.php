<?php


class  ClassDiscsQuestionaries extends ClassDiscsTitleRows
{
    //Array storing questionary.
    var $Questions=array();




    //*
    //* function QuestionariesLatexTable, Parameter list: $student=array()
    //*
    //* Generate latex code for HandleQuestionaries.
    //*

    function QuestionariesLatexTable($student=array())
    {
        $this->ShowAbsencesPercent=FALSE;

        if (empty($student)) { $student=$this->ApplicationObj->Student; }

        $this->ApplicationObj->ClassQuestionsObject->LatexMode=$this->LatexMode;
        $this->ApplicationObj->ClassObservationsObject->LatexMode=$this->LatexMode;

        $spec="|c|l|";
        for ($n=1;$n<=$this->ApplicationObj->Disc[ "NAssessments" ];$n++) { $spec.="p{0.5cm}|"; }
        $spec.="c|";

        $latex=
            $this->ApplicationObj->ClassQuestionsObject->LatexLegendTable().
            $this->LatexTable
            (
               "",
               $this->ApplicationObj->ClassQuestionsObject->QuestionariesTable
               (
                  $this->ApplicationObj->Class,
                  $student,
                  0,0
               ),
               $spec
            ).
            "\n\\clearpage\n\n".
            $this->LatexTable
            (
               "",
               $this->ApplicationObj->ClassObservationsObject->ObservationsTable
               (
                  $this->ApplicationObj->Class,
                  $student,
                  0,0
               ),
               "|l|p{8cm}|p{8cm}|"
            ).
            "";
        $latex.="\\vspace{0.5cm}\n";
        $latex.="\\begin{normalsize}\n";

        $box1="\\textbf{\\underline{Assinatura do Responsável:}}\n\n";
        $box1.="\\begin{tabular}{ll}\n";
        for ($n=1;$n<=$this->ApplicationObj->Disc[ "NAssessments" ];$n++)
        {
            $box1.=
                "&\\\\\n".
                "Trimestre ".$this->Latins[ $n ].":&\\underline{\\hspace{6cm}}\\\\\n";

        }
        $box1.="\\end{tabular}";

        $box2=
            "\\begin{flushright}\n".
                $this->ApplicationObj->Unit[ "City" ]."-".
                $this->ApplicationObj->UnitsObject->MakeShowField
                (
                   "State",
                   $this->ApplicationObj->Unit
                ).
            "\\end{flushright}\n".
            "\\vspace{0.75cm}\n".
            "\\begin{center}\n".
                "\\underline{\\hspace{6cm}}\n".
                "\\begin{tiny}\n".
                "Assinatura do(a) Diretor(a)\n\n".
                "\\end{tiny}\n".
            "\\end{center}\n".
            "";

        $latex.=
            $this->LatexBox(10.0,$box1,FALSE,TRUE,'t',"flushleft").
            $this->LatexBox(7.0,$box2,FALSE,TRUE,'t',"flushleft").
            "\\end{normalsize}\n".
            "\\clearpage\n\n".
            "";

        return $latex;
        
    }

    //*
    //* function QuestionariesTable, Parameter list: $edit=0,$tedit=0,$action="StudentMarks"
    //*
    //* Creates one student table, questionaries form.
    //*

    function HandleQuestionaries($edit=0,$tedit=0,$action="StudentMarks")
    {
        if ($this->LatexMode)
        {
            //$this->InitLatexData();
            return $this->QuestionariesLatexTable();
        }

        $html=$this->ApplicationObj->ClassQuestionsObject->HtmlLegendTable();
        if ($edit==1 || $tedit==1)
        {
            $html.=
                $this->StartForm("?ModuleName=Classes&Action=".$action).
                $this->Buttons();
        }

        $html.=
            $this->HtmlTable
            (
               "",
               $this->ApplicationObj->ClassQuestionsObject->QuestionariesTable
               (
                  $this->ApplicationObj->Class,
                  $this->ApplicationObj->Student,
                  $edit,$tedit
               )
            ).
            $this->HtmlTable
            (
               "",
               $this->ApplicationObj->ClassObservationsObject->ObservationsTable
               (
                  $this->ApplicationObj->Class,
                  $this->ApplicationObj->Student,
                  $edit,$tedit
               )
            ).
            "";

        if ($edit==1 || $tedit==1)
        {
            $html.=
                $this->MakeHidden("StudentID",$this->ApplicationObj->Student[ "ID" ]).
                $this->MakeHidden("Update",1).
                $this->Buttons().
                $this->EndForm();
        }
  
        return $html;
     }

}

?>