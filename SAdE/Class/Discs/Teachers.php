<?php

include_once("Class/Discs/Teachers/Select.php");

class ClassDiscsTeachers extends ClassDiscsTeachersSelect
{
    //*
    //* function UpdateClassDiscsTeachers, Parameter list: $editdata
    //*
    //* Displays List of class disciplines for editing only teachers..
    //*

    function UpdateClassDiscsTeachers($editdata)
    {
        if ($this->GetPOST("Save")!=1) { return; }

        foreach (array_keys($this->ApplicationObj->Discs) as $id)
        {
            $updatedata=array();
            foreach ($editdata as $data)
            {
                $cgikey=$this->ApplicationObj->Discs[ $id ][ "ID" ]."_".$data;
                $cgivalue=$this->GetPOST($cgikey);

                if ($this->ApplicationObj->Discs[ $id ][ $data ]!=$cgivalue)
                {
                    $this->ApplicationObj->Discs[ $id ][ $data ]=$cgivalue;
                    array_push($updatedata,$data);
                }
            }

            if (count($updatedata)>0)
            {
                $this->MySqlSetItemValues("",$updatedata,$this->ApplicationObj->Discs[ $id ]);
            }
        }

    }


    //*
    //* function ShowClassDiscsTeachers, Parameter list: 
    //*
    //* Displays List of class disciplines for editing only teachers..
    //*

    function ShowClassDiscsTeachers()
    {
        $edit=0;
        if (preg_match('/^(Admin|Clerk|Secretary)$/',$this->Profile)) { $edit=1; }

        if (!preg_match('/(Clerk|Secretary|Admin)/',$this->ApplicationObj->Profile)) { $edit=0; }
        //if (!preg_match('/^Edit/',$this->GetGET("Action"))) { $edit=0; }

        $showdata=array("Name","CHS","CHT",);
        $editdata=array("Teacher","Teacher1","Teacher2",);

        $this->InitProfile("ClassDiscs");
        $this->InitActions();
        $this->PostInit();

        if ($this->GetPOST("Save")==1)
        {
            $this->UpdateClassDiscsTeachers($editdata);
        }

        $this->TabMovesDownKey=1;

        $titles=array_merge($showdata,$editdata);
        $titles=$this->GetDataTitles($titles);
        array_unshift($titles,"No.");
        $titles=$this->B($titles);

        $table=array($titles);
        $n=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $row=array($this->B($n));
            foreach ($showdata as $data)
            {
                $cell=$this->MakeField(0,$disc,$data,TRUE);
                array_push($row,$cell);
            }

            $k=1; //tab index
            foreach ($editdata as $data)
            {
                $cell=$this->MakeField($edit,$disc,$data,TRUE,$k);
                array_push($row,$cell);
                $k++;
            }

            array_push($table,$row);
            $n++;
        }

        print 
            $this->H(3,"Professores da Turma").
            $this->ApplicationObj->ClassesObject->EditForm
            (
               "",
               $this->ApplicationObj->Class,
               $edit,
               FALSE,
               array("Name","Teacher","Teacher1","Teacher2",),
               FALSE
            ).
            $this->H(3,"Professores das Disciplinas").
            "";

        if ($edit==1)
        {
            print
                $this->StartForm().
                $this->Buttons().
                "";
        }

        print
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center'),
               array(),
               array(),
               TRUE,
               FALSE
            ).
            "";

        if ($edit==1)
        {
            print
                $this->MakeHidden("Save",1).
                $this->Buttons().
                $this->EndForm().
                "";
        }
    }
}

?>