<?php


class ClassDiscsSelectForm extends ClassDiscsMenu
{
    //*
    //* function DiscsSelectForm, Parameter list:
    //*
    //* Creates discs select form.
    //*

    function DiscsSelectForm($size=FALSE)
    {
        if (empty($this->ApplicationObj->Discs)) { return ""; }
        if (empty($this->ApplicationObj->Class)) { return ""; }

        $action=$this->DetectAction();

        if (
              $this->ApplicationObj->Class[ "AbsencesType" ]==$this->ApplicationObj->OnlyTotals
              &&
              $action=="DiscAbsences"
            )
        {
            return "";
        }

        $ids=array();
        $names=array();
        $titles=array();
        $current="";

        $select="";
        if (
              $action!="DiscAbsences"
              ||
              $this->ApplicationObj->Class[ "AbsencesType" ]==$this->ApplicationObj->AbsencesYes
           )
        {
            foreach ($this->ApplicationObj->Discs as $disc)
            {
                $args[ "Disc" ]=$disc[ "ID" ];
                array_push($ids,$disc[ "ID" ]);
                array_push($names,$disc[ "Name" ]);
                array_push($titles,$disc[ "Name" ]);
                if ($this->ApplicationObj->Disc[ "ID" ]==$disc[ "ID" ])
                {
                    $current=$disc[ "Name" ];
                }
            }

            $selectname="DiscID";
            $selectoptions=array();
            if ($size)
            {
                $selectname="DiscIDs[]";
                $selectoptions=array
                (
                   "SIZE" => count($this->ApplicationObj->Discs),
                   "MULTIPLE" => 'multiple',
                );
            }

            $discs=array();
            if (isset($_POST[ "DiscIDs" ])) { $discs=$_POST[ "DiscIDs" ]; }
            elseif (isset($_POST[ "DiscID" ]))  { $discs=array($_POST[ "DiscID" ]); }
 
            $selecteds=array();
            foreach ($discs as $disc) { $selecteds[ $disc ]=1; }

            $select=
                $this->B("Selecione Disciplina: ").
                $this->MakeSelectfield
                (
                   $selectname,
                   $ids,
                   $names,
                   $selecteds,
                   array(),
                   $titles,
                   "",
                   0,FALSE,FALSE,NULL,
                   $selectoptions
                );
        }
        else
        {
            $select=$this->B("Somente Faltas Totais! ");
        }


        $args=$this->Query2Hash();
        $args=$this->Hidden2Hash($args);

        $args[ "ModuleName" ]="Classes";

        $menu=$this->MakeActionMenu
        (
            $this->DiscsActions,
            "ptablemenu",
            $this->GetGET("Disc")
        );

        return
            $this->Center
            (
                $this->ApplicationObj->ClassDiscsObject->ItemsSelectForm
                (
                   "Disciplina",
                   $ids,
                   $names,
                   $titles,
                   $this->ApplicationObj->Disc[ "ID" ],
                   $args,
                   "Disc",
                   "DiscID",
                   $this->BR().
                   $this->BR().
                   $this->B($this->ApplicationObj->Disc[ "Name" ].": ").
                   $menu
                )
           ).
           $this->BR().
            "";
    }



}

?>