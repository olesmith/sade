<?php

class ClassDiscContentsResultsTable extends ClassDiscContentsResultsUpdate
{
    var $NDateCols=6;
    var $NPreviewCols=3;
    var $NRegisteredCols=3;
    var $NRegisterCols=3;

    //*
    //* function GenDatesResultsTable, Parameter list: $period,$disc,&$updatemsgs
    //*
    //* Generates serch table for registering content dates.
    //*

    function GenDatesResultsTable($period,$disc,&$updatemsgs)
    {
        $dates=$this->ApplicationObj->DatesObject->SelectHashesFromTable
        (
           "",
           $this->DatesSearchSqlWhere($period,$disc),
           array(),
           FALSE,
           "SortKey"
        );

        if ($this->GetPOST("Update")==1)
        {
            if ($this->GetPOST("SaveProgrammed")==1)
            {
                $updatemsgs=$this->AddProgrammedLessons($period,$disc,$dates);
            }
            else
            {
                $updatemsgs=$this->UpdateDatesRegisterTable($period,$disc,$dates);
            }
        }

        $titles=array_merge
        (
           array("No."),
           $this->ApplicationObj->DatesObject->GetDataTitles($this->ResultsDatesData)
        );

        $titles=$this->B($titles);
        array_push
        (
           $titles,
           $this->MultiCell("Aulas Previstas",$this->NPreviewCols),
           $this->MultiCell("Lançar",$this->NRegisterCols),
           $this->MultiCell("Aulas Lançadas",$this->NRegisteredCols)
       );

        $table=array();

        if (
              $this->GetPOST("Search")==1
              &&
              count($this->DiscLessonDates($disc))>0
           )
        {
            array_push
            (
               $table,
               array
               (
                  $this->MultiCell
                  (
                     $this->B("Lançar todos os Dias de Aula: ")." ".
                     $this->MakeCheckBox("SaveProgrammed",1,$this->GetPOST("SaveProgrammed")),
                     15
                  ),
               )
            );
        }

        array_push($table,$titles);

        $names=array
        (
           "Year" => "",
           "Semester" => "",
           "Month" => "",
           "WeekNo" => "",
           "WeekDay" => "",
        );

        $updatemsgs=array();

        $n=1;
        foreach ($dates as $date)
        {
            $this->GenDatesResultsRows($period,$disc,$n++,$date,$names,$table,$updatemsgs);
        }

        return $table;
    }

}

?>