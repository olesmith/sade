<?php

class ClassMarksUpdate extends ClassMarksRead
{
    //*
    //* function MarkFieldCGIVar, Parameter list: $classid,$discid,$studentid,$teacherid,$assessment
    //*
    //* Returns CGI key name of mark field.
    //*

    function MarkFieldCGIVar($class,$disc,$student,$assessment)
    {
        return
            "Mark_".
            $class[ "ID" ]."_".
            $disc[ "ID" ]."_".
            $student[ "StudentHash" ][ "ID" ]."_".
            $assessment;
    }

    //*
    //* function MarkFieldSqlWhere, Parameter list: $class,$disc,$student,$assessment,$secedit=2
    //*
    //* Returns CGI key name of mark field.
    //*

    function MarkFieldSqlWhere($class,$disc,$student,$assessment,$secedit=2)
    {
        return array
        (
           "Class" => $class[ "ID" ],
           "ClassDisc" => $disc[ "ID" ],
           "Student" => $student[ "StudentHash" ][ "ID" ],
           "Assessment" => $assessment,
           "SecEdit" => $secedit,
        );
    }

    //*
    //* function MakeMarkField, Parameter list: $edit,$class,$disc,$student,$assessment
    //*
    //* Updates and creates Mark input field.
    //*

    function UpdateMarkField($class,$disc,$student,$assessment)
    {
        $value=NULL;
        if (!empty($mark[ "Mark" ])) { $value=$mark[ "Mark" ]; }

        $oldteacher="";
        if (!empty($mark[ "Teacher" ])) { $oldteacher=$mark[ "Teacher" ]; }

        $value=$this->ReadStudentDiscMark($class,$disc,$student,$assessment);

        $newvalue=$this->GetPOST
        (
           $this->MarkFieldCGIVar($class,$disc,$student,$assessment)
        );

        if ($newvalue=="") { $newvalue=NULL; }

        if ($newvalue!=$value)
        {
            $value=preg_replace('/[^\d,\.]/',"",$newvalue);
            $value=preg_replace('/,/',".",$value);

            $where=$this->MarkFieldSqlWhere($class,$disc,$student,$assessment,2);

            $mark=$where;
            $mark[ "Mark" ]=$value;
            $mark[ "SecEdit" ]=2;
            //$mark[ "Teacher" ]=$teacherid;

            $this->AddOrUpdate("",$where,$mark);
        }
    }


    //*
    //* function UpdateMarkFields, Parameter list: $class,$disc,$teacherid,$student
    //*
    //* Updates Student Disc mark fields.
    //*

    function UpdateMarkFields($class,$disc,$student)
    {
        $keys=preg_grep
        (
           '/^Mark_'.$class[ "ID" ].'_'.$disc[ "ID" ].'_'.$student[ "StudentHash" ][ "ID" ].'_/',
           array_keys($_POST)
        );

        $assessments=array();
        foreach ($keys as $key)
        {
            array_push($assessments,preg_replace('/.*_/',"",$key));
        }

        foreach ($assessments as $assessment)
        {
            $this->UpdateMarkField
            (
               $class,
               $disc,
               $student,
               $assessment
            );
        }
    }
}

?>