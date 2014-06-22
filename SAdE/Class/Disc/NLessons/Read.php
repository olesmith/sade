<?php

class ClassDiscNLessonsRead extends ClassDiscNLessonsUpdate
{
    //*
    //* function ReadClassDiscNLesson, Parameter list: $class,$disc,$assessment
    //*
    //* Reads NAssessment ClassDiscNLessons entries.
    //*

    function ReadClassDiscNLesson($class,$disc,$assessment)
    {
        $nlessons=$this->ReadOrAdd
        (
           $this->NLesssonsDefaultItem
           (
              $class,
              $disc,
              $assessment
           )
        );

        if (empty($nlessons[ "SecEdit" ]))
        {
            $nlessons[ "SecEdit" ]=2;
        }

        return $nlessons;
    }


    //*
    //* function ReadClassDiscNLessons, Parameter list: $class,&$disc
    //*
    //* Reads NAssessment ClassDiscNLessons entries.
    //*

    function ReadClassDiscNLessons($class,&$disc)
    {
        if (empty($class)) { return; }

        $disc[ "NLessons" ]=array();
        for ($assessment=1;$assessment<=$this->Disc2NAssessments($class,$disc);$assessment++)
        {
            array_push
            (
               $disc[ "NLessons" ],
               $this->ReadClassDiscNLesson($class,$disc,$assessment)
            );
        }
    }

}

?>