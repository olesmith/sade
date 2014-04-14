<?php

include_once("../MySql2/Table.php");

//This class is common to all SAdE modules.
//Created for overriding LatexHead, LatexHeadLand.

class Common extends Table
{
    //*
    //* function IsSchool, Parameter list: $schoolid
    //*
    //* Checks whether $schoolid is actually a school.
    //*

    function IsSchool($schoolid)
    {
        $school=$this->SelectUniqueHash
        (
           "Places",
           array("ID" => $schoolid),
           TRUE,
           array("ID")
        );

        if (!empty($school)) { return TRUE; }

        return FALSE;
    }

    //*
    //* function DocLatexFancyhrCHead, Parameter list: 
    //*
    //* Fabricates fancyhdr chead entry.
    //*

    function DocLatexFancyhrCHead()
    {
        $sizes=array("Huge","huge","LARGE","Large","large","normalsize","small","footnotesize","scriptsize","tiny");
        $titles="   \\bfseries\n";// begin-end textbf gave problems, since in preamble?

        $n=3;
        foreach ($this->ApplicationObj->TInterfaceLatexTitles as $title)
        {
            if (!empty($title))
            {
                if (!empty($sizes[$n]))
                {
                    $titles.=
                        "      \\begin{".$sizes[$n]."}\n".
                        "      ".$title."\n".
                        "      \\end{".$sizes[$n]."}\\\\\n";
                }
                $n++;
            }
        }

        return "\\chead\n{\n".$titles."}\n";
    }

    //*
    //* function DocLatexFancyhr, Parameter list: $latex,$landscape=FALSE
    //*
    //* Fabricates fancyhdr entries: lhead, chead, etc.
    //* Adds to $latex and include a \pagestyle command.
    //*

    function DocLatexFancyPages($latex,$landscape)
    {
        $latex=preg_replace('/\\\\begin{document}\n?/',"",$latex);
        $latex=preg_replace('/\\\\pagestyle{.+}\n?/',"",$latex);

        $width=$this->ApplicationObj->TInterfaceLatexIcons[1][ "Width" ];
        $height=$this->ApplicationObj->TInterfaceLatexIcons[1][ "Height" ];

        $specs=array();
        if (!empty($height))
        {
            array_push($specs,"height=".$height."cm");
        }
        if (!empty($width))
        {
            array_push($specs,"width=".$width."cm");
        }

        if (empty($specs))
        {
            $width=2.5;
            if ($landscape)
            {
                $width=3;
            }

            array_push($specs,"width=".$width."cm");
        }

        $lhead=
            "\\lhead{\n".
            "   \\includegraphics*".
            "[".join(",",$specs)."]".
            "{".$this->ApplicationObj->TInterfaceLatexIcons[1][ "Icon" ]."}\n".
            "}\n";

        $rhead=
            "\\rhead{\n".
            "   \\includegraphics*".
            "[".join(",",$specs)."]".
            "{".$this->ApplicationObj->TInterfaceLatexIcons[2][ "Icon" ]."}\n".
            "}\n";


        $lfoot=
            "\\lfoot{\\small{\\textbf{\n".
            "    SAdE2: Sistema Administrativo Escolar\\\\\n".
            "    Author: Prof. PhD Ole Peter Smith\n".
            "}}}\n";

        $cfoot=""; //"\\cfoot{}\n";

        $rfoot=
            "\\rfoot{\\small{\\textbf{\n".
            "   \\today\\\\\n".
            "   ".
               $this->ApplicationObj->School[ "City" ]."-".
               $this->States_Short[ $this->ApplicationObj->School[ "State" ]-1 ]."\n".
            "}}}\n";


        return 
            "%%                                 %%\n".
            "%% Automatic Fancyhdr entry header %%\n".
            "%% inserted by:                    %%\n".
            "%%    Common::DocLatexFancyPages   %%\n".
            "%%                                 %%\n".
            $latex.
            "\n\n".
            $lhead.
            $this->DocLatexFancyhrCHead().
            $rhead.
            $lfoot.
            $cfoot.
            $rfoot.
            "\\pagestyle{fancy}\n\n".
            "\\begin{document}\n\n".
            "";
    }

    //*
    //* function LatexHead, Parameter list:
    //*
    //* Prints the Latex Head (leading HEAD section and top/left of page).
    //* Stored in /usr/local/UEG/Skels/Html/Head.Head.html, resp.
    //* Stored in /usr/local/UEG/Skels/Html/Head.Body.html.
    //* 

    function LatexHead()
    {
        $latex=parent::LatexHead();
        return $this->DocLatexFancyPages($latex,FALSE);
    }

    //*
    //* function LatexHeadLand , Parameter list:
    //*
    //* Prints the Latex Head with landscape set(leading HEAD section and top/left of page).
    //* Stored in /usr/local/UEG/Skels/Html/Head.Head.html, resp.
    //* Stored in /usr/local/UEG/Skels/Html/Head.Body.html.
    //* 

    function LatexHeadLand()
    {
        $latex=parent::LatexHeadLand();
        return $this->DocLatexFancyPages($latex,TRUE);
    }

    //*
    //* function GetLatexHead, Parameter list: $type,$latexdocno=0
    //*
    //* Return latex header (until and including \begin{document}. 
    //*

    function GetLatexHead($type,$latexdocno=0)
    {
        $latex=$this->GetLatexSkel
        (
           $this->LatexData[ $type."LatexDocs" ][ "Docs" ][ $latexdocno ][ "Head" ]
        );

        $landscape=FALSE;
        if
            (
               isset($this->LatexData[ $type."LatexDocs" ][ "Docs" ][ $latexdocno ][ "Landscape" ])
               &&
               $this->LatexData[ $type."LatexDocs" ][ "Docs" ][ $latexdocno ][ "Landscape" ]
            )
        {
            $landscape=TRUE;
        }

        return $this->DocLatexFancyPages($latex,$landscape);
    }
}

?>