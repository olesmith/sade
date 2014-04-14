<?php

include_once("Class/Status/Row.php");

class ClassStatusTables extends ClassStatusRow
{

    //*
    //* Variables of ClassStatus class:
    //*

    var $IncludeResult=0;
    var $IncludePerDisc=0;
    var $IncludePerStudent=0;

    //*
    //* function ReadResultSettings, Parameter list:
    //*
    //* Reads piint settings from CGI.
    //*

    function ReadResultSettings()
    {
        if (isset($_POST[ "Result" ]))
        {
            $this->IncludeResult=$this->GetPOST("Result");
        }

        if (isset($_POST[ "PerDisc" ]))
        {
            $this->IncludePerDisc=$this->GetPOST("PerDisc");
        }

        if (isset($_POST[ "PerStudent" ]))
        {
            $this->IncludePerStudent=$this->GetPOST("PerStudent");
        }
     }


    //*
    //* function ResultGenForm, Parameter list:
    //*
    //* Generates Class status detailed tables.
    //*

    function ResultGenForm()
    {
        $args=$this->Query2Hash();
        $args=$this->Hidden2Hash($args);

        $args[ "ModuleName" ]="Classes";

        $table=array
        (
           array
           (
              $this->H(5,"Incluir em Imprimivel")
           ),
           array
           (
              $this->B("Ata, Resumo"),
              $this->MakeCheckBox("Result",1,TRUE)
           ),
           array
           (
              $this->B("Resultado Completo, por Disciplina"),
              $this->MakeCheckBox("PerDisc",1,FALSE)
           ),
           array
           (
              $this->B("Resultado Completo, por Aluno"),
              $this->MakeCheckBox("PerStudent",1,FALSE)
           ),
           array
           (
              $this->Button
              (
                 "submit",
                 "Ver. Imprimivel",
                 array
                 (
                    "NAME" => "Latex",
                    "VALUE"=> 1
                 )
              )
           ),
        );


        return
            $this->Center
            (
                $this->StartForm("?".$this->Hash2Query($args)).
                $this->HtmlTable("",$table).
                $this->EndForm()
            ).
            "";
    }

    //*
    //* function ResultAllDiscs, Parameter list: $class,$infotable
    //*
    //* Generates complete result for all discs.
    //*

    function ResultAllDiscs($class,$infotable)
    {
        if ($this->LatexMode)
        {
            $this->ApplicationObj->Sigma="\$\\Sigma\$";
            $this->ApplicationObj->Mu="\$\\mu\$";
            $this->ApplicationObj->Percent="\\%";
        }

        $this->ApplicationObj->ClassDiscsObject->PerDisc=TRUE;

        $output="";
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $this->ApplicationObj->Disc=$disc;
            $this->ApplicationObj->ClassDiscsObject->ReadTable($disc[ "ID" ]);

            if ($this->LatexMode)
            {
                $output.=
                    "\n\n\\clearpage\n\n".
                    "\\begin{center}\n".
                    $this->H(3,"Resultado, Disciplina: ".$disc[ "Name" ]).
                    $infotable.
                    "\n\n".
                    //$this->LatexTable
                    //(
                    //   "",
                    //join("", $this->ApplicationObj->ClassDiscsObject->MakeInfoTable(FALSE) ).//no form
                    //).
                    $this->ApplicationObj->ClassDiscsObject->StudentsTable().
                    "\\end{center}\n".
                        "";
             }
            else
            {
                $output.=
                    $this->H(3,"Resultado, Disciplina: ".$disc[ "Name" ]).
                    $this->HtmlTable
                    (
                       "",
                       $this->ApplicationObj->ClassDiscsObject->MakeInfoTable(FALSE)//no form
                    ).
                    $this->ApplicationObj->ClassDiscsObject->StudentsTable();
            }
        }

