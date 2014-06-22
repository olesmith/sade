<?php


class ClassDiscsStatusTableTrimester extends ClassDiscsStatusTableSearchRows
{
    //*
    //* function DiscsStatusTableTrimesterNCols, Parameter list: 
    //*
    //* Creates titles columns of DiscsStatusTable, pertaining to Trimesters.
    //* 
    //*

    function DiscsStatusTableTrimesterNCols()
    {
        return count($this->DiscStatusTrimesterData)+count($this->DiscStatusTrimesterExtended);
    }

    //*
    //* function DiscsStatusTableTrimesterDataTitle, Parameter list: $trimester,$data
    //*
    //* Return $trimester $data.$trimester entry.
    //* 
    //*

    function DiscsStatusTableTrimesterDataTitle($trimester,$data)
    {
        $key=$data.$trimester;

        $name=preg_replace
        (
           '/\s+[IVX]+$/',
           "",
           $this->GetRealNameKey($this->ItemData[ $key ],"ShortName")
        );

        $title=preg_replace
        (
           '/\s+[IVX]+$/',
           "",
           $this->GetRealNameKey($this->ItemData[ $key ],"Title")
        );

        return $this->B($name,array("TITLE" => $title));
    }

    //*
    //* function DiscsStatusTableTrimesterExtendedTitle, Parameter list: $trimester,$data
    //*
    //* Return $trimester extended $data title.
    //* 
    //*

    function DiscsStatusTableTrimesterExtendedTitle($trimester,$data)
    {
        return $this->B
        (
           $this->DiscStatusTrimesterExtended[ $data ][ "Name" ],
           array
           (
              "TITLE" => $this->DiscStatusTrimesterExtended[ $data ][ "Title" ],
            )
        );
    }

    //*
    //* function DiscsStatusTableTrimesterTitleCols, Parameter list: &$titles,$trimester
    //*
    //* Creates titles columns of DiscsStatusTable, pertaining to Trimesters.
    //* 
    //*

    function DiscsStatusTableTrimesterTitleCols(&$titles,$trimester)
    {
        foreach (array_keys($this->DiscStatusTrimesterData) as $data)
        {
            if ($this->IncludeSearchData($data))
            {
                array_push
                (
                   $titles,
                   $this->DiscsStatusTableTrimesterDataTitle($trimester,$data)
                );
            }
        }

        foreach (array_keys($this->DiscStatusTrimesterExtended) as $data)
        {
            if ($this->IncludeSearchExtended($data))
            {
                array_push
                (
                   $titles,
                   $this->DiscsStatusTableTrimesterExtendedTitle($trimester,$data)
               );
            }
        }
    }


    //*
    //* function DiscsStatusTableTrimesterRow, Parameter list: &$row,$disc,$trimester
    //*
    //* Creates titles columns of DiscsStatusTable, pertaining to Trimesters.
    //* 
    //*

    function DiscsStatusTableTrimesterRow(&$row,$disc,$trimester)
    {
        foreach (array_keys($this->DiscStatusTrimesterData) as $data)
        {
            if ($this->IncludeSearchData($data))
            {
                $key=$data.$trimester;
                array_push
                (
                   $row,
                   $this->MakeField(0,$disc,$key,TRUE)
                );
            }
        }

        foreach (array_keys($this->DiscStatusTrimesterExtended) as $data)
        {
            if ($this->IncludeSearchExtended($data))
            {
                $method=$this->DiscStatusTrimesterExtended[ $data ][ "Method" ];
                array_push
                (
                   $row,
                   $this->$method($disc,$trimester)
                );
            }
        }
    }
}

?>