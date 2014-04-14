<?php


class ClassDiscsMenu extends ClassDiscsInfoTable
{
    //*
    //* function DiscsMenu, Parameter list: $absences=TRUE,$marks=TRUE
    //*
    //* Creates horisontal discs menu, links to the discs.
    //*

    function DiscsMenu($absences=TRUE,$marks=TRUE)
    {
        if (empty($this->ApplicationObj->Discs)) { return ""; }
        if ($this->GetGET("Class")=="") { return ""; }

        $args=$this->Query2Hash();
        $args=$this->Hidden2Hash($args);

        $args[ "ModuleName" ]="Classes";

        $links=array();
        $names=array();
        $titles=array();
        $current="";

        foreach ($this->ApplicationObj->Discs as $disc)
        {
            if (
                  !$absences
                  &&
                  $disc[ "AbsencesType" ]==$this->ApplicationObj->AbsencesNo
                ) { continue; }

            if (
                  !$marks
                  &&
                  $disc[ "AssessmentType" ]==$this->ApplicationObj->MarksNo
                ) { continue; }

            $args[ "Disc" ]=$disc[ "ID" ];
            array_push($links,"?".$this->Hash2Query($args));
            array_push($names,$disc[ "NickName" ]);
            array_push($titles,$disc[ "Name" ]);
            if ($this->ApplicationObj->Disc[ "ID" ]==$disc[ "ID" ])
            {
                $current=$disc[ "Name" ];
            }
        }

        return $this->HRefMenu
        (
            "",
            $links,
            $names,
            $titles,
            6,
            "discmenuinactive",
            "discmenuitem",
            "discmenutitle",
            $current
         );
    }

    //*
    //* function DiscMenu, Parameter list: $disc=array(),$absences=TRUE,$marks=TRUE
    //*
    //* Creates horisontal disc menu, links to the individual actions.
    //*

    function DiscMenu($disc=array(),$absences=TRUE,$marks=TRUE)
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        return preg_replace
        (
           '/#Class/',
           $this->ApplicationObj->Class[ "ID" ],
           $this->ApplicationObj->ClassesObject->MakeActionMenu
           (
              array("DiscMarks","DiscAbsences","DiscTotals","Dayly","DiscPrint"),
              "ptablemenu",
              $disc[ "ID" ]
           )
        );
    }


    //*
    //* function MakeDiscMenu, Parameter list:
    //*
    //* Generates Disc menu.
    //*

    function MakeDiscMenu()
    {
        return array
        (
           preg_replace
           (
              '/#Disc/',
              $this->ApplicationObj->Disc[ "ID" ],
              $this->DiscMenu()
           )
        );
    }

    //*
    //* function MakeStudentMenu, Parameter list:
    //*
    //* Generates Student menu.
    //*

    function MakeStudentMenu()
    {
        return array
        (
           preg_replace
           (
              '/#Student/',
              $this->ApplicationObj->Student[ "ID" ],
              $this->ApplicationObj->StudentsObject->StudentMenu()
           )
        );
    }
   
}

?>