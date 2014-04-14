<?php


class GradeDiscs extends Common
{

    //*
    //* Variables of Grade class:
    //*

    //var $Period2DiscTransferData=array("NAssessments","NRecoveries","AssessmentType","MediaLimit","AbsencesType","AbsencesLimit");

    //*
    //*
    //* Constructor.
    //*

    function GradeDiscs($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("NCHT");
        $this->Sort=array("CHT","Name");

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

        $updatedatas=array();
        if (empty($item[ "NCHT" ]) || $item[ "NCHT" ]!=1000-$item[ "CHT" ])
        {
            $item[ "NCHT" ]=sprintf("%04d",1000-$item[ "CHT" ]);
            array_push($updatedatas,"NCHT");
        }

        if (!empty($item[ "GradePeriod" ]))
        {
            $item[ "Grade" ]=$this->ApplicationObj->GradePeriodsObject->MySqlItemValue
            (
               "",
               "ID",$item[ "GradePeriod" ],
               "Grade",
               TRUE
            );
            array_push($updatedatas,"Grade");
        }

        $item[ "Chave" ]=$this->Text2Sort
        (
           $this->Html2Sort
           (
              preg_replace('/\s+/',"",$item[ "Name" ])
           )
        );

        $item=$this->MakeSureWeHaveRead("",$item,$this->ApplicationObj->GradeObject->ModeVars);
        foreach ($this->ApplicationObj->GradeObject->ModeVars as $data)
        {
            if (empty($item[ $data ]) && !empty($item[ "GradePeriod" ]))
            {
                $item[ $data ]=$this->ApplicationObj->GradePeriodsObject->MySqlItemValue
                (
                   "","ID",
                   $item[ "GradePeriod" ],
                   $data
                );
                array_push($updatedatas,$data);
            }
        }

        $trans=array
        (
           "LinguaEstrangeiraModernaIngles" => "Ingles",
           "LinguaEstrangeiraModernaEspanhol" => "Espanhol",
           "LinguaPortuguesa" => "Portugues",
        );

        if (isset($trans[ $item[ "Chave" ] ]))
        {
            $item[ "Chave" ]=$trans[ $item[ "Chave" ] ];
        }
        array_push($updatedatas,"Chave");

        $keys=array
        (
           'Religiosa' => "Ed. Religiosa",
           'Educa.*o\s+F.*sica' => "Ed. Física",
           'Moderna\s+Espanhol' => "Espanhol",
           'Moderna\s+Ingl' => "Inglês",
           'L.*ngua\s+Portuguesa' => "Português",
        );

        if (isset($item[ "NickName" ]) && empty($item[ "NickName" ]))
        {
            $nickname=$item[ "Name" ];
            foreach ($keys as $regex => $value)
            {
                if (preg_match('/'.$regex.'/',$item[ "Name" ]))
                {
                    $nickname=$value;
                    break;
                }
            }

            $item[ "NickName" ]=$nickname;

            array_push($updatedatas,"NickName");
        }

 
        if (!empty($updatedatas))
        {
            $this->MySqlSetItemValues("",$updatedatas,$item);
        }

        return $item;
    }

    //*
    //* function EditGradePeriodDiscs, Parameter list: $grade,$period,&$table
    //*
    //* Shows selected GradePeriod and table of included disciplines.
    //* Will also call Edit on the discs. 
    //* 
    //*

    function EditGradePeriodDiscs($edit,$grade,$period,&$table)
    {
        $this->SqlWhere="GradePeriod='".$period[ "ID" ]."'";

        $newdisc=array
        (
           "GradePeriod" => $period[ "ID" ],
        );

        foreach ($this->ApplicationObj->GradeObject->ModeVars as $data)
        {
            $newdisc[ $data ]=$period[ $data ];
        }

        $this->Sort=array("CHT","Name");
        $rtable=array();

        if ($edit==1)
        {
            $rtable=$this->ItemsTableDataGroupWithAddRow
            (
               "Disciplinas",
               "DiscData",
               "UpdateDiscs",
               "Disc",
               $newdisc
            );
        }
        else
        {

            $this->ReadItems("",array(),TRUE,FALSE,2);
            $rtable=$this->ItemsTableDataGroup("Disciplinas",0,"DiscData");
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
                $this->MakeHidden("UpdateDiscs",1).
                $this->Buttons().
                $this->EndForm();                
        }
        array_push
        (
            $table,
            array
            (
               $this->H(4,"Disciplinas: ".$period[ "Name" ])
            ),
            array($dtable)
        );
    }
}

?>