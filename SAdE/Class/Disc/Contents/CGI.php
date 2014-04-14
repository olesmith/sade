<?php


class ClassDiscContentsCGI extends ClassDiscContentsLatex
{
    //*
    //* function CGI2Month, Parameter list: 
    //*
    //* Returns month as specified by CGI. Takes Semester into account.
    //*

    function CGI2Month()
    {
        $month=$this->GetGET("Month");
        $semester=$this->GetGET("Semester");

        if (empty($month) && empty($semester))
        {
            $month=$this->CurrentMonth();
        }

        return $month;
    }

    //*
    //* function CGI2ContentsWhere, Parameter list: 
    //*
    //* Generates SQL clause (as hash) for reading Contents
    //* specified by CGI.
    //*

    function CGI2ContentsWhere()
    {
        //Always print all
        if ($this->LatexMode) { return array(); }

        $where=array();
        $month=$this->CGI2Month();
        $semester=$this->GetGET("Semester");

        if (
              preg_match('/^\d\d?$/',$month)
              &&
              $month>=1
              &&
              $month<=12
           )
        {
            $semester="";
            $where[ "DateKey" ]=
                "LIKE '".$this->ApplicationObj->Period[ "Year" ].
                sprintf("%02d",$month)."%'";
        }
        elseif (
                  preg_match('/^\d$/',$semester)
                  &&
                  $semester>=1
                  &&
                  $semester<=$this->ApplicationObj->Period[ "NPeriods" ]
               )
        {
            $month="";
            $where[ "Semester" ]=$semester;
        }

        return $where;
    }

    //*
    //* function CGI2Contents, Parameter list: $datas=array(),$class=array(),$disc=array(),$latex=FALSE
    //*
    //* Reads Contents as specified by CGI.
    //*

    function CGI2Contents($datas=array(),$class=array(),$disc=array(),$latex=FALSE)
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        if (empty($disc))  { $disc =$this->ApplicationObj->Disc; }

        $where=$this->CGI2ContentsWhere();
        if ($this->LatexMode || !empty($where))
        {
            $where[ "Class" ]=$class[ "ID" ];
            $where[ "Disc" ]=$disc[ "ID" ];

            $contents=$this->SelectHashesFromTable("",$where,$datas,FALSE,"DateKey,ID");

            if ($this->LatexMode || $latex)
            {
                foreach (array_keys($contents) as $id)
                {
                    $contents[ $id ]=$this->TrimLatexItem($contents[ $id ]);
                }

                return $contents;
            }
        }
        else
        {
            print $this->H(1,"Escolhe Mes ou ".$this->ApplicationObj->PeriodsObject->PeriodSubPeriodsTitle()." nos Menus acima");
        }

        return $contents;
    }
}

?>