<?php


class ClassDiscsStatusTableSearchRows extends ClassDiscsStatusTableCells
{
    //*
    //* function DiscsStatusTableSearchRows, Parameter list: 
    //*
    //* Generates lines with include-checkboxes for
    //* trimesters, data per semester, etc.
    //* 
    //*

    function DiscsStatusTableSearchRows()
    {
        return array_merge
        (
           array($this->DiscsStatusTableTrimestersSearchRow()),
           $this->DiscsStatusTableTrimesterSearchRows()
        );
    }

    //*
    //* function DiscsStatusTableTrimestersSearchRow, Parameter list: 
    //* 
    //* Creates row in search table with Trimester select boxes.
    //*

    function DiscsStatusTableTrimestersSearchRow()
    {
        $row=array($this->B("Incluir Trimestre(s):"));

        $cells=array();
        for ($n=1;$n<=$this->ApplicationObj->Period[ "NPeriods" ];$n++)
        {
            $cgivar="Trimester_".$n;

            $val=$this->GetPOST($cgivar);

            if ($val==1) { $val=TRUE; }
            else         { $val=FALSE; }

            array_push
            (
               $cells,
               $this->B($this->Latins[ $n ]).": ".
               $this->MakeCheckBox($cgivar,1,$val)
            );
        }

        array_push($row,join(" - ",$cells),"","");

        return $row;
    }

    //*
    //* function DiscsStatusTableTrimesterSearchDataRow, Parameter list: 
    //* 
    //* Creates row in search table with Trimester data select boxes.
    //*

    function DiscsStatusTableTrimesterSearchDataRow()
    {
        $searchpressed=$this->SearchPressed();

        $row=array();
        foreach (array_keys($this->DiscStatusTrimesterData) as $data)
        {
            array_push
            (
               $row,
               $this->DiscsStatusTableTrimesterDataTitle(1,$data),
               $this->TrimesterSearchDataCell($data),
               ""
            );
        }

        return $row;
    }

    //*
    //* function DiscsStatusTableTrimesterSearchExtendedRow, Parameter list: 
    //* 
    //* Creates row in search table with Trimester extended data select boxes.
    //*

    function DiscsStatusTableTrimesterSearchExtendedRow()
    {
        $searchpressed=$this->SearchPressed();

        $row=array();
        foreach (array_keys($this->DiscStatusTrimesterExtended) as $data)
        {
            array_push
            (
               $row,
               $this->DiscsStatusTableTrimesterExtendedTitle(1,$data),
               $this->TrimesterSearchExtendedCell($data,$searchpressed),
               ""
            );
        }

        return $row;
    }

    //*
    //* function DiscsStatusTableTrimesterSearchRows, Parameter list: 
    //* 
    //* Creates row in search table with Trimester data select boxes.
    //*

    function DiscsStatusTableTrimesterSearchRows()
    {
        return array
        (
           array
           (
              $this->B("Incluir Dados:"),
              $this->HtmlTable
              (
                 "",
                 array
                 (
                    $this->DiscsStatusTableTrimesterSearchDataRow(),
                    $this->DiscsStatusTableTrimesterSearchExtendedRow()
                 ),
                 array(),
                 array(),
                 array(),
                 FALSE,FALSE
              )
           )
        );
    }
}

?>