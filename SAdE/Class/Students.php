<?php

include_once("Class/Students/Import.php");
include_once("Class/Students/Show.php");
include_once("Class/Students/Prints.php");


class ClassStudents extends ClassStudentsPrints
{

    //*
    //* Variables of ClassStudents class:
    //*


    //*
    //*
    //* Constructor.
    //*

    function ClassStudents($args=array())
    {
        $this->Hash2Object($args);
        $this->Sort=array("Student");
    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        $this->Sort=array("Student");

        for ($n=1;$n<=$this->ApplicationObj->MaxNAssessments;$n++)
        {
            $this->ItemData[ "MarksTrans".$n ]=array
            (
               "Name" => "Nota Transf. #".$n,
               "Title" => "Nota Transferida, #".$n,
               "Sql" => "VARCHAR(8)",

               "Public"      => 0,
               "Person"      => 0,
               "Admin"       => 1,

               "Clerk" => 2,
               "Teacher"     => 1,
               "Secretary" => 2,
               "Coordinator" => 1,             
            );
        }

    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->ReadSchool();
        $this->Sort=array("Student");
        $this->AddDBField("","Grade");
        $this->Actions[ "Remove" ][ "AccessMethod" ]="MayRemove";
    }
 
    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        if ($this->GetGET("ModuleName")!="Classes")
        {
            return $item;
        }

