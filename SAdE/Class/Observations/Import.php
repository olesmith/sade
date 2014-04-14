<?php


class ClassObservationsImport extends Common
{
    //*
    //* function ImportStudentObservations, Parameter list: $class,$student,$dir,&$table
    //*
    //* Import $student observations.
    //*

    function ImportStudentObservations($class,$student,$dir,&$table)
    {
        $file=$dir."/Observations.".$student[ "StudentHash" ][ "UniqueID" ].".txt";

        if ($class[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
        {
            for ($n=1;$n<=$class[ "NAssessments" ];$n++)
            {
                $file=$dir."/Quest/".$student[ "StudentHash" ][ "UniqueID" ].".Observations.".($n-1).".txt";
                if (file_exists($file))
                {
                    $value=join("",$this->MyReadFile($file));
                    $where=array
                    (
                       "Class" => $class[ "ID" ],
                       "Student" => $student[ "StudentHash" ][ "ID" ],
                       "Assessment" => $n,
                       "Class" => $class[ "ID" ],
                    );
                    $newitem=$where;
                    $newitem[ "Value" ]=$value;

                    $msg=$this->AddOrUpdate("",$where,$newitem);
                    array_push
                    (
                       $table,
                       array
                       (
                          "",
                          "",
                          "",
                          "Import Observation ".$n.", ".$student[ "StudentHash" ][ "Name" ].": ",
                          $file,
                          $value
                       )
                    );
                }
            }

            return;
        }

        //Only one observation
        for ($n=$class[ "NAssessments" ];$n<=$class[ "NAssessments" ];$n++)
        {
            if (file_exists($file))
            {
                $value=join("",$this->MyReadFile($file));
                $where=array
                (
                   "Class" => $class[ "ID" ],
                   "Student" => $student[ "StudentHash" ][ "ID" ],
                   "Assessment" => $n,
                   "Class" => $class[ "ID" ],
                );

                $newitem=$where;
                $newitem[ "Value" ]=$value;

                $msg=$this->AddOrUpdate("",$where,$newitem);
                array_push
                (
                   $table,
                   array
                   (
                      "",
                      "",
                      "",
                      "Import Observation ".$n.", ".$student[ "StudentHash" ][ "Name" ].": ",
                      $file,
                      $value
                   )
                );
            }
         }
    }

    //*
    //* function ImportStudentsObservations, Parameter list: $class,$students,$dir,&$table
    //*
    //* Updates questions fields.
    //*

    function ImportStudentsObservations($class,$students,$dir,&$table)
    {
        foreach ($students as $student)
        {
            $this->ImportStudentObservations($class,$student,$dir,$table);
        }
    }
}

?>