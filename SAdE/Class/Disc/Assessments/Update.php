<?php


class ClassDiscAssessmentsUpdate extends ClassDiscAssessmentsTables
{
    //*
    //* function UpdateDaylyNAssessments, Parameter list: $disc=array(),$class=array()
    //*
    //* Generates a table, resuming number of assessments per semester.
    //*

    function UpdateDaylyNAssessments($disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        foreach ($this->Assessments as $semester => $assessments)
        {
            $row=array($this->B($semester));

            $where=array
            (
               "Class" => $this->ApplicationObj->Class[ "ID" ],
               "Disc" => $this->ApplicationObj->Disc[ "ID" ],
               "Semester" => $semester,
            );

            $nentries=$this->MySqlNEntries("",$where);

            $nnew=$this->GetPOST("NAssessments_".$semester);

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

                $n=1;
                foreach ($assessements as $id => $assessment)
                {
                    unset($_POST[ $assessment[ "ID" ]."_Number" ]);
                    $this->Assessments[ $semester ][ $id  ][ "Number" ]=$n;
                    $this->MySqlSetItemValue("","ID",$assessment[ "ID" ],"Number",$n++);

                }

                for (;$n<=$nnew;$n++)
                {
                    $text="Nota";

                    $no=$semester;
                    if ($semester>$disc[ "NAssessments" ])
                    {
                        $text="Recup.";
                        $no=$semester-$disc[ "NAssessments" ];
                    }

                    $newass=$where;
                    $newass[ "Name" ]=$text." ".$n;
                    $newass[ "Number" ]=$n;
                    $newass[ "MaxVal" ]=1.0;                    
                    $newass[ "CTime" ]=time();                    
                    $newass[ "ATime" ]=time();                    
                    $newass[ "MTime" ]=time();      

                    $msg=$this->MySqlInsertItem("",$newass);
                }
            }
        }

        $this->ReadDaylyAssessments();
    }

    

    //*
    //* function UpdateDaylyAssessments, Parameter list: $disc=array(),$class=array()
    //*
    //* Generates a table, resuming number of assessments per semester.
    //*

    function UpdateDaylyAssessments($disc=array(),$class=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        foreach ($this->Assessments as $semester => $assessments)
        {
            $row=array($this->B($semester));
            foreach ($assessments as $id => $assessment)
            {
                if ($this->GetPOST("Delete_".$assessment[ "ID" ])==1)
                {
                    $this->MySqlDeleteItem("",$assessment[ "ID" ]);

                    unset($this->Assessments[ $semester ][ $id  ]);
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
                            $this->Assessments[ $semester ][ $id  ][ $data ]=$cgivalue;
                            array_push($updatedatas,$data);
                        }
                    }
                }

                if (count($updatedatas)>0)
                {
                    $this->MySqlSetItemValues("",$updatedatas,$this->Assessments[ $semester ][ $id  ]);
                }
            }
        }
    }
}

?>