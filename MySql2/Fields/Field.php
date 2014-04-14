<?php

class FieldFields extends ShowFields
{
    //*
    //* Variables of Fields class:
    //*

    //*
    //* Returns comment to add to field
    //*

    function FieldComment($data)
    {
        if (
            !$this->NoFieldComments
            &&
            !isset($this->ItemData[ $data ][ "NoComment" ])
           )
        {
            $comment=$this->GetRealNameKey($this->ItemData[ $data ],"Comment");
            if ($comment!="")
            {
                return $comment;
            }
        }

        return "";
    }


    //*
    //* Creates input field based on data definition (type, size, etc.).
    //*

    function MakeDataValue($data,$item,$value="")
    {
        if ($this->ItemData[ $data ][ "Sql" ]=="TEXT")
        {
            $value=preg_replace("/\n/","<BR>\n",$value);
            $value=preg_replace("/ /","&nbsp;",$value);
        }
        elseif ($this->ItemData[ $data ][ "Sql" ]=="ENUM")
        {
            $value=$this->GetEnumValue($data,$item);
        }
        elseif ($this->ItemData[ $data ][ "SqlDerivedData" ])
        {
            $value=$item[ $data."_Name" ];
        }
        elseif ($this->ItemData[ $data ][ "Link" ])
        {
            $link=$this->ItemData[ $data ][ "LinkRef" ];
            $name=$this->ItemData[ $data ][ "LinkName" ];
            $link=$this->FilterHash($link,$item);
            $value="<A HREF='".$link."'>".$name."</A>";
        }

        //Defalut here?

        return $value;
    }

    


    function MakeDataField($data,$item,$value="",$index="",$plural=FALSE)
    {
        $access=$this->GetDataAccessType($data,$item);

        $sql=$this->ItemData[ $data ][ "Sql" ];
        if ($value=="" && isset($item[ $data ])) { $value=$item[ $data ]; }

        if ($this->ItemData[ $data ][ "Type" ]=="TEXT")
        {
            return "";
        }
        elseif ($this->Action=="Add") { $access=2; }

        if ($access==2 && isset($this->ItemData[ $data ]))
        {
            return $this->MakeInputField($data,$item,$value,$index,$plural);
        }
        elseif ($access>0)
        {
            return $this->MakeShowField($data,$item,$plural);
        }
        else
        {
            return "Forbidden";
        }
    }

    //*
    //* Generates non-edit input value, showing field value as text. 
    //*

    function MakeField($edit,$item,$data,$plural=FALSE,$index="")
    {
        if (empty($this->ItemData[ $data ]) && !empty($item[ $data ]))
        {
            return "*".$item[ $data ];
        }
        elseif (isset($this->Actions[ $data ]) && !isset($this->ItemData[ $data ]))
        {
            $action=$this->ActionEntry($data,$item);
            $value=$action;
        }
        elseif (isset($this->ItemData[ $data ][ "Type" ]) && $this->ItemData[ $data ][ "Type" ]=="TEXT")
        {
            return "";
        }
        elseif ($data=="ID" && isset($item[ "_RID_" ]))
        {
            $value=$item[ "_RID_" ];
        }
        elseif (isset($this->ItemData[ $data ][ "TimeType" ]) && $this->ItemData[ $data ][ "TimeType" ])
        {
            $value="-";
            if (isset($item[ $data ]))
            {
                $value=$this->TimeStamp2Text($item[ $data ]);
            }
        }
        elseif ($data!="ID" && $edit==1) //count($grep)>0)
        {
            $value=$this->MakeDataField($data,$item,"",$index,$plural);
            if ($plural && isset($item[ "ID" ]))
            {
                $nmax=1;//only the first
                if ($this->ItemData[ $data ][ "IsDate" ])
                {
                    $nmax=4;
                }
                elseif ($this->ItemData[ $data ][ "IsHour" ])
                {
                    $nmax=3;
                }

 
                $value=$this->PrependInputNameTag($value,$item[ "ID" ]."_",$nmax);
            }
        }
        else
        { 
            $value=$this->MakeShowField($data,$item,$plural);
        }

        if ($edit==0)
        {
            if ($this->LatexMode) {}
            elseif (empty($this->ItemData[ $data ])) {}
            elseif ($this->ItemData[ $data ][ "HRef" ])
            {
                $value=$this->Href
                (
                   $this->FilterHash($this->ItemData[ $data ][ "HRef" ],$item),
                   $value
                );
            }
            elseif ($this->ItemData[ $data ][ "HRefIt" ] && $value!="")
            {
                if (!preg_match('/^http/',$value)) { $value="http://".$value; }

                $text=$value;
                if ($this->ItemData[ $data ][ "HRefIcon" ])
                {
                    $text=$this->Img($this->ItemData[ $data ][ "HRefIcon" ]);
                }
                $value="<A HREF='".$value."' TARGET='_blank'>".$text."</A>";
            }
            elseif ($this->ItemData[ $data ][ "Iconed" ])
            {
                $value=$this->FilterHash($this->ItemData[ $data ][ "Iconed" ],$item);
            }

            if (!empty($this->ItemData[ $data ][ "Format" ]))
            {
                $value=sprintf($this->ItemData[ $data ][ "Format" ],$value);
            }
        }

        $value=$this->ApplicationObj->FilterObject($value);
        return $this->Span($value,array("CLASS" => 'data'));
    }


    //*
    //* function PrependInputNameTag, Parameter list: $inputhtml,$prepend,$n=1
    //*
    //* Prepends $prepend to first occorrence of Name='...' in $inputhtml.
    //*

    function PrependInputNameTag($inputhtml,$prepend,$nmax=1)
    {
       $inputhtml=preg_replace  //Prepend $prepend to input Name=
        (
           '/NAME="/i',
           "NAME=\"".$prepend,
           $inputhtml,
           $nmax
        );

        return preg_replace  //Prepend $prepend to input Name=
        (
           '/NAME=\'/i',
           "NAME='".$prepend,
           $inputhtml,
           $nmax
        );
    }
}

?>