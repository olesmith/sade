<?php



class StudentsRemanageTrimester extends StudentsRemanageSelects
{
    //*
    //* function StudentDiscTrimesterAbsences, Parameter list: $disc,$trimester
    //*
    //* Returns number of $student trimester absences.
    //* 
    //*

    function StudentDiscTrimesterAbsences($disc,$trimester)
    {
        return $this->ApplicationObj->ClassAbsencesObject->ReadStudentDiscAbsence
        (
           $this->ApplicationObj->Class,
           $disc,
           $this->ApplicationObj->Student,
           $trimester
        );
    }

    //*
    //* function StudentDiscTrimesterMark, Parameter list: $disc,$trimester
    //*
    //* Returns $student trimester mark.
    //* 
    //*

    function StudentDiscTrimesterMark($disc,$trimester)
    {
        $mark=$this->ApplicationObj->ClassMarksObject->ReadStudentDiscMark
        (
           $this->ApplicationObj->Class,
           $disc,
           $this->ApplicationObj->Student,
           $trimester
        );

        if (empty($mark)) { $mark="-"; }

        return $mark;
    }

    //*
    //* function StudentDiscTrimesterMarkField, Parameter list: $disc,$trimester
    //*
    //* Returns $student trimester mark.
    //* 
    //*

    function StudentDiscTrimesterMarkField($disc,$trimester)
    {
        return $this->MakeInput
        (
           "Mark_".$trimester,
           $this->StudentDiscTrimesterMark($disc,$trimester),
           2
        );

    }


    //*
    //* function TrimesterNCols, Parameter list: 
    //*
    //* Returns number of columns spanned by one trimester.
    //* 
    //*

    function TrimesterNCols()
    {
        return 2;
    }
    //*
    //* function TrimesterColsTitles, Parameter list: &$titles,$trimester
    //*
    //* Generate Title rows cells, pertaining to Trimester.
    //* 
    //*

    function TrimesterColsTitles(&$titles,$trimester)
    {
        array_push
        (
           $titles[1],
           $this->MultiCell
           (
              $this->Latins[ $trimester ],
              $this->TrimesterNCols()
           )
        );

        $titles[2]=array_merge
        (
           $titles[2],
           $this->B(array("F","M"))
        );
    }

    //*
    //* function TrimesterCols, Parameter list: $disc,$trimester
    //*
    //* Generates $disc $trimester cells.
    //* 
    //*

    function TrimesterCols($disc,$trimester)
    {
        return array
        (
           $this->StudentDiscTrimesterAbsences($disc,$trimester),
           $this->StudentDiscTrimesterMark($disc,$trimester),
        );
    }
}

?>