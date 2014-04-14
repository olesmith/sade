<?php


class ClassDiscMarksTitles extends ClassDiscMarksRow
{
    //************** Student Data titles **************//

    var $SemesterRow=0;
    var $MaxsRow=1;
    var $ShortsRow=3;
    var $LongsRow=2;
    var $StudentDataActions=array();

    //*
    //* function DaylyMarksStudentDataTitles, Parameter list:
    //*
    //* Generates titles of student data.
    //*

    function DaylyMarksStudentDataTitles()
    {
        $row=array("No.");

        if (!$this->LatexMode)
        {
            $action="";
            if (preg_match('/^(Admin|Clerk|Secretary)$/',$this->ApplicationObj->Profile))
            {
                $action="Edit";
            }
            elseif (preg_match('/^(Teacher|Coordinator)$/',$this->ApplicationObj->Profile))
            {
                $action="Show";
            }

            if (!empty($action))
            {
                array_push($row,"");
                array_push($this->StudentDataActions,$action);
            }
        }

        $n=1;
        foreach ($this->StudentData as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->StudentsObject->GetDataTitle($data)
            );
        }

        return $this->B($row);
    }

    //************** Student Title cols **************//

    //*
    //* function DaylyMarksStudentTitleRows, Parameter list: 
    //*
    //* Generates student data title row of Marks table.
    //*

    function DaylyMarksStudentTitleRows(&$table)
    {
        //Must be first - updates $this->StudentDataActions
         $table[ $this->ShortsRow ]=array_merge
        (
           $table[ $this->ShortsRow ],
           $this->DaylyMarksStudentDataTitles()
        );

        array_push
        (
           $table[ $this->SemesterRow ],
           $this->MultiCell
           (
               "",
               count($this->StudentData)+
               count($this->StudentDataActions)+
               1
           )
        );

       array_push
        (
           $table[ $this->MaxsRow ],
           $this->MultiCell
           (
               "",
               count($this->StudentData)+
               count($this->StudentDataActions)+
               1
           )
        );

        array_push
        (
           $table[ $this->LongsRow ],
           $this->MultiCell
           (
               "",
               count($this->StudentData)+
               count($this->StudentDataActions)+
               1
           )
         );
    }

    //************** Student Semester Title cols **************//

    //*
    //* function DaylyAbsencesSemesterTitleRows, Parameter list: $semester,&$table,$assess
    //*
    //* Generates title rows, one semester.
    //*

    function DaylyAbsencesSemesterTitleRows($semester,&$table,$assess)
    {
        $ncols=1;
        if (count($this->Assessments[ $semester ])>1)
        {
            $ncols=count($this->Assessments[ $semester ])+1;
        }

        array_push
        (
           $table[ $this->SemesterRow ],
           $this->MultiCell
           (
               $this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle()." ".$semester.": ".
               $this->B(sprintf("*%.1f",$this->ApplicationObj->Disc[ "Weights" ][ $semester-1 ][ "Weight" ])),
               $ncols
           )           
        );

        $n=0;
        foreach ($this->Assessments[ $semester ] as $assessment)
        {
            array_push
            (
               $table[ $this->MaxsRow ],
               $this->B(sprintf("%.1f",$assessment[ "MaxVal" ]))
            );

            array_push
            (
               $table[ $this->ShortsRow ],
               $this->B($this->SUB("N",++$n))
            );

            array_push
            (
               $table[ $this->LongsRow ],
               $this->Center($this->B($assessment[ "Name" ]))
            );

        }

        if (count($this->Assessments[ $semester ])>1)
        {
            array_push
            (
               $table[ $this->MaxsRow ],
               $this->B(sprintf("%.1f",$assess[ "MaxVal" ][ $semester ]))
            );

            array_push
            (
               $table[ $this->ShortsRow ],
               $this->B($this->SUB("M",$semester))
            );

            array_push
            (
               $table[ $this->LongsRow ],
               $this->B("Média")
            );
        }
    }


    //*
    //* function DaylyAbsencesSemestersTitleRows, Parameter list: &$table,$assess,$include
    //*
    //* Generates titles cols, all semesters.
    //*

    function DaylyAbsencesSemestersTitleRows(&$table,$assess,$include)
    {
        $sem=$this->GetGET("Semester");
        for ($semester=1;$semester<=$this->ApplicationObj->Disc[ "NAssessments" ];$semester++)
        {
            if (!empty($sem) && $sem!=$semester) { continue; }

            //Latex paging
            if (empty($include[ "Semesters" ][ $semester ])) { continue; }

            $this->DaylyAbsencesSemesterTitleRows($semester,$table,$assess);
        }
    }


    //************** Recoveries Title cols **************//


    //*
    //* function DaylyAbsencesRecoveryTitleRows, Parameter list: $recovery,&$table,$assess
    //*
    //* Generates recovery title cols.
    //*

    function DaylyAbsencesRecoveryTitleRows($recovery,&$table,$assess)
    {
        $semester=$recovery+$this->ApplicationObj->Disc[ "NAssessments" ];

        $ncols=2;
        if (count($this->Assessments[ $semester ])>1)
        {
            $ncols=count($this->Assessments[ $semester ])+2;
        }

        array_push
        (
           $table[ $this->SemesterRow ],
           $this->MultiCell
           (
               "Rec. ".$recovery,
               $ncols
           )
        );

        $n=0;
        foreach ($this->Assessments[ $semester ] as $assessment)
        {
            array_push
            (
               $table[ $this->ShortsRow ],
               $this->B($this->SUB("N",$this->SUB("R",++$n)))
            );

            array_push
            (
               $table[ $this->LongsRow ],
               $this->Center($this->B($assessment[ "Name" ]))
            );

        }


        if (count($this->Assessments[ $semester ])>1)
        {
            array_push
            (
               $table[ $this->MaxsRow ],
               $this->B(sprintf("%.1f",$assess[ "MaxVal" ][ $semester-1 ]))
            );

            array_push
            (
               $table[ $this->ShortsRow ],
               $this->B($this->SUB("R",$recovery))
            );

            array_push
            (
               $table[ $this->LongsRow ],
               $this->B("Média")
            );
        }

        array_push
        (
           $table[ $this->MaxsRow ],
           $this->MultiCell("",2)
        );
        array_push
        (
           $table[ $this->LongsRow ],
           $this->B("Média")
        );

        array_push
        (
           $table[ $this->ShortsRow ],
           $this->B($this->SUB("M",$this->SUB("R",$recovery)))
        );
    }


    //*
    //* function DaylyAbsencesRecoveriesTitleRows, Parameter list: &$table,$assess,$include
    //*
    //* Generates title rows for all recovery assessments.
    //*

    function DaylyAbsencesRecoveriesTitleRows(&$table,$assess,$include)
    {
        $sem=$this->GetGET("Semester");
        if (!empty($sem) && $sem!=$this->ApplicationObj->Disc[ "NAssessments" ]+1) { return; }

        for ($recovery=1;$recovery<=$this->ApplicationObj->Disc[ "NRecoveries" ];$recovery++)
        {
            //Latex paging
            if (empty($include[ "Recoveries" ][ $recovery ])) { continue; }

            $this->DaylyAbsencesRecoveryTitleRows($recovery,$table,$assess);
        }
    }




    //************** Semester Results Title cols **************//




    //*
    //* function DaylyAbsencesSemestersResultTitleRows, Parameter list: &$table,$assess
    //*
    //* Generates recoveries cols, all.
    //*

    function DaylyAbsencesSemestersResultTitleRows(&$table,$assess)
    {
       array_push
        (
           $table[ $this->SemesterRow ],
           $this->MultiCell("Resultado",2)
        );

        array_push
        (
           $table[ $this->MaxsRow ],
           $this->B(sprintf("%.1f",$this->ApplicationObj->Disc[ "MediaLimit" ])),
           ""
        );

        array_push
        (
           $table[ $this->LongsRow ],
           $this->MultiCell
           (
               "",
               2
           )
        );

         array_push
        (
           $table[ $this->ShortsRow ],
           $this->B("M"),
           $this->B("R")
        );
   }

    //************** Final Results Title cols **************//




    //*
    //* function DaylyAbsencesFinalResultTitleRows, Parameter list: &$table,$assess
    //*
    //* Generates recoveries cols, all.
    //*

    function DaylyAbsencesFinalResultTitleRows(&$table,$assess)
    {
       array_push
        (
           $table[ $this->SemesterRow ],
           $this->B(sprintf("*%.1f",$assess[ "Weight" ])),
           ""
        );
        array_push
        (
           $table[ $this->MaxsRow ],
           $this->B(sprintf("%.1f",$this->ApplicationObj->Disc[ "FinalMedia" ])),
           ""
        );

        array_push
        (
           $table[ $this->LongsRow ],
           $this->MultiCell
           (
               "Final",
               2
           )
        );

         array_push
        (
           $table[ $this->ShortsRow ],
           $this->B($this->SUB("M","F")),
           $this->B("R")
        );
   }


    //**************  Generate title cols **************//


    //*
    //* function DaylyMarksTableTitleRows, Parameter list: $assess,$include
    //*
    //* Handles Dayly Marks pages.
    //*

    function DaylyMarksTableTitleRows($assess,$include)
    {
        $table=array
        (
           array(),
           array(),
           array(),
           array(),
        );

        $this->DaylyMarksStudentTitleRows($table);

        if (!empty($include[ "Semesters" ]))
        {
            $this->DaylyAbsencesSemestersTitleRows($table,$assess,$include);
        }

        if ($this->ApplicationObj->Disc[ "NRecoveries" ]>0)
        {
            if (!empty($include[ "SemesterResults" ]))
            {
                $this->DaylyAbsencesSemestersResultTitleRows($table,$assess);
            }

            if (!empty($include[ "Recoveries" ]))
            {
                $this->DaylyAbsencesRecoveriesTitleRows($table,$assess,$include);
            }
        }

        if (!empty($include[ "Result" ]))
        {
            $this->DaylyAbsencesFinalResultTitleRows($table,$assess);
        }

        return $table;
    }
}

?>