<?php


class ClassDiscsStatusTableTrimesters extends ClassDiscsStatusTableTrimester
{


    //*
    //* function DiscsStatusTableTrimestersTitleCols, Parameter list: &$titles
    //*
    //* Creates titles columns of DiscsStatusTable, pertaining to Trimesters.
    //* 
    //*

    function DiscsStatusTableTrimestersTitleCols(&$titles)
    {
        for ($trimester=1;$trimester<=$this->ApplicationObj->Period[ "NPeriods" ];$trimester++)
        {
            if ($this->GetPOST("Trimester_".$trimester)==1)
            {
                $this->DiscsStatusTableTrimesterTitleCols($titles,$trimester);
            }
        }
    }



    //*
    //* function DiscsStatusTableTrimestersRow, Parameter list: &$row,$disc
    //*
    //* Creates titles columns of DiscsStatusTable, pertaining to Trimesters.
    //* 
    //*

    function DiscsStatusTableTrimestersRow(&$row,$disc)
    {
        for ($trimester=1;$trimester<=$this->ApplicationObj->Period[ "NPeriods" ];$trimester++)
        {
            if ($this->GetPOST("Trimester_".$trimester)==1)
            {
                $this->DiscsStatusTableTrimesterRow($row,$disc,$trimester);
            }
        }
    }
}

?>