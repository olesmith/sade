<?php


class ClassDiscsImport extends Common

{    //*
    //* function Import, Parameter list: $item,$key,$diariosdir,$materiasdir,$rperiod,&$table
    //*
    //* Imports class discs.
    //* 
    //*

    function Import($class,$key,$diariosdir,$materiasdir,$rperiod,&$table)
    {
        $this->ApplicationObj->Class=$class;

        $discs=$this->ApplicationObj->GradeObject->ReadGradePeriodDiscs($class);

        $rdiscs=array();
        foreach ($discs as $disc)
        {
            $rdiscs[ $disc[ "Chave" ] ]=$disc;
        }

        $pdir=$diariosdir."/".$rperiod;
        if (!file_exists($pdir)) { var_dump("Invalid PDir: ".$pdir); return; }

        $matdirs=$this->DirSubdirs($pdir);
        sort($matdirs);

        $this->ApplicationObj->ClassesObject->UpdateSubTablesStructure($class,"Classes");


        $sdiscs=array();
        foreach ($matdirs as $matdir)
        {
            $discname=basename($matdir);
            if ($discname=="Totals") { continue; }

            if (isset($rdiscs[ $discname ]))
            {
                $disc=$rdiscs[ $discname ];

                $disc[ "Teacher" ]=0;
                $matfile=$materiasdir."/".$rperiod."/".$discname."/Turma.".$key.".txt";
                if (file_exists($matfile))
                {
                    $mat=$this->ReadHash($matfile);
                    $teacher=$this->ApplicationObj->PeopleObject->SelectUniqueHash
                    (
                       "",
                       array
                       (
                          "UniqueID" => $mat[ "ProfessorID" ],
                          "School" => $class[ "School" ],
                       ),
                       TRUE,
                       array("ID")
                    );

                    if (!empty($teacher))
                    {
                        $disc[ "Teacher" ]=$teacher[ "ID" ];
                    }
                }

                $where=array
                (
                   "School" => $class[ "School" ],
                   "Class" => $class[ "ID" ],
                   "Period" => $class[ "Period" ],
                   "Grade" => $class[ "Grade" ],
                   "GradePeriod" => $class[ "GradePeriod" ],
                   "GradeDisc" => $disc[ "ID" ],
                );

                $cdisc=$where;
                $cdisc[ "Teacher" ]=$disc[ "Teacher" ];
                $cdisc[ "Name" ]=$disc[ "Name" ];
                $cdisc[ "FileKey" ]=$matdir;

                foreach (array("NAssessments","AssessmentType","AbsencesType","NRecoveries") as $key)
                {
                    $cdisc[ $key ]=$class[ $key ];
                }

                $msg=$this->AddOrUpdate("",$where,$cdisc);
                array_push
                (
                   $table,
                   array
                   (
                      "",
                      "",
                      "Import Disc ".$disc[ "ID" ],
                      $msg,
                      $disc[ "Name" ]
                   )
                );

                foreach (array("Chave") as $key)
                {
                    $cdisc[ $key ]=$disc[ $key ];
                }

                array_push($sdiscs,$cdisc);

                $this->ApplicationObj->ClassDiscWeightsObject->ImportDiscWeights($class,$cdisc,$table);
                $this->ApplicationObj->ClassDiscLessonsObject->ImportDiscLessons($class,$cdisc,$table);

                if ($this->GetPOST("Lessons")==1)
                {
                    $comps=preg_split('/\//',$diariosdir);
                    $faltasfile=$diariosdir."/".$rperiod."/".$disc[ "Chave" ]."/Faltas.".$comps[6].".txt";

                    $this->ApplicationObj->ClassDiscNLessonsObject->ImportDiscNLessons($faltasfile,$class,$cdisc,$table);
                }
                else
                {
                    array_push($table,array($this->H(5,"Lessons Given Omitted")));
                }
            }
            else
            {
                $this->ApplicationObj->AddImportLogEntry
                (
                   $pdir,
                   "Invalid disc key: ".$discname
                );
            }

        }

        //foreach ($sdiscs as $disc) { var_dump($disc[ "ID" ]); }
        return $sdiscs;
    }

 

}

?>