<?php

class GradePeriods extends Common
{

    //*
    //* Variables of Grade class:
    //*

    var $Grade2PeriodTransferData=array
    (
       "AssessmentType","AbsencesType","MediaLimit","AbsencesLimit","AssessmentsWeights",
       "NRecoveries","NAssessments"
    );

    //*
    //*
    //* Constructor.
    //*

    function GradePeriods($args=array())
    {
        $this->Hash2Object($args);
        $this->Sort=array("SortOrder","Name");
        $this->AlwaysReadData=array
        (
           "Grade","NAssessments","AssessmentType","MediaLimit","AbsencesType","AbsencesLimit",
           "AssessmentsWeights","NRecoveries",
        );

        $this->SumVars=array("CHS","CHT");

        $this->ItemData=$this->ReadPHPArray("System/Grade/Data.Modes.php",$this->ItemData);
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
        $this->Actions[ "Delete" ][ "AccessMethod" ]="MayDelete";
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if (!preg_match('/^Grade/',$module))
        {
            return $item;
        }

        $where=array
        (
           "GradePeriod" => $item[ "ID" ],
           "Status" => 1,
        );

        $this->DatasNeedUpdate
        (
           array
           (
              "CHT" => $this->ApplicationObj->GradeDiscsObject->RowSum("",$where,"CHT"),
              "CHS" => $this->ApplicationObj->GradeDiscsObject->RowSum("",$where,"CHS"),
              "NDiscs" => $this->ApplicationObj->GradeDiscsObject->MySqlNEntries("",$where),
              "NDiscsTotal" => $this->ApplicationObj->GradeDiscsObject->MySqlNEntries("",$where),
           ),
           $item
        );

        $item=$this->MakeSureWeHaveRead("",$item,$this->ApplicationObj->GradeObject->ModeVars);

        $updatesdatas=array();
        foreach ($this->ApplicationObj->GradeObject->ModeVars as $data)
        {
            if (empty($item[ $data ]))
            {
                $item[ $data ]=$gradevalue=$this->ApplicationObj->GradeObject->MySqlItemValue
                (
                   "","ID",
                   $item[ "Grade" ],
                   $data
                );

                array_push($updatesdatas,$data);
            }
        }

        if (count($updatesdatas)>0)
        {
            $this->MySqlSetItemValues("",$updatesdatas,$item);
        }

        return $item;
    }

    //*
    //* function ReadDisciplines, Parameter list: $item=array()
    //*
    //* Reads disciplines, pertaining to GradePeriod $item - or $this->ItemHash.
    //*

    function ReadDisciplines($id,$datas=array())
    {
        return $this->ApplicationObj->GradeDiscsObject->SelectHashesFromTable
        (
           "",
           array("GradePeriod" => $id),
           $datas,
           FALSE,
           "NCHT"
        );
    }

    //*
    //* function NextGradePeriod, Parameter list: $gradeid,$gradeperiodid
    //*
    //* Finds procedding Grade Period
    //*

    function NextGradePeriod($gradeid,$gradeperiodid)
    {
        $next=$this->ApplicationObj->GradePeriod[ "SortOrder" ]+1;
        $nextpers=$this->SelectHashesFromTable
        (
            "",
            array
            (
               "Grade" => $gradeid,
               "SortOrder" => $this->ApplicationObj->GradePeriod[ "SortOrder" ]+1,
            )
        );

        $nextper=array();
        $nextgrade=$this->ApplicationObj->GradeObject->GetGrade($gradeid);
        if (count($nextpers)==0)
        {
            if (!empty($this->ApplicationObj->Grade[ "NextGrade" ]))
            {
                $nextgrade=$this->ApplicationObj->Grade[ "NextGrade" ];
                $nextpers=$this->SelectHashesFromTable
                (
                   "",
                   array
                   (
                      "Grade" => $nextgrade,
                      //"SortOrder" => 1,
                   )
                );
                $min=10000;
                foreach ($nextpers as $per)
                {
                    if ($per[ "SortOrder" ]<$min)
                    {
                        $nextper=$per;
                        $min=$per[ "SortOrder" ];
                    }
                }
            }
        }
        else
        {
            $nextper=$nextpers[0];
        }
        
        return $nextper;
    }

    //*
    //* function ClassNextGradePeriod, Parameter list: $class
    //*
    //* Finds procedding Grade Period
    //*

    function ClassNextGradePeriod($class)
    {
        return $this->NextGradePeriod($class[ "Grade" ],$class[ "GradePeriod" ]);
    }

