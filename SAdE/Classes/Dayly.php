<?php

class ClassesDayly extends ClassesPrints
{
    var $VarTeacherList=array("Teacher","Teacher1","Teacher2");
    var $DaylyActions=array("Dayly");

    var $DiscData=array("Name","CHS","CHT","Teacher","Teacher1","Teacher2");
    var $DiscDataTitles=array("Disciplina","CHS","CHT","Prof.","Prof. Apoio","Prof. Recursos");
    var $ClassData=array("NameKey","NStudents");
    var $ClassDataTitles=array("Turma","No. Alunos");

    //*
    //* function AreWeTeacher, Parameter list: $classordisc,$id=0
    //*
    //* Tests if we are teacher of class or disc.
    //*

    function AreWeTeacher($classdisc,$id=0)
    {
        if (empty($id)) { $id=$this->LoginData[ "ID" ]; }

        $res=FALSE;
        foreach ($this->VarTeacherList as $teacher)
        {
            if ($classdisc[ $teacher ]==$id)
            {
                $res=TRUE;
                continue;
            }
        }

        return $res;
    }

    //*
    //* function TeacherFunction, Parameter list: $classordisc,$id=0
    //*
    //* Tests if we are teacher of class or disc.
    //*

    function TeacherFunction($classdisc,$id=0)
    {
        if (empty($id)) { $id=$this->LoginData[ "ID" ]; }

        $function="";
        foreach ($this->VarTeacherList as $teacher)
        {
            if ($classdisc[ $teacher ]==$id)
            {
                $function=$this->GetDataTitle($teacher);
                continue;
            }
        }

        return $function;
    }


    //*
    //* function TeacherDiscsRow, Parameter list: $n,$class
    //*
    //* Generates class row for teacher
    //*

    function TeacherDiscRow($class,$m,$disc)
    {
        $row=array($this->B($m));
        foreach ($this->ClassData as $data)
        {
            array_push
            (
               $row,
               $this->MakeShowField($data,$class)
            );
        }

        foreach ($this->DiscData as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->MakeShowField($data,$disc)
            );
        }

        array_push($row,$this->TeacherFunction($disc));

        $daylieslinks=array();
        foreach ($this->DaylyActions as $action)
        {
            array_push($daylieslinks,$this->ActionEntry($action,$disc));
        }

        array_push($row,join("",$daylieslinks));

        return $row;
    }

    //*
    //* function TeacherDiscsRow, Parameter list: &$n,$class
    //*
    //* Generates class row for teacher
    //*

    function TeacherDiscsRow(&$n,$class)
    {
        $this->ApplicationObj->Discs=array();
        $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs($class,$this->ApplicationObj->LoginData[ "ID" ]);

        $table=array();
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            if ($this->AreWeTeacher($disc))
            {
                $row=$this->TeacherDiscRow($class,$n++,$disc);
                array_push($table,$row);
            }
        }
 
       return $table;
    }

    //*
    //* function TeacherClassRows, Parameter list: &$n,$class
    //*
    //* Generates class row for teacher
    //*

    function TeacherClassRows(&$n,$class)
    {
        $this->ApplicationObj->UsersObject->ItemHash=array();
        $this->ApplicationObj->UsersObject->ItemHashes=array();
        $rclass=$this->ReadItem($class[ "ID" ]);
        foreach ($this->VarTeacherList as $teacher)
        {
            $rclass[ $teacher."_Name" ]="";
            if ($rclass[ $teacher ]>0)
            {
                $rclass[ $teacher."_Name" ]=$this->ApplicationObj->UsersObject->MySqlItemValue
                (
                   "",
                   "ID",$rclass[ $teacher ],
                   "Name"
                );
            }
        }


        $this->Actions[ "Dayly" ][ "Teacher" ]=1;
        $rows=$this->TeacherDiscsRow($n,$rclass);

        return $rows;
    }

    //*
    //* function HandleDaylyTeacher, Parameter list: 
    //*
    //* Central Classes Handler for Teacher.
    //*

    function HandleDaylyTeacher()
    {
        $this->TitleKeyName="Title";
        $this->TitleKeyTitle="Name";

        $table=array();

        $titles=array_merge
        (
           $this->ClassDataTitles,
           $this->DiscDataTitles
        );

        array_unshift($titles,"No.");
        array_push($titles,"Função","Diário");
        array_push($table,$this->B($titles));

        $n=1;
        foreach ($this->ApplicationObj->Classes as $class)
        {
            $rows=$this->TeacherClassRows($n,$class);
            foreach ($rows as $row) { array_push($table,$row); }
        }

        print
            $this->H(1,$this->Actions[ "DaylyTeacher" ][ "Name" ]).
            $this->H(2,$this->ApplicationObj->School[ "Name" ]).
            $this->H(3,$this->ApplicationObj->Period[ "Name" ]).
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center',"BORDER" => '1'),
               array(),
               array(),
               FALSE,
               FALSE
            ).
            "";
    }

}

?>