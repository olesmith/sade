<?php



class StudentsRemanageTables extends StudentsRemanageStart
{

    //*
    //* function StudentRemanageTableTitles, Parameter list: 
    //*
    //* Generates titles of remanage table.
    //* 
    //*

    function StudentRemanageTableTitles()
    {
        $titles=$this->StartColsTitles();
        $this->TrimestersColsTitles($titles);

        return $titles;
    }

    //*
    //* function StudentRemanageTableRow, Parameter list: $n,$disc
    //*
    //* Generates row of remanage table.
    //* 
    //*

    function StudentRemanageTableRow($n,$disc)
    {
        $row=$this->StartCols($n,$disc);

        $row=array_merge
        (
           $this->StartCols($n,$disc),
           $this->TrimestersCols($disc)
        );
        return $row; 
    }

    //*
    //* function StudentRemanageTable, Parameter list: 
    //*
    //* Creates informative table,. assisiting decision to remanage student.
    //* 
    //*

    function StudentRemanageTable()
    {
        $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs();

        $table=$this->StudentRemanageTableTitles();

        $n=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            array_push($table,$this->StudentRemanageTableRow($n,$disc));

            $n++;
        }

        return
            $this->H(2,"Dados Lançados, ".$this->ApplicationObj->GetPeriodTitle()).
            $this->Html_Table
            (
               "",
               $table,
               array(),
               array(),
               array("STYLE" => 'border-style: solid;border-width: 1px;'),
               FALSE,
               FALSE
            ).
            "";
    }


    //*
    //* function TransferStudentOriginTable, Parameter list: $classstudent
    //*
    //* Returns origin table of student transfer form.
    //*

    function TransferStudentOriginTable($classstudent)
    {
        $table=array
        (
           array($this->MultiCell("Origim",2)),
           array
           (
              $this->B("Escola:"),
              $this->ApplicationObj->School[ "Name" ]
           ),
           array
           (
              $this->B("Turma:"),
              $this->ApplicationObj->Class[ "NameKey" ]
           ),           
        );

        $today=$this->ApplicationObj->DatesObject->GetTodayDatesItem();

        $classstudent[ "ToSchool" ]=$this->GetPOST("ToSchool");
        $classstudent[ "ToClass" ]=$this->GetPOST("ToClass");
        $classstudent[ "End" ]=$this->GetPOST("End");
        if (empty($classstudent[ "End" ]))
        {
            $classstudent[ "End" ]=$this->ApplicationObj->DatesObject->GetTodayDatesItem();
            $classstudent[ "End" ]=$classstudent[ "End" ][ "ID" ];
        }

        $edit=1;
        foreach (array("End","ToSchool","ToClass") as $data)
        {
            array_push
            (
               $table,
               array
               (
                  $this->B
                  (
                     $this->ApplicationObj->ClassStudentsObject->GetDataTitle($data).":"
                  ),
                  $this->ApplicationObj->ClassStudentsObject->MakeField($edit,$classstudent,$data)
               )
            );

            $edit=0;
        }

        array_push
        (
           $table,
           $this->StudentDiscsOriginTrimestersTable()
        );

        return $this->FrameIt
        (
           $this->Html_Table
           (
              "",
              $table,
              array(),
              array(),
              array(),
              FALSE,
              FALSE
           )
        );
    }

    //*
    //* function TransferStudentDestinationTable, Parameter list: $classstudent
    //*
    //* Returns dsetination table of student transfer form.
    //*

    function TransferStudentDestinationTable($classstudent)
    {
        $table=array
        (
           array($this->MultiCell("Destino",2)),
           array
           (
              $this->B("Escola:"),
              $this->DestinationSchool[ "Name" ]
           ),
           array
           (
              $this->B("Turma:"),
              $this->DestinationClass[ "NameKey" ]
           ),                      
       );

        $rclassstudent=$classstudent;

        $rclassstudent[ "FromSchool" ]=$classstudent[ "School" ];
        $rclassstudent[ "FromClass"   ]=$classstudent[ "Class" ];
        $rclassstudent[ "Start" ]=$this->GetPOST("End");
        if (empty($rclassstudent[ "Start" ]))
        {
            $rclassstudent[ "Start" ]=$this->ApplicationObj->DatesObject->GetTodayDatesItem();
            $rclassstudent[ "Start" ]=$rclassstudent[ "Start" ][ "ID" ];
        }

        $edit=0;
        foreach (array("Start","FromSchool","FromClass") as $data)
        {
            array_push
            (
               $table,
               array
               (
                  $this->B
                  (
                     $this->ApplicationObj->ClassStudentsObject->GetDataTitle($data).":"
                  ),
                  $this->ApplicationObj->ClassStudentsObject->MakeField($edit,$rclassstudent,$data)
               )
            );
        }

        array_push
        (
           $table,
           $this->StudentDiscsDestinationTrimestersTable()
        );

        return $this->FrameIt
        (
           $this->Html_Table
           (
              "",
              $table,
              array(),
              array(),
              array(),
              FALSE,
              FALSE
           )
        );
     }
}

?>