<?php

class ClassDiscContentsResultsUpdate extends ClassDiscContentsResultsRows
{
    //*
    //*
    //* function UpdateDatesRegisterRow, Parameter list: $period,$disc,$date,&$updatemsgs
    //*
    //* Generates serch form for limitng Period dates.
    //*

    function UpdateDatesRegisterRow($period,$disc,$date,&$updatemsgs)
    {
        $this->ApplicationObj->PeriodsObject->Date2Trimester($period,$date);

        $val=$this->GetPOST("I_".$date[ "ID" ]);
        if ($this->GetPOST("I_".$date[ "ID" ])==1)
        {
            $n=intval($this->GetPOST("N_".$date[ "ID" ]));
            $w=intval($this->GetPOST("W_".$date[ "ID" ]));

            if ($n>0 && $n<=5 && $w>0  && $w<=5)
            {
                for ($m=1;$m<=$n;$m++)
                {
                    $newcontent=array
                    (
                       "Class" => $this->ApplicationObj->Class[ "ID" ],
                       "Disc" => $disc[ "ID" ],
                       "Date" => $date[ "ID" ],
                       "DateKey" => $date[ "SortKey" ],
                       "Semester" => $date[ "Semester" ],
                       "Month" => $date[ "Month" ],
                       "Weight" => $w,
                       "Content" => "",
                       "CTime" => time(),
                       "MTime" => time(),
                       "ATime" => time(),
                    );

                    $this->MySqlInsertItem("",$newcontent);
                    array_push($updatemsgs,"Criado: ".$date[ "Name" ].", Aula ".$n.", peso ".$w);

                }
            }
        }
    }

    //*
    //*
    //* function UpdateDatesRegisterTable, Parameter list: $period,$disc,$dates
    //*
    //* Updates table listing search results.
    //*

    function UpdateDatesRegisterTable($period,$disc,$dates)
    {
        if ($this->GetPOST("Update")!=1) { return; }

        $updatemsgs=array();
        foreach ($dates as $date)
        {
            $this->UpdateDatesRegisterRow($period,$disc,$date,$updatemsgs);
        }

        return $updatemsgs;
    }


    //*
    //*
    //* function AddProgrammedLessons, Parameter list: $period,$disc,$date
    //*
    //* Makes sure all lessons dates are included.
    //*

    function AddProgrammedLessons($period,$disc,$dates)
    {
        $updatemsgs=array();
        foreach ($dates as $date)
        {
            $nchprev=$this->DiscDate2NProgrammedLessons($disc,$date);
            $rows=$this->DateProgrammedLessonsRows($period,$disc,$date,$nchprev);

            $nchpreg=$this->DiscDate2NWeightRegistered($disc,$date);

            $ch=$nchprev-$nchpreg;
            if ($ch>0)
            {
                $this->ApplicationObj->PeriodsObject->Date2Trimester($period,$date);
                $newcontent=array
                (
                   "Class" => $this->ApplicationObj->Class[ "ID" ],
                   "Disc" => $disc[ "ID" ],
                   "Date" => $date[ "ID" ],
                   "DateKey" => $date[ "SortKey" ],
                   "Semester" => $date[ "Semester" ],
                   "Month" => $date[ "Month" ],
                   "Weight" => $ch,
                   "Content" => "",
                   "CTime" => time(),
                   "MTime" => time(),
                   "ATime" => time(),
                );

                $this->MySqlInsertItem("",$newcontent);
                array_push($updatemsgs,"Criado: ".$date[ "Name" ].", Aula 1, peso ".$ch);
            }
        }

        return $updatemsgs;
    }

}

?>