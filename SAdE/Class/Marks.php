<?php


include_once("Class/Marks/Import.php");
include_once("Class/Marks/Read.php");
include_once("Class/Marks/Update.php");
include_once("Class/Marks/Calc.php");
include_once("Class/Marks/TitleRows.php");
include_once("Class/Marks/Row.php");
include_once("Class/Marks/Tables.php");


class ClassMarks extends ClassMarksTables
{

    //*
    //* Variables of ClassMarks class:
    //*

    var $NAssessments=0;
    var $Marks=array();


    //*
    //*
    //* Constructor.
    //*

    function ClassMarks($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->Sort=array("Name");
    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
    }


    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->ReadSchool();
        $this->ApplicationObj->ReadClass();
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if (!preg_match('/^Class/',$module))
        {
            return $item;
        }

        return $item;
    }



    //*
    //* function MayDelete, Parameter list: $item
    //*
    //* Decides whether Grade is deletable.
    //*

    function MayDelete($item)
    {
        $res=TRUE;

        return $res;
    }


    //*
    //* function PaintStudentResult, Parameter list: $res
    //*
    //* Creates green AP, if $res is TRUE, red RE otherwise
    //*

    function PaintStudentResult($res)
    {
        $results=array
        (
           0 => "",
           1 => "RE",
           2 => "AP",
        );

        if (isset($results[ $res ]))
        {
            $res=$results[ $res ];
        }

        return $res;
    }
    

    //*
    //* function DiscMarkLatexLegend, Parameter list: $disc
    //*
    //* Create disc marks latex legend.
    //*

    function DiscMarkLatexLegend($disc)
    {
        $weight=0.0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            $weight+=$disc[ "Weights" ][ $n-1 ][ "Weight" ];
        }

        $formula="M=\\frac{";
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            if ($n<$disc[ "NAssessments" ]) { $formula.="="; }

            $formula.=
                sprintf("%.1f",$disc[ "Weights" ][ $n-1 ][ "Weight" ]).
                "*".
                "M_".$n;
        }

        $formula.="}{".$weight."}";

        /* for ($n=1;$n<=$disc[ "NRecoveries" ];$n++) */
        /* { */
            $mweight=1.0;
            $rweight=sprintf("%.1f",$disc[ "Weights" ][ $disc[ "NAssessments" ] ][ "Weight" ]);
            $weight=sprintf("%.1f",$mweight+$rweight);
            $mweight=sprintf("%.1f",$mweight);

            $formula.=
                "\\quad\\quad M_R_i=".
                "\\frac{".
                $rweight.
                "*N_R_i+".
                $mweight.
                "*M}{".
                $weight.
                "}";
                ;
        /* } */
        
        return
            "\\fbox{\n".
            "\\begin{small}\n".
            "\\textbf{Legenda:}~~~".
            "\\(\n".
            $formula.
            "\\)\n\n".
            "\\end{small}\n".
            "}\n\n".
            "";
    }

}

?>