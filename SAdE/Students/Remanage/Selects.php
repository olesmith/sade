<?php


class StudentsRemanageSelects extends StudentsMatriculate
{
    //*
    //* function DestinationSchoolSelect, Parameter list: $classstudent
    //*
    //* Creates destination school select.
    //* 
    //*

    function DestinationSchoolSelect($classstudent)
    {
        $schools=$this->ApplicationObj->SchoolsObject->SelectHashesFromTable
        (
           "",
           array(),
           array("ID","Name"),
           FALSE,
           "Name"
        );

        $ids=array();
        $names=array();
        foreach ($schools as $rschool)
        {
            array_push($ids,$rschool[ "ID" ]);
            array_push($names,$rschool[ "Name" ]);
        }

        return $this->MakeSelectField
        (
           "ToSchool",
           $ids,
           $names,
           $this->DestinationSchool[ "ID" ]
        );
    }

    //*
    //* function DestinationClassSelect, Parameter list: $classstudent
    //*
    //* Creates destination class select.
    //* 
    //*

    function DestinationClassSelect($classstudent)
    {
        $classes=$this->SelectHashesFromTable
        (
           $this->DestinationSchool[ "ID" ]."_Classes",
           array
           (
              "Grade"       => $this->ApplicationObj->Class[ "Grade" ],
              "GradePeriod" => $this->ApplicationObj->Class[ "GradePeriod" ],
              "Period"      => $this->ApplicationObj->Class[ "Period" ],
           ),
           array("ID","NameKey"),
           FALSE,
           "Name"
        );

        $ids=array();
        $names=array();
        foreach ($classes as $rclass)
        {
            if (
                  $this->DestinationSchool[ "ID" ]!=$this->ApplicationObj->Class[ "School" ]
                  ||
                  $rclass[ "ID" ]!=$this->ApplicationObj->Class[ "ID" ]
               )
            {
                array_push($ids,$rclass[ "ID" ]);
                array_push
                (
                   $names,
                   $rclass[ "ID" ].", ".$rclass[ "NameKey" ].", ".
                   $this->DestinationSchool[ "ShortName" ]
                );

                if (empty($this->DestinationClass))
                {
                    $this->DestinationClass=$rclass;
                }
            }
        }

        if (count($ids)==0)
        {
            return "Nenhuma Turma de Destino Adequada na ".$this->DestinationSchool[ "ShortName" ]."...";
        }

        return $this->MakeSelectField
        (
           "ToClass",
           $ids,
           $names,
           $this->DestinationClass[ "ID" ]
        );
    }

 
}

?>