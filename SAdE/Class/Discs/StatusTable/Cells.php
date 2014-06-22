<?php


class ClassDiscsStatusTableCells extends ClassDiscsStatusTableCGI
{
    //*
    //* function TrimesterSearchDataCell, Parameter list: $data
    //*
    //* Creates search cell associated with Search $data.
    //* 
    //*

    function TrimesterSearchDataCell($data)
    {
        return $this->MakeCheckBox
        (
           "Data_".$data,
           1,
           $this->IncludeSearchData($data)
        );
    }

    //*
    //* function TrimesterSearchExtendedCell, Parameter list: $data
    //*
    //* Creates search cell associated with Search extended $data.
    //* 
    //*

    function TrimesterSearchExtendedCell($data)
    {
        return $this->MakeCheckBox
        (
           "Extended_".$data,
           1,
           $this->IncludeSearchExtended($data)
        );
    }


    //*
    //* function TrimesterNWeightsCell, Parameter list: $disc,$trimester
    //*
    //* Creates cell with number of contents registered by $disc in $trimester.
    //* 
    //*

    function TrimesterNWeightsCell($disc,$trimester)
    {
        $where=array
        (
           "Class" => $disc[ "Class" ],
           "Disc"  => $disc[ "ID" ],
        );

        return $this->ApplicationObj->ClassDiscContentsObject->MySqlSumNEntries
        (
           $this->ApplicationObj->SchoolPeriodSqlClassTableName("ClassDiscContents",$disc[ "Class" ]),
           $this->Hash2SqlWhere($where).
           " AND ".
           "Date IN ('".
           join
           (
              "','",
              $this->ApplicationObj->PeriodsObject->TrimesterDates($trimester)
           ).
           "')",
           "Weight"
        );
    }

    //*
    //* function TrimesterNContentsCell, Parameter list: $disc,$trimester
    //*
    //* Creates cell with number of contents registered by $disc in $trimester.
    //* 
    //*

    function TrimesterNContentsCell($disc,$trimester)
    {
        $where=array
        (
           "Class" => $disc[ "Class" ],
           "Disc"  => $disc[ "ID" ],
        );

        return $this->ApplicationObj->ClassDiscContentsObject->MySqlNEntries
        (
           $this->ApplicationObj->SchoolPeriodSqlClassTableName("ClassDiscContents",$disc[ "Class" ]),
           $this->Hash2SqlWhere($where).
           " AND ".
           "Date IN ('".
           join
           (
              "','",
              $this->ApplicationObj->PeriodsObject->TrimesterDates($trimester)
           ).
           "')"
        );
    }

    //*
    //* function TrimesterAbsencesCell, Parameter list: $disc,$trimester
    //*
    //* Creates cell with number of absences dates registered by $disc in $trimester.
    //* 
    //*

    function TrimesterAbsencesCell($disc,$trimester)
    {
        $where=array
        (
           "Class" => $disc[ "Class" ],
           "Disc"  => $disc[ "ID" ],
        );

        $contents=$this->ApplicationObj->ClassDiscContentsObject->MySqlUniqueColValues
        (
           $this->ApplicationObj->SchoolPeriodSqlClassTableName("ClassDiscContents",$disc[ "Class" ]),
           "ID",
           $this->Hash2SqlWhere($where).
           " AND ".
           "Date IN ('".
           join
           (
              "','",
              $this->ApplicationObj->PeriodsObject->TrimesterDates($trimester)
           ).
           "')"
        );

        $dates=$this->ApplicationObj->ClassDiscAbsencesObject->MySqlUniqueColValues
        (
           $this->ApplicationObj->SchoolPeriodSqlClassTableName("ClassDiscAbsences",$disc[ "Class" ]),
           "Content",
           $this->Hash2SqlWhere($where).
           " AND ".
           "Content IN ('".join("','",$contents)."')"
        );

        return count($dates);
    }

    //*
    //* function TrimesterNAssessmentsCell, Parameter list: $disc,$trimester
    //*
    //* Creates cell with number of assessments registered by $disc in $trimester.
    //* 
    //*

    function TrimesterNAssessmentsCell($disc,$trimester)
    {
        $where=array
        (
           "Class"    => $disc[ "Class" ],
           "Disc"     => $disc[ "ID" ],
           "Semester" => $trimester,
        );

        return $this->ApplicationObj->ClassDiscAssessmentsObject->MySqlNEntries
        (
           $this->ApplicationObj->SchoolPeriodSqlClassTableName("ClassDiscAssessments",$disc[ "Class" ]),
           $where
        );
    }


    //*
    //* function TrimesterNMarksCell, Parameter list: $disc,$trimester
    //*
    //* Creates cell with number of assessments registered by $disc in $trimester.
    //* 
    //*

    function TrimesterNMarksCell($disc,$trimester)
    {
        $where=array
        (
           "Class"    => $disc[ "Class" ],
           "Disc"     => $disc[ "ID" ],
           "Semester" => $trimester,
        );

        $assessments=$this->ApplicationObj->ClassDiscMarksObject->MySqlUniqueColValues
        (
           $this->ApplicationObj->SchoolPeriodSqlClassTableName("ClassDiscMarks",$disc[ "Class" ]),
           "Assessment",
           $where
        );

        return count($assessments);
    }
}

?>