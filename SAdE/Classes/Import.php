<?php


class ClassesImport extends Common
{
    var $ImportData=array
    (
       "Period",
       array("Titulo" => "Title"),
       array("StartYear" => "Year"),
       array("StartSemester" => "Semester"),
       array("Stat" => "Status"),
       array("Turno" => "Shift"),
       array("ProfessorID" => "TeacherID"),
       array("ProfApoio" => "TeacherAssistID"),
       array("ProfRecursos" => "TeacherRecoursesID"),
        "NumberOfAlunos",
       "NumberOfInvisibleAlunos",
    );

    var $ImportShowData=array
    (
       "Period",
       "Title",
       "Name",
       "Year",
       "Semester",
       "Status",
       "Shift",
       "Teacher",
       "NumberOfAlunos",
       "NumberOfInvisibleAlunos",
    );

    var $Stats=array();

    //*
    //* function ImportClassPeriod, Parameter list: $file,$citem,$period,$diariosdir,$materiasdir,&$table,$importdiscs
    //*
    //* Reads periods referenced for class.
    //* 
    //*

    function ImportClassPeriod($file,$citem,$period,$diariosdir,$materiasdir,&$table)
    {

        if ($this->GetPOST("Classes")!=1) { return; }

        $key=basename($file);
        $perfile=preg_replace('/Diario\//',"",$period).".txt";
        $perhash=$this->ReadHash($perfile);
        $per=preg_replace('/.*Diario\/+/',"",$period);

        $grade=NULL;
        $rperiod=0;
        if (preg_match('/00\.(\d)/',$per,$matches))
        {
            $grade=$this->ApplicationObj->Grades[0][ "ID" ];
            $rperiod=$matches[1];
        }
        elseif (preg_match('/0[1-5]/',$per))
        {
            $grade=$this->ApplicationObj->Grades[1][ "ID" ];
            $rperiod=$per;
        }
        else
        {
            $grade=$this->ApplicationObj->Grades[2][ "ID" ];
            $rperiod=$per;
        }

        $sem=1;
        $year=0;
        $type=1;
        if (preg_match('/(\d+)\.(\d)/',$perhash[ "PeriodTitle" ],$matches))
        {
            $year=intval($matches[1]);
            $sem=intval($matches[2]);
            $type=2;
        }
        else
        {
            $year=intval($perhash[ "PeriodTitle" ]);
        }

        $years[ $year ]=1;

        $perhash=$this->ApplicationObj->PeriodsObject->SelectUniqueHash
        (
           "",
           array
           (
              "Year" => $year,
              "Type" => $type,
              "Semester" => $sem,
           )
        );

        $gradeperhash=$this->ApplicationObj->GradePeriodsObject->SelectUniqueHash
        (
           "",
           array
           (
              "Grade" => $grade,
              "Year" => intval($rperiod),
           )
        );

        $item=$citem;
        $item[ "School" ]=$this->ApplicationObj->School[ "ID" ];
        $item[ "Grade" ]=$grade;
        $item[ "Period" ]=$perhash[ "ID" ];
        $item[ "Year" ]=$year;

        $item[ "GradePeriod" ]=$gradeperhash[ "ID" ];
        foreach (array("NAssessments","AssessmentType","AbsencesType","NRecoveries")  as $key)
        {
            $item[ $key ]=$gradeperhash[ $key ];
        }

        $item[ "AssessmentType" ]=$gradeperhash[ "AssessmentType" ];
        $item[ "AbsencesType" ]=$gradeperhash[ "AbsencesType" ];

        $item[ "FileKey" ]=preg_replace('/\.txt$/',".inf",$file);

        $tid=intval($item[ "TeacherID" ]);

        if ($tid>0)
        {
            $teacher=$this->ApplicationObj->UsersObject->SelectUniqueHash
            (
               "",
               array
               (
                  "School" => $item[ "School" ],
                  "UniqueID" => $item[ "TeacherID" ],
               ),
               TRUE,
               array("ID")
            );

            if (isset($teacher[ "ID" ]))
            {
                $item[ "Teacher" ]=$teacher[ "ID" ];
            }
        }


        $where=array
        (
           "School" => $item[ "School" ],
           "Period" => $item[ "Period" ],
           "Grade" => $item[ "Grade" ],
           "GradePeriod" => $item[ "GradePeriod" ],
           "FileKey" => $item[ "FileKey" ],
        );

        $msg=$this->AddOrUpdate("",$where,$item);
        array_push
        (
           $table,
           array
           (
              "","Import Class ".$item[ "ID" ]." - ".$item[ "AbsencesType" ],
              $msg
           )
        );

        if ($this->GetPOST("Discs")==1)
        {
            array_push($table,array($this->H(5,"Disciplines")));
            $discs=$this->ApplicationObj->ClassDiscsObject->Import
            (
               $item,
               $key,
               $diariosdir,
               $materiasdir,
               basename($period),
               $table
            );
        }
        else
        {
            array_push($table,array($this->H(5,"Disciplines Omitted")));
        }

        if ($this->GetPOST("Students")==1)
        {
            array_push($table,array($this->H(5,"Students")));
            $this->ApplicationObj->ClassStudentsObject->Import
            (
               $item,
               $key,
               $diariosdir,
               basename($period),
               $discs,
               $table
            );
        }
        else
        {
            array_push($table,array($this->H(5,"Students Omitted")));
        }
    }

