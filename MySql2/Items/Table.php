<?php


class ItemsTable extends ItemsRead
{
    function ItemsTableRow($edit,$item,$nn,$datas,$subdatas=array(),&$tbl=array(),$even)
    {
        //Test if we have individual access to Edit $item
        if (!$this->ActionAllowed("Edit",$item))
        {
            $edit=0;
        }

        $item[ "_RID_" ]="";
        if (isset($item[ "ID" ])) { $item[ "_RID_" ]=sprintf("%03d",$item[ "ID" ]); }
        $nn=sprintf("%03d",$nn);

        $tabindex=1;
        $row=array();
        foreach ($datas as $data)
        {
            if ($data=="")
            {
                $value="&nbsp;";
            }
            elseif ($data=="No")
            {
                $value=$this->B($nn);
            }
            elseif (preg_match('/^text\_/',$data))
            {
                $value=preg_replace('/^text\_/',"",$data);
                $value=$this->Span($value,array("CLASS" => 'Bold Right'));
            }
            elseif (preg_match('/newline\((\d*)\)\((\d*)\)/',$data,$matches))
            {
                $preempties=0;
                if (isset($matches[1])) { $preempties=$matches[1]; }
                
                $postempties=0;
                if (isset($matches[2])) { $postempties=$matches[2]; }
                for ($n=1;$n<=$postempties;$n++)
                {
                    array_push($row,"");
                }

                array_push($tbl,$row);

                $row=array();
                for ($n=1;$n<=$preempties;$n++)
                {
                    array_push($row,"");
                }
                $value="";
            }
            elseif (!isset($this->ItemData[ $data ]) && isset($this->Actions[ $data ]))
            {
                if ($even)
                {
                    $this->Actions[ $data ][ "Icon" ]=
                        preg_replace
                        (
                           '/light.png$/',
                           "dark.png",
                           $this->Actions[ $data ][ "Icon" ]
                        );
                }
                else
                {
                    $this->Actions[ $data ][ "Icon" ]=
                        preg_replace
                        (
                           '/dark.png/',
                           "light.png",
                           $this->Actions[ $data ][ "Icon" ]
                        );
                }

                $value=$this->ActionEntry($data,$item);
                if (!empty($this->Actions[ $data ][ "Icon" ]))
                {
                    $value=$this->Center($value);
                }
            }
            elseif (
                    preg_match('/\S+\_\S+/',$data)
                    &&
                    empty($this->ItemData[ $data ])
                    &&
                    isset($item[ $data ])
                   )
            {
                 $value=$item[ $data ];
            }
            else
            {
                $value=$this->MakeField($edit,$item,$data,TRUE,$tabindex);//TRUE for plural
                
                if (!preg_match('/\S/',$value)) { $value="&nbsp;"; }
            }

            if (
                $edit==1
                &&
                $this->Profile!="Public"
                &&
                isset($item[ $data."_Message" ])
                &&
                $item[ $data."_Message" ]!=""
               )
            {
                $value.=$this->Font($item[ $data."_Message" ],array("CLASS" => 'errors'));
            }

            array_push($row,$value);

            $tabindex++;
        }

        if (count($row)>0 && isset($item[ "ID" ]))
        {
            $row[0].=$this->HtmlTags("A","",array("NAME" => "#".$this->ModuleName."_".$item[ "ID" ]));
        }

        array_push($tbl,$row);
        if (count($subdatas)>0)
        {
            $ctable=array();
            for ($i=1;$i<=$item[ $countdef[ "Counter" ] ];$i++)
            {
                $crow=array($this->B($i));

                foreach ($subdatas as $data)
                {
                    $value=$this->MakeField($edit,$item,$data.$i,TRUE);//TRUE for plural
                    array_push($crow,$value);
                }

                array_push($ctable,$crow);
            }

            array_push
            (
               $tbl,
               array
               (
                  $this->HtmlTable
                  (
                     $subtitles,
                     $ctable,
                     array
                     (
                        'BORDER' => 1,
                        'ALIGN' => 'center'
                     )
                  )
               )
            );
        }
    }

    //*
    //* function ItemsTable, Parameter list: $title,$edit=0,$datas=array(),$items=array(),$countdef=array(),$titles=array(),$sumvars=TRUE,$cgiupdatevar="Update"
    //*
    //* Joins table as a matrix for items in $items, or if empty, in $this->ItemHashes.
    //* Includes $title as a H2 title.
    //* If $edit==1 (Edit), produces input fields (Edit), otherwise just 'shows' data. Default 0 (Show).
    //* $titles should be deprecated!!! Title row is inserted in Table class.
    //* 

