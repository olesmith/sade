<?php


class SearchOptions extends Paging
{


    //*
    //* function AddSearchOptionFields, Parameter list: $omitvars,&$table
    //*
    //* Adds the IncludeAll, Output, Paging and Data Group
    //* fields.
    //*

    function AddSearchOptionFields($omitvars,&$table)
    {
        $row1=array();
        $row2=array();
        $row3=array();
        if (!preg_grep('/^ShowAll$/',$omitvars))
        {
            array_push
            (
               $row1,
               $this->B
               (
                  $this->GetMessage($this->SearchDataMessages,"ShowAll").":"
               ),
               $this->MakeRadioSet //($name,$values,$titles,$selected=-1)
               (
                  $this->ModuleName."_IncludeAll",
                  array(1,2),
                  $this->GetMessage($this->SearchDataMessages,"NoYes"),
                  $this->CGI2IncludeAll()
               ).
               /* $this->MakeSelectField */
               /* ( */
               /*    $this->ModuleName."_IncludeAll", */
               /*    array(1,2), */
               /*    $this->GetMessage($this->SearchDataMessages,"NoYes"), */
               /*    $this->CGI2IncludeAll(), */
               /*    array(), */
               /*    $this->GetMessage($this->SearchDataMessages,"ShowAllTitles"), */
               /*    $this->GetMessage($this->SearchDataMessages,"ShowAllTitle") */
               /* ). */
               ""
             );
        }


        $nitemspp=$this->GetCGIVarValue($this->ModuleName."_NItemsPerPage");
        if (!preg_grep('/^Paging/',$omitvars))
        {
            $val=$this->GetCGIVarValue($this->ModuleName."_Paging");
            if ($val=="") { $val=0; }
            array_push
            (
               $row3,
               $this->PagingFormPagingRow($nitemspp)
            );
        }

        if (!preg_grep('/^DataGroups/',$omitvars))
        {
            $field=$this->DataGroupsSearchField();
            if ($field!="")
            {
                array_push
                ( 
                   $row1,
                   $this->B($this->GetMessage($this->ItemDataGroupsMessages,"DataGroupsTitle").":"),
                   $field
                );
            }
        }

        array_push($table,$row1,$row2);
        if (!preg_grep('/^Paging/',$omitvars))
        {
            $prows=$this->PagingFormPagingRow($nitemspp);
            foreach ($prows as $row) { array_push($table,$row); }
        }

        array_push
        (
           $table,
           $this->GetLatexSelectFieldRow("Plural")
        );
    }
}


?>