<?php


class ClassDiscAbsencesLatex extends ClassDiscAbsencesLatexStudents
{
    var $StudentsAcc=array();
  

    //*
    //* function AbsencesLatex, Parameter list: $class,$disc
    //*
    //* Generates Absences Latex page(s).
    //*

    function AbsencesLatex($class,$disc)
    {
        $this->StudentData=$this->StudentLatexData;

        $this->ApplicationObj->DaylyMonths=$this->ApplicationObj->PeriodsObject->GetMonths();
        $contents=$this->ApplicationObj->ClassDiscContentsObject->ReadDiscContents();
        $chs=$this->ApplicationObj->ClassDiscContentsObject->ReadDaylyContents();

        $mentries=array();
        $mcontents=array();
        foreach ($contents as $content)
        {
            $month=$content[ "Month" ];
            if (empty($mcontents[ $month ])) { $mcontents[ $month ]=array(); }
            if (empty($mentries[ $month ]))  { $mentries[ $month ]=0; }

            $mentries[ $month ]++;
            array_push($mcontents[ $month ],$content);
        }


        $pages=array(0 => array());
        $page=0;
        $ncols=0;
        foreach ($mcontents as $month => $contents)
        {
            if ( ($ncols+$mentries[ $month ])>$this->NDatesPerPage)
            {
                $page++;
                $ncols=0;
            }

            $pages[ $page ][ $month ]=$contents;

            $ncols+=$mentries[ $month ];
        }

        $latex="";
        $rch=0;

        $npages=count(array_keys($pages));
        $n=1;
        $pageno=0;
        foreach ($pages as $page)
        {
            $lastpage=FALSE;
            if ($n==$npages) { $lastpage=TRUE; }

            $latex.=$this->AbsencesLatexStudents($class,$disc,$chs,$page,$rch,$pageno,$lastpage);
            $n++;
        }

        return $latex;
    }

    //*
    //* function PrintAbsencesLatex, Parameter list: $class=array(),$disc=array(),$month=""
    //*
    //* Prints and generates Absences Latex page(s).
    //*

    function PrintAbsencesLatex($class=array(),$disc=array(),$month="")
    {
        $this->InitLatexData();
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        if (empty($disc))  { $disc =$this->ApplicationObj->Disc; }

        $this->ApplicationObj->ClassStudentsObject->ReadClassStudents($class[ "ID" ],TRUE);

        $latex=
            $this->LatexHeadLand().
            $this->AbsencesLatex($class,$disc).
            $this->LatexTail().
            "";

        //$this->ShowLatexCode($latex);exit();

        $texfilename=
            "Absences.".
            $this->CurrentYear().".".
            sprintf("%02d",$this->CurrentMonth()).".".
            sprintf("%02d",$this->CurrentDate()).".".
            time().".".
            ".tex";

        $this->RunLatexPrint($texfilename,$latex);
        exit();
    }

}

?>