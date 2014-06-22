<?php


class ClassDiscAssessmentsHandle extends ClassDiscAssessmentsRead
{
    //*
    //* function HandleDaylyQuestionaries, Parameter list: $disc=array(),$class=array()
    //*
    //* Handles Daylies qualitatvie assessment, per student!
    //*

    function HandleDaylyQuestionaries($edit=1,$disc=array(),$class=array())
    {
        if (empty($class)) {$class=$this->ApplicationObj->Class; }

        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($class[ "ID" ]);

        if (empty($this->ApplicationObj->Students))
        {
            print $this->H(2,"Nenhum aluno encontrado na turma...");
            return;
        }

        $studentid=intval($this->GetGETOrPOST("StudentID"));

        $this->ApplicationObj->Student=array();
        if (empty($studentid))
        {
            $this->ApplicationObj->Student=$this->ApplicationObj->Students[0];
        }
        elseif ($studentid>0)
        {
            foreach (array_keys($this->ApplicationObj->Students) as $sid)
            {
                if ($studentid==$this->ApplicationObj->Students[ $sid  ][ "ID" ])
                {
                    $this->ApplicationObj->Student=$this->ApplicationObj->Students[ $sid ];
                    break;
                }
            }

            if (empty($this->ApplicationObj->Student))
            {
                print $this->H(2,"Aluno não Encontrado...");
                return;
            }
        }

        if ($edit==1 && $this->GetPOST("Update")==1)
        {
            $this->ApplicationObj->ClassDiscsObject->UpdateQuestions($class,$this->ApplicationObj->Student);
            $this->ApplicationObj->ClassObservationsObject->UpdateObservations($class,$this->ApplicationObj->Student);
        }

        $this->ApplicationObj->ClassesObject->Actions[ "StudentPrint" ][ $this->ApplicationObj->Profile ]=1;
        $this->ApplicationObj->ClassesObject->Actions[ "StudentsPrint" ][ $this->ApplicationObj->Profile ]=1;


        $this->ApplicationObj->ClassDiscAssessmentsObject->AssessmentActions=array("StudentPrint","StudentsPrint");

        print
            $this->ApplicationObj->StudentsObject->StudentSelectForm("DaylyAssessments").
            $this->H(3,"Avaliações Qualitativas").
            $this->ApplicationObj->ClassDiscAssessmentsObject->MakeAssessmentsMenu($disc,TRUE).
            $this->ApplicationObj->StudentsObject->InfoTable
            (
               $this->ApplicationObj->Student
            ).
            $this->ApplicationObj->ClassDiscsObject->HandleQuestionaries($edit,0,"DaylyAssessments");

   }

    //*
    //* function HandleDaylyAssessments, Parameter list: 
    //*
    //* Handles Dayly Assessments pages.
    //*

    function HandleDaylyAssessments()
    {
        $edit=$this->ApplicationObj->ClassDiscsObject->CheckAccessEdit2Dayly();

        if ($this->ApplicationObj->Class[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
        {
            $this->HandleDaylyQuestionaries($edit);

            return;
        }

        $this->ReadDaylyAssessments();

        if ($edit==1)
        {
            if ($this->GetPOST("Save")==1)
            {
                $this->UpdateDaylyAssessments();
            }

            print
                $this->H(2,"Lançar Avaliações").
                $this->StartForm().
                $this->Buttons().
                $this->Html_Table
                (
                   "",
                   $this->DaylyNAssessmentsTable(),
                   array("ALIGN" => 'center',"BORDER" => '1'),
                   array(),
                   array(),
                   FALSE,
                   FALSE
                ).
                $this->Buttons();
        }

        print
            $this->H(2,"Avaliações Lançadas").
            $this->Html_Table
            (
               "",
               $this->DaylyAssessmentsTable($edit),
               array("ALIGN" => 'center',"BORDER" => '1'),
               array(),
               array(),
               FALSE,
               FALSE
            );

        if ($edit==1)
        {
            print
                $this->Buttons().
                $this->MakeHidden("Save",1).
                $this->EndForm().
                "";
        }
    }


}

?>