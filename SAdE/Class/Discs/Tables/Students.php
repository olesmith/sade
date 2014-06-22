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

        $width=10;
        $height=1.25;


        $ctable=$this->ApplicationObj->ClassObservationsObject->ObservationsTable
        (
           $this->ApplicationObj->Class,
           $this->ApplicationObj->Student,
           $edit,
           $tedit,
           FALSE,
           $width,
           $height
        );

        if (!$this->LatexMode())
        {
            array_push($table,"");
            foreach ($ctable as $row)
            {
                $cell1="";
                if (!empty($row[0])) { $cell1=$row[0]; }
                $cell2="";
                if (!empty($row[1])) { $cell2=$row[1]; }
                $cell3="";
                if (!empty($row[1])) { $cell3=$row[2]; }

                $cells1=3;
                $cells2=($ncols-$cells1+1)/2;
                $cells3=$ncols-$cells1-$cells2;
                array_push
                (
                   $table,
                   array
                   (              
                      $this->MultiCell($cell1,$cells1),
                      $this->MultiCell($cell2,$cells2),
                      $this->MultiCell($cell3,$cells3),
                   )
                );
            }
        }

        $observations=TRUE;
        if ($this->GetGET("NoObs")==1) { $observations=FALSE; }
        if ($this->NoObservations) { $observations=FALSE; }

        $html="";
        if ($this->LatexMode())
        {
            $scale=1.0;
            //if ($observations) { $scale=0.75; }

            $html=
                "\\scalebox{".$scale."}{".
                $this->LatexTable("",$table).
                "}";

            if ($observations)
            {
                $html.=
                    //"\\vspace{0.1cm}\n\n".
                    $this->LatexTable("",$ctable,"|l|p{10cm}|p{10cm}|").
                    "";

                $html.=$this->ApplicationObj->ClassesObject->LatexResponsibleSignatureLine(3.0,1.0,1.5,7.0);
            }
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
                //"\n\\hspace{0.5cm}\\vspace{-1cm}\n\n".
                $this->H(1,$this->GetDisplayTitle()).
                //"\n\\vspace{0.1cm}\n\n".
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

            $rlatex.=$latex;
        }

        return $rlatex;
    }
}

?>