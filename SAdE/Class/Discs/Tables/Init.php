<?php

class ClassDiscsTablesInit extends ClassDiscsQuestionaries
{
    //*
    //* function ReadDiscTable, Parameter list: $discid=0
    //*
    //* Reads and initilizes stuff needed for generating the disc tables.
    //*

    function ReadDiscTable($id=0)
    {
        $this->ApplicationObj->ClassDiscsObject->ReadDisc($id);
        $this->ApplicationObj->PostInitSubModule($this->ApplicationObj->ClassStudentsObject);
        $this->ApplicationObj->ClassDiscsObject->InitActions();

        $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs($this->ApplicationObj->Class);

        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($this->ApplicationObj->Class[ "ID" ]);

        if (count($this->ApplicationObj->Students)==0)
        {
            print "Nenhum Aluno(a) na Turmas";
            exit();
        }

        $this->ApplicationObj->Student=$this->ApplicationObj->Students[0];
    }


    //*
    //* function ReadStudentTable, Parameter list: $discid=0
    //*
    //* Reads and initilizes stuff needed for generating the student tables.
    //*

    function ReadStudentTable($id=0)
    {
        $this->ApplicationObj->StudentsObject->ReadStudent($id);
        $this->ApplicationObj->PostInitSubModule($this->ApplicationObj->ClassDiscsObject);
        $this->ApplicationObj->ClassStudentsObject->InitActions();

        $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs($this->ApplicationObj->Class);

        $this->ApplicationObj->Disc=$this->ApplicationObj->Discs[0];
    }

    //*
    //* function ReadTable, Parameter list: $discid=0
    //*
    //* Reads and initilizes stuff needed for generating the tables.
    //* Branches for per Disc or per Student.
    //*

    function ReadTable($id=0)
    {
        if ($this->PerDisc)
        {
            $this->ReadDiscTable($id);
        }
        else
        {
            $this->ReadStudentTable($id);
        }
    }

    //*
    //* function InitDisplayTable, Parameter list: $group,$type,$edit=0,$tedit=0
    //*
    //* Set variables necessary for displaying the tables,
    //* detecting if we are displaing marks or absences or bot,
    //* and if we are displaying per student or per disc.
    //*

    function InitDisplayTable($edit=0,$tedit=0)
    {
        foreach (
                   array
                   (
                      "ShowStatus","ShowFinal",
                      "ShowAbsences","ShowAbsencesTotals","ShowAbsenceFinal","ShowAbsencesPercent",
                      "ShowNLessons","ShowNLessonsTotals","ShowNLessonsPercent",
                      "ShowMarkWeights","ShowMarkWeightsTotals",
                      "ShowMarks","ShowMarksTotals","ShowMarkSums","ShowMediaFinal",
                      "ShowRecoveries",
                   )
                   as $key)
        {
            $this->$key=FALSE;
        }

        if ($this->TableType=="Marks")
        {
            $this->ShowMarks=TRUE;
            $this->ShowMediaFinal=TRUE;

            $this->ShowMarkWeights=TRUE;
            $this->ShowMarkSums=TRUE;
            $this->ShowMarksTotals=TRUE;

            $this->ShowRecoveries=TRUE;

            $this->ShowAbsencesFinal=TRUE;
        }
        elseif ($this->TableType=="Absences")
        {
            $this->ShowAbsences=TRUE;
            $this->ShowAbsencesFinal=TRUE;

            $this->ShowMediaFinal=TRUE;


            $this->ShowNLessons=TRUE;
            $this->ShowNLessonsTotals=TRUE;
            $this->ShowNLessonsPercent=TRUE;
        }
        elseif ($this->TableType=="Totals")
        {
            $this->ShowMarks=TRUE;
            $this->ShowMediaFinal=TRUE;
            $this->ShowMarksTotals=TRUE;

            $this->ShowRecoveries=TRUE;

            $this->ShowAbsences=TRUE;
            $this->ShowAbsencesTotals=TRUE;
            $this->ShowAbsenceFinal=TRUE;
        }
        elseif ($this->TableType=="Print")
        {
            $this->ShowMarks=TRUE;
            $this->ShowMediaFinal=TRUE;
            $this->ShowMarksTotals=TRUE;

            $this->ShowRecoveries=TRUE;

            $this->ShowAbsences=TRUE;
            $this->ShowAbsencesTotals=TRUE;
            $this->ShowAbsenceFinal=TRUE;
        }

        if ($this->ApplicationObj->Class[ "AbsencesType" ]==$this->ApplicationObj->OnlyTotals)
        {
            //Only absence totals - disables Absences for disc - and for Student actions.
            if (!$this->PerDisc)
            {
                //$this->ShowAbsences=FALSE;
            }

            foreach (array("StudentAbsences","StudentMarks") as $action)
            {
                $this->ApplicationObj->ClassStudentsObject->Actions[ $action ][ $this->ApplicationObj->Profile ]=0;
                $this->ApplicationObj->ClassStudentsObject->Actions[ $action ][ $this->ApplicationObj->LoginType ]=0;
            }

        }
    }
}

?>