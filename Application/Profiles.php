<?php


class Profiles extends Perms
{
    var $ValidProfiles=array();
    var $Menus=array();
    var $DefaultProfile=0;
    var $AllowedProfiles=array();
    var $Profiles=array();
    var $Profile="";
    var $NProfiles=0;

    var $ProfilesSubPath="Profiles";
    var $ProfilesPath="System/Profiles";

    //*
    //* function ReadProfiles, Parameter list: 
    //*
    //* Reads Profiles from setup files.
    //*

    function ReadProfiles()
    {
        if (count($this->Profiles)>0)
        {
            return;
        }

        if (file_exists($this->ProfileFile()))
        {
            $this->Profiles=$this->ReadPHPArray($this->ProfileFile());
            foreach ($this->ValidProfiles as $id => $profile)
            {
                if (empty($this->Profiles[ $profile ]))
                {
                    print "ReadProfiles: Profile ".$profile." unset in ".$this->ProfileFile()."<BR>";
                    //exit();
                }
            }
        }
        else
        {
            print "ReadProfiles: No Profiles file: ".$this->ProfileFile()."<BR>";
            exit();            
        }

        $this->NProfiles=count($this->ValidProfiles);
        $this->AddCookieVar("Profile");
    }

    //*
    //* function GetProfileDef, Parameter list: $profile=""
    //*
    //* Returns profile definition belonging to $profile.
    //* If $profile omitted or empty, returns profile
    //* belonging to $this->Profile.
    //*

    function GetProfileDef($profile="")
    {
        if ($profile=="") { $profile=$this->Profile; }

        return $this->Profiles[ $profile ];
    }

    //*
    //* function GetProfileName, Parameter list: $profile=""
    //*
    //* Returns name of Profile $profile or current.
    //*

    function GetProfileName($profile="")
    {
        if ($profile=="") { $profile=$this->Profile; }
        return $this->GetRealNameKey($this->Profiles[ $profile ],"Name");
    }

    //*
    //* function DetectAllowedProfiles, Parameter list: 
    //*
    //* Detect profiles allowed. Runs thorugh defined profiles,
    //* and adds it, if hash key in $this->LoginData
    //* "Profile_".$profile is ==2.
    //*

    function DetectAllowedProfiles()
    {
        if (count($this->AllowedProfiles)>0) { return; }

        $this->NProfiles=count($this->ValidProfiles);

        $this->Profile=$this->GetCGIVarValue("Profile");
        $this->AllowedProfiles=array();
        if ($this->LoginData)
        {
            for ($n=0;$n<count($this->ValidProfiles);$n++)
            {
                $profile=$this->ValidProfiles[ $n ];
                if (
                      $profile!="Public"
                      &&
                      isset($this->LoginData[ "Profile_".$profile ])
                      &&
                      $this->LoginData[ "Profile_".$profile ]==2
                   )
                {
                    array_push($this->AllowedProfiles,$profile);
                    if ($this->Profile=="" && $profile!="Public")
                    {
                        $this->Profile=$profile;
                    }
                }
            }
        }

       //No profile yet? Use Public
        if ($this->Profile=="")
        {
            $this->Profile="Public";
        }

        if (
            !preg_grep('/^'.$this->Profile.'$/',$this->AllowedProfiles) 
            &&
            $this->Profile!="Public"
           )
        {
            if (count($this->AllowedProfiles)>0)
            {
                $this->Profile=$this->AllowedProfiles[0];
            }
            else
            {
                print "Profile ".$this->Profile." not allowed";
                exit();
            }
        }

        $this->SetCookie("Profile",$this->Profile,time()+$this->CookieTTL);
    }


    //*
    //* function DetectProfile, Parameter list: 
    //*
    //* Detect allowed profiles from $this->LoginData, and
    //* then CGI/Cookie value of key Profile. Sets profile
    //* to value found, if allowed. Otherwise, set profile to Public.
    //*


    function DetectProfile()
    {
        $this->Profile=$this->GetCGIVarValue("Profile");
        if ($this->Profile=="")
        {
            $this->DetectAllowedProfiles();
        }

        if ($this->LoginType=="Admin" && $this->Profile!="Admin")
        {
            $this->LoginType="Person";
        }

        if ($this->LoginType!="Admin" && $this->Profile=="Admin")
        {
            if ($this->MayBecomeAdmin())
            {
                $this->LoginType="Admin";
            }
            else
            {
                $this->Profile="Public";
            }
        }
        elseif ($this->LoginType=="Public")
        {
            $this->Profile="Public";
        }

        if ($this->LoginType!="")
        {
            if ($this->LoginType=="Public")
            {
                $this->Profile="Public";
            }
            else
            {
                //$this->Profile="";
                $this->DetectAllowedProfiles();

               if ($this->LoginType=="Admin")
                {
                    $this->Profile="Admin";
                    $this->SetCookie("Admin",1,time()+$this->CookieTTL);
                }
                elseif ($this->LoginType=="Person")
                {
                    $this->SetCookie("Admin",0,time()-$this->CookieTTL);
                }
                else { print "Invalid profile: ".$this->Profile." - exiting"; exit(); }
            }
        }

        return $this->Profile;
    }




}

?>