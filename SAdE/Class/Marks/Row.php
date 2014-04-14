<?php


class ClassMarksRow extends ClassMarksTitleRows
{

    //*
    //* function MakeMarkField, Parameter list: $edit,$class,$disc,$student,$assessment
    //*
    //* Updates and creates Mark input field.
    //*

    function MakeMarkField($edit,$class,$disc,$student,$assessment)
    {
        $emph="";
        $value=$this->ReadStudentDiscMark($class,$disc,$student,$assessment);

        if (preg_match('/\d/',$value))
        {
            $value=sprintf("%.1f",$value);
            if ($value<0.0 || $value>10.0)
            {
                $emph="*";
            }
        }

        if ($this->ReadStudentDiscSecEdit($class,$disc,$student,$assessment)==1) { $edit=0; }

        if ($edit==0) { return $value.$emph; }
        return $this->MakeInput
        (
            $this->MarkFieldCGIVar($class,$disc,$student,$assessment),
            $value,
            3,
            array("TABINDEX" => $assessment+30)
        ).
        $emph;
    }

    //*
    //* function MarksRow, Parameter list: $edit,$class,$disc,$student
    //*
    //* Creates the Disc Student marks fields.
    //*

    function MarksRow($edit,$class,$disc,$student,$markshash)
    {
        if (intval($disc[ "AssessmentType" ])==$this->ApplicationObj->MarksNo)
        {
            $n=$disc[ "NAssessments" ];
            if ($this->ApplicationObj->ClassDiscsObject->ShowMarkSums) { $n++; }
            if ($this->ApplicationObj->ClassDiscsObject->ShowMarksTotals) { $n++; }

            return array($this->MultiCell("",$n));
        }

        $row=array();
        $total=0.0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            array_push
            (
               $row,
               $this->MakeMarkField
               (
                  $edit,
                  $class,
                  $disc,
                  $student,
                  $n
               )
            );

            $total+=$this->ReadStudentDiscMark($class,$disc,$student,$n);
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowMarkSums)
        {
            array_push($row,sprintf("%.1f",$total));
        }

        if ($this->ApplicationObj->ClassDiscsObject->ShowMarksTotals)
        {
            array_push($row,sprintf("%.1f",$markshash[ "Media" ]));
        }

        return $row;
    }

    //*
    //* function MarksTotalsRow, Parameter list: $edit,$class,$disc,$student,$markshash
    //*
    //* Creates the Disc Student marks fields.
    //*

    function MarksTotalsRow($edit,$class,$disc,$student,$markshash)
    {
        if (intval($disc[ "AssessmentType" ])==$this->ApplicationObj->MarksNo)
        {
            return array($this->MultiCell("",2));
        }

        return array
        (
           $this->B($markshash[ "Media" ]),
           $this->PaintStudentResult($markshash[ "MediaResult" ])       
        );
    }

    //*
    //* function RecoveriesRow, Parameter list: $edit,$class,$disc,$student,$markshash
    //*
    //* Creates the Disc Student marks fields.
    //*

    function RecoveriesRow($edit,$class,$disc,$student,$markshash)
    {
        $row=array();
        $mm=$disc[ "NAssessments" ]+1;
        for ($m=1;$m<=$markshash[ "NRecoveries" ];$m++,$mm++)
        {
            array_push
            (
               $row,
               $this->MakeMarkField
               (
                  $edit,
                  $class,
                  $disc,
                  $student,
                  $mm
               )
            );

            if (empty($markshash[ "Marks" ][ $mm ]))
            {
                array_push($row,"-","-");
                continue;
            }
            else
            {
                array_push
                (
                   $row,
                   $this->B(sprintf("%.1f",$markshash[ "RecoveryMedias" ][ $m ])),
                   $this->PaintStudentResult($markshash[ "RecoveryResults" ][ $m ])       
                );
            }
        }

        if ($markshash[ "NRecoveries" ]==0)
        {
            for ($m=1;$m<=$disc[ "NRecoveries" ];$m++)
            {
                array_push($row,"","","");
            }
        }

        return $row;
    }


    //*
    //* function FinalRow, Parameter list: $edit,$class,$disc,$student,$markshash
    //*
    //* Generates final titles.
    //*

    function FinalRow($edit,$class,$disc,$student,$markshash)
    {
        if (intval($disc[ "AssessmentType" ])==$this->ApplicationObj->MarksNo)
        {
            return array($this->MultiCell("",2));
        }

        return array
        (
            $markshash[ "MediaFinal" ],
            $this->PaintStudentResult($markshash[ "MarkResult" ]) 
        );
    }

    //*
    //* function DiscStudentRow, Parameter list: $edit,$class,$disc,$student
    //*
    //* Creates the Disc Student Row.
    //*

    function DiscStudentRow($edit,$class,$disc,$student)
    {
        $status=$this->ApplicationObj->ClassStatusObject->ReadStudentDiscStatus
        (
           $class,
           $disc,
           $student
        );

        $row=array
        (
           $this->ApplicationObj->ClassStatusObject->MakeStatusField
           (
              $edit,
              $class,
              $disc,
              $student
           )
        );

        //Freeze rest, if inactive
        if ($status>1) { $edit=0; }

        $marks=$this->ReadStudentDiscMarks($class,$disc,$student);

        $markshash=$this->CalcStudentDiscMarks
        (
           $disc,
           $marks
        );

        $this->ApplicationObj->ClassStatusObject->UpdateStudentDiscMarkTotals($class,$student,$disc,$markshash);


        $row=array_merge($row,$this->MarksRow($edit,$class,$disc,$student,$markshash));
        $row=array_merge($row,$this->MarksTotalsRow($edit,$class,$disc,$student,$markshash));
        $row=array_merge($row,$this->RecoveriesRow($edit,$class,$disc,$student,$markshash));
        $row=array_merge($row,$this->FinalRow($edit,$class,$disc,$student,$markshash));

        return $row;
    }
}

?>