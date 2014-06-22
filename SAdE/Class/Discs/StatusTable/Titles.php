<?php


class ClassDiscsStatusTableTitles extends ClassDiscsStatusTableRow
{
    //*
    //* function DiscsStatusTableTitles, Parameter list:
    //*
    //* Creates Discs status table titles.
    //* 
    //*

    function DiscsStatusTableTitles()
    {
        $titles=array();

        $ncols=count($this->DiscStatusData)+1;
        if (count($this->DiscStatusActions)>0) { $ncols++; }

        array_push($titles,"No.","");

        foreach ($this->DiscStatusData as $data)
        {
            array_push
            (
               $titles,
               $this->GetDataTitle($data)
            );
        }


         array_push
        (
           $titles,
           "Trimestre"
        );

         $this->DiscsStatusTableTrimesterTitleCols($titles,1);

        return array($this->B($titles));
    }
}

?>