        return $item;
    }

    //*
    //* function MayRemove, Parameter list: $item=array()
    //*
    //* Decides whether ClassStudent is deletable.
    //*

    function MayRemove($item=array())
    {
        $student=$this->GetGET("Student");

        $item=$this->ApplicationObj->StudentsObject->SelectUniqueHash
        (
           "",
           array
           (
              "ID" => $student,
           ),
           TRUE,
           array("ID","Status","StatusDate1","StatusDate2")
        );

        if (empty($item)) { return FALSE; }

        return $this->ApplicationObj->StudentsObject->MayRemove($item);
    }

    //*
    //* function HandleRemove, Parameter list:
    //*
    //* Handles ClassStudent removal
    //*

    function HandleRemove()
    {
        if ($this->MayRemove())
        {
            $student=$this->GetGET("Student");

            $table=$this->ApplicationObj->SchoolPeriodSqlTableName("ClassStudents");
            $item=$this->SelectUniqueHash
            (
               $table,
               array
               (
                  "Student" => $student,
               ),
               TRUE
            );

            $args=$this->ScriptQueryHash();
            $args[ "Delete" ]=1;
            $dhref=$this->Href
            (
               "?".
               $this->Hash2Query($args),
               "Deletar"
            );

            $args[ "ModuleName" ]="Classes";
            $args[ "Action" ]="Students";
            unset($args[ "Student" ]);
            unset($args[ "Delete" ]);

            $href=$this->Href
            (
               "?".
               $this->Hash2Query($args),
               "Voltar para Turma"
            );

            if (!empty($item))
            {
                $sitem=$this->ApplicationObj->StudentsObject->SelectUniqueHash
                (
                   "",
                   array
                   (
                      "ID" => $student,
                   ),
                   TRUE,
                   array("ID","Name","Status","StatusDate1")
                );

                $title=
                    $this->Html_Table
                    (
                       "",
                       $this->ApplicationObj->StudentsObject->ItemTable
                       (
                          0,
                          $sitem,
                          TRUE,
                          array("Status","StatusDate1")
                       ),
                       array("ALIGN" => 'center')
                    ).
                    "";
                

                if ($this->GetPOST("Delete")==1)
                {
                    $this->MySqlDeleteItem($table,$item[ "ID" ],"ID");

                    print 
                        $this->H
                        (
                           1,
                           "Aluno(a):"
                        ).
                        $this->H
                        (
                           3,
                           $title
                        ).
                        $this->H
                        (
                           2,
                           "Deletado com êxito... ".
                           $href
                        ).
                        "";
                }
                else
                {
                    print 
                        $this->H
                        (
                           1,
                           "Deletar Aluno(a)?"
                        ).
                        $this->H
                        (
                           3,
                           $title
                        ).
                        $this->StartForm().
                        $this->MakeHidden("Delete",1).
                        $this->Center($this->Button("submit","&gt;&gt;DELETAR&lt;&lt;")).
                        $this->EndForm().
                        "";
                }
            }
            else
            {
                print
                    $this->H
                    (
                       3,
                       "Aluno(a) inexistente... ".
                       $href
                    ).
                    "";
            }
        }
        else
        {
            die("Not allowed...");
        }
    }



    //*
    //* function StudentMatriculatedAtDate, Parameter list: $student,$date
    //*
    //* Tests student is matriculated at date $date.
    //*

    function StudentMatriculatedAtDate($student,$date)
    {
        if ($student[ "StudentHash" ][ "MatriculaDate" ]<=$date)
        {
            return TRUE;
        }

        return FALSE;
    }

    //*
    //* function StudentActiveAtDate, Parameter list: $student,$date
    //*
    //* Tests student dates.
    //*

    function StudentActiveAtDate($student,$date)
    {
        if ($student[ "StudentHash" ][ "Status" ]==1)
        {
            return TRUE;
        }
        elseif ($student[ "StudentHash" ][ "StatusDate1" ]>$date)
        {
            return TRUE;
        }

        return FALSE;
    }


    //*
    //* function TestStudentDates, Parameter list: $student,$class,$currentdate,$limdate
    //*
    //* Tests student dates.
    //*

    function TestStudentDates($student,$class,$firstdate,$lastdate)
    {
        $startdate=$this->ApplicationObj->DatesObject->DateID2SortKey($class[ "Period_StartDate" ]);
        $enddate=$this->ApplicationObj->DatesObject->DateID2SortKey($class[ "Period_EndDate" ]);

        $res=0;
        if ($student[ "StudentHash" ][ "MatriculaDate" ]>$enddate)
        {
             //matriculated after period ended
            $res=1;
        }
        elseif ($student[ "StudentHash" ][ "MatriculaDate" ]>$firstdate)
        {
            //matriculated in the future, but before period ended
            $res=-2;
        }
        else
        {
            //Dates OK, test status
            if ($student[ "StudentHash" ][ "Status" ]!=1)
            {
                if ($student[ "StudentHash" ][ "StatusDate1" ]<=$startdate)
                {
                    $res=-1; //inactive, before beginning
                }
                elseif ($student[ "StudentHash" ][ "StatusDate1" ]>$lastdate)
                {
                    $res=0; //inactive, but active in beginning of period
                }
                else
                {
                    $res=2; //inactive
                }
            }
        }

        return $res;
    }




    //*
    //* function StudentsDailyOrder, Parameter list: $limdate,$students=array()
    //*
    //* Put students in dayly order.
    //*

    function StudentsDailyOrder($limdate,$students=array())
    {
        if (count($students)==0)
        {
            $students=$this->ItemHashes;
        }

        $rstudents=array();
        foreach ($students as $student)
        {
            $sortdate=$limdate;
            if ($student[ "StudentHash" ][ "MatriculaDate" ]>$limdate)
            {
                $sortdate=$student[ "StudentHash" ][ "MatriculaDate" ];
            }

            $key=
                $sortdate."_".
                $this->Html2Sort($student[ "StudentHash" ][ "Name" ])."_".
                $this->Html2Sort($student[ "StudentHash" ][ "ID" ])."_".$limdate."_".
                $this->Html2Sort($student[ "StudentHash" ][ "MatriculaDate" ]);

            $rstudents[ $key ]=$student;
        }

        $keys=array_keys($rstudents);
        sort($keys);

        $rrstudents=array();
        foreach ($keys as $key)
        {
            array_push($rrstudents,$rstudents[ $key ]);
        }

        return $rrstudents;
    }


    //*
    //* function DisplayTable, Parameter list: $edit,$tedit
    //*
    //* Put students in dayly order.
    //*

    function DisplayTable($edit,$tedit)
    {
        $this->ApplicationObj->ClassDiscsObject->DisplayTable("Student","Totals",0,0,FALSE);
    }

    //*
    //* function LatexStudents, Parameter list: 
    //*
    //* 
    //*

    function LatexStudents()
    {
        foreach (array_keys($this->ApplicationObj->Students) as $sid)
        {
            $this->ApplicationObj->Students[ $sid ][ "StudentHash" ]=
                $this->ApplicationObj->StudentsObject->TrimLatexItem
                (
                   $this->ApplicationObj->Students[ $sid ][ "StudentHash" ]
                );
        }
    }

    //*
    //* function MakeStartEndSelect, Parameter list: $data,$item,$edit
    //*
    //* Generates Start date selectfield.
    //* 
    //*

    function MakeStartEndSelect($data,$item,$edit)
    {
        if ($edit==0)
        {
            $field=$item[ $data ];
            if (intval($field)>0)
            {
                $field=$this->ApplicationObj->DatesObject->DateID2Name($field);
            }

            return $field;
        }

        $today=$this->ApplicationObj->DatesObject->GetTodayDatesItem();

        return $this->ApplicationObj->PeriodsObject->MakePeriodDaySelect
        (
           $data,
           $this->ApplicationObj->Period,
           $today[ "ID" ]
        );        
    }

    //*
    //* function MakeToFromClassField, Parameter list: $data,$item,$edit
    //*
    //* Returns To/FromClass name. Reads class from To/FromSchool class table.
    //* 
    //*

    function MakeToFromClassField($data,$item,$edit)
    {
        $sdata=preg_replace('/Class$/',"School",$data);

        return $this->MySqlItemValue
        (
           $item[ $sdata ]."_Classes",
           "ID",
           $item[ $data ],
           "NameKey"
        );
    }

}

?>