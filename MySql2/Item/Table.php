<?php

class ItemTable extends ItemEdits
{
    //*
    //* Creates item table row
    //*

    var $ItemEditData=array();


    //*
    //* Creates item table, calling ItemTableRow for each var.
    //*

    function ItemTable($edit=0,$item=array(),$noid=FALSE,$rdatalist=array(),$tbl=array(),
                       $plural=FALSE,$includename=TRUE,$includecompulsorymsg=TRUE)
    {
        if (count($item)>0) {} else { $item=$this->ItemHash; }
        $item=$this->TestItem($item);

        $datalist=array_keys($this->ItemData);
        $datalist=preg_grep('/^[AMC]Time$/',$datalist,PREG_GREP_INVERT);
        if (
            count($rdatalist)==0
            &&
            count($this->ItemEditData)>0
           )
        {
            $rdatalist=$this->ItemEditData;
        }

        if (count($rdatalist)==0) { $rdatalist=$datalist; }
        if ($noid) { $rdatalist=preg_grep('/^ID$/',$rdatalist,PREG_GREP_INVERT); }

        if ($includename)
        {
            array_push
            (
               $tbl,
               $this->ItemAnchor($item).
               $this->H(5,$this->GetItemName($item))
            );
        }

        $compulsories=0;
        foreach ($rdatalist as $data)
        {
            $hidden=FALSE;
            if (
                isset($this->ItemData[ $data ][ "Hidden" ]) &&
                $this->ItemData[ $data ][ "Hidden" ]
               )
            {
                $hidden=TRUE;
            }

            if (
                !$hidden &&
                $data!="No" &&
                isset($this->ItemData[ $data ])
               )
            {
                $row=array();
                $this->ItemTableRow($edit,$item,$data,$compulsories,$row,$plural);
                if (count($row)>0) { array_push($tbl,$row); }
            }
            elseif (isset($this->Actions[ $data ]))
            {
                $row=array
                (
                   $this->DecorateDataTitle
                   (
                      $this->GetRealNameKey($this->Actions[ $data ])
                   ),
                   $this->ActionEntry($data,$item)
                );
                if (count($row)>0) { array_push($tbl,$row); }
            }

        }

        if ($includecompulsorymsg && $compulsories>0 && $edit==1)
        {
            array_push
            (
               $tbl,
               array
               (
                  $this->CompulsoryMessage()
               )
             );
        }


        return $tbl;
    }


    //*
    //* function ItemTableRow, Parameter list: $edit,$item,$data,&$compulsories=0,&$row=array(),$plural=FALSE
    //*
    //* Creates ItemTableRow.
    //*

    function ItemTableRow($edit,$item,$data,&$compulsories=0,&$row=array(),$plural=FALSE)
    {
        $dagger=$this->SPAN("*",array("CLASS" => "errors"));

        $access=$this->GetDataAccessType($data,$item);

        if ($access>=1)
        {
            $name=$this->ItemData[ $data ][ "Name" ];
            $title=$this->ItemData[ $data ][ "Title" ];
            if (
                  preg_match('/^([^_]+)_(.+)/',$data,$matches)
                  &&
                  isset($this->ItemData[ $matches[1] ])
                  &&
                  $this->ItemData[ $matches[1] ][ "SqlObject" ]!=""
                )
            {
                $object=$this->ItemData[ $matches[1] ][ "SqlObject" ];

                if (isset($this->$object->ItemData[ $matches[2] ]))
                {
                    $name=$this->$object->ItemData[ $matches[2] ][ "Name" ];
                }
            }
            elseif ($this->LoginType=="Admin" && $this->ItemData[ $data ][ "NamerLink" ]) 
            {
                if ($item[ $data ]!="" && $item[ $data ]>0)
                {
                    $name="<A TARGET='_item'";
                    if ($this->ItemData[ $data ][ "NamerText" ])
                    {
                        $name.=" TITLE='".$this->ItemData[ $data ][ "NamerText" ]."'";
                    }
                    $name.=
                        " HREF='".
                        $this->ItemData[ $data ][ "NamerLink" ]."=".
                        $item[ $data ]."'>".$this->ItemData[ $data ][ "Name" ]."</A>";

                    $name=preg_replace('/#LoginType/',$this->LoginType,$name);
                }
            }
            elseif (isset($this->ItemDerivedData[ $data ][ "LongName" ]))
            {
                $name=$this->ItemDerivedData[ $data ][ "LongName" ];
            }
            elseif (isset($this->ItemDerivedData[ $data ][ "Name" ]))
            { 
                $name=$this->ItemDerivedData[ $data ][ "Name" ];
            }
            elseif ($this->ItemData[ $data ][ "LongName" ])
            {
                $name=$this->ItemData[ $data ][ "LongName" ];
            }

            $name.=":";

            $value="";
            if ($access==1)
            {
                $value=$this->MakeShowField($data,$item);
            }
            else
            {
                $value=$this->MakeField($edit,$item,$data,$plural);
            }

            if (isset($item[ $data."_Message" ]) && $item[ $data."_Message" ]!="" && $this->LoginType!="Public")
            {
                $value.="<FONT CLASS='errors'>".$item[ $data."_Message" ]."</FONT>";
            }

            $action=$this->DetectAction();
            $add="";
            if (
                $this->ItemData[ $data ][ "Compulsory" ] &&
                $edit==1
               )
            {
                $add=$dagger;
                $compulsories++;
            }

            if ($title=="") { $title=$name; }
            if ($this->ItemData[ $data ][ "Compulsory" ] && $this->LoginType!="Public" && $edit==1)
            {
                $title.=" - ".$this->GetMessage($this->ItemDataMessages,"CompulsoryFieldTag")."!";
                $add.=$this->SPAN
                (
                   "*",
                   array
                   (
                      "CLASS" => 'errors',
                   )
                );
            }
            array_push
            (
               $row,
               $this->DecorateDataTitle($name,$title).$add,
               $value
            );
        }

        return $row;
    }

    //*
    //* 
    //*

    function ItemAnchor($item=array(),$anchor="",$text="")
    {
        if (count($item)==0)
        {
            $item=$this->ItemHash;
        }

        if ($anchor=="" && isset($item[ "ID" ]))
        {
            $anchor=$this->ModuleName."_".$item[ "ID" ];
        }

        return "<A NAME='".$anchor."'>".$text."</A>";
    }

    //*
    //* 
    //*

    function CompulsoryMessage()
    {
        return $this->Center
        (
           "&gt;&gt; ".
           $this->GetMessage($this->ItemDataMessages,"CompulsoryMessage").
           " &lt;&lt;",
           array("CLASS" => 'datatitlelink')
        );
    }

    //*
    //* 
    //*

    function ItemAnchorLink($item=array(),$anchor="",$text="")
    {
        if (count($item)==0)
        {
            $item=$this->ItemHash;
        }

        if ($anchor=="")
        {
            $anchor=$this->ModuleName."_".$item[ "ID" ];
        }

        if ($text=="")
        {
            $text=$this->IMG("../icons/forward.gif");
        }

        return "<A HREF='#".$anchor."'>".$text."</A>";
    }


}
?>