<?php


class ClassStudentsImport extends Common
{
    //*
    //* function ReadQuestStudents, Parameter list: $class,$dir,$discs
    //*
    //* Detects relevant student uniqueids from file structure.
    //* 
    //*

    function ReadQuestStudents($class,$dir,$discs)
    {
        $ids=array();
        if (is_dir($dir."/Quest"))
        {
            $files=$this->DirFiles($dir."/Quest",'^\d+\.txt$');
 
            $ids=array();
            foreach ($files as $file)
            {
                $matricula=basename($file);
                $matricula=preg_replace('/\.txt$/',"",$matricula);

                $ids[ $matricula ]=1;
            }
        }

        $ids=$this->ReadStudentsTxtFile($class,$dir,$ids);

        return array_keys($ids);
    }

     //*
    //* function ReadStudentsTxtFile, Parameter list: $class,$dir,$discs
    //*
    //* Reads from Student text file.
    //* 
    //*

    function ReadStudentsTxtFile($class,$dir,$ids)
    {
        if (count($ids)==0 && $this->GetPOST("Force")==1)
        {
            $comps=preg_split('/\//',$dir);
            array_pop($comps);array_pop($comps);array_pop($comps);

            $file=join("/",$comps).".txt";

            $lines=array();
            if (file_exists($file))
            {
                $lines=file($file);
                foreach ($lines as $line)
                {
                    $rcomps=preg_split('/\s+/',$line);
                    //Per UniqueID
                    $ids[ $rcomps[0] ]=1;
                }
            }
        }

        return $ids;
    }


     //*
    //* function ReadDirStudents, Parameter list: $class,$dir,$discs
    //*
    //* Detects relevant student uniqueids from file structure.
    //* 
    //*

