<?php

class SAdEClass extends SAdEPeriod
{
     //*
    //* function ReadTeacherDiscs, Parameter list: $teacherid
    //*
    //* Reads discs, that $teacher should see.
    //* 
    //*

    function ReadTeacherDiscs($teacherid)
    {
        $periodname=$this->GetPeriodName($this->Period);

        return $this->ClassDiscsObject->MySqlUniqueColValues
        (
           $this->School[ "ID" ]."_".$periodname."_ClassDiscs",
           "Class",
            "(".
              "Teacher='".$teacherid."' OR ".
              "Teacher1='".$teacherid."' OR ".
              "Teacher2='".$teacherid."'".
            ")".
            "",
            "Class"
        );
    }
     //*
    //* function ReadClassTeacherDiscs, Parameter list: $teacherid,$class=array()
    //*
    //* Reads discs of $class, that $teacher should see.
    //* 
    //*

    function ReadClassTeacherDiscs($teacherid,$class=array())
    {
        if (empty($class)) { $class=$this->Class; }

        $periodname=$this->GetPeriodName($this->Period);
        return $this->ClassDiscsObject->SelectHashesFromTable
        (
           $this->School[ "ID" ]."_".$periodname."_ClassDiscs",
           "Class='".$class[ "ID" ]."' AND ".
            "(".
              "Teacher='".$teacherid."' OR ".
              "Teacher1='".$teacherid."' OR ".
              "Teacher2='".$teacherid."'".
            ")".
            "",
            array()
        );
    }

     //*
    //* function ReadClassDiscs, Parameter list: $teacherid,$class=array()
    //*
    //* Reads discs of $class, that $teacher should see.
    //* 
    //*

    function ReadClassDiscs($class=array())
    {
        if (empty($class)) { $class=$this->Class; }

        $periodname=$this->GetPeriodName($this->Period);
        return $this->ClassDiscsObject->SelectHashesFromTable
        (
           $this->School[ "ID" ]."_".$periodname."_ClassDiscs",
           "Class='".$class[ "ID" ]."'",
            array()
        );
    }
  
    //*
    //* function GetNAssessments, Parameter list:$dics=array(),$class=array()
    //*
    //* Detects NAssessments.
    //* 
    //*

    function GetNAssessments($dics=array(),$class=array())
    {
        if (empty($dics))  { $disc=$this->Disc; }
        if (empty($class)) { $class=$this->Class; }

        $nassessments=0;
        if (
              $nassessments==0
              &&
              !empty($dics[ "NAssessments" ])
           )
        { 
            $nassessments=$dics[ "NAssessments" ];
        }
        if (
              $nassessments==0
              &&
              !empty($class[ "NAssessments" ])
           )
        { 
            $nassessments=$class[ "NAssessments" ];
        }

        if ($nassessments==0) { $nassessments=4; }

        return $nassessments;
    }

    //*
    //* function ReadPeriods, Parameter list:
    //*
    //* Reads all periods according to $where.
    //* 
    //*

    function ReadClasses()
    {
        return $this->ClassesObject->ReadPeriodClasses
        (
           $this->School,
           $this->Period
        );
    }

    //*
    //* function ReadClass, Parameter list: $where=array()
    //*
    //* Reads all periods according to $where.
    //* 
    //*

    function ReadClass()
    {
        return $this->ClassesObject->ReadClass();
    }


}

?>
