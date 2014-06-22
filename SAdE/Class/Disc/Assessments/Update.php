<?php


class ClassDiscAssessmentsUpdate extends ClassDiscAssessmentsTables
{
    //*
    //* function UpdateDaylyAssessments, Parameter list: $disc=array(),$class=array()
    //*
    //* Updates the (max values) of the individual marks - all trimesters.
    //*

    function UpdateDaylyAssessments($disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        if ($this->GetPOST("Save")==1)
        {
            $updated1=$this->UpdateDaylyNAssessments($disc,$class);
            $updated2=$this->UpdateDaylyAssessmentsData($disc,$class);

            if ($updated1 || $updated2)
            {
                $this->ApplicationObj->ClassDiscMarksObject->UpdateAllStudentsMarks();
            }

        }

    }

     //*
    //* function UpdateDaylyNAssessments, Parameter list: $disc=array(),$class=array()
    //*
    //* Updates number of assessments of all trimesters.
    //*

    function UpdateDaylyNAssessments($disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $updated=FALSE;
        foreach ($this->Assessments as $trimester => $assessments)
        {
            $row=array($this->B($trimester));

            $where=array
            (
               "Class" => $this->ApplicationObj->Class[ "ID" ],
               "Disc" => $this->ApplicationObj->Disc[ "ID" ],
               "Semester" => $trimester,
            );

            $nentries=$this->MySqlNEntries("",$where);

            $nnew=$this->GetPOST("NAssessments_".$trimester);

            if ($nnew>$nentries)
            {
                $assessements=$this->SelectHashesFromTable
                (
                   "",
                   $where,
                   array(),
                   FALSE,
                   "Number"
                );

                //Renumber
                $n=1;
                foreach ($assessements as $id => $assessment)
                {
                    unset($_POST[ $assessment[ "ID" ]."_Number" ]);
                    $this->Assessments[ $trimester ][ $id  ][ "Number" ]=$n;
                    $this->MySqlSetItemValue("","ID",$assessment[ "ID" ],"Number",$n++);
                }

                for (;$n<=$nnew;$n++)
                {
                    $text="Nota";

                    $no=$trimester;
                    if ($trimester>$disc[ "NAssessments" ])
                    {
                        $text="Recup.";
                        $no=$trimester-$disc[ "NAssessments" ];
                    }

                    $newass=$where;
                    $newass[ "Name" ]=$text." ".$no;
                    $newass[ "Number" ]=$no;
                    $newass[ "MaxVal" ]=1.0;                    
                    $newass[ "CTime" ]=time();                    
                    $newass[ "ATime" ]=time();                    
                    $newass[ "MTime" ]=time();      

                    $msg=$this->MySqlInsertItem("",$newass);
                }
                $updated=TRUE;
            }
        }

        $this->ReadDaylyAssessments();

        return $updated;
    }

    

    //*
    //* function UpdateDaylyAssessmentsData, Parameter list: $disc=array(),$class=array()
    //*
    //* Updates the (max values) of the individual marks - all trimesters.
    //*

    function UpdateDaylyAssessmentsData($disc=array(),$class=array())
    {
        if (empty($disc))  { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $updated=FALSE;
        foreach ($this->Assessments as $trimester => $assessments)
        {
            $row=array($this->B($trimester));
            foreach ($assessments as $id => $assessment)
            {
                if ($this->GetPOST("Delete_".$assessment[ "ID" ])==1)
                {
                    $this->MySqlDeleteItem("",$assessment[ "ID" ]);

                    unset($this->Assessments[ $trimester ][ $id  ]);
                    continue;
                }

                $updatedatas=array();
                foreach ($this->AssessmentData as $data)
                {
                    $cgikey=$assessment[ "ID" ]."_".$data;

                    if (isset($_POST[ $cgikey ]))
                    {
                        $cgivalue=preg_replace('/,/',".",$this->GetPOST($cgikey));

                        if ($assessment[ $data ]!=$cgivalue)
                        {
                            $this->Assessments[ $trimester ][ $id  ][ $data ]=$cgivalue;
                            array_push($updatedatas,$data);
                        }
                    }
                }

                if (count($updatedatas)>0)
                {
                    $this->MySqlSetItemValues
                    (
                       "",
                       $updatedatas,
                       $this->Assessments[ $trimester ][ $id  ]
                    );
                }

                if (preg_grep('/^MaxVal$/',$updatedatas))
                {
                    $updated=TRUE;
                }
            }
        }

        return $updated;
    }
}

?>