<?php


class PeriodsCalendar extends PeriodsYear
{

    //*
    //* function CalendarLegend, Parameter list:
    //*
    //* Generates legend for calendar table.
    //*

    function CalendarLegend()
    {
        $legends=array($this->B("Legenda:"));
        foreach (array_keys($this->DateCellDefs) as $type)
        {
            array_push
            (
               $legends,
               $this->Div
               (
                  $this->DateCellDefs[ $type ][ "Text" ],
                  array
                  (
                     "CLASS" => $this->DateCellDefs[ $type ][ "Class" ],
                  )
               )
            );
        }

        $legends=array($legends);

        return array($this->HtmlTable("",$legends,array("BORDER" => "0","ALIGN" => 'center')));
    }


    //*
    //* function HtmlCalendar, Parameter list:
    //*
    //* Generates Html Calendar table.
    //*

    function HtmlCalendar()
    {
        $nmonthsperline=3;

        $table=array();
        $row=array();
        foreach ($this->GetMonths() as $month)
        {
            array_push
            (
               $row,
               $this->Html_Table
               (
                  "",
                  $this->HtmlCalendarMonth($month),
                  array("ALIGN" => 'center'),
                  array(),
                  array(),
                  TRUE
               )
            );

            if (count($row)==$nmonthsperline)
            {
                array_push($table,$row);
                $row=array();
            }
        }

        if (count($row)>0)
        {
            array_push($table,$row);
            $row=array();
        }

        //array_push($table,$this->CalendarLegend());

        print
            $this->H(2,"Calendário").
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center'),
               array(),
               array()
            );
    }
    
}

?>