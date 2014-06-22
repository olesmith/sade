<?php



class ClassQuestionsImport extends ClassQuestionsUpdate
{
    var $Questionaries=NULL;

    //*
    //* function ImportStudentQuestionaries, Parameter list: $qdir,$class,$student,&$table
    //*
    //* Returns sql where clause specifying student question.
    //*

    function ImportStudentQuestionaries($qdir,$class,$student,&$table)
    {
        $file=$qdir."/".$student[ "UniqueID" ].".txt";
        if (!file_exists($file))
        {
            return;
        }

        $lines=$this->MyReadFile($file);

        $nupdated=0;
        $nadded=0;
        foreach ($lines as $line)
        {
            $comps=preg_split('/\s/',$line);
            //uniqueid repeated in file, skip
            array_shift($comps);

            foreach ($comps as $rcomp)
            {
                if (!empty($rcomp))
                {
                    $rcomps=preg_split('/:/',$rcomp);
                    $questinaryno=$rcomps[0];
                    $questionno=$rcomps[1];
                    $bimester=$rcomps[2]+1;
                    $value=$rcomps[3];

                    if (empty($this->Questionaries[ $questinaryno ][ "Questions" ][ $questionno ]))
                    {
                        continue;
                    }
                    $questionhash=$this->Questionaries[ $questinaryno ][ "Questions" ][ $questionno ];

                    $where=array
                    (
                       "Class" => $class[ "ID" ],
                       "Student" => $student[ "StudentHash" ][ "ID" ],
                       "Question" => $questionhash[ "ID" ],
                       "Assessment" => $bimester,
                    );

                    $item=$where;
                    $item[ "Value" ]=$value;

                    $msg=$this->AddOrUpdate("",$where,$item);
                    if (preg_match('/^Update/',$msg)) { $nupdated++; }
                    else                              { $nadded++; }
                    
                }
            }

        }


        array_push
        (
           $table,
           array
           (
              "",
              "",
              "",
              "Import Quest ".$student[ "StudentHash" ][ "Name" ]." (".$student[ "StudentHash" ][ "UniqueID" ].")".
              "U: ".$nupdated.", A: ".$nadded
           )
        );

        for ($n=0;$n<4;$n++)
        {
            $file=$qdir."/".$student[ "UniqueID" ].".Observations.".$n.".txt";
            $lines=$this->MyReadFile($file);
        }
    }

    //*
    //* function ImportStudentsQuestions, Parameter list: $class,$students,&$table
    //*
    //* Returns sql where clause specifying student question.
    //*

    function ImportStudentsQuestions($dir,$class,$students,&$table)
    {
        $this->Questionaries=$this->ApplicationObj->GradeQuestionariesObject->ReadGradeQuestionaries($class);

        $qdir=$dir."/Quest";

        foreach ($students as $student)
        {
            $this->ImportStudentQuestionaries($qdir,$class,$student,$table);
        }
    }
}

?>