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
    //* function ObservationCGIField, Parameter list: $class,$student,$n
    //*
    //* Returns name of CGI field, associated with  
    //*

    function ObservationCGIField($class,$student,$n)
    {
        return join
        (
           "_",
           array
           (
              "Observation",
              $student[ "StudentHash" ][ "ID" ],
              $n
           )
        );
    }



    //*
    //* function ObservationField, Parameter list: $class,$student,$edit=0,$tedit=0
    //*
    //* Creates the row with question, one for each 
    //*

    function ObservationField($class,$student,$n,$edit=0,$tedit=0)
    {        
        $item=$this->ReadObservation($class,$student,$n);

        $item[ "Value"]=preg_replace('/^\s/',"",$item[ "Value"]);
        $item[ "Value"]=preg_replace('/\s$/',"",$item[ "Value"]);

        if ($edit!=1) { return $item[ "Value"]; }

        $field=$this->MakeField($edit,$item,"Value",TRUE);
        if (!preg_match('/\S/',$field) && $this->LatexMode)
        {
            $field="\\mbox{\\begin{minipage}[t]{10cm}\\hspace{1cm}\\vspace{4cm}\\end{minipage}}\n";
        }

        return preg_replace
        (
           '/NAME=[\'"](\S+)[\'"]/',
           "NAME='".
           $this->ObservationCGIField($class,$student,$n).
           "' TABINDEX='".$n."'",
           preg_replace('/<BR>/i',"",$field)
        );
    }

     //*
    //* function ObservationsTable, Parameter list: $class,$student,$questionaire,$edit=0,$tedit=0
    //*
    //* Creates one student questions table.
    //*

    function ObservationsTable($class,$student,$edit=0,$tedit=0,$plural=FALSE)
    {        
        $table=array();
        if (!$plural) { array_push($table,$this->B(array("Trimestre:","Observações:"))); }

        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            array_push
            (
               $table,
               array
               (
                  $this->Latins[ $n ]."º:",
                  $this->ObservationField($class,$student,$n,$edit,$tedit)
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