<?php


class ItemTests extends ItemBackRefs
{
    //*
    //* Variables of Item class:
    //*

    var $ID,$ItemHash;
    var $PersonIDRow,$CoordIDRow;
    var $AddDefaults=array();
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


   //*
    //* function TestItem, Parameter list: $item
    //*
    //* Tests item data according to prescribed data definitions.
    //* Messages are stored in $data."_Message" keys of item.
    //* Modified item is returned.
    //*


    function TestItem($item=array())
    {
        if (count($item)==0) { $item=$this->ItemHash; }

        $item=$this->ReadItemDerivedData($item);

        $messages=array();
        $nerrors=0;

        if (!$item) { $item=array(); }

        foreach ($item as $data => $value)
        {
            if (!isset($this->ItemData[ $data ]))
            {
                continue;
            }

            unset($item[ $data."_Message" ]);
            if ($item[ $data ]!="" && isset($this->ItemData[ $data ][ "Regexp" ]))
            {
                if (!preg_match('/'.$this->ItemData[ $data ][ "Regexp" ].'/',$item[ $data ]))
                {
                    if (isset($this->ItemData[ $data ][ "RegexpText" ]))
                    {
                        $item[ $data."_Message" ]=$this->GetRealNameKey($this->ItemData[ $data ],"RegexpText");
                    }
                    else
                    {
                        $item[ $data."_Message" ]=
                            "Não Conforme à Regexp: ".
                            $this->ItemData[ $data ][ "Regexp" ];
                    }

                    array_push
                    (
                       $messages,
                       $this->GetDataTitle($data)." '".$item[ $data ]."': ".$item[ $data."_Message" ]
                    );
                    $nerrors++;
                }
            }

            if ($this->ItemData[ $data ][ "Compulsory" ])
            {
                $value=$item[ $data ];
                if (
                      (preg_match('/^ENUM/',$this->ItemData[ $data ][ "Sql" ]) && $value=="0")
                      ||
                      (isset($this->ItemData[ $data ][ "SqlClass" ]) && $value=="0")
                      ||
                      $value==""
                   )
                {
                    $vmarker=$this->GetMessage($this->ItemDataMessages,"CompulsoryFieldTag");
                    if ($this->ItemData[ $data ][ "CompulsoryText" ])
                    {
                        $vmarker=$this->ItemData[ $data ][ "CompulsoryText" ];
                    }

                    $item[ $data."_Message" ]="<SPAN CLASS='errors'>&lt;&lt; ".$vmarker."</SPAN>";
                    array_push
                    (
                       $messages,
                       $this->GetDataTitle($data)." '".$item[ $data ]."': ".$item[ $data."_Message" ]
                    );
                    $nerrors++;
                }
            }

            if ($this->ItemData[ $data ][ "Unique" ])
            {
                if (!$this->ItemDataIsUnique($item,$data))
                {
                    $item[ $data."_Message" ]="<SPAN CLASS='errors'>&lt;&lt; Não Único(a)</SPAN>";
                    array_push
                    (
                       $messages,
                       $this->GetDataTitle($data)." '".$item[ $data ]."': ".$item[ $data."_Message" ]
                    );
                    $nerrors++;
                }
            }

            if (!isset($item[ $data."_Message" ]) || $item[ $data."_Message" ]=="")
            {
                unset($item[ $data."_Message" ]);
            }
        }

        if ($this->TestMethod)
        {
            $method=$this->TestMethod;
            if (method_exists($this,$method))
            {
                $item=$this->$method($item);
            }
            else
            {
                $this->AddMsg("TestMethod '".$method."' undefined");
            }
        }

        unset($item[ "__Error_Messages__" ]);
        unset($item[ "__Errors__" ]);
        if ($nerrors>0)
        {
            $item[ "__Error_Messages__" ]=$messages;
            $item[ "__Errors__" ]=$nerrors;
        }

        $this->ItemHash=$item;

        return $item;
    }



    //*
    //* Tests whether $item[ $data ] is unique.
    //*

