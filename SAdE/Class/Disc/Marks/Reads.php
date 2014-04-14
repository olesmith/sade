<?php


class ClassDiscMarksReads extends ClassDiscMarksLatex
{
    //*
    //* function ReadDaylyStudent, Parameter list: $assess,$student
    //*
    //* Reads $student Dayly totals from DB.
    //*

    function ReadDaylyStudent($assess,$student)
    {
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
              "Class" => $this->ApplicationObj->Class[ "ID" ],
              "Disc" => $this->ApplicationObj->Disc[ "ID" ],
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

            $studmarks[ "Marks" ][ $semester ]=$this->SemesterStudentMark($student,$this->Assessments[ $semester ]);
        }

        $studmarks[ "MarksHash" ]=$this->ApplicationObj->ClassMarksObject->CalcStudentDiscMarks
        (
           $this->ApplicationObj->Disc,
           $studmarks[ "Marks" ]
        );

        return $studmarks;
    }
}

?>