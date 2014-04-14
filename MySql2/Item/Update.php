<?php


class ItemUpdate extends ItemTests
{

    //*
    //* Updates File field (ie moves file)
    //*

    function UpdateFileField($data,$update,$item,$rdata="")
    {
        if (empty($rdata)) { $rdata=$data; }

        $uploadpath=$this->GetUploadPath();
        $extensions=$this->ItemData[ $data ][ "Extensions" ];

        if (!empty($_FILES[ $rdata ]) && !empty($_FILES[ $rdata ][ 'tmp_name' ]))
        {
            $uploadinfo=$_FILES[ $rdata ];
            $tmpname=$uploadinfo['tmp_name'];
            $name=$uploadinfo['name'];
            $error=$uploadinfo['error'];

            $comps=preg_split('/\./',$name);
            $ext=$comps[ count($comps)-1 ];

            $comps=preg_split('/\//',$name);
            $rname=$comps[ count($comps)-1 ];
            $rdata=$this->GetDataTitle($data);
            if (preg_grep('/^'.$ext.'$/i',$extensions))
            {
                $destfile=$this->GetUploadedFileName($data,$item,$ext);
                $res=move_uploaded_file($tmpname,$destfile);

                $this->MySqlSetItemValue($this->SqlTableName(),"ID",$item[ "ID" ],$data,$destfile);

                $item[ $data ]=$destfile;
                $update++;
                

                $msgtext=$this->GetMessage($this->ItemDataMessages,"FileUploaded");
                $msgtext=preg_replace('/#Extensions/',join(",",$extensions),$msgtext);
                $msgtext=preg_replace('/#Ext/',$ext,$msgtext);
                $msgtext=preg_replace('/#Name/',$rname,$msgtext);
                $msgtext=preg_replace('/#Data/',$rdata,$msgtext);
                $this->HtmlStatus=$msgtext."<BR><BR>";

                print $this->H(3,$msgtext);
                $item[ "__Res__" ]=TRUE;
                return $item;
            }
            elseif ($name!='')
            {
                $msgtext=$this->GetMessage($this->ItemDataMessages,"InvalidExtension");
                $msgtext=preg_replace('/#Extensions/',join(",",$extensions),$msgtext);
                $msgtext=preg_replace('/#Ext/',$ext,$msgtext);
                $msgtext=preg_replace('/#Name/',$rname,$msgtext);
                $msgtext=preg_replace('/#Data/',$rdata,$msgtext);
                $item[ $data."_Message" ]=$msgtext;

                $msgtext=$this->GetMessage($this->ItemDataMessages,"InvalidExtensionStatus");
                $msgtext=preg_replace('/#Extensions/',join(",",$extensions),$msgtext);
                $msgtext=preg_replace('/#Ext/',$ext,$msgtext);
                $msgtext=preg_replace('/#Name/',$rname,$msgtext);
                $msgtext=preg_replace('/#Data/',$rdata,$msgtext);
                 $this->HtmlStatus=$msgtext."<BR><BR>";
                 $item[ "__Res__" ]=FALSE;

                 print $this->H(4,$msgtext);
           }
        }

        return FALSE;
    }


    //*
    //* Returns name of Trigger function, if any for $data
    //*

    function TriggerFunction($data)
    {
        if (!empty($this->ItemData[ $data ][ "TriggerFunction" ]))
        {
            return $this->ItemData[ $data ][ "TriggerFunction" ];
        }
        elseif (isset($this->TriggerFunctions[ $data ]))
        {
            return $this->TriggerFunctions[ $data ];
        }


        return FALSE;
    }

    //*
    //* Applies trigger function, for data $data item in DB.
    //*

    function ApplyTriggerFunction($data,$item,$prepostkey="",$plural=FALSE)
    {
        $method=$this->TriggerFunction($data);
        if ($method)
        {
            if (method_exists($this,$method))
            {
                $rdata=$data;
                if (!empty($this->ItemData[ $data ][ "CGIName" ]) && !$plural)
                {
                    $rdata=$this->ItemData[ $data ][ "CGIName" ];
                }

                $newvalue=$this->GetPOST($prepostkey.$rdata);
                $ritem=$this->$method($item,$data,$newvalue);

                if (is_array($ritem) && count($ritem)>0)
                {
                    $item=$ritem;
                }
            }
            else
            {
                die("Warning: ($data) TriggerFunction: $method undefined");
            }
        }
        return $item;
    }


    //*
    //* Updates item in DB.
    //*

    function UpdateItem($item=array(),$datas=array(),$prepost="")
    {
        if (count($item)==0) { $item=$this->ItemHash; }
        if (isset($this->PostProcessed[ $item[ "ID" ] ]))
        {
            unset($this->PostProcessed[ $item[ "ID" ] ]);
        }

        $olditem=$item;

        if (count($datas)==0) { $datas=array_keys($this->ItemData); }

        $rupdate=0;
        $update=0;
        $rdatas=array(); //datas that are actually updated
        foreach ($datas as $id => $data)
        {
            $access=$this->GetDataAccessType($data,$item);
            if ($access<2)
            {
                 //Not allowed to edit - ignore
                continue;
            }
            elseif (preg_match('/^FILE$/',$this->ItemData[ $data ][ "Sql" ]))
            {
                $res=$this->UpdateFileField($data,$rupdate,$item);
                if (is_array($res) && $res[ "__Res__" ])
                {
                    $item=$res;
                    $rupdate++;
                    array_push($rdatas,$data);

                    if ($this->TriggerFunction($data))
                    { 
                        $item=$this->ApplyTriggerFunction($data,$item,$prepost);
                    }
                    $update++;
                }
            }
            elseif ($this->ItemData[ $data ][ "Derived" ]=="" && 
                    $this->ItemData[ $data ][ "TimeType" ]=="")
            {
                $newvalue=$this->TestUpdateItem($data,$item,FALSE,$prepost);
 
                if (!isset($item[ $data ]) || $newvalue!=$item[ $data ])
                {
                    if ($this->TriggerFunction($data))
                    { 
                        $item=$this->ApplyTriggerFunction($data,$item,$prepost);
                    }
                    else
                    {
                        $item[ $data ]=$newvalue;
                    }

                    $update++;
                    array_push($rdatas,$data);
                }
            }
        }

        $this->FormWasUpdated=FALSE;
        if ($update>0)
        {
            $this->LogMessage
            (
               "UpdateItem",
               $item[ "ID" ].": ".
               $this->GetItemName($item)
            );

            $this->MySqlUpdateItem
            (
                $this->SqlTableName(),
                $item,
                "ID='".$item[ "ID" ]."'",
                $rdatas
            );

            $item=$this->ReadItemDerivedData($item);
            $item=$this->SetItemTime("MTime",$item);
            $item=$this->SetItemTime("ATime",$item);

            $rdatanames=array();
            foreach ($rdatas as $rdata)
            {
                array_push($rdatanames,$this->GetDataTitle($rdata));
            }

            $this->AddHtmlStatusMessage
            (
               $this->B("Alterado: ").
               $this->HtmlList($rdatanames)
            );

            $this->AddHtmlStatusMessage
            (
               $this->GetMessage
               (
                  $this->ItemDataMessages,
                  "DataChanged"
               )
            );

            $this->FormWasUpdated=TRUE;
            if (method_exists($this,"PostUpdateActions"))
            {
                $this->PostUpdateActions($olditem,$item);
            }
        }
        else
        {
            $this->AddHtmlStatusMessage
            (
               $this->GetMessage
               (
                  $this->ItemDataMessages,
                  "DataUnchanged"
               )
            );
        }

        return $this->PostProcessItem($item);
    }
}
?>