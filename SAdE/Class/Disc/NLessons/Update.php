<?php


class ClassDiscNLessonsUpdate extends ClassDiscNLessonsRow
{
    //*
    //* function UpdateNLessonsField, Parameter list: $class,&$disc,$assessment,$secedit=1
    //*
    //* Updates Disc nlessons field.
    //*

    function UpdateNLessonsField($class,&$disc,$assessment,$secedit=1)
    {
        $updatedatas=array();

        $oldvalue=$this->GetNLessons($class,$disc,$assessment);
        $newvalue=$this->GetPOST
        (
           $this->NLessonsFieldCGIVar
           (
              $class,
              $disc,
              $assessment
           )
        );

        $var="NLessons".$assessment;
        if ($newvalue!=$oldvalue)
        {
            $disc[ "NLessons" ][ $assessment-1 ][ "NLessons" ]=$newvalue;
            array_push($updatedatas,"NLessons");
        }

        if ($secedit!=$disc[ "NLessons" ][ $assessment-1 ][ "SecEdit" ])
        {
            $disc[ "NLessons" ][ $assessment-1 ][ "SecEdit" ]=$secedit;
            array_push($updatedatas,"SecEdit");
        }

        $this->MySqlSetItemValues
        (
           "",
           $updatedatas,
           $disc[ "NLessons" ][ $assessment-1 ]
        );
    }


    //*
    //* function UpdateNLessonsFields, Parameter list: $class,&$disc,$secedit=1
    //*
    //* Updates Disc nlessons fields.
    //*

    function UpdateNLessonsFields($class,&$disc,$secedit=1)
    {
        if (empty($disc[ "NLessons" ]))
        {
            $this->ReadClassDiscNLessons($class,$rdisc);
        }

        for ($n=1;$n<=$this->Disc2NAssessments($class,$disc);$n++)
        { 
            if ($this->NLessonsFieldEditable(1,$disc[ "NLessons" ][ $n-1 ])==1)
            {
                $this->UpdateNLessonsField($class,$disc,$n,$secedit);
            }
        }
    }

}

?>