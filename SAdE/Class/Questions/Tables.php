<?php


class ClassQuestionsTables extends Common
{
    //*
    //* function ReadQuestionaries, Parameter list: $class,$forceread=FALSE
    //*
    //* Reads Questionaries into $this->Questions.
    //*

    function ReadQuestionaries($class,$forceread=FALSE)
    {
        if (empty($this->Questions) || $forceread)
        {
            $this->Questions=$this->ApplicationObj->GradeQuestionariesObject->ReadGradeQuestionaries($class);
        }
    }

    //*
    //* function ReadQuestion, Parameter list: $class,$question,$student,,$n
    //*
    //* Returns registered value, consulting POST, if allowed: $edit=1. 
    //*

    function ReadQuestion($class,$question,$student,$n)
    {
        $item=$this->QuestionSqlWhere($class,$question,$student,$n);

        $res=$this->SelectUniqueHash
        (
           "",
           $item,
           TRUE,
           array()
        );

        if (empty($res)) { $item[ "Value" ]=$this->ItemData[ "Value" ][ "Default" ]; }
        else             { $item=$res; }

        return $item;
    }

    //*
    //* function QuestionaryTable, Parameter list: $class,$student,$questionaire,$edit=0,$tedit=0
    //*
    //* Creates one student table, questions form.
    //*

    function QuestionaryTable($class,$student,$questionaire,&$table,$edit=0,$tedit=0)
    {        
        array_push
        (
           $table,
           $this->QuestionTitleRow($class,$student,$questionaire)
        );

        foreach ($questionaire[ "Questions" ] as $question)
        {
            array_push
            (
               $table,
               $this->QuestionRow($class,$student,$question,$edit,$tedit)
            );
        }

        if ($edit==1) { array_push($table,array($this->Buttons())); }
     }

    //*
    //* function QuestionariesTable, Parameter list: $class,$student,$edit=0,$tedit=0
    //*
    //* Creates one student table, questionaries form.
    //*

    function QuestionariesTable($class,$student,$edit=0,$tedit=0)
    {        
        $this->ReadQuestionaries($class);

        $table=array();
        $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs($class);
        if ($class[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
        {
            $disc=array();
            if (!empty($this->ApplicationObj->Disc)) { $disc=$this->ApplicationObj->Disc; }
            if (empty($disc)) { $disc=$this->ApplicationObj->Discs[0]; }

            $row=array("",$this->MultiCell("Trimestre:",1,"r"));

            for ($n=1;$n<=$class[ "NAssessments" ];$n++)
            {
                $cell=$this->Latins[ $n ];
                if ($this->LatexMode) { $cell="\\small{".$cell."}"; }

                array_push
                (
                   $row,
                   $this->B($cell)
                );
            }

            if ($this->ApplicationObj->ClassDiscsObject->ShowAbsencesTotals)
            {
                array_push($row,$this->B($this->ApplicationObj->Sigma));
            }

            if ($this->ApplicationObj->ClassDiscsObject->ShowAbsencesPercent)
            {
                array_push($row,$this->B($this->ApplicationObj->Percent));
            }

            array_push
            (
               $table,
               $row,
               array_merge
               (
                  array("",$this->MultiCell("Aulas Dadas:",1,"r")),
                  $this->ApplicationObj->ClassDiscNLessonsObject->NLessonsRow
                  (
                     $edit,
                     $class,
                     $disc
                  )
               ),
               array_merge
               (
                  array("",$this->MultiCell("Faltas:",1,"r")),
                  $this->ApplicationObj->ClassAbsencesObject->AbsencesRow
                  (
                     $edit,
                     $class,
                     $disc,
                     $student
                  )
               )
           );
        }

        foreach ($this->Questions as $questionaire)
        {
            $this->QuestionaryTable
            (
               $class,
               $student,
               $questionaire,
               $table,
               $edit,$tedit
            );
        }



        return $table;
     }

}

?>