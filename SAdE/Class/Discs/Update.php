<?php



class ClassDiscsUpdate extends ClassDiscsImport
{
    //*
    //* function UpdateDiscData, Parameter list: $class,&$disc
    //*
    //* Updates data pertaining to $disc - that is Weights and 
    //* NLessons.
    //*

    function UpdateDiscData($class,&$disc)
    {
        if ($this->ShowMarks)
        {
            $this->ApplicationObj->ClassDiscWeightsObject->UpdateWeightFields($class,$disc);
        }

        if ($this->ShowAbsences && intval($disc[ "AbsencesType" ])==$this->ApplicationObj->AbsencesYes)
        {
            $this->ApplicationObj->ClassDiscNLessonsObject->UpdateNLessonsFields($class,$disc);
        }
    }

    //*
    //* function UpdateStudentDisc, Parameter list: $class,$student,$disc
    //*
    //* Update student disc entries.
    //*

    function UpdateStudentDisc($class,$student,$disc)
    {
        if ($this->ShowStatus)
        {
            $this->ApplicationObj->ClassStatusObject->UpdateStatusField
            (
               $class,
               $disc,
               $student
            );
        }

        $status=$this->ApplicationObj->ClassStatusObject->ReadStudentDiscStatus
        (
           $class,
           $disc,
           $student
        );

        if ($status!=1) { return; } //no edits

        if ($this->ShowMarks)
        {
            $this->ApplicationObj->ClassMarksObject->UpdateMarkFields
            (
               $class,
               $disc,
               $student
            );
        }

        $markshash=$this->ApplicationObj->ClassMarksObject->CalcStudentDiscMarks
        (
           $disc,
           $this->ApplicationObj->ClassMarksObject->ReadStudentDiscMarks
           (
              $class,
              $disc,
              $student
           )
        );


        $absenceshash=$this->ApplicationObj->ClassAbsencesObject->CalcStudentDiscAbsences
        (
           $disc,
           $this->ApplicationObj->ClassAbsencesObject->ReadStudentAbsences
           (
              $class,
              $disc,
              $student
           )
        );

        if ($this->ShowAbsences)
        {
            $this->ApplicationObj->ClassAbsencesObject->UpdateAbsencesFields
            (
               $class,
               $disc,
               $student
            );
        }
    }

    //*
    //* function UpdateStudents, Parameter list: $class,$disc
    //*
    //* Updates all students for one disc,&$disc
    //*

    function UpdateStudents($class,&$disc)
    {
        $this->UpdateDiscData($class,$disc);
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            $this->UpdateStudentDisc($class,$student,$disc);
        }

    }

    //*
    //* function UpdateDiscs, Parameter list: $class,$student
    //*
    //* Update all discs for one student, $student
    //*

    function UpdateDiscs($class,$student)
    {
        foreach (array_keys($this->ApplicationObj->Discs) as $id)
        {
            if ($this->ApplicationObj->Discs[ $id ][ "AbsencesType" ]!=$this->ApplicationObj->MarksNo)
            {
                $this->UpdateDiscData($class,$this->ApplicationObj->Discs[ $id ]);
                $this->UpdateStudentDisc($class,$student,$this->ApplicationObj->Discs[ $id ]);
            }
        }
    }

    //*
    //* function UpdateObservations, Parameter list: $class,$student
    //*
    //* Update observations for one student, $student
    //*

    function UpdateObservations($class,$student)
    {
        $this->ApplicationObj->ClassObservationsObject->UpdateObservations($class,$student);
    }

    //*
    //* function UpdateQuestions, Parameter list: $class,$student
    //*
    //* Update all questions for one student, $student
    //*

    function UpdateQuestions($class,$student)
    {
        $this->ApplicationObj->ClassQuestionsObject->UpdateQuestionaries($class,$student);
    }

    //*
    //* function UpdateTable, Parameter list: $class=array()
    //*
    //* Does the table updating.
    //*

    function UpdateTable($class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        if ($this->PerDisc)
        {
            $this->UpdateStudents($class,$this->ApplicationObj->Disc);
        }
        else
        {
            if ($this->ApplicationObj->Class[ "AssessmentType" ]==$this->ApplicationObj->Quantitative)
            {
                $this->UpdateDiscs($class,$this->ApplicationObj->Student);
            }
            elseif ($this->ApplicationObj->Class[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
            {
                $this->ApplicationObj->ClassAbsencesObject->UpdateAbsencesFields
                (
                   $class,
                   $this->ApplicationObj->Disc,
                   $this->ApplicationObj->Student,
                   $class[ "Teacher" ]
                );

                $this->ApplicationObj->ClassDiscNLessonsObject->UpdateNLessonsFields
                (
                   $class,
                   $this->ApplicationObj->Disc
                );

                $this->UpdateQuestions($class,$this->ApplicationObj->Student);
            }
        }

        $this->UpdateObservations($class,$this->ApplicationObj->Student);
    }
}

?>