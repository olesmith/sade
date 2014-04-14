<?php

include_once("Html.php");
include_once("CSV.php");
include_once("Latex.php");
include_once("Cookies.php");
include_once("../Base/Messages.php");
include_once("SqlQuery.php");

class CGI extends SqlQuery
{
    var $URL="";
    var $HiddenVars=array();
    var $CookieVars=array();
    var $GlobalGCVars=array();
    var $CookieTTL=0;
    var $IconsPath="";
    var $Language="";
    var $Languages=array();
    var $URL_Args_Separator="&";

    function CGI()
    {
        $this->InitCGI();
    }

    function ReadExtraPathInfo()
    {
        $pathinfos=$this->GetExtraPathInfos();
        foreach ($this->ExtraPathVars as $id => $var)
        {
            if (count($pathinfos)>0)
            {
                $value=array_shift($pathinfos);
                $this->$var=$value;
            }

            if ($this->$var=="")
            {
                $value=$this->GetCGIVarValue($var);
                if ($value!="")
                {
                    $this->AddMsg("Extra path info from cgi: ".$var."=".$value,2,TRUE);
                    $this->$var=$value;
                }
            }
        }
    }

    function ExtraPathPathCorrection()
    {
        $comps=preg_split('/\//',$this->GetExtraPathInfo());

        $pathinfos=array();
        foreach ($comps as $id => $val)
        {
            if ($val!="")
            {
                array_push($pathinfos,"..");
            }
        }

        $pc="";
        if (count($pathinfos)>0)
        {
            $pc=join("/",$pathinfos);
        }

        return $pc;
    }

    function FilterExtraPathVars($text)
    {
        $hash=array();
        foreach ($this->ExtraPathVars as $id => $var)
        {
            $hash[ $var ]=$this->$var;
        }

        return $this->FilterHash($text,$hash);
    }

    function GenExtraPathInfo()
    {
        $pathinfos=array();
        foreach ($this->ExtraPathVars as $id => $var)
        {
            if ($this->$var!="")
            {
                array_push($pathinfos,$this->$var);
            }
        }

        if (count($pathinfos)>0)
        {
            return "/".join("/",$pathinfos);
        }

        return "";
    }

    function InitCGI($hash=array())
    {
        $this->ReadExtraPathInfo();

        $lang=$this->GetCGIVarValue("Lang");
        if ($lang!="")
        {
            array_push($this->CookieVars,"Lang");
            $this->Language=$lang;
        }

        foreach ($this->GlobalGCVars as $key => $def)
        {
            $rvalue=$this->$key;
            $value=$this->GetCookieOrGET($key);
            if ($value!="")
            {
                $this->$key=$value;
            }

            if ($this->$key=="")
            {
                $this->$key=$def[ "Default" ];
            }

            $rvalue=$this->$key;
            if ($rvalue=="")
            {
                print "No $key, ciiiiao! (".$rvalue.")";
                exit();
            }

            $values=$def[ "Values" ];
            if (!preg_grep('/^'.$rvalue.'$/',$values))
            {
                print "Invalid '$key'-value: '$rvalue', tiiau!";
                exit();
            }
        }
    }

    function GetExtraPathInfo()
    {
        $pathinfo="";
        if (isset($_SERVER['PATH_INFO']))
        {
            $pathinfo=$_SERVER['PATH_INFO'];
        }
        return $pathinfo;
    }

    function GetExtraPathInfos()
    {
        $pathinfo=$this->GetExtraPathInfo();
        $pathinfo=preg_replace('/^\//',"",$pathinfo);

        if ($pathinfo!="")
        {
            return preg_split('/\//',$pathinfo);
        }
        else
        {
            return array();
        }
    }

    function ExtraPathInfoCorrection()
    {
        $pathinfos=$this->GetExtraPathInfos();

        if (count($pathinfos)>0)
        {
            $rpaths=array();
            foreach ($pathinfos as $id => $data)
            {
                array_push($rpaths,"..");
            }

            return join("/",$rpaths)."/";
        }
        else
        {
            return "";
        }
    }

  function SetURL()
  {
      $this->URL="http";
      if (isset($_SERVER[ "HTTPS" ]))
      {
          $this->URL.="s";
      }

      $this->URL.="://".$this->ServerName().$this->ScriptPath()."/".$this->ScriptName();
      $this->URL=preg_replace('/\/?index.php/',"",$this->URL);

      return $this->URL;
  }

