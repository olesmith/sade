<?php


class ClassDiscContentsForm extends ClassDiscContentsSelects
{
    //*
    //*
    //* function DatesSearchTable, Parameter list: $period,$disc
    //*
    //* Generates serch form for limitng Period dates.
    //*

    function DatesSearchTable($period,$disc)
    {
        $table=array
        (
           array
           (
              $this->H(3,"Pesquisar Dias de Aula Potenciais"),
           ),
           array
           (
              $this->B($this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle().":"),
              $this->SemesterSearchSelect($disc),
           ),
           array
           (
              $this->B("Mês:"),
              $this->MonthSearchSelect($period),
           ),
           array
           (
              $this->B("Dia de Semana:"),
              $this->WeekDaySearchSelect(),
           ),
           array
           (
              $this->B("Data:"),
              $this->DateSearchSelect(),
           ),
        );

        $wdays=$this->DiscLessonDates($disc);
        if (count($wdays)>0)
        {
            for ($n=0;$n<count($wdays);$n++) { $wdays[$n]=$this->WeekDays[ $wdays[$n]-1 ]; }

            array_push
            (
               $table,
               array
               (
                  $this->B("Dias de Aula: ".join(", ",$wdays)),
                  $this->MakeCheckBox("Programmed",1,$this->GetPOST("Programmed")),
               )
            );
        }

        return $table;

    }

    //*
    //*
    //* function DatesSearchForm, Parameter list: $period,$disc
    //*
    //* Generates serch form for limitng Period dates.
    //*

    function DatesSearchForm($period,$disc)
    {
        return
            $this->Center
            (
               $this->StartForm().
               $this->Html_Table
               (
                  "",
                  $this->DatesSearchTable($period,$disc),
                  array("ALIGN" => 'center'),
                  array(),
                  array(),
                  TRUE,
                  FALSE
               ).
               $this->MakeHidden("Search",1).
               $this->Button("submit","Pesquisar",array("ALIGN" => 'center')).
               $this->EndForm()
            ).
            "";
    }


}

?>