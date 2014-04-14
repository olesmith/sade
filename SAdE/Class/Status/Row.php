<?php

include_once("Class/Status/TitleRows.php");


class ClassStatusRow extends ClassStatusTitleRows
{ 
    //*
    //* function StatusFieldCGIVar, Parameter list: $class,$disc,$student,$teacher
    //*
    //* Returns CGI key name of status field.
    //*

    function StatusFieldCGIVar($class,$disc,$student)
    {
        return
            "Status_".
            $class[ "ID" ]."_".
            $disc[ "ID" ]."_".
            $student[ "StudentHash" ][ "ID" ];
    }

    //*
    //* function MakeStatusField, Parameter list: $edit,$class,$disc,$student
    //*
    //* Updates and creates Status input field.
    //*

    function MakeStatusField($edit,$class,$disc,$student)
    {
        $value=$this->ReadStudentDiscStatus($class,$disc,$student);
        if ($edit==0) { return $this->GetEnumValue("Status",array("Status" => $value)); }

        return $this->MakeSelectField
        (
            $this->StatusFieldCGIVar($class,$disc,$student),
            array(1,2),
            array("Ativo","Inativo"),
            $this->ReadStudentDiscStatus($class,$disc,$student)
        );
    }





    //*
    //* function ClassStatusStudentRowDiscCells, Parameter list: $class,$student,$disc,&$row
    //*
    //* Genrates Class status table.
    //*

    function ClassStatusStudentRowDiscCells($class,$student,$disc,&$row)
    {
        $status=$this->SelectUniqueHash
        (
           "",
           array
           (
              "Class"     => $class[ "ID" ],
              "ClassDisc" => $disc[ "ID" ],
              "Student"   => $student[ "StudentHash" ][ "ID" ],
           ),
           TRUE,
           $this->StatusData
        );

        $markshash=$this->ApplicationObj->ClassMarksObject->ReadAndCalcStudentDiscMarks
        (
           $class,
           $disc,
           $student
        );

        $absenceshash=$this->ApplicationObj->ClassAbsencesObject->ReadAndCalcStudentDiscAbsences
        (
           $class,
           $disc,
           $student
        );


        $status=$this->CalcStudentDiscStatus($markshash,$absenceshash);
        array_push
        (
           $row,
           sprintf("%.1f",$markshash[ "MediaFinal" ]),
           sprintf("%.1f",$absenceshash[ "Percent" ]),
           $this->PaintStudentResult($status)
        );

        return $status;
    }

    //*
    //* function ClassStatusStudentRow, Parameter list: $no,$class,$student
    //*
    //* Genrates Class status table student row.
    //*

    function ClassStatusStudentRow($no,$class,$student,$discs)
    {
        $row=array($this->B(sprintf("%02d",$no)));
        foreach ($this->StudentData as $data)
        {
            //array_push($row,$this->ApplicationObj->StudentsObject->MakeShowField($data,$student[ "StudentHash" ],TRUE));
        }

        $statusdate=$this->ApplicationObj->StudentsObject->MakeShowField("StatusDate1",$student[ "StudentHash" ],TRUE);
        if ($statusdate=="-")
        {
            $statusdate="";
        }
        else
        {
            $statusdate=", ".$statusdate;
        }


        array_push
        (
           $row,
           $this->ApplicationObj->StudentsObject->MakeShowField("Name",$student[ "StudentHash" ],TRUE),
           $this->ApplicationObj->StudentsObject->MakeShowField("Status",$student[ "StudentHash" ],TRUE).
           $statusdate
        );


        $res=2;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            if (empty($discs[ $disc[ "ID" ] ])) { continue; }

            $dres=$this->ClassStatusStudentRowDiscCells($class,$student,$disc,$row);
            if ($dres!=1) { $res=1; }
        }

        array_push($row,$this->ApplicationObj->ClassMarksObject->PaintStudentResult($res));

        return $row;
    }

}

?>