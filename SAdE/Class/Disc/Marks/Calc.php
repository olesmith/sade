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
        /* $markhash=$this->SelectUniqueHash */
        /* ( */
        /*    "", */
        /*    $this->StudentMarkSqlWhere($student,$assessment), */
        /*    TRUE, //no echo, may be absent */
        /*    array("ID","Mark") */
        /* ); */

        $marks=$this->SelectHashesFromTable("",$this->StudentMarkSqlWhere($student,$assessment),array("ID","Mark"),FALSE,"ID");

        $rmarks=array();
        foreach ($marks as $mark)
        {
            if (!empty($mark[ "Mark" ]) && $mark[ "Mark" ]>0.0) { array_push($rmarks,$mark); }
        }

        $markhash=array();
            if (count($rmarks)>0) { $markhash=array_pop($rmarks); }
        elseif (count($marks)>0)  { $markhash=array_pop($marks); }

/*           if ($student[ "StudentHash" ][ "ID" ]==276){ */

/*               $marks=$this->SelectHashesFromTable("",$this->StudentMarkSqlWhere($student,$assessment)); */
/*               var_dump($this->StudentMarkSqlWhere($student,$assessment)); */
/*               var_dump($marks); */

/* } */
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
    //* function TrimesterStudentMark, Parameter list: $student,,$semester
    //*
    //* Reads semester ind. marks and calculates semester media.
    //*

    function TrimesterStudentMark($student,$semester)
    {
        $hasmedia=FALSE;
        $weight=0.0;
        $media=0.0;
        $secmark=$this->ApplicationObj->ClassMarksObject->ReadStudentDiscMark
        (
           $this->ApplicationObj->Class,
           $this->ApplicationObj->Disc,
           $student,
           $semester,
           2
        );

        if (preg_match('/^\d+(\.\d)?$/',$secmark))
        {
            $media+=$secmark;
        }

        foreach ($this->Assessments[ $semester ] as $assessment)
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
            $media=$this->Min($media,10.0);
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
            $mmedia=$this->TrimesterStudentMark($student,$this->Assessments[ $semester ],$semester);
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
    //* function TrimesterWeights, Parameter list: $assessments
    //*
    //* Sums semester ind. marks weights
    //*

    function TrimesterWeights($assessments)
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