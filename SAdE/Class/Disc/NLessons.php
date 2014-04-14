<?php

include_once("NLessons/Import.php");
include_once("NLessons/Field.php");
include_once("NLessons/Row.php");
include_once("NLessons/Update.php");
include_once("NLessons/Read.php");
include_once("../MySql2/Unique.php");


class ClassDiscNLessons extends ClassDiscNLessonsRead
{
    var $NLessonsDataGroup="Common";
    var $DiscsActions=array
    (
       "Edit","DiscMarks","DiscAbsences","DiscTotals",
    );
    var $DiscsData=array
    (
       "Name","NickName","CHS","CHT","Teacher"
    );


    //*
    //* Variables of ClassDiscNLessons class:
    //*

    //*
    //*
    //* Constructor.
    //*

    function ClassDiscNLessons($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->Sort=array("Assessment");

        $this->UniqueKeys=array("Class","ClassDisc","Assessment");
    }


    //*
    //* function SqlTableName, Parameter list: $table=""
    //*
    //* Returns fully qualified and filtered name of table.
    //* Uses default value if $table is not given.
    //* Overrides MySql2::SqlTableName.
    //*

    function SqlTableName($table="")
    {
        return $this->ApplicationObj->SchoolPeriodSqlTableName($this->ModuleName);
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
        $this->ApplicationObj->ReadSchool();
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if (!preg_match('/^Class/',$module))
        {
            return $item;
        }

        return $item;
    }


    //*
    //* function MayDelete, Parameter list: $item
    //*
    //* Decides whether Grade is deletable.
    //*

    function MayDelete($item)
    {
        $res=FALSE;

        return $res;
    }


    //*
    //* function GetNLessons, Parameter list: $disc,$n
    //*
    //* Returns $disc number of lessons for period $n.
    //*

    function GetNLessons($class,$disc,$n)
    {
        if (isset($disc[ "NLessons" ][ $n-1 ][ "NLessons" ]))
        {
            return $disc[ "NLessons" ][ $n-1 ][ "NLessons" ];
        }
        else
        {
            return 0;
        }
    }

    //*
    //* function SumNLessons, Parameter list: $disc=array()
    //*
    //* Sums NLessons.
    //*

    function SumNLessons($class,$disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $total=0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            $total+=$this->GetNLessons($class,$disc,$n);
        }
 
        if ($total<$disc[ "CHT" ])      { $total=$this->TextColor("red",$total."-"); }
        elseif ($total>$disc[ "CHT" ])  { $total=$this->TextColor("green",$total."+"); }
        else                            { $total=$this->TextColor("green",$total); }

        return array($total,"100.0");
    }

    //*
    //* function NLesssonsDefaultItem, Parameter list: $classid,$discid,$studentid,$teacherid,$assessment
    //*
    //* Returns default item based on $class and $discv.
    //*

    function NLesssonsDefaultItem($class,$disc,$assessment)
    {
        $item=$this->NLesssonsFieldSqlWhere($class,$disc,$assessment);
        $item[ "Name" ]="No. ".$assessment;

        return $item;
    }

    //*
    //* function Disc2NAssessments, Parameter list: $class,$disc
    //*
    //* Returns NAssessments from $disc - or $this->ApplicationObj->Class.
    //*

    function Disc2NAssessments($class,$disc)
    {
        $nassessments=$class[ "NAssessments" ];
        if (isset($disc[ "NAssessments" ]))
        {
            $nassessments=$disc[ "NAssessments" ];
        }

        return $nassessments;
    }


    //*
    //* function ClassDiscNLessonsTable, Parameter list: $item
    //*
    //* Generates class disc lessons table.
    //*

    function ClassDiscNLessonsTable($disc)
    {
        return $this->ItemsTableDataGroup
        (
           "Disciplinas",
           1,
           $this->NLessonsDataGroup,
           $disc[ "NLessons" ]
        );
    }    

     //*
    //* function ShowClassDiscNLessons, Parameter list: $disc,$classid=0,&$n,&$table
    //*
    //* Displays List of class disciplines.
    //*

    function ShowClassDiscNLessons($disc,$classid=0,$n,&$table,$plural=TRUE)
    {
        $row=array($this->B($n));
        foreach ($this->NLessonsDiscsData as $data)
        {
            array_push
            (
               $row,
               $this->MakeField(0,$disc,$data,TRUE)
            );
        }

        array_push($table,$row);

        array_push
        (
           $table,
           array
           (
              "",
              $this->HtmlTable
              (
                 "",
                 $this->ClassDiscNLessonsTable($disc)
              )
           )
        );
    }

     //*
    //* function ClassDiscNLessonsRow, Parameter list: $disc,$class=array(),&$n
    //*
    //* Displays row of class disciplines.
    //*

    function ClassDiscNLessonsRow($edit,$disc,$class=array(),$n,$plural=TRUE)
    {
        if ($edit==1 && $this->GetPOST("Update")==1)
        {
            $this->UpdateNLessonsFields($class,$disc);
        }

        $row=array($this->B($n));

        foreach ($this->DiscsActions as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->ActionEntry($data,$disc)
            );
        }

        foreach ($this->DiscsData as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->MakeField(0,$disc,$data,TRUE)
            );
        }

        $nlessons=0;
        for ($nn=0;$nn<$disc[ "NAssessments" ];$nn++)
        {
            array_push
            (
               $row,
               $this->NLessonsField($edit,$class,$disc,$nn+1)
            );

            $nlessons+=$disc[ "NLessons" ][ $nn ][ "NLessons" ];
        }
        array_push($row,$nlessons);
      

        return $row;

    }


    //*
    //* function ShowClassDiscsNLessons, Parameter list: $edit=0,$class=array()
    //*
    //* Displays List of class disciplines.
    //*

    function ShowClassDiscsNLessons($edit=0,$class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $this->ApplicationObj->ClassDiscsObject->ReadClassDisciplines($class);

        $this->InitProfile("ClassDiscs");
        $this->ApplicationObj->ClassDiscsObject->InitActions();
        $this->PostInit();

        $titles=array_merge($this->DiscsActions,$this->DiscsData);
        $titles=$this->ApplicationObj->ClassDiscsObject->GetDataTitles($titles);
        array_unshift($titles,"");

        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            array_push($titles,"Aulas Dadas $n");
        }
        array_push($titles,"&Sigma;");

        $table=array($this->B($titles));

        $n=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            array_push($table,$this->ClassDiscNLessonsRow($edit,$class,$disc,$n));
            $n++;
        }


        print 
            $this->H(2,"Aulas Dados da Turma  ".$this->ApplicationObj->Period[ "Name" ]).
            $this->StartForm().
            $this->Buttons().
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center'),
               array(),
               array(),
               TRUE
            ).
            $this->MakeHidden("Update",1).
            $this->Buttons().
            $this->EndForm().
            "";
    }


}

?>