<?php

class ClassMarksImport extends Common
{
    //*
    //* function ImportStudentMarks, Parameter list: $class,$student,&$table
    //*
    //* Calls functions for importing Marks.
    //* 
    //*

    function ImportStudentMarks($marksline,$class,$student,$disc,&$table)
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

        $comps=preg_split('/\s/',$marksline);

        $marks=array();
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            $where=array
            (
               "Class" => $class[ "ID" ],
               "ClassDisc" => $disc[ "ID" ],
               "Student" => $student[ "StudentHash" ][ "ID" ],
               "Assessment" => $n,
            );

            $mark=$where;
            $mark[ "Teacher" ]=$teacher;
            $mark[ "Mark" ]="";

            if (!empty($comps[$n]))
            {
                $mark[ "Mark" ]=preg_replace('/[^\d-]/',"",$comps[$n]);
            }

            $msg=$this->AddOrUpdate("",$where,$mark);

            array_push($marks,$mark[ "Mark" ]);

        }



        //Report
        array_push
        (
           $table,
           array
           (
              "",
              "",
              "",
              "Marks ".$disc[ "Name" ]." - ".$disc[ "ID" ],
              $msg,
              join(", ",$marks)
           )
        );
    }
 }

?>