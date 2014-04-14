<?php



class ClassDiscsTeachersSelect extends ClassDiscsSelectForm
{
    var $Teachers=array();
    var $Schools=array();

    //*
    //* function ReadTeacher, Parameter list: $tid 
    //*
    //* Readteacher by $id.
    //* 
    //*

    function ReadTeacher($tid)
    {
        return $this->ApplicationObj->UsersObject->SelectUniqueHash
        (
           "",
           array("ID" => $tid),
           FALSE,
           array("Name","Email","Status",)
        );
    }

    //*
    //* function ReadTeachers, Parameter list: 
    //*
    //* Reads possible teachers into hash
    //* 
    //*

    function ReadTeachers()
    {
        if (!empty($this->Teachers)) { return; }

        $teachers=$this->ApplicationObj->UsersObject->SelectHashesFromTable
        (
           "",
           array
           (
              "Status" => 1,
              "Profile_Teacher" => 2,
           ),
           array("ID","Name","Email","School","Status"),
           TRUE,
           "Name"
        );

        $this->Teachers=array();
        foreach ($teachers as $teacher)
        {
            $school=$teacher[ "School" ];
            if ($school==0) { continue; }

            if (empty($this->Teachers[ $school ]))
            {
                $this->Teachers[ $school ]=array();
            }

            $sort=$this->Html2Sort($teacher[ "Name" ].$teacher[ "ID" ]);
            $this->Teachers[ $school ][ $sort ]=$teacher;
        }

        $schools=array();
        foreach (array_keys($this->Teachers) as $school)
        {
            $schoolname=$this->Html2Sort
            (
               $this->ApplicationObj->SchoolsObject->MySqlItemValue
               (
                  "",
                  "ID",
                  $school,
                  "ShortName"
               )
            );
            $schools[ $schoolname ]=$school;
        }

        $schoolnames=array_keys($schools);
        sort($schoolnames);
        $this->Schools=array();
        foreach ($schoolnames as $schoolname)
        {
            array_push($this->Schools,$schools[ $schoolname ]);
        }

        $this->Schools=preg_grep('/^'.$this->ApplicationObj->School[ "ID" ].'$/',$this->Schools,PREG_GREP_INVERT);
        array_unshift($this->Schools,intval($this->ApplicationObj->School[ "ID" ]));

        foreach ($this->Schools as $school)
        {
            $teachers=$this->Teachers[ $school ];
            $sorts=array_keys($teachers);
            sort($sorts);
            $this->Teachers[ $school ]=array();
            foreach ($sorts as $sort)
            {
                array_push($this->Teachers[ $school ],$teachers[ $sort ]);
            }
        }
    }


    //*
    //* function TeacherSelectName, Parameter list: $teacher 
    //*
    //* Returns selecvt teacher name in select list.
    //* 
    //*

    function TeacherSelectName($teacher)
    {
        $rname=$teacher[ "Name" ];
        if (empty($teacher[ "Email" ]))
        {
            $rname.=" - Sem Login!";
        }
        else
        {
            if (isset($emails[ $teacher[ "Email" ] ]))
            {
                $rname.=", Email Duplicada!!!";
            }
        }

        return $rname;
    }

    //*
    //* function TeacherSelectTitle, Parameter list: $teacher,$schoolname=""
    //*
    //* Returns selecvt teacher name in select list.
    //* 
    //*

    function TeacherSelectTitle($teacher,$schoolname="")
    {
        if (empty($teacher[ "Email" ])) { $teacher[ "Email" ]="-"; }

        $rtitle=
            $teacher[ "Name" ].":\n".
            "Status: ".$this->ApplicationObj->UsersObject->GetEnumValue("Status",$teacher)."\n".
            "Login: ".$teacher[ "Email" ]."\n".
            "";

        if (!empty($schoolname))
        {
            $rtitle.="Escola: ".$schoolname;
        }

        return $rtitle;

    }


    //*
    //* function MakeTeacherSelect, Parameter list: $disc,$data,$edit=0
    //*
    //* Creates Teacher select field.
    //* 
    //*

    function MakeTeacherSelect($data,$disc,$edit)
    {
        if ($edit!=1)
        {
            $teacher=$this->ApplicationObj->UsersObject->SelectUniqueHash
            (
               "",
               array("ID" => $disc[ $data ]),
               FALSE,
               array("Name","Email",)
            );

            return $teacher[ "Name" ];
        }

        $this->ReadTeachers();

        $rids=array();
        foreach ($this->Schools as $school)
        {
            foreach ($this->Teachers[ $school ] as $teacher)
            {
                $rids[ $teacher[ "ID" ] ]=1;
            }
        }

        $ids=array(0);
        $disableds=array(0);
        $names=array("");
        $titles=array("");
        $title="";

        if ($disc[ $data ]>0 && empty($rids[ $disc[ $data ] ]))
        {
            $teacher=$this->ReadTeacher($disc[ $data ]);
            $status=$this->ApplicationObj->UsersObject->GetEnumValue("Status",$teacher);

            array_push($ids,$disc[ $data ]);
            array_push($disableds,0);
            array_push($names,$teacher[ "Name" ]."- ".$status);
            $title=
                $teacher[ "Name" ]."\n".
                "Status: ".$status."\n".
                "Email: ".$teacher[ "Email" ]."\n".
                "";
            array_push($titles,$title);
        }

        $emails=array();
        foreach ($this->Schools as $school)
        {
            $schoolname=$this->ApplicationObj->SchoolsObject->MySqlItemValue("","ID",$school,"ShortName");

            array_push($ids,0);
            array_push($disableds,1);
            array_push
            (
               $names,
               "Escola ".$schoolname.", Profs.:"
            );
             array_push($titles,"Professores, Escola: ".$schoolname);

            foreach ($this->Teachers[ $school ] as $teacher)
            {
                $rtitle=$this->TeacherSelectTitle($teacher,$schoolname);

                array_push($ids,$teacher[ "ID" ]);
                array_push($names,$this->TeacherSelectName($teacher));
                array_push($titles,$rtitle);
                array_push($disableds,0);

                if ($teacher[ "ID" ]==$disc[ $data ]) { $title=$rtitle; }
            }
        }

        return $this->MakeSelectField
        (
           $data,
           $ids,
           $names,
           $disc[ $data ],
           $disableds,
           $titles,
           $title
        );
    }
}

?>