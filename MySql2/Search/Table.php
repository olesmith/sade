<?php

class SearchTable extends SearchFields
{
    var $SearchVarTableModule=FALSE;
    var $ExtraSearchRowsMethod=NULL;

    //*
    //* function GenerateSearchVarsTable, Parameter list: $omitvars=array(),$title="",$action="",$addvars=array(),$fixedvalues=array()
    //*
    //* Creates form search vars table. Returns table as matrix.
    //*

    function GenerateSearchVarsTable($omitvars=array(),$title="",$action="",$addvars=array(),$fixedvalues=array())
    {
        $btitle=$this->GetMessage($this->SearchDataMessages,"SearchButton");
        if ($title=="")
        {
            $title=$btitle." ".$this->ItemsName;
        }

        $showall=$this->CGI2IncludeAll();


        $omit=join("|",$omitvars);
        $tbl=array();
        foreach ($this->GetSearchVars() as $var)
        {
            //Search may have been disabled, since call to InitSearchVars - so check again
            if (!$this->ItemData[ $var ][ "Search" ]) { continue; }
            if ($this->ItemData[ $var ][ "NoSearchRow" ]) { continue; }

            $rvar=$var;
            if ($this->CheckHashKeyValue($this->ItemData[ $var ],"Compound",1))
            {
                $rvar=$this->SearchVars[ $var ][ "Var" ];
            }

            if (!preg_match('/^('.$omit.')$/',$rvar))
            {
                if (
                      $this->GetDataAccessType($var)>=1
                      ||
                      $this->CheckHashKeyValue($this->ItemData[ $var ],"Compound",1)
                   )
                {
                    $name=$this->GetSearchVarTitle($var);


                    $method="MakeSearchVarInputField";
                    if ($this->ItemData[ $var ][ "SearchFieldMethod" ]!="")
                    {
                        $method=$this->ItemData[ $var ][ "SearchFieldMethod" ];
                    }

                    $fixedvalue="";
                    if (!empty($fixedvalues[ $var ])) { $fixedvalue=$fixedvalues[ $var ]; }

                    $input=$this->$method($var,$fixedvalue);
                    if ($showall==2)
                    {
                        $input=preg_replace('/NAME=/',"DISABLED='disabled' NAME=",$input);
                    }

                    $row=array
                    (
                       array
                       (
                          "Text" => $name.":",
                          "Class" => 'searchtitle',
                       ),
                    );

                    if (is_array($input))
                    {
                        foreach ($input as $id => $rinput)
                        {
                            array_push($row,$rinput);
                        }
                    }
                    else
                    {
                        array_push($row,$input,"");
                    }

                    array_push($row,"");
                    array_push($tbl,$row);
                }
            }
        }

        if (!preg_grep('/^OptionFields$/',$omitvars))
        {
            $this->AddSearchOptionFields($omitvars,$tbl);
        }

        foreach ($addvars as $addvar)
        {
            $val=$this->GetPOST($addvar[ "Name" ]);
            if ($val=="" && isset($addvar[ "Default" ])) { $val=$addvar[ "Default" ]; }

            $width=10;
            if (isset($addvar[ "Width" ])) { $width=$addvar[ "Width" ]; }

            if (empty($addvar[ "Hidden" ]))
            {
                array_push
                (
                   $tbl,
                   array
                   (
                      $this->B($addvar[ "Title" ]),
                      $this->MakeInput($addvar[ "Name" ],$val,$width),
                      ""
                   )
                );
            }
            else
            {               
                array_push
                (
                   $tbl,
                   array
                   (
                    $this->MakeHidden($addvar[ "Name" ],$val)
                   )
                );
            }
        }

        $tabmovesdown="";
        if ($this->Action=="EditList" || $this->CGI2Edit()==2)
        {
            $tabmovesdown=$this->B
            (
               $this->GetMessage($this->SearchDataMessages,"TabMovesDown"),
               array
               (
                  "TITLE" => $this->GetMessage($this->SearchDataMessages,"TabMovesDown","Title")
                )
            ).
            $this->MakeCheckBox
            (
               $this->ModuleName."_TabMovesDown",
               1,
               $this->GetCGIVarValue($this->ModuleName."_TabMovesDown")
            );
        }

        if (!empty($this->ExtraSearchRowsMethod))
        {
            $method=$this->ExtraSearchRowsMethod;
            $tbl=array_merge
            (
               $tbl,
               $this->$method()
            );
        }

        array_push
        (
           $tbl,
           array
           (
              $this->Center
              (
                 $tabmovesdown.
                 $this->Button("submit",strtoupper($btitle))
              )
           )
        );

        //Title line
        array_unshift
        (
           $tbl,
           array
           (
              $this->Center($this->SPAN
              (
                 $title,
                 array("CLASS" => 'searchtabletitle')
              ))
           )
        );


        return $tbl;
    }

    //*
    //* function SearchPressed, Parameter list: 
    //*
    //* Checks if search form loads for the first time (time to take default)
    //* or if we should obey form values (particular to checkbox'es...)
    //*

    function SearchPressed()
    {
        $searchpressed=$this->GetPOST("SearchPressed");
        if ($searchpressed==1) { $searchpressed=TRUE; }
        else                   { $searchpressed =FALSE; }

        return $searchpressed;
    }

    //*
    //* function SearchVarsTable, Parameter list: $omitvars=array(),$title="",$action="",$addvars=array(),$fixedvalues=array()
    //*
    //* Creates full form search vars table.
    //*

    function SearchVarsTable($omitvars=array(),$title="",$action="",$addvars=array(),$fixedvalues=array(),$module="")
    {
        if ($this->SearchVarsTableWritten) { return ""; }

        if (empty($module)) { $module=$this->SearchVarTableModule; }

        if (empty($module)) { $module=$this->ModuleName; }
        if (empty($action)) { $action="Search"; }

        $this->Singular=FALSE;
        $this->Plural=TRUE;
        $this->SearchVarsTableWritten=FALSE;
        $table=$this->GenerateSearchVarsTable($omitvars,$title,$action,$addvars,$fixedvalues);
        $this->SearchVarsTableWritten=TRUE;

        $action=$this->GetGETOrPOST("Action");
        return 
            $this->StartForm("?ModuleName=".$module."&Action=".$action).
            $this->Html_Table
            (
               "",
               $table,
               array
               (
                  "ALIGN" => 'center',
                  "CLASS" => 'searchtable'
               ),
               array(),
               array(),
               TRUE
            ).
            $this->MakeHidden("SearchPressed",1). //determines if search button has been pressed
            $this->MakeHidden("Action",$action).
            $this->EndForm().
            "";
    }

}


?>