<?php

include_once("Class/Absences/Calc.php");


class ClassAbsencesTitleRows extends ClassAbsencesCalc
{
    //*
    //* function AbsencesTitles, Parameter list: $disc
    //*
    //* Generates marks titles.
    //*

    function AbsencesTitles($disc)
    {
        $row=array();
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
            {
                array_push($row,$this->Sub("F",$n));
            }
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowAbsencesTotals)
        {
            array_push($row,$this->ApplicationObj->Sigma."F");
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowAbsencesPercent)
        {
            array_push($row,"");
        }

        return $row;
    }

    //*
    //* function AbsencesTotalsTitles, Parameter list: $disc
    //*
    //* Generates absences totals titles.
    //*

    function AbsencesTotalsTitles($disc)
    {
        return array($this->ApplicationObj->Percent,"Res");
    }

    //*
    //* function FinalTitles, Parameter list: $disc
    //*
    //* Generates final titles.
    //*

    function FinalTitles($disc)
    {
        return array($this->ApplicationObj->Percent,$this->Sub("R","F"));
    }

    //*
    //* function NAbsencesTitles, Parameter list: $nassessments
    //*
    //* 
    //*

    function NAbsencesTitles($disc)
    {
        $row=array();
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++) { array_push($row,$this->B($this->Sub("A",$n))); }

        if ($this->ApplicationObj->ClassDiscsObject->ShowNLessonsTotals)
        {
            array_push($row,$this->B($this->ApplicationObj->Sigma));
        }

        return $row;
    }


    //*
    //* function ClassAbsencesHtmlTableNLessonsTitles, Parameter list: $sdatas=array(),$sactions=array(),$disc=array(
    //*
    //* Makes Marks HTML table for discid $discid.
    //*

    function ClassAbsencesHtmlTableNLessonsTitles($sdatas=array(),$sactions=array(),$disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $titles=array();
        foreach ($sdatas as $data) { array_push($titles,""); }
        foreach ($sactions as $data) { array_push($titles,""); }
        array_push($titles,"","");

        $titles=array_merge($titles,$this->NLessonsInputs($this->ApplicationObj->Class,$disc));

        array_push($titles,"","");

 
        return $this->B($titles);
    }


    //*
    //* function ClassAbsencesHtmlTableTitles, Parameter list: $actionobj,$sdatas=array(),$sactions=array(),$disc=array()
    //*
    //* Makes Absences HTML table for discid $discid.
    //*

    function ClassAbsencesHtmlTableTitles($actionobj,$sdatas=array(),$sactions=array(),$disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $titles=$actionobj->GetActionNames($sactions);
        $titles=array_merge($titles,$this->ApplicationObj->StudentsObject->GetDataTitles($sdatas));
        array_push($titles,"Disc. Status");
        array_unshift($titles,"No.");

        $totals=array_merge($titles,$this->AbsencesTitles($disc));
        $totals=array_merge($titles,$this->AbsencesTotalsTitles($disc));


        $totals=array_merge($titles,$this->FinalTitles($disc));

        return $this->B($titles);
    }

    //*
    //* function DiscStudentTitleRows, Parameter list: $type,$sdatas=array(),$sactions=array()
    //*
    //* Creates the Disc Student title Rows (2 rows).
    //*

    function DiscStudentTitleRows($obj,$fdatas=array(),$factions=array(),$disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        return array
        (
           $this->ApplicationObj->ClassAbsencesObject->ClassAbsencesHtmlTableNLessonsTitles
           (
              $fdatas,
              $factions,
              $disc
           ),
           $this->ApplicationObj->ClassAbsencesObject->ClassAbsencesHtmlTableTitles
           (
              $obj,
              $fdatas,
              $factions,
              $disc
           ),
        );
    }


}

?>