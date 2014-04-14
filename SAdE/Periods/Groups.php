<?php


class PeriodsGroups extends PeriodsCalendar
{
    //*
    //* function GenerateDayliesDatesSingle, Parameter list: $edit,$item
    //*
    //* Generates the Daylies stat/end dates table.
    //*

    function GenerateDayliesDatesSingle($edit,$item)
    {
        $datas=$this->ItemDataSGroups[ "DayliesDates" ][ "Data" ];

        $nmax=$item[ "NPeriods" ];

        $datas=preg_grep('/[1-'.$nmax.']$/',$datas);

        return $this->ItemTable($edit,$item,0,$datas,array(),FALSE,FALSE,FALSE);
    }

    //*
    //* function GenerateDayliesDatesPlural, Parameter list: $edit
    //*
    //* Generates the Daylies stat/end dates table.
    //*

    function GenerateDayliesDatesPlural($edit)
    {
        $datas=$this->ItemDataGroups[ "DayliesDates" ][ "Data" ];

        $nmax=0;
        foreach ($this->ItemHashes as $item)
        {
            $nmax=$this->Max($nmax,$item[ "NPeriods" ]);
        }
 
        $rdatas=preg_grep('/^Daylies\d$/',$datas,PREG_GREP_INVERT);

        $datas=preg_grep('/[1-'.$nmax.']$/',$datas);
        $datas=array_merge($rdatas,$datas);

        return $this->ItemsTable("",$edit,$datas,array(),array(),$datas);
    }


    //*
    //* function GenerateDayliesLimitsSingle, Parameter list: $edit,$item
    //*
    //* Generates the Daylies stat/end dates table.
    //*

    function GenerateDayliesLimitsSingle($edit,$item)
    {
        $datas=$this->ItemDataSGroups[ "DayliesLimits" ][ "Data" ];

        $nmax=$item[ "NPeriods" ];

        $datas=preg_grep('/[1-'.$nmax.']$/',$datas);

        return $this->ItemTable($edit,$item,0,$datas,array(),FALSE,FALSE,FALSE);
    }


    //*
    //* function GenerateDayliesLimitsPlural, Parameter list: $edit
    //*
    //* Generates the Daylies stat/end dates table.
    //*

    function GenerateDayliesLimitsPlural($edit)
    {
        $datas=$this->ItemDataGroups[ "DayliesLimits" ][ "Data" ];

        $nmax=0;
        foreach ($this->ItemHashes as $item)
        {
            $nmax=$this->Max($nmax,$item[ "NPeriods" ]);
        }
 
        $rdatas=preg_grep('/^DayliesLimit\d$/',$datas,PREG_GREP_INVERT);

        $datas=preg_grep('/[1-'.$nmax.']$/',$datas);
        $datas=array_merge($rdatas,$datas);

        return $this->ItemsTable("",$edit,$datas,array(),array(),$datas);
    }
}
?>