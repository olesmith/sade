<?php



class ClassesRead extends ClassesImport
{
    //*
    //* function ClassSortName, Parameter list: $class
    //*
    //* Returns sort class name
    //*

    function ClassSortName($class)
    {
        $name="";
        if (!empty($class[ "Name" ]))
        {
            $name.=$class[ "Name" ].$class[ "Name" ];
        }
        if (!empty($class[ "GradePeriod" ]))
        {
            $name.=
                $this->ApplicationObj->GradePeriodsObject->MySqlItemValue
                (
                   "",
                   "ID",
                   $class[ "GradePeriod" ],
                   "Name",
                   TRUE
                ).
                "";
        }

        return $name;
    }

    //*
    //* function ClassName, Parameter list: $class
    //*
    //* Returns qualified class name
    //*

    function ClassName($class)
    {
        $name="";
        if (!empty($class[ "GradePeriod" ]))
        {
            $name.=
                $this->ApplicationObj->GradePeriodsObject->MySqlItemValue
                (
                   "",
                   "ID",
                   $class[ "GradePeriod" ],
                   "Name",
                   TRUE
                ).
                ", ";
        }
        if (!empty($class[ "Name" ]))
        {
            $name.=$class[ "Name" ];
        }

        return $name;
    }
    
    //*
    //* function GetItemName, Parameter list: $class=array(),$datas=array()
    //*
    //* Overrides MySql2 GetItemName to get a better clas name.
    //*

    function GetItemName($class=array(),$datas=array())
    {
        return $this->ClassName($class);
    }
    

   //*
    //* function ReadClass, Parameter list :$readdiscs=TRUE
    //*
    //* Reads class, id being GET Class.
    //* 
    //*

    function ReadClass($readdiscs=TRUE)
    {
        $classid=$this->GetGET("Class");
        if (empty($classid))
        {
            $classid=$this->GetGET("ID");
        }

        if (!empty($this->ApplicationObj->School) && !empty($classid) && preg_match('/^\d+$/',$classid) && $classid>0)
        {
            if (!$this->MySqlIsTable($this->ApplicationObj->School[ "ID" ]."_Classes"))
            {
                return;
            }

            $this->ApplicationObj->Class=$this->SelectUniqueHash
            (
              $this->ApplicationObj->School[ "ID" ]."_Classes",
              array("ID" => $classid)
            );

            if (!empty($this->ApplicationObj->Class[ "Grade" ]))
            {
                $this->ApplicationObj->Grade=$this->SelectUniqueHash
                (
                   "Grade",
                   array("ID" => $this->ApplicationObj->Class[ "Grade" ])
                );
            }


            if (!empty($this->ApplicationObj->Class[ "GradePeriod" ]))
            {
                $this->ApplicationObj->GradePeriod=$this->ApplicationObj->GradePeriodsObject->SelectUniqueHash
                (
                   "",
                   array("ID" => $this->ApplicationObj->Class[ "GradePeriod" ])
                );
            }

            if (!empty($this->ApplicationObj->Class[ "Teacher" ]))
            {
                $this->ApplicationObj->Teacher=$this->ApplicationObj->PeopleObject->SelectUniqueHash
                (
                   "People",
                   array("ID" => $this->ApplicationObj->Class[ "Teacher" ])
                );
            }
        }
    }

    //*
    //* function ReadPeriodNonTeacherClasses, Parameter list: $school,$period
    //*
    //* Reads classes for non teacher.
    //*

    function ReadPeriodNonTeacherClasses($school,$period)
    {
        $this->AddDBField("","Number");
        $classes=$this->SelectHashesFromTable
        (
           "",
           array
           (
              "School" => $school[ "ID" ],
              "Period" => $period[ "ID" ],
           ),
           array("ID","Name","Grade","GradePeriod","FileKey","NStudents","NInactive","Number"),
           FALSE,
           "Grade,GradePeriod","Name"
        );

        $rclasses=array();
        foreach ($classes as $id => $class)
        {
            $class[ "Name" ]=$this->ClassName($class);

            $sortkey=
                sprintf("%02d",$class[ "Number" ]).
                $this->ClassSortName($class).
                $id;

            $rclasses[ $sortkey ]=$class;
        }

        $names=array_keys($rclasses);
        sort($names);

        $this->ApplicationObj->Classes=array();
        foreach ($names as $name)
        {
            array_push($this->ApplicationObj->Classes,$rclasses[ $name ]);
        }

        return $this->ApplicationObj->Classes;
    }

    //*
    //* function ReadPeriodTeacherClasses, Parameter list: $school,$period
    //*
    //* Reads classes for teacher.
    //*

    function ReadPeriodTeacherClasses($school,$period)
    {
        $datas=array("Teacher","Teacher1","Teacher2");
        foreach (array_keys($datas) as $id)
        {
            $datas[ $id ].="='".$this->LoginData[ "ID" ]."'";
        }

        $classids=$this->ApplicationObj->ReadTeacherDiscs($this->LoginData[ "ID" ]);
        //Classes as teacher
        //$classestable=$this->ApplicationObj->School[ "ID" ]."_Classes";
        $classes=$this->SelectHashesFromTable
        (
           "",
           "ID IN ('".join("','",$classids)."')",
           array("ID"),
           FALSE,
           "ID",
           TRUE
        );

        //ClassDiscs as teacher
        $periodname=$this->ApplicationObj->GetPeriodName($period);
        //$classdiscstable=$this->School[ "ID" ]."_".$periodname."_ClassDiscs";

        $schoolperwhere=
            "School='". $school[ "ID" ]."' AND ".
            "Period='".$period[ "ID" ]."' AND ";

        $discs=$this->ApplicationObj->ClassDiscsObject->SelectHashesFromTable
        (
           $this->ApplicationObj->SchoolPeriodSqlTableName("ClassDiscs"),
           $schoolperwhere.
           "(".join(" OR ",$datas).")",
           array("ID","Class"),
           FALSE,
           "ID"
        );

        $rclasses=array();
        foreach ($classes as $class)
        {
            $rclasses[ $class[ "ID" ] ]=1;
        }
        foreach ($discs as $disc)
        {
            $rclasses[ $disc[ "Class" ] ]=1;
        }

        $classes=array_keys($rclasses);

        $classes=$this->SelectHashesFromTable
        (
           "",
           $schoolperwhere.
           " ID IN ('".join("','",$classes)."')",
           array("ID","Name","Grade","GradePeriod","FileKey","NStudents","NInactive"),
           FALSE,
           "Grade,GradePeriod","Name"
        );

        $rclasses=array();
        foreach ($classes as $id => $class)
        {
            $class[ "Name" ]=$this->ClassName($class);
            $rclasses[ $class[ "Name" ].$id ]=$class;
        }

        $names=array_keys($rclasses);
        sort($names);

        $this->ApplicationObj->Classes=array();
        foreach ($names as $name)
        {
            array_push($this->ApplicationObj->Classes,$rclasses[ $name ]);

        }

        return $this->ApplicationObj->Classes;
    }

    //*
    //* function ReadPeriodClasses, Parameter list: $school,$period
    //*
    //* Reads classes, branches for Teacher and non Teacher
    //*

    function ReadPeriodClasses($school,$period)
    {
        if (preg_match('/Teacher/',$this->Profile))
        {
            return $this->ReadPeriodTeacherClasses($school,$period);
        }
        else
        {
            return $this->ReadPeriodNonTeacherClasses($school,$period);
        }
    }
}

?>