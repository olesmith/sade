<?php


class ClassDiscsPrintForm extends ClassDiscsCGI
{
    //*
    //* function DaylyTitleRows, Parameter list: 
    //*
    //* Creates stub table with leading bolds.
    //*

    function DaylyTitleRows()
    {
        return array
        (
           $this->H(3,"Incluir Componentes"),
           array
           (
              "",
              $this->B("Todos:"),
              $this->B("Conteúdo:"),
              $this->B("Frequências:"),
              $this->B("Notas:")
           ),
        );
    }

    //*
    //* function AddDiscRows, Parameter list: $class,$disc,&$table
    //*
    //* Creates stub table with leading bolds.
    //*

    function AddDiscRows($class,$disc,&$table)
    {
        $row=array($this->B("Incluir"));

        foreach (array("All","Contents","Absences","Marks") as $component)
        {
            array_push
            (
               $row,
               $this->MakeCheckBox
               (
                  $this->ComponentCGIKey($component,$class,$disc),
                  1
               )
            );
        }

        array_push($row,"");
        array_push($table,$row);
   }

    //*
    //* function DaylyOptionTable, Parameter list:
    //*
    //* Creates stub table with leading bolds.
    //*

    function DaylyOptionTable()
    {
        $table=array
        (
           $this->H(3,"Opções"),   
           $this->Button("submit","Gerar PDF"),   
           array
           (
              $this->B("Alunos por Pagina (Horisontal):"),
              $this->MakeInput
              (
                 "NStudentsPP",
                 $this->PostOrDefault
                 (
                    "NStudentsPP",
                    $this->ApplicationObj->ClassDiscAbsencesObject->NStudentsPerPage
                 ),
                 1
              ),
           ),
           array
           (
              $this->B("Aulas por Página (Vertical):"),
              $this->MakeInput
              (
                 "NDatesPP",
                 $this->PostOrDefault
                 (
                    "NDatesPP",
                    $this->ApplicationObj->ClassDiscAbsencesObject->NDatesPerPage
                 ),
                 1
              ),
           ),
           array
           (
              $this->B("Notas por Pagina (Vertical):"),
              $this->MakeInput
              (
                 "NMarksPP",
                 $this->PostOrDefault
                 (
                    "NMarksPP",
                    $this->ApplicationObj->ClassDiscMarksObject->NMarkCells
                 ),
                 1
              )
           )
        );

        return $table;
    }

    //*
    //* function DaylyDiscPrintTable, Parameter list: $class,$disc
    //*
    //* Creates stub table with leading bolds.
    //*

    function DaylyDiscPrintTable($class,$disc)
    {
        $table=$this->DaylyTitleRows();

        $this->AddDiscRows($class,$disc,$table);
        array_push
        (
           $table,
           $this->Button("submit","Gerar PDF")
        );   

        return $table;
    }



    //*
    //* function LatexDaylyDisc, Parameter list: $class,$disc
    //*
    //* Generates latex comps for $class/$disc.
    //*

    function LatexDaylyDisc($class,$disc)
    {
        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($class[ "ID" ]);

        $latex="";
        if (
              $this->ComponentCGIValue("Contents",$class,$disc)==1
              ||
              $this->ComponentCGIValue("All",$class,$disc)==1
           )
        {
            $this->ApplicationObj->ClassDiscContentsObject->LatexMode=TRUE;
            $latex.=$this->ApplicationObj->ClassDiscContentsObject->ContentsLatex($class,$disc);
        }

        if (
              $this->ComponentCGIValue("Absences",$class,$disc)==1
              ||
              $this->ComponentCGIValue("All",$class,$disc)==1
           )
        {
            $this->ApplicationObj->ClassDiscAbsencesObject->LatexMode=TRUE;
            $latex.=$this->ApplicationObj->ClassDiscAbsencesObject->AbsencesLatex($class,$disc);
        }

        if (
              $this->ComponentCGIValue("Marks",$class,$disc)==1
              ||
              $this->ComponentCGIValue("All",$class,$disc)==1
           )
        {
            $this->ApplicationObj->ClassDiscAssessmentsObject->ReadDaylyAssessments();
            $this->ApplicationObj->ClassDiscMarksObject->Assessments=
                $this->ApplicationObj->ClassDiscAssessmentsObject->Assessments;

            $this->ApplicationObj->ClassDiscMarksObject->LatexMode=TRUE;
            $latex.=$this->ApplicationObj->ClassDiscMarksObject->MarksLatex($class,$disc);
        }

        return $latex;
    }

    //*
    //* function PrintDaylyDisc, Parameter list: $class,$disc
    //*
    //* Generates latex comps for $class/$disc.
    //*

    function PrintDaylyDisc($class,$disc)
    {
        //Use Classes object, has latex heads and tail configured.
        $this->ApplicationObj->ClassesObject->InitLatexData();
        $latex=
            $this->ApplicationObj->ClassesObject->LatexHeadLand().
            $this->LatexDaylyDisc($class,$disc).
            $this->ApplicationObj->ClassesObject->LatexTail().
            "";

        //$this->ShowLatexCode($latex);exit();

        $texfilename=
            "Dayly.".
            $this->CurrentYear().".".
            sprintf("%02d",$this->CurrentMonth()).".".
            sprintf("%02d",$this->CurrentDate()).".".
            time().".".
            ".tex";

        $this->RunLatexPrint($texfilename,$latex);
        exit();
    }

    //*
    //* function DaylyPrint, Parameter list: $class=array(),$disc=array()
    //*
    //* Central Daylies Handler.
    //*

    function DaylyPrint($class=array(),$disc=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        if (empty($disc))  { $disc=$this->ApplicationObj->Disc; }

        if ($this->GetPOST("Generate")==1)
        {
            $this->PrintDaylyDisc($class,$disc);
        }

        print 
            $this->H(1,"Diário Eletrônico").
            $this->H(2,"Versão Imprimível").
            $this->ApplicationObj->ClassesObject->StartForm("?Latex=1").
            $this->Center
            (
               $this->Html_Table
               (
                  "",
                  $this->DaylyOptionTable(),
                  array("ALIGN" => 'center'),
                  array(),
                  array(),
                  TRUE,
                  FALSE
               ).
               $this->Html_Table
               (
                  "",
                  $this->DaylyDiscPrintTable($class,$disc),
                  array("ALIGN" => 'center'),
                  array(),
                  array(),
                  TRUE,
                  FALSE
               ).
               $this->MakeHidden("Generate",1)
            ).
            $this->EndForm().
            "";
    }
}

?>