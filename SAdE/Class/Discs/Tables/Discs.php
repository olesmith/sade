<?php

class ClassDiscsTablesDiscs extends ClassDiscsTablesInit
{
    //*
    //* function DiscTable, Parameter list: $edit=0,$tedit=0
    //*
    //* Creates table, one disc all students.
    //*

    function DiscTable($edit=0,$tedit=0)
    {
        $this->ReadClassDiscs();
        $this->ReadDiscData($this->ApplicationObj->Disc);

        $table=$this->MakeTitleRows($edit,$tedit,$this->ApplicationObj->Disc);

        $no=1;
        foreach ($this->ApplicationObj->ClassStudentsObject->ItemHashes as $student)
        {
            //Omit not matriculateds
            if ($student[ "StudentHash" ][ "Status" ]==8) { continue; }
            array_push
            (
               $table,
               $this->MakeStudentDiscRow
               (
                  $edit,$tedit,$no,
                  $this->ApplicationObj->Class,
                  $student,
                  $this->ApplicationObj->Disc
               )
            );

            if (!$this->LatexMode() && ($no%10)==0)
            {
                $table=array_merge($table,$this->MakeTitleRows(0,0,$this->ApplicationObj->Disc));
            }

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
                $this->MakeHidden("DiscID",$this->ApplicationObj->Disc[ "ID" ]).
                $this->MakeHidden("Update",1).
                $this->Buttons().
                $this->EndForm();
        }

        $html="";
        if ($this->LatexMode())
        {
            $html=$this->LatexTable("",$table);
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
}

?>