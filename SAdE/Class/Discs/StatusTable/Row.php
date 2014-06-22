<?php


class ClassDiscsStatusTableRow extends ClassDiscsStatusTableTrimesters
{
    //*
    //* function DiscsStatusTableRows, Parameter list: $n,$disc
    //*
    //* Creates Discs status table $disc row.
    //* 
    //*

    function DiscsStatusTableRows($n,$disc)
    {
        $row=array($this->B($n));
        $empty=array("");

        if (count($this->DiscStatusActions)>0)
        {
            $actions=array();
            foreach ($this->DiscStatusActions as $action)
            {
                array_push
                (
                   $actions,
                   $this->ActionEntry($action,$disc)
                );
            }

            array_push($row,join("",$actions)); 
            array_push($empty,""); 

        }

        foreach ($this->DiscStatusData as $data)
        {
            array_push
            (
               $row,
               $this->MakeField(0,$disc,$data,TRUE)
            );
            array_push($empty,""); 
        }

        $rows=array();
        for ($trimester=1;$trimester<=$this->ApplicationObj->Period[ "NPeriods" ];$trimester++)
        {
            if ($this->GetPOST("Trimester_".$trimester)==1)
            {
                array_push
                (
                   $row,
                   $this->B($this->Latins[ $trimester ])
                );
                $this->DiscsStatusTableTrimesterRow($row,$disc,$trimester);
                array_push($rows,$row);

                $row=$empty;
            }
        }

        if (empty($rows))
        {
            array_push($row,"Nenhum Trimestre indicado...");
            array_push($rows,$row);
        }

        return $rows;
    }
}

?>