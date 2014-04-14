<?php

class ClassesHandle extends ClassesDayly
{
    //*
    //* function ClassHtmlInfoTable, Parameter list: $class=array()
    //*
    //* Genmrates info table for class.
    //*

    function ClassHtmlInfoTable($class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $teacher="-";
        if (!empty($class[ "Teacher_Name" ]))
        {
            $teacher=$class[ "Teacher_Name" ];
        }

        return array
        (
           array
           (
              $this->B("Escola:"),
              $this->ApplicationObj->School[ "Name" ]
           ),
           array
           (
              $this->B("Turma:"),
              $class[ "NameKey" ]
           ),
           array
           (
              $this->B("Grade:"),
              $this->ApplicationObj->Grade[ "Name" ]
           ),
           array
           (
              $this->B("Período:"),
              $this->ApplicationObj->Period[ "Name" ]
           ),
           array
           (
              $this->B("Turno:"),
              $this->GetEnumValue("Shift",$class)
           ),
           array
           (
              $this->B("Professor da Turma:"),
              $teacher
           ),
           array
           (
              $this->B("No. de Alunos:"),
              $class[ "NStudents" ]." (".$class[ "NInactive" ].")" 
           ),
           array("",""),
        );
    }


    //*
    //* function HandleClass, Parameter list: $echo=TRUE
    //*
    //* Central Classes Handler.
    //*

    function HandleClass($echo=TRUE)
    {
        $this->UpdateSubTablesStructure($this->ItemHash,"Classes");

        $edit=1;
        $tedit=1;
        if (!preg_match('/^(Clerk|Admin|Secretary)$/',$this->ApplicationObj->Profile))
        {
            $edit=0;
            $tedit=0;
        }

        $action=$this->GetGET("Action");
        if (preg_match('/^Disc$/',$action,$matches))
        {
            $this->ApplicationObj->ClassDiscsObject->EditDisc($edit,$tedit);
        }
        elseif (preg_match('/^DeleteDisc$/',$action,$matches))
        {
            $this->ApplicationObj->ClassDiscsObject->DeleteDisc();
        }
        elseif (preg_match('/^(Student)?$/',$action))
        {
            $this->ApplicationObj->ClassStudentsObject->DisplayTable($edit,$tedit);
        }
        elseif (preg_match('/^(Student|Disc)(Marks|Absences|Totals|Print)?$/',$action,$matches))
        {
            $this->ApplicationObj->ClassDiscsObject->DisplayTable($matches[1],$matches[2],$edit,$tedit);
        }
        elseif (preg_match('/^Results/',$action))
        {
            $this->ApplicationObj->ClassStatusObject->ClassResults();
        }
        elseif (preg_match('/^Status$/',$action))
        {
            $this->ApplicationObj->ClassStatusObject->ClassStatusTable();
        }
        elseif (preg_match('/^Prints$/',$action))
        {
            $this->HandleClassPrintDaylies();
        }
        elseif (preg_match('/^StudentsPrints$/',$action))
        {
            $this->ApplicationObj->ClassStudentsObject->HandleClassStudentsPrints();
        }
        elseif (preg_match('/^Daylies$/',$action))
        {
            $this->HandleClassDaylies();
        }
        elseif (preg_match('/^DayliesPrints$/',$action))
        {
            $this->HandleClassDayliesPrints();
        }
        elseif (preg_match('/^DiscDaylies$/',$action))
        {
            $this->ApplicationObj->ClassDiscsObject->HandleDiscDaylies();
        }
        elseif (preg_match('/^DaylyTeacher$/',$action))
        {
            $this->HandleDaylyTeacher();
        }
        elseif (preg_match('/^Dayly/',$action))
        {
            $this->ApplicationObj->ClassDiscsObject->HandleDayly();
        }
        else
        {
            if (preg_match('/^(Weights|NLessons|Hours)/',$action))
            {
                $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs();
               if (preg_match('/^Weights/',$action))
                {
                    $this->ApplicationObj->ClassDiscWeightsObject->ShowClassDiscsWeights($edit);
                }
                elseif (preg_match('/^NLessons/',$action))
                {
                    $this->ApplicationObj->ClassDiscNLessonsObject->ShowClassDiscsNLessons($edit);
                }
                elseif (preg_match('/^Hours$/',$action))
                {
                    $this->ApplicationObj->ClassDiscLessonsObject->ShowClassDiscsLessons($edit);
                }
            }
            elseif (preg_match('/^PrintStudents$/',$action))
            {
                $this->ApplicationObj->ClassStudentsObject->PrintClassStudents();
            }
            elseif (preg_match('/^(Edit)?Students$/',$action))
            {
                $this->ApplicationObj->ClassStudentsObject->ShowClassStudents();
            }
            /* elseif (preg_match('/^StudentsPrints$/',$action)) */
            /* { */
            /*     $this->ApplicationObj->ClassStudentsObject->ShowClassStudents(TRUE); */
            /* } */
            elseif (preg_match('/^(Edit)?Discs$/',$action))
            {
                $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs();
                $this->ApplicationObj->ClassDiscsObject->ShowClassDiscs();
            }
            elseif (preg_match('/^(Edit)?Teachers$/',$action))
            {
                $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs();
                $this->ApplicationObj->ClassDiscsObject->ShowClassDiscsTeachers();
            }
        }
    }


}

?>