  function ServerName()
  {
    return $_SERVER[ "SERVER_NAME" ];
  }

  function ScriptPath()
  {
    $scriptname=$_SERVER[ "SCRIPT_NAME" ];
    $comps=preg_split('/\//',$scriptname);
    $name=array_pop($comps);

    return join("/",$comps);
  }

  function ScriptPathInfo()
  {
    $scriptname=$_SERVER[ "REQUEST_URI" ];
    $comps=preg_split('/\?/',$scriptname);

    $info=array_shift($comps);
    if (preg_match('/\.php\/(\S+)$/',$info,$comps))
    {
        $info="/".$comps[1];
    }
    else
    {
        $info="";
    }

    return $info;
  }

  function ScriptName()
  {
    $scriptname=$_SERVER[ "SCRIPT_NAME" ];
    $comps=preg_split('/\//',$scriptname);
    $name=array_pop($comps);

    return $name;;
  }


  function ScriptQuery()
  {
    $scriptname=$_SERVER[ "REQUEST_URI" ];
    $comps=preg_split('/\?/',$scriptname);
    $name=array_pop($comps);

    return $name;
  }


  function ScriptQueryHash($hash=array())
  {
      $args=$this->Query2Hash($this->ScriptQuery());
      foreach ($hash as $key => $value) { $args[ $key ]=$value; }

      return $args;
  }


  function ScriptProtocol()
  {
      $protocol="http";
      if (isset($_SERVER[ "HTTPS" ]))
      {
          $protocol.="s";
      }

      return $protocol;
  }


  function ScriptExec($query="",$scriptname="")
  {
      if ($scriptname=="") { $scriptname=$this->ScriptName(); }

      $exec=
          $this->ScriptProtocol()."://".
          $this->ServerName().
          $this->ScriptPath().
          "/".
          $scriptname.
          $this->ScriptPathInfo();

      if ($query!="") { $exec.="?".$query; }

      $exec=preg_replace('/\s+/',"",$exec);
      return $exec;
  }

  function ThisScriptExec()
  {
      return preg_replace
      (
         '/index.php/',
         "",
         $this->ScriptExec($this->ScriptQuery())
      );
  }

  function SendDocHeader($contenttype,$filename="",$charset="")
  {
      $contenttypes=array
      (
         "html" => "text/html",
         "sql"  => "text/plain",
         "csv"  => "application/vnd.ms-excel",
         "tex"  => "application/x-latex",
         "pdf"  => "application/pdf",
         "odt"  => "application/vnd.oasis.opendocument.text",
         "ods"  => "application/vnd.oasis.opendocument.spreadsheet",
         "doc"  => "application/vnd.msword",
         "xls"  => "application/vnd.ms-excel",
         "zip"  => "application/zip",
         "jpg"  => "image/jpeg",
         "png"  => "image/png",
      );

      if (!empty($contenttypes[ $contenttype ]))
      {
          $contenttype=$contenttypes[ $contenttype ];
      }

      if ($contenttype=="") { $contenttype="text/plain"; }

      if ($charset=="" && isset($this->HtmlSetupHash[ "CharSet"  ]))
      {
          $charset=$this->HtmlSetupHash[ "CharSet"  ];
      }
      else { $charset="utf=8"; }

      header('Content-type: '.$contenttype.'; charset='.$charset);

      if ($filename!="")
      {
          header
          (
             'Content-Disposition: attachment;'.
             'filename="'.$filename.'"; charset='.$charset
          );             
      }
  }


  function FindIconsPath()
  {
      //Set if not detected
      if ($this->IconsPath=="")
      {
          $script=$_SERVER[ "SCRIPT_NAME" ];
          $paths=preg_split('/[\/]/',$script);
          array_pop($paths);

          $spath="";
          $found=0;
          $n=0;
          //Looping up through path, unitl spath/icons is found (max 10...)
          //Paths in the filesystem!
          while ($n<10 && $found==0)
          {
              if (is_dir($spath."icons")) { $found=1; }
              else                        { $spath="../".$spath; }

              $n++;
          }

          //Take Extra Path Information into consideration,
          //since relative links changes using this feature
          //One up (../)  per / in path info
          $pathinfos=$this->GetExtraPathInfos();
          for ($n=0;$n<count($pathinfos);$n++)
          {
              $spath="../".$spath;
          }

          $this->IconsPath=$spath."icons";
      }
      

      return $this->IconsPath;
  }

