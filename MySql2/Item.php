<?php


include_once("Item/Forms.php");
include_once("Item/Edits.php");
include_once("Item/Table.php");
include_once("Item/Prints.php");
include_once("Item/Reads.php");
include_once("Item/PostProcess.php");
include_once("Item/Latex.php");
include_once("Item/BackRefs.php");
include_once("Item/Tests.php");
include_once("Item/Update.php");

class Item extends ItemUpdate
{
    //*
    //* Variables of Item class:
    //*

    var $ID,$ItemHash;
    var $PersonIDRow,$CoordIDRow;
    var $ItemPostProcessor,$TestMethod;
    var $TimeVars=array("CTime","MTime","ATime");
    var $BackRefDBs=array();
    var $TriggerFunctions=array();
    var $Upload;
    var $ParentTables=array();
    var $HasFileFields,$FileVars=array();
    var $ItemDataMessages="Item.php";
    var $NoFieldComments=FALSE;
    var $FormWasUpdated=FALSE;
    var $CreatorField="Creator";
    var $UploadPath="Uploads/#Module";
    var $UploadFilesHidden=FALSE;

    //*
    //* function InitItem, Parameter list: $hash=array()
    //*
    //* Initializer.
    //*

    function InitItem($hash=array())
    {
        foreach ($this->ItemData as $data => $hash)
        {
            if (preg_match('/^FILE$/',$hash[ "Sql" ]))
            {
                array_push($this->FileVars,$data);
            }
        }

        $this->HasFileFields=1;
    }



    //*
    //* function TrimCaseItem, Parameter list: $item
    //*
    //* Trims casing for each ItemData definition,
    //* which has TrimCase set.
    //* Returns modified item.
    //*

    function TrimCaseItem($item)
    {
        foreach ($this->DatasRead as $data)
        {
            if (isset($this->ItemData[ $data ]) && $this->ItemData[ $data ][ "TrimCase" ])
            {
                $item[ $data ]=$this->TrimCase($item[ $data ]);
            }
         }

        return $item;
    }


    //*
    //* function GetDataPrefix, Parameter list: $data
    //*
    //* Returns prefix to use in parent object; items and titles
    //*

    function GetDataPrefix($data)
    {
        $prefix=$data."_";
        if (isset($this->ItemData[ $data ][ "SqlDataPrefix" ]))
        {
            $prefix=$this->ItemData[ $data ][ "SqlDataPrefix" ];
        }

        return $prefix;
    }


    function GetItemName($item=array(),$datas=array())
    {
        if (!is_array($item) && preg_match('/^\d+$/',$item))
        {
            $item=$this->ReadItem($item,$datas);
        }
        elseif (count($item)==0)
        {
            $item=$this->ItemHash;
        }


        $name="";
        if ($this->ItemNamer!="")
        {
            if (preg_match('/#/',$this->ItemNamer))
            {
                $name=$this->Filter($this->ItemNamer,$item);
            }
            else
            {
                if (count($item)>0)
                {
                    if (!isset($item[ $this->ItemNamer ]))
                    {
                        if (!isset($this->ItemData[ $this->ItemNamer ]))
                        {
                            print "Item: ".$this->ModuleName.": Invalid Itemnamer: ".$this->ItemNamer."<BR>";
                            var_dump($item);
                        }
                    }
                    else
                    {
                        $name=$item[ $this->ItemNamer ];
                    }
                }
            }
        }

        return $name;
    }

    //*
    //* Returns full (relative) upload path: UploadPath/Module.
    //*

    function GetUploadPath()
    {
        $path=preg_replace('/#Module/',$this->ModuleName,$this->UploadPath);

        if ($path=="") { return; }

        $path=$this->FilterObject($path);

        $comps=preg_split('/\/+/',$path);
        if (!preg_grep('/'.$this->ModuleName.'/',$comps))
        {
            array_push($comps,$this->ModuleName);
        }


        $path="";
        for ($n=0;$n<count($comps);$n++)
        {
            if ($path!="")
            {
                $path.="/";
            }

            $path.=$comps[$n];

            if (!is_dir($path))
            {
                var_dump($path);
                mkdir($path);
            }
            
        }

        touch($path."/index.php");
        return $path;
    }

    //*
    //* Returns full (relative) name of uploaded file pertaining to $data.
    //*

    function GetUploadedFileName($data,$item,$ext)
    {
        $uploadpath=$this->GetUploadPath();

        //Make sure we have an index.php, so no-one may list the files
        $index=$uploadpath."/index.php";
        if (!file_exists($index))
        {
            if (is_writable($index))
            {

                $this->MyWriteFile($index,array());
            }
        }

        $uploadpath.="/";
        if ($this->UploadFilesHidden)
        {
            $uploadpath.=".";
        }

        //Make files hidden
        return $uploadpath.$data."_".$item[ "ID" ].".".$ext;
    }

    //*
    //* function TreatDataAsLatex, Parameter list: $value
    //*
    //* Processes 
    //*

    function TreatDataAsLatex($value)
    {
        return $value;
    }

    //*
    //* function TreatDataAsNonLatex, Parameter list: $value
    //*
    //* Processes 
    //*

