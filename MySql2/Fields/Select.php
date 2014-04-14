<?php

class SelectFields extends FileFields
{
    //*
    //* Variables of Fields class:
    //*


    //*
    //* Creates input field based on data definition (type, size, etc.).
    //* Should ONLY be called by MakeDataField, who checks access
    //*

    function CreateDataSelectField($data,$item,$value="",$ignoredefault=0,$checkbox=FALSE,$fieldtitle="")
    {
        if ($this->ItemData[ $data ][ "SqlClass" ]!="")
        {
            return $this->CreateSubItemSelectField($data,$item,$value,$ignoredefault);
        }

        if ($value=="" && isset($item[ $data ])) { $value=$item[ $data ]; }

        //Put select items in alphabetic order
        $sorteds=array();  //names
        $rvalues=array();  //numbers
        $values=array();
        if (
            is_array($this->ItemData[ $data ][ "ValuesMatrix" ]) &&
            $this->ItemData[ $data ][ "ValuesDependencyVar" ]!=""
           )
        {
            $val=$item[ $this->ItemData[ $data ][ "ValuesDependencyVar" ] ];
            if ($val!="" && $val>0)
            {
                $values=$this->GetDependentEnumValues($data,$item,FALSE);
            }
        }
        else
        {
            $values=$this->GetRealNameKey($this->ItemData[ $data ],"Values");
            if (!is_array($values) || count($values)==0)
            {
                if ($this->ItemData[ $data ][ "SqlClass" ]!="")
                {
                    $values=array();
                    $this->ReadSubItemValues($data,$item);
                }
                else
                {
                    $this->Debug=1;
                    $this->AddMsg("ENUM data $data has no values set");
                    $values=array();
                }
            }
        }

        $n=$this->ItemData[ $data ][ "SelectOffset" ];
        $keys=array_keys($values);
        if (count($keys)>0)
        {
            //Values is array, we need only the keys
            if (is_array($values[ $keys[0] ]))
            {
                $values=$keys;
            }
        }

        foreach ($values as $val)
        {
            $sorteds[ $val ]=$n;
            array_push($rvalues,$val);

            $n++;
        }

        if (!$this->ItemData[ $data ][ "NoSort" ] && !$this->ItemData[ $data ][ "NoSelectSort" ])
        {
            sort($rvalues,SORT_STRING);
        }

        $values=array();
        $names=array();
        if ($checkbox==FALSE)
        {
            if (!$this->ItemData[ $data ][ "NoSearchEmpty" ])
            {
                $values=array(0);
                $names=array($this->ItemData[ $data ][ "EmptyName" ]);
            }
        }
        elseif ($checkbox==2)
        {
            $values=array(0);
            $names=array("Ignorar");
        }

        foreach ($rvalues as $val)
        {
            array_push($values,$sorteds[ $val ]+1);

            if ($this->ItemData[ $data ][ "MaxLength" ]>0)
            {
                $val=substr($val,0,$this->ItemData[ $data ][ "MaxLength" ]);
            }

            array_push($names,$val);
            $n++;
        }

        if ($value==0 &&  $ignoredefault==0 &&  $this->ItemData[ $data ][ "Default" ])
        {
            $value=$this->ItemData[ $data ][ "Default" ];
        }

        if ($checkbox==1)
        {
            $value=$this->MakeCheckBoxSetTable($data,$values,$names,$value,3,array("ALIGN" => 'left'));
        }
        elseif ($checkbox==2)
        {
            $value=$this->MakeRadioSet($data,$values,$names,$value);
        }
        else
        {
            $value=$this->MakeSelectField($data,$values,$names,$value,array(),array(),$fieldtitle);
        }

        return $value;
   }

}

?>