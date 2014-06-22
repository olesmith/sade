<?php



class PeriodsDiscs extends PeriodsPeriod
{
    //*
    //* function HandleDiscs, Parameter list: $title=""
    //*
    //* Handles Period disciplines search.
    //*

    function HandleDiscs($title="")
    {
        $this->ApplicationObj->ClassDiscsObject->Singular=FALSE;
        $this->ApplicationObj->ClassDiscsObject->ItemName="Disciplina";
        $this->ApplicationObj->ClassDiscsObject->ItemsName="Disciplinas";

        $this->ApplicationObj->ClassDiscsObject->InitProfile("ClassDiscs");
        $this->ApplicationObj->ClassDiscsObject->InitActions();
        $this->ApplicationObj->ClassDiscsObject->PostInit();

        $action=$this->GetGET("Action");
        $edit=0;
        if (preg_match('/^EditDiscs$/',$action))
        {
            $this->DefaultAction="EditDiscs";
            $edit=1;
        }

        $group=$this->GetCGIVarValue($this->GroupDataCGIVar());
        if (empty($group)) { $group="Daylies"; }

        $this->ApplicationObj->ClassDiscsObject->CurrentDataGroup=$group;

        if ($group=="Daylies")
        {
            $this->ApplicationObj->ClassDiscsObject->ExtraSearchRowsMethod="DiscsStatusTableSearchRows";
        }

        $this->ApplicationObj->ClassDiscsObject->SearchVarTableModule="Periods";

        //Disable School and Period, as they are fixed by GET vars
        $this->ApplicationObj->ClassDiscsObject->ItemData[ "School" ][ "Search" ]=FALSE;
        $this->ApplicationObj->ClassDiscsObject->ItemData[ "Period" ][ "Search" ]=FALSE;

        $this->ApplicationObj->ClassDiscsObject->ItemData[ "Teacher" ][ "Search" ]=TRUE;
        $this->ApplicationObj->ClassDiscsObject->ItemData[ "Teacher1" ][ "Search" ]=TRUE;
        $this->ApplicationObj->ClassDiscsObject->ItemData[ "Teacher2" ][ "Search" ]=TRUE;



        $this->ApplicationObj->ClassesObject->UpdateSubTablesStructure();

        $sqlwhere=array
        (
           "Period" => $this->ApplicationObj->Period[ "ID" ]
        );
        $psqlwhere=array();

        $this->ApplicationObj->ClassDiscsObject->ItemData[ "Class" ][ "SqlWhere" ]=$sqlwhere;
        $this->ApplicationObj->ClassDiscsObject->ItemDataGroups[ "Daylies" ][ "GenTableMethod" ]="DiscsStatusTable";

        $grade=$this->ApplicationObj->ClassesObject->GetSearchVarCGIValue("Grade");
        if ($grade>0)
        {
            $sqlwhere[ "Grade" ]=$grade;
            $psqlwhere[ "Grade" ]=$grade;
            $this->ApplicationObj->ClassDiscsObject->ItemData[ "GradePeriod" ][ "SqlWhere" ]=$psqlwhere;
            $this->ApplicationObj->ClassDiscsObject->ItemData[ "GradeDiscs" ][ "SqlWhere" ]=$psqlwhere;
            $this->ApplicationObj->ClassDiscsObject->ItemData[ "Class" ][ "SqlWhere" ]=$sqlwhere;
        }

        $gradeperiod=$this->ApplicationObj->ClassesObject->GetSearchVarCGIValue("GradePeriod");
        if ($gradeperiod>0)
        {
            $sqlwhere[ "GradePeriod" ]=$gradeperiod;
            $psqlwhere[ "GradePeriod" ]=$gradeperiod;
            $this->ApplicationObj->ClassDiscsObject->ItemData[ "GradeDisc" ][ "SqlFilter" ]=preg_replace
            (
               '/#GradePeriod/',
               $this->ApplicationObj->GradeObject->MySqlItemValue("","ID",$grade,"Name").", ".
               $this->ApplicationObj->GradePeriodsObject->MySqlItemValue("","ID",$gradeperiod,"Name"),
               $this->ApplicationObj->ClassDiscsObject->ItemData[ "GradeDisc" ][ "SqlFilter" ]
            );

            $this->ApplicationObj->ClassDiscsObject->ItemData[ "GradeDiscs" ][ "SqlWhere" ]=$psqlwhere;
            $this->ApplicationObj->ClassDiscsObject->ItemData[ "Class" ][ "SqlWhere" ]=$sqlwhere;
        }

        $this->ApplicationObj->ClassDiscsObject->ItemName="Disciplina";
        $this->ApplicationObj->ClassDiscsObject->ItemsName=
            "Disciplinas".
            $this->BR().
            $this->ApplicationObj->PeriodsObject->GetPeriodTitle();

        $this->ApplicationObj->ClassDiscsObject->ItemHashes=array();
        $this->ApplicationObj->ClassDiscsObject->HandleList("",TRUE,$edit);
    }
}

?>