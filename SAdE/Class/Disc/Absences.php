<?php


include_once("Class/Disc/Absences/Cell.php");
include_once("Class/Disc/Absences/Update.php");
include_once("Class/Disc/Absences/Row.php");
include_once("Class/Disc/Absences/Titles.php");
include_once("Class/Disc/Absences/Table.php");
include_once("Class/Disc/Absences/Latex/Row.php");
include_once("Class/Disc/Absences/Latex/Titles.php");
include_once("Class/Disc/Absences/Latex/Students.php");
include_once("Class/Disc/Absences/Latex.php");
include_once("Class/Disc/Absences/Reads.php");
include_once("Class/Disc/Absences/Handle.php");

class ClassDiscAbsences extends ClassDiscAbsencesHandle
{
    //*
    //* Variables of ClassDiscAbsences class:
    //*

    var $Contents=array();
    var $StudentData=array("Matricula","MatriculaDate","Name","Status","StatusDate1");
    var $StudentLatexData=array("Matricula","Name","Status");

    //Default number of contents/dates cells in Latex output.
    var $NDatesPerPage=20;

    //Default number of students per page in Latex output.
    var $NStudentsPerPage=25;

    var $AbsencesActions=array
    (
       //"DaylyAbsences",
       "DaylyAbsencesStats",
       "DaylyAbsencesMonths",
       "DaylyAbsencesSemesters",
       "DaylyAbsencesPrint",
    );

    //*
    //*
    //* Constructor.
    //*

    function ClassDiscAbsences($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->Sort=array("Date");
    }

    //*
    //* function GetStudentStatus, Parameter list: $student,$month,$date=""
    //*
    //* Handles Dayly Absences pages.
    //*

    function GetStudentStatusType($student,$month,$date="")
    {
        $res=0;
 
        $year=$this->ApplicationObj->Period[ "Year" ];
        if (empty($date))
        {
            $date=$year.sprintf("%02d",$month).sprintf("%02d",1);
        }

        if ($student[ "StudentHash" ][ "MatriculaDate" ]>$date)
        {
           $res=1;
        }
        elseif ($student[ "StudentHash" ][ "Status" ]!=1)
        {
            $sdate=$student[ "StudentHash" ][ "StatusDate1" ];
            if ($sdate<=$date)
            {
                $res=2;
            }
        }

        return $res;
    }

    //*
    //* function GetStudentStatus, Parameter list: $student,$month,$date=""
    //*
    //* Handles Dayly Absences pages.
    //*

    function GetStudentStatus($student,$month,$date="")
    {
        if ($this->GetStudentStatusType($student,$month,$date)==0) { return TRUE; }

        return FALSE;
    }


    //*
    //* function MakeAbsencesMenu, Parameter list: $disc
    //*
    //* Genrerates sub horisontal menu for Contents module.
    //*

    function MakeAbsencesMenu($disc)
    {
        foreach ($this->AbsencesActions as $action)
        {
            $this->ApplicationObj->ClassesObject->Actions[ $action ][ "HrefArgs" ]=preg_replace
            (
               '/#ID/',
               $this->ApplicationObj->Class[ "ID" ],
               $this->ApplicationObj->ClassesObject->Actions[ $action ][ "HrefArgs" ]
            );
        }

        print $this->ApplicationObj->ClassesObject->MakeActionMenu
        (
           $this->AbsencesActions,
           "ptablemenu",
           $disc[ "ID" ]
        );
    }



    //*
    //* function AbsencesDatesMenu, Parameter list: 
    //*
    //* Creates menus with links to individual dates.
    //*

    function AbsencesDatesMenu($args)
    {
        $dates=$this->ApplicationObj->ClassDiscContentsObject->GetContentsDateKeys();

        $hrefs=array();

        $today=$this->TimeStamp2DateSort();

        foreach ($dates as $datekey)
        {
            unset($args[ "Semester" ]);
            $args[ "Date" ]=$datekey;

            $date=preg_replace('/^\d\d\d\d/',"",$datekey);
            if (preg_match('/^(\d\d)(\d\d)/',$date,$matches))
            {
                $date=sprintf("%02d",$matches[2])."/".sprintf("%02d",$matches[1]);
            }
            array_push
            (
               $hrefs,
               $this->Href
               (
                  "?".$this->Hash2Query($args),
                  $date
               )
            );
        }

        $date=$this->GetGET("Date");
        //Add an all, blinding default generation of today
        if (!empty($date))
        {
            unset($args[ "Semester" ]);
            unset($args[ "Date" ]);

            array_push
            (
               $hrefs,
               $this->Href
               (
                  "?".$this->Hash2Query($args),
                  "Todas"
               )
            );            
        }

        return $this->Center("[ ".join(" | ",$hrefs)." ]");
    }




}

?>