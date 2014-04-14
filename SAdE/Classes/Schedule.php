<?php


class ClassesSchedule extends ClassesRead
{
    //*
    //* function Discs2Hours, Parameter list: 
    //*
    //* Returns Hours for $this->ApplicationObj->Discs.
    //*

    function Discs2Hours()
    {
        $hours=array();
        foreach ($this->ApplicationObj->Discs as $id => $disc)
        {
            if (empty($disc[ "Lessons" ])) { continue; }

            foreach ($disc[ "Lessons" ] as $lid => $lesson)
            {
                if (
                      !empty($lesson[ "WeekDay" ])
                      &&
                      !empty($lesson[ "Start" ])
                      &&
                      !empty($lesson[ "End" ])
                   )
                {
                    $hours[ $lesson[ "Start" ]."-".$lesson[ "End" ] ]=1;

                    $disc[ "Lessons" ][ $lid ][ "TimeSpan" ]=
                        $lesson[ "Start" ]."-".$lesson[ "End" ];
                }
            }

            $this->ApplicationObj->Discs[ $id ]=$disc;
        }

        $hours=array_keys($hours);
        sort($hours);

        return $hours;
    }

    //*
    //* function ClassLessonCell, Parameter list: $disc,$lesson
    //*
    //* Formatted Cell entry for schedule entry (disc/lesson).
    //*

    function ClassLessonCell($disc,$lesson)
    {
        $cell=$disc[ "Name" ];
        if (!empty($lesson[ "Teacher" ]))
        {
            $cell.=
                "<BR>".
                $this->B
                (
                   "Prof: ".
                   $lesson[ "Teacher" ]
                );
        }

        return $cell;
    }

    //*
    //* function AddClassLessonCell, Parameter list: &$schedule,$disc,$lesson
    //*
    //* Adds Formatted Cell entry to schedule.
    //*

    function AddClassLessonCell(&$schedule,$disc,$lesson)
    {
        if (!empty($lesson[ "TimeSpan" ]))
        {
            if (empty($schedule[ $lesson[ "TimeSpan" ] ][ $lesson[ "WeekDay" ] ]))
            {
                $schedule[ $lesson[ "TimeSpan" ] ][ $lesson[ "WeekDay" ] ]=array();
            }

            $cell=$disc[ "Name" ];
            if (!empty($disc[ "Teacher" ]))
            {
                $cell.=
                    $this->BR().
                    "Prof: ".$this->ApplicationObj->UsersObject->MySqlItemValue
                   (
                      "",
                      "ID",$disc[ "Teacher" ],
                      "Name"
                   );
            }

            array_push
            (
               $schedule[ $lesson[ "TimeSpan" ] ][ $lesson[ "WeekDay" ] ],
               $cell
             );
        }
    }

    //*
    //* function ClassSchedule, Parameter list: $echo=TRUE
    //*
    //* Displays Schedule for class disciplines.
    //*

    function ClassSchedule()
    {
        $days=array("");
        foreach ($this->WeekDays as $day) { array_push($days,""); }

        $schedule=array();

        $hours=$this->Discs2Hours();
        foreach ($hours as $hour)
        {
            $schedule[ $hour ]=$days;
            $schedule[ $hour ][0]=$this->B($hour.":");
        }

        foreach ($this->ApplicationObj->Discs as $id => $disc)
        {
            if (empty($disc[ "Lessons" ])) { continue; }

            foreach ($disc[ "Lessons" ] as $lid => $lesson)
            {
                $this->AddClassLessonCell($schedule,$disc,$lesson);
            }
        }

        $titles=$this->B($this->WeekDays);
        array_unshift($titles,"");

        $table=array
        (
           $this->H(3,"Horários da Turma: ".$this->ApplicationObj->Class[ "NameKey" ]),
           $titles,
        );

        $hours=array_keys($schedule);
        sort($hours);

        foreach ($hours as $hour)
        {
            $hrow=$schedule[ $hour ];
            $row=array();
            foreach ($hrow as $cell)
            {
                if (is_array($cell))
                {
                    $pre="";
                    if (count($cell)>1) { $pre="!!!"; }
                    array_push
                    (
                       $row,
                       $pre.
                       join("<BR>",$cell)
                    );
                }
                else
                {
                    array_push($row,$cell);
                }
            }

            for ($k=count($row);$k<count($titles);$k++) { array_push($row,""); }

            array_push($table,$row);
        }

        print $this->HtmlTable("",$table);
    }
}

?>