<?php

include_once("Class/Absences/Update.php");


class ClassAbsencesCalc extends ClassAbsencesUpdate
{
    //*
    //* function CalcAbsencesNAbsences, Parameter list: $disc,$marks
    //*
    //* Calculates number of assessments.
    //*

    function CalcAbsencesNAbsences($disc,$absences)
    {
        $weight=0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            if (!empty($absences[ $n ]))
            {
                if (preg_match('/\d/',$absences[ $n ]))
                {
                    $weight++;
                }
            }
        }

        return $weight;
    }


    //*
    //* function CalcAbsencesSum, Parameter list: $disc,$absences
    //*
    //* Calculates absences sum.
    //*

    function CalcAbsencesSum($disc,$absences)
    {
        $sum=0.0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            if (!empty($absences[ $n ]))
            {
                if (preg_match('/\d/',$absences[ $n ]))
                {
                    $sum+=$absences[ $n ];
                }
            }
        }

        $sum=sprintf("%d",$sum);
        if ($sum==0.0) { $sum="-"; }

        return $sum;
    }


    //*
    //* function CalcDiscNAbsences, Parameter list: $disc
    //*
    //* Calculates absences percent.
    //*

    function CalcDiscNAbsences($disc)
    {
        $nabsences=0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            if (
                  !empty($disc[ "NLessons" ])
                  &&
                  !empty($disc[ "NLessons" ][ $n-1 ])
                  &&
                  !empty($disc[ "NLessons" ][ $n-1 ][ "NLessons" ])
              )
            {
                $nabsences+=$disc[ "NLessons" ][ $n-1 ][ "NLessons" ];
            }
        }

        if ($nabsences==0) { $nabsences=0; }
        else               { $nabsences=sprintf("%d",$nabsences); }

        return $nabsences;
    }

    //*
    //* function CalcAbsencesPercent, Parameter list: $disc,$absences
    //*
    //* Calculates absences percent.
    //*

    function CalcAbsencesPercent($disc,$absences)
    {
        $nabsences=$this->CalcDiscNAbsences($disc);
 
        if ($nabsences>0)
        {
           $sum=$this->CalcAbsencesSum($disc,$absences);

            return 100.0*$sum/$nabsences;
        }

        return "-";
    }


    //*
    //* function CalcStudentDiscAbsences, Parameter list: $disc,$absences
    //*
    //* Populates discipline marks for student.

    function CalcStudentDiscAbsences($disc,$absences)
    {
        $hash=array
        (
           "Absences" => $absences,
           "NAssessments" => $this->CalcAbsencesNAbsences($disc,$absences),
           "Sum" => $this->CalcAbsencesSum($disc,$absences),
           "Percent" => $this->CalcAbsencesPercent($disc,$absences),
           "AbsencesResult" => 0,
        );

        //if ($hash[ "NAssessments" ]<$disc[ "NAssessments" ])
        //{
        //    $hash[ "AbsencesResult" ]=0;
        //}
        //else

        $hash[ "AbsencesResult" ]=0;
        if ($hash [ "NAssessments" ]==$disc[ "NAssessments" ])
        {
            if ($hash[ "Percent" ]>$disc[ "AbsencesLimit" ])
            {
                $hash[ "AbsencesResult" ]=1;
            }
            else
            {
                $hash[ "AbsencesResult" ]=2;
            }
        }

        return $hash;

    }


}

?>