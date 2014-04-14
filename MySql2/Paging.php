<?php


class Paging extends Items
{

    ###*
    ###* Variables of Items class:

    var $Page,$NItemsPerPage=15,$NumberOfItems=0;
    var $ActiveID=0;
    var $PresetPage="";
    var $PagingFormWritten=FALSE;
    var $PagingMessages="Paging.php";

    function InitPaging($hash=array())
    {
    }

    function GetNItemsPerPage()
    {
        $nitemspp=$this->GetCGIVarValue($this->ModuleName."_NItemsPerPage");
        if ($nitemspp=="") { $nitemspp=$this->NItemsPerPage; }

        return $nitemspp;
    }

    function GetPageNo($nitems)
    {
        $pageno=$this->PresetPage;
        if ($pageno=="") { $pageno=$this->GetPOST($this->ModuleName."_Page"); }

        if ($pageno=="" || $pageno>$this->GetNPages($nitems)) { $pageno=1; }

        return $pageno;
    }

    function GetNPages($nitems)
    {
        $nitemspp=$this->GetNItemsPerPage();
        if ($nitems>$nitemspp)
        {
            $npages=$nitems/$nitemspp;
            $npages=(int) $npages;
            $res=$nitems % $nitemspp;
            if ($res>0) { $npages++; }
        }
        else
        {
            $npages=1;
        }

        return $npages;
    }


    function PageNo2ItemNos($nitems,$items=array())
    {
        if (count($items)==0)     { $items=$this->ItemHashes; }
        $nitemspp=$this->GetNItemsPerPage();

        if ($nitems>$nitemspp)
        {
            if ($this->ActiveID && $this->ActiveID>0)
            {
                $firstitem=0;
                foreach ($items as $id => $item)
                {
                    if ($item[ "ID" ]==$this->ActiveID)
                    {
                        $firstitem=$id;
                        $offset=$nitems;
                    }
                }
            }
            else
            {
                $pageno=$this->GetPageNo($nitems);

                if ($pageno==0)
                {
                    $firstitem=0;
                    $offset=$nitems;
                }
                elseif (preg_match('/\d+/',$pageno) && $pageno>0)
                {
                    $res=$nitemspp % $nitems;

                    $firstitem=($pageno-1)*$nitemspp;
                    $offset=$res;
                }
                else
                {
                    $firstitem=0;
                    $offset=0;
                }
            }
        }
        else
        {
            $firstitem=0;
            $offset=$nitems;
        }

        return array($firstitem,$offset);
     }



    function PagingSelects()
    {
        $nitems=$this->NumberOfItems;
        $page=$this->GetPageNo($this->NumberOfItems);
        $npages=$this->GetNPages($this->NumberOfItems);
        $nitemspp=$this->GetNItemsPerPage();

        $page=$this->GetPOST($this->ModuleName."_Page");
        if ($page=="") { $page=1; }

        $table=array();
        if ($this->NumberOfItems>=0)
        {
            $values=array();
            $names=array();

            if ($npages>=0)
            {
                $values=array(0);
                $names=array($this->GetMessage($this->PagingMessages,"All"));
            }

            $start=1;
            for ($i=1;$i<=$npages;$i++)
            {
                $end=$start+$nitemspp-1;
                if ($end>$nitems) { $end=$nitems; }

                array_push($values,$i);
                array_push
                (
                   $names,
                   "p. ".$i.": (".
                   $start." ".$this->GetMessage($this->PagingMessages,"To")." ".$end.")"
                );

                $start=$end+1;
            }

            $start=($page-1)*$nitemspp+1;
            $end=$page*$nitemspp;
            if ($end>$nitems) { $end=$nitems; }
            array_push
            (
                $table,
                array
                (
                   $this->B($this->GetMessage($this->PagingMessages,"Page").": "),
                   $this->MakeSelectField($this->ModuleName."_Page",$values,$names,$page)." ",
                   $this->B($this->GetMessage($this->PagingMessages,"Total").": "),
                   $npages,
                )
            );
        }

        return $table;
    }

    function PagingFormPagingRow($nitemspp)
    {
        $nitems=$this->NumberOfItems;
        $page=$this->GetPageNo($this->NumberOfItems);
        $npages=$this->GetNPages($this->NumberOfItems);
        $nitemspp=$this->GetNItemsPerPage();

        $rows=array();
        if ($this->NumberOfItems>=0)
        {
            if ($page>0)
            {
                $start=($page-1)*$nitemspp+1;
                $end=$page*$nitemspp;
            }
            else
            {
                $start=1;
                $end=$nitems;
            }

            if ($end>$nitems) { $end=$nitems; }

            $rows=array
            (
               array
               (
                  $this->B
                  (
                     $this->GetMessage($this->SearchDataMessages,"PagingTitle").":"
                  ),
                  $this->MakeInput
                  (
                     $this->ModuleName."_NItemsPerPage",
                     $this->GetNItemsPerPage(),
                     2
                  ).
                  $this->ItemsName." ".
                  $this->GetMessage($this->PagingMessages,"PerPage"),
                  $this->B($this->ItemsName.": "),
                  count($this->ItemHashes).": ".
                  $start." ".
                  $this->GetMessage($this->PagingMessages,"To")." ".
                  $end." ".
                  $this->GetMessage($this->PagingMessages,"Of")." ".
                  $this->NumberOfItems
               )
            );

            foreach ($this->PagingSelects() as $row)
            {
                array_push($rows,$row);
            }
        }
        else
        {
            $rows=array
            (
                array
                (
                   $this->B
                   (
                      $this->GetMessage($this->SearchDataMessages,"PagingTitle").":"
                   ),
                   $this->GetMessage($this->PagingMessages,"No")." ".
                   $this->ItemsName." ".
                   $this->GetMessage($this->PagingMessages,"Selected"),
                   ""
                )
            );
        }

        return $rows;
    }
}
?>