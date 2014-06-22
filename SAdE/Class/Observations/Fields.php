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
    //* function ResponsibleObservationCGIField, Parameter list: $class,$student,$n
    //*
    //* Returns name of CGI field, associated with  
    //*

    function ResponsibleObservationCGIField($class,$student,$n)
    {
        return join
        (
           "_",
           array
           (
              "Responsible",
              $student[ "StudentHash" ][ "ID" ],
              $n
           )
        );
    }



    //*
    //* function ObservationField, Parameter list: $class,$student,$edit=0,$tedit=0,$weight=8,$height=2.5
    //*
    //* Creates the row with question, one for each 
    //*

    function ObservationField($class,$student,$n,$edit=0,$tedit=0,$weight=8,$height=2.5)
    {        
        $data="Value";
        $item=$this->ReadObservation($class,$student,$n);

        $item[ $data ]=preg_replace('/^\s/',"",$item[ $data ]);
        $item[ $data ]=preg_replace('/\s$/',"",$item[ $data ]);

        $field=$this->MakeField($edit,$item,$data,TRUE);
        if (!preg_match('/\S/',$item[ $data ]) && $this->LatexMode())
        {
            $field="\\mbox{\\begin{minipage}[t]{".$weight."cm}\\hspace{1cm}\\vspace{".$height."cm}\\end{minipage}}\n";
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
    //* function ResponsibleObservationField, Parameter list: $class,$student,$edit=0,$tedit=0,$weight=8,$height=2.5
    //*
    //* Creates the row with question, one for each 
    //*

    function ResponsibleObservationField($class,$student,$n,$edit=0,$tedit=0,$weight=8,$height=2.5)
    {        
        $data="ResponsibleValue";
        $item=$this->ReadObservation($class,$student,$n);

        $item[ $data ]=preg_replace('/^\s/',"",$item[ $data ]);
        $item[ $data ]=preg_replace('/\s$/',"",$item[ $data ]);

        if ($edit!=1) { return $item[ $data ]; }

        $field=$this->MakeField($edit,$item,$data,TRUE);
        if (!preg_match('/\S/',$item[ $data ]) && $this->LatexMode())
        {
            $field="\\mbox{\\begin{minipage}[t]{".$weight."cm}\\hspace{1cm}\\vspace{".$height."cm}\\end{minipage}}\n";
        }

        return preg_replace
        (
           '/NAME=[\'"](\S+)[\'"]/',
           "NAME='".
           $this->ResponsibleObservationCGIField($class,$student,$n).
           "' TABINDEX='".$n."'",
           preg_replace('/<BR>/i',"",$field)
        );
    }

}

?>