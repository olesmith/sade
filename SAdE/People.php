<?php

class People extends Common
{

    //*
    //* Variables of People class:

    //*
    //* function People, Parameter list: $args=array()
    //*
    //* Constructor.
    //*

    function People($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Status");
        $this->AddDatas=array
        (
           "Status","Name","PRN","Email",
           "PRN","SUS","BirthDay","Sex","Race","Civil","City",
           "WorkAddress","Profession","Phone","WorkPhone","Cell",
           "Street","Area","City","Father","Mother",
        );
    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        $this->Actions[ "Show" ][ "AccessMethod" ]="CheckEditAccess";
        $this->Actions[ "Edit" ][ "AccessMethod" ]="CheckEditAccess";
        $this->Actions[ "Edit" ][ "AltAction" ]="Show";

        foreach (array("State","BirthState","MotherState","FatherState","PRN1State","PRN2State","PRN3State") as $data)
        {
            $this->ItemData[ $data ][ "Values" ]=$this->ApplicationObj->States;
        }


        foreach ($this->ApplicationObj->Profiles as $profile => $profiledef)
        {
            if ($profile=="Public") { continue; }

            $pkey="Profile_".$profile;

            $secretary=2;
            if ($profile=="Secretary") { $secretary=1; }

            $this->ItemData[ $pkey ]=array
            (
               "Name" => $profiledef[ "Name" ],
               "Name_UK" => $profiledef[ "Name_UK" ],
               "Sql" => "ENUM",
               "Values" => array("Não","Sim"),
               "Values_UK" => array("No","Yes"),
               "Default" => 1,
               "Search" => FALSE,
               "SearchCheckBox" => TRUE,

               "Admin" => 2,
               "Person" => 0,
               "Public" => 0,
               "Secretary" => $secretary,
               "Coordinator" => 1,
               "Clerk" => 1,
               "Teacher" => 1,
               "Medical" => 0,
               "Nurse" => 0,
            );
         }
     }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ItemDataGroups[ "Profiles" ]=array
        (
           "Name" => "Perfis",
           "Name_UK" => "Profiles",
           "Data" => array("No","Edit","Name","Status","Department",),
           "Admin" => 1,
           "Public" => 0,
           "Secretary" => 1,
           "Medical" => 0,
           "Nurse" => 0,
        );
        $this->ItemDataSGroups[ "Profiles" ]=array
        (
           "Name" => "Perfis",
           "Name_UK" => "Profiles",
           "Data" => array(),
           "Admin" => 1,
           "Public" => 0,
           "Secretary" => 1,
           "Medical" => 0,
           "Nurse" => 0,
        );


        foreach ($this->ApplicationObj->Profiles as $profile => $profiledef)
        {
            if ($profile=="Public") { continue;  }

            $pkey="Profile_".$profile;
            array_push($this->ItemDataGroups[ "Profiles" ][ "Data" ],$pkey);
            array_push($this->ItemDataSGroups[ "Profiles" ][ "Data" ],$pkey);
         }