  function MakeHiddenFields($tabmovesdown=FALSE)
  {
      $fields=array();
      if ($tabmovesdown)
      {
          array_push
          (
             $fields,
             $this->MakeHidden
             (
                $this->ModuleName."_TabMovesDown",
                $this->GetPOST($this->ModuleName."_TabMovesDown")
             )
          );
      }

      if (is_array($this->HiddenVars))
      {
          foreach ($this->HiddenVars as $var)
          {
              $value=$this->GetGETOrPOST($var);
              if ($value!="") { array_push($fields,$this->MakeHidden($var,$value)); }
          }
      }

      if (is_array($this->SplitVars))
      {
          foreach ($this->SplitVars as $var => $def)
          {
              $vvar=$this->ItemName."_".$var;
              $val=$this->GetCGIVarValue($vvar."_Only");
           
              if ($val!="")
              {
                  array_push($fields,$this->MakeHidden($vvar."_Only",$val));
              }
          }
      }
      if (is_array($this->SearchVars))
      {
          foreach ($this->SearchVars as $var => $def)
          {
              $vvar=$this->ItemName."_".$var;
              $val=$this->GetCGIVarValue($vvar."_Search");
           
              if ($val!="")
              {
                  array_push($fields,$this->MakeHidden($vvar."_Search",$val));
              }
          }
      }


      return join("",$fields);
  }

  function MakeHiddenQuery()
  {
      $fields=array();
      if (is_array($this->HiddenVars))
      {
          foreach ($this->HiddenVars as $var)
          {
              $value=$_GET[ $var ];
              if ($value=="") { $value=$_POST[ $var ]; }
              if ($value!="") { array_push($fields,join("=",array($var,$value))); }
          }
      }

      return join($this->URL_Args_Separator,$fields);
  }

  function Hidden2Hash($hash=array())
  {
      if (is_array($this->HiddenVars))
      {
          foreach ($this->HiddenVars as $var)
          {
              if (isset($_POST[ $var ]) && $_POST[ $var ]!="")
              {
                  $hash[ $var ]=$value;
              }
          }
      }

      return $hash;
  }


  function Query2Hash($qs="",$argshash=array())
  {
      if ($qs=="") { $qs=$_SERVER[ "QUERY_STRING" ]; }
      $qs=preg_replace('/\?/',"",$qs);

      if (preg_match('/\S/',$qs))
      {
          $qargs=preg_split('/\s*&(amp;)?\s*/',$qs);
          foreach ($qargs as $arg)
          {
              $argss=preg_split('/\s*=\s*/',$arg);
              if (isset($argss[1]))
	          {
                  $argshash[ $argss[0] ]=$argss[1];
              }
          }
      }

      return $argshash;
  }

  function Hash2Query($argshash)
  {
      $queries=array();
      foreach ($argshash as $arg => $value)
      {
          $string=$arg."=".$value;
          array_push($queries,$string);
      }

      return join($this->URL_Args_Separator,$queries);
  }

  function QueryString($args=array())
  {
      //Retrive query hash
      $argshash=$this->Query2Hash();
      if (is_array($args))
      {
          //Overwrite specified value
          foreach ($args as $arg => $value)
          {
              $argshash[ $arg ]=$value;
          }
      }
      elseif ($args!="")
      {
          $argshash=$this->Query2Hash($args);
      }

      //Retransform in query
      $qs=$this->Hash2Query($argshash);

      //Add ? if necessary
      if (preg_match('/\S/',$qs)) { $qs="?".$qs; }

      return $qs;
  }

    //*
    //* sub TreatCGIValue, Parameter list: $value
    //*
    //* Clean value retrieved from the CGI, ie:
    //*
    //* turn ''s into "&#39;"

    function TreatCGIValue($value)
    {
        //$value=preg_replace('/\\\\\'/',"&#39;",$value);
        $value=htmlentities($value,ENT_QUOTES,'UTF-8' );

        return $value;
    }

    //*
    //* sub GetPOST, Parameter list: $name
    //*
    //*

    function GetPOST($name)
    {
        $value="";
        if (isset($_POST[ $name ]))
        {
            $value=$_POST[ $name ];
        }
        
        
        return $this->TreatCGIValue($value);
    }

    //*
    //* sub GetGET, Parameter list: $name
    //*
    //*

