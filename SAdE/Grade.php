<?php

class Grade extends Common
{

    //*
    //* Variables of Grade class:
    //*
    var $ModeVars=array();


    //*
    //*
    //* Constructor.
    //*

    function Grade($args=array())
    {
        $this->Hash2Object($args);
        $this->Sort=array("SortOrder","Name");
        $this->IncludeAll=1;
        $this->ItemData=$this->ReadPHPArray("System/Grade/Data.Modes.php",$this->ItemData);

        $this->ModeVars=array_keys($this->ItemData);
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
        $this->ApplicationObj->GradePeriodsObject->UpdateTableStructure();
        $this->ApplicationObj->GradeDiscsObject->UpdateTableStructure();
        $this->ApplicationObj->GradeQuestionariesObject->UpdateTableStructure();
        $this->ApplicationObj->GradeQuestionsObject->UpdateTableStructure();

        $this->ApplicationObj->GradePeriodsObject->InitActions();
        $this->ApplicationObj->GradeDiscsObject->InitActions();
        $this->ApplicationObj->GradeQuestionariesObject->InitActions();
        $this->ApplicationObj->GradeQuestionsObject->InitActions();

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
        if ($module!="Grade")
        {
            return $item;
        }

        $this->DatasNeedUpdate
        (
           array
           (
              "NPeriods" => $this->ApplicationObj->GradePeriodsObject->MySqlNEntries
              (
                 "",
                 array("Grade" => $item[ "ID" ])
              ),
              "NDiscs" => $this->ApplicationObj->GradeDiscsObject->MySqlNEntries
              (
                 "",
                 array
                 (
                    "Grade" => $item[ "ID" ],
                    "Status" => 1,
                 )
              ),
              "NDiscsTotal" => $this->ApplicationObj->GradeDiscsObject->MySqlNEntries
              (
                 "",
                 array("Grade" => $item[ "ID" ])
              ),
              "CHT" => $this->ApplicationObj->GradePeriodsObject->RowSum
              (
                 "",
                 array("Grade" => $item[ "ID" ]),
                 "CHT"
              ),
              "CHS" => $this->ApplicationObj->GradePeriodsObject->RowSum
              (
                 "",
                 array("Grade" => $item[ "ID" ]),
                 "CHS"
              ),
           ),
           $item
        );

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
        if (preg_match('/(Admin)/',$this->Profile))
        {
            if ($this->ApplicationObj->GradePeriodsObject->MySqlNEntries("",array("Grade" => $item[ "ID" ]))==0)
            {
                $res=TRUE;
            }
        }

        return $res;
    }


    //*
    //* function , Parameter list: $data,$item,$edit=0)
    //*
    //* Periods editing handler.
    //*

    function NextGradeSelect($data,$item,$edit=0)
    {
        $this->ReadGrades();
        if ($edit==0)
        {
            foreach ($this->ApplicationObj->Grades as $grade)
            {
                if ($grade[ "ID" ]==$item[ $data ])
                {
                    return $grade[ "Name" ];
                }
            }

            return ;
        }

        $ids=array(0);
        $names=array("");
        foreach ($this->ApplicationObj->Grades as $grade)
        {
            array_push($ids,$grade[ "ID" ]);
            array_push($names,$grade[ "Name" ]);
        }

         $value="";
        if (!empty($item[ $data ])) { $value=$item[ $data ]; }
        return $this->MakeSelectField($data,$ids,$names,$value);
    }

    //*
    //* function HandlePeriods, Parameter list: 
    //*
    //* Periods editing handler.
    //*

    function HandlePeriods()
    {
        $this->ReadItem($this->GetGET("GID"));
        $edit=0;
        if (preg_match('/(Admin|Secretary)/',$this->ApplicationObj->Profile))
        {
            $edit=1;
        }


        $table=array
        (
           $this->EditForm
           (
              "Gerenciar ".$this->ItemName,
              $this->ItemHash,
              $edit,
              FALSE,
              array(),
              FALSE
           )
        );
        
        $this->ApplicationObj->GradePeriodsObject->EditGradePeriods($edit,$this->ItemHash,$table);

        print $this->Html_Table
        (
           "",
           $table,
           array(),
           array(),
           array()
        );
    }


     //*
    //* function ReadGrades, Parameter list:
    //*
    //* Reads Grades
    //* 
    //*

