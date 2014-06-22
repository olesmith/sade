<?php

include_once("Periods/Dates.php");
include_once("Periods/Months.php");
include_once("Periods/Year.php");
include_once("Periods/Calendar.php");
include_once("Periods/Groups.php");
include_once("Periods/Trimester.php");
include_once("Periods/Read.php");
include_once("Periods/Period.php");
include_once("Periods/Discs.php");


class Periods extends PeriodsDiscs
{
    //*
    //* Variables of Periods class
    //*

    var $DateCellDefs=array
    (
           0 => array
           (
              "Text" => "Dia de Aula",
              "Class" => "Lessonday",
           ),
           6 => array
           (
              "Text" => "Sábado Letivo",
              "Class" => "Saturday",
           ),
           4 => array
           (
              "Text" => "Feriado",
              "Class" => "Holiday",
           ),
           5 => array
           (
              "Text" => "Recesso",
              "Class" => "Recessday",
           ),
           7 => array
           (
              "Text" => "Domingo",
              "Class" => "Sunday",
           ),
    );

    //*
    //*
    //* Constructor.
    //*

    function Periods($args=array())
    {
        $this->IDGETVar="Period";
        $this->Hash2Object($args);
        $this->Sort=array("Year","Period");
        $this->AlwaysReadData=array("Type","Year","Semester","Period","NPeriods","NextPeriod","Daylies","Title");
        $this->NItemsPerPage=25;
        $this->Reverse=TRUE;
    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        $itemdata=array
        (
           "Sql" => "INT",
           "SqlClass" => "Dates",
           "FieldMethod" => "MakeDayliesDateSelect",

           "Public"      => 1,
           "Person"      => 1,
           "Admin"       => 2,

           "Clerk" => 1,
           "Teacher"     => 1,
           "Secretary" => 2,
        );

        $rkey="DayliesStart";

        $this->ItemData[ $rkey ]=$itemdata;
        $this->ItemData[ $rkey ][ "Name" ]="Diários, Inicio";
        $this->ItemData[ $rkey ][ "Title" ]="Dia Inicial do Periodo dos Diários";

        $rkey="DayliesEnd";

        $this->ItemData[ $rkey ]=$itemdata;
        $this->ItemData[ $rkey ][ "Name" ]="Diários, Fim";
        $this->ItemData[ $rkey ][ "Title" ]="Último Dia do Periodo dos Diários";


        $key="Daylies";
        for ($n=1;$n<=$this->ApplicationObj->MaxNAssessments;$n++)
        {
            $rkey=$key.$n;

            $this->ItemData[ $rkey ]=$itemdata;
            $this->ItemData[ $rkey ][ "ShortName" ]="Fim ".$n."º";
            $this->ItemData[ $rkey ][ "Name" ]="Diários, Fim ".$n."º";
            $this->ItemData[ $rkey ][ "Title" ]="Diários, Fim do Periodo ".$n."º";
        }

        $key="DayliesLimit";
        for ($n=1;$n<=$this->ApplicationObj->MaxNAssessments;$n++)
        {
            $rkey=$key.$n;

            $this->ItemData[ $rkey ]=$itemdata;
            $this->ItemData[ $rkey ][ "ShortName" ]="Prazo ".$n."º";
            $this->ItemData[ $rkey ][ "Name" ]="Diários, Prazo ".$n."º";
            $this->ItemData[ $rkey ][ "Title" ]="Prazo para Fechar Diários do Periodo ".$n."º";
        }
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->DatesObject->UpdateTableStructure();

        $key="AssessmentDate";
        $key1="Daylies";
        $key2="DayliesLimit";


        $group="DayliesDates";
        $groupdef=array
        (
            "Name" => "Periodos, Diários Eletrônicos",
            "GenTableMethod" =>"GenerateDayliesDatesSingle",
            "Admin" => 1,
            "Person" => 1,
            "Public" => 1,
            "Data" => array(),
        );

        for ($n=1;$n<=$this->ApplicationObj->MaxNAssessments;$n++)
        {
            array_push($groupdef[ "Data" ],$key1.$n);
        }

        //Single group
        $this->AddItemDataGroup($group,$groupdef,FALSE);

        $groupdef[ "GenTableMethod" ]="GenerateDayliesDatesPlural";
        array_unshift
        (
           $groupdef[ "Data" ],
           "No","Edit","Classes",
           "Name",
           "Type",
           "Year",
           "Semester",
           "StartDate",
           "EndDate",
           "Daylies"
        );

        //Plural group
        $this->AddItemDataGroup($group,$groupdef,TRUE);


        $group="DayliesLimits";
        $groupdef=array
        (
            "Name" => "Limites, Diários Eletrônicos",
            "GenTableMethod" =>"GenerateDayliesLimitsSingle",
            "Admin" => 1,
            "Person" => 1,
            "Public" => 1,
            "Data" => array(),
        );

        for ($n=1;$n<=$this->ApplicationObj->MaxNAssessments;$n++)
        {
            array_push($groupdef[ "Data" ],$key2.$n);
        }

        //Single group
        $this->AddItemDataGroup($group,$groupdef,FALSE);

        $groupdef[ "GenTableMethod" ]="GenerateDayliesLimitsPlural";
        array_unshift
        (
           $groupdef[ "Data" ],
           "No","Edit","Classes",
           "Name",
           "Type",
           "Year",
           "Semester",
           "StartDate",
           "EndDate",
           "Daylies"
        );

        //Plural group
        $this->AddItemDataGroup($group,$groupdef,TRUE);
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if ($module!="Periods")
        {
            return $item;
        }

        $this->SetStartDate($item);
        $this->SetEndDate($item);

        $this->SetNextPeriod($item);
        $this->SetPeriodName($item);

        if ($item[ "Daylies" ]==2)
        {
            $this->SetDayliesDates($item);
        }
        
        $nperiods=$this->ItemData[ "Type" ][ "NSemesters" ][ $item[ "Type" ]-1 ];
        if ($item[ "NPeriods" ]!=$nperiods)
        {
            $item[ "NPeriods" ]=$nperiods;
            $this->MySqlSetItemValue
            (
               "",
               "ID",
               $item[ "ID" ],
               "NPeriods" ,
               $nperiods
            );
        }

        return $item;
    }



    //*
    //* function HandleEdit, Parameter list: $echo=TRUE,$title=""
    //*
    //* Overrides Table HandleEdit:
    //*
    //* 1: Checks if all dates between start and end date are in Dates table -
    //*    adding necessary dates.
    //*

    function HandleEdit($echo=TRUE,$formurl=NULL,$title="")
    {
        parent::HandleEdit($echo,$formurl,$title);
    }

    //*
    //* function HandleShow, Parameter list: $title=""
    //*
    //* Overrides Table HandleShow:
    //*
    //* 1: Checks if all dates between start and end date are in Dates table -
    //*    adding necessary dates.
    //*

    function HandleShow($title="")
    {
        parent::HandleShow($title);

        $this->HtmlCalendar();
    }


    //*
    //* function StudentMatriculatedInPeriod, Parameter list: $student,$period
    //*
    //* Returns TRUE if $student matriculated in $period, FALSE elsewise.
    //*

    function StudentMatriculatedInPeriod($student,$period)
    {
        $enddate=$this->ApplicationObj->DatesObject->DateID2SortKey($period[ "EndDate" ]);
        if ($student[ "StudentHash" ][ "MatriculaDate" ]<$enddate)
        {
            return TRUE;
        }

        return FALSE;
    }

    
}

?>