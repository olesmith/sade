<?php


class ClassMarksTitleRows extends ClassMarksCalc
{
    //*
    //* function MarksTitles, Parameter list: $disc
    //*
    //* Generates marks titles.
    //*

    function MarksTitles($disc)
    {
        $row=array();
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
            {
                array_push($row,$this->Sub("N",$n));
            }
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowMarkSums)
        {
            array_push($row,$this->ApplicationObj->Sigma."N");
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowMarksTotals)
        {
            array_push($row,"M");
        }

        return $row;
    }

    //*
    //* function MarksTotalsTitles, Parameter list: $disc
    //*
    //* Generates marks totals titles.
    //*

    function MarksTotalsTitles($disc)
    {
        return array($this->ApplicationObj->Mu,"Res");
    }

    //*
    //* function RecoveryWeightsInputs, Parameter list: $disc=array(),$n
    //*
    //* Makes Rercovery mark weight input field.
    //*

    function RecoveryWeightsInputs($edit=0,$disc=array(),$n)
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $nn=$n+$disc[ "NAssessments" ];

        $cell="";
        if (isset($disc[ "Weights" ][ $nn-1 ][ "Weight" ]))
        {
            $cell=sprintf("%.1f",$disc[ "Weights" ][ $nn-1 ][ "Weight" ]);
        }

        if ($edit==1)
        {
            $cell=$this->MakeInput("Weight_".$nn,$cell,2);
        }

        return array
        (
           $cell,
           1.0,
           ""
        );
    }


    //*
    //* function ClassMarksHtmlTableTitles, Parameter list: $sdatas=array(),$sactions=array(),$disc=array(
    //*
    //* Makes Marks HTML table for discid $discid.
    //*

    function ClassMarksHtmlTableWeightsTitles($sdatas=array(),$sactions=array(),$disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $titles=array();
        foreach ($sdatas as $data) { array_push($titles,""); }
        foreach ($sactions as $data) { array_push($titles,""); }
        array_push($titles,"","");

        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            array_push
            (
               $titles,
               sprintf("%.1f",$disc[ "Weight".$n ])
            );
        }

        array_push
        (
           $titles,
           "","","",
           sprintf
           (
              "%.1f",
              $disc[ "Weight".($disc[ "NAssessments" ]+1) ]
           ),
           ""
        );
        for ($n=1;$n<=$disc[ "NRecoveries" ];$n++)
        {
            array_push
            (
               $titles,
               sprintf("%.1f",$disc[ "Weight".($n+$disc[ "NAssessments" ]) ])
            );
            array_push($titles,"","","","");
        }
        array_push($titles,"");

        return $this->B($titles);
    }

    //*
    //* function RecoveryTitles, Parameter list: $disc
    //*
    //* Generates recovery titles.
    //*

    function RecoveryTitles($disc,$n)
    {
        $row=array
        (
           $this->Sub("R",$n),
           $this->Sub("M",$n),
           $this->Sub("Res",$n),
        );
 
        return $row;
    }

    //*
    //* function FinalTitles, Parameter list: $disc
    //*
    //* Generates final titles.
    //*

    function FinalTitles($disc)
    {
        return array
        (
           $this->Sub("M","F"),
           $this->Sub("R","N"),
        );
    }

    //*
    //* function MarkWeightTitles, Parameter list: $disc
    //*
    //* 
    //*

    function MarkWeightTitles($disc)
    {
        $row=array();
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++) { array_push($row,$this->Sub("P",$n)); }

        if ($this->ApplicationObj->ClassDiscsObject->ShowMarkWeightsTotals)
        {
            array_push($row,$this->ApplicationObj->Sigma."P");
        }

        return $this->B($row);
    }























    //*
    //* function ClassMarksHtmlTableTitles, Parameter list: $actionobj,$sdatas=array(),$sactions=array(),$disc=array()
    //*
    //* Makes Marks HTML table for discid $discid.
    //*

    function ClassMarksHtmlTableTitles($actionobj,$sdatas=array(),$sactions=array(),$disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $titles=$actionobj->GetActionNames($sactions);
        $titles=array_merge($titles,$this->ApplicationObj->StudentsObject->GetDataTitles($sdatas));
        array_push($titles,"Disc. Status");
        array_unshift($titles,"No.");

        $totals=array_merge($titles,$this->MarksTitles($disc));
        $totals=array_merge($titles,$this->MarksTotalsTitles($disc));

        for ($n=1;$n<=$disc[ "NRecoveries" ];$n++)
        {
            $totals=array_merge($titles,$this->RecoveryTitles($disc,$n));
        }

        $totals=array_merge($titles,$this->FinalTitles($disc));

        return $this->B($titles);
    }


    //*
    //* function DiscStudentTitleRows, Parameter list: $type,$sdatas=array(),$sactions=array()
    //*
    //* Creates the Disc Student title Rows (2 rows).
    //*

    function DiscStudentTitleRows($obj,$fdatas=array(),$factions=array(),$disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        return array
        (
           $this->ApplicationObj->ClassMarksObject->ClassMarksHtmlTableWeightsTitles
           (
              $fdatas,
              $factions,
              $disc
           ),
           $this->ApplicationObj->ClassMarksObject->ClassMarksHtmlTableTitles
           (
              $obj,
              $fdatas,
              $factions,
              $disc
           ),
        );
    }


}

?>