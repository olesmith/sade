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

        if ($class[ "AbsencesType" ]==$this->ApplicationObj->OnlyTotals)
        {
            if (empty($disc[ "ID" ]))
            {
                //Sum individual noof lessons
                $where=array
                (
                   "Class" => $class[ "ID" ],
                   "Assessment" => $assessment,       
                );

                $items=$this->SelectHashesFromTable("",$where,array("ClassDisc","NLessons","SecEdit"));

                $registeredbyteacher=FALSE;
                $nregistered=0;
                foreach ($items as $item)
                {
                    if ($item[ "ClassDisc" ]>0 && isset($item[ "NLessons" ]))
                    {
                        $nregistered+=$item[ "NLessons" ];
                        if ($item[ "SecEdit" ]==1)
                        {
                            $registeredbyteacher=TRUE;
                        }
                    }
                }

                $secedit=2;
                if ($registeredbyteacher)
                {
                    $secedit=1;
                    if (empty($nlessons[ "NLessons" ]) || $nlessons[ "NLessons" ]!=$nregistered)
                    {
                        $this->MySqlSetItemValue("","ID",$nlessons[ "ID" ],"NLessons",$nregistered);
                        $nlessons[ "NLessons" ]=$nregistered;
                    }
                }

                if ($nlessons[ "SecEdit" ]!=$secedit)
                {
                    $this->MySqlSetItemValue("","ID",$nlessons[ "ID" ],"SecEdit",$secedit);
                    $nlessons[ "SecEdit" ]=$secedit;
                }
            }

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