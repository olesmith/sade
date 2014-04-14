<?php


class ClassDiscContentsTitles extends ClassDiscContentsRow
{

    //*
    //* function DaylyContentsContentRow, Parameter list: 
    //*
    //* Generates content row for $content.
    //*

    function DaylyContentsContentTitles($edit)
    {
        $titles=array_merge
        (
           array("No."),
           $this->ApplicationObj->DatesObject->GetDataTitles($this->DaylyDateDatas)
        );

        $titles1=array();
        if ($edit==1)
        {
            array_push($titles1,"Deletar");
        }

        return $this->B
        (
           array_merge
           (
              $titles,
              $this->GetDataTitles($this->DaylyContentDatas),
              $titles1
           )
        );
    }
}

?>