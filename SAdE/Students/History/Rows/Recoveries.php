<?php


class StudentsHistoryRecoveries extends StudentsHistoryRecovery
{
    //*
    //* function AddRecoveriesCells, Parameter list: &$row,$disc,$markshash
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddRecoveriesCells(&$row,$disc,$markshash)
    {
        for ($n=$disc[ "NAssessments" ]+1;$n<=$disc[ "NAssessments" ]+$disc[ "NRecoveries" ];$n++)
        {
            $this->AddRecoveryCells($row,$n,$disc,$markshash);
        }
    }

    //*
    //* function AddRecoveriesTitles, Parameter list: &$titles,$disc
    //*
    //* Generates student history html table.
    //* 
    //*

    function AddRecoveriesTitles(&$titles,$disc)
    {
        for ($n=1;$n<=$disc[ "NRecoveries" ];$n++)
        {
            $this->AddRecoveryTitles($titles,$n,$disc);
        }
    }

 
}

?>