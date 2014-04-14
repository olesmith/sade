<?php

class ClassDiscsTablesStudents extends ClassDiscsTablesDiscs
{
    var $NoObservations=FALSE;

    //*
    //* function StudentTable, Parameter list: $edit=0,$tedit=0
    //*
    //* Creates one student table, all discs.
    //*

    function StudentTable($edit=0,$tedit=0)
    {        
        $table=$this->MakeTitleRows($edit,$tedit,$this->ApplicationObj->Discs[0]);

        $ncols=0;
        $no=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $row=$this->MakeStudentDiscRow
            (
               $edit,$tedit,
               $no,
               $this->ApplicationObj->Class,
               $this->ApplicationObj->Student,
               $disc
            );

            $ncols=$this->Max($ncols,count($row));
            array_push($table,$row);
            $no++;
        }
 

        $startform="";
        $endform="";
        if ($edit==1 || $tedit==1)
        {
            $startform=
                $this->StartForm().
                $this->Buttons();

            $endform=
                $this->MakeHidden("StudentID",$this->GetGET("Student")).
                $this->MakeHidden("Update",1).
                $this->Buttons().
                $this->EndForm();
        }


        $ctable=$this->ApplicationObj->ClassObservationsObject->ObservationsTable
        (
           $this->ApplicationObj->Class,
           $this->ApplicationObj->Student,
           $edit,
           $tedit
        );

        if (!$this->LatexMode)
        {
            array_push($table,"");
            foreach ($ctable as $row)
            {
                $cell1="";
                if (!empty($row[0])) { $cell1=$row[0]; }
                $cell2="";
                if (!empty($row[1])) { $cell2=$row[1]; }

                array_push
                (
                   $table,
                   array
                   (              
                      $this->MultiCell($cell1,3),
                      $this->MultiCell($cell2,$ncols-2)
                   )
                );
            }
        }

        $observations=TRUE;
        if ($this->GetGET("NoObs")!=1) { $observations=FALSE; }
        if ($this->NoObservations) { $observations=FALSE; }

 
        $html="";
        if ($this->LatexMode)
        {
            $html=
                $this->LatexTable("",$table);

            if ($observations)
            {
                $html.=
                    "\\vspace{0.25cm}\n\n".
                    $this->LatexTable("",$ctable,"|l|p{10cm}|").
                    "";
            }

            $html.=$this->ApplicationObj->ClassesObject->LatexResponsibleSignatureLine(3.0,1.0,1.5,7.0);
        }
        else
        {
            $html=$this->HtmlTable("",$table);
        }

        return
            $startform.
            $html.
            $endform;
    }

    //*
    //* function StudentLatexTablesTable, Parameter list: $students=array()
    //*
    //* Branches: Student or Disc table.
    //*

    function StudentLatexTablesTable($students=array())
    {
        $observations=TRUE;
        if ($this->GetGET("NoObs")!=1) { $observations=FALSE; }
        if ($this->NoObservations) { $observations=FALSE; }

        if (empty($students)) { $students=$this->ApplicationObj->Students; }

        $rlatex="";
        foreach (array_keys($students) as $sid)
        {
            $this->ApplicationObj->Student=$students[ $sid ];
            $tables=$this->GenerateTable(0,0);

            $latex=
                "\n\\hspace{0.5cm}\\vspace{-1cm}\n\n".
                $this->H(1,$this->GetDisplayTitle()).
                "\n\\vspace{0.25cm}\n\n".
                "";

            foreach ($tables as $table)
            {
                if (is_array($table)) { $table=join("\n\n",$table); }
                $latex.=$table;
            }

            if ($observations)
            {
                $latex.="\\clearpage\n\n";
            }

            $rlatex.=$this->LatexOnePage($latex,"27.5cm",0.75);
        }

        //$this->ShowLatexCode($rlatex);exit();
        return $rlatex;
    }
}

?>