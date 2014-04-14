<?php


class ItemsRead extends ItemsLatex
{
    var $SortsAsOrderBy=FALSE;

    //*
    //* function ReadItems, Parameter list: $where="",$datas=array(),$nosearches=FALSE,$nopaging=FALSE,$includeall=0
    //*
    //* Reads items according to parameters:
    //*
    //* $where: where clause to use in select statement. Default *.
    //* $datas: array of data to read, default all.
    //* $nosearches: Ignore search vars. Default FALSE.
    //* $nopaging: do not do paging. Default FALSE.
    //* $includeall: If ==1, includes all items in table. Default Include_All CGI var,
    //*              from search form.
    //*
    //* If $includeall==0 and no search vars specified, ReadItems do not read any items at all.
    //* Prevents delayed onload, when 'many' items.
    //* ReadItems reads the CGI vars from the search table, calling GetDefinedSearchVars.
    //* If defined search vars not ijn $datas, these will be added.
    //* Stores list read in $this->ItemHashes, and returns this list.
    //*

    function ReadItems($where="",$datas=array(),$nosearches=FALSE,$nopaging=FALSE,$includeall=1)
{
        if (!is_array($where)) { $where=$this->SqlClause2Hash($where); }

        if ($this->NoSearches) { $nosearches=TRUE; }
        if ($this->NoPaging) { $nopaging=TRUE; }

        if ($this->IncludeAll) { $includeall=2; }
        if ($includeall==1)
        {
            $includeall=$this->CGI2IncludeAll();
        }

        //Figure out which data we should read
        $rdatas=$this->FindDatasToRead($datas,$nosearches);

        //Figure out where clause
        $rwhere=$this->FindActualWhere($where,$datas,$nosearches,$includeall);

        $searchvars=array();
        $rsearchvars=$this->GetDefinedSearchVars($datas);

        //Read
        $this->ItemHashes=array();
        if (!empty($rwhere) || count($rsearchvars)>0 || $includeall==2 || !empty($this->OnlyReadIDs))
        {
            $rwhere=$this->GetRealWhereClause($rwhere);

            if ($this->OnlyReadIDs)
            {
                $rrwhere="ID IN ('".join("','",$this->OnlyReadIDs)."')";
                if (empty($rwhere))
                {
                    $rwhere=$rrwhere;
                }
                else
                {
                    $rwhere=$rrwhere." AND ".$rwhere;
                }
            }

            $orderby="";
            if ($this->SortsAsOrderBy)
            {
                if (is_array($this->Sort))
                {
                    $orderby=join(",",$this->Sort);
                }
                else
                {
                    $orderby=$this->Sort;
                }
            }
            $this->ItemHashes=$this->SelectHashesFromTable
            (
               $this->SqlTableName(),
               $rwhere,
               $rdatas,
               FALSE,
               $orderby
            );
            $this->LastWhereClause=$rwhere;
            //var_dump($rwhere);
            //var_dump($rdatas);
            //var_dump(count($this->ItemHashes));
            //var_dump($this->ItemHashes[0]);
        }

        $this->SkipNonAllowedItems();
        $this->ReadItemsDerivedData($rdatas);

        //Search items
        if (!$nosearches && $includeall!=2)
        {
           $this->SearchItems();
        }

        $this->NumberOfItems=count($this->ItemHashes);

        if (!$this->SortsAsOrderBy)
        {
            $this->SortItems();
        }

        if (!$nopaging)
        {
            $no=1;
            foreach (array_keys($this->ItemHashes) as $id)
            {
                $this->ItemHashes[ $id ][ "No" ]=$no;
                $no++;
            }
            $itemnos=$this->PageNo2ItemNos(count($this->ItemHashes),$this->ItemHashes);

            $this->ItemHashes=array_splice($this->ItemHashes,$itemnos[0],$itemnos[1]);
        }

        $this->SetItemsDefaults($rdatas);
        $this->TrimItems($rdatas);
        $this->PostProcessItems();
    }

    //*
    //* function ReadItemsAsHashes, Parameter list: $where="",$datas=array(),$nosearches=FALSE,$nopaging=FALSE,$includeall=FALSE
    //*
    //* Calls ReadItems to read items, and the restores in a assoc array, id as keys.
    //*

    function ReadItemsAsHashes($where="",$datas=array(),$nosearches=FALSE,$nopaging=FALSE,$includeall=FALSE)
    {
        $this->ReadItems($where,$datas,$nosearches,$nopaging,$includeall);
        $ritems=array();
        foreach ($this->ItemHashes as $id => $item)
        {
            $ritems[ $item[ "ID" ] ]=$item;
        }

        return $ritems;
    }
}
?>