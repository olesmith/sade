<?php

class StudentsRemanageForms extends StudentsRemanageTables
{
    //*
    //* function TransferStudentForm, Parameter list: $classstudent
    //*
    //* Does actual student transfer:
    //*
    //* Updates:
    //*   In origin class student: "End","ToSchool","ToClass"
    //*
    //* Creates destination class student, with: "Start","FromSchool","FromClass",
    //* and: Tarsnfer values.
    //*

    function TransferStudentForm($classstudent)
    {
        if (
              $this->GetPOST("Transfer")!=1
              || 
              empty($this->DestinationSchool)
              || 
              empty($this->DestinationClass)
           )
        {
            return "";
        }

        $html=
            $this->H(3,"Confirmar Transferência").
            $this->Html_Table
            (
               "",
               array
               (
                array
                (
                  $this->TransferStudentOriginTable($classstudent),
                  $this->TransferStudentDestinationTable($classstudent),
                 )
               ),
               array(),
               array(),
               array(),
               FALSE,
               FALSE
            ).
            "";


        return $html;
    }

    

    //*
    //* function RemanageDecisionForm, Parameter list: $classstudent
    //*
    //* Creates remanage decision table form;
    //* 
    //*

    function RemanageDecisionForm($classstudent)
    {
        $schoolid=$this->GetPOST("ToSchool");
        if (empty($schoolid)) { $schoolid=$this->GetGET("School"); }

        $classid=$this->GetPOST("ToClass");

        $this->DestinationSchool=array();
        if (!empty($schoolid))
        {
            $this->DestinationSchool=$this->ApplicationObj->SchoolsObject->SelectUniqueHash
            (
               "",
               array("ID" => $schoolid)
            );
        }

        $this->DestinationClass=array();
        if (!empty($classid))
        {
            $this->DestinationClass=$this->SelectUniqueHash
            (
               $this->DestinationSchool[ "ID" ]."_Classes",
               array("ID" => $classid)
            );
        }

        $table=array
        (
           array
           (
              $this->B("Escola de Destino:"),
              $this->DestinationSchoolSelect($classstudent)
           ),
           array
           (
              $this->B("Turma de Destino:"),
              $this->DestinationClassSelect($classstudent)
           ),
           array($this->Button("submit","Proceder")),
        );

        return $this->FrameIt
        (
            $this->StartForm().
            $this->Html_Table
            (
               "",
               $table,
               array(),
               array(),
               array(),
               FALSE,
               FALSE
            ).
            $this->MakeHidden("Procede",1).
            $this->EndForm().
            ""
        ).
        $this->TransferStudentForm($classstudent);
     }
}

?>