    function TreatDataAsNonLatex($value)
    {
        $value=$this->Html2Text($value);

        $value=preg_replace('/&#92;/','\\\\',$value);

        $show=FALSE;
        if (preg_match('/\\\\/',$value)) { $show=TRUE;}

        $value=preg_replace('/%/','\%',$value);
        $value=preg_replace('/#/','\#',$value);
        //Added \ on regexp, 18082013
        $value=preg_replace('/\$/','\$',$value);
        $value=preg_replace('/&/','\&',$value);
        $value=preg_replace('/_/','\_',$value);

        //Escapes also the brackets in \command{...}
        //$value=preg_replace('/\s+{/',' \{',$value);
        //$value=preg_replace('/}\s+/','\} ',$value);

        $value=preg_replace('/~/','$\sim$',$value);
        $value=preg_replace('/\^/','$\wedge$',$value);
        $value=preg_replace('/&quot;/','"',$value);

        //????$value=preg_replace('/&#39;/','\'',$value);
        //if ($show) { var_dump($value); exit();}

        return $value;
    }

    //*
    //* function PreProcessFieldsForLatex, Parameter list: &$item,$data
    //*
    //* Preprocesses data fields for latex specific stuff.
    //*

    function PreProcessFieldForLatex(&$item,$data)
    {
        if (
            !empty($this->ItemData[ $data."_LaTeX" ])
            &&
            $item[ $data."_LaTeX" ]==1
           )
        {
            $item[ $data ]=$this->TreatDataAsLatex($item[ $data ]);
        }
        else
        {
            $item[ $data ]=$this->TreatDataAsNonLatex($item[ $data ]);
        }
    }

    //*
    //* function PreProcessFieldsForLatex, Parameter list: &$item
    //*
    //* Preprocesses data fields for latex specific stuff.
    //* Should be called before printing!
    //*

    function PreProcessFieldsForLatex(&$item)
    {
        foreach ($this->ItemData as $data => $value)
        {
            $this->PreProcessFieldForLatex($item,$data);
        }
    }


    //*
    //* function TrimHourData, Parameter list: $item,$data,$newvalue
    //*
    //* Trims hour/min strings. TriggerFunction style, that is may be used as a TriggerFunction.
    //*

    function TrimHourData($item,$data,$newvalue)
    {
        if (preg_match('/\d\d?/',$newvalue))
        {
            $newvalue=$this->TrimHourValue($newvalue);
        }

        $item[ $data ]=$newvalue;

        return $item;
    }

    //*
    //* function TrimDateData, Parameter list: $item,$data,$newvalue
    //*
    //* Trims date/mon/year strings. TriggerFunction style, that is may be used as a TriggerFunction.
    //*

    function TrimDateData($item,$data,$newvalue)
    {
        if (empty($newvalue) && !empty($item[ $data ]))
        {
            $newvalue=$item[ $data ];
        }

        if (preg_match('/\d\d?/',$newvalue))
        {
            $newvalue=$this->TrimDateValue($newvalue);
        }

        $item[ $data ]=$newvalue;

        return $item;
    }

    //*
    //* function TakeUndefinedKey, Parameter list: &$item,&$ukeys,$citem,$keys,$rkey=""
    //*
    //* Takes keys in $keys, set but empty in $item and sets them to correspondinbg key in $citem.
    //* $ukeys is incremented with keys set.
    //*

    function TakeUndefinedKey(&$item,&$ukeys,$citem,$key,$rkey="")
    {
        if (isset($item[ $key ]) && empty($item[ $key ]))
        {
            $data=$key;
            if (!empty($rkey)) { $data=$rkey; }

            $item[ $key ]=$citem[ $data ];
            array_push($ukeys,$key);
        }
    }

     //*
    //* function TakeUndefinedKeys, Parameter list: &$item,&$ukeys,$citem,$keys,$rkey=""
    //*
    //* Takes keys in $keys, set but empty in $item and sets them to correspondinbg key in $citem.
    //* $ukeys is incremented with keys set.
    //*

    function TakeUndefinedKeys(&$item,&$ukeys,$citem,$keys,$rkey="")
    {
        foreach ($keys as $key)
        {
            $this->TakeUndefinedKey($item,$ukeys,$citem,$key,$rkey);
        }
    }

    //*
    //* function HtmlItemTable, Parameter list: 
    //*
    //* Creates a full blown HtmlItemTable.
    //*

    function HtmlItemTable($datas,$item=array(),$table=array(),$wantarray=FALSE)
    {
        if (empty($item)) { $item=$this->ItemHash; }

        $table=$this->ItemTable
        (
           0,
           $item,
           FALSE,
           $datas,
           $table,
           FALSE,
           FALSE //don't include title
        );

        if (!$wantarray)
        {
            $table=$this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center'),
               array(),
               array(),
               FALSE,
               FALSE
            );
        }
        return $table;
    }


    //*
    //* function TakeUndefinedListOfKeys, Parameter list: $item,$citem,$list,$updatemysql=FALSE
    //*
    //* Takes undefined 
    //*

    function TakeUndefinedListOfKeys(&$item,$citem,$list,$updatemysql=FALSE)
    {
        $udatas=array();
        foreach ($list as $def)
        {
            if (is_array($def))
            {
                $this->TakeUndefinedKeys
                (
                   $item,
                   $udatas,
                   $citem,
                   $def[ "Keys" ],
                   $def[ "Key" ]               
                );
            }
            else
            {
                $this->TakeUndefinedKey
                (
                   $item,
                   $udatas,
                   $citem,
                   $def,
                   $def               
                );
            }
        }

        if ($updatemysql && count($udatas)>0)
        {
            $this->MySqlSetItemValues("",$udatas,$item);
        }

        return $udatas;
    }
}
?>