    function ReadGrades()
    {
        if (!empty($this->Grades)) { return; }

        $this->ApplicationObj->Grades=$this->SelectHashesFromTable();
    }

    //*
    //* function GetGrade, Parameter list: $gradeid
    //*
    //* Reads Grades
    //* 
    //*

    function GetGrade($gradeid)
    {
        $grade=NULL;
        foreach ($this->ApplicationObj->Grades as $id => $rgrade)
        {
            if ($rgrade[ "ID" ]==$gradeid)
            {
                $grade=$rgrade;
            }
        }

        if (empty($grade))
        {
            return $this->SelectUniqueHash
            (
               "",
               array("ID" => $gradeid)
            );
        }
        
        return $grade;
    }

    //*
    //* function ReadGrade, Parameter list: $gradeid,$force=FALSE
    //*
    //* Reads Grade.
    //* 
    //*

    function ReadGrade($gradeid,$force=FALSE)
    {
        if (!empty($this->ApplicationObj->Grade) && !$force) { return; }

        $this->ApplicationObj->Grade=$this->GetGrade($gradeid);
    }

    //*
    //* function ReadGradeDiscs, Parameter list:
    //*
    //* Reads Grades
    //* 
    //*

    function ReadGradeDiscs($item)
    {
        $periodids=$this->ApplicationObj->GradePeriodsObject->MySqlUniqueColValues
        (
           "",
           "ID",
           array("Grade" => $item[ "Grade" ]),
           "",
           "SortOrder"
        );


        return $this->ApplicationObj->GradeDiscsObject->SelectHashesFromTable
        (
           "",
           "GradePeriod IN ('".join("','",$periodids)."')"
        );
    }

    //*
    //* function ReadGradePeriodDiscs, Parameter list:
    //*
    //* Reads Grades
    //* 
    //*

    function ReadGradePeriodDiscs($item)
    {
        return $this->ApplicationObj->GradeDiscsObject->SelectHashesFromTable
        (
           "",
           array("GradePeriod" => $item[ "GradePeriod" ])
        );
    }

    //*
    //* function ReadGradePeriods, Parameter list: $gradeid,$datas=array()
    //*
    //* returns list of periods, sorted by SortKey..
    //* 
    //*

    function ReadGradePeriods($gradeid,$datas=array())
    {
        if (!empty($this->ApplicationObj->Grades[ $gradeid-1 ][ "Periods" ])) { return; }

        if (empty($datas)) { $datas=array("ID","Name","Year","SortOrder"); }

        $this->ApplicationObj->Grades[ $gradeid-1 ][ "Periods" ]=
            $this->ApplicationObj->GradePeriodsObject->SelectHashesFromTable
            (
               "",
               array("Grade" => $gradeid),
               $datas,
               FALSE,
               "Year,SortOrder"
            );
    }

    //*
    //* function GetGradePeriodWithID, Parameter list: $gradeid,$periodid
    //*
    //* Finds grade $gradeids period with $id.
    //* 
    //*

    function GetGradePeriodWithID($gradeid,$periodid)
    {
        $pperiod=array();
        foreach ($this->ApplicationObj->Grades[ $gradeid-1 ][ "Periods" ] as $period)
        {
            if ($period[ "ID" ]==$periodid)
            {
                $pperiod=$period;
                break;
            }
        }

        return $pperiod;
    }

    //*
    //* function PreviousClassGradePeriod, Parameter list: $class
    //*
    //* Tries to detect previous class.
    //* 
    //*

    function PreviousClassGradePeriod($class)
    {
        $periods=$this->ReadGradePeriods($class);
        $previousper=NULL;
        $pperiod=array();
        foreach ($periods as $period)
        {
            if ($period[ "ID" ]==$class[ "GradePeriod" ])
            {
                if ($previousper) { $pperiod=$previousper; }
                break;
            }

            $previousper=$period;
        }

        return $pperiod;
    }

 
    //*
    //* Transfers data read into $this->Grade, into empty $table.
    //*

    function Grade2InfoTable(&$table)
    {
        array_push
        (
           $table,
           array
           (
              $this->B("Grade:"),
              $this->MakeShowField("Name",$this->ApplicationObj->Grade)
           ),
           array
           (
              $this->B("Periodo da Grade:"),
              $this->ApplicationObj->GradePeriodsObject->MakeShowField
              (
                 "Name",
                 $this->ApplicationObj->GradePeriod
              )
           )
       );
   }

}

?>