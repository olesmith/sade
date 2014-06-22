<?php


class ClassStatusTitleRows extends ClassStatusUpdate
{
    //*
    //* function ClassStatusTableTitles, Parameter list: $class
    //*
    //* Genrates Class status table title row.
    //*

    function ClassStatusTableDiscTitles($class,$discs)
    {
        $row=array();

        $nrows=count($this->StudentDataTitles);
        //if (!$this->LatexMode()) { $nrows++; }

        array_push
        (
           $row,
           $this->MultiCell("",$nrows)
        );

        foreach ($this->ApplicationObj->Discs as $disc)
        {
            if (empty($discs[ $disc[ "ID" ] ])) { continue; }

            array_push
            (
               $row,
               $this->MultiCell($disc[ "NickName" ],count($this->StatusDataTitles))
            );
       }

        array_push($row,$this->B("Res."));
        return $row;
    }


    //*
    //* function ClassStatusTableDataTitles, Parameter list: $class
    //*
    //* Genrates Class status table title row.
    //*

    function ClassStatusTableDiscDataTitles($class,$discs)
    {
        $titles=$this->StatusDataTitles;
        if ($this->LatexMode())
        {
            foreach (array_keys($titles) as $id)
            {
                $titles[ $id ]=preg_replace('/%/',"\\%",$titles[ $id ]);

                //$titles[ $id ]="\\rotatebox{90}{".$titles[ $id ]."}";

                $titles[ $id ]="\\begin{scriptsize}".$titles[ $id ]."\\end{scriptsize}";
            }
        }

        $row=array();
        $row=array_merge($this->StudentDataTitles,$row);

        foreach ($this->ApplicationObj->Discs as $disc)
        {
            if (empty($discs[ $disc[ "ID" ] ])) { continue; }

            $row=array_merge($row,$titles);
        }


        array_push($row,"Final");
        return $this->B($row);
    }    
}

?>