    //*
    //* function MayDelete, Parameter list: $item
    //*
    //* Decides whether Grade is deletable.
    //*

    function MayDelete($item)
    {
        if (empty($item)) { return FALSE; }

        $res=FALSE;
        if (preg_match('/(Admin)/',$this->Profile))
        {
            $ndiscs=$this->ApplicationObj->GradeDiscsObject->MySqlNEntries
            (
               "",
               array("GradePeriod" => $item[ "ID" ])
            );

            if ($ndiscs==0)
            {
                $res=TRUE;
            }
        }

        return $res;
    }
    //*
    //* function RenumberGradePeriod, Parameter list:
    //*
    //* Handles grade periods editing> list with add row.
    //*

    function RenumberGradePeriod($newitem)
    {
        $periods=array();
        foreach ($this->ItemHashes as $id => $item)
        {
            $key=$item[ "SortOrder" ];
            while (isset($periods[ $key ]))
            {
                $key.="1";
            }
            $periods[ $key ]=$id;
        }

        $sortperiods=array_keys($periods);
        sort($sortperiods,SORT_NUMERIC);

        for ($n=0;$n<count($sortperiods);$n++)
        {
            $id=$periods[ $sortperiods[ $n ] ];
            $this->ItemHashes[ $id ][ "SortOrder" ]=$n+1;
        }

        $newitem[ "SortOrder" ]=count($sortperiods)+1;
        return $newitem;
    }

    //*
    //* function EditGradePeriods, Parameter list: $edit,$grade,&$table
    //*
    //* Handles grade periods editing> list with add row.
    //*

    function EditGradePeriods($edit,$grade,&$table)
    {
        $this->InitData();

        $this->Actions[ "Delete" ][ "AccessMethod" ]="MayDelete";
        $this->SqlWhere="Grade='".$grade[ "ID" ]."'";

        $newperiod=array
        (
           "Grade" => $grade[ "ID" ],
        );

        foreach ($this->ApplicationObj->GradeObject->ModeVars as $data)
        {
            $newperiod[ $data ]=$grade[ $data ];
        }

        $rtable=array();

        if ($edit==1)
        {
            $rtable=$this->ItemsTableDataGroupWithAddRow
            (
               "Períodos",
               "PeriodData",
               "UpdatePeriods",
               "Period",
               $newperiod,
               "RenumberGradePeriod",
               "AddRow",
               1
            );
        }
        else
        {
            $this->ReadItems("",array(),TRUE,FALSE,2);
            $rtable=$this->ItemsTableDataGroup("Períodos",0,"PeriodData");
        }


        if ($this->GetGET("PID"))
        {
            $this->ReadItem($this->GetGET("PID"));
        }

        $ptable=$this->Html_Table
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
            $ptable=
                $this->StartForm().
                $this->Buttons().
                $ptable.
                $this->MakeHidden("UpdatePeriods",1).
                $this->Buttons().
                $this->EndForm();                
        }

        array_push
        (
           $table,
           array
           (
              $this->H(3,"Períodos da Grade: ".$grade[ "Name" ])
           ),
           array($ptable)
        );

        if (!empty($this->ItemHash))
        {
            array_push
            (
               $table,
               array
               (
                  $this->H(2,"Período Selecionado").
                  $this->Html_Table
                  (
                     "",
                     $this->ItemTable(),
                     array("ALIGN" => 'center'),
                     array(),
                     array(),
                     TRUE
                   )
               )
            );

        }

        if (!empty($this->ItemHash))
        {
            $gradeperiod=$this->ItemHash;
            $this->ApplicationObj->GradeDiscsObject->EditGradePeriodDiscs($edit,$grade,$this->ItemHash,$table);

            if ($gradeperiod[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
            {
                $this->ApplicationObj->GradeQuestionariesObject->EditGradeQuestionaries($edit,$grade,$this->ItemHash,$table);
            }
        }

    }

    //*
    //* function HandleCopyQuestionaries, Parameter list:
    //*
    //* Creates form for copying Questionaries. 
    //* 
    //*

    function HandleCopyQuestionaries()
    {
        $srcgradeperiodid=intval($this->GetGET("PID"));
        if ($srcgradeperiodid>0)
        {
            $this->ReadItem($srcgradeperiodid);

            if ($this->ItemHash[ "AssessmentType" ]!=2)
            {
                die("Period has not Qualitaqtive Assessment");
            }

            $this->ApplicationObj->GradeQuestionariesObject->CopyQuestionariesForm($this->ItemHash);
        }
    }

}

?>