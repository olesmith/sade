<?php


class ClassDiscAbsencesCell extends Common
{
    //*
    //* function StudentAbsenceCGIName, Parameter list: $student,$content
    //*
    //* Generates absence cell for $student/$content.
    //*

    function StudentAbsenceCGIName($student,$content)
    {
        return "Absence_".$student[ "ID" ]."_".$content[ "ID" ];
    }

    //*
    //* function StudentAbsenceSqlWhere, Parameter list: $student,$content
    //*
    //* Generates absence sql where clause for $student/$content.
    //*

    function StudentAbsenceSqlWhere($student,$content)
    {
        return array
        (
           "Class" => $this->ApplicationObj->Class[ "ID" ],
           "Disc"  => $this->ApplicationObj->Disc[ "ID" ],
           "Student" => $student[ "StudentHash" ][ "ID" ],
           "Content" => $content[ "ID" ],
       );
    }


    //*
    //* function GetStudentAbsence, Parameter list: $student,$content
    //*
    //* Reads absence entriy from SQL. Should be 0 or 1.
    //*

    function GetStudentAbsence($student,$content)
    {
        return $this->MySqlNEntries
        (
           "",
           $this->StudentAbsenceSqlWhere($student,$content)
        );
    }

    //*
    //* function DaylyAbsencesStudentAbsenceCell, Parameter list: $n,$month,$student,$m,$content,&$ch,&$studchs
    //*
    //* Generates absence cwell for student.
    //*

    function DaylyAbsencesStudentAbsenceCell($edit,$n,$month,$student,$m,$content,&$ch,&$studchs)
    {
        $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($content[ "Date" ]);

        $status=$this->GetStudentStatus($student,$month,$datekey);

        if (!$status) { return "-";  }

        /* if ($edit==1 && $this->GetPOST("Save")==1) */
        /* { */
        /*     /\* $this->UpdateStudentAbsenceCell($month,$student,$content); *\/ */
        /*     $this->UpdateStudentAbsenceCell($student,$content); */
        /* } */

        $absence=$this->GetStudentAbsence($student,$content);
        $checked=FALSE;
        if ($absence>0)
        {
            $ch+=$content[ "Weight" ];
            $checked=TRUE;
        }

        if ($edit==1)
        {
            return $this->MakeCheckBox
            (
               $this->StudentAbsenceCGIName($student,$content),
               1,
               $checked,
               FALSE,
               array("TABINDEX" => $m)
            );
        }
        else
        {
                if ($checked)            { return "F"; }
            elseif ($this->LatexMode())  { return "\$\\cdot\$"; }
            else                         { return "&bullet;"; }
        }
    }
}

?>