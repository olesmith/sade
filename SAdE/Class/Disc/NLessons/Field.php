<?php


class ClassDiscNLessonsField extends ClassDiscNLessonsImport
{
    //*
    //* function NLessonsFieldCGIVar, Parameter list: $class,$disc,$assessment
    //*
    //* Returns the name associated with disc nlessons no $n.
    //*

    function NLessonsFieldCGIVar($class,$disc,$assessment)
    {
        $list=array("NLessons",$class[ "ID" ]);
        if (!empty($disc[ "ID" ]))
        {
            array_push($list,$disc[ "ID" ]);
        }

        array_push($list,$assessment);
        return join("_",$list);
    }

    //*
    //* function NLessonsFieldCGIRegex, Parameter list: $classid,$discid
    //*
    //* Returns CGI key name of mark field.
    //*

    function NLessonsFieldCGIRegex($class,$disc)
    {
        $list=array("NLessons",$class[ "ID" ]);
        if ($class[ "AbsencesType" ]==$this->ApplicationObj->AbsencesYes)
        {
            array_push($list,$disc[ "ID" ]);
        }

        return join("_",$list);
    }

    //*
    //* function NLesssonsFieldSqlWhere, Parameter list: $classid,$discid,$studentid,$teacherid,$assessment
    //*
    //* Returns CGI key name of mark field.
    //*

    function NLesssonsFieldSqlWhere($class,$disc,$assessment)
    {
        $classid=$class;
        if (is_array($class)) { $classid=$class[ "ID" ]; }
        $where=array
        (
           "Class" => $classid,
           "Assessment" => $assessment,       
           //"Name" => "No. ".$assessment,
           //"NLessons" => 0,
        );

        if ($disc[ "AbsencesType" ]!=$this->ApplicationObj->OnlyTotals || !empty($disc[ "ID" ]))
        {
            $where[ "ClassDisc" ]=$disc[ "ID" ];
        }
        else
        {
            $where[ "ClassDisc" ]=0;
        }

        return $where;
    }


    //*
    //* function NLessonsField, Parameter list: $edit,$class,$disc,$n,$tab=1
    //*
    //* Genrates NLessons field.
    //*

    function NLessonsField($edit,$class,$disc,$n,$tab=1)
    {
        if ($edit==0) { return $this->GetNLessons($disc,$n); }

        return $this->MakeInput
        (
           $this->NLessonsFieldCGIVar($class,$disc,$n),
           $this->GetNLessons($class,$disc,$n),
           2,
           array
           (
              "TABINDEX" => $tab,
           )
        );
    }

    //*
    //* function NLessonsFieldEditable, Parameter list: $edit,$nlesson
    //*
    //* Returns 1 if NLessonsField is editable, 0 otherwise.
    //*

    function NLessonsFieldEditable($edit,$nlesson)
    {
        if ($edit==1)
        {
            if (
                  intval($nlesson[ "NLessons" ])>0
                  &&
                  !empty($nlesson[ "SecEdit" ])
                  &&
                  intval($nlesson[ "SecEdit" ])==1
               )
            {

                $edit=0;
            }
        }

        return $edit;
    }

 }

?>