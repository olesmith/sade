<?php

global $Queries;
$Queries=NULL;

class MySqlQuery extends MySqlFetch
{

    //*
    //* function MySqlConnect, Parameter list: 
    //*
    //* Performs MySql Query, $query. Returns the raw result.
    //* 
    //* 

    function MySqlConnect()
    {
        $link=mysql_connect
        (
           $this->DBHash[ "Host" ],
           $this->DBHash[ "User" ],
           $this->DBHash[ "Password" ]
        );

        if (!$link)
        {
            $this->MySqlError('Could not connect to server: '.$this->DBHash[ "Host" ]);
        }
        //else { print "Connect ".$this->DBHash[ "DB" ].", ".$this->ModuleName."<BR>";  }

        $this->DBHash[ "Link" ]=$link;

        return $link;
    }

    //*
    //* function MysqlClose, Parameter list: 
    //*
    //* Calls mysql_close.
    //* 
    //* 

    function MysqlClose()
    {
        mysql_close($this->DBHash[ "Link" ]);
    }

    //*
    //* function MysqlSelectDB, Parameter list: 
    //*
    //* Calls mysql_select_db
    //* 
    //* 

    function MysqlSelectDB()
    {
        return mysql_select_db($this->DBHash[ "DB" ],$this->DBHash[ "Link" ]);
    }

    //*
    //* function MysqlListDBs, Parameter list: 
    //*
    //* Calls mysql_list_dbs.
    //* 
    //* 

    function MysqlListDBs()
    {
        return mysql_list_dbs($this->DBHash[ "Link" ]);
    }

    //*
    //* function GetInsertID, Parameter list: 
    //*
    //* Performs MySql Query, $query. Returns the raw result.
    //* 
    //* 

    function GetInsertID()
    {
        return mysql_insert_id($this->DBHash[ "Link" ]);
    }

    //*
    //* function MySqlError, Parameter list: 
    //*
    //* Prints last mysql erro message.
    //* 
    //* 

    function MySqlError($message)
    {
        die($message.": ".mysql_error());
    }

    //*
    //* function QueryDB, Parameter list: $query,$ignore=FALSE
    //*
    //* Performs MySql Query, $query. Returns the raw result.
    //* 
    //* 

    function QueryDB($query,$ignore=FALSE)
    {
        $query.=";";
        $result = mysql_query($query,$this->DBHash[ "Link" ]);

        global $Queries;
        if (is_array($Queries))
        {
            array_push($Queries,$query);
        }

        //    $this->LogMessage("QueryDB",$query.", ".$this->Action,10);
        if (!$result && !$ignore)
        {
            $message  = $this->ModuleName.', Invalid query: ' . mysql_error() . "\n";
            $message .= 'Whole query: ' . $query;

            $this->Debug=1;
            if ($this->Debug==1)
            {
                $this->PrintCallStack();
            }

            $this->AddMsg($message,10);
            die($message);
        }

        return $result; 
    }

    //*
    //* function QueriesDB, Parameter list: $queries
    //*
    //* Performs MySql Queries, $query. Returns the raw results.
    //* 
    //* 

    function QueriesDB($queries)
    {
        $results=array();
        for ($n=0;$n<count($queries);$n++)
        {
            array_push($results,$this->QueryDB($queries[ $n ]));
        }

        return $results;
    }

    //*
    //* function MysqlFreeResult, Parameter list: $result
    //*
    //* Frees query result $result.
    //* 
    //* 

    function MysqlFreeResult($result)
    {
        mysql_free_result($result);
    }

}

?>