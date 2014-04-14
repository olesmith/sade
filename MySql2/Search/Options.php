<?php


class SearchOptions extends Paging
{
    //*
    //* function CGI2IncludeAll, Parameter list:
    //*
    //* Retrieves CGI POST value of $this->ModuleName."_IncludeAll",
    //* setting on as default.
    //*

    function CGI2IncludeAll()
    {
        $val=$this->GetCGIVarValue($this->ModuleName."_IncludeAll");

        $default=1;
        if ($this->IncludeAllDefault) { $default=2; }
        if ($val=="") { $val=$default; }

        return $val;
    }

    //*
    //* function CGI2Edit, Parameter list:
    //*
    //* Retrieves CGI POST value of $this->ModuleName."_IncludeAll",
    //* setting on as default.
    //*

    function CGI2Edit()
    {
        $val=$this->GetCGIVarValue($this->ModuleName."_Edit");

        $default=1;
        if ($val=="") { $val=$default; }

        return $val;
    }




    //*
    //* function AddSearchOptionFields, Parameter list: $omitvars,&$table
    //*
    //* Adds the IncludeAll, Output, Paging and Data Group
    //* fields.
    //*

    function AddSearchOptionFields($omitvars,&$table)
    {
        $allradiotitles=$this->GetMessage($this->SearchDataMessages,"NoYes");
        $nitemspp=$this->GetCGIVarValue($this->ModuleName."_NItemsPerPage");

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
               $this->MakeSelectField
               (
                  $this->ModuleName."_IncludeAll",
                  array(1,2),
                  $allradiotitles,
                  $this->CGI2IncludeAll(),
                  array(),
                  $this->GetMessage($this->SearchDataMessages,"ShowAllTitles"),
                  $this->GetMessage($this->SearchDataMessages,"ShowAllTitle")
               )
             );
        }


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