    //*
    //* function ImportClassPeriods, Parameter list: $file,&$table,$importdiscs
    //*
    //* Reads periods referenced for class.
    //* 
    //*

    function ImportClassPeriods($file,$citem,&$table,$importdiscs)
    {
        $perioddir=preg_replace('/\.(inf|txt)$/',"/",$file);
        $diariosdir=preg_replace('/\.(inf|txt)$/',"/Diario/",$file);
        $materiasdir=preg_replace('/\.(inf|txt)$/',"/Materias/",$file);
        $key=basename($file);
        $key=preg_replace('/\.(inf|txt)$/',"",basename($file));

        if (!file_exists($diariosdir))
        {
            return;
        }

        $periods=$this->DirSubdirs($diariosdir);
        sort($periods);

        $years=array();
        foreach ($periods as $period)
        {
            array_push($table,array("Import Period ",$period));
            $this->ImportClassPeriod($file,$citem,$period,$diariosdir,$materiasdir,$table,$importdiscs);
        }
    }

    //*
    //* function ImportClass, Parameter list: $file,&$table,$importdiscs
    //*
    //* Does the import process for a $file, ex. 2010.01.01.inf.
    //* 
    //*

    function ImportClass($file,&$table,$importdiscs)
    {
        $lines=$this->MyReadFile($file);

        $inffile=preg_replace('/\.txt$/',".inf",$file);
        $class=array();
        if (file_exists($inffile))
        { 
            $class=$this->ReadHash( preg_replace('/\.txt$/',".inf",$file) );

            $key=basename($file);

            $this->ApplicationObj->ClassLog[ $key ]=array();

            $citem=array();
            foreach ($this->ImportData as $data)
            {
                $rdata=$data;
                if (is_array($data))
                {
                    foreach ($data as $key => $value)
                    {
                        $rdata=$value;
                        $data=$key;

                        break;
                    }
                }

                if (isset($this->ItemData[ $rdata ]) && $this->ItemData[ $rdata ][ "Sql" ]=="ENUM")
                {
                    $rrvalue=$class[ $data ];
                    $value=$this->Html2Text($rrvalue);
                    $value=$this->Text2Sort($value);
                    $value=strtolower($value);
                    $val=0;

                    $text=$data.", ".$value.":<BR>";

                    $k=1;
                    foreach ($this->ItemData[ $rdata ][ "Values" ] as $rvalue)
                    {
                        $rvalue=$this->Html2Text($rvalue);
                        $rvalue=$this->Text2Sort($rvalue);
                        $rvalue=strtolower($rvalue);

                        if ($value==$rvalue)
                        {
                            $val=$k;
                        }

                        $k++;
                    }

                    if ($val>0)
                    {
                        //print "OK, ".$this->ItemData[ $rdata ][ "Values" ][ $val-1 ];
                    }
                    else
                    {
                        if (!empty($this->ItemData[ $rdata ][ "Default" ]))
                        {
                            $val=$this->ItemData[ $rdata ][ "Default" ];
                        }
                        else
                        {
                            $this->ApplicationObj->AddImportLogEntry
                            (
                               $file,
                               $text.", ".$rrvalue." NOT! ".
                               join(", ",$this->ItemData[ $rdata ][ "Values" ])
                            );
                        }
                    }
    
                    $citem[ $rdata ]=$val;
                }
                elseif (isset($class[ $data ]))
                {
                    $citem[ $rdata ]=$class[ $data ];
                }
            }

            $citem[ "Name" ]=preg_replace('/\s*\d\d\d\d\s*/',"",$citem[ "Title" ]);
            $citem[ "Name" ]=preg_replace('/\s*\-\s*/',"",$citem[ "Name" ]);

            $this->ImportClassPeriods($file,$citem,$table,$importdiscs);
        }
    }

    //*
    //* function ImportForm, Parameter list:
    //*
    //* Creates form for importing file NAME from SAdE, as does the importing.
    //* 
    //*

