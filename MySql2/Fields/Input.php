<?php

include_once("Select.php");

class InputFields extends SelectFields
{
    //*
    //* Variables of Fields class:
    //*

    //*
    //* Creates input field based on data definition (type, size, etc.).
    //* Should ONLY be called by MakeDataField, who checks access
    //*

    function MakeInputField($data,$item,$value="",$index="",$plural=FALSE,$links=TRUE)
    {
        $sql=$this->ItemData[ $data ][ "Sql" ];

        $fieldmethod=$this->ItemData[ $data ][ "FieldMethod" ];
        if ($fieldmethod!="" && !method_exists($this,$fieldmethod))
        {
            print $this->ModuleName.": Invalid FieldMethod for data $data: $fieldmethod<BR>\n";
        }

        if ($this->ItemData[ $data ][ "Type" ]=="TEXT")
        {
            return "";
        }
        elseif ($fieldmethod!="" && method_exists($this,$fieldmethod))
        {
            $value=$this->$fieldmethod($data,$item,1);
        }
        elseif ($this->ItemData[ $data ][ "Derived" ]!="")
        {
            $value=$item[ $data ];
        }
        elseif ($this->ItemData[ $data ][ "Sql" ]=="ENUM")
        {
            $value=$this->CreateDataSelectField($data,$item,$value);
        }
        elseif ($sql=="TEXT")
        {
            $size=$this->ItemData[ $data ][ "Size" ];
            if ($plural && $this->ItemData[ $data ][ "TableSize" ]!="")
            {
                $size=$this->ItemData[ $data ][ "TableSize" ];
            }
            $size=preg_split('/\s*x\s*/',$size);

            $width=50;
            if (count($size)>0) { $width=$size[0]; }
            $height=5;
            if (count($size)>1) { $height=$size[1]; }

            $value=preg_replace('/^\s+/',"",$value);
            $value=preg_replace('/\s+$/',"",$value);

            if ($height>1)
            {
                $value=$this->MakeTextArea($data,$height,$width,$value);
            }
            else
            {
                $value=$this->MakeInput($data,$value,$width);
            }
        }
        elseif ($this->ItemData[ $data ][ "SqlObject" ])
        {
            $object=$this->ItemData[ $data ][ "SqlObject" ];
            $value=$this->CreateDataSelectField($data,$item,$value);


            if (
                !$this->LatexMode()
                &&
                isset($item[ $data ])
                &&
                $item[ $data ]>0
                &&
                !empty($this->ItemData[ $data ][ "EditLinkAction" ])
               )
            {
                $action=$this->Filter($this->ItemData[ $data ][ "EditLinkAction" ][ "Url" ],$item);
                $url=$this->Query2Hash($action);
                $url[ "ModuleName" ]=$this->$object->ModuleName;
                $url[ "ID" ]=$item[ $data ];

                $value.="<BR>".$this->SystemLink
                (
                   $url,
                   $this->Filter($this->ItemData[ $data ][ "EditLinkAction" ][ "Text" ],$item),
                   $this->Filter($this->ItemData[ $data ][ "EditLinkAction" ][ "Title" ],$item),
                   "_".$this->$object->ModuleName
                );
            }
        }
        elseif (preg_match('/^FILE$/',$this->ItemData[ $data ][ "Sql" ]))
        {
            $value=
                $this->MakeFileField
                (
                   $data,
                   array
                   (
                    "SIZE" => $this->ItemData[ $data ][ "Size" ],
                    "Title" => "Permitido: ".join(", ",$this->ItemData[ $data ][ "Extensions" ])
                   )
                ).
                $this->FileFieldDecorator($data,$item,$plural,1);
        }
        elseif ($this->ItemData[ $data ][ "Password" ])
        {
            $size=8;
            if ($this->ItemData[ $data ][ "Size" ]) { $size=$this->ItemData[ $data ][ "Size" ]; }
            $value=$this->MakePassword($data,$value,$size);
        }
        elseif ($this->ItemData[ $data ][ "IsDate" ])
        {
            $value=$this->CreateDateField($data,$item,$value);
        }
        elseif ($this->ItemData[ $data ][ "IsHour" ])
        {
            $value=$this->CreateHourSelectFields($data,$item,$value);
        }
        else
        {
            $size=25;
            if ($this->ItemData[ $data ][ "Size" ]) { $size=$this->ItemData[ $data ][ "Size" ]; }
            if ($plural && $this->ItemData[ $data ][ "TableSize" ]!="")
            {
                $size=$this->ItemData[ $data ][ "TableSize" ];
            }

            $value=$this->MakeInput($data,$value,$size);
        }



        if ($index!="" && $this->GetCGIVarValue($this->TabMovesDownKey)==1)
        {
            $value=preg_replace('/>/'," TABINDEX='".$index."'>",$value,1);//max 1
        }

        if (!$plural)
        {
            $value.=$this->FieldComment($data);
        }

        if (!empty($this->ItemData[ $data ][ "CGIName" ]) && !$plural)
        {
            $regex="\sNAME='$data";
            if (preg_match('/'.$regex.'/',$value))
            {
                $value=preg_replace('/'.$regex.'/'," NAME='".$this->ItemData[ $data ][ "CGIName" ],$value);
            }
        }

        return $value;
    }


}

?>