    function GetGET($name)
    {
        $value="";
        if (isset($_GET[ $name ]))
        {
            $value=$_GET[ $name ];
        }

        $value=preg_replace('/\?.*/',"",$value);

        return $this->TreatCGIValue($value);
    }
    //*
    //* sub GetCookie, Parameter list: $name
    //*
    //*

    function GetCookie($name)
    {
        $value="";
        if (isset($_COOKIE[ $name ]))
        {
            $value=$_COOKIE[ $name ];
        }
        
        return $this->TreatCGIValue($value);
    }
    //*
    //* sub GetGETOrPOST, Parameter list: $name
    //*
    //*

    function GetGETOrPOST($name)
    {
        $value="";
        if (isset($_GET[ $name ]))
        {
            $value=$_GET[ $name ];
        }
        else
        {
            if (isset($_POST[ $name ]))
            {
                $value=$_POST[ $name ];
            }
        }

        return $this->TreatCGIValue($value);
    }

    //*
    //* sub GetCookieOrGET, Parameter list: $name
    //*
    //*

    function GetCookieOrGET($name)
    {
        $value="";
        if (isset($_GET[ $name ]))
        {
            $value=$_GET[ $name ];
        }
        else
        {
            if (isset($_COOKIE[ $name ]))
            {
                $value=$_COOKIE[ $name ];
            }
        }

        return $this->TreatCGIValue($value);
    }

    //*
    //* sub GetCookieOrPOST, Parameter list: $name
    //*
    //*

    function GetCookieOrPOST($name)
    {
        $value="";
        if (isset($_POST[ $name ]))
        {
            $value=$_POST[ $name ];
        }
        else
        {
            if (isset($_COOKIE[ $name ]))
            {
                $value=$_COOKIE[ $name ];
            }
        }
        
        return $this->TreatCGIValue($value);
    }

    //*
    //* sub GetCGIVarValue, Parameter list: $name
    //*
    //*

    function GetCGIVarValue($name)
    {
        $value="";

        $value=$this->GetGET($name);
        if ($value=="")
        {
            $value=$this->GetPOST($name);
            if ($value=="")
            {
                if (isset($_COOKIE[ $name ]))
                {
                    $value=$_COOKIE[ $name ];
                }          
            }
        }

        return $this->TreatCGIValue($value);
    }

    //*
    //* sub GetCGIVarValue, Parameter list: $name
    //*
    //*

    function GetCGIVarRegexValue($regex)
    {
        $keys=array_keys($_GET);
        $keys=preg_grep('/'.$regex.'/',$keys);
        if (count($keys)==1)
        {
            return $this->GetCGIVarValue(array_pop($keys));
        }

        $keys=array_keys($_POST);
        $keys=preg_grep('/'.$regex.'/',$keys);
        if (count($keys)==1)
        {
            return $this->GetCGIVarValue(array_pop($keys));
        }

        $keys=array_keys($_COOKIE);
        $keys=preg_grep('/'.$regex.'/',$keys);
        if (count($keys)==1)
        {
            return $this->GetCGIVarValue(array_pop($keys));
        }
    }

    //*
    //* sub GetFirstPOSTDefined, Parameter list: $name
    //*
    //*

    function GetFirstPOSTDefined($fieldnames)
    {
        $value="";
        $n=0;
        while ($value=="" && $n<count($fieldnames))
        {
            if (isset($_POST[ $fieldnames[ $n ] ]))
            {
                $value=$_POST[ $fieldnames[ $n ] ];
            }

            $n++;
        }
        
        return $this->TreatCGIValue($value);
    }



    //*
    //* sub ReadUploadedFile, Parameter list: $field,$extensions=array()
    //*
    //* Reads uploaded file $data
    //*

    function ReadUploadedFile($field,$extensions=array())
    {
        $uploadinfo=$_FILES[ $field ];

        $lines=array();
        if (is_array($uploadinfo) && $uploadinfo[ 'tmp_name' ]!="")
        {
            $error=$uploadinfo['error']; //react!

            $comps=preg_split('/\./',$uploadinfo['name']);
            $ext=array_pop($comps);
            if (count($extensions)==0 || preg_grep('/^'.$ext.'$/',$extensions))
            {
                $lines=$this->MyReadFile($uploadinfo['tmp_name']);
            }
        }

        return $lines;
    }



}


?>