    function ItemDataIsUnique($item,$data)
    {
        if (!empty($item[ $data ]))
        {
            $nitems=$this->MySqlNEntries("",array($data => $item[ $data ]));
            if ($nitems>1)
            {
                if (!array($this->HtmlStatus))
                {
                    $this->HtmlStatus=array($this->HtmlStatus);
                }

                $msg=
                    $this->ItemName.": Campo ".$this->ItemData[ $data ][ "Name" ].
                    " não é único: ".$item[ $data ];

                array_push($this->HtmlStatus,$msg);

                //print $this->H(4,$msg);

                return FALSE; 
            }
        }

        return TRUE;
    }


   //*
   //* Tests if data declared uniques ("Unique" => 1) is really unique.
   //* First detects the list of data that needs to be unique.
   //* Then queiries the DB if any of these values in $item
   //* are already present.
   //* Returns the TRUE, if everything OK, FALSE it nonunique.
   //*

    function ItemIsUnique($item)
    {
        foreach ($this->ItemData as $data => $value)
        {
            if (empty($this->ItemData[ $data ][ "Unique" ])) { continue; }
            if (empty($item[ $data ])) { continue; }

            $nitems=$this->MySqlNEntries("",array($data => $item[ $data ]));
            if ($nitems>1)
            {
                if (!array($this->HtmlStatus))
                {
                    $this->HtmlStatus=array($this->HtmlStatus);
                }

                array_push
                (
                   $this->HtmlStatus,
                   "Campo ".$this->ItemData[ $data ][ "Name" ].
                   " não é único - ".$this->ItemName
                );

                print $this->H
                (
                   4,
                   "Campo ".$this->ItemData[ $data ][ "Name" ].
                   " não é único - ".$this->ItemName
                );

                return FALSE; // return right away, minimizing mysql talks
            }
        }

        return TRUE;
    }

 
    //*
    //* Tests item mtime, in rel a form mtime.
    //*

    function TestItemMTime($item,$formmtime=0)
    {
        return TRUE;
        if ($formmtime==0)
        {
            $formmtime=$this->GetPOST("__MTime__");
        }

        $itemmtime=$item[ "MTime" ];
        if ($formmtime<$itemmtime)
        {
            //If any MTime change, we should'nt update
            $this->LogMessage
            (
               "UpdateItem",
               "Outdated attempt: ".$item[ "ID" ].": ".$this->GetItemName($item)
            );

            $msg=
                "Mudou depois que este formulário carregou,<BR>".
                "atualização insegura omitido<BR>Recarregar cliqando aqui: ".
                $this->ActionEntry("Edit",$this->ItemHash,1);

            $this->AddMsg($this->GetItemName($item).": ".$msg);
            $this->HtmlStatus.=$msg."<BR>";

            $item[ "MTime" ]=$formmtime;

            return FALSE;
        }

        return TRUE;
    }

    //*
    //* Treats $newvalue, backspaces and stuff.
    //*

    function TreatNewValue($newvalue)
    {
        //replace's
        $newvalue=preg_replace("/'/","&#39;",$newvalue);

        //backslashes
        $newvalue=preg_replace('/\\\\/',"&#92;",$newvalue);
        //$newvalue=preg_replace("/&#92;&#92;/","&#92;",$newvalue);
        $newvalue=preg_replace('/\s+$/',"",$newvalue);

        return $newvalue;
    }


     //*
    //* Tests if item should be updated.
    //*

