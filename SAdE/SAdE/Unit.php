<?php


class SAdEUnit extends Application
{
    var $Unit=array();

    //*
    //* function ConnectUnitDB, Parameter list:
    //*
    //* Opens Unit DB and connects.
    //*

    function ConnectUnitDB()
    {
        if (empty($this->DBHash))
        {
            $this->DBHash=$this->ReadPHPArray(".DB.php");
        }

        $unitsobj=new Units;
        $unitsobj->DBHash=$this->DBHash;
        $unitsobj->DBHash[ "DB" ]=$this->DBHash[ "DB" ];

        $unitsobj->OpenDB();

        return $unitsobj;
     }


    //*
    //* function ReadUnit, Parameter list: $unit=FALSE
    //*
    //* 
    //*

    function ReadUnit($unit=FALSE)
    {
        if (!empty($this->Unit)) { return; }

        if (!$unit)
        {
            $unit=$this->GetGET("Unit");
        }

        if (preg_match('/^\d+$/',$unit) && $unit>0)
        {
            $this->RealUnitsObject=$this->ConnectUnitDB();

            $units=$this->RealUnitsObject->SelectHashesFromTable("Units","",array("ID","Name"));
            $runit=0;

            if (preg_match('/^\d+$/',$unit))
            {
                foreach ($units as $unithash)
                {
                    if ($unithash[ "ID" ]==$unit)
                    {
                        $runit=$unithash[ "ID" ];
                        $unit=$unithash[ "Name" ];
                    }
                }
            }
            else
            {
                foreach ($units as $unithash)
                {
                    if (strtolower($unithash[ "Name" ])==strtolower($unit))
                    {
                        $runit=$unithash[ "ID" ];
                    }
                }
            }


            if ($runit>0)
            {
                $this->Unit=$this->RealUnitsObject->SelectUniqueHash("Units",array("ID" => $runit));

                if ($this->Unit)
                {
                    foreach (array("Email","AdmEmail","AdmEmailPassword","CCEmail","FromName") as $data)
                    {
                        $this->MailInfo[ $data ]=$this->Unit[ $data ];
                    }
                }
 
                if ($this->Profile=="Person")
                {
                    $loginunit=$this->MySqlItemValue("People","ID",$this->LoginData[ "ID" ],"Unit");
                    if ($loginunit!=$runit) { print "User not allowed..."; exit(); }
                }

                $this->DefaultAction="MyUnit";
            }
            else
            {
                //$this->UnitList();
            }

            //$this->RealUnitsObject=$unitobj;

            $this->DBHash[ "DB" ]=$this->RealUnitsObject->DBHash[ "DB" ]."_".$runit;
            $this->OpenDB();
        }
        else
        {
            //$this->UnitList();
        }
    }

    //*
    //* Transfers data read into $this->Unit, into $this->CompanyHash.
    //*

    function Unit2CompanyHash()
    {
        if (!empty($this->Unit))
        { 
            foreach (array_keys($this->Unit) as $key)
            {
                $this->CompanyHash[ $key ]=$this->Unit[ $key ];
            }

            $this->CompanyHash[ "Institution" ]="";
            if (!empty($this->Unit[ "Title" ]))
            {
                $this->CompanyHash[ "Institution" ]=$this->Unit[ "Title" ];
            }

            $this->CompanyHash[ "Url" ]="";
            if (!empty($this->Unit[ "WWW" ]))
            {
                $this->CompanyHash[ "Url" ]=$this->Unit[ "WWW" ];
            }

            $this->CompanyHash[ "City" ]="";
             if (!empty($this->Unit[ "City" ]))
            {
                $this->CompanyHash[ "City" ]=$this->Unit[ "City" ];
            }

            $this->CompanyHash[ "State" ]="";
            if (!empty($this->Unit[ "State" ]))
            {
                $this->CompanyHash[ "State" ]=$this->States[ $this->Unit[ "State" ]-1 ];
            }

            $this->CompanyHash[ "ZIP" ]="";
             if (!empty($this->Unit[ "" ]))
            {
                $this->CompanyHash[ "ZIP" ]="CEP: ".$$this->Unit[ "ZIP" ];
            }
        }
    }

    //*
    //* Creates List of Units.
    //*

    function UnitList()
    {
        $unitsobj=$this->ConnectUnitDB();
        $units=$unitsobj->SelectHashesFromTable("Units","",array(),FALSE,"ID");

        $unitsobj->ApplicationObj=$this;
        $unitsobj->LoginType="Public";
        $unitsobj->TInterfaceMenuSend=1;
        $this->NoLeftMenu=TRUE;

        $unitsobj->SavePrintDocHeads();

        $table=array();
        $n=1;
        foreach ($units as $unit)
        {
            array_push
            (
               $table,
               array
               (
                  $this->B($n++),
                  $unit[ "Title" ],
                  $unit[ "Department" ],
                  $this->HRef("?Unit=".$unit[ "ID" ],"Acessar")
               )
            );
        }

        print
            $this->H(1,"Unidades do Sistema nesse Servidor").
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center',"BORDER" => '1'),
               array(),
               array(),
               FALSE,
               FALSE
            );
    }

}

?>