        $this->SetItemGroupDefaults($this->ItemDataGroups[ "Profiles" ]);
    }

    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        if (empty($item[ "ID" ])) { return $item; }

        if ($this->GetGET("ModuleName")!="People")
        {
            return $item;
        }

        $udatas=$this->TakeUndefinedListOfKeys
        (
           $item,
           $this->ApplicationObj->Unit,
           array
           (
              array
              (
                 "Key" => "State",
                 "Keys" => array
                 (
                    "State","BirthState","MotherState","FatherState",
                    "PRN1State","PRN2State","PRN3State"
                 ),
              ),
              array
              (
                 "Key" => "City",
                 "Keys" => array("City","BirthCity","MotherCity","FatherCity"),
              ),
              array
              (
                 "Key" => "",
                 "Keys" => array("ZIP"),
              ),
           ),
           TRUE
        );

        //Update Age
        if (!empty($item[ "BirthDay" ]))
        {
            $today=$this->TimeStamp2DateSort();

            $byear = substr($item[ "BirthDay" ],0,4);
            $bmon = substr($item[ "BirthDay" ],4,2);
            $bdate = substr($item[ "BirthDay" ],6,2);

            $year  = substr($today,0,4);
            $mon = substr($today,4,2);
            $date = substr($today,6,2);

            $item[ "Age" ]=$year-$byear;
            if ($mon<$bmon) { $item[ "Age" ]--; }
            elseif ($mon==$bmon)
            {
                if ($date<$bdate) { $item[ "Age" ]--; }
            }

            $this->MySqlSetItemValues("",array("BirthDay","Age"),$item);
        }

        return $item;
    }

    //*
    //* function GetRealWhereClause, Parameter list: $where="",$data=""
    //*
    //* Returns the real overall where clause for People.
    //*

    function GetRealWhereClause($where="",$data="")
    {
        if (!empty($where) && !is_array($where))
        {
            $where=$this->SqlClause2Hash($where);
        }

        if ($this->ApplicationObj->Unit)
        {
            if ($this->LoginType=="Public")
            {
            }
            elseif ($this->Profile=="Secretary")
            {
            }
            elseif ($this->Profile=="Medical")
            {
            }
            elseif ($this->Profile=="Nurse")
            {
            }
        }

        return $where;
    }

    //*
    //* function InitAddDefaults, Parameter list: $hash=array()
    //*
    //* 
    //*

    function InitAddDefaults($hash=array())
    {
        if ($this->Profile=="Secretary")
        {
        }

        parent::InitAddDefaults($hash);
    }
    //*
    //* function AddForm, Parameter list: $title,$addedtitle,$echo=TRUE
    //*
    //* 
    //*

    function AddForm($title,$addedtitle,$echo=TRUE)
    {
        if ($this->Profile=="Secretary")
        {
            $this->AddFixedValues[ "Profile_Person" ]=2;
            $this->AddFixedValues[ "Profile_Admin" ]=1;
        }
        elseif ($this->Profile=="Nurse")
        {
            $this->AddFixedValues[ "Profile_Person" ]=2;
            $this->AddFixedValues[ "Profile_Admin" ]=1;
        }

        parent::AddForm($title,$addedtitle,$echo);
    }

    //*
    //* function SendPasswordChangeMail, Parameter list: $item
    //*
    //* Should be registered as a trigger function for Passwd.
    //* Sends an email informing the change.
    //*

    function SendPasswordChangeMail($item,$data,$newvalue)
    {
        $text=
            "Prezado(a) ".$item[ "Name" ]."\n\n".
            "Informamos que sua senha para acessar nosso sistema, ".$this->ApplicationObj->HtmlSetupHash[ 'WindowTitle' ].", ".
            "fue alterado. Para acessar, utilize os credencials:\n\n".
            "Usuário: ".$item[ "Email" ]."\n".
            "Senha: ".$newvalue."\n\n".
            "Como uma medida de segurança, solicitamos que você acessa o sistema e altera sua senha.\n\n".
            "";

        $this->SendGMail
        (
           $item[ "Email" ],
           "Alteração de Senha",
           $text
        );

        $item[ $data ]=md5($newvalue);
        return $item;
    }


    //*
    //* function SendEmailChangeMail, Parameter list: $item
    //*
    //* Should be registered as a trigger function for Passwd.
    //* Sends an email informing the change.
    //*

    function SendEmailChangeMail($item,$data,$newvalue)
    {
        $text=
            "Prezado(a) ".$item[ "Name" ]."\n\n".
            "Informamos que sua nome de usuário para acessar nosso sistema, ".$this->ApplicationObj->HtmlSetupHash[ 'WindowTitle' ].", ".
            "fue alterado. Para acessar, utilize os credencials:\n\n".
            "Usuário: ".$newvalue."\n\n".
            "Informamos, que e senha de acesso mantenha-se inalterada.\n\n".
            "Como uma medida de segurança, solicitamos que você acessa o sistema e altera sua senha.\n\n".
            "";

        $this->SendGMail
        (
           $item[ "Email" ],
           "Alteração de Usuário",
           $text
        );

        $item[ $data ]=$newvalue;
        return $item;
    }


    //*
    //* Handles the edit personal data form.
    //*

    function HandleMyData()
    {
        foreach (array("Passwd") as $data)
        {
            $this->ItemData[ $data ][ $this->LoginType ]=0;
            $this->ItemData[ $data ][ $this->Profile ]=0;
        }

        foreach (preg_grep('/^Profile\_/',array_keys($this->ItemData)) as $data)
        {
            $this->ItemData[ $data ][ $this->LoginType ]=1;
            $this->ItemData[ $data ][ $this->Profile ]=1;
        }

        $this->ReadItem($this->LoginData[ "ID" ]);
        $this->EditForm("Editar Dados Pessoais",array(),1,FALSE,array(),TRUE,array(),"?Action=MyData");
    }


    //*
    //* function CheckEditAccess, Parameter list: $item
    //*
    //* Checks if $item may be edited or shown.
    //* Admin may always.
    //* Secretary may if Unit matches login unit
    //* Outher persons may if ID matches login ID
    //*

    function CheckEditAccess($item)
    {
        if (empty($item[ "ID" ])) { return FALSE; }

        $res=FALSE;
        if (preg_match('/^(Admin)$/',$this->LoginType))
        {
            $res=TRUE;
        }
        elseif (preg_match('/^(Secretary)$/',$this->Profile))
        {
            $res=TRUE;
        }
        elseif (preg_match('/^(Person)$/',$this->LoginType))
        {
            if (!empty($item[ "ID" ]) && $item[ "ID" ]==$this->LoginData[ "ID" ])
            {
                $res=TRUE;
            }
        }

        return $res;
    }
}

?>