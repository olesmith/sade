<?php

include_once("Classes/Prints/Print.php");
include_once("Classes/Prints/Daylies.php");
include_once("Classes/Prints/Table.php");
include_once("Classes/Prints/Latex.php");

class ClassesPrints extends ClassesPrintsLatex
{
    //*
    //* function UpdateDayliesDisc, Parameter list: $editdatas,&$disc,$class=array()
    //*
    //* GUpdate all discs.
    //*

    function UpdateDayliesDisc($editdatas,&$disc,$class=array())
    {
        if (
              !empty($disc[ "ID" ])
              &&
              $this->GetPOST("Save")==1
           )
        {
            $updatedatas=array();
            foreach ($editdatas as $data)
            {
                $key=$disc[ "ID" ]."_".$data;

                if (!isset($_POST[ $key ])) { continue; }

                $newvalue=$this->GetPOST($key);
                if (empty($disc[ $data ]) || $newvalue!=$disc[ $data ])
                {
                    $disc[ $data ]=$newvalue;
                    array_push($updatedatas,$data);
                }
            }

            if (count($updatedatas)>0)
            {
                $this->ApplicationObj->ClassDiscsObject->MySqlSetItemValues("",$updatedatas,$disc);
            }
        }
    }

    //*
    //* function UpdateDayliesDiscs, Parameter list: $editdatas,$class=array()
    //*
    //* GUpdate all discs.
    //*

    function UpdateDayliesDiscs($editdatas,$class=array())
    {
        if ($this->GetPOST("Save")==1)
        {
            foreach (array_keys($this->ApplicationObj->Discs) as $id)
            {
                if (!empty($this->ApplicationObj->Discs[ $id ][ "ID" ]))
                {
                    $this->UpdateDayliesDisc($editdatas,$this->ApplicationObj->Discs[ $id ],$class);
                }
            }
        }
    }

    //*
    //* function ClassDayliesTable, Parameter list: $edit,$class
    //*
    //* Generates info table for class.
    //*

    function ClassDayliesTable($edit,$class)
    {
        $this->ApplicationObj->ClassDiscsObject->InitActions();

        $table=array();

        $actions=array("Edit","DiscMarks","DiscAbsences","DiscTotals","Dayly",);
        $ractions=array("No.","","","","","",);
        $showdatas=array("GradeDisc","Name","Daylies","AssessmentType","AbsencesType","Teacher","Teacher1","Teacher2");
        $editdatas=$this->ApplicationObj->ClassDiscsObject->DayliesDatas();
        $trimesterdatas=array("DayliesLimit","DayliesClosed","DayliesClosedTime");

        $editdatas=preg_grep('/^DayliesLimit\d$/',$editdatas);

        $empties=array("","","",);
        for ($k=1;$k<=count($actions);$k++) { array_push($empties,""); }


        if ($edit==1 && $this->GetPOST("Save")==1)
        {
            $this->UpdateDayliesDiscs($editdatas,$class);
        }

        $titles=array_merge($ractions,$showdatas);
        $titles=$this->ApplicationObj->ClassDiscsObject->GetDataTitles($titles);
        array_push($table,$this->B($titles));

        $n=0;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $n++;
            $row=array($n);

            foreach ($actions as $action)
            {
                 array_push
                 (
                    $row,
                    $this->ApplicationObj->ClassDiscsObject->ActionEntry($action,$disc)
                 );
            }

            foreach ($showdatas as $data)
            {
                 array_push
                 (
                    $row,
                    $this->ApplicationObj->ClassDiscsObject->MakeShowField($data,$disc)
                 );
            }

            array_push($table,$row);

            
            $titles=array("Trimestre","Início - Fim","Data Limite","Fechado, Prof.","Data");

            $rtable=array();
            array_push($rtable,$this->B($titles));

            for ($k=1;$k<=$disc[ "NAssessments" ];$k++)
            {
                $row=array
                (
                   $this->B($k.":"),
                   $this->ApplicationObj->PeriodsObject->GetTrimesterDateSpan($this->ApplicationObj->Period,$k,"Date"),
                );

                foreach ($trimesterdatas as $data)
                {
                    $rdata=$data.$k;
                    array_push
                    (
                       $row,
                       $this->ApplicationObj->ClassDiscsObject->MakeField($edit,$disc,$rdata,TRUE)
                    );
                }

                array_push($rtable,$row);
            }

            $row=$empties;
            array_push
            (
               $row,
               $this->Html_Table
               (
                  "",
                  $rtable,
                  array("BORDER" => 1),
                  array(),
                  array(),
                  FALSE,
                  FALSE
               )
            );

            array_push($table,$row);
        }
        return $table;
    }

    //*
    //* function HandleClassDaylies, Parameter list: $class=array()
    //*
    //* Generates info table for class.
    //*

    function HandleClassDaylies($class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $edit=0;
        if (preg_match('/^(Admin|Secretary|Clerk)$/',$this->ApplicationObj->Profile))
        {
            $edit=1;
        }

        $this->ApplicationObj->ClassDiscsObject->ReadClassDisciplines($class);

        $this->InitPrintDaylies($class);

        $this->UpdatePrintsSpecForm($class);

        if ($this->GetPOST("Generate")==1)
        {
            $months=$this->ApplicationObj->PeriodsObject->MonthNames($this->ApplicationObj->Period);
            $this->PrintClassDaylies($class,$months);
        }

        $datas=array("Daylies","DayliesStart","DayliesEnd",);
        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            array_push($datas,"Daylies".$n);
        }

        $html=
            $this->H(1,"Diários Eletrônicos ").
            $this->H(2,"Disciplinas");

        if ($edit==1)
        {
            $html.=
                $this->StartForm().
                $this->Buttons().
                "";
        }

        $html.=
            $this->Html_Table
            (
               "",
               $this->ClassDayliesTable($edit,$class),
               array("ALIGN" => 'center'),
               array(),
               array(),
               TRUE,
               FALSE
            );

        if ($edit==1)
        {
            $html.=
                $this->Buttons().
                $this->MakeHidden("Save",1).
                $this->EndForm().
                "";
        }

        print $html;
    }



}

?>