    function ReadDirStudents($class,$dir,$discs)
    {
        if ($class[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
        {
            return $this->ReadQuestStudents($class,$dir,$discs);
        }

        $ids=array();
        $files=$this->DirFiles($dir,"(Observations|Date|Result)\.");

        foreach ($files as $file)
        {
            $id=preg_replace
            (
               '/(Observations|Date|Result)\./',
               "",
               preg_replace
               (
                  '/\.txt/',
                  "",
                  basename($file)
               )
            );

            $ids[ $id ]=1;
        }

        $comps=preg_split('/\//',$dir);

        foreach ($discs as $disc)
        {
            foreach (array("Notas","Faltas","Totais") as $type)
            {
                $file=$dir."/".$disc[ "Chave" ]."/".$type.".".$comps[6].".txt";

                $lines=array();
                if (file_exists($file))
                {
                    $lines=file($file);
                    foreach ($lines as $line)
                    {
                        $rcomps=preg_split('/\s+/',$line);
                        //Per UniqueID
                        $ids[ $rcomps[0] ]=1;
                    }
                }
            }
        }

        $ids=$this->ReadStudentsTxtFile($class,$dir,$ids);
        return array_keys($ids);
    }

    //*
    //* function ImportStudent, Parameter list: $dir,$class,$student,&$table
    //*
    //* 
    //* 
    //*

    function ImportStudentData($dir,$class,$student,&$table)
    {
        if ($student=="Total") { return; }

        $item=array
        (
           "UniqueID" => $student,
           "School" => $this->ApplicationObj->School[ "ID" ],
           "Class" => $class[ "ID" ],
           "Grade" => $class[ "Grade" ],
           "GradePeriod" => $class[ "GradePeriod" ],
        );

        $where=array
        (
           "UniqueID" => $student,
           "School" => $this->ApplicationObj->School[ "ID" ]
        );

        $studenthash=$this->ApplicationObj->StudentsObject->SelectUniqueHash
        (
           "",
           $where,
           TRUE,
           array("ID","Name","UniqueID")
        );

        if (!empty($studenthash))
        {
            $sid=$studenthash[ "ID" ];
            $item[ "Student" ]=$sid;

            $msg=$this->AddOrUpdate
            (
               "",
               array("Student" => $sid),
               $item
            );

            //print
            array_push
            (
             $table,
             array
             (
                "",
                "",
                "Import Student ".$item[ "ID" ],
                $msg,
                $studenthash[ "UniqueID" ],
                $studenthash[ "Name" ]
             )
            );

            $item[ "StudentHash" ]=$studenthash;

            return $item;
        }
        else
        {
            print "No such student: ";var_dump($where);
        }

        return NULL;
    }

    //*
    //* function ImportStudentDisc, Parameter list: $dir,$class,$student,$disc,&$table
    //*
    //* Calls functions for importing Marks, Absences and Totals.
    //* 
    //*

    function ImportStudentDisc($marksline,$absencesline,$class,$student,$disc,&$table)
    {
        if (isset($disc[ "NAssessments" ]))
        {
            $class[ "AbsencesType" ]=$disc[ "AbsencesType" ];
            $class[ "NAssessments" ]=$disc[ "NAssessments" ];
        }

        if ($this->GetPOST("Marks")==1 && !empty($marksline))
        {
            $this->ApplicationObj->ClassMarksObject->ImportStudentMarks($marksline,$class,$student,$disc,$table);
        }
        else
        {
            array_push($table,array($this->H(5,"No Student Marks - Omitted")));
        }

        if ($this->GetPOST("Absences")==1 &&!empty($absencesline))
        {
            $this->ApplicationObj->ClassAbsencesObject->ImportStudentAbsences($absencesline,$class,$student,$disc,$table);
        }
        else
        {
            array_push($table,array($this->H(5,"No Student Absences - Omitted")));
        }
    }


    //*
    //* function ImportDiscStudents, Parameter list: $class,$student,&$table
    //*
    //* Calls functions for importing Marks, Absences and Totals.
    //* 
    //*

    function ImportDiscStudents($dir,$class,$students,$disc,&$table)
    {
        if (empty($disc))
        {
            //Totals
            $disc=array
            (
               "ID" => 0,
               "Chave" => "Totals",
               "Name" => "Totals",
            );
        }

        $comps=preg_split('/\//',$dir);
        $marksfile=$dir."/".$disc[ "Chave" ]."/Notas.".$comps[6].".txt";
        $absencesfile=$dir."/".$disc[ "Chave" ]."/Faltas.".$comps[6].".txt";

        if ($class[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
        {
            $absencesfile=$dir."/"."/Quest/Faltas.".$comps[6].".txt";
        }

        $markslines=array();
        if (file_exists($marksfile)) { $markslines=file($marksfile); }

        $absenceslines=array();
        if (file_exists($absencesfile)) { $absenceslines=file($absencesfile); }

        foreach ($students as $student)
        {
            $marksline=preg_grep('/^'.$student[ "StudentHash" ][ "UniqueID" ].'/',$markslines);
            if (count($marksline)>=1) { $marksline=array_pop($marksline); }
            else                      { $marksline=""; }

            $absencesline=preg_grep('/^'.$student[ "StudentHash" ][ "UniqueID" ].'/',$absenceslines);
            if (count($absencesline)>=1) { $absencesline=array_pop($absencesline); }
            else                         { $absencesline=""; }

            $this->ImportStudentDisc($marksline,$absencesline,$class,$student,$disc,$table);
        }

        $markslines=array();
        $absenceslines=array();
    }



    //*
    //* function ImportDiscs, Parameter list: $class,$key,$diariosdir,$per,$discs,&$table
    //*
    //* Importa alunos. 
    //* 
    //*

    function ImportDiscs($class,$key,$diariosdir,$per,$students,$discs,&$table)
    {
        $dir=join("/",array($diariosdir,$per));

        if ($class[ "AssessmentType" ]==$this->ApplicationObj->Qualitative)
        {
            $this->ApplicationObj->ClassQuestionsObject->ImportStudentsQuestions($dir,$class,$students,$table);
        }
        else
        {
            foreach ($discs as $disc)
            {
                $this->ImportDiscStudents($dir,$class,$students,$disc,$table);
            }
        }

        if ($class[ "AbsencesType" ]==$this->ApplicationObj->OnlyTotals)
        {
            //Totals
            $this->ImportDiscStudents($dir,$class,$students,array(),$table);
        }
    }

    //*
    //* function Import, Parameter list: $class,$key,$diariosdir,$per,$discs,&$table
    //*
    //* Importa alunos. 
    //* 
    //*

    function Import($class,$key,$diariosdir,$per,$discs,&$table)
    {
        $dir=join("/",array($diariosdir,$per));

        $students=$this->ReadDirStudents($class,$dir,$discs);

        foreach (array_keys($students) as $id)
        {
            $students[ $id ]=$this->ImportStudentData($dir,$class,$students[ $id ],$table);
        }

        $this->ImportDiscs($class,$key,$diariosdir,$per,$students,$discs,$table);

        if (
              $this->GetPOST("Lessons")==1
              &&
              $class[ "AbsencesType" ]==$this->ApplicationObj->OnlyTotals
           )
        {
             //Total
            $comps=preg_split('/\//',$dir);
            $faltasfile=$dir."/Totals/Faltas.".$comps[6].".txt";

            $this->ApplicationObj->ClassDiscNLessonsObject->ImportDiscNLessons($faltasfile,$class,array(),$table);
        }

        if ($this->GetPOST("Observations")==1)
        {
            $this->ApplicationObj->ClassObservationsObject->ImportStudentsObservations($class,$students,$dir,$table);
        }
    }
}

?>