    function ImportForm()
    {
        $this->ApplicationObj->GradeObject->ReadGrades();

        $path=$this->GetPOST("Path");
        $files=array();
        if (!empty($path))
        {
            if (is_dir($path))
            {
                $files=$this->DirFiles($this->GetPOST("Path"),'\d\d\d\d\.\d\d\.\d\d\.inf');
                sort($files);
            }
            elseif (is_file($path))
            {
                $path=preg_replace('/\.txt$/',".inf",$path);
                $files=array($path);
            }
        }

        $table=array
        (
           array
           (
              $this->B("Path to Class Files:"),
              $this->MakeInput("Path",$this->GetPOST("Path"),50)
           ),
           array
           (
              $this->B("Classes:"),
              $this->HtmlInputCheckBox("Classes",1),
              $this->B("Discs:"),
              $this->HtmlInputCheckBox("Discs",1),
              $this->B("Lessons Given:"),
              $this->HtmlInputCheckBox("Lessons",1),
              ""
           ),
           array
           (
              $this->B("Students:"),
              $this->HtmlInputCheckBox("Students",1),
              $this->B("Marks:"),
              $this->HtmlInputCheckBox("Marks",1),
              $this->B("Absences:"),
              $this->HtmlInputCheckBox("Absences",1),
              $this->B("Statuses:"),
              $this->HtmlInputCheckBox("Statuses",1),
              $this->B("Observations:"),
              $this->HtmlInputCheckBox("Observations",1),
              ""
           ),
           array
           (
              $this->B("All Data:"),
              $this->HtmlInputCheckBox("AllData",1),
              ""
           ),
           array
           (
              $this->B("All Classes:"),
              $this->HtmlInputCheckBox("All",1,FALSE),
              ""
           ),
           array
           (
              $this->B("Force Student Read:"),
              $this->HtmlInputCheckBox("Force",1,FALSE),
              ""
           ),
        );

        $ncells=0;
        $rfiles=array();
        foreach ($files as $file)
        {
            $fname=basename($file);
            $year=preg_replace('/^(\d\d\d\d).*/',"\$1",$fname);

            if (!isset($rfiles[ $year ])) { $rfiles[ $year ]=array(); }

            array_push($rfiles[ $year ],$fname);
            if (count($rfiles[ $year ])>$ncells) { $ncells=count($rfiles[ $year ]); }
        }

        $ncells*=2;
        foreach ($rfiles as $year => $fnames)
        {
            $row=array();
            foreach ($fnames as $fname)
            {
                $name=preg_replace('/\./',"",$fname);
                array_push
                (
                   $row,
                      $this->B($fname.":"),
                      $this->HtmlInputCheckBox($name,1)
                );

            }

            if (count($row)<$ncells) { array_push($row,""); }
            array_push($table,$row);
        }

        array_push
        (
           $table,
           array
           (
              $this->Button("submit","Importar").
              $this->MakeHidden("Process",1)
           )
        );

        print
            $this->H(2,"Importar ".$this->ItemsName).
            $this->StartForm().
            $this->Center
            (
                $this->HtmlTable("",$table)
            ).
            $this->EndForm().
            "";


        //$this->ClassStats();

        if ($this->GetPOST("Process")==1)
        {
            $all=$this->GetPOST("All");
            $only=$this->GetPOST("Only");
            if ($only==1) { $only=FALSE; }
            else          { $only=TRUE; }

            $table=array();
            foreach ($files as $file)
            {
                $fname=basename($file);
                $name=preg_replace('/\./',"",$fname);
                if ($all==1 || $this->GetPOST($name)==1 || count($files)==1)
                {
                    array_push($table,array($this->H(3,$file.":")));

                    $this->ImportClass($file,$table,$only);
                }
            }
            
            //print $this->ClassInfo();
            print $this->HtmlTable("",$table);

        }
    }

    //*
    //* function GetSchoolTables, Parameter list: $schoolid
    //*
    //* Returns of sql tables in DB pertaining to 
    //* 
    //*

    function GetSchoolTables($schoolid)
    {
        $sqltables=$this->GetDBTableNames();
        $sqltables=preg_grep('/^'.$schoolid.'_/',$sqltables);
        sort($sqltables);

        return $sqltables;
    }

    //*
    //* function GetPeriods, Parameter list: $schoolid
    //*
    //* Returns of Period names.
    //* 
    //*

