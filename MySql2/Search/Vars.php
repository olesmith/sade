<?php


class SearchVars extends SearchInit
{
    protected $SearchVars=array();

    //*
    //* function IsSearchVar, Parameter list: $data
    //*
    //* Returns TRUE if $data is a search var.
    //*

    function IsSearchVar($data)
    {
        if (isset($this->ItemData[ $data ][ "Search" ]) && $this->ItemData[ $data ][ "Search" ])
        {
            if ($this->GetDataAccessType($data)>=1)
            {
                return TRUE;
            }
        }

        return FALSE;
    }

    //*
    //* function AddSearchVar, Parameter list: $data
    //*
    //* Marks $data as search var.
    //*

    function AddSearchVar($data)
    {
        $this->ItemData[ $data ][ "Search" ]=TRUE;
        if (!preg_grep('/^'.$data.'$/',$this->SearchVars)) { array_push($this->SearchVars,$data); }
    }

    //*
    //* function RemovesSearchVar, Parameter list: $data
    //*
    //* UnMarks $data as search var.
    //*

    function RemoveSearchVar($data)
    {
        $this->ItemData[ $data ][ "Search" ]=FALSE;
        $this->SearchVars=preg_grep('/^'.$data.'$/',$this->SearchVars,PREG_GREP_INVERT);
    }

    //*
    //* function GetSearchVars, Parameter list: 
    //*
    //* Returns list of vars indicated with Search TRUE.
    //*

    function GetSearchVars()
    {
        if (empty($this->SearchVars))
        {
            $this->SearchVars=array();
            foreach (array_keys($this->ItemData) as $data)
            {
                if ($this->IsSearchVar($data))
                {
                    array_push($this->SearchVars,$data);
                }
            }
        }

        return $this->SearchVars;
    }


    //*
    //* function GetPreSearchVars, Parameter list: 
    //*
    //* Returns list of pre search vars and their value, that is: 
    //* search vars which are actually INT's: INT and ENUM. These
    //* may be included with initial SELECT statement, as they
    //* are 'exact'.
    //*

    function GetPreSearchVars()
    {
        $searchvars=array();
        foreach ($this->GetSearchVars() as $data)
        {
            if ($this->GetDataAccessType($data)>=1)
            {
                $rdata=$this->GetSearchVarCGIName($data);
                $value=$this->GetSearchVarCGIValue($data);
                if (preg_match('/^(ENUM|INT)$/',$this->ItemData[ $data ][ "Sql" ]))
                {
                    if ($value!="" && $value!=0)
                    {
                        $searchvars[ $data ]=$value;
                    }
                    elseif ($this->ItemData[ $data ][ "SqlTextSearch" ])
                    {
                        $value=$this->GetTextSearchVarCGIValue($data);
                        if ($value!="")
                        {
                            if ($value==0) { $searchvars[ $data ]='0'; }
                            unset($searchvars[ $data ]);
                            //don't set in PRE $searchvars[ $data ]=$value;
                        }
                    }
                }
                elseif ($this->ItemData[ $data ][ "SqlMethod" ]!="")
                {
                   if ($value!="" && $value!=0)
                   {
                        $searchvars[ $data ]=1;
                   }
                }
            }
        }

        return $searchvars;
    }

    //*
    //* function GetPostSearchVars, Parameter list: 
    //*
    //* Returns list of post search vars and their value, that is: 
    //* search vars which are NOT INT's: INT and ENUM. These
    //* should be searched over in SearchItems.
    //*

    function GetPostSearchVars()
    {
        $searchvars=array();
        foreach ($this->GetSearchVars() as $data)
        {
            if ($this->GetDataAccessType($data)>=1)
            {
                if ($this->ItemData[ $data ][ "SqlMethod" ]!="")
                {
                }
                elseif (!preg_match('/^(ENUM|INT)$/',$this->ItemData[ $data ][ "Sql" ]))
                {
                   $rdata=$this->GetSearchVarCGIName($data);
                    $value=$this->GetCGIVarValue($rdata);
                    if ($value!="")
                    {
                        $searchvars[ $data ]=$value;
                    }
                }
                elseif ($this->ItemData[ $data ][ "SqlTextSearch" ])
                {
                    $value=$this->GetTextSearchVarCGIValue($data);
                    if ($value!='0')
                    {
                        $searchvars[ $data ]=$this->GetTextSearchVarCGIValue($data);
                    }
                }
            }
        }

        return $searchvars;
    }


    //*
    //* function GetDefinedSearchVars, Parameter list: 
    //*
    //* Returns list of allowed and defined search vars.
    //* If one or more search vars are defined, sets $this->IncludeAll
    //* to 0, in order NOT to read all items.
    //*

    function GetDefinedSearchVars()
    {
        $searchvars=array();
        foreach ($this->GetSearchVars() as $data)
        {
            if ($this->GetDataAccessType($data)>=1)
            {
                $rdata=$this->GetSearchVarCGIName($data);
                $value=$this->GetSearchVarCGIValue($data);

                if (is_array($value))
                {
                    if (count($value)>0)
                    {
                        $searchvars[ $data ]=$value;
                    }
                }
                elseif ($value!="" && (!preg_match('/^0$/',$value)))
                {
                    $searchvars[ $data ]=$value;
                }
                elseif ($this->ItemData[ $data ][ "SqlTextSearch" ])
                {
                    $value=$this->GetTextSearchVarCGIValue($data);
                    if ($value!="")
                    {
                        if ($value==0) { $searchvars[ $data ]='0'; }
                     }
                }
            }
        }

        if (count($searchvars)>0)
        {
            $this->IncludeAll=0;
        }

        return $searchvars;
    }

    //*
    //* function AddSearchVarsToDataList, Parameter list: $datas
    //*
    //* Adds search vars to list of datas to read/display.
    //* Stores previous result in $this->ResSearchVars (hash), and
    //* in case this is set, simply returns it.
    //*

    function AddSearchVarsToDataList($datas)
    {
        if (count($this->ResSearchVars)>0)
        { 
            return array_merge($datas,$this->ResSearchVars);
        }

        $searchvars=$this->GetDefinedSearchVars();

        $ressearchvars=array();
        foreach ($searchvars as $data => $value)
        {
            if (!preg_grep('/^'.$data.'$/',$datas))
            {
                if ($this->ItemData[ $data ][ "SqlMethod" ]=="")
                {
                    array_push($datas,$data);
                    array_push($ressearchvars,$data);
                }
            }
        }

        $this->ResSearchVars=$ressearchvars;

        return $datas;
    }

}


?>