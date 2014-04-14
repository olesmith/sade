<?php

class ClassDiscsTablesInfo extends ClassDiscsTablesDisplay
{
    //*
    //* function GetDisplayTitle, Parameter list: 
    //*
    //* From tabels typpe and class data, figures out the title of the Display table.
    //*

    function GetDisplayTitle()
    {
        $hash=$this->ApplicationObj->Disc;
        if (empty($hash)) { $hash=$this->ApplicationObj->Class; }

        if ($this->GetGET("Action")=="Student")
        {
            return 
                $this->ApplicationObj->Student[ "StudentHash" ][ "Name" ].
                $this->BR().
                $this->ApplicationObj->GetPeriodTitle();
        }

        $per="Aluno";
        $this->HandleTitle=" por Aluno(a)";
        if ($this->PerDisc)
        {
            if ($hash[ "AssessmentType" ]!=$this->ApplicationObj->Qualitative)
            {
                $per="Disciplina";
            }

            if ($this->LatexMode) { return "Relatório de Notas"; }
        }
        elseif ($this->LatexMode)
        {
            return "Ficha de Notas";
        }

        $type="Avaliações";
        if ($this->TableType=="Absences")
        {
            $type="Faltas";
        }
        elseif ($this->TableType=="Totals")
        {
            $type="Avaliações e Faltas";
        }

        return $type." por ".$per;
    }

    //*
    //* function MakeInfoTable, Parameter list: $form=TRUE
    //*
    //* Generates info table. Branches for Student or Disc table.
    //*

    function MakeInfoTable($form=TRUE)
    {
        $table=array();
        if ($this->PerDisc)
        {
            if (!$this->LatexMode)
            {
                array_push
                (
                   $table,
                   $this->DiscsMenu(TRUE,FALSE)
                );
            }

            array_push
            (
               $table,
               $this->InfoTable
               (
                  $this->ApplicationObj->Disc
               )
            );

            if (!$this->LatexMode)
            {
                $table=array_merge
                (
                   $table,
                   $this->MakeDiscMenu()
                );
            }
        }
        else
        {
            if (!$this->LatexMode && $form)
            {
               array_push
                (
                   $table,
                   $this->ApplicationObj->StudentsObject->StudentSelectForm("Student".$this->TableType)
                );
            }

            array_push
            (
               $table,
               $this->ApplicationObj->StudentsObject->InfoTable
               (
                  $this->ApplicationObj->Student
               )
            );

            if (!$this->LatexMode)
            {
                $table=array_merge
                (
                   $table,
                   $this->MakeStudentMenu()
                );
            }

        }

        if ($this->LatexMode) 
        {
            array_push($table,"\n\\vspace{0.25cm}\n\n");
        }

        return $table;
    }
}

?>