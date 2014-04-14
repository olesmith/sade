<?php

class ClassesPrintsPrintSpecTable extends ClassesSchedule
{
    var $DaylyKey="Dayly";
    var $MarksKey="Marks";
    var $SignaturesKey="Signatures";


    //*
    //* function ClassDayliesPrintDiscMonthCell, Parameter list: $disc,$montid
    //*
    //* Generates info table for class.
    //*

    function ClassDayliesPrintDiscMonthKey($discid,$monthid)
    {
        return $this->DaylyKey."_".$monthid."_".$discid;
    }


    //*
    //* function ClassDayliesPrintDiscMonthCell, Parameter list: $disc,$montid
    //*
    //* Generates info table for class.
    //*

    function ClassDayliesPrintDiscMonthCell($disc,$monthid)
    {
        $discid=$disc;
        if (is_array($disc)) { $discid=$disc[ "ID" ]; }

        $key=$this->ClassDayliesPrintDiscMonthKey($discid,$monthid);
        return $this->MakeCheckBox($key,1,FALSE);
    }





    //*
    //* function ClassDayliesPrintPeriodRow, Parameter list: $class=array()
    //*
    //* Generates info table for class.
    //*

    function ClassDayliesPrintPeriodRow($months,$class=array())
    {
        $row=array();
        $keys=array_keys($months);
        $month=$months[ $keys[0] ];
        $comps=preg_split('/\//',$month);
        $mon=$comps[0];
        $year=$comps[1];

        $text="Meses";
        if (count($months)==12)
        {
            $text="Ano de ".$year;
        }
        elseif (count($months)==12)
        {
            $text="";
            if ($mon<7) { $text.="1"; }
            else        { $text.="2"; }

            $text.=" Semestre de ".$year;
        }

        return array
        (
           $this->MultiCell("",3),
           $this->MultiCell($text,count($months)),
           $this->MultiCell("",2),
        );
   }





    //*
    //* function ClassDayliesPrintTitleRow, Parameter list: $months
    //*
    //* Generates title row for $months
    //*

    function ClassDayliesPrintMonthTitleRow($months)
    {
        $row=$this->B(array("","","Todos<BR>os<BR>Meses"));
        foreach ($months as $id => $month)
        {
            $monthname=preg_replace('/\/\d\d\d\d/',"",$month);
            $monthname=intval($monthname);
            $monthname=$this->Months_Short[ $monthname-1 ];
            array_push($row,preg_replace('/\/\d\d\d\d/',"",$monthname));
        }

        array_push($row,"Ficha<BR>de<BR>Notas","Lista<BR>de<BR>Presença");
        return $this->B($row);
    }



    //*
    //* function ClassDayliesPrintTitleRow, Parameter list: $disc,$months
    //*
    //* Generates info table for class.
    //*

    function ClassDayliesPrintDiscRow($n,$disc,$months)
    {
        $row=$this->B(array(sprintf("%02d",$n),$disc[ "Name" ]));

        //$key="$this->Dayly_Key_All_".$disc[ "ID" ];
        $key=$this->ClassDayliesPrintDiscMonthKey($disc[ "ID" ],"All");

        array_push
        (
           $row,
           $this->MakeCheckBox($key,1,FALSE)
        );

        foreach ($months as $monthid => $month)
        {
            array_push
            (
               $row,
               $this->ClassDayliesPrintDiscMonthCell($disc,$monthid)
            );
        }

        $key=$this->MarksKey."_".$disc[ "ID" ];
        array_push
        (
           $row,
           $this->MakeCheckBox($key,1,FALSE)
        );

        $key=$this->SignaturesKey."_".$disc[ "ID" ];
        array_push
        (
           $row,
           $this->MakeCheckBox($key,1,FALSE)
        );
        return $row;
    }

    //*
    //* function ClassDayliesPrintAllDiscsRow, Parameter list: $months
    //*
    //* Generates info table for class.
    //*

    function ClassDayliesPrintAllDiscsRow($months)
    {
        $row=array("No.",$this->B("Todos as Disciplinas"));

        $key=$this->ClassDayliesPrintDiscMonthKey("All","All");
        array_push
        (
           $row,
           $this->MakeCheckBox($key,1,FALSE)
        );

        foreach ($months as $monthid => $month)
        {
            $key=$this->ClassDayliesPrintDiscMonthKey("All",$monthid);
            array_push
            (
               $row,
               $this->MakeCheckBox($key,1,FALSE)
            );
        }

        $key="Marks_All";
        array_push
        (
           $row,
           $this->MakeCheckBox($key,1,FALSE)
        );

        $key="Signatures_All";
        array_push
        (
           $row,
           $this->MakeCheckBox($key,1,FALSE)
        );

        return $row;
    }

    //*
    //* function ClassDayliesPrintTable, Parameter list: 
    //*
    //* Generates info table for class.
    //*

    function ClassDayliesPrintTable()
    {
        $this->ApplicationObj->ClassDiscsObject->ReadClassDiscs();
        $months=$this->ApplicationObj->PeriodsObject->MonthNames($this->ApplicationObj->Period);


        $rtable=array();

        array_push
        (
           $rtable,
           $this->ClassDayliesPrintPeriodRow($months),
           $this->ClassDayliesPrintMonthTitleRow($months),
           $this->ClassDayliesPrintAllDiscsRow($months)
        );

        $n=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            array_push
            (
               $rtable,
               $this->ClassDayliesPrintDiscRow($n++,$disc,$months)
            );
        }

        return $rtable;
    }
}

?>