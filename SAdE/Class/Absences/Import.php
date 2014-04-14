<?php


class ClassAbsencesImport extends Common
{    
    //*
    //* function ImportStudentAbsences, Parameter list: $absencesline,$class,$student,&$table
    //*
    //* Calls functions for importing Marks.
    //* 
    //*

    function ImportStudentAbsences($absencesline,$class,$student,$disc,&$table)
    {
        $teacher=0;
        if (!empty($class[ "TeacherID" ]))
        {
            $teacher=$class[ "TeacherID" ];
        }
        elseif (!empty($disc[ "TeacherID" ]))
        {
            $teacher=$disc[ "TeacherID" ];
        }

        //Totals, when $disc is empty
        if (empty($disc))
        {
            $disc[ "Name" ]="Totals";
            $disc[ "Chave" ]="Totals";
            $disc[ "ID" ]=0;
        }

        $comps=preg_split('/\s+/',$absencesline);

        $abss=array();
        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            $where=array
            (
               "Class" => $class[ "ID" ],
               "ClassDisc" => $disc[ "ID" ],
               "Student" => $student[ "StudentHash" ][ "ID" ],
               "Assessment" => $n,
            );

            $abs=$where;
            $abs[ "Teacher" ]=$teacher;
            $abs[ "Absences" ]=0;

            if (!empty($comps[$n]))
            {
                $abs[ "Absences" ]=preg_replace('/[^\d-]/',"",$comps[$n]);
            }

            $msg=$this->AddOrUpdate("",$where,$abs);
            array_push($abss,$abs[ "Absences" ]);
        }

        //print
        array_push
        (
           $table,
           array
           (
              "",
              $class[ "Year" ].".".$class[ "Year" ],
              "Absences ".$disc[ "Name" ],
              $student[ "StudentHash" ][ "Name" ],
              $msg,
              $abs[ "Assessment" ],
              join(", ",$abss),
              $this->SqlTableName()
           )
        );
    }
}

?>