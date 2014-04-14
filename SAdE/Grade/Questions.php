<?php


class GradeQuestions extends Common
{

    //*
    //* Variables of GradeQuestions class:
    //*


    //*
    //*
    //* Constructor.
    //*

    function GradeQuestions($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Questionaire","Number","Name");
        $this->Sort=array("Questionaire","Number","Name");
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
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        if (!preg_match('/^Grade/',$this->GetGET("ModuleName")))
        {
            return $item;
        }

        return $item;
    }

    //*
    //* function ItemsTableRow, Parameter list: $grade,$period,&$table,$even
    //*
    //* Overrides MySql2::ItemsTableRow (called first), adding
    //* Questions table.
    //* 
    //*

    function ItemsTableRow($edit,$item,$nn,$datas,$subdatas=array(),&$tbl=array(),$even)
    {
        parent::ItemsTableRow($edit,$item,$nn,$datas,$subdatas,$tbl,$even);
    }

    //*
    //* function ReadGradeQuestions, Parameter list: $questionary,$class=array()
    //*
    //* Shows selected GradePeriod and table of included questionaries
    //* 
    //*

    function ReadGradeQuestions($questionary,$class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        return $this->SelectHashesFromTable
        (
           "",
           array
           (
              "GradePeriod" => $class[ "GradePeriod" ],
              "Questionaire" => $questionary[ "ID" ],
           ),
           array(),
           FALSE,
           "Number"
        );
    }

    //*
    //* function EditGradeQuestionaries, Parameter list: $grade,$period,&$table
    //*
    //* Shows selected GradePeriod and table of included questionaries
    //* 
    //*

    function EditGradeQuestions($edit,$grade,$period,&$table)
    {
        $this->InitData();

        $this->SqlWhere="GradePeriod='".$period."'";

        $new=array
        (
           "Grade" => $grade,
           "GradePeriod" => $period,
        );

        $this->SqlWhere=$new;

        $this->Sort=array("Questionaire","Number","Name");

        $rtable=array();

        $this->ApplicationObj->GradeQuestionariesObject->ItemHashes=array();
        $this->NoPaging=TRUE;

        if ($edit==1)
        {
            $rtable=$this->ItemsTableDataGroupWithAddRow
            (
               "Questionários",
               "QuestData",
               "UpdateQuestions",
               "Questions",
               $new,
               FALSE,
               "AddRow",
               1
            );
        }
        else
        {
            $this->ReadItems("",array(),TRUE,FALSE,2);

            $rtable=$this->ItemsTableDataGroup("Questionários",0,"QuestData");
        }

        $dtable=$this->Html_Table("",$rtable,array("ALIGN" => 'center'));
        if ($edit==1)
        {
            $dtable=
                $this->StartForm().
                $this->Buttons().
                $dtable.
                $this->MakeHidden("UpdateQuestions",1).
                $this->Buttons().
                $this->EndForm();                
        }

        array_push
        (
            $table,
            array($dtable)
        );
    }
}

?>