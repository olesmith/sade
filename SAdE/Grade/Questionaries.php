<?php


class GradeQuestionaries extends Common
{

    //*
    //* Variables of GradeQuestionaries class:
    //*


    //*
    //*
    //* Constructor.
    //*

    function GradeQuestionaries($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Number","Name");
        $this->Sort=array("Number","Name");
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

    function ItemsTableRow($edit,$item,$nn,$datas,$subdatas=array(),&$table=array(),$even)
    {
        parent::ItemsTableRow($edit,$item,$nn,$datas,$subdatas,$table,$even);
    }

    //*
    //* function ReadGradeQuestionaries, Parameter list: $class=array()
    //*
    //* Shows selected GradePeriod and table of included questionaries
    //* 
    //*

    function ReadGradeQuestionaries($class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $questionaries=$this->SelectHashesFromTable
        (
           "",
           array("GradePeriod" => $class[ "GradePeriod" ]),
           array(),
           FALSE,
           "Number"
        );

        foreach (array_keys($questionaries) as $id)
        {
            $questionaries[ $id ][ "Questions" ]=
                $this->ApplicationObj->GradeQuestionsObject->ReadGradeQuestions
                (
                   $questionaries[ $id ],
                   $class
                );
        }

        return $questionaries;
    }

    //*
    //* function EditGradeQuestionaries, Parameter list: $grade,$period,&$table
    //*
    //* Shows selected GradePeriod and table of included questionaries
    //* 
    //*

    function EditGradeQuestionaries($edit,$grade,$period,&$table)
    {
        $this->InitData();

        $this->SqlWhere="GradePeriod='".$period[ "ID" ]."'";

        $newdisc=array
        (
           "Grade" => $grade[ "ID" ],
           "GradePeriod" => $period[ "ID" ],
        );

        $this->Sort=array("Number","Name");
        $rtable=array();

        if ($edit==1)
        {
            $rtable=$this->ItemsTableDataGroupWithAddRow
            (
               "Questionários",
               "QuestData",
               "UpdateQuest",
               "Quest",
               $newdisc,
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

        $dtable=$this->Html_Table
        (
           "",
           $rtable,
           array("ALIGN" => 'center'),
           array(),
           array(),
           TRUE
        );
        if ($edit==1)
        {
            $dtable=
                $this->StartForm().
                $this->Buttons().
                $dtable.
                $this->MakeHidden("UpdateQuest",1).
                $this->Buttons().
                $this->EndForm();                
        }
        array_push
        (
            $table,
            array
            (
               $this->H(4,"Questionários: ".$period[ "Name" ])
            ),
            array($dtable)
        ); 

        $this->ApplicationObj->GradeQuestionsObject->EditGradeQuestions
        (
           $edit,
           $grade[ "ID" ],
           $period[ "ID" ],
           $table
        ); 

    }
}

?>