        return $output;
    }

    //*
    //* function ResultAllStudents, Parameter list: $class,$infotable
    //*
    //* Generates complete result for all discs.
    //*

    function ResultAllStudents($class,$infotable)
    {
        if ($this->LatexMode)
        {
            $this->ApplicationObj->Sigma="\$\\Sigma\$";
            $this->ApplicationObj->Mu="\$\\mu\$";
            $this->ApplicationObj->Percent="\\%";
        }

        $this->ApplicationObj->ClassDiscsObject->PerDisc=FALSE;

        $output="";
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            $this->ApplicationObj->Student=$student;
            $this->ApplicationObj->ClassDiscsObject->ReadTable($student[ "ID" ]);


            if ($this->LatexMode)
            {
                $output.=
                    "\n\n\\clearpage\n\n".
                    "\\begin{center}\n".
                    $this->H(3,"Resultado, Aluno: ".$student[ "StudentHash" ][ "Name" ]).
                    $infotable.
                    "\n\n".
                    //$this->LatexTable
                    //(
                    //   "",
                    //join("", $this->ApplicationObj->ClassDiscsObject->MakeInfoTable(FALSE) ).//no form
                    //).
                    $this->ApplicationObj->ClassDiscsObject->DiscsTable().
                    "\\end{center}\n".
                        "";
             }
            else
            {
                $output.=
                    $this->H(3,"Resultado, Aluno: ".$student[ "StudentHash" ][ "Name" ]).
                    $this->HtmlTable
                    (
                       "",
                       $this->ApplicationObj->ClassDiscsObject->MakeInfoTable(FALSE)//no form
                    ).
                    $this->ApplicationObj->ClassDiscsObject->DiscsTable();
            }
        }

        return $output;
    }


    //*
    //* function ResultsTable, Parameter list: $class,$discs
    //*
    //* Generates Class status table.
    //*

    function ResultsTable($class,$discs)
    {
        $table=array
        (
           $this->ClassStatusTableDiscTitles($class,$discs),
           $this->ClassStatusTableDiscDataTitles($class,$discs),
        );

        $no=1;
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            array_push
            (
               $table,
               $this->ClassStatusStudentRow($no,$class,$student,$discs)
            );

            $no++;
        }

        return $table;
    }

    //*
    //* function ClassResults, Parameter list: $class=array()
    //*
    //* Genrates Class status table.
    //*

    function ClassResults($class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $latex=$this->GetGETOrPOST("Latex");
        if ($latex==1)
        {
            $this->LatexMode=TRUE;
            $this->ApplicationObj->ClassMarksObject->LatexMode=TRUE;
            $this->ApplicationObj->ClassAbsencesObject->LatexMode=TRUE;
            $this->ApplicationObj->ClassesObject->LatexMode=TRUE;
            $this->ApplicationObj->ClassDiscsObject->LatexMode=TRUE;
            $this->ApplicationObj->ClassStudentsObject->LatexMode=TRUE;
            $this->ApplicationObj->ClassesObject->LatexMode=TRUE;
        }


        $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs($class);
        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($class[ "ID" ]);

        $discids=array();
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $discs[ $disc[ "ID" ] ]=1;
        }

        $title=$this->H(3,"Atas da Turma");

        $infotable=$this->ApplicationObj->ClassesObject->ClassHtmlInfoTable($this->ApplicationObj->Class);
        array_pop($infotable);

        if ($this->LatexMode)
        {
            $infotable=$this->LatexTable("",$infotable);
        }
        else
        {
            $infotable=$this->HtmlTable("",$infotable);
        }

        $this->ReadResultSettings();

        $table=array();
        if ($this->IncludeResult || !$this->LatexMode)
        {
            $table=$this->ResultsTable($class,$discs);
        }
        

        if ($this->LatexMode)
        {
            $latex=
                $this->ApplicationObj->ClassesObject->LatexHeadLand();

            if ($this->IncludeResult)
            {
                $latex.=
                    "\\begin{center}\n".
                    $title.
                    "\n\n".
                    $infotable.
                    "\n\n".
                    "\\scalebox{0.75}{".
                    $this->LatexTable("",$table).
                    "}".
                    "\\end{center}\n\n".
                    "\\clearpage\n\n".
                    "\n";
            }

            if ($this->IncludePerDisc)
            {
                $latex.=
                    $this->ResultAllDiscs($class,$infotable).
                    "\n\n\\clearpage\n\n".
                    "";
            }

            if ($this->IncludePerStudent)
            {
                $latex.=
                    $this->ResultAllStudents($class,$infotable).
                    "\n\n\\clearpage\n\n".
                    "";
            }

            $latex.=
                $this->ApplicationObj->ClassesObject->LatexTail().
                "";

             $texfile=$this->ApplicationObj->ClassesObject->TexFileName($class,"Atas");
             //print preg_replace('/\n/',"<BR>",$latex); exit();
            //print $texfile; exit();

            $this->RunLatexPrint($texfile,$latex);
            exit();
        }       

        print $this->HtmlTable
        (
           "",
           array
           (
              array($title),
              array($infotable),
              array
              (
                 $this->ResultGenForm(5)
              ),
              array($this->HtmlTable("",$table))
           )
        );
    }
}

?>