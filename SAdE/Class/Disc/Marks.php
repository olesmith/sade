<?php


include_once("Class/Disc/Marks/Calc.php");
include_once("Class/Disc/Marks/Update.php");
include_once("Class/Disc/Marks/Cell.php");
include_once("Class/Disc/Marks/Cells.php");
include_once("Class/Disc/Marks/Row.php");
include_once("Class/Disc/Marks/Titles.php");
include_once("Class/Disc/Marks/Table.php");
include_once("Class/Disc/Marks/Tables.php");
include_once("Class/Disc/Marks/Latex.php");
include_once("Class/Disc/Marks/Reads.php");
include_once("Class/Disc/Marks/Handle.php");

class ClassDiscMarks extends ClassDiscMarksHandle
{
    //*
    //* Variables of ClassDiscMarksTable class:
    //*

    var $Assessments=array();
    var $StudentData=array("Matricula","MatriculaDate","Name","Status","StatusDate1");

    //Default number of mark cells on Latex output.
    var $NMarkCells=15;

    //*
    //*
    //* Constructor.
    //*

    function ClassDiscMarks($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->Sort=array("Date");
    }

    //*
    //* function MakeMarksMenu, Parameter list: $disc
    //*
    //* Genrerates sub horisontal menu for Marks module.
    //*

    function MakeMarksMenu($disc)
    {
        print $this->ApplicationObj->ClassesObject->MakeActionMenu
        (
           array
           (
              "DaylyMarksPrint",
           ),
           "ptablemenu",
           $disc[ "ID" ]
        );
    }


    //*
    //* function StudentMarkCGIName, Parameter list: $student,$assessment
    //*
    //* Generates absence cell for $student/$assessment.
    //*

    function StudentMarkCGIName($student,$assessment)
    {
        return "Mark_".$student[ "StudentHash" ][ "ID" ]."_".$assessment[ "ID" ];
    }

    //*
    //* functionSemestersMenu , Parameter list: $args
    //*
    //* Generates semesters menu.
    //*

    function SemestersMenu($args)
    {
        $semester=$this->GetGET("Semester");
        $nassess=4;
        $hrefs=array();
        for ($n=1;$n<=$this->ApplicationObj->Disc[ "NAssessments" ];$n++)
        {
            $name=$n."º ".$this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle();
            if ($n!=$semester)
            {
               unset($args[ "Month" ]);
                $args[ "Semester" ]=$n;
                $hrefs[ $n-1 ]=$this->Href
                (
                   "?".$this->Hash2Query($args),
                   $name
                );
            }
            else
            {
                $hrefs[ $n-1 ]=$name;
            }
        }

        $args[ "Semester" ]=$this->ApplicationObj->Disc[ "NAssessments" ]+1;
        $hrefs[ $this->ApplicationObj->Disc[ "NAssessments" ] ]=$this->Href
        (
           "?".$this->Hash2Query($args),
           "Recuperação"
        );

        unset($args[ "Semester" ]);
        $hrefs[ $this->ApplicationObj->Disc[ "NAssessments" ]+1 ]=$this->Href
        (
           "?".$this->Hash2Query($args),
           "Todos"
        );

        return $this->Center("[ ".join(" | ",$hrefs)." ]");
    }

    //*
    //* function StudentMarkSqlWhere, Parameter list: $student,$assessment
    //*
    //* Generates absence sql where clause for $student/$assessment.
    //*

    function StudentMarkSqlWhere($student,$assessment)
    {
        return array
        (
           "Class" => $this->ApplicationObj->Class[ "ID" ],
           "Disc"  => $this->ApplicationObj->Disc[ "ID" ],
           "Student" => $student[ "StudentHash" ][ "ID" ],
           "Assessment" => $assessment[ "ID" ],
       );
    }    
}

?>