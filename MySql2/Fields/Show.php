<?php

class ShowFields extends InputFields
{

    //*
    //* Generates non-edit input value, showing fieled value as text. 
    //*

    function MakeShowField($data,$item,$plural=FALSE,$iconify=TRUE)
    {
        $value="";
        if (isset($item[ $data ])) { $value=$item[ $data ]; }

        $access=$this->GetDataAccessType($data,$item);
        if ($access<1)
        {
            return "forbidden";
        }

        $fieldmethod=$this->ItemData[ $data ][ "FieldMethod" ];
        if ($fieldmethod!="" && method_exists($this,$fieldmethod))
        {
            return $this->$fieldmethod($data,$item,0);
        }
        elseif ($this->ItemData[ $data ][ "Type" ]=="TEXT")
        {
            return "";
        }
        elseif ($this->ItemData[ $data ][ "Sql" ]=="TEXT")
        {
            $size=$this->ItemData[ $data ][ "Size" ];
            $size=preg_split('/\s*x\s*/',$size);

            $width=50;
            if ($size[0]) { $width=$size[0]; }
            $value=preg_replace("/(\s*\n\s*)+/","<BR>\n",$value);

            $values=preg_split('/\s+/',$value);
            $rvalues=array();

            $rvalue="";
            foreach ($values as $svalue)
            {
                if (strlen($rvalue.$svalue)<$width)
                {
                    $rvalue.=" ".$svalue;
                }
                else
                {
                    array_push($rvalues,$rvalue);
                    $rvalue=$svalue;
                }
            }

            if (preg_match('/\S/',$rvalue)) { array_push($rvalues,$rvalue); }

            $value=join("<BR>\n",$rvalues);

        }
        elseif (preg_match('/^FILE$/',$this->ItemData[ $data ][ "Sql" ]))
        {
            $value="";
            if (isset($item[ $data ])) { $value=$item[ $data ]; }

            $rvalue="";
            if ($value!="")
            {
                $rvalue=$this->FileFieldDecorator($data,$item,$plural,0);
            }

            $value=$rvalue;
        }
        elseif ($this->ItemData[ $data ][ "TimeType" ])
        {
            $value="-";
            if (!empty($item[ $data ]))
            {
                $value=$this->TimeStamp2Text($item[ $data ]);
            }
        }
        elseif ($iconify && $this->ItemData[ $data ][ "Iconify" ])
        {
            if ($this->ItemData[ $data ][ "Iconify" ]==2)
            {
                $value=$item[ $data ];
                if (!empty($this->ItemData[ $data ][ "IconifyText" ]))
                {
                    if (!empty($item[ $data ]))
                    {
                        $value=$this->Filter($this->ItemData[ $data ][ "IconifyText" ],$item);
                    }
                }

                    $value="<A HREF='".$item[ $data ]."'>".$value."</A>";
            }
            elseif ($this->ItemData[ $data ][ "Iconify" ])
            {
                $file=$item[ "ID" ]."_".$data.".png";
                $value=$this->IconText
                (
                   $file,
                   $item[ $data ],
                   $this->ItemData[ $data ][ "IconColors" ],
                   $this->ItemData[ $data ][ "BkIconColors" ]
                );
            }
            else
            {
                $file=$this->ItemData[ $data ][ "Iconify" ];
                $extrapath_pathcorrection=$this->ExtraPathPathCorrection();
                if ($extrapath_pathcorrection!="")
                {
                    $file=$extrapath_pathcorrection."/".$file;
                }

                $value="<IMG SRC='".$file."' BORDER='0' ALT='img'";
                if ($this->ItemData[ $data ][ "Width" ]!="")
                {
                    $value.=" WIDTH='".$this->ItemData[ $data ][ "Width" ]."'";
                }
                if ($this->ItemData[ $data ][ "Height" ]!="")
                {
                    $value.=" HEIGHT='".$this->ItemData[ $data ][ "Height" ]."'";
                }

                $value.=">";
                $value="<A HREF='".$item[ $data ]."'>".$value."</A>";
            }
        }
        elseif (
                isset($this->ItemData[ $data ][ "Filter" ])
                ||
                isset($this->ItemData[ $data ][ $this->Profile."Filter" ])
               )
        {
            $value="";
            if (isset($this->ItemData[ $data ][ $this->Profile."Filter" ]))
            {
                $value=$this->ItemData[ $data ][ $this->Profile."Filter" ];
            }
            elseif (isset($this->ItemData[ $data ][ "Filter" ]))
            {
                $value=$this->ItemData[ $data ][ "Filter" ];
            }

            if ($value!="" && method_exists($this,$value))
            {
                $value=$this->$value($data,$item);
            }

            $value=$this->Filter($value,$item);
            $value=$this->FilterObject($value);
        }
        elseif (isset($this->ItemData[ $data ][ "SqlObject" ]))
        {
            $object=$this->ItemData[ $data ][ "SqlObject" ];
            $value=$this->CreateSubItemShowField($value,$data);
        }
        elseif ($this->ItemData[ $data ][ "Sql" ]=="ENUM")
        {
            $value=$this->GetEnumValue($data,$item);

            //Avoid print of 0's
            if ($value=="0")  { $value=""; }

            if (
                  !$this->LatexMode()
                  &&
                  isset($item[ $data ])
                  &&
                  $item[ $data ]>0
                  &&
                  !empty($this->ItemData[ $data ][ "ValueColors" ])
               )
            {
                $color=$this->ItemData[ $data ][ "ValueColors" ][ $item[ $data ]-1 ];
                $value=$this->TextColor($color,$value);
            }
        }
        elseif ($this->ItemData[ $data ][ "IsDate" ])
        {
            $value=$this->CreateDateShowField($data,$item,$value);
        }
        elseif ($this->ItemData[ $data ][ "IsHour" ])
        {
            $value=$this->CreateHourShowField($data,$item,$value);
        }
        else
        {
            if (isset($item[ $data ]) && $item[ $data ]) { return $item[ $data ]; }

            if (
                  preg_match('/^(\S+)_(.+)/',$data,$matches) &&
                  !empty($this->ItemData[ $matches[1] ][ "SqlObject" ])
                )
            {
                $basedata=$matches[1];

                $object=$this->ItemData[ $basedata ][ "SqlObject" ];
                $keys=preg_grep('/^'.$basedata.'_/',array_keys($item));

                $ritem=array();
                foreach ($keys as $kid => $key)
                {
                    $rkey=preg_replace('/^'.$basedata.'_/',"",$key);
                    $ritem[ $rkey ]=$item[ $key ];
                }

                $value=$this->$object->MakeShowField($matches[2],$ritem,$plural,$iconify);
            }
            else
            {
                $value=$this->GetEnumValue($data,$item);
                if (!$this->LatexMode() && !empty($this->ItemData[ $data ][ "ValueColors" ]))
                {
                    $color=$this->ItemData[ $data ][ "ValueColors" ][ $item[ $data ]-1 ];
                    $value=$this->TextColor($color,$value);
                }
            }
        }

        if (!empty($this->ItemData[ $data ][ "Format" ]))
        {
            $value=sprintf($this->ItemData[ $data ][ "Format" ],$value);
        }

        if (!$plural)
        {
            $value.=$this->FieldComment($data);
        }

        if (preg_match('/^0\s?$/',$value)) { $value=""; }

        return $value;
    }
}

?>