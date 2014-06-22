<?php

include_once("Class/Absences/TitleRows.php");


class ClassAbsencesTables extends ClassAbsencesTitleRows
{
    //*
    //* function MakeAbsencesField, Parameter list: $edit,$class,$disc,$student,$n,$nlessons=0
    //*
    //* Updates and creates Mark input field.
    //*

    function MakeAbsencesField($edit,$class,$disc,$student,$n,$nlessons=0)
    {
        $emph="";
        $value=$this->ReadStudentDiscAbsence($class,$disc,$student,$n);

        if (preg_match('/\d/',$value))
        {
            $value=preg_replace('/[^\d]/',"",$value);
            if ($value<0)
            {
                $emph="**";
            }

            if ($nlessons>0 && $value>$nlessons)
            {
                $emph="*";
            }
        }
        else { $value=""; }

        //Absences registered by teacher - read only

        if ($this->ReadStudentDiscSecEdit($class,$disc,$student,$n)==1) { $edit=0; }

        if ($edit==0) { return $value.$emph; }

        return $this->MakeInput
        (
            $this->AbsencesFieldCGIVar($class,$disc,$student,$n),
            $value,
            2,
            array("TABINDEX" => $n+30)
        ).
        $emph;
    }

    //*
    //* function AbsencesRow, Parameter list: $edit,$class,$disc,$student
    //*
    //* Creates the Disc Student absences fields.
    //*

    function AbsencesRow($edit,$class,$disc,$student)
    {
        if (empty($disc))
        {
            $disc[ "ID" ]=0;
            $disc[ "NAssessments" ]=$class[ "NAssessments" ];

            $this->ApplicationObj->ClassDiscNLessonsObject->ReadClassDiscNLessons($class,$disc);
        }

        if (intval($disc[ "AbsencesType" ])==$this->ApplicationObj->AbsencesNo)
        {
            $n=$disc[ "NAssessments" ];
            if ($this->ApplicationObj->ClassDiscsObject->ShowAbsencesTotals) { $n++; }
            return array($this->MultiCell("",$n));
        }

        $row=array();
        $total=0;
        $nlessonstotal=0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            $nlessons="";
            if (isset($disc[ "NLessons" ][ $n-1 ][ "NLessons" ]))
            {
                $nlessons=$disc[ "NLessons" ][ $n-1 ][ "NLessons" ];
                $nlessonstotal+=$nlessons;
            }

            array_push
            (
               $row,
               $this->MakeAbsencesField
               (
                  $edit,
                  $class,
                  $disc,
                  $student,
                  $n,
                  $nlessons
               )
            );

            $total+=$this->ReadStudentDiscAbsence($class,$disc,$student,$n);
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowAbsencesTotals)
        {
            array_push($row,$total);
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowAbsencesPercent)
        {
            $percent="-";
            if ($nlessonstotal>0)
            {
                $percent=(100.0*$total)/(1.0*$nlessonstotal);
                $percent=sprintf("%.1f",$percent);
            }
            array_push($row,$percent);
        }

        return $row;
    }

    //*
    //* function AbsencesTotalsRow, Parameter list: $edit,$class,$disc,$student,$absenceshash
    //*
    //* Creates the Disc Student absences fields.
    //*

    function AbsencesTotalsRow($edit,$class,$disc,$student,$absenceshash)
    {
        if ($absenceshash[ "Percent" ]!="-")
        {
            $absenceshash[ "Percent" ]=sprintf("%.1f",$absenceshash[ "Percent" ]);
        }

        return array
        (
           $absenceshash[ "Percent" ],
           $this->PaintStudentResult($absenceshash[ "AbsencesResult" ])       
        );

    }

    //*
    //* function FinalRow, Parameter list: $edit,$class,$disc,$student,$absenceshash
    //*
    //* Generates final titles.
    //*

    function FinalRow($edit,$class,$disc,$student,$absenceshash)
    {
        if (intval($disc[ "AbsencesType" ])==$this->ApplicationObj->AbsencesNo)
        {
            return array($this->MultiCell("",2));
        }

        return array
        (
           sprintf("%.1f",$absenceshash[ "Percent" ]),
           $this->PaintStudentResult($absenceshash[ "AbsencesResult" ])
        );
    }

}

?>