<?php



class ClassAbsencesUpdate extends ClassAbsencesImport
{
    //*
    //* function AbsencesFieldCGIVar, Parameter list: $classid,$disc,$student,$n
    //*
    //* Returns CGI key name of mark field.
    //*

    function AbsencesFieldCGIVar($class,$disc,$student,$n)
    {
        $list=array("Absences",$class[ "ID" ]);
        if ($class[ "AbsencesType" ]==$this->ApplicationObj->AbsencesYes)
        {
            array_push($list,$disc[ "ID" ]);
        }

        array_push($list,$student[ "StudentHash" ][ "ID" ],$n);
        return join("_",$list);
    }

    //*
    //* function AbsencesFieldCGIRegex, Parameter list: $class,$disc,$student
    //*
    //* Returns CGI key name of Absence field.
    //*

    function AbsencesFieldCGIRegex($class,$disc,$student)
    {
        $list=array("Absences",$class[ "ID" ]);
        if ($class[ "AbsencesType" ]==$this->ApplicationObj->AbsencesYes)
        {
            array_push($list,$disc[ "ID" ]);
        }

        array_push($list,$student[ "StudentHash" ][ "ID" ]);
        return join("_",$list);
    }

    //*
    //* function AbsencesFieldSqlWhere, Parameter list: $class,$disc,$student,$n
    //*
    //* Returns CGI where clause for Absence field.
    //*

    function AbsencesFieldSqlWhere($class,$disc,$student,$n)
    {
        $where=array
        (
           "Class"      => $class[ "ID" ],
           "ClassDisc"  => $disc[ "ID" ],
           "Student"    => $student[ "StudentHash" ][ "ID" ],
           "Assessment" => $n,
        );

        if ($class[ "AbsencesType" ]==$this->ApplicationObj->AbsencesYes)
        {
            $where[ "ClassDisc" ]=$disc[ "ID" ];
        }

        return $where;
    }

    //*
    //* function MakeAbsencesField, Parameter list: $edit,$classid,$disc,$student,$n
    //*
    //* Updates and creates Absences input field.
    //*

    function UpdateAbsencesField($class,$disc,$student,$n)
    {
        $value=NULL;
        if (!empty($mark[ "Absences" ])) { $value=$mark[ "Absences" ]; }

        $oldteacher="";
        if (!empty($mark[ "Teacher" ])) { $oldteacher=$mark[ "Teacher" ]; }

        $value=$this->ReadStudentDiscAbsence($class,$disc,$student,$n);

        $newvalue=$this->GetPOST
        (
           $this->AbsencesFieldCGIVar($class,$disc,$student,$n)
        );
        if ($newvalue=="") { $newvalue=NULL; }

        if ($newvalue!=$value)
        {
            $value=preg_replace('/[^\d,\.]/',"",$newvalue);
            $value=preg_replace('/,/',".",$value);

            $where=$this->AbsencesFieldSqlWhere($class,$disc,$student,$n);

            $mark=$where;
            $mark[ "Absences" ]=$value;

            $this->AddOrUpdate("",$where,$mark);
        }
    }


    //*
    //* function UpdateAbsencesFields, Parameter list: $class,$disc,$student
    //*
    //* Updates Student Disc absences fields.
    //*

    function UpdateAbsencesFields($class,$disc,$student)
    {
        for ($n=1;$n<=$this->ApplicationObj->ClassDiscNLessonsObject->Disc2NAssessments($class,$disc);$n++)
        {
            $this->UpdateAbsencesField
            (
               $class,
               $disc,
               $student,
               $n//,
               //$teacherid
            );
        }
    }
}

?>