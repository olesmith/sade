<?php

include_once("Class/Questions/Row.php");


class ClassQuestionsLegend extends ClassQuestionsRow
{
    //*
    //* function HtmlLegendTable, Parameter list: 
    //*
    //* Returns screen Legend (for values).
    //*

    function HtmlLegendTable()
    {
        $row=array();
        foreach (array_keys($this->ApplicationObj->ClassQuestionsObject->ItemData[ "Value" ][ "Values" ]) as $id)
        {
            array_push
            (
               $row,
               $this->B
               (
                  $this->ApplicationObj->ClassQuestionsObject->ItemData[ "Value" ][ "Values_Latex" ][ $id ].
                  ": "
               ),
               $this->ApplicationObj->ClassQuestionsObject->ItemData[ "Value" ][ "Values" ][ $id ].
               "."
            );
        }

        return $this->Html_Table
        (
            "",
            array
            (
               array("Legenda:"),
               $row,
            ),
            array("ALIGN" => 'center',"FRAME" => 'box'),
            array(),
            array(),
            FALSE,
            FALSE
        ).
        $this->BR();
    }

    //*
    //* function LatexLegendTable, Parameter list: 
    //*
    //* Returns latex Legend (for values).
    //*

    function LatexLegendTable()
    {
        $n=count($this->ApplicationObj->ClassQuestionsObject->ItemData[ "Value" ][ "Values" ])+1;
        $dx=0.2;
        $width=7.85+$n*$dx;

        
        $latex=
            "\\begin{small}\n".
            "\\fbox{\\begin{minipage}[t]{".$width."cm}\n".
            "\\begin{center}\n".
            "  \\textbf{Legenda:}\\hspace{".$dx."cm}\n";

        foreach (array_keys($this->ApplicationObj->ClassQuestionsObject->ItemData[ "Value" ][ "Values" ]) as $id)
        {
            $latex.=
                "\\textbf{".
                $this->ApplicationObj->ClassQuestionsObject->ItemData[ "Value" ][ "Values_Latex" ][ $id ].
                ":} ".
                $this->ApplicationObj->ClassQuestionsObject->ItemData[ "Value" ][ "Values" ][ $id ].
                ".\\hspace{".$dx."cm}".
                "";
        }

        $latex.=
            "\\end{center}\n".
            "\\end{minipage}}\n".
            "\\end{small}\n".
            "\\\\\n\\vspace{0.25cm}";

        return $latex;
    }

}

?>