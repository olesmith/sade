<?php


class ClassDiscAbsencesHandle extends ClassDiscAbsencesReads
{
    //*
    //* function HandleDaylyAbsences, Parameter list: 
    //*
    //* Handles Dayly Absences pages.
    //*

    function HandleDaylyAbsences()
    {
        if ($this->LatexMode())
        {
            $this->PrintAbsencesLatex();
            return;
        }

        $date=$this->GetGET("Date");
        $month=$this->GetGET("Month");
        if (empty($month)) { $month=$this->CurrentMonth(); }

        $this->ApplicationObj->ClassDiscContentsObject->Contents2Trimester();

        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($this->ApplicationObj->Class[ "ID" ]);

        $edit=$this->ApplicationObj->ClassDiscsObject->CheckAccessEdit2Dayly();


        print
            $this->H(1,"Cadastrar Frequências: Mês de ".$this->ApplicationObj->Months[ $month-1 ]).
            $this->H(5,"Lançar/Marcar somente Faltas!").
            $this->ApplicationObj->PeriodsObject->MonthsMenu
            (
               $this->ScriptQueryHash(),
               array(),
               $month
            ).
            $this->BR().
            $this->AbsencesDatesMenu
            (
               $this->ScriptQueryHash()
            ).
            $this->BR().
                "";

        if ($edit==1)
        {
            print
                $this->StartForm().
                $this->Buttons();
        }

        print
            $this->Html_Table
            (
               "",
               $this->DaylyAbsencesTable($edit,$month),
               array("ALIGN" => 'center'),
               array(),
               array(),
               TRUE,TRUE
            );
        
        if ($edit==1)
        {
            print
                $this->MakeHidden("Save",1).
                $this->Buttons().
                $this->EndForm().
                "";
        }
    }

    //*
    //* function HandleDaylyStatAbsences, Parameter list: 
    //*
    //* Handles Dayly Absences pages.
    //*

    function HandleDaylyStatAbsences()
    {
        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($this->ApplicationObj->Class[ "ID" ]);

        $action=$this->GetGET("Action");

        $type=-1;
        if ($action=="DaylyAbsencesStats") { $type=0; }
        elseif ($action=="DaylyAbsencesMonths") { $type=1; }
        elseif ($action=="DaylyAbsencesSemesters") { $type=2; }

        if ($type<0) { die("HandleDaylyStatAbsences: Invalid type!"); }


        $subtitle="Ano/Semestre";
        if ($type==1)
        {
            $subtitle="Por Mês";
        }
        elseif ($type==2)
        {
            $subtitle="Por ".$this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle();
        }
        print
            $this->H(1,"Frequências, Resumo").
            $this->H(2,$subtitle).
             "";

        print
            $this->Html_Table
            (
               "",
               $this->DaylyAbsencesStatsTable($type),
               array("ALIGN" => 'center'),
               array(),
               array(),
               TRUE,TRUE
            );
        
   }

}

?>