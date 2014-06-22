<?php



class StudentsRemanageTrimesters extends StudentsRemanageTrimester
{
    //*
    //* function StudentDiscTrimestersAbsences, Parameter list: $disc
    //*
    //* Returns number of $student absences, all trimesters.
    //* 
    //*

    function StudentDiscTrimestersAbsences($disc)
    {
        $nabsences=0;
        for ($trimester=1;$trimester<=$this->ApplicationObj->Class[ "NAssessments" ];$trimester++)
        {
            $nabsences+=$this->StudentDiscTrimesterAbsences($disc,$trimester);
        }

        return $nabsences;
    }

    //*
    //* function StudentDiscTrimestersAbsencesField, Parameter list: $disc
    //*
    //* Returns number of $student absences, all trimesters.
    //* 
    //*

    function StudentDiscTrimestersAbsencesField($disc)
    {
        return $this->MakeInput
        (
           "Absences",
           $this->StudentDiscTrimestersAbsences($disc),
           1
        );
    }

    //*
    //* function StudentDiscTrimestersMarks, Parameter list: $disc
    //*
    //* Returns $student marks, all trimesters.
    //* 
    //*

    function StudentDiscTrimestersMarks($disc)
    {
        $marks=array();
        for ($trimester=1;$trimester<=$this->ApplicationObj->Class[ "NAssessments" ];$trimester++)
        {
            array_push
            (
               $marks,
               $this->StudentDiscTrimesterMark($disc,$trimester)
            );
        }

        return $marks;
    }

    //*
    //* function StudentDiscTrimestersMarkFields, Parameter list: $disc
    //*
    //* Returns $student marks, all trimesters.
    //* 
    //*

    function StudentDiscTrimestersMarkFields($disc)
    {
        $marks=array();
        for ($trimester=1;$trimester<=$this->ApplicationObj->Class[ "NAssessments" ];$trimester++)
        {
            array_push
            (
               $marks,
               $this->StudentDiscTrimesterMarkField($disc,$trimester)
            );
        }

        return $marks;
    }

    //*
    //* function StudentDiscTrimestersMarksTitles, Parameter list:
    //*
    //* Returns number of $student absences, all trimesters.
    //* 
    //*

    function StudentDiscTrimestersMarksTitles()
    {
        $titles=array();
        for ($trimester=1;$trimester<=$this->ApplicationObj->Class[ "NAssessments" ];$trimester++)
        {
            array_push
            (
               $titles,
               $this->SUB("N",$trimester)
            );
        }

        return $titles;
    }


    //*
    //* function TrimestersNCols, Parameter list: 
    //*
    //* Returns number of columns spanned by trimesters.
    //* 
    //*

    function TrimestersNCols()
    {
        return $this->ApplicationObj->Class[ "NAssessments" ]*$this->TrimesterNCols()+1;
    }

    //*
    //* function TrimestersColsTitles, Parameter list: &$titles
    //*
    //* Generate Title rows cells, pertaining to Trimester.
    //* 
    //*

    function TrimestersColsTitles(&$titles)
    {
        array_push
        (
           $titles[0],
           $this->MultiCell
           (
              "Trimestres",
              $this->TrimestersNCols()
           )
        );

        for ($trimester=1;$trimester<=$this->ApplicationObj->Class[ "NAssessments" ];$trimester++)
        {
            $this->TrimesterColsTitles($titles,$trimester);
        }

        array_push
        (
           $titles[1],
           ""
        );
        array_push
        (
           $titles[2],
           $this->B($this->ApplicationObj->Sigma."F")
        );
    }

    //*
    //* function TrimestersCols, Parameter list: $disc
    //*
    //* Generates $disc trimesters cells.
    //* 
    //*

    function TrimestersCols($disc)
    {
        $row=array();

        for ($trimester=1;$trimester<=$this->ApplicationObj->Class[ "NAssessments" ];$trimester++)
        {
            $row=array_merge
            (
               $row,
               $this-> TrimesterCols($disc,$trimester)
            );
        }

        array_push
        (
           $row,
           $this->StudentDiscTrimestersAbsences($disc)
        );
        return $row;
    }


    //*
    //* function StudentDiscsOriginTrimestersTable, Parameter list:
    //*
    //* Generates origin student discs TrimestersTable.
    //* 
    //*

    function StudentDiscsOriginTrimestersTable()
    {
        $titles=array_merge
        (
           array("No.","Disciplina","Faltas"),
           $this->StudentDiscTrimestersMarksTitles()
        );

        $table=array($this->B($titles));
        $n=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $row=array
            (
               $this->B($n++),
               $this->B($disc[ "Name" ]),
               $this->StudentDiscTrimestersAbsences($disc),
            );

            $row=array_merge
            (
               $row,
               $this->StudentDiscTrimestersMarks($disc)
            );

            array_push($table,$row);
        }

        return $this->FrameIt
        (
           $this->Html_Table
           (
              "",
              $table,
              array(),
              array(),
              array(),
              FALSE,
              FALSE
           )
        );
    }

    //*
    //* function StudentDiscsDestinationTrimestersTable, Parameter list:
    //*
    //* Generates destination student discs TrimestersTable.
    //* 
    //*

    function StudentDiscsDestinationTrimestersTable()
    {
        $titles=array_merge
        (
           array("No.","Disciplina","Faltas"),
           $this->StudentDiscTrimestersMarksTitles()
        );

        $table=array($this->B($titles));
        $n=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $row=array
            (
               $this->B($n++),
               $this->B($disc[ "Name" ]),
               $this->StudentDiscTrimestersAbsencesField($disc),
            );

            $row=array_merge
            (
               $row,
               $this->StudentDiscTrimestersMarkFields($disc)
            );

            array_push($table,$row);
        }

        return $this->FrameIt
        (
           $this->Html_Table
           (
              "",
              $table,
              array(),
              array(),
              array(),
              FALSE,
              FALSE
           )
        );
    }

}

?>