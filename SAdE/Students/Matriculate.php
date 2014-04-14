<?php

include_once("Students/Matriculate/Fields.php");
include_once("Students/Matriculate/Row.php");
include_once("Students/Matriculate/Table.php");
include_once("Students/Matriculate/Update.php");


class StudentsMatriculate extends StudentsMatriculateUpdate
{
    //*
    //* function HandleMatricula, Parameter list: 
    //*
    //* Generates student history.
    //* 
    //*

    function HandleMatricula()
    {
        $edit=1;

        $this->ItemHash[ "StudentHash" ]=$this->ItemHash;

        if ($edit==1 && $this->GetPOST("Save")==1)
        {
            $this->UpdateMatriculaTable();
        }


        print
            $this->H(1,"Matricular Aluno(a)").
            $this->HtmlTable
            (
               "",
               $this->ItemTable
               (
                  0,
                  array(),
                  FALSE,
                  array("Name","Matricula","MatriculaDate","Status","StatusDate1","StatusDate2")
               )
            ).
            $this->H(2,"Turmas Matriculadas do(a) Aluno(a)").
            $this->StartForm().
            $this->HtmlTable
            (
               "",
               $this->MatriculaTable()
            ).
            $this->MakeHidden("Save",1).
            $this->Buttons().
            $this->EndForm().
            "";
    }

}

?>