<?php

class ClassesPrintsTable extends ClassesPrintsDaylies
{

    //*
    //* function ClassDaylyInfoTable, Parameter list: $class,$disc=array(),$month=""
    //*
    //* Generates latex info table for Dayly.
    //*

    function ClassDaylyInfoTable($class,$disc=array(),$month="")
    {
        return $this->ApplicationObj->SchoolsObject->GenerateLatexPageTable
        (
           $this->ApplicationObj->School,
           $class,$disc,$month
        );
    }

    //*
    //* function ClassInfoTable, Parameter list: $class
    //*
    //* Generates latex info table for class.
    //*

    function ClassInfoTable($class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        return array
        (
           array
           (
              $this->B("Grade:"),
              $this->ApplicationObj->GradeObject->MySqlItemValue("","ID",$class[ "Grade" ],"ShortName"),
              $this->B("Período:"),
              $this->ApplicationObj->GradePeriodsObject->MySqlItemValue("","ID",$class[ "GradePeriod" ],"Name"),
           ),
           array
           (
              $this->B("Período Leitivo:"),
              $this->ApplicationObj->PeriodsObject->MakeShowField("Name",$this->ApplicationObj->Period),
              $this->B("Turma:"),
              $this->MakeShowField("Name",$class),
           ),
        );
    }



    //*
    //* function ClassDaylyStudentRow, Parameter list: $class,$disc,$firstdate,$lastdate,$student,&$n,&$table
    //*
    //* Generates student daily line.
    //*

    function ClassDaylyStudentRow($class,$disc,$firstdate,$lastdate,$student,&$n,&$table)
    {
        $active=TRUE;

        $row=array();

        array_push($row,$this->B(sprintf("%02d",$n)));

        array_push($row,$student[ "StudentHash" ][ "Name" ]);

        $status=$this->ApplicationObj->StudentsObject->GetEnumValue("Status",$student[ "StudentHash" ]);
        $statdate=$student[ "StudentHash" ][ "StatusDate1" ];
        if ($statdate==0) { $statdate="-"; }

        if (!$this->LatexMode)
        {
            array_push
            (
               $row,
               $student[ "StudentHash" ][ "MatriculaDate" ],
               $status,
               $statdate
            );
        }

        if ($this->ApplicationObj->ClassStudentsObject->StudentMatriculatedAtDate($student,$lastdate))
        {
            if ($this->ApplicationObj->ClassStudentsObject->StudentActiveAtDate($student,$firstdate))
            {
                $row=array_merge($row,$this->Empties);
            }
            else
            {
                array_push
                (
                   $row,
                   $this->MultiCell
                   (
                    $status." ".$this->SortTime2Date($statdate),
                      $this->NFields+2
                   )
                );
            }
            $n++;
        }
        else
        {
            $mon=preg_replace('/^\d\d\d\d/',"",$student[ "StudentHash" ][ "MatriculaDate" ]);
            $mon=preg_replace('/\d\d$/',"",$mon);
            if (!empty($this->Months[ $mon-1 ]))
            {
                $mon=$this->Months[ $mon-1 ];
            }
            else
            {
                $mon="";
            }
            array_push
            (
               $row,
               $this->MultiCell
               (
                  "Entre Mes de ".$mon,
                  $this->NFields+2
               )
            );

            $row[0]="";

            if ($this->LatexMode) { $active=FALSE; }
        }

        if ($active)
        {
            array_push($table,$row);
        }
    }

    //*
    //* function ClassMarksStudentRow, Parameter list: $class,$disc,$firstdate,$lastdate,$student,&$n,&$table
    //*
    //* Generates student marks line.
    //*

    function ClassMarksStudentRow($class,$disc,$firstdate,$lastdate,$student,&$n,&$table)
    {
        $active=TRUE;

        $row=array();

        array_push($row,$this->B(sprintf("%02d",$n)));

        array_push($row,$student[ "StudentHash" ][ "Name" ]);

        $status=$this->ApplicationObj->StudentsObject->GetEnumValue("Status",$student[ "StudentHash" ]);
        $statdate=$student[ "StudentHash" ][ "StatusDate1" ];
        if ($statdate==0) { $statdate="-"; }

        if (!$this->LatexMode)
        {
            array_push
            (
               $row,
               $student[ "StudentHash" ][ "MatriculaDate" ],
               $status,
               $statdate
            );
        }

        if ($this->ApplicationObj->ClassStudentsObject->StudentMatriculatedAtDate($student,$lastdate))
        {
            $row=array_merge($row,$this->MEmpties);
            array_push($table,$row);
        }
    }

    //*
    //* function ClassSignaturesStudentRow, Parameter list: $class,$disc,$firstdate,$lastdate,$student,&$n,&$table
    //*
    //* Generates student marks line.
    //*

    function ClassSignaturesStudentRow($class,$disc,$firstdate,$lastdate,$student,$nsigs,&$n,&$table)
    {
        $active=TRUE;

        $row=array();

        array_push($row,$this->B(sprintf("%02d",$n)));

        array_push($row,$student[ "StudentHash" ][ "Name" ]);

        $status=$this->ApplicationObj->StudentsObject->GetEnumValue("Status",$student[ "StudentHash" ]);
        $statdate=$student[ "StudentHash" ][ "StatusDate1" ];
        if ($statdate==0) { $statdate="-"; }

        if ($this->ApplicationObj->ClassStudentsObject->StudentMatriculatedAtDate($student,$lastdate))
        {
            for ($k=1;$k<=$nsigs;$k++)
            {
                array_push($row,"\\hspace{4.5cm}~ ~ ");
            }

            array_push($table,$row);
        }
    }

}

?>