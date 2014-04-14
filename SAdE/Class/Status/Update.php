<?php

include_once("Class/Status/Calc.php");


class ClassStatusUpdate extends ClassStatusCalc
{
    //*
    //* function NewStatusHash, Parameter list: $edit,$class,$disc,$student
    //*
    //* Updates and creates Status input field.
    //*

    function NewStatusHash($class,$disc,$student)
    {
        return array
        (
           "Class" => $classid[ "ID" ],
           "ClassDisc" => $disc[ "ID" ],
           "Student" => $student[ "StudentHash" ][ "ID" ],
           "Status" => $this->ReadStudentDiscStatus($class,$disc,$student),
        );
    }

    //*
    //* function UpdateStatusField, Parameter list: $edit,$class,$disc,$student
    //*
    //* Updates and creates Status input field.
    //*

    function UpdateStatusField($class,$disc,$student)
    {
        $oldteacher="";
        if (!empty($mark[ "Teacher" ])) { $oldteacher=$mark[ "Teacher" ]; }

        $oldvalue=$this->ReadStudentDiscStatus($class,$disc,$student);
        $newvalue=$this->GetPOST
        (
           $this->StatusFieldCGIVar($class,$disc,$student)
        );

        if ($newvalue=="") { $newvalue=NULL; }

        if ($newvalue!=$oldvalue)
        {
            $newvalue=preg_replace('/[^\d,\.]/',"",$newvalue);

            $where=array
            (
               "Class" => $class[ "ID" ],
               "ClassDisc" => $disc[ "ID" ],
               "Student" => $student[ "StudentHash" ][ "ID" ],
            );

            $rstatus=$where;
            $rstatus[ "Status" ]=$newvalue;
            //$rstatus[ "Teacher" ]=$teacherid;

            $this->AddOrUpdate("",$where,$rstatus);
        }
    }


    //*
    //* function UpdateDiscStudentsStatus, Parameter list: $class,$disc
    //*
    //* Updates all students discipline status.
    //*

    function UpdateDiscStudentsStatus($class,$disc)
    {
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            $studentid=$student[ "StudentHash" ][ "ID" ];
            $this->UpdateStatusField
            (
               $class,
               $disc,
               $student
            );
        }
     }

    //*
    //* function UpdateStudentDiscsStatus, Parameter list: $class,$student
    //*
    //* Updates all disiplines student marks.
    //*

    function UpdateStudentDiscsStatus($class,$student)
    {
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $this->UpdateStatusField
            (
               $class,
               $disc,
               $student
            );
        }
     }

    //*
    //* function UpdateStudentDiscMarkTotals, Parameter list: $class,$student,$disc,$markshash
    //*
    //* Writes student marks totals to student status.
    //*

    function UpdateStudentDiscMarkTotals($class,$student,$disc,$markshash)
    {
        $where=array
        (
           "Class" => $class[ "ID" ],
           "Student" => $student[ "StudentHash" ][ "ID" ],
           "ClassDisc" => $disc[ "ID" ],
        );

        $updatedata=array("NAssessments","Media","MediaFinal","MarkResult");
        foreach ($updatedata as $data)
        {
            $item[ $data ]=$markshash[ $data ];
        }

        $this->UpdateUniqueItem("",$where,$updatedata,$item);
    }


    //*
    //* function UpdateStudentDiscMarkTotals, Parameter list: $class,$student,$disc,$markshash
    //*
    //* Writes student marks totals to student status.
    //*

    function UpdateStudentDiscAbsencesTotals($class,$student,$disc,$absenceshash)
    {
        $where=array
        (
           "Class" => $class[ "ID" ],
           "Student" => $student[ "StudentHash" ][ "ID" ],
           "ClassDisc" => $disc[ "ID" ],
        );

        $updatedata=array("Sum","Percent","AbsencesResult");
        foreach ($updatedata as $data)
        {
            $item[ $data ]=$absenceshash[ $data ];
        }

        $this->UpdateUniqueItem("",$where,$updatedata,$item);
    }

}

?>