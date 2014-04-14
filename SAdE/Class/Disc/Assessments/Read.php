<?php


class ClassDiscAssessmentsRead extends ClassDiscAssessmentsUpdate
{
    //*
    //* function ReadDaylyAssessments, Parameter list: $disc=array(),$class=array()
    //*
    //* Handles Dayly Assessments pages.
    //*

    function ReadDaylyAssessments($disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $this->DiscDefaultAssessments($disc,$class);

        $assessments=array();
        for ($trimester=1;$trimester<=($disc[ "NAssessments" ]+$disc[ "NRecoveries" ]);$trimester++) { array_push($assessments,$trimester); }
        $this->Assessments=$this->SelectHashesFromTable
        (
           "",
           array
           (
               "Class" => $class,
               "Disc" => $disc[ "ID" ],
               "Semester" => "IN ('".join("','",$assessments)."')",
            ),
           array("ID","Semester,Number","MaxVal","Name"),
           FALSE,
           "Semester,Number"
        );

        $rassessments=array();
        foreach ($this->Assessments as $assessment)
        {
            if (empty($rassessments[ $assessment[ "Semester" ] ]))
            {
                $rassessments[ $assessment[ "Semester" ] ]=array();
            }

            array_push($rassessments[ $assessment[ "Semester" ] ],$assessment);
        }

        $this->Assessments=$rassessments;

        $assess=array
        (
           "MaxVal" => array(),
           "Weight" => 0.0,
        );

        for ($semester=1;$semester<=$this->ApplicationObj->Disc[ "NAssessments" ];$semester++)
        {
            $assess[ "MaxVal" ][ $semester ]=0.0;
            foreach ($this->Assessments[ $semester ] as $assessment)
            {
                $assess[ "MaxVal" ][ $semester ]+=$assessment[ "MaxVal" ];
            }

            $assess[ "Weight" ]+=$this->ApplicationObj->Disc[ "Weights" ][ $semester-1 ][ "Weight" ];
        }

        return $assess;
    }


    //*
    //* function DiscDefaultAssessments, Parameter list: $disc=array(),$class=array()
    //*
    //* Checks for existence of default marks, if not create.
    //* One should be present for each Semester.
    //*

    function DiscDefaultAssessments($disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            $where=array
            (
               "Class" => $class[ "ID" ],
               "Disc" => $disc[ "ID" ],
               "Semester" => $n,
            );

            $newass=$where;
            $newass[ "Name" ]="Nota 1";
            $newass[ "Number" ]=1;
            $newass[ "MaxVal" ]=10.0;
            $newass[ "CTime" ]=time();                    
            $newass[ "ATime" ]=time();                    
            $newass[ "MTime" ]=time();                    
            $msg=$this->MySqlInsertUnique("",$where,$newass);
        }

        for ($n=1;$n<=$disc[ "NRecoveries" ];$n++)
        {
            $nn=$disc[ "NAssessments" ]+$n;

            $where=array
            (
               "Class" => $class[ "ID" ],
               "Disc" => $disc[ "ID" ],
               "Semester" => $nn,
            );

            $newass=$where;
            $newass[ "Name" ]="Recup. 1";
            $newass[ "Number" ]=1;
            $newass[ "MaxVal" ]=10.0;
            $newass[ "CTime" ]=time();                    
            $newass[ "ATime" ]=time();                    
            $newass[ "MTime" ]=time();                    
            $msg=$this->MySqlInsertUnique("",$where,$newass);
        }
    }
}

?>