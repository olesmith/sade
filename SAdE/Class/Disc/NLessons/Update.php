<?php


class ClassDiscNLessonsUpdate extends ClassDiscNLessonsRow
{
    //*
    //* function UpdateNLessonsField, Parameter list: $class,&$disc,$assessment
    //*
    //* Updates Disc nlessons field.
    //*

    function UpdateNLessonsField($class,&$disc,$assessment)
    {
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
            $this->MySqlSetItemsValue
            (
               "",
               "ID",
               $disc[ "NLessons" ][ $assessment-1 ][ "ID" ],
               "NLessons",
               $newvalue
             );

            $disc[ "NLessons" ][ $assessment-1 ][ "NLessons" ]=$newvalue;
        }
    }


    //*
    //* function UpdateNLessonsFields, Parameter list: $class,&$disc
    //*
    //* Updates Disc nlessons fields.
    //*

    function UpdateNLessonsFields($class,&$disc)
    {
        if (empty($disc[ "NLessons" ]))
        {
            $this->ReadClassDiscNLessons($class,$rdisc);
        }

        for ($n=1;$n<=$this->Disc2NAssessments($class,$disc);$n++)
        { 
            if ($this->NLessonsFieldEditable(1,$disc[ "NLessons" ][ $n-1 ])==1)
            {
                $this->UpdateNLessonsField($class,$disc,$n);
            }
        }
    }

}

?>