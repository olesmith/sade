<?php


class ClassObservationsFields extends ClassObservationsImport
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

        $field=$this->MakeField($edit,$item,"Value",TRUE);
        if (!preg_match('/\S/',$field) && $this->LatexMode)
        {
            $field="\\mbox{\\begin{minipage}[t]{10cm}\\hspace{1cm}\\vspace{4cm}\\end{minipage}}\n";
        }

        return preg_replace
        (
           '/NAME=\'(\S+)\'/',
           "NAME='".
           $this->ObservationCGIField($class,$student,$n).
           "' TABINDEX='".$n."'",
           preg_replace('/<BR>/i',"",$field)
        );
    }
}

?>