<?php

class ClassDiscsTablesDisplay extends ClassDiscsTablesGenerate
{
    //*
    //* function DisplayLatexTable, Parameter list: 
    //*
    //* 
    //*

    function DisplayLatexTable()
    {
        $this->LatexMode=TRUE;
        $this->ApplicationObj->ClassMarksObject->LatexMode=TRUE;
        $this->ApplicationObj->ClassAbsencesObject->LatexMode=TRUE;
        $this->ApplicationObj->ClassStatusObject->LatexMode=TRUE;
        $this->ApplicationObj->ClassObservationsObject->LatexMode=TRUE;
        $this->ApplicationObj->ClassStudentsObject->LatexMode=TRUE;
        $this->ApplicationObj->StudentsObject->LatexMode=TRUE;
        $this->ApplicationObj->ClassesObject->LatexMode=TRUE;
        $this->ApplicationObj->Sigma="\$\\Sigma\$";
        $this->ApplicationObj->Mu="\$\\mu\$";
        $this->ApplicationObj->Percent="\\%";
        $edit=0;
        $tedit=0;

        $this->DiscsActions=array();
        $this->StudentsActions=array();

        $students=array();
        if ($this->GetGET("Student")=="All")
        {
            $this->ApplicationObj->StudentsObject->ReadClassStudents();
        }
        else
        {
            $students=array($this->ApplicationObj->Student);
        }

        return
            "\\begin{center}\n".
            $this->StudentLatexTablesTable($students).
            "\\end{center}\n";
    }

    //*
    //* function DisplayTable, Parameter list: $group,$type,$edit=0,$tedit=0,$form=TRUE
    //*
    //* Creates row student, all discs.
    //*

    function DisplayTable($group,$type,$edit=0,$tedit=0,$form=TRUE)
    {
        $this->PerDisc=TRUE;
        if ($group=="Student") { $this->PerDisc=FALSE; }

        $this->TableType=$type;

        $this->ReadTable();

        $this->InitDisplayTable($edit,$tedit);

        $latex=$this->GetGETOrPOST("Latex");
        if ($latex==1)
        {
            $latex="";
            if ($this->ApplicationObj->Disc[ "AssessmentType" ]==2)
            {
                $latex=$this->ApplicationObj->ClassesObject->LatexHead();
            }
            else
            {
                $latex=$this->ApplicationObj->ClassesObject->LatexHeadLand();
            }

            $latex.=
                $this->DisplayLatexTable().
                $this->ApplicationObj->ClassesObject->LatexTail().
                "";

            //print preg_replace('/\n/',"<BR>\n",$latex)."";exit();

            $texfile="Students.".$this->MTime2FName().".tex";
            $this->RunLatexPrint($texfile,$latex);
            return;
            
        }

        $update=$this->GetPOST("Update");
        if ($edit==1 && $update==1)
        {
            $this->UpdateTable();
        }

        $table=$this->GenerateTable($edit,$tedit,$form);
        print
            $this->H(1,$this->GetDisplayTitle()).
            $this->HtmlTable("",$table).
            "";
    }


}

?>