<?php


class ClassDiscContentsHandle extends ClassDiscContentsUpdate
{
    var $ContentsDates=array();
    var $ContentsDateIDs=array();
    var $ContentsDateNames=array();

   //*
    //* function DatesLessonsTable, Parameter list: $period,$disc
    //*
    //* Splits dates.
    //*

    function DatesLessonsTable($period,$disc)
    {
        $html=$this->DatesSearchForm($period,$disc);

        if ($this->GetPOST("Search")==1)
        {
            $html.=$this->DatesSearchResults($period,$disc);
        }

        return $html;
    }

    //*
    //* function HandleDaylyContents, Parameter list: 
    //*
    //* Handles Dayly Contents pages.
    //*

    function HandleDaylyContentsDates()
    {
        $contents=$this->CGI2Contents();
        $dates=$this->ReadContentDates($contents);

        $html=$this->DatesLessonsTable
        (
           $this->ApplicationObj->Period,
           $this->ApplicationObj->Disc
        );

        print
            $this->Html_Table
            (
               "",
               $this-> PeriodContentsTable($contents,$dates),
               array("ALIGN" => 'center',"BORDER" => '1'),
               array(),
               array(),
               FALSE,
               FALSE
            ).
            $html.
            "";
    }

    //*
    //* function AddContentDateSelect, Parameter list: 
    //*
    //* Genrate months add date select field;
    //*

    function AddContentDateSelect()
    {
        $month=$this->CGI2Month();

        if (empty($month) && empty($semester)) { $month=$this->CurrentMonth(); }

        $dates=$this->ApplicationObj->PeriodsObject->GetPeriodMonthDates($month);
        foreach (array_keys($dates) as $id)
        {
            if (!$this->ApplicationObj->SchoolsObject-> DateIsLecturable($dates[ $id ]))
            {
                unset($dates[ $id ]);
            }
        }

        $ids=array(0);
        $names=array("");
        $datehash=array("");
        foreach ($dates as $date)
        {
            array_push($ids,$date[ "ID" ]);

            $contents=$this->SelectHashesFromTable
            (
               "",
               array
               (
                  "Class" => $this->ApplicationObj->Class[ "ID" ],
                  "Disc"  => $this->ApplicationObj->Disc[ "ID" ],
                  "Date"  => $date[ "ID" ]
               ),
               array("Weight")
            );

            $ch=0;
            foreach ($contents as $content) { $ch+=$content[ "Weight" ]; }

            $info="";
            if (count($contents)>0)
            {
                $info=" (".count($contents)."/".$ch.")";
            }

            array_push
            (
               $names,
               $this->ApplicationObj->DatesObject->DateID2Name($date[ "ID" ]).
               $info
            );
        }

        return $this->MakeSelectField("Date",$ids,$names,0);
    }

    //*
    //* function AddContentForm, Parameter list: 
    //*
    //* Generates add content form.
    //*

    function AddContentForm()
    {
        return 
            $this->StartForm("?ModuleName=Classes&Action=DaylyAddContents&Created=0").
            $this->Html_Table
            (
               "",
               array
               (
                  array
                  (
                     $this->H(4,"Adicionar dia de Aula:")
                  ),
                  array
                  (
                     $this->B("Peso:"),
                     $this->MakeInput("Weight",1,1)
                  ),
                  array
                  (
                     $this->B("Data:"),
                     $this->AddContentDateSelect()
                  ),
                  array
                  (
                     $this->MakeHidden("Add",1).
                     $this->Button("submit","Adicionar")
                  ),
               ),
               array(),
               array(),
               array(),
               FALSE,
               FALSE
            ).
            $this->EndForm().
            "";

    }


    //*
    //* function HandleDaylyContents, Parameter list: 
    //*
    //* Generates dayly contents editing form.
    //*

