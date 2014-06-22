<?php


class ClassDiscAbsencesReads extends ClassDiscAbsencesLatex
{
    //*
    //* function ReadDaylyStudent, Parameter list: $discchs,$student
    //*
    //* Reads $student Dayly totals from DB.
    //*

    function ReadDaylyStudent($discchs,$student)
    {
        $chs=array
        (
           "Period" => 0,
           "SortKey" => array(),
           "Month" => array(),
           "Semester" => array()
        );

        $absences=$this->SelectHashesFromTable
        (
           "",
           array
           (
              "Class" => $this->ApplicationObj->Class[ "ID" ],
              "Disc" => $this->ApplicationObj->Disc[ "ID" ],
              "Student" => $student[ "StudentHash" ][ "ID" ],
           ),
           array()
        );

        foreach ($absences as $absence)
        {
            $content=$this->ApplicationObj->ClassDiscContentsObject->SelectUniqueHash
            (
               "",
               array("ID" => $absence[ "Content" ]),
               FALSE,
               array("ID","Date","Weight")
            );

            if (empty($content[ "Date" ])) { continue; }

            $date=$this->ApplicationObj->DatesObject->ReadDate
            (
               $content[ "Date" ],
               array(),
               array("ID","Month","SortKey")
            );

            //Per Period
            $chs[ "Period" ]+=$content[ "Weight" ];

            //Per date (SortKey)
            $key="SortKey";

            $value=$date[ $key ];
            if (!isset($chs[ $key ][ $value ]))
            {
                $chs[ $key ][ $value ]=0;
            }

            $chs[ $key ][ $value ]+=$content[ "Weight" ];

            //Per Month
            $key="Month";
            $value=$date[ $key ];
            if (!isset($chs[ $key ][ $value ]))
            {
                $chs[ $key ][ $value ]=0;
            }
            $chs[ $key ][ $value ]+=$content[ "Weight" ];

                
            //Per Semester
            $key="Semester";
            $value=$date[ $key ];
            if (!isset($chs[ $key ][ $value ]))
            {
                $chs[ $key ][ $value ]=0;
            }
            $chs[ $key ][ $value ]+=$content[ "Weight" ];
        }

        return $chs;
    }
}

?>