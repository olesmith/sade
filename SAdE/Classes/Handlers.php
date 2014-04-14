<?php

class ClassesHandlers extends ClassesSelects
{
    //*
    //* function HandleShow, Parameter list: $title=""
    //*
    //* Overrides MySql2 HandleShow
    //*

    function HandleShow($title="")
    {
        $this->ItemHash=$this->ApplicationObj->Class;
        parent::HandleShow($title);

        $this->ApplicationObj->ClassDiscsObject->ItemDataGroups[ "DiscList" ][ $this->ApplicationObj->Profile ]=1;
        $this->ApplicationObj->ClassDiscsObject->Singular=FALSE;;
        $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs();
        $this->ApplicationObj->ClassDiscsObject->ShowClassDiscs();
    }

    //*
    //* function HandleEdit, Parameter list: $echo=TRUE,$formurl=NULL,$title=""
    //*
    //* Overrides MySql2 HandleEdit
    //*

    function HandleEdit($echo=TRUE,$formurl=NULL,$title="")
    {
        $this->ItemHash=$this->ApplicationObj->Class;
        parent::HandleEdit($echo,$formurl,$title);
    }


    //*
    //* function HandleDelete, Parameter list: $title="",$actionname="Delete",$formurl="?Action=Delete",$idvar="ID"
    //*
    //* Overrides MySql2 HandleDelete
    //*

    function HandleDelete($title="",$actionname="Delete",$formurl="?Action=Delete",$idvar="ID")
    {
        $this->ItemHash=$this->ApplicationObj->Class;
        print parent::HandleDelete($title,$actionname,$formurl,$idvar);
    }

    //*
    //* function HandleAdd, Parameter list: $echo=TRUE
    //*
    //* Overrides MySql2 HandleAdd
    //*

    function HandleAdd($echo=TRUE)
    {
        $this->ItemData[ "School" ][ $this->ApplicationObj->Profile ]=1;

        $this->ItemData[ "Grade" ][ $this->ApplicationObj->Profile ]=2;

        $this->AddDatas=array("School","Period");

        $period=$this->GetPOST("Period");
        if (empty($period)) { $period=$this->ApplicationObj->Period[ "ID" ]; }

        $this->ItemHash=array
        (
           "School" => $this->ApplicationObj->School[ "ID" ],
           "Period" => $period,
           "Grade" => $this->GetPOST("Grade"),
           "GradePeriod" => $this->GetPOST("GradePeriod"),
           "Name" => $this->GetPOST("Name"),
        );

        $add=FALSE;

        $data="Period";
        if (!empty($this->ItemHash[ "Period" ]))
        {
            array_push($this->AddDatas,"Grade");
            $data="Grade";
        }

        if (!empty($this->ItemHash[ "Grade" ]))
        {
            array_push($this->AddDatas,"GradePeriod");
            $data="GradePeriod";
        }

        if (!empty($this->ItemHash[ "GradePeriod" ]))
        {
            array_push($this->AddDatas,"Shift","Teacher","Name");
            $data="Name";
        }
        if (!empty($this->ItemHash[ "Name" ])) { $add=TRUE; }

        if ($add)
        {
            $year="";
            $semester="";
            foreach ($this->ApplicationObj->Periods as $rperiod)
            {
                if ($period==$rperiod[ "ID" ])
                {
                    $year=$rperiod[ "Year" ];
                    $semester=$rperiod[ "Semester" ];
                    break;
                }
            }

            $this->ItemHash[ "Year" ]=$year;
            $this->ItemHash[ "Semester" ]=$semester;


            $rdatas=$this->GradePeriodTransferData;
            array_unshift($rdatas,"ID","Name");
            $this->ApplicationObj->GradeObject->ReadGrades();
            $this->ApplicationObj->GradeObject->ReadGradePeriods($this->ItemHash[ "Grade" ],$rdatas);


            $grade=$this->ApplicationObj->Grades[ $this->ItemHash[ "Grade" ]-1 ];

            $gradeperiod=0;
            foreach ($grade[ "Periods" ] as $rgradeperiod)
            {
                if ($this->ItemHash[ "GradePeriod" ]==$rgradeperiod[ "ID" ])
                {
                    $gradeperiod=$rgradeperiod;
                    break;
                }
            }

            foreach ($this->Grade2ClassData as $rdata) { $this->ItemHash[ $rdata ]=$grade[ $rdata ]; }
            foreach ($this->GradePeriod2ClassData as $rdata) { $this->ItemHash[ $rdata ]=$gradeperiod[ $rdata ]; }

            $this->MySqlInsertItem("",$this->ItemHash);

            $args=$this->Query2Hash();
            $args=$this->Hidden2Hash($args);
            $this->AddCommonArgs2Hash($args);
            $args[ "Action" ]="Search";

            header("Location: ?".$this->Hash2Query($args));
        }


        $this->PrintDocHeadsAndLeftMenu();
        print 
            $this->H(4,"Selecione ".$this->ItemData[ $data ][ "Name" ]).
            $this->StartForm().
            $this->Html_Table
            (
               "",
               $this->ItemTable(1,array(),TRUE,$this->AddDatas),
               array("ALIGN" => 'center')
            ).
            $this->Center($this->Button("submit","Continuar")).
            $this->MakeHidden("GO",1).
            $this->EndForm().
            "";
    }
}

?>