<?php


class ClassDiscNLessonsImport extends Unique
{
    //*
    //* function ImportDiscNLessons, Parameter list: $file,$class,$disc,&$table
    //*
    //* Importa number of lessons given for $disc.. 
    //* 
    //*

    function ImportDiscNLessons($file,$class,$disc,&$table)
    {
        //Totals, when $disc is empty
        if (empty($disc))
        {
            $disc[ "Name" ]="Totals";
            $disc[ "Chave" ]="Totals";
            $disc[ "ID" ]=0;
        }

        if (file_exists($file))
        {
            array_push($table,array("Reading from Faltas file: ".$file)); 
            $lines=file($file);
            $line=preg_grep('/^Total/',$lines);
            if (!empty($line))
            {
                $line=array_pop($line);
                $comps=preg_split('/\s+/',$line);

                for ($n=1;$n<=$class[ "NAssessments" ];$n++)
                {
                    $nlessons=0;
                    if (!empty($comps[$n])) { $nlessons=$comps[$n]; }

                    $where=array
                    (
                       "Class" => $class[ "ID" ],
                       "ClassDisc" => $disc[ "ID" ],
                       "Assessment" => $n,
                    );

                    $hash=$where;
                    $hash[ "NLessons" ]=$nlessons;

                    $msg=$this->AddOrUpdate("",$where,$hash);

                    //print
                    array_push
                    (
                       $table,
                       array
                       (
                          "",
                          "",
                          "",
                          "No. of Lessons, ".$disc[ "ID" ],
                          $msg,
                          $hash[ "Assessment" ].": ".$hash[ "NLessons" ]
                       )
                    );
                }
            }
        }
        else { array_push($table,array("Faltas file: ".$file." non-existent")); }
    }
}

?>