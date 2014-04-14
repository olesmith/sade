<?php


class ClassesAccess extends ClassesHandlers
{
    //*
    //* function MayDelete, Parameter list: $item
    //*
    //* Decides whether Class is deletable.
    //*

    function MayDelete($item)
    {
        $res=FALSE;

        $classid=$this->GetGET("Class");
        if (!empty($item[ "ID" ])) { $classid=$item[ "ID" ]; }
        if (empty($this->ApplicationObj->Period)) { return $res; }

        $nstudents=$this->ApplicationObj->ClassStudentsObject->MySqlNEntries
        (
           $this->ApplicationObj->GetSqlTable("ClassStudents",TRUE,TRUE,FALSE),
           array
           (
              "Class" => $classid,
           )
        );

        if ($nstudents==0) { $res=TRUE; }

        return $res;
    }

    //*
    //* function DiscID2Type, Parameter list: 
    //*
    //* Reads AbsencesType and AssessmentType from DB. Disc in CGI POST or GET.
    //*

    function DiscID2Type()
    {
        $discid=$this->GetGETOrPOST("Disc");

        $abs=array();
        if (!empty($discid))
        {
            $abs=$this->ApplicationObj->ClassDiscsObject->MySqlItemValues
            (
               $this->ApplicationObj->GetSqlTable("ClassDiscs",TRUE,TRUE),
               "ID",
               $discid,
               array("AbsencesType","AssessmentType"),
               TRUE
            );
        }

        return $abs;
    }

    //*
    //* function RegisterDiscAbsences, Parameter list: $class
    //*
    //* Checks whether we should have register Absences.
    //*

    function RegisterDiscAbsences($class)
    {
        $abs=$this->DiscID2Type();

        $res=FALSE;
        if (!empty($abs))
        {
            $res=$this->ApplicationObj->ClassDiscsObject->RegisterDiscAbsences($abs);
        }

        return $res;
    }

    //*
    //* function RegisterDiscMarks, Parameter list:
    //*
    //* Checks whether we should have register Marks.
    //*

    function RegisterDiscMarks()
    {
        $abs=$this->DiscID2Type();

        $res=FALSE;
        if (!empty($abs))
        {
            $res=$this->ApplicationObj->ClassDiscsObject->RegisterDiscMarks($abs);
        }

        return $res;
    }

    //*
    //* function RegisterDiscTotals, Parameter list:
    //*
    //* Checks whether we should have register Totals.
    //*

    function RegisterDiscTotals()
    {
        $abs=$this->DiscID2Type();

        $res=FALSE;
        if (!empty($abs))
        {
            $res=$this->ApplicationObj->ClassDiscsObject->RegisterDiscTotals($abs);
        }

        return $res;
    }


    //*
    //* function RegisterStudentAbsences, Parameter list:
    //*
    //* Checks whether we should have register Absences.
    //*

    function RegisterStudentAbsences()
    {
        return $this->ApplicationObj->StudentsObject->RegisterStudentAbsences();
    }

    //*
    //* function RegisterStudentMarks, Parameter list: $class
    //*
    //* Checks whether we should have register Marks.
    //*

    function RegisterStudentMarks($class)
    {
        return $this->ApplicationObj->StudentsObject->RegisterStudentMarks();
    }

    //*
    //* function RegisterStudentTotals, Parameter list: $class
    //*
    //* Checks whether we should have register Totals.
    //*

    function RegisterStudentTotals($class)
    {
        return $this->ApplicationObj->StudentsObject->RegisterStudentTotals();
    }

}

?>