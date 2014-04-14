<?php

include_once("Class/Disc/Contents/Search/Selects.php");
include_once("Class/Disc/Contents/Search/Form.php");
include_once("Class/Disc/Contents/Search/Wheres.php");
include_once("Class/Disc/Contents/Results/Rows.php");
include_once("Class/Disc/Contents/Results/Update.php");
include_once("Class/Disc/Contents/Results/Table.php");

class ClassDiscContentsSearch extends ClassDiscContentsResultsTable
{
    //*
    //* Variables of ClassDiscNLessons class:
    //*

    var $ResultsDatesData=array("Year","Month","WeekNo","WeekDay","Date",);

    //*
    //*
    //* function DatesSearchResults, Parameter list: $period,$disc
    //*
    //* Generates serch form for limitng Period dates.
    //*

    function DatesSearchResults($period,$disc)
    {
        $updatemsgs=array();
        $table=$this->GenDatesResultsTable($period,$disc,$updatemsgs);

        $updatemsg="";
        if (count($updatemsgs)>0)
        {
            $updatemsg=
                $this->H(3,"Mensagem Gerada:").
                join("<BR>\n",$updatemsgs);
        }

        return
            $this->Center
            (
               $updatemsg.
               $this->H(2,"Dias Conformando a Pesquisa:").
               $this->StartForm().
               $this->Buttons().
               $this->Html_Table
               (
                  "",
                  $table,
                  array("ALIGN" => 'center'),
                  array(),
                  array("STYLE" => 'border-style: solid;border-width: 1px;'),
                  FALSE,
                  FALSE
               ).
               $this->MakeHidden("Semester",$this->GetPOST("Semester")).
               $this->MakeHidden("Month",$this->GetPOST("Month")).
               $this->MakeHidden("Date",$this->GetPOST("Date")).
               $this->MakeHidden("WeekDay",$this->GetPOST("WeekDay")).
               $this->MakeHidden("Programmed",$this->GetPOST("Programmed")).
               $this->MakeHidden("Search",1).
               $this->MakeHidden("Update",1).
               $this->Buttons().
               $this->EndForm()
            ).
            "";
    }


}

?>