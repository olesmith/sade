<?php


global $DBLinks;
$DBLinks=array();

class MySqlDB extends MySqlSystem
{
    //*
    //* function OpenDB, Parameter list: 
    //*
    //* Opens the DB, using the parameters in DBHash.
    //*

    function OpenDB()
    {
        global $DBLinks;
        if (!empty($DBLinks[ $this->DBHash[ "DB" ] ]))
        {
            $this->DBHash[ "Link" ]=$DBLinks[ $this->DBHash[ "DB" ] ];
        }
        else
        {
            $this->DBHash[ "Link" ]=$this->MySqlConnect();
            while (preg_match('/#(\S+)/',$this->DBHash[ "DB" ],$matches))
            {
                $var=$matches[1];
                $value=$this->$var;
                if (is_array($value)) { $value=$value[ "ID" ]; }

                $this->DBHash[ "DB" ]=preg_replace('/#'.$var.'/',$value,$this->DBHash[ "DB" ]);
            }

            $this->SelectDB($this->DBHash[ "DB" ]);

 
            foreach ($this->GlobalGCVars as $key => $def)
            {
                if (isset($def[ "Method" ]) && preg_match('/\S/',$def[ "Method" ]))
                {
                    if (method_exists($this,$def[ "Method" ]))
                    {
                        $method=$def[ "Method" ];
                        $this->$method();
                    }
                    else
                    {
                        print "No such method '$method' (key '$key'), tiiiau!";
                        exit();
                    }
                }
            }

            $DBLinks[ $this->DBHash[ "DB" ] ]=$this->DBHash[ "Link" ];
        }

        return $this->DBHash[ "Link" ];
    }

    //*
    //* function CloseDB, Parameter list: $link
    //*
    //* Closes DB referenced in $link:
    //* 
    //* 

    function CloseDB($link)
    {
        $this->MysqlClose();
    }

    //*
    //* function CreateDB, Parameter list: $dbname
    //*
    //* Creates DB $dbname.
    //* 
    //* 

    function DBExists($dbname)
    {
        $query="SHOW DATABASES LIKE '".$dbname."'";
        $result=$this->QueryDB($query);
        $res=$this->MySqlFetchAssoc($result);

        $this->MySqlFreeResult($result);
        return $res;
    }

    //*
    //* function CreateDB, Parameter list: $dbname
    //*
    //* Creates DB $dbname.
    //* 
    //* 

    function CreateDB($dbname)
    {
        if (!$this->DBExists($dbname))
        {
            $query="CREATE DATABASE IF NOT EXISTS ".$dbname;
            $res=$this->QueryDB($query);
            if ($res)
            {
                $this->FreeResult($res);

                return TRUE;
            }

            return $res;
        }
    }

    //*
    //* function SelectDB, Parameter list: $dbname,$link=NULL
    //*
    //* Set the current DB to $dbname.
    //* 
    //* 

    function SelectDB()
    {
        if (!$this->MysqlSelectDB())
        {
            $this->CreateDB($this->DBHash[ "DB" ]);
        }

        $res=$this->MysqlSelectDB();
        if (!$res)
        {
            echo "DB ".$dbname." does not exist and unable to create<BR>\n";
        }

        return $res;
    }

    //*
    //* function GetDBList, Parameter list:
    //*
    //* Returns list of avaliable DBs.
    //* 
    //* 

    function GetDBList()
    {
        $result= $this->MysqlListDBs();

        $dbases=array();
        $m=0;
        while ($row = $this->FetchAssoc($result))
        {
            foreach ($row as $key => $value)
            {
                $dbases[$m]=$value;
            }

           $m++;
        }

        $this->FreeResult($result);

        return $dbases;
    }


}

?>