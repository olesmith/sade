<?php


class ClassDiscsDaylyStudentsHandle extends ClassDiscsDaylyStudentsUpdate
{
    //*
    //* function HandleDaylyStudents, Parameter list: 
    //*
    //* Handles DaylyStudents table. Incl. update.
    //*

    function HandleDaylyStudents()
    {
        $edit=0;
        if (preg_match('/^(Admin|Secretary|Clerk)$/',$this->ApplicationObj->Profile))
        {
            $edit=1;
        }
        elseif (preg_match('/^(Teacher)$/',$this->ApplicationObj->Profile))
        {
            if (
                  $this->ApplicationObj->LoginData[ "ID" ]==$this->ApplicationObj->Class[ "Teacher" ]
                  ||
                  $this->ApplicationObj->LoginData[ "ID" ]==$this->ApplicationObj->Class[ "Teacher1" ]
                  ||
                  $this->ApplicationObj->LoginData[ "ID" ]==$this->ApplicationObj->Class[ "Teacher2" ]
               )
            {
                $edit=1;
            }
        }

        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($this->ApplicationObj->Class[ "ID" ]);


        if ($edit==1 && $this->GetPOST("Save"))
        {
            $this->UpdateDaylyStudents();
        }
        

        print
            $this->H(1,"Alunos da Turma").
            $this->HtmlForm
            (
               $this->Html_Table
               (
                  "",
                  $this->DaylyStudentsTable($edit,$edit),
                  array("ALIGN" => 'center'),
                  array(),
                  array
                  (
                    "STYLE" => 'border-style: solid;border-width: 1px;',
                  ),
                  FALSE,
                  FALSE
               ),
               $edit,
               array
               (
                  "CGI" => "Save",
                  "Method" => "",
                  "Args" => array(),
               )
            ).
            "";
    }
}

?>