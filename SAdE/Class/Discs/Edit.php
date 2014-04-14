<?php


class ClassDiscsEdit extends ClassDiscsUpdate
{

    //*
    //* function GenerateLessonsTable, Parameter list: 
    //*
    //* Updates Disc weight field.
    //*

    function GenerateLessonsTable()
    {
        return $this->ApplicationObj->ClassDiscLessonsObject->ClassDiscLessonsTable
        (
           0,
           $this->ItemHash
        );
    }

    //*
    //* function EditDisc, Parameter list: 
    //*
    //* Creates Disc central editor, to be handled from Classes.
    //*

    function EditDisc($edit,$tedit)
    {
        $this->ReadDisc();

        $this->InitActions();
        $this->ItemHash=$this->ApplicationObj->Disc;

        $actions=array("DiscAbsences","DiscMarks","DiscTotals");

        $this->HandleEdit
        (
           TRUE,
           "?ModuleName=Classes&Action=Disc",
           "Editar Disciplina da Turma"
        );
    }

    //*
    //* function UpdateTeacherLessons, Parameter list: $item,$data,$newvalue
    //*
    //* Update Disc lessons, when Teacher is altered.
    //*

    function UpdateTeacherLessons($item,$data,$newvalue)
    {
        if ($newvalue!=$item[ $data ])
        {
            $this->ApplicationObj->ClassDiscLessonsObject->MySqlSetItemsValue
            (
               "",
               "ClassDisc",
               $item[ "ID" ],
               "Teacher",
               $newvalue
            );

            foreach (array_keys($item[ "Lessons" ]) as $id)
            {
                $item[ "Lessons" ][ $id ][ "Teacher" ]=$newvalue;

                $_POST[  $item[ "Lessons" ][ $id ][ "ID" ]."_Teacher" ]=$newvalue;
            }

            $item[ $data ]=$newvalue;
        }

        return $item;
    }

    //*
    //* function DeleteDisc, Parameter list: 
    //*
    //* Creates Disc central delete, to be handled from Classes.
    //*

    function DeleteDisc()
    {
        $this->ReadDisc();

        $this->InitActions();
        $this->ItemHash=$this->ApplicationObj->Disc;

        $actions=array("DiscAbsences","DiscMarks","DiscTotals");

        $this->ItemName="Disciplina";
        $this->ItemHash=$this->ApplicationObj->Disc;
        $this->HandleDelete
        (
           TRUE,
           "DeleteDisc",
           "?ModuleName=Classes&Action=DeleteDisc",
           "Disc"
        );
    }


}

?>