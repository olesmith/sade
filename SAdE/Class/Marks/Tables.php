<?php



class ClassMarksTables extends ClassMarksRow
{
    //*
    //* function StudentDiscsHtmlTable, Parameter list: $edit=1
    //*
    //* Makes Marks HTML table for student $this->ApplicationObj->Student[ "StudentHash" ][ "ID" ].
    //*

    function StudentDiscsHtmlTable($edit=1)
    {
        $this->ApplicationObj->PostInitSubModule($this->ApplicationObj->ClassDiscsObject);
        $this->ApplicationObj->ClassStudentsObject->InitActions();

        $this->ApplicationObj->ReadClassDiscs($this->ApplicationObj->Class[ "ID" ]);

        $table=array
        (
           $this->H(1,"Notas por Aluno"),
           $this->ApplicationObj->ClassStudentsObject->StudentHtmlInfoTable
           (
              $this->ApplicationObj->Student
           ),
           $this->ApplicationObj->StudentSelectForm().
           $this->ApplicationObj->ClassStudentsObject->ActionEntry
           (
              "StudentAbsences",
              $this->ApplicationObj->Student
           ),
        );

        $rtable=$this->DiscStudentTitleRows
        (
            $this->ApplicationObj->ClassDiscsObject,
            $this->ApplicationObj->ClassDiscsObject->DiscsData,
            $this->ApplicationObj->ClassDiscsObject->DiscsActions,
            $this->ApplicationObj->Discs[0]
        );

        array_unshift($rtable,$this->Buttons());

        if ($edit==1)
        {
            $this->UpdateStudentDiscs();
        }


        $no=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            array_push
            (
               $rtable,
               array_merge
               (
                  $this->ApplicationObj->ClassDiscsObject->ClassDiscData
                  (
                     $no,
                     $disc,
                     $this->ApplicationObj->ClassDiscsObject->DiscsData ,
                     $this->ApplicationObj->ClassDiscsObject->DiscsActions
                  ),
                  $this->DiscStudentRow
                  (
                     $edit,
                     $this->ApplicationObj->Class[ "ID" ],
                     $disc,
                     $this->ApplicationObj->Student[ "StudentHash" ][ "ID" ]
                  )
               )
            );

            $no++;
        }

        array_push
        (
           $table,
           $this->StartForm().
           $this->HtmlTable("",$rtable).
           $this->MakeHidden("StudentID",$this->ApplicationObj->Student[ "ID" ]).
           $this->MakeHidden("Update",1).
           $this->Buttons().
           $this->EndForm()          
        );


        print
            $this->HtmlTable("",$table).
            "";
    }


    //*
    //* function DiscStudentsHtmlTable, Parameter list: $edit=1
    //*
    //* Makes Marks HTML table for discid $discid.
    //*

    function DiscStudentsHtmlTable($edit=1)
    {
        $this->ApplicationObj->PostInitSubModule($this->ApplicationObj->ClassStudentsObject);
        $this->ApplicationObj->ClassDiscsObject->InitActions();

        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($this->ApplicationObj->Class);

        if ($edit==1)
        {
            $this->UpdateDiscStudents();
        }

        $titlerows=$this->DiscStudentTitleRows
        (
            $this->ApplicationObj->ClassStudentsObject,
            $this->ApplicationObj->ClassDiscsObject->StudentsData,
            $this->ApplicationObj->ClassDiscsObject->StudentsActions
        );

        $table=array
        (
           $this->H(1,"Notas por Disciplina"),
           /* $this->ApplicationObj->ClassDiscsObject->DiscHtmlInfoTable */
           /* ( */
           /*    $this->ApplicationObj->Disc */
           /* ), */
           $this->ApplicationObj->DiscsSelectForm().
           $this->ApplicationObj->ClassDiscsObject->ActionEntry
           (
              "DiscAbsences",
              $this->ApplicationObj->Student
           ),
        );

        $rtable=array($this->Buttons());
        foreach ($titlerows as $row) { array_push($rtable,$row); }

        $no=1;
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            array_push
            (
               $rtable,
               array_merge
               (
                  $this->ApplicationObj->ClassDiscsObject->ClassStudentData
                  (
                     $no,
                     $student,
                     $this->ApplicationObj->ClassDiscsObject->StudentsData,
                     $this->ApplicationObj->ClassDiscsObject->StudentsActions
                  ),
                  $this->DiscStudentRow
                  (
                     $edit,
                     $this->ApplicationObj->Class,
                     $this->ApplicationObj->Disc,
                     $student
                  )
               )
            );

            if ( ($no%10)==0)
            {
                array_push($rtable,$this->Buttons());
                foreach ($titlerows as $row) { array_push($rtable,$row); }
            }
            $no++;
        }

        array_push
        (
           $table,
           $this->StartForm().
           $this->HtmlTable("",$rtable).
           $this->MakeHidden("DiscID",$this->ApplicationObj->Disc[ "ID" ]).
           $this->MakeHidden("Update",1).
           $this->Buttons().
           $this->EndForm()          
        );

        print
            $this->HtmlTable("",$table).
            "";
    }

}

?>