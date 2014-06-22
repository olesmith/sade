<?php


class ClassDiscNLessonsRow extends ClassDiscNLessonsField
{
    //*
    //* function NLessonsRow, Parameter list: $edit=0,$class=array(),$disc=array()
    //*
    //* Makes Marks HTML table for disc $disc.
    //*

    function NLessonsRow($edit=0,$class=array(),$disc=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        if (empty($disc))  { $disc=$this->ApplicationObj->Disc; }

        if (empty($disc[ "NLessons" ]))
        {
            $this->ReadClassDiscNLessons($class,$disc);
        }

        if (intval($disc[ "AbsencesType" ])==$this->ApplicationObj->AbsencesNo)
        {
            $n=$disc[ "NAssessments" ];
            if ($showtotals) { $n++; }
            return array($this->MultiCell("",$n));
        }

        $row=array();
        $total=0;
        for ($n=1;$n<=$this->Disc2NAssessments($class,$disc);$n++)
        {
            $nlesson=$disc[ "NLessons" ][ $n-1 ];

            $cell="";
            if (!empty($nlesson[ "NLessons" ]))
            {
                $val=$nlesson[ "NLessons" ];
                $total+=$val;
                $cell=$val;
            }

            $redit=$this->NLessonsFieldEditable($edit,$nlesson);
            if ($redit==1)
            {
                $cell=$this->MakeInput
                (
                   $this->NLessonsFieldCGIVar($this->ApplicationObj->Class,$disc,$n),
                   $cell,
                   2,
                   array
                   (
                      "TABINDEX" => $n+10,
                   )
                );
            }
            
            array_push($row,$cell);
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowNLessonsTotals)
        {
            array_push($row,$total);
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowNLessonsPercent)
        {
            array_push($row,"100.0");
        }
        return $row;
    }
}

?>