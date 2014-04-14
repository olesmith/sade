<?php


class StudentsHistoryTable extends StudentsHistoryStudent
{
    //*
    //* function StudentClassShortTable, Parameter list: $edit,$student,$entries
    //*
    //* Generates a table with classes of students.
    //* 
    //*

    function StudentHistoryShortTable($edit,$student,$entries)
    {
        if ($this->GetPOST("Save")==1)
        {
            $this->UpdateHistoryShortTable($edit,$student,$entries);
        }

        $table=array();
        foreach ($entries as $gradeid => $gradeentries)
        {
            $grade=$this->ApplicationObj->GradeObject->GetGrade($gradeid);
            array_push($table,array($this->H(4,$grade[ "Name" ])));

            $name="Turma";
            if ($edit==1) {$name="Selectionar ".$name; }

            array_push($table,$this->B(array("Ano/Semestre","Turma","Nome","Matriculado(a)","Cadastros",$name)));

            $n=1;
            foreach ($gradeentries as $gradeentry)
            {
                $gtable=array();
                $row=$this->MakeGradeEntryRow($edit,$student,$gradeentry);
                array_push($table,$row);
            }
        }

        return $table;
    }

    //*
    //* function StudentClassShortTableForm, Parameter list: $edit,$student,$entries
    //*
    //* Generates a table with classes of students.
    //* 
    //*

    function StudentHistoryShortTableForm($edit,$student,$entries)
    {
        $html="";
        if ($edit==1)
        {
            $html.=
                $this->StartForm();
        }

        $html.=
            $this->Html_Table
            (
               "",
               $this->StudentHistoryShortTable($edit,$student,$entries),
               array("ALIGN" => 'center'),
               array(),
               array(),
               TRUE
             );

        if ($edit==1)
        {
            $html.=
                $this->MakeHidden("Save",1).
                $this->Button("submit","Salvar").
                $this->EndForm();
        }

        return $html;
    }

    //*
    //* function StudentLongClassTable, Parameter list: $edit,$student,$entries
    //*
    //* Generates a table with classes of students.
    //* 
    //*

    function StudentHistoryLongTable($edit,$student,$entries)
    {
        $rtable=array();
        foreach ($entries as $gradeid => $gradeentries)
        {
            $grade=$this->ApplicationObj->GradeObject->GetGrade($gradeid);

            $name="Turma";
            if ($edit==1) {$name="Selectionar ".$name; }

            array_push($rtable,array($this->H(3,$grade[ "Name" ])));
            array_push($rtable,$this->B(array("No.","","Ano/Semestre","Periodo","Turma","")));

            $n=1;
            foreach ($gradeentries as $gradeentry)
            {
                $gtable=array();

                //$gradeentry[ "Class" ], etc are hashes!!
                if (!empty($gradeentry[ "Class" ]))
                {
                    $this->StudentClassHistoryTitles
                    (
                       $rtable,
                       $n,
                       $student,
                       $gradeentry[ "Class" ],
                       $gradeentry[ "Grade" ],
                       $gradeentry[ "GradePeriod" ],
                       $gradeentry [ "Period" ],
                       $gradeentry [ "ClassStudent" ]
                    );

                    $this->StudentClassHistoryTable
                    (
                       $gtable,
                       $student,
                       $gradeentry[ "Class" ],
                       $gradeentry[ "Grade" ],
                       $gradeentry[ "GradePeriod" ],
                       $gradeentry [ "Period" ]
                    );

                    $n++;
                }


                array_push
                (
                   $rtable,
                   array
                   (
                      $this->MultiCell("",5),
                      $this->Html_Table
                      (
                         "",
                         $gtable,
                         array("WIDTH" => '100%'),
                         array(),
                         array(),
                         TRUE
                      )
                   )
                );
            }
        }

        return $rtable;
    }
    //*
    //* function StudentHistoryTable, Parameter list: $edit,$student,$periodsentries
    //*
    //* Generates a table with classes of students.
    //* 
    //*

    function StudentHistoryTable($edit,$student,$gradeentries)
    {
        $entries=$this->ReadStudentHistoryEntries($edit,$student,$gradeentries);        

        return
            $this->Center
            (
               $this->StudentHistoryShortTableForm($edit,$student,$entries)
            ).
            $this->H(3,"Detalhes").
            $this->Html_Table
            (
               "",
               $this->StudentHistoryLongTable($edit,$student,$entries),
               array("ALIGN" => 'center'),
               array(),
               array(),
               TRUE
            );
    }
}

?>