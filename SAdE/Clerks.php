<?php


class Clerks extends Common
{

    //*
    //*
    //* Constructor.
    //*

    function Clerks($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Clerk","School");
        $this->NItemsPerPage=25;
        $this->Reverse=TRUE;
    }

    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        //$this->ApplicationObj->ReadSchool();
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if (!preg_match('/^Clerks/',$module))
        {
            return $item;
        }

        return $item;
    }

    //*
    //* function MayAdd, Parameter list: $newitem
    //*
    //* Returns TRUE if allowed to add $newitem.
    //* Checks unicity for School and Clerk.
    //*

    function MayAdd($newitem)
    {
        $res=FALSE;
        if (!empty($newitem[ "School" ]) && !empty($newitem[ "Clerk" ]))
        {
            $items=$this->SelectHashesFromTable
            (
               "",
               array
               (
                  "School" => $newitem[ "School" ],
                  "Clerk"  => $newitem[ "Clerk" ],
               ),
               array("ID")
            );

            if (count($items)==0)
            {
                $res=TRUE;
            }
        }

        return $res;
    }
}

?>