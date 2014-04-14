<?php


class ClassMarksCalc extends ClassMarksUpdate
{
    //*
    //* function CalcMarksNAssessments, Parameter list: $disc,$marks
    //*
    //* Calculates number of assessments.
    //*

    function CalcMarksNAssessments($disc,$marks)
    {
        $weight=0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            if (!empty($marks[ $n ]))
            {
                if (preg_match('/\d/',$marks[ $n ]))
                {
                    $weight++;
                }
            }
        }

        return $weight;
    }

    //*
    //* function CalcMarksMedia, Parameter list: $disc,$marks
    //*
    //* Calculates media.
    //*

    function CalcMarksWeight($disc,$marks)
    {
        $weight=0.0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            if (!empty($marks[ $n ]))
            {
                if (preg_match('/\d/',$marks[ $n ]))
                {
                    $weight+=$disc[ "Weights" ][ $n-1 ][ "Weight" ];
                }
            }
        }

        $weight=sprintf("%.1f",$weight);
        if ($weight==0.0) { $weight="-"; }

        return $weight;
    }

    //*
    //* function CalcMarksSum, Parameter list: $disc,$marks
    //*
    //* Calculates media.
    //*

    function CalcMarksSum($disc,$marks)
    {
        $sum=0.0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            if (!empty($marks[ $n ]))
            {
                if (preg_match('/\d/',$marks[ $n ]))
                {
                    $sum+=$disc[ "Weights" ][ $n-1 ][ "Weight" ]*$marks[ $n ];
                }
            }
        }

        $sum=sprintf("%.1f",$sum+0.01);
        if ($sum==0.0) { $sum="-"; }

        return $sum;
    }

    //*
    //* function CalcMarksMedia, Parameter list: $disc,$marks
    //*
    //* Calculates media.
    //*

    function CalcMarksMedia($disc,$marks)
    {
        $sum=$this->CalcMarksSum($disc,$marks);
        $weight=$this->CalcMarksWeight($disc,$marks);

        $media=$sum;
        if ($weight>0.0)
        {
            $media=$media/$weight;
        }

        $media=sprintf("%.1f",$media+0.01);
        if ($media==0.0) { $media="-"; }

        return $media;
    }

    //*
    //* functionCalcNRecoveryFields , Parameter list: $disc,$marks
    //*
    //* Calculates number of assessments.
    //*

    function CalcNRecoveryFields($disc,$marks)
    {
        $nassessments=$this->CalcMarksNAssessments($disc,$marks);
        if ($nassessments<$disc[ "NAssessments" ]) { return 0; }

        $media=$this->CalcMarksMedia($disc,$marks);
        if ($media>=$disc[ "MediaLimit" ]) { return 0; }

        return $disc[ "NRecoveries" ];
    }

    //*
    //* function MarksRecoveryMediaWeightNo, Parameter list: $disc
    //*
    //* Returns weight media of in claculation recovery marks.
    //*

    function MarksRecoveryMediaWeightNo($disc)
    {
        return $disc[ "NAssessments" ]+1;
    }

    //*
    //* function MarksRecoveryRecoveryWeightNo, Parameter list: $media,$recno
    //*
    //* Returns weight media of in claculation recovery marks.
    //*

    function MarksRecoveryRecoveryWeightNo($disc,$recno)
    {
        return $disc[ "NAssessments" ]+$recno;
    }

    //*
    //* function MarksRecoveryMarkNo, Parameter list: $disc,$recno
    //*
    //* Returns weight media of in claculation recovery marks.
    //*

    function MarksRecoveryRecoveryMarkNo($disc,$recno)
    {
        return $disc[ "NAssessments" ]+$recno;
    }

    //*
    //* function MarksRecoveryMediaWeight, Parameter list:$disc,$recno
    //*
    //* Returns weight media in calculation recovery marks.
    //*

    function MarksRecoveryMediaWeight($disc,$recno)
    {
        return 1.0;
    }

    //*
    //* function MarksRecoveryRecoveryWeight, Parameter list: $disc,$recno
    //*
    //* Returns weight media of in claculation recovery marks.
    //*

    function MarksRecoveryRecoveryWeight($disc,$recno)
    {
        return $disc[ "Weights" ][ $this->MarksRecoveryRecoveryWeightNo($disc,$recno)-1 ][ "Weight" ];
    }

    //*
    //* function CalcRecoveryWeights, Parameter list: $disc,$recno
    //*
    //* Returns sum of weights of media and recovery marks.
    //*

    function CalcRecoveryWeights($disc,$recno)
    {
        return 
            $this->MarksRecoveryMediaWeight($disc,$recno)
            +
            $this->MarksRecoveryRecoveryWeight($disc,$recno);
            
    }

    //*
    //* function CalcRecoverySum, Parameter list: $disc,$media,$marks,$recno
    //*
    //* Calculates recovery media.
    //*

    function CalcRecoverySum($disc,$media,$marks,$recno)
    {
        $sum=0.0;

        $no=$this->MarksRecoveryRecoveryMarkNo($disc,$recno);

        if (!empty($marks[ $no ]))
        {
            $sum=
                $this->MarksRecoveryMediaWeight($disc,$recno)*$media
                +
                $this->MarksRecoveryRecoveryWeight($disc,$recno)*
                $marks[ $no ];
        }

        $sum=sprintf("%.1f",$sum);
        if ($sum==0.0) { $sum="-"; }

        return $sum;
    }

    //*
    //* function CalcRecoveryMedia, Parameter list: $disc,$media,$marks,$recno
    //*
    //* Calculates recovery media.
    //*

    function CalcRecoveryMedia($disc,$media,$marks,$recno)
    {
        $sum=$this->CalcRecoverySum($disc,$media,$marks,$recno);
        $weight=$this->CalcRecoveryWeights($disc,$recno);

        $media=$sum;
        if ($weight>0.0)
        {
            $media=$media/$weight;
        }

        $media=sprintf("%.1f",$media+0.01);
        if ($media==0.0) { $media="-"; }

        return $media;
    }

    //*
    //* function CalcStudentDiscMarks, Parameter list: $disc
    //*
    //* Populates discipline marks for student.

    function CalcStudentDiscMarks($disc,$marks)
    {
        $hash=array
        (
           "Marks" => $marks,
           "NAssessments" => $this->CalcMarksNAssessments($disc,$marks),
           "Media" => $this->CalcMarksMedia($disc,$marks),
           "Weight" => $this->CalcMarksWeight($disc,$marks),
           "Sum" => $this->CalcMarksSum($disc,$marks),
           "RecoveryMarks" => array(),
           "RecoveryMedias" => array(),
           "RecoverySums" => array(),
           "RecoveryWeigths" => array(),
           "RecoveryResults" => array(),
           "NRecoveries" => $this->CalcNRecoveryFields($disc,$marks),
           "MarkResult" => 0,
           "MediaResult" => 0,
           "MediaFinal" => "",
        );

        if ($hash [ "NAssessments" ]==$disc[ "NAssessments" ])
        {
            $hash [ "MediaFinal" ]=$hash [ "Media" ];
            if ($hash [ "Media" ]>=$disc[ "MediaLimit" ])
            {
                $hash [ "MediaResult" ]=2;
                $hash [ "MarkResult" ]=2;
            }
            else
            {
                $hash [ "MarkResult" ]=1;
                $hash [ "MediaResult" ]=1;
                $hash [ "MediaFinal" ]=$hash [ "Media" ];

                //Convenient to have semkester media as recovery mark 0
                $hash[ "RecoveryResults" ][ 0 ]=1;
                $hash[ "RecoveryMedias" ][ 0 ]=$hash [ "Media" ];

                $mm=$disc[ "NAssessments" ]+1;
                for ($m=1;$m<=$disc[ "NRecoveries" ];$m++,$mm++)
                {
                    $mark=NULL;
                    if (empty($marks[ $mm ])) { continue; }

                    $hash[ "RecoveryMarks" ][ $m ]=$mark;
                    $hash[ "RecoveryWeigths" ][ $m ]=$this->CalcRecoveryWeights($disc,$m);
                    $rmedia=$this->CalcRecoveryMedia
                    (
                       $disc,
                       $hash[ "Media" ],
                       $marks,
                       $m
                    );

                    $hash[ "RecoveryMedias" ][ $m ]=$rmedia;
                    $hash[ "RecoverySums" ][ $m ]=$this->CalcRecoverySum
                    (
                       $disc,
                       $hash[ "Media" ],
                       $marks,
                       $m
                    );

                    $hash [ "MediaFinal" ]=$rmedia;
                    if ($rmedia>=$disc[ "FinalMedia" ])
                    {
                        $hash [ "RecoveryResults" ][ $m ]=2;
                        $hash [ "MarkResult" ]=2;
                        break; //no more recoveries
                    }
                    else
                    {
                        $hash [ "RecoveryResults" ][ $m ]=1;
                    }
                }
            }
        }

        return $hash;

    }


}

?>