    function HandleDaylyContents()
    {
        if ($this->LatexMode())
        {
            $this->PrintContentsLatex();
            return;
        }

        $addform=$this->AddContentForm();

        $edit=0;
        if (preg_match('/(Admin|Secretary|Clerk|Teacher)/',$this->Profile))
        {
            $edit=1;
        }

        $args=$this->ScriptQueryHash();

        $month="";
        $semester="";

        $month=$this->CGI2Month();

        $semester=$this->GetGET("Semester");

        if (empty($month) && empty($semester)) { $month=$this->CurrentMonth(); }
        $where=$this->CGI2ContentsWhere();
        if (empty($where)) { return; }

        $sumtabletype=0;

        $subtitle="";
        if (!empty($where[ "DateKey" ]))
        {
            $subtitle="Mês de ".$this->ApplicationObj->PeriodsObject->GetMonthName($month);
            $sumtabletype=1;
        }
        elseif (!empty($where[ "Semester" ]))
        {
           $subtitle=$semester."º ".$this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle();
           $sumtabletype=2;
        }

        $contents=$this->CGI2Contents();
        $dates=$this->ReadContentDates($contents);
 
        if ($edit==1)
        {
            if ($this->GetPOST("Save")==1)
            {
                $contents=$this->UpdateDaylyContents($dates,$contents,$this->DaylyContentDatas);
            }
        }

        print
            $this->Html_Table
            (
               "",
               $this-> PeriodContentsTable($contents,$dates,$sumtabletype),
               array("ALIGN" => 'center',"BORDER" => '1'),
               array(),
               array(),
               FALSE,
               FALSE
            ).
            $this->BR().
            $this->ApplicationObj->PeriodsObject->MonthsMenu($args,array(),$month).
            $this->BR().
            $this->ApplicationObj->PeriodsObject->TrimestersMenu($args).
            $this->H(1,"Lançar Conteúdos").
            $this->H(2,$subtitle).
            $this->FrameIt
            (
               $this->AddContentForm()
            ).
            "";

        if (count($contents)==0)
        {
            print
                $this->H(1,"Nenhuma Aula Lançada: ".$subtitle).
                $this->H
                (
                   2,
                   "Utilize '".
                   $this->ApplicationObj->ClassesObject->Actions[ "DaylyContentsDates" ][ "Name" ].
                   "' para Lançar"
                );

            return;
        }

        $addeddate=intval($this->GetGET("Created"));
        if (!empty($addeddate))
        {
            $addedcontent=$this->SelectUniqueHash
            (
               "",
               array
               (
                  "ID" => $addeddate,
                  
               ),
               TRUE
            );

            print 
                $this->H
                (
                   5,
                   "Adicionada Aula: ".
                   $this->ApplicationObj->DatesObject->DateID2Name($addedcontent[ "Date" ]).
                   " com Carga Horário: ".$addedcontent[ "Weight" ]
                ); 
        }

        if ($edit==1)
        {
            print
                $this->StartForm("?ModuleName=Classes&Created=0").
                $this->Buttons().
                "";
        }

        print
            $this->Html_Table
            (
               "",
               $this->DaylyContentsTable($edit,$contents,$dates),
               array("ALIGN" => 'center',"BORDER" => '1'),
               array(),
               array(),
               FALSE,
               FALSE
            ).
            "";

        if ($edit==1)
        {
            print
            $this->Buttons().
            $this->MakeHidden("Save",1).
            $this->EndForm().
                "";
        }
    }

    //*
    //* function HandleDaylyAddContents, Parameter list: 
    //*
    //* Generates dayly contents editing form.
    //*

    function HandleDaylyAddContents()
    {
        $edit=0;
        if (preg_match('/(Admin|Secretary|Clerk|Teacher)/',$this->Profile))
        {
            $edit=1;
        }

        $args=$this->ScriptQueryHash();

        $month="";
        $semester="";

        $month=$this->CGI2Month();

        $semester=$this->GetGET("Semester");

        if (empty($month) && empty($semester)) { $month=$this->CurrentMonth(); }
        $where=$this->CGI2ContentsWhere();
        if (empty($where)) { return; }

        $sumtabletype=0;

        $subtitle="";
        if (!empty($where[ "DateKey" ]))
        {
            $subtitle="Mês de ".$this->ApplicationObj->PeriodsObject->GetMonthName($month);
            $sumtabletype=1;
        }
        elseif (!empty($where[ "Semester" ]))
        {
           $subtitle=$semester."º ".$this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle();
           $sumtabletype=2;
        }

        $contents=$this->CGI2Contents();
        $dates=$this->ReadContentDates($contents);

        //Update and redirect!
        $this->UpdateAddContent();
    }


    //*
    //* function HandleCalendar, Parameter list: 
    //*
    //* Handles Dayly Calendar pages. Should move to Periods.
    //*

    function HandleCalendar()
    {
        $this->DatesCalendar($this->ApplicationObj->Period);
    }
}

?>