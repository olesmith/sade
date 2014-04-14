<?php

class SAdESchool extends SAdEUnit
{
    var $AccessibleSchools=array();

    //*
    //* function ReadSchools, Parameter list: 
    //*
    //* Reads all permitted schools.
    //*

    function ReadSchools()
    {
        if (empty($this->SchoolsObject))
        {
            $this->LoadSubModule("Schools");
        }

        $this->SchoolsObject->ReadSchools();
    }


    //*
    //* function ReadSchool, Parameter list: $die=TRUE
    //*
    //* Reads School ID from CGI GET, exits if nonexistent or inadeqaute
    //*

    function ReadSchool($die=TRUE)
    {
        if (empty($this->SchoolsObject))
        {
            $this->LoadSubModule("Schools");
        }
        $this->SchoolsObject->ReadSchool($die);
    }

    //*
    //* function GetSchoolID, Parameter list: 
    //*
    //* Reads School ID from CGI GET.
    //*

    function GetSchoolID()
    {
        return $this->School("ID");
    }

    //*
    //* function AccessibleSchools, Parameter list: $item
    //*
    //* Detects schools that someone is allowed to access.
    //*

    function AccessibleSchools()
    {
        if (empty($this->AccessibleSchools))
        {
            $this->AccessibleSchools=array();
            if (preg_match('/^(Clerk|Coordinator)$/',$this->Profile))
            {
                $schools=$this->MySqlUniqueColValues
                (
                   "Clerks",
                   "School",
                   array("Clerk" => $this->LoginData[ "ID" ])
                );

                foreach ($schools as $school) { $this->AccessibleSchools[ $school ]=1; }
            }
        }

       return $this->AccessibleSchools;
    }
}

?>