    function ItemsTable($title="",$edit=0,$datas=array(),$items=array(),$countdef=array(),$titles=array(),$sumvars=TRUE,$cgiupdatevar="Update")
    {
        if (count($items)==0)     { $items=$this->ItemHashes; }
        if (count($datas)==0)     { $datas=$this->GetDefaultDataGroup(); }

        $searchvars=$this->GetDefinedSearchVars($datas);
        if ($this->AddSearchVarsToDataList)
        {
            $datas=$this->AddSearchVarsToDataList($datas);
        }

        if ($this->GetPOST($cgiupdatevar))
        {
            $items=$this->UpdateItems($items);
        }

        $showall=$this->GetPOST($this->ModuleName."_Page");
        if (empty($showall))
        {
            $showall=$this->ShowAll;
        }
        else
        {
            $showall=TRUE;
        }

        if (!$showall)
        {
            $nitems=count($items);
            $itemnos=$this->PageNo2ItemNos($nitems,$items);
            $nn=$itemnos[0]+1;
        }
        else { $nn=1; }

        $actions=array();
        if (is_array($this->ItemActions)) { $actions=$this->ItemActions; }

        $subdatas=array();
        $subtitles=array();
        if (count($countdef)>0)
        {
            $subdatas=$this->CheckHashKeysArray
            (
               $countdef,
               array($this->Profile."_Data",$this->LoginType."_Data","Data")
            );

            $rdatas=array();
            foreach ($subdatas as $data)
            {
                array_push($rdatas,$data."1");
            }

            $subtitles=$this->GetDataTitles($rdatas);

            $title1="";
            if (isset($countdef[ "NoTitle" ])) { $title1=$countdef[ "NoTitle" ]; }

            array_unshift($subtitles,$title1);
        }

        $tbl=array();
        if (count($titles)>0)
        {
            array_push
            (
               $tbl,
               array
               (
                  "Class" => 'head',
                  "Row" => $this->GetSortTitles($titles)
               )
            );
        }

        $sums=array();
        foreach ($this->SumVars as $data)
        {
            $sums[ $data ]=0;
        }

        $even=FALSE;
        foreach ($items as $item)
        {
            $this->ItemsTableRow($edit,$item,$nn,$datas,$subdatas,$tbl,$even);
            foreach ($this->SumVars as $data)
            {
                if (isset($item[ $data ]))
                {
                    $sums[ $data ]+=$item[ $data ];
                }
            }

            if ($even) { $even=FALSE; }
            else       { $even=TRUE; }

            $nn++;
        }

        if ($sumvars && count($this->SumVars)>0)
        {
            $row=array();
            foreach ($datas as $data)
            {
                if ($data=="No")               { array_push($row,$this->B("&Sigma;")); }
                elseif (isset($sums[ $data ])) { array_push($row,$sums[ $data ]); }
                else                           { array_push($row,"&nbsp;"); }
            }

            array_push($tbl,$row);
        }

        return $tbl;
    }

    //*
    //* function ItemsSelectForm, Parameter list: 
    //*
    //* Creates an Items Select Form, with first, previous, current, next and last links.
    //* 

    function ItemsSelectForm($title,$ids,$names,$titles,$current,$args,$argsfield,$selectfieldname,$posthtml,$nameref="ItemSelect")
    {
        $last=-1;
        $first=-1;
        $prev=-1;
        $next=-1;

        if (count($ids)>0)
        {
            $first=0;
            $last=count($ids)-1;
        }

        $i=0;
        $rnames=array();
        $pos=array();
        foreach ($ids as $id)
        {
            if ($id==$current)
            {
                if ($i==0)             { $first=-1; }
                if ($i==count($ids)-1) { $last=-1; }
                if ($i>1)              { $prev=$i-1; }
                if ($i<count($ids)-2)  { $next=$i+1; }
            }

            $rnames[ $id ]=$names[$i];
            $pos[ $id ]=$i;

            $i++;
        }

        $lastname="";
        $firstname="";
        $prevname="";
        $nextname="";
        if ($last>=0)  { $lastname =$names[ $last  ]; }
        if ($first>=0) { $firstname=$names[ $first ]; }
        if ($prev>=0)  { $prevname =$names[ $prev  ]; }
        if ($next>=0)  { $nextname =$names[ $next  ]; }


        $prevtext="";
        if ($prev>=0 && $prev-$first>1) { $prevtext="..."; }

        $nextttext="";
        if ($next>=0 &&  $last-$next>1) { $nextttext="..."; }


        $prelinks=array();
        if ($first>=0)
        {
            $args[ $argsfield ]=$ids[ $first ];
            array_push
            (
               $prelinks,
               $this->Href
               (
                  "?".$this->Hash2Query($args)."#".$nameref,
                  $firstname,
                  "Prim. ".$title." ".$names[ $first ]
               )
            );
        }

        if ($prev>=0)
        {
            $args[ $argsfield ]=$ids[ $prev ];
            array_push
            (
               $prelinks,
               $prevtext,
               $this->Href
               (
                  "?".$this->Hash2Query($args)."#".$nameref,
                  $prevname,
                  "Prev. ".$title." ".$names[ $prev ]
               )
            );
        }

        $postlinks=array();
        if ($next>=0)
        {
            $args[ $argsfield ]=$ids[ $next ];
            array_push
            (
               $postlinks,
               $this->Href
               (
                  "?".
                  $this->Hash2Query($args)."#".$nameref,
                  $nextname,
                  "Prox. ".$title." ".$names[ $next ]
               ),
               $nextttext
            );
        }

        if ($last>=0)
        {
            $args[ $argsfield ]=$ids[ $last ];
            array_push
            (
               $postlinks,
               $this->Href
               (
                  "?".$this->Hash2Query($args)."#".$nameref,
                  $lastname,
                  "Ult. ".$title." ".$names[ $last ]
               )
            );
        }

        return
            preg_replace
            (
               '/&'.$argsfield.'=\d+/',
               "",
               $this->StartForm("?".$this->Hash2Query($args))
            )."\n".
            $this->Anchor($nameref,"Selecionar ".$title.":")."\n".
            "[ "."\n".
            join(" &nbsp;\n ",$prelinks)."\n".
            $this->MakeSelectfield
            (
               $selectfieldname,
               $ids,
               $names,
               $current,
               array(),
               $titles
            )."\n".
            join(" &nbsp;\n ",$postlinks).
            " ]"."\n".
            $this->Button("submit","GO")."\n".
            $posthtml."\n".
            $this->EndForm().
            "";
    }
}
?>