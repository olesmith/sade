<?php


class ClassDiscMarksReads extends ClassDiscMarksLatex
{
    //*
    //* function ReadDaylyAssessments, Parameter list: 
    //*
    //* Reads Assessments, calling  ClassDiscAssessmentsObject.
    //*

    function ReadDaylyAssessments()
    {
        if (empty($this->ApplicationObj->ClassDiscAssessmentsObject->Assessments))
        {
            $this->ApplicationObj->ClassDiscAssessmentsObject->ReadDaylyAssessments();
        }

        $this->Assessments=$this->ApplicationObj->ClassDiscAssessmentsObject->Assessments;
    }

    //*
    //* function ReadDaylyStudent, Parameter list: $assess,$student,$disc=array(),$class=array()
    //*
    //* Reads $student Dayly totals from DB.
    //*

    function ReadDaylyStudent($assess,$student,$disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $studmarks=array
        (
           "Period" => 0,
           "Semester" => array(),
           "Marks" => array(),
        );

        $marks=$this->SelectHashesFromTable
        (
           "",
           array
           (
              "Class" => $class[ "ID" ],
              "Disc" => $disc[ "ID" ],
              "Student" => $student[ "StudentHash" ][ "ID" ],
           ),
           array("ID","Assessment","Mark")
        );


        foreach ($marks as $mark)
        {
            $assessment=$this->ApplicationObj->ClassDiscAssessmentsObject->SelectUniqueHash
            (
               "",
               array("ID" => $mark[ "Assessment" ]),
               FALSE,
               array("ID","Semester","Number","MaxVal")
            );
 
            $number=$assessment[ "Number" ];
            $semester=$assessment[ "Semester" ];
            if (!isset($studmarks[ "Semester" ][ $semester ]))
            {
                $studmarks[ "Semester" ][ $semester ]=array();
            }

            $studmarks[ "Semester" ][ $semester ][ $number ]=$mark;

            $studmarks[ "Marks" ][ $semester ]="";
            if (!empty($this->Assessments[ $semester ]))
            {
                $studmarks[ "Marks" ][ $semester ]=$this->TrimesterStudentMark($student,$semester);
            }
        }

        $studmarks[ "MarksHash" ]=$this->ApplicationObj->ClassMarksObject->CalcStudentDiscMarks
        (
           $disc,
           $studmarks[ "Marks" ]
        );

        return $studmarks;
    }
}

?>