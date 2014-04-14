<?php

include_once("../MySql2/Table.php");


class Matriculas extends Table
{
    //*
    //*
    //* Constructor.
    //*

    function Matriculas($args=array())
    {
        $this->Hash2Object($args);
    }

    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
    }

    //*
    //* function UpdateStudentMatricula, Parameter list: &$student
    //*
    //* Chekcs and sets (if necessary), student matricula.
    //*

    function UpdateStudentMatricula(&$student)
    {
        $where=array
        (
           "School"   => $student[ "School" ],
           "SID" => $student[ "ID" ],
        );

        $matricula=$this->SelectUniqueHash
        (
           "",
           $where,
           TRUE,
           array("Matricula")
        );

        if (empty($matricula))
        {
            $matricula=$where;
            $matricula[ "MatriculaDate" ]=$student[ "MatriculaDate" ];
            $matricula[ "Matricula" ]=$this->GenerateMatricula($student);

            foreach (array("CTime","ATime","MTime") as $data) { $matricula[ $data ]=time(); }

            print $this->H(2,"Matricula Gerado: ".$student[ "Name" ].": '".$matricula[ "Matricula" ]."'");

            $this->MySqlInsertItem("",$matricula);
        }

        if ($student[ "Matricula" ]!=$matricula[ "Matricula" ])
        {
            $student[ "Matricula" ]=$matricula[ "Matricula" ];
            $this->ApplicationObj->StudentsObject->MySqlSetItemValue
            (
               "",
               "ID",$student[ "ID" ],
               "Matricula",$matricula[ "Matricula" ]
            );
        }
    }

    //*
    //* function GenerateMatricula, Parameter list: $student
    //*
    //* Chekcs and sets (if necessary), student matricula.
    //*

    function GenerateMatricula($student)
    {
        $year=0;
        $val=0;
        if (preg_match('/^(\d\d\d\d)(\d\d)(\d\d)$/',$student[ "MatriculaDate" ],$matches))
        {
            $year=$matches[1];
           
            $matriculas=$this->MySqlUniqueColValues
            (
               "",
               "Matricula",
               array("Matricula" => "LIKE '".$year."%'")
            );

            $max=0;
            foreach ($matriculas as $matricula)
            {
                $matricula=preg_replace('/^'.$year.'/',"",$matricula);
                $max=$this->Max($max,$matricula);
            }

            $max++;
            $matricula=$year.sprintf("%06d",$max);
        }


        return $matricula;
    }

}

?>