    function GetPeriods($schoolid)
    {
        $sqltables=$this->GetSchoolTables($schoolid);

        $periods=array();
        foreach ($sqltables as $sqltable)
        {
            $comps=preg_split('/_/',$sqltable);

            $period=NULL;
            $type=NULL;

            if (count($comps)>2)
            {
               $periods[ $comps[1] ]=1;
            }
            elseif (count($comps)==2)
            {
               $periods[ $comps[0] ]=1;
            }
        }

        return preg_grep('/\d\d\d\d/',array_keys($periods));
    }
    //*
    //* function GetTypes, Parameter list: $schoolid
    //*
    //* Returns of typess.
    //* 
    //*

    function GetTypes($schoolid)
    {
        $sqltables=$this->GetSchoolTables($schoolid);

        $types=array();
        foreach ($sqltables as $sqltable)
        {
            $comps=preg_split('/_/',$sqltable);

            $period=NULL;
            $type=NULL;

            if (count($comps)>2)
            {
               $types[ $comps[2] ]=1;
            }
            elseif (count($comps)==2 && preg_match('/^ \d\d\d\d/',$comps[0]))
            {
                $types[ $comps[1] ]=1;
            }
        }

        return array_keys($types);
    }

    //*
    //* function ClassTableStats, Parameter list: $sqltable
    //*
    //* Provides stats of classes (by id) in table $sqltable.
    //* 
    //*

    function ClassTableStats($sqltable)
    {
        $nentries=$this->MySqlNEntries($sqltable);

        $cell=$nentries;
        if (isset($this->Stats[ $sqltable ])) { $cell.="/".$this->Stats[ $sqltable ]; }

        if ($nentries>0)
        {
            $classids=$this->MySqlUniqueColValues($sqltable,"Class");

            $rtable=array($this->B(array("Class ID:","No:")));
            foreach ($classids as $classid)
            {
                array_push
                (
                   $rtable,
                   array
                   (
                      $classid,
                      $this->MySqlNEntries($sqltable,array("Class" => $classid))
                   )
                );
            }

            $cell.=
                "<BR>".
                $this->HtmlTable("",$rtable);
        }

        $this->Stats[ $sqltable ]=$nentries;

        return $cell;
    }

    //*
    //* function ClassStats, Parameter list: 
    //*
    //* Produces a table with statistics loaded.
    //* 
    //*

    function ClassStats()
    {
        $schoolid=$this->ApplicationObj->School[ "ID" ];
        $sqltables=$this->GetSchoolTables($schoolid);
  
        $periods=$this->GetPeriods($schoolid);
        $types=$this->GetTypes($schoolid);


        $table=array($this->H(3,"Stats:"));
        $row=array("");

        foreach ($periods as $period)
        {
            array_push($row,$this->B($period));                
        }
        array_push($table,$row);

        foreach (array("Classes","Students") as $type)
        {
            array_push($table,array($this->B($type.":"),$this->MySqlNEntries($schoolid."_".$type),""));

        }

        foreach ($types as $type)
        {
            $row=array($this->B($type.":"));
            foreach ($periods as $period)
            {
                $sqltable=preg_grep('/'.$period."_".$type.'$/',$sqltables);
                $sqltable=array_pop($sqltable);

                array_push($row,$this->ClassTableStats($sqltable));
            }

            array_push($table,$row);
        }


        return $this->Html_Table
        (
           "",
           $table
        );
    }

    //*
    //* function ClassPeriodStats, Parameter list: 
    //*
    //* Produces a table with statistics loaded.
    //* 
    //*

    function ClassPeriodStats()
    {
        $schoolid=$this->ApplicationObj->School[ "ID" ];
        $sqltables=$this->GetSchoolTables($schoolid);
  
        $types=$this->GetTypes($schoolid);

        $period=$this->ApplicationObj->GetPeriodName($this->ApplicationObj->Period);

        $table=array($this->H(3,"Stats: ".$period));
        $row=array("");

        foreach (array("Classes","Students") as $type)
        {
            array_push($table,array($this->B($type.":"),$this->MySqlNEntries($schoolid."_".$type),""));

        }

        foreach ($types as $type)
        {
            array_push($row,$this->B($type));                
        }

        array_push($table,$row);


        $row=array("");
        foreach ($types as $type)
        {
            $sqltable=preg_grep('/'.$period."_".$type.'$/',$sqltables);
            $sqltable=array_pop($sqltable);

            array_push($row,$this->ClassTableStats($sqltable));
        }

        array_push($table,$row);

        return $this->Html_Table
        (
           "",
           $table
        );
    }
    //*
    //* function HandleStats, Parameter list: $file,&$table,$importdiscs
    //*
    //* Produces a table with statistics loaded.
    //* 
    //*

    function HandleStats()
    {
        print 
            $this->H(3,"Estatisticas").
            $this->ClassPeriodStats();
    }
}

?>