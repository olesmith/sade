<?php



class StudentsMatriculateUpdate extends StudentsMatriculateTable
{
    //*
    //* function AddStudentToClass, Parameter list: $period,$classcgi
    //*
    //* Inserts student into class table.
    //* 
    //*

    function AddStudentToClass($period,$classcgi)
    {
        $class=$this->ApplicationObj->ClassesObject->SelectUniqueHash
        (
           "",
           array("ID" => $classcgi)
        );

        $classstudent=array
        (
           "School" => $this->ItemHash[ "StudentHash" ][ "School" ],
           "Class" => $classcgi,
           "Student" => $this->ItemHash[ "StudentHash" ][ "ID" ],
           "Grade" => $class[ "Grade" ],
           "GradePeriod" => $class[ "GradePeriod" ],
           "UniqueID" => $this->ItemHash[ "StudentHash" ][ "UniqueID" ],
         );

        $classstudent=$this->MySqlInsertItem
        (
           $this->ClassStudentSqlTable($period),
           $classstudent
        );

        print $this->H
        (
           5,
           "Aluno(a) Matriculado(a) no Periodo ".
           $this->ApplicationObj->GetPeriodName($period)
        );

        return $classstudent;
    }

    //*
    //* function MoveStudentToClass, Parameter list: $period,$classstudent,$classcgi
    //*
    //* Moves student into class, updates class student table.
    //* 
    //*

    function MoveStudentToClass($period,$classstudent,$classcgi)
    {
        $this->ApplicationObj->ClassStudentsObject->MySqlSetItemValue
        (
           $this->ClassStudentSqlTable($period),
           "ID",$classstudent[ "ID" ],
           "Class",$classcgi
        );

        print $this->H
        (
           5,
           "Aluno(a) Rematriculado(a) no Periodo ".
           $this->ApplicationObj->GetPeriodName($period)
        );

        return $classstudent;
    }

    //*
    //* function UpdateMatriculaPeriodRow, Parameter list: $period
    //*
    //* Updates student matricula period row.
    //* 
    //*

    function UpdateMatriculaPeriodRow($period)
    {
        $classstudent=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
        (
           $this->ClassStudentSqlTable($period),
           array("Student" => $this->ItemHash[ "StudentHash" ][ "ID" ])
        );

        $class=array("ID" => 0);
        if (!empty($classstudent))
        {
            $class=$this->ApplicationObj->ClassesObject->SelectUniqueHash
            (
               "",
               array("ID" => $classstudent[ "Class" ])
             );
        }

        $hasrecords=$this->StudentHasRecords
        (
           $this->ItemHash,
           $this->ApplicationObj->GetPeriodName($period)
        );

        if ($hasrecords && !empty($classstudent)) { return; }

        $classcgi=$this->GetPOST($this->SelectFieldName($period));
        if (
              preg_match('/^\d+$/',$classcgi)
              &&
              $classcgi>0
              &&
              $class[ "ID" ]!=$classcgi
           )
        {
            if (empty($classstudent))
            {
                $classstudent=$this->AddStudentToClass($period,$classcgi);
            }
            else
            {
                $classstudent=$this->MoveStudentToClass($period,$classstudent,$classcgi);
            }
        }
        else
        {
            print $this->H
            (
                   5,
                   "Aluno(a) Inalterado(a) no Periodo ".
                   $this->ApplicationObj->GetPeriodName($period)
                );
        }
    }

    //*
    //* function UpdateMatriculaTable, Parameter list: 
    //*
    //* Updates student matricula table.
    //* 
    //*

    function UpdateMatriculaTable()
    {
        foreach ($this->ApplicationObj->Periods as $period)
        {
            //Skip if not $student Matriculated in $period
            if ($this->ApplicationObj->PeriodsObject->StudentMatriculatedInPeriod($this->ItemHash,$period))
            {
                $this->UpdateMatriculaPeriodRow($period);
            }
        }

    }

}

?>