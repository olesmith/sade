<?php

class SearchCGI extends SearchOptions
{

    //*
    //* function GetSearchVarCGIName, Parameter list: $data
    //*
    //* Returns the name of the CGI search var associated with $data.
    //*

    function GetSearchVarCGIName($data)
    {
        return $this->Application.$this->ModuleName."_".$data."_Search";
    }

    //*
    //* function GetTextSearchVarCGIName, Parameter list: $data
    //*
    //* Returns the name of the CGI TEXT search var associated with $data.
    //* This valid, only for SQL search fields, with SqlTextSearch set
    //* (in ItemData[ $data ])
    //*

    function GetTextSearchVarCGIName($data)
    {
        return $this->Application.$this->ModuleName."_".$data."_Search_Text";
    }

    //*
    //* function GetSearchVarCGIValue, Parameter list: $data,$rdata=""
    //*
    //* Returns the value of the CGI search var associated with $data.
    //*

    function GetSearchVarCGIValue($data,$rdata="")
    {
        if (empty($rdata))
        {
            $rdata=$this->GetSearchVarCGIName($data);
        }

        $value=$this->GetPOST($data);
        if ($value=="")
        {
            $value=$this->GetGETOrPOST($rdata);
        }

        $cvalue=$this->GetCookie($rdata);

        if ($value=="")
        {
            if ($data=="ID")
            {
                $value=$this->GetCGIVarValue("ID");
            }

            if (!empty($this->ItemData[ $data ][ "GETSearchVarName" ]))
            {
                $value=$this->GetGET($this->ItemData[ $data ][ "GETSearchVarName" ]);
            }
        }

        if ($value=="")
        {
            $value=$cvalue;
        }

        if (
              !empty($this->ItemData[ $data ][ "SearchCheckBox" ])
              &&
              $this->CheckHashKeyValue($this->ItemData[ $data ],"SearchCheckBox",1)
           )
        {
            $cgikeys=array();
            for ($i=0;$i<count($this->ItemData[ $data ][ "Values" ]);$i++)
            {
                array_push($cgikeys,$rdata."_".($i+1));
            }

            $values=array();
            foreach ($cgikeys as $no => $cgikey)
            {
                $rcgikey="";
                if (preg_match('/(\d)+$/',$cgikey,$matches))
                {
                    $rcgikey=$matches[1];
                }

                if ($rcgikey!="" && $this->GetPOST($cgikey)==$rcgikey)
               {
                    array_push($values,$rcgikey);
                }
            }

            if (empty($values) && !empty($this->ItemData[ $data ][ "SearchDefault" ]))
            {
                $values=array($this->ItemData[ $data ][ "SearchDefault" ]);
            }

            return $values;
        }
        elseif (
                  !empty($this->ItemData[ $data ])
                  &&
                  $this->CheckHashKeyValue($this->ItemData[ $data ],"IsDate",TRUE)
               )
        {
            $name=$this->GetSearchVarCGIName($data);
            
            if (empty($value))
            {
                //take default
                $value=$this->ItemData[ $data ][ "SearchDefault" ];
            }
            else
            {
                $value=$this->HtmlDateInputValue($data,TRUE);
            }

            return $value;
        }

        if (
              $value!=0
              &&
              empty($this->ItemData[ $data ][ "SearchCheckBox" ])
              &&
              preg_match('/^0?$/',$value)
              &&
              $this->CheckHashKeySet($this->ItemData[ $data ],"SearchDefault")
           )
        {
            $value=$this->ItemData[ $data ][ "SearchDefault" ];
        }


       return $value;
    }

    //*
    //* function GetTextSearchVarCGIValue, Parameter list: $data
    //*
    //* Returns the value of the CGI TEXT search var associated with $data.
    //* This valid, only for SQL search fields, with SqlTextSearch set
    //* (in ItemData[ $data ])
    //*

    function GetTextSearchVarCGIValue($data)
    {
        $rdata=$this->GetSearchVarCGIName($data);
        $rrdata=$this->GetTextSearchVarCGIName($data);

        $value=$this->GetCGIVarValue($rdata);
        $rvalue=$this->GetCGIVarValue($rrdata);

        if ($value!="" && $value!=0)
        {
            $rvalue="";
        }

        return $rvalue;
    }

    //*
    //* function TrimSearchValue, Parameter list: $value
    //*
    //* Trims the search value read, that is:
    //*
    //* Removes accented characters
    //* Convert all to lowercase.
    //*

    function TrimSearchValue($value)
    {
        $value=html_entity_decode($value,ENT_COMPAT,'UTF-8');
        $value=$this->Text2Sort($value);
        $value=strtolower($value);

        $value=preg_replace('/[^\.]?\*/',".*",$value);
        return $value;
    }

    //*
    //* function SearchVarsAsHiddens, Parameter list: 
    //*
    //* Creates hiddens according to search vars defined.
    //*

    function SearchVarsAsHiddens()
    {
        $hiddens=array();
        foreach ($this->GetSearchVars() as $data)
        {
            if ($this->GetDataAccessType($data)>=1)
            {
                $rdata=$this->GetSearchVarCGIName($data);
                $value=$this->GetSearchVarCGIValue($data);

                if ($value!="" && !is_array($value) && !preg_match('/^0$/',$value))
                {
                    array_push($hiddens,$this->MakeHidden($rdata,$value));
                }
            }
        }

        return $hiddens;
    }

    //*
    //* function SearchVarsURL, Parameter list: 
    //*
    //* Creates URL according to search vars defined.
    //*

    function SearchVarsAsURL()
    {
        $hiddens=array();
        foreach ($this->GetSearchVars() as $data)
        {
            if ($this->GetDataAccessType($data)>=1)
            {
                $rdata=$this->GetSearchVarCGIName($data);
                $value=$this->GetSearchVarCGIValue($data);

                if ($value!="" && (!preg_match('/^0$/',$value)))
                {
                    array_push($hiddens,$rdata."=".$value);
                }
            }
        }

        return join("&",$hiddens);
    }
}


?>