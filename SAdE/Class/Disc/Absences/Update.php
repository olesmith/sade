<?php


class ClassDiscAbsencesUpdate extends ClassDiscAbsencesCell
{
    //*
    //* function UpdateStudentAbsenceCell, Parameter list: $student,$content
    //*
    //* Updates student absence cell.
    //*

    function UpdateStudentAbsenceCell($student,$content)
    {
        $newvalue=$this->GetPOST($this->StudentAbsenceCGIName($student,$content));
        $absence=$this->GetStudentAbsence($student,$content);

        if ($newvalue==0)
        {
            if ($absence>0)
            {
                $this->MySqlDeleteItems
                (
                   "",
                   $this->StudentAbsenceSqlWhere($student,$content)
                );
            }
        }
        elseif ($newvalue==1)
        {
            if ($absence==0)
            {
                //No register, insert new
                $absence=$this->StudentAbsenceSqlWhere($student,$content);
                $absence[ "Month" ]=$content[ "Month" ];
                $absence[ "Semester" ]=$content[ "Semester" ];
                $absence[ "Weight" ]=$content[ "Weight" ];
                foreach (array("CTime","ATime","MTime") as $data) { $absence[ $data ]=time(); }

                $this->MySqlInsertItem("",$absence);
            }
            else
            {
                //Register exists, update
                $this->MySqlSetItemValuesWhere
                (
                   "",
                   $this->StudentAbsenceSqlWhere($student,$content),
                   array("Weight","Month","Semester"),
                   $content
                );
            }
        }
    }

    //*
    //* function UpdateStudentAbsenceCells, Parameter list: $student,$date
    //*
    //* Updates student absence cells, absences in $this->ApplicationObj->Contents.
    //*

    function UpdateStudentAbsenceCells($student)
    {
        $date=$this->GetGET("Date");

        foreach ($this->ApplicationObj->Contents as $trimester => $contents)
        {
            foreach ($contents as $content)
            {
                $redit=$this->EditMonthCell(1,$student,$content);
                if (!empty($date) && $date!=$content[ "DateKey" ]) { $redit=0; }

                if ($redit==1)
                {
                    $this->UpdateStudentAbsenceCell($student,$content);
                }
            }
        }
     }


   //*
    //* function UpdateStudentsAbsenceTable, Parameter list: 
    //*
    //* Updates all student absence cells, students in $this->ApplicationObj->Students.
    //*

    function UpdateStudentsAbsenceTable()
    {
        foreach ($this->ApplicationObj->Students as $student)
        {
            $this->UpdateStudentAbsenceCells($student);
            $this->UpdateStudentDiscAbsences($student);
        }
    }

 
    //*
    //* function UpdateStudentDiscAbsences, Parameter list: $student,$disc=array(),$class=array()
    //*
    //* 
    //*

    function UpdateStudentDiscAbsences($student,$disc=array(),$class=array())
    {
        if (empty($disc))  { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $sem=$this->GetGET("Semester");
        for ($semester=1;$semester<=$disc[ "NAssessments" ];$semester++)
        {
            if (!empty($sem) && $sem!=$semester) { continue; }
            $where=array
            (
               "Class"      => $class[ "ID" ],
               "Disc"  => $disc[ "ID" ],
               "Student"    => $student[ "StudentHash" ][ "ID" ],
               "Semester" => $semester,
            );

            $nabsences=$this->RowSum("",$where,"Weight");

            $rwhere=array
            (
               "Class"      => $class[ "ID" ],
               "ClassDisc"  => $disc[ "ID" ],
               "Student"    => $student[ "StudentHash" ][ "ID" ],
               "Assessment" => $semester,
            );

            $absences=$rwhere;
            $absences[ "Absences" ]=$nabsences;
            $absences[ "SecEdit" ]=1;

            $this->ApplicationObj->ClassAbsencesObject->AddOrUpdate("",$rwhere,$absences);
        }
   }
}

?>