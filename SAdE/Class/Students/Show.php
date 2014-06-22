<?php


class ClassStudentsShow extends ClassStudentsImport
{
    //*
    //* function ReadClassStudents, Parameter list: $classid,$datas=array(),$latex=TRUE
    //*
    //* Reads data for students of class.
    //*

    function ReadClassStudents($classid,$datas=array(),$latex=TRUE)
    {
        $this->ItemHashes=$this->SelectHashesFromTable
        (
           "",
           array("Class" => $classid),
           $datas
        );

        //Get current period id
        $periodid=$this->ApplicationObj->ClassesObject->MySqlItemValue("","ID",$classid,"Period");

        //Period Start date
        $startdate=$this->ApplicationObj->PeriodsObject->MySqlItemValue("","ID",$periodid,"StartDate");
        $startdatekey=$this->ApplicationObj->DatesObject->ID2SortKey($startdate);

        //Period Start date
        $enddate=$this->ApplicationObj->PeriodsObject->MySqlItemValue("","ID",$periodid,"EndDate");
        $enddatekey=$this->ApplicationObj->DatesObject->ID2SortKey($enddate);

        $id2studids=array();
        $this->ApplicationObj->StudentsObject->OnlyReadIDs=array();
        foreach ($this->ItemHashes as $id => $classstudent)
        {
            $this->ItemHashes[ $id ][ "StudentHash" ]=$this->ApplicationObj->StudentsObject->SelectUniqueHash
            (
               "",
               array("ID" => $classstudent[ "Student" ])
            );

            $this->ItemHashes[ $id ][ "StudentHash" ][ "ClassStudent" ]=$classstudent;

            array_push
            (
               $this->ApplicationObj->StudentsObject->OnlyReadIDs,
               $this->ItemHashes[ $id ][ "StudentHash" ][ "ID" ]
            );

            $id2studids[ $this->ItemHashes[ $id ][ "StudentHash" ][ "ID" ] ]=$classstudent[ "ID" ];
        }

        foreach ($this->ApplicationObj->StudentsObject->ItemHashes as $id => $student)
        {
            $this->ApplicationObj->StudentsObject->ItemHashes[ $id ][ "ClassStudent" ]=$id2studids[ $student[ "ID" ] ];
        }

        $searchvars=$this->ApplicationObj->StudentsObject->GetDefinedSearchVars();

        $this->ApplicationObj->StudentsObject->SearchItems($searchvars);
        $this->ApplicationObj->StudentsObject->SortItems("Name");

 
        $names=array();
        foreach ($this->ItemHashes as $item)
        {
            $names[ $item[ "StudentHash" ][ "Name" ].$item[ "StudentHash" ][ "ID" ] ]=$item;

        }

        $rnames=array_keys($names);
        sort($rnames);

        $items=array();
        foreach ($rnames as $name)
        {
            array_push($items,$names[ $name ]);
        }

        $this->ItemHashes=$items;

        $this->ApplicationObj->Students=$this->ItemHashes;

        if ($this->LatexMode || $latex)
        {
            $this->ApplicationObj->ClassStudentsObject->LatexStudents();
        }

        return $this->ItemHashes;
   }

    //*
    //* function ShowClassStudents, Parameter list: $classid=0
    //*
    //* Displays List of class students.
    //*

    function ShowClassStudents($classid=0)
    {
        if ($classid==0) { $classid=$this->ApplicationObj->Class[ "ID" ]; }

        $this->ReadClassStudents($classid);

        $this->ApplicationObj->StudentsObject->InitProfile("Students");
        $this->ApplicationObj->StudentsObject->InitActions();
        $this->ApplicationObj->StudentsObject->PostInit();
        $this->ApplicationObj->StudentsObject->Actions[ "Print" ][ "Title" ]="Ficha de Matricula";

        if (!empty($this->ApplicationObj->Students))
        {
            print $this->ApplicationObj->StudentsObject->SearchVarsTable
            (
               array("Email","Age","Paging","Edit","Output","School"),
               "","",array(),array(),
               "Classes"
            );

            $action=$this->GetGET("Action");
            $edit=0;
            if (preg_match('/^EditStudents$/',$action))
            {
                $this->ApplicationObj->StudentsObject->DefaultAction="EditStudents";
                $edit=1;
            }

            $this->ApplicationObj->StudentsObject->NoPaging=TRUE;
            $statussearch=$this->GetPOST("Students_Status_Search");
            if ($statussearch==0)
            {
                $this->ApplicationObj->StudentsObject->ItemData[ "Status" ][ "Search" ]=FALSE;
            }

            $this->ApplicationObj->StudentsObject->PostProcessed=array();

            $groupname=$this->GetPOST("Students_GroupName");
            if (empty($groupname)) { $groupname="Class"; }

            $this->ApplicationObj->StudentsObject->HandleList
            (
               "",
               FALSE, //No paging!
               $edit,
               $groupname
            );
        }
        else
        {
            print $this->H(4,"Nenhum(a) Aluno(a) na Turma");
        }
   }

    //*
    //* function PrintClassStudents, Parameter list: $classid=0
    //*
    //* Prints List of class students.
    //*

    function PrintClassStudents($classid=0)
    {
        if ($classid==0) { $classid=$this->ApplicationObj->Class[ "ID" ]; }

        $this->ReadClassStudents($classid);

        $this->ApplicationObj->StudentsObject->InitProfile("Students");
        $this->ApplicationObj->StudentsObject->InitActions();
        $this->ApplicationObj->StudentsObject->PostInit();
        $this->ApplicationObj->StudentsObject->Actions[ "Print" ][ "Title" ]="Imprimir Matricula";

        if (!empty($this->ApplicationObj->Students))
        {
            $edit=0;

            $this->ApplicationObj->StudentsObject->NoPaging=TRUE;
            $statussearch=$this->GetPOST("Students_Status_Search");
            if ($statussearch==0) { $this->ApplicationObj->StudentsObject->ItemData[ "Status" ][ "Search" ]=FALSE; }

            $this->ApplicationObj->StudentsObject->InitLatexData();
            $this->ApplicationObj->StudentsObject->PostProcessed=array();

            $this->ApplicationObj->StudentsObject->HandlePrints();
        }
        else
        {
            print $this->H(4,"Nenhum(a) Aluno(a) na Turma");
        }
   }

}

?>