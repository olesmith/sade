<?php

class ClassDiscsTitleRows extends ClassDiscsRow
{

    //*
    //* function FirstRows, Parameter list: $disc
    //*
    //* Generates diss or student first title entries.
    //*

    function FirstRows($disc)
    {
        $row=array("No.");
        if (!$this->LatexMode())
        {
            array_push($row,"");
        }

        if ($this->PerDisc)
        {
            $row=array_merge
            (
               $row,
               $this->ApplicationObj->StudentsObject->GetDataTitles
               (
                  $this->StudentsData
               )
            );
        }
        else
        {
            $row=array_merge
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->GetDataTitles
               (
                  $this->DiscsData
               )
            );
        }

        return array
        (
           $row,
           array($this->MultiCell("",count($row))),
           array($this->MultiCell("",count($row))),
        );

    }


     //*
    //* function StatusTitles, Parameter list: $disc
    //*
    //* Generates dis or student status entries.
    //*

    function StatusTitles($disc)
    {
        $row=array();
        if ($this->PerDisc)
        {
            array_push($row,"Status");
        }
        else
        {
            array_push($row,"Status");   
        }

        return $row;
    }


    //*
    //* function FinalTitles, Parameter list: $disc
    //*
    //* Generates final titles.
    //*

    function FinalTitles($disc)
    {
        return array("R");
    }

    //*
    //* function AddEmpties, Parameter list: &$rows,$nr=3,$nc=
    //*
    //* Adds empties to all entries em $rows..
    //*

    function AddEmpties(&$rows,$nc=1,$empty="")
    {
        if ($this->EmptyTableColumns)
        {
            foreach (array_keys($rows) as $id)
            {
                for ($n=1;$n<=$nc;$n++)
                {
                    array_push($rows[ $id ],$empty);
                }
            }
        }
    }

    //*
    //* function AbsencesTitleRows, Parameter list: &$rows,$edit=0,$tedit=0,$disc=array()
    //*
    //* Creates Titles rows petrtaining to Absences for Discs or Students table
    //*

    function AbsencesTitleRows(&$rows,$edit=0,$tedit=0,$disc=array())
    {
        if ($this->ApplicationObj->ClassDiscsObject->ShowAbsences)
        {
            $absencestitles=$this->ApplicationObj->ClassAbsencesObject->AbsencesTitles($disc);
            $rows[0]=array_merge($rows[0],$absencestitles);
            array_push($rows[1],$this->MultiCell("Faltas",count($absencestitles)));
            $rows[2]=array_merge
            (
               $rows[2],
               $this->ApplicationObj->ClassDiscNLessonsObject->NLessonsRow
               (
                  $edit,
                  $this->ApplicationObj->Class,
                  $disc,
                  $this->ApplicationObj->ClassDiscsObject->ShowNLessonsTotals
               )
            );

            $this->AddEmpties($rows);
        }
    }

    //*
    //* function MarksTitleRows, Parameter list: &$rows,$edit=0,$tedit=0,$disc=array()
    //*
    //* Creates Titles rows petrtaining to Absences for Discs or Students table
    //*

    function MarksTitleRows(&$rows,$edit=0,$tedit=0,$disc=array())
    {
        $ncells=3;
        if ($this->LatexMode()) { $ncells=1; }
        if ($this->ApplicationObj->ClassDiscsObject->ShowMarks)
        {
            $markstitles=$this->ApplicationObj->ClassMarksObject->MarksTitles($disc);
            $rows[0]=array_merge($rows[0],$markstitles);
            array_push($rows[1],$this->MultiCell("Notas",count($markstitles)));
            $rows[2]=array_merge
            (
               $rows[2],
               $this->ApplicationObj->ClassDiscWeightsObject->WeightsInputs
               (
                  $tedit,
                  $disc,
                  $this->ShowMarkSums,
                  $ncells
               ),
               array("")
            );

            $this->AddEmpties($rows);
        }

        if ($this->ShowRecoveries)
        {
            for ($n=1;$n<=$disc[ "NRecoveries" ];$n++)
            {
                $recoverytitles=$this->ApplicationObj->ClassMarksObject->RecoveryTitles($disc,$n);
                $rows[0]=array_merge($rows[0],$recoverytitles);
                array_push($rows[1],$this->MultiCell("Recup. No. $n",count($recoverytitles)));
                $rows[2]=array_merge
                (
                   $rows[2],
                   $this->ApplicationObj->ClassMarksObject->RecoveryWeightsInputs($tedit,$disc,$n)
                );

                $this->AddEmpties($rows);
            }
        }
    }

    //*
    //* function ResultTitleRows, Parameter list: &$rows,$edit=0,$tedit=0,$disc=array()
    //*
    //* Creates Titles rows petrtaining to Absences for Discs or Students table
    //*

    function ResultTitleRows(&$rows,$edit=0,$tedit=0,$disc=array())
    {
        if (
              $this->ApplicationObj->ClassDiscsObject->ShowMediaFinal
              ||
              $this->ApplicationObj->ClassDiscsObject->ShowAbsenceFinal
              ||
              $this->ApplicationObj->ClassDiscsObject->ShowFinal
           )
        {
            $finaltitles=array();

            if ($this->ApplicationObj->ClassDiscsObject->ShowAbsenceFinal)
            {
                $finaltitles=array_merge
                (
                   $finaltitles,
                   $this->ApplicationObj->ClassAbsencesObject->FinalTitles($disc)
                );
            }

            if ($this->ApplicationObj->ClassDiscsObject->ShowMediaFinal)
            {
                $finaltitles=array_merge
                (
                   $finaltitles,
                   $this->ApplicationObj->ClassMarksObject->FinalTitles($disc)
                );
            }

            if ($this->ApplicationObj->ClassDiscsObject->ShowFinal)
            {
                $finaltitles=array_merge
                (
                   $finaltitles,
                   $this->FinalTitles($disc)
                );
            }

            $rows[0]=array_merge($rows[0],$finaltitles);
            array_push($rows[1],$this->MultiCell("Resultado",count($finaltitles)));
            array_push($rows[2],$this->MultiCell("",count($finaltitles)));
        }
    }

    //*
    //* function MakeTitleRows, Parameter list: $edit=0,$tedit=0,$disc=array()
    //*
    //* Creates Titles rows for Discs or Students table
    //*

    function MakeTitleRows($edit=0,$tedit=0,$disc=array())
    {
        $rows=$this->FirstRows($disc);

        $this->AddEmpties($rows);

        if ($this->ApplicationObj->ClassDiscsObject->ShowStatus)
        {
            $statustitles=$this->StatusTitles($disc);
            $rows[0]=array_merge($rows[0],$statustitles);
            array_push($rows[1],$this->MultiCell("",count($statustitles)));
            array_push($rows[2],$this->MultiCell("",count($statustitles)));

            $this->AddEmpties($rows);
        }

        if (!$this->PerDisc)
        {
            if ($this->ApplicationObj->ClassDiscsObject->ShowMarkWeights)
            {
                $weighttitles=$this->ApplicationObj->ClassMarksObject->MarkWeightTitles($disc);
                $rows[0]=array_merge($rows[0],$weighttitles);
                array_push($rows[1],$this->MultiCell("Pesos",count($weighttitles)));
                array_push($rows[2],$this->MultiCell("",count($weighttitles)));

                $this->AddEmpties($rows);
            }

            if ($this->ApplicationObj->ClassDiscsObject->ShowNLessons)
            {
                $weighttitles=$this->ApplicationObj->ClassAbsencesObject->NAbsencesTitles($disc);
                $rows[0]=array_merge($rows[0],$weighttitles);
                array_push($rows[1],$this->MultiCell("Aulas Dadas",count($weighttitles)));
                array_push($rows[2],$this->MultiCell("",count($weighttitles)));

                $this->AddEmpties($rows);
            }
        }


        $this->AbsencesTitleRows($rows,$edit,$tedit,$disc);
        $this->MarksTitleRows($rows,$edit,$tedit,$disc);
        $this->ResultTitleRows($rows,$edit,$tedit,$disc);


        $rows[0]=$this->B($rows[0]);
        if (!$this->PerDisc)
        {
            array_pop($rows);
        }

        return array_reverse($rows);
    }

 

}

?>