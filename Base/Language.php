<?php

class Language extends File
{
    var $Languages=array();
    var $Messages=array();
    var $DefaultLanguage="";
    var $Language=FALSE;
    var $LanguageID=FALSE;
    var $MessagePath="Messages";
    var $MessagePaths=array("./","../Base/","../MySql2/","../Application/");

    //*
    //* function Language, Parameter list: 
    //*
    //* Language class creator.
    //*

    function Language()
    {
    }

    //*
    //* function InitLanguage, Parameter list: 
    //*
    //* Initializer called by class Base.
    //*

    function InitLanguage()
    {
    }

    //*
    //* function DetectLanguage, Parameter list: 
    //*
    //* Detects if language is set as post var Lang.
    //* If unset, uses DefaultLanguage.
    //* Sets Language and return result.
    //*

    function DetectLanguage($storedlanguage="")
    {
        $language=$this->GetCGIVarValue("Lang");
        if ($language=="")
        {
            if ($storedlanguage!="") { $language=$storedlanguage; }
            else                     { $language=$this->DefaultLanguage; }
        }

        $this->Language=$language;

        $n=1;
        foreach ($this->Languages as $key => $value)
        {
            if ($key==$language) { $this->LanguageID=$n; }
            $n++;
        }

        return $language;
    }

    //*
    //* function GetLanguage, Parameter list: 
    //*
    //* Returns language as set by Language.
    //* If unset, calls DetectLanguage and
    //* returns value obtained.
    //*

    function GetLanguage()
    {
        if ($this->Language==FALSE)
        {
            $this->DetectLanguage();
        }

        return $this->Language;
    }


    //*
    //* function GetLanguageKeys, Parameter list: 
    //*
    //* Returns list of avaliable language keys -
    //* used to retrieved languaged data.
    //*

    function GetLanguageKeys()
    {
        $langs=array_keys($this->Languages);
        $rlangs=array();
        foreach ($langs as $id => $lang)
        {
            if ($lang==$this->DefaultLanguage) { $lang=""; }
            if ($lang!="") { $lang="_".$lang; }
            array_push($rlangs,$lang);
        }

        return $rlangs;
    }

    //*
    //* function SearchMessageFile, Parameter list: $file
    //*
    //* Searches for message file using include_path.
    //* Returns name of Message file.
    //*

    function SearchMessageFile($file,$subpath="")
    {
        if ($subpath=="") { $subpath=$this->MessagePath; }

        $file=join("/",array($subpath,$file));

        return $this->SearchForFile($file,$this->MessagePaths);
    }

    //*
    //* function ReadMessageFile, Parameter list: $file
    //*
    //* Reads php array defined in message file $file.
    //*

    function ReadMessageFile($file)
    {
        $this->Messages[ $file ]=$this->ReadPHPArray($file);
        return;
    }

    //*
    //* function WriteMessageFile, Parameter list: $file
    //*
    //* Writes back out php array defined in hash $hash to file $file.
    //*

    function WriteMessageFile($hash,$file)
    {
        $text=
            "<?php\n".
            var_export($hash,TRUE).
            ";\n".
            "?>\n";
        $this->MyWriteFile($file,$text);
    }

    //*
    //* function GetRealNameKey, Parameter list: $hash,$key="Name"
    //*
    //* Retrives key $key in $hash, as appropriate language,
    //* Deprecated, should be migrated to use GetMessage!
    //*

    function GetRealNameKey($hash,$key="Name")
    {
        $language=$this->GetLanguage();
        $val="";
        if (
            $language!=""
            &&
            isset($hash[ $key."_".$language ])
            &&
            $hash[ $key."_".$language ]!=""
           )
        {
            $val=$hash[ $key."_".$language ];
        }
        elseif (
                isset($hash[ $key ])
                &&
                $hash[ $key ]!=""
               )
        {
            $val=$hash[ $key ];
        }

        return $val;
        
    }

    //*
    //* function GetMessage, Parameter list: $file,$key,$subkey="Name"
    //*
    //* Retrieves message $key => $subkey from file $file.
    //* Files are read in full as needed, maintaining result in memory
    //* to be used by future calls to GetMessage.
    //* Read message files, are store in $this->Messages hash:
    //* 
    //*   $this->Messages[ $file ][ $key ][ $subkey ]
    //*
    //* $subkey is subject to language iteration.
    //*

    function GetMessage($file,$key,$subkey="Name")
    {
        if (!is_array($file))
        {
            $file=$this->SearchMessageFile($file);
            if ($file!=FALSE)
            {
                if (!isset($this->Messages[ $file ]) || !is_array($this->Messages[ $file ]))
                {
                    $this->ReadMessageFile($file);
                }
            }
        }

        $language=$this->GetLanguage();
        if (is_array($this->Messages[ $file ]))
        {
            $val=$this->Messages[ $file ][ $key ][ $subkey ];
            if ($language!="")
            {
                if ($this->Messages[ $file ][ $key ][ $subkey."_".$language ]!="")
                {
                    $val=$this->Messages[ $file ][ $key ][ $subkey."_".$language ];
                }
            }

            return $val;
        }
        
    }


}

?>