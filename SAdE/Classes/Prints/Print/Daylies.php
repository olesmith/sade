<?php

class ClassesPrintsPrintDaylies extends ClassesPrintsPrintSpecForm
{
    //*
    //* Variables of ClassesHandle class:
    //*

    var $NFields,$NStudentsPP;

    var $Daylies_Orientation=2;
    var $Daylies_NFields=23;
    var $Daylies_NFields_Port=25;
    var $Daylies_NStudents_Port=40;
    var $Daylies_NFields_Land=45;
    var $Daylies_NStudents_Land=25;

    //*
    //* function HandleClassPrintDaylies, Parameter list: $class=array(),$school=array()
    //*
    //* Generates info table for class.
    //*

    function HandleClassPrintDaylies($class=array(),$school=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        if (empty($school)) { $school=$this->ApplicationObj->School; }

        $this->ApplicationObj->ClassDiscsObject->ReadClassDisciplines($class);

        $this->InitPrintDaylies($class);

        if ($this->GetPOST("Generate")==1)
        {
            $this->UpdatePrintsSpecForm($class);
            $months=$this->ApplicationObj->PeriodsObject->MonthNames($this->ApplicationObj->Period);
            $this->PrintClassDaylies($class,$months);
        }

        print
            $this->H(1,"Imprimíveis por Disciplinas").
            $this->MakeActionMenu
            (
               array
               (
                  "DayliesFlux",
                  "StudentsPrints"
               ),
               "atablemenu",
               $class[ "ID" ]
            ).
            $this->BR().
            $this->StartForm().
            $this->PrintsSpecForm($class).
            $this->ApplicationObj->SchoolsObject->PrintHeadTableForm($school).
            $this->Center($this->Button("submit","Salvar/Gerar Imprimiveis")).
            $this->H(3,"Selecionar Impressos").
            $this->HtmlTable
            (
               "",
               $this->ClassDayliesPrintTable($class)
            ).
            $this->Center($this->Button("submit","Salvar/Gerar Imprimiveis")).
            $this->MakeHidden("Latex",1).
            $this->MakeHidden("Generate",1).
            $this->EndForm().
            "";
   }

    //* function , Parameter list: $class=array()
    //*
    //* Generates info table for class.
    //*

    function HandleClassDayliesFlux($class=array())
    {
         if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $this->ApplicationObj->ClassDiscsObject->ReadClassDisciplines($class);
        $this->InitPrintDaylies($class);

        $rdisc=$this->GetPOST("Test_Disc");
        $rmonth=$this->GetPOST("Test_Disc");

        $rrmonth=-1;
        $firstmonth=-1;
        foreach ($this->ApplicationObj->PeriodsObject->MonthNames($this->ApplicationObj->Period) as $monthid => $month)
        {
            if ($firstmonth<0) { $firstmonth=$month; }
            if ($rmonth==$month) { $rrmonth=$month; break; }
        }

        if ($rrmonth<0) { $rrmonth=$firstmonth; }

        $rrdisc=array();
        $firstdisc=array();
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            if (empty($firstdisc)) { $firstdisc=$disc; }

            if ($rdisc==$disc[ "ID" ]) { $rrdisc=$disc; break; }
        }

        if (empty($rrdisc)) { $rrdisc=$firstdisc; }

        if (!empty($rrdisc[ "ID" ]) && preg_match('/^\d\d\/\d\d\d\d$/',$rrmonth))
        {
            print
                $this->H(1,$this->Actions[ "DayliesFlux" ][ "Name" ]).
                $this->HtmlClassPrintDayly($class,$rrdisc,$rrmonth).
                "";
        }
        else
        {
            print "Not allowed, PrintDaylies...";
            exit();
        }
    }


    //*
    //* function GetMonthFirstDate, Parameter list: $month
    //*
    //*  Returns the sort date of the first date this month.
    //*

    function GetMonthFirstDate($month)
    {
        $comps=preg_split('/\//',$month);
        $comps=array_reverse($comps);

        return $comps[0].sprintf("%02d",$comps[1])."01";
    }
    //*
    //* function GetNextMonthFirstDate, Parameter list: $month
    //*
    //*  Returns the sort date of the first date next month.
    //*

    function GetNextMonthFirstDate($month)
    {
        $comps=preg_split('/\//',$month);
        $comps=array_reverse($comps);

        if ($comps[1]==12)
        {
            $comps[0]++;
            $comps[1]=1;
        }
        else
        {
            $comps[1]++;
        }

        return $comps[0].sprintf("%02d",$comps[1])."01";
    }

    //*
    //* function DiscSelect, Parameter list:
    //*
    //* Generates Test_Disc select field.
    //*

    function DiscSelect()
    {
        $discs=array();
        $discids=array();
        foreach ($this->ApplicationObj->Discs as $rdisc)
        {
            array_push($discs,$rdisc[ "Name" ]);
            array_push($discids,$rdisc[ "ID" ]);
        }

        return $this->MakeSelectField
        (
           "Test_Disc",
           $discids,
           $discs,
           $this->GetPOST("Test_Disc")
        );
    }

    //*
    //* function MonthSelect, Parameter list:
    //*
    //* Generates Test_Month select field.
    //*

    function MonthSelect()
    {
        $months=array();
        $monthids=array();
        foreach ($this->ApplicationObj->PeriodsObject->MonthNames($this->ApplicationObj->Period) as $id => $month)
        {
            array_push($months,$month);
            array_push($monthids,$id);
        }

        return $this->MakeSelectField
        (
           "Test_Month",
           $monthids,
           $months,
           $this->GetPOST("Test_Month")
        );
    }

    //*
    //* function ClassDaylyTitle, Parameter list:
    //*
    //* Returns Daylies title )(html or latex(
    //*

    function ClassDaylyTitle()
    {
        return $this->H(3,"Diários de Classe");
    }


    //*
    //* function ClassMarksTitle, Parameter list:
    //*
    //* Returns Daylies title )(html or latex(
    //*

    function ClassMarksTitle()
    {
        return $this->H(3,"Ficha de Notas");
    }

    //*
    //* function ClassSignaturesTitle, Parameter list:
    //*
    //* Returns Daylies title )(html or latex(
    //*

    function ClassSignaturesTitle()
    {
        return $this->H(3,"Lista de Assinaturas");
    }


    //*
    //* function GetDayliesNFields, Parameter list: $class
    //*
    //* Returns number of students per page, orientation dependant.
    //*

    function GetDayliesNFields($class)
    {
        $key=25;
        if     ($class[ "DayliesOrientation" ]==1) { $key=$class[ "DayliesNFields_1" ]; }
        elseif ($class[ "DayliesOrientation" ]==2) { $key=$class[ "DayliesNFields_2" ]; }

        return $key;
    }

    //*
    //* function GetDayliesNFields, Parameter list: $class
    //*
    //* Returns number of students per page, orientation dependant.
    //*

    function GetDayliesNStudentsPP($class)
    {
        $key=30;
        if     ($class[ "DayliesOrientation" ]==1) { $key=$class[ "DayliesNStudentsPP_1" ]; }
        elseif ($class[ "DayliesOrientation" ]==2) { $key=$class[ "DayliesNStudentsPP_2" ]; }

        return $key;
  }


    //*
    //* function InitPrintDaylies, Parameter list: &$class
    //*
    //* Initializes Daylies settings (nempties, etc).
    //*

    function InitPrintDaylies(&$class)
    {
        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($class[ "ID" ]);

        if ($class[ "LastStudentsLast" ]==1) { $class[ "LastStudentsLastDate" ]=""; }
        elseif ($class[ "LastStudentsLast" ]==2)
        {
            if (empty($class[ "LastStudentsLastDate" ]))
            {
                $class[ "LastStudentsLastDate" ]=$this->TimeStamp2DateSort();
            }
        }

        $orientation=$class[ "DayliesOrientation" ];

        //Orientation dependents
        $this->NFields=$this->GetDayliesNFields($class);
        $this->NStudentsPP=$this->GetDayliesNStudentsPP($class);

        $this->NMFields=$class[ "DayliesNMarkFields" ];

        $this->Empty=" ";
        $this->Empties=array();
        for ($n=1;$n<=$this->NFields+2;$n++) { array_push($this->Empties,$this->Empty); }

        $this->MEmpty=" ";
        $this->MEmpties=array();
        for ($n=1;$n<=$this->NMFields;$n++) { array_push($this->MEmpties,$this->MEmpty); }


        $this->LatexPath=preg_replace
        (
           '/#Setup/',
           $this->ApplicationObj->SetupPath,
           $this->LatexSkelPath()."/".
           $this->ModuleName."/".
           "Daylies"
        );

        $this->DaylyHash=array
        (
           "School" => $this->ApplicationObj->School[ "Name" ],
           "Class"  => $this->ApplicationObj->Class[ "Name" ],
           "Period" => $this->ApplicationObj->Period[ "Name" ],
        );
    }





    //*
    //* function HtmlClassPrintDayly, Parameter list: $class,$disc,$month
    //*
    //* Generates info table for class as Latex and tries to generate the PDF.
    //*

    function HtmlClassPrintDayly($class,$disc,$month)
    {
        $tdisc=$this->GetPOST("Test_Disc");
        if (!empty($tdisc))
        {
            foreach ($this->ApplicationObj->Discs as $disc)
            {
                if ($disc[ "ID" ]==$tdisc)
                {
                    $disc=$disc;
                }
            }
        }

        $tmonth=$this->GetPOST("Test_Month");
        if (!empty($tmonth))
        {
            foreach ($this->ApplicationObj->PeriodsObject->MonthNames($this->ApplicationObj->Period) as $id => $rmonth)
            {
                if ($id==$tmonth)
                {
                    $month=$rmonth;
                }
            }
        }

        $html=
            $this->ClassDaylyTitle().
            $this->StartForm().
            $this->HtmlTable
            (
               "",
               $this->ClassDaylyInfoTable($class,$disc,$month)
            );

        $comps=preg_split('/\//',$month);
        $comps=array_reverse($comps);

        if ($comps[1]==12)
        {
            $comps[0]++;
            $comps[1]=1;
        }
        else
        {
            $comps[1]++;
        }

        $currentdate=$comps[0].sprintf("%02d",$comps[1])."01";
        foreach ($this->StudentsDaylyTables($class,$disc,$currentdate,$month) as $table)
        {
            $html.=$this->HtmlTable("",$table);
        }

        return $html;
    }
}

?>