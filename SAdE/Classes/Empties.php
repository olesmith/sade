<?php

class ClassesEmpties extends ClassesTransfer
{
    //*
    //* function RemoveEmptyClasses, Parameter list: 
    //*
    //* Handles action EmptyClasses.
    //*

    function RemoveEmptyClasses()
    {
        if ($this->GetPOST("Remove")!=1) { return; }

        $classids=$this->MySqlUniqueColValues
        (
           "",
           "ID",
           array
           (
              "School" => $this->ApplicationObj->School[ "ID" ],
              "Period" => $this->ApplicationObj->Period[ "ID" ],
           )
        );

        foreach ($classids as $classid)
        {
            $check=$this->GetPOST("Remove_Class_".$classid);

            if ($check!=1) { continue; }

            $class=$this->ReadItem($classid);

            $nstudents=$this->ApplicationObj->ClassStudentsObject->MySqlNEntries
            (
               $this->SchoolAndPeriod2SqlTable($class,"ClassStudents"),
               array
               (
                  "School" => $this->ApplicationObj->School[ "ID" ],
                  "Class" => $classid,
               )
            );

            if ($nstudents==0)
            {
                $this->ApplicationObj->ClassDiscsObject->MySqlDeleteItems
                (
                   $this->SchoolAndPeriod2SqlTable($class,"ClassDiscs"),
                   $this->Hash2SqlWhere
                   (
                      array
                      (
                         "School" => $this->ApplicationObj->School[ "ID" ],
                         "Class" => $classid,
                      )
                   )
                );

                $this->MySqlDeleteItem("",$classid,"ID");
            }
        }
    }


    //*
    //* function HandleEmptyClasses, Parameter list: 
    //*
    //* Handles action EmptyClasses.
    //*

    function HandleEmptyClasses()
    {
        if ($this->GetPOST("Remove")==1) { $this->RemoveEmptyClasses(); }
        $classids=$this->MySqlUniqueColValues
        (
           "",
           "ID",
           array
           (
              "School" => $this->ApplicationObj->School[ "ID" ],
              "Period" => $this->ApplicationObj->Period[ "ID" ],
           )
        );

        $table=array
        (
           $this->B
           (
            array("Ano/Semestre","Grade","Periodo","Turma","Turno","No. de Alunos","No. de Disciplinas","Remover")
           )
        );

        foreach ($classids as $classid)
        {
            $class=$this->ReadItem($classid);

            $nstudents=$this->ApplicationObj->ClassStudentsObject->MySqlNEntries
            (
               $this->SchoolAndPeriod2SqlTable($class,"ClassStudents"),
               array
               (
                  "School" => $this->ApplicationObj->School[ "ID" ],
                  "Class" => $classid,
               )
            );

            $ndiscs=$this->ApplicationObj->ClassDiscsObject->MySqlNEntries
            (
               $this->SchoolAndPeriod2SqlTable($class,"ClassDiscs"),
               array
               (
                  "School" => $this->ApplicationObj->School[ "ID" ],
                  "Class" => $classid,
               )
            );

            //var_dump($class);
            $row=array
            (
               $class[ "Period_Name" ],
               $class[ "Grade_Name" ],
               $class[ "GradePeriod_Name" ],
               $class[ "Name" ],
               $this->GetEnumValue("Shift",$class),
               $nstudents,
               $ndiscs,
            );

            if ($nstudents==0)
            {
                array_push
                (
                   $row,
                   $this->HtmlInputCheckBox("Remove_Class_".$classid,1)
                );
            }
            else
            {
                array_push
                (
                   $row,
                   "-"
                );
            }

            array_push($table,$row);

        }
        print
            $this->H(2,"Turmas e Quantia de Alunos").
            $this->StartForm().
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => "center"),
               array(),
               array(),
               TRUE
            ).
            $this->MakeHidden("Remove",1).
            $this->Center($this->Button("submit","REMOVER")).
            $this->EndForm().
            "";
    }
}

?>