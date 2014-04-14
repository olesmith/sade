<?php


class ClassDiscMarksCalc extends Common
{
    //*
    //* function GetStudentMarkHash, Parameter list: $student,$assessment
    //*
    //* Detects whether mark is in DB. If so, return hash with ID and Mark keys.
    //*

    function GetStudentMarkHash($student,$assessment)
    {
        $markhash=$this->SelectUniqueHash
        (
           "",
           $this->StudentMarkSqlWhere($student,$assessment),
           TRUE, //no echo, may be absent
           array("ID","Mark")
        );

        if (!empty($markhash)) { return $markhash; }

        return array();
    }

    //*
    //* function GetStudentMarkValue, Parameter list: $student,$assessment
    //*
    //* Returns the mark value, as string (number);
    //*

    function GetStudentMarkValue($student,$assessment)
    {
        $markhash=$this->GetStudentMarkHash($student,$assessment);

        if (!empty($markhash)) { return $markhash[ "Mark" ]; }

        return "";
    }

    //*
    //* function SemesterStudentMark, Parameter list: $student,$assessments
    //*
    //* Reads semester ind. marks and calculates semester media.
    //*

    function SemesterStudentMark($student,$assessments)
    {
        $hasmedia=FALSE;
        $weight=0.0;
        $media=0.0;
        foreach ($assessments as $assessment)
        {
            $mmedia=$this->GetStudentMarkValue($student,$assessment);
            if (!empty($mmedia) || preg_match('/^0\.?0*$/',$mmedia))
            {
                $mmedia=$this->Min($mmedia,$assessment[ "MaxVal" ]);

                $media+=$mmedia;
                $hasmedia=TRUE;
            }

            $weight+=$assessment[ "MaxVal" ];
        }

        if ($weight>0.0)
        {
            $media*=10.0/$weight;
        }

        if ($hasmedia)
        {
            $media=sprintf("%.1f",$media+0.01);
        }
        else
        {
            $media="";
        }

        return $media;
    }

    //*
    //* function DiscStudentMark, Parameter list: $student
    //*
    //* Calculates final mark for student
    //*

    function DiscStudentMark($student)
    {
        $hasmedia=0;
        $weight=0.0;
        $media=0.0;
        for ($semester=1;$semester<=$this->ApplicationObj->Disc[ "NAssessments" ];$semester++)
        {
            $mmedia=$this->SemesterStudentMark($student,$this->Assessments[ $semester ]);
            if (!empty($mmedia) || preg_match('/^0\.?0*$/',$mmedia))
            {
                $rweight=$this->ApplicationObj->Disc[ "Weights" ][ $semester-1 ][ "Weight" ];

                $media+=$rweight*$mmedia;
                $weight+=$rweight;
                $hasmedia++;
            }
        }

        if ($hasmedia==$this->ApplicationObj->Disc[ "NAssessments" ])
        {
            $media/=$weight;
            $media=sprintf("%.1f",$media+0.01);
        }
        else
        {
            $media="-";
        }

        return $media;
    }

    //*
    //* function SemesterWeights, Parameter list: $assessments
    //*
    //* Sums semester ind. marks weights
    //*

    function SemesterWeights($assessments)
    {
        $weight=0.0;
        foreach ($assessments as $assessment)
        {
            $weight+=$assessment[ "MaxVal" ];
        }

        return sprintf("%.1f",$weight);
    }

    //*
    //* function DiscWeights, Parameter list:
    //*
    //* Sums semester ind. marks weights
    //*

    function DiscWeights()
    {
        $weight=0.0;
        foreach ($this->Assessments as $semester => $assessments)
        {
            $weight+=$this->ApplicationObj->Disc[ "Weights" ][ $semester-1 ][ "Weight" ];
        }

        return sprintf("%.1f",$weight);
    }

}

?>