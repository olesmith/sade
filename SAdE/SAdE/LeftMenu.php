<?php


class SAdELeftMenu extends SAdEInfoTable
{
    var $TeacherSchoolModule="Classes";
    var $TeacherSchoolAction="DaylyTeacher";
    var $NonTeacherSchoolModule="Schools";
    var $NonTeacherSchoolAction="Show";

    var $TeacherPeriodAction="DaylyTeacher";
    var $NonTeacherPeriodAction="Show";
    var $TeacherPeriodModule="Classes";
    var $NonTeacherPeriodModule="Periods";


    var $NonTeacherClassAction="Show";
    var $TeacherClassAction="DaylyTeacher";
    var $TeacherClassModule="Classes";
    var $NonTeacherClassModule="Classes";

    //*
    //* function SchoolMenu, Parameter list: 
    //*
    //* Produces the avaliabe School left menu.
    //*

    function SchoolMenu()
    {
        $submenu=$this->ReadPHPArray("System/Schools/LeftMenu.php");
        
        $html=
            $this->GenerateSubMenu($submenu,$this->School).
            $this->HtmlList($this->PeriodsMenu(),"UL",array("CLASS" => 'leftmenulist')).
            "";

        return $html;
    }

    //*
    //* function SchoolsMenuLink, Parameter list: $item
    //*
    //* Returns link to use in Schools menu.
    //*

    function SchoolsMenuLink()
    {
        $args=array
        (
           "Unit"       => $this->Unit[ "ID" ],
           "School"     => "#ID",
           "ModuleName" => "Schools",
        );

        if (preg_match('/^(Teacher)$/',$this->Profile))
        {
            $args[ "Action" ]=$this->TeacherSchoolAction;
            $args[ "ModuleName" ]=$this->TeacherSchoolModule;
        }
        else
        {
            $args[ "Action" ]=$this->NonTeacherSchoolAction;
            $args[ "ModuleName" ]=$this->NonTeacherSchoolModule;
        }

        return "?".$this->Hash2Query($args);
    }

    //*
    //* function SchoolsMenu, Parameter list: $item
    //*
    //* Produces the avaliabe Schools left menu.
    //* Activated from System/LeftMenu.php.
    //*

    function SchoolsMenu()
    {
        $school=$this->GetGET("School");
        if (!empty($school))
        {
            $this->ReadSchool();
        }

        $this->ReadSchools();


        return $this->GenerateItemListSubMenu
        (
           "SchoolMenu", //menu generator
           $this->Schools,
           $school,
           $this->SchoolsMenuLink(),
           "#ShortName",
           "#Title"
        );
    }


    //*
    //* function PeriodMenu, Parameter list: 
    //*
    //* Produces the avaliabe Period left menu.
    //*

    function PeriodMenu()
    {
        $submenu=$this->ReadPHPArray("System/Periods/LeftMenu.php");
        
        return
            $this->GenerateSubMenu($submenu,$this->Period).
            $this->HtmlList($this->ClassesMenu(),"UL",array("CLASS" => 'leftmenulist')).
           "";
    }

    //*
    //* function PeriodsMenuLink, Parameter list: $item
    //*
    //* Returns link to use in Periods left menu.
    //*

    function PeriodsMenuLink()
    {
        $args=array
        (
           "Unit"       => $this->Unit[ "ID" ],
           "School"     => $this->School[ "ID" ],
           "ModuleName" => "Periods",
           "Period"     => "#ID",
        );

        if (preg_match('/^(Teacher)$/',$this->Profile))
        {
            $args[ "Action" ]=$this->TeacherPeriodAction;
            $args[ "ModuleName" ]=$this->TeacherPeriodModule;
        }
        else
        {
            $args[ "Action" ]=$this->NonTeacherPeriodAction;
            $args[ "ModuleName" ]=$this->NonTeacherPeriodModule;
        }

        return "?".$this->Hash2Query($args);
    }

    //*
    //* function PeriodsMenu, Parameter list: 
    //*
    //* Produces the avaliabe Periods left menu.
    //*

    function PeriodsMenu()
    {
        if (empty($this->School)) { return ""; }

        $this->ReadSchoolPeriods();

        $currentperiod=$this->GetGET("Period");
        //Default first period
        if (empty($currentperiod) && isset($this->Periods[0][ "ID" ]))
        {
            $currentperiod=$this->Periods[0][ "ID" ];
        }


        return $this->GenerateItemListSubMenu
        (
           "PeriodMenu", //menu generator
           $this->Periods,
           $currentperiod,
           $this->PeriodsMenuLink(),
           "#Name",
           "#Name, #NClasses Turmas"
        );
    }


    //*
    //* function ClassMenu, Parameter list: 
    //*
    //* Returns qualified class name
    //*

    function ClassMenu()
    {
        $submenu=$this->ReadPHPArray("System/Classes/LeftMenu.php");
        $discsmenu="";

        if ($this->Profile=="Teacher")
        {
            $discsmenu=$this->HtmlList
            (
               $this->DiscsMenu($this->Class),
               "UL",
               array("CLASS" => 'leftmenulist')
            );
        }

        return
            $this->GenerateSubMenu($submenu,$this->Class).
            $discsmenu.
            "";
    }

    //*
    //* function ClassesMenuLink, Parameter list: $item
    //*
    //* Returns link to use in Classes left menu.
    //*

    function ClassesMenuLink()
    {
        $args=array
        (
           "Unit"       => $this->Unit[ "ID" ],
           "School"     => $this->School[ "ID" ],
           "ModuleName" => "Classes",
           "Period"     => $this->Period[ "ID" ],
           "Class"     => "#ID",
        );

        if (preg_match('/^(Teacher)$/',$this->Profile))
        {
            $args[ "Action" ]=$this->TeacherClassAction;
            $args[ "ModuleName" ]=$this->TeacherClassModule;
        }
        else
        {
            $args[ "Action" ]=$this->NonTeacherClassAction;
            $args[ "ModuleName" ]=$this->NonTeacherClassModule;
        }

        return "?".$this->Hash2Query($args);
    }

    //*
    //* function ClassesMenu, Parameter list: 
    //*
    //* Returns qualified class name
    //*

    function ClassesMenu()
    {
        if (empty($this->School)) { return array(); }
        if (empty($this->Period)) { return array(); }

        $currentclass=intval($this->GetGET("Class"));

        return $this->GenerateItemListSubMenu
        (
           "ClassMenu", //menu generator
           $this->ClassesObject->ReadPeriodClasses($this->School,$this->Period),
           $this->GetGET("Class"),
           $this->ClassesMenuLink(),
           "#Name",
           "#Name, #NStudents Alunos"
        );
    }



    //*
    //* function DiscsMenuLink, Parameter list: $item
    //*
    //* Returns link to use in Classes left menu.
    //*

    function DiscsMenuLink()
    {
        $args=array
        (
           "Unit"       => $this->Unit[ "ID" ],
           "School"     => $this->School[ "ID" ],
           "ModuleName" => "Classes",
           "Action" => "Dayly",
           "Period"     => $this->Period[ "ID" ],
           "Class"     => $this->Class[ "ID" ],
           "Disc"     => "#ID",
        );

        return "?".$this->Hash2Query($args);
    }


    //*
    //* function DiscMenu, Parameter list:
    //*
    //* Returns qualified class name
    //*

    function DiscMenu()
    {
        return "";
    }

    //*
    //* function DiscsMenu, Parameter list: $class
    //*
    //* Returns qualified class name
    //*

    function DiscsMenu($class)
    {
        $discs=$this->ReadClassTeacherDiscs($this->LoginData[ "ID" ],$class);

        return $this->GenerateItemListSubMenu
        (
           "DiscMenu", //menu generator
           $this->ReadClassTeacherDiscs($this->LoginData[ "ID" ],$class),
           $this->GetGET("Disc"),
           $this->DiscsMenuLink(),
           "#Name",
           "#Name"
        );

        return array("Discs");
    }

}

?>