    function TestUpdateItem($data,&$item,$plural=FALSE,$prepost="")
    {
        $oldvalue="";
        if (isset($item[ $data ])) { $oldvalue=$item[ $data ]; }

        if (!empty($this->ItemData[ $data ][ "TimeType" ])) { return $oldvalue; }

        $access=$this->GetDataAccessType($data,$item);
        if ($access<2)
        {
            return $oldvalue;
        }

        $cginame=$data;
        if (!empty($this->ItemData[ $data ][ "CGIName" ]) && !$plural)
        {
            $cginame=$this->ItemData[ $data ][ "CGIName" ];
        }

        $rdata=$cginame;
        if ($plural) { $rdata=$item[ "ID" ]."_".$rdata; }
        elseif ($prepost) { $rdata=$prepost.$rdata; }

        $newvalue="";
        if ($this->ItemData[ $data ][ "IsDate" ])
        {
            $newvalue=$this->GetPOST($rdata);
        }
        elseif ($this->ItemData[ $data ][ "IsHour" ])
        {
            $newvalue=
                sprintf("%02d",$this->GetPOST($rdata."Hour")).
                sprintf("%02d",$this->GetPOST($rdata."Min"));
        }
        else
        {
            if (!isset($_POST[ $rdata ]))
            {
                return $oldvalue;
            }

            $newvalue=$this->GetPOST($rdata);
        }


        if (
              preg_match('/^(Add|Copy)$/',$this->Action)
              &&
              $this->AddDefaults[ $data ]!=""
           )
        {
            $newvalue=$this->AddDefaults[ $data ];
        }

        if (isset($this->ItemData[ $data ][ "Regexp" ]))
        {
            if ($newvalue!="" && !preg_match('/'.$this->ItemData[ $data ][ "Regexp" ].'/',$newvalue))
            {
                $item[ $data."_Message" ]=
                    $this->GetItemName($item)." ".
                    "'".$newvalue."': ".
                    $this->GetMessage($this->ItemDataMessages,"DataInvalid");

                if (isset($this->ItemData[ $data ][ "RegexpText" ]))
                {
                    $item[ $data."_Message" ].="<BR>".$this->ItemData[ $data ][ "RegexpText" ];
                }
                else
                {
                    $item[ $data."_Message" ].="<BR>".$this->ItemData[ $data ][ "Regexp" ];
                }


                if (is_array($this->HtmlStatus))
                {
                    array_push($this->HtmlStatus,$item[ $data."_Message" ]);
                }
                else
                {
                    $this->HtmlStatus.=$item[ $data."_Message" ]."<BR><BR>";
                }

                $newvalue=$item[ $data ];
            }
        }

        $newvalue=$this->TreatNewValue($newvalue);

        //Allow emptying, if not compulsory.
        if (empty($newvalue))
        {
            if ($this->ItemData[ $data ][ "Compulsory" ] && empty($oldvalue))
            {
                $this->AddHtmlStatusMessage($newvalue." undef e ".$data." obrigatorio - ignorado!");
                $newvalue=$oldvalue;
            }
        }


        if ($oldvalue!=$newvalue)
        {
            if ($this->ItemData[ $data ][ "MD5" ] && $newvalue!="")
            {
                $newvalue=md5($newvalue);
            }

            return $newvalue;
        }

        return $oldvalue;
    }

    //*
    //* function TestPRN, Parameter list: $item
    //*
    //* Verifies brazilian PRN, rejects if invalid.
    //*

    function TestPRN($prn)
    {
        if (preg_match('/^7294913919[1-5]/',$prn)) { return TRUE; }

        $body = substr($prn,0,9);
        $dv = substr($prn,9,2);
        $d1 = 0;
        for ($i = 0; $i < 9; $i++)
        {
            $d1 += intval( substr ($body, $i, 1)) * (10 - $i);
        }

        $res=TRUE;
        if ($d1 == 0)
        {
            $res=FALSE;
        }

        $d1 = 11 - ($d1 % 11);
        if ($d1 > 9)
        {
            $d1 = 0;
        }
        if (substr ($dv, 0, 1) != $d1)
        {
            $res=FALSE;
        }

        $d1 *= 2;
        for ($i = 0; $i < 9; $i++)
        {
            $d1 += intval(substr($body, $i, 1)) * (11 - $i);
        }
        $d1 = 11 - ($d1 % 11);
        if ($d1 > 9)
        {
            $d1 = 0;
        }
        if (substr ($dv, 1, 1) != $d1)
        {
            $res=FALSE;
        }

        if (!$res)
        {
            $this->AddHtmlStatusMessage("CPF ".$prn." Inválido!");
            print $this->H(5,"CPF ".$prn." Inválido!");
        }

        return $res;
    }
}
?>