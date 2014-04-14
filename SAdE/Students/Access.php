<?php



class StudentsAccess extends StudentsHistory
{
    //*
    //* function MayRemove, Parameter list: $item
    //*
    //* Decides whether ClassStudent is deletable.
    //*

    function MayRemove($item)
    {
        $res=FALSE;
        if (empty($item[ "Status" ]))
        {
            $res=FALSE;
        }
        elseif ($item[ "Status" ]==8)
        {
            //Not matriculated
            $res=TRUE;
        }
        elseif ($item[ "Status" ]!=1)
        {
            if (empty($this->ApplicationObj->Period))
            {
                $this->ApplicationObj->Period=$this->ApplicationObj->PeriodsObject->SelectUniqueHash
                (
                   "",
                   array("ID" => $this->GetGET("Period"))
                );
            }

            $semdate=$this->ApplicationObj->DatesObject->ID2SortKey($this->ApplicationObj->Period[ "StartDate" ]);
            if (empty($item[ "StatusDate2" ]))
            {
                if ($item[ "StatusDate1" ]<$semdate)
                {
                    $res=TRUE;
                }
            }
        }

        return $res;
    }

    //*
    //* function RegisterStudentAbsences, Parameter list: 
    //*
    //* Checks whether we should have register Absences.
    //*

    function RegisterStudentAbsences()
    {
        $res=TRUE;
        if (
              !isset($this->ApplicationObj->Class[ "AbsencesType" ])
              ||
              intval($this->ApplicationObj->Class[ "AbsencesType" ])==$this->ApplicationObj->AbsencesNo
              ||
              intval($this->ApplicationObj->Class[ "AssessmentType" ])==$this->ApplicationObj->Qualitative
           ) { $res=FALSE; }

        return $res;
    }

    //*
    //* function RegisterStudentMarks, Parameter list:
    //*
    //* Checks whether we should have register Marks.
    //*

    function RegisterStudentMarks()
    {
        $res=TRUE;
        if (
              !isset($this->ApplicationObj->Class[ "AssessmentType" ])
              ||
              intval($this->ApplicationObj->Class[ "AssessmentType" ])==$this->ApplicationObj->MarksNo
          ) { $res=FALSE; }

        return $res;
    }

    //*
    //* function RegisterStudentTotals, Parameter list:
    //*
    //* Checks whether we should have register Totals.
    //*

    function RegisterStudentTotals()
    {
        $res=$this->RegisterStudentMarks();
        if ($res) { $res=$this->RegisterStudentAbsences(); }

        return $res;
    }
}

?>