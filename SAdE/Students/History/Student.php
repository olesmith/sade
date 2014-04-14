<?php



class StudentsHistoryStudent extends StudentsHistoryRows
{
    //*
    //* function StudentClassHistoryActions, Parameter list: $student,$class,$grade,$gradeperiod,$rperiod
    //*
    //* Generates student history html table.
    //* 
    //*

    function StudentClassHistoryActions($student,$class,$grade,$gradeperiod,$rperiod,$classstudent)
    {
        $args=array
        (
           "ModuleName" => "Classes",
           "Action" => "StudentMarks",
           "Unit" => $this->ApplicationObj->Unit[ "ID" ],
           "School" => $this->ApplicationObj->School[ "ID" ],
           "Period" => $rperiod[ "ID" ],
           "Class" => $class[ "ID" ],
           "Student" => $classstudent[ "ID" ],
        );

        return
            preg_replace
            (
               '/#ClassStudent/',
               $classstudent[ "ID" ],
               $this->ActionEntry("StudentMarks",$args)
            ).
            preg_replace
            (
               '/#ClassStudent/',
               $classstudent[ "ID" ],
               $this->ActionEntry("StudentAbsences",$args)
            );
    }


    //*
    //* function StudentClassHistoryTable, Parameter list: &$table,$student,$class,$grade,$gradeperiod,$period
    //*
    //* Generates student history html table.
    //* 
    //*

    function StudentClassHistoryTable(&$table,$student,$class,$grade,$gradeperiod,$period)
    {
        $periodname=$this->ApplicationObj->GetPeriodName($period);

        $this->ApplicationObj->ClassMarksObject->SqlTable=
            $this->ApplicationObj->School[ "ID" ]."_".$periodname."_ClassMarks";
        $this->ApplicationObj->ClassStudentsObject->SqlTable=
            $this->ApplicationObj->School[ "ID" ]."_".$periodname."_ClassStudents";
        $this->ApplicationObj->ClassAbsencesObject->SqlTable=
            $this->ApplicationObj->School[ "ID" ]."_".$periodname."_ClassAbsences";

        $this->ApplicationObj->Discs=array();
        $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs($class);


        $classstudent=$this->ApplicationObj->ClassStudentsObject->SelectUniqueHash
        (
           "",
           array("Student" => $student[ "ID" ])
        );

        $this->StudentDiscHistoryTitles($table,$student,$periodname,$class,$grade,$gradeperiod,$period,$classstudent);


        $n=1;
        $nfieldstot=0;
        foreach($this->ApplicationObj->Discs as $disc)
        {
            $row=array();
            $nfields=$this->StudentDiscHistoryRow($row,$n,$student,$disc,$periodname,$class,$grade,$gradeperiod,$period);

            if ($nfields>=0)
            {
                array_push($table,$row);
            }

            $nfieldstot+=$nfields;
            $n++;
        }

        return $nfieldstot;
    }

    //*
    //* function StudentClassHistoryTitles, Parameter list: &$table,$student,$class,$grade,$gradeperiod,$rperiod
    //*
    //* Generates student history html table.
    //* 
    //*

    function StudentClassHistoryTitles(&$table,$n,$student,$class,$grade,$gradeperiod,$rperiod,$classstudent)
    {
        $titles=array
        (
           $n,
           $this->StudentClassHistoryActions
           (
              $student,
              $class,
              $grade,
              $gradeperiod,
              $rperiod,
              $classstudent
           ),
           $gradeperiod[ "Name" ],
           $rperiod[ "Name" ],
           $class[ "Name" ],
           $this->Center("Cadastros:")
        );

        array_push($table,$this->B($titles));
    }


}

?>