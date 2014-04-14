<?php


class SchoolsHandlers extends SchoolsLatexSettings
{
    //*
    //* function HandleClerks, Parameter list:
    //*
    //* 
    //*

    function HandleClerks()
    {
        //$this->ApplicationObj->ReadSchool();

        $this->ApplicationObj->UpdateTablesStructure(array("Clerks"),"Clerks");

        $this->ApplicationObj->ClerksObject->ItemData[ "School" ][ "Admin" ]=1;
        $this->ApplicationObj->ClerksObject->ItemData[ "School" ][ "Secretary" ]=1;
        $this->ApplicationObj->ClerksObject->SqlWhere=array
        (
           "School" => $this->ApplicationObj->School[ "ID" ],
        );

        $newperm=array
        (
           "School" => $this->ApplicationObj->School[ "ID" ],
        );

        $this->Sort=array("Clerk");
        $table=$this->ApplicationObj->ClerksObject->ItemsTableDataGroupWithAddRow
        (
           "Secretatio(a)s da Escola: ".$this->ApplicationObj->School[ "Name" ],
           "Common",
           "Update",
           "",
           $newperm
        );

        print
            $this->H(3,"Secretario(a)s da Escola").
            $this->StartForm().
            $this->Buttons().
            $this->HtmlTable("",$table).
            $this->MakeHidden("Update",1).
            $this->Buttons().
            $this->EndForm();
    }

    //*
    //* function HandlePeriods, Parameter list:
    //*
    //* 
    //*

    function HandlePeriods()
    {
        //$this->ApplicationObj->ReadSchool();
        $this->ApplicationObj->ReadSchoolPeriods();

        //$this->HandleShow($this->ApplicationObj->School[ "Name" ]);

        print $this->HtmlTable
        (
           $this->H(3,"Periodos da Escola"),
           $this->ApplicationObj->PeriodsObject->ItemsTableDataGroup
           (
              "",
              0,
              "Table",
              $this->ApplicationObj->Periods
           )
        );
    }

    //*
    //* function HandleTeachers, Parameter list:
    //*
    //* 
    //*

    function HandleTeachers()
    {
        //$this->ApplicationObj->ReadSchool();

        //$this->HandleShow("Professore(a)s da Escola");

        $this->Sort=array("Name");
        $this->ApplicationObj->UsersObject->SqlWhere=array
        (
           "Profile_Teacher" => 2,
        );

        $this->ApplicationObj->UsersObject->InitActions();


        $this->ApplicationObj->UsersObject->HandleList
        (
           "",
           TRUE,
           0,
           "",
           array("Profile_.+","Age","SUS","PRN_.+","PIS","UniqueID"),
           "Teachers",
           "Schools"
        );
    }

    //*
    //* function HandleEdit, Parameter list: $echo=TRUE,$title=""
    //*
    //* Overrides Table HandleEdit: Sets item to $this->Application->School.
    //*
    //*

    function HandleEdit($echo=TRUE,$formurl=NULL,$title="")
    {
        if ($this->GetGET("School")>0)
        {
            $this->ItemHash=$this->ApplicationObj->School;
        }
        parent::HandleEdit($echo,$formurl,$title);
    }

    //*
    //* function HandleShow, Parameter list: $title=""
    //*
    //* Overrides Table HandleShow: Sets item to $this->Application->School.
    //*
    //*

    function HandleShow($title="")
    {
        if ($this->GetGET("School")>0)
        {
            $this->ItemHash=$this->ApplicationObj->School;
        }
        parent::HandleShow($title);
    }

    //*
    //* function HandleTitleGroup, Parameter list: $type
    //*
    //* Shows just a two-group edit screen for school:
    //* HTML or Latex layout setup data.
    //*
    //*

    function HandleTitleGroup($type)
    {
        if ($this->GetGET("School")>0)
        {
            $this->ItemHash=$this->ApplicationObj->School;
        }

        foreach (array_keys($this->ItemDataSGroups) as $group)
        {
            if (!preg_match('/^'.$type.'(Titles|Icons)$/',$group))
            {
                unset($this->ItemDataSGroups[ $group ]);
            }
            else
            {
                $this->ItemDataSGroups[ $group ][ "Admin" ]=1;
                $this->ItemDataSGroups[ $group ][ "Secretary" ]=1;

                $this->ItemDataSGroups[ $group ][ "Single" ]=TRUE;
            }
        }

        parent::HandleEdit();
    }
    //*
    //* function HandleHtmlTitles, Parameter list: 
    //*
    //* Shows just a two-group edit screen for school:
    //* HTML layout setup data.
    //*
    //*

    function HandleHtmlTitles()
    {
        $this->HandleTitleGroup("Html");
    }

    //*
    //* function HandleLatexTitles, Parameter list: 
    //*
    //* Shows just a two-group edit screen for school:
    //* Latex - or printables - layout setup data.
    //*
    //*

    function HandleLatexTitles()
    {
        $this->HandleTitleGroup("Latex");
    }

 }

?>