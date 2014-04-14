<?php


class StudentsMatriculateTable extends StudentsMatriculateRow
{
   //*
    //* function MatriculaTable, Parameter list: 
    //*
    //* Generates student matricula table, one line per period matriculated.
    //* 
    //*

    function MatriculaTable()
    {
        $table=array($this->B(array("No.","Periodo","Turma","Dados Lançados","")));
        $n=1;
        foreach ($this->ApplicationObj->Periods as $period)
        {
            //Skip if not $student Matriculated in $period
            if ($this->ApplicationObj->PeriodsObject->StudentMatriculatedInPeriod($this->ItemHash,$period))
            {
                array_push($table,$this->MatriculaPeriodRow($period,$n));
                $n++;
            }
        }

        return $table;

    }

}

?>