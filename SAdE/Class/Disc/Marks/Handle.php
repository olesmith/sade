<?php


class ClassDiscMarksHandle extends ClassDiscMarksReads
{
    //*
    //* function HandleDaylyMarks, Parameter list: 
    //*
    //* Handles Dayly Marks pages.
    //*

    function HandleDaylyMarks($print=FALSE)
    {
        $edit=$this->ApplicationObj->ClassDiscsObject->CheckAccessEdit2Dayly();
        if ($print)
        {
            $this->ApplicationObj->SetLatexMode();
            $edit=0;
        }

        if (intval($this->ApplicationObj->Class[ "AssessmentType" ])==$this->ApplicationObj->Qualitative)
        {
            $this->ApplicationObj->ClassDiscsObject->HandleQuestionaries($edit,$edit);

            return;
        }

        $this->ApplicationObj->ClassDiscAssessmentsObject->ReadDaylyAssessments();

        $this->Assessments=$this->ApplicationObj->ClassDiscAssessmentsObject->Assessments;

        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($this->ApplicationObj->Class[ "ID" ]);
        if ($this->LatexMode())
        {
            $this->PrintMarksLatex();
            exit();
        }

        print
            $this->H(1,"Cadastro de Notas").
            $this->SemestersMenu($this->ScriptQueryHash()).
            $this->BR();

        $table=$this->DaylyMarksTable($edit);

        if (count($table)==0) { die("Nenhum Aluno na turma..."); }

        if ($edit==1)
        {
            print
                $this->StartForm().
                $this->Buttons();
        }



        print
            $this->Html_Table
            (
               "",
               $table[0],
               array("ALIGN" => 'center'),
               array(),
               array(),
               TRUE,TRUE
            );
        
        if ($edit==1)
        {
            print
                $this->MakeHidden("Save",1).
                $this->Buttons().
                $this->EndForm().
                "";
        }
    }
}

?>