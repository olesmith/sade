<?php


class ClassDiscAssessmentsTables extends Common
{
    //*
    //* function TrimesterTitle, Parameter list: $disc,$semester
    //*
    //* Generates a table, resuming number of assessments per semester.
    //*

    function TrimesterTitle($disc,$semester)
    {
        $name="Nota ".$semester;
        if ($semester>$disc[ "NAssessments" ]) { $name="Recuperação ".($semester-$disc[ "NAssessments" ]); }

        return $name.":";
    }

    //*
    //* function DaylyNAssessmentsTable, Parameter list: $disc=array(),$class=array()
    //*
    //* Generates a table, resuming number of assessments per semester.
    //*

    function DaylyNAssessmentsTable($disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $table=array($this->B(array("Trimester","No. de Avaliações")));
        foreach ($this->Assessments as $semester => $assessments)
        {
            $name=$semester;
            if ($semester>$disc[ "NAssessments" ]) { $name="Recuperação ".($semester-$disc[ "NAssessments" ]); }

            $row=array
            (
               $this->B
               (
                  $this->TrimesterTitle($disc,$semester)
               )
            );

            $nentries=$this->MySqlNEntries
            (
               "",
               array
               (
                  "Class" => $this->ApplicationObj->Class,
                  "Disc" => $this->ApplicationObj->Disc[ "ID" ],
                  "Semester" => $semester,
               )
            );

            $values=array(1,2,3,4,5,6,7,8,9,10);
            array_push
            (
               $row,
               $this->MakeSelectField
               (
                  "NAssessments_".$semester,
                  $values,
                  $values,
                  $nentries
               )
            );
            
            array_push($table,$row);
        }

        return $table;
    }

    //*
    //* function DaylyAssessmentsTable, Parameter list: $edit,$disc=array(),$class=array()
    //*
    //* Generates a table, resuming number of assessments per semester.
    //*

    function DaylyAssessmentsTable($edit,$disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $titles=array("Trimester");
        if ($edit==1) { array_push($titles,"Deletar"); }


        $titles=array_merge($titles,$this->AssessmentData);
        $titles=$this->GetDataTitles($titles);
        
        $table=array($this->B($titles));
        foreach ($this->Assessments as $semester => $assessments)
        {
            $row=array
            (
               $this->B
               (
                  $this->TrimesterTitle($disc,$semester)
               )
            );

            $nassessment=1;
            $sum=0.0;
            foreach ($assessments as $assessment)
            {
                $n=1;

                $deletebox="";
                if ($edit==1 && $nassessment>1)
                {
                    $deletebox=$this->MakeCheckBox("Delete_".$assessment[ "ID" ],1);

                }
                array_push($row,$deletebox);

                foreach ($this->AssessmentData as $data)
                {
                    array_push
                    (
                       $row,
                       preg_replace
                       (
                          '/NAME=[\'"]/',
                          "NAME='".$assessment[ "ID" ]."_",
                          $this->MakeDataField($data,$assessment,$assessment[ $data ],$n,TRUE)
                       )
                    );
                }

                $sum+=$assessment[ "MaxVal" ];

                array_push($table,$row);
                $nassessment++;

                $row=array("");
            }

            $sum=sprintf("%.1f",$sum);
            array_push($table,$this->B(array("","","",$this->ApplicationObj->Sigma,$sum)));
        }
        return $table;
    }
}

?>