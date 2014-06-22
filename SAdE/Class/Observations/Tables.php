<?php


class ClassObservationsTables extends ClassObservationsFields
{
    //*
    //* function ObservationSqlWhere, Parameter list: $class,$student,$n
    //*
    //* Returns sql where clause specifying student observation.
    //*

    function ObservationSqlWhere($class,$student,$n)
    {
        return array
        (
           "Class" => $class[ "ID" ],
           "Student" => $student[ "StudentHash" ][ "ID" ],
           "Assessment" => $n,
       );
    }




     //*
    //* function ObservationsTable, Parameter list: $class,$student,$questionaire,$edit=0,$tedit=0,$plural=FALSE,$weight=8,$height=2.5
    //*
    //* Creates one student questions table.
    //*

    function ObservationsTable($class,$student,$edit=0,$tedit=0,$plural=FALSE,$weight=8,$height=2.5)
    {        
        $table=array();
        if (!$plural) { array_push($table,$this->B(array("Trimestre:","Observações:","Observações do(a) Responsável:"))); }

        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            array_push
            (
               $table,
               array
               (
                  $this->Latins[ $n ]."º:",
                  $this->ObservationField($class,$student,$n,$edit,$tedit,$weight,$height),
                  $this->ResponsibleObservationField($class,$student,$n,$edit,$tedit,$weight,$height)
               )
            );
        }

        return $table;
    }

     //*
    //* function ObservationsHtmlTable, Parameter list: $class,$student,$questionaire,$edit=0,$tedit=0
    //*
    //* Creates one student questions html table.
    //*

    function ObservationsHtmlTable($class,$student,$edit=0,$tedit=0,$plural=FALSE)
    {        
        return $this->HtmlTable
        (
           "",
           $this->ObservationsTable($class,$student,$edit,$tedit,$plural),
           array(),
           array(),
           array(),
           FALSE,
           FALSE
        );
    }


}

?>