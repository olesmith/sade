<?php

//This MUST be global
global $SessionInitialized;
$SessionInitialized=0;

class Session extends Login
{
    var $SessionsTable="Sessions";
    var $MayCreateSessionTable=FALSE;
    var $MaxLoginAttempts=5;
    var $BadLogonRegistered=0;
    var $NoInitSession=0;
    var $SessionData=array();
    var $SessionMessages="Session.php";
    var $CookieTTL=3600;

    var $AuthData=array
        (
          "ID" => array
           (
              "Name"   => "ID",
              "Sql"    => "INT NOT NULL PRIMARY KEY AUTO_INCREMENT",
              "Public" => "0",
              "Person" => "0",
              "Admin"  => "1",
           ),
           "Login" => array
           (
              "Name"   => "Login",
              "Sql"    => "VARCHAR(55)",
              "Public" => "0",
              "Person" => "0",
              "Admin"  => "1",
           ),
           "LoginID" => array
           (
              "Name"   => "Login ID",
              "Sql"    => "INT",
              "Public" => "0",
              "Person" => "0",
              "Admin"  => "1",
           ),
           "LoginName" => array
           (
              "Name"   => "Nome",
              "Sql"    => "VARCHAR(255)",
              "Public" => "0",
              "Person" => "0",
              "Admin"  => "1",
           ),
           "SID" => array
           (
              "Name"   => "Login",
              "Sql"    => "VARCHAR(55)",
              "Public" => "0",
              "Person" => "0",
              "Admin"  => "1",
           ),
           "IP" => array
           (
              "Name"   => "Login",
              "Sql"    => "VARCHAR(55)",
              "Public" => "0",
              "Person" => "0",
              "Admin"  => "1",
           ),
           "CTime" => array
           (
              "Name" => "Início da Sessão",
              "Sql" => "INT",
              "Public" => "0",
              "Person" => "0",
              "Admin" => "1",
           ),
           "ATime" => array
           (
              "Name"   => "Último Autenticação",
              "Sql"    => "INT",
              "Public" => "0",
              "Person" => "0",
              "Admin"  => "1",
           ),
           "Authenticated" => array
           (
              "Name"   => "Autenticado",
              "Sql"    => "ENUM",
              "Values" => array("Não","Sim"),
              "Public" => "0",
              "Person" => "0",
              "Admin"  => "1",
           ),
           "LastAuthenticationAttempt" => array
           (
              "Name"     => "Última Autenticação Falhado",
              "Sql"      => "INT",
              "TimeType" => 1,
              "Public"   => "0",
              "Person"   => "0",
              "Admin"    => "1",
           ),
           "LastAuthenticationSuccess" => array
           (
              "Name"     => "Última Autenticão com Êxito",
              "Sql"      => "INT",
              "TimeType" => 1,
              "Public"   => "0",
              "Person"   => "0",
              "Admin"    => "1",
           ),
           "NAuthenticationAttempts" => array
           (
              "Name"     => "Nº de Tentativas",
              "Sql"      => "INT",
              "TimeType" => 1,
              "Public"   => "0",
              "Person"   => "0",
              "Admin"    => "1",
           ),
           "SULoginID" => array
           (
              "Name"   => "SU'ed Login ID",
              "Sql"    => "INT",
              "Public" => "0",
              "Person" => "0",
              "Admin"  => "1",
           ),
        );


    //*
    //* function Session, Parameter list: 
    //*
    //* Constructur
    //*
    
    function Session()
    {
    }

    //*
    //* function Session, Parameter list: 
    //*
    //* Constructur
    //*
    
    function SessionAddMsg($msg)
    {
        if (TRUE)
        {
            $this->AddMsg($msg);
        }
    }

    //*
    //* function InitSession, Parameter list: 
    //*
    //* Initilializes session DB
    //*

    function InitSession($hash=array())
    {
        $datas=array_keys($this->AuthData);

        $stable=$this->GetSessionsTable();

        if ($this->MayCreateSessionTable)
        {
            $this->CreateTable($stable);
        }


        if ($this->MySqlIsTable($stable))
        {
            $this->UpdateDBFields($stable,$datas,$this->AuthData);
        }
        else
        {
            die("Session not Allowed!<BR>");
        }

        if (!$this->MySqlIsTable($stable))
        {
            die("No session table and unable to create");
        }

        //Keep sessions table clean, delete olds
        $time=time()-60*60;
        $query="DELETE FROM ".$stable." WHERE ATIME<".$time;
        $this->QueryDB($query);

        //Must be global!
        global $SessionInitialized;
        if ($SessionInitialized==0 && $this->NoInitSession==0)
        {
            $this->InitUserSession();
            $SessionInitialized=1;
        }

        $this->PostInitSession();
    }    

    //*
    //* function PostInitSession, Parameter list: $logindata=array()
    //*
    //* Does nothing, avaliable to be overriden, for actions to do right after
    //* user session has been established.
    //*

    function PostInitSession($logindata=array())
    {
    }

    //*
    //* function GetSessionsTable, Parameter list: 
    //*
    //* Returns name of Sessions table
    //*

    function GetSessionsTable()
    {
        return $this->SqlTableName($this->SessionsTable);
    }

    //*
    //* function TestUserSID, Parameter list: $sid
    //*
    //* Tests if SID privided by cookies is valid.
    //* Returns TRUE on success and FALSE on failure.
    //* On sucess SessionData hash is populated.
    //*

    function TestUserSID($sid)
    {
        if (preg_match('/^\d+$/',$sid) && $sid>0)
        {
            $session=$this->SelectUniqueHash
            (
                $this->GetSessionsTable(),
                "SID='".$sid."'",
                TRUE
            );

            if (is_array($session) && count($session)>0)
            {
                if ($session[ "SID" ]==$sid)
                {
                    $time=time();
                    if ($session[ "Authenticated" ]==2)
                    {
                        if ($time-$session[ "ATime" ]<=60*60)
                        {
                            if ($session[ "IP" ]==$_SERVER[ "REMOTE_ADDR" ])
                            {
                                $this->SessionData=$session;
                                return TRUE;
                            }
                            else
                            {
                                $this->BadGuy();
                                exit();
                            }
                        }
                        else
                        {
                            $this->HtmlStatus=
                                $this->GetMessage($this->SessionMessages,"Expired");
                            $this->SessionAddMsg("Logon expired");
                            $this->LoginForm("Logon Expired",1,"");
                            exit();
                        }
                    }
                    else
                    {
                        $this->SessionAddMsg("Not authenticated");
                        $this->LoginForm("Not auth",1,"");
                        exit();
                    }
                }
            }
            else
            {
                $this->SessionAddMsg("Invalid session: $session");
            }
        }
        else
        {
            $this->SessionAddMsg("Invalid format: $sid");
        }

        return FALSE;
    }


    //*
    //* function RegisterBadLogon, Parameter list: 
    //*
    //* Registers bad logon attempt.
    //*

    function RegisterBadLogon()
    {
        if ($this->BadLogonRegistered!=0) { return; }

        $login=strtolower($this->GetPOST("Login"));

        $time=time();

        //All sessions with attempted Login OR IP address
        $where="Login='".$login."' OR IP='".$_SERVER[ "REMOTE_ADDR" ]."'";
        $where="Login='".$login."'";
        $sessions=$this->SelectHashesFromTable($this->GetSessionsTable(),$where);

        if (count($sessions)>0)
        {
            foreach ($sessions as $id => $session)
            {
                $session[ "Login" ]=$login;
                $session[ "IP" ]=$_SERVER[ "REMOTE_ADDR" ];
                $session[ "ATime" ]=$time;
                $session[ "LastAuthenticationAttempt" ]=$time;
                $session[ "Authenticated" ]=1; //1, is not auth, enum!
                $session[ "LastAuthenticationSuccess" ]=-1;
                $session[ "NAuthenticationAttempts" ]++;

                $this->MySqlUpdateItem
                (
                   $this->GetSessionsTable(),
                   $session,$where
                );
                $this->SessionAddMsg("Removing bad session: ".$session[ "Login" ]);
            }
        }
        else
        {                    
            $session=array
            (
                "SID"       => -1,
                "LoginID"   => $this->LoginData[ "ID" ],
                "Login"     => $login,
                "LoginName" => $this->LoginData[ "Name" ],
                "CTime"     => $time,
                "ATime"     => $time,
                "IP"        => $_SERVER[ "REMOTE_ADDR" ],
                "Authenticated"  => 1, //1, is not auth, enum!
                "LastAuthenticationAttempt"  => $time,
                "LastAuthenticationSuccess"  => -1,
                "NAuthenticationAttempts"  => 1,
            );

            $this->MySqlInsertItem($this->GetSessionsTable(),$session);
        }

        $this->BadLogonRegistered=1;
     }



    //*
    //* Testes authentication
    //*

    function Authenticate()
    {
        $login=strtolower($this->GetPOST("Login"));
        if ($login!="")
        {
            $where="Login='".$login."'";
            $sessions=$this->SelectHashesFromTable($this->GetSessionsTable(),$where);

            $session=NULL;
            if (count($sessions)==1)
            {
                $session=$sessions[0];
            }

            if (is_array($session) && 
                $session[ "NAuthenticationAttempts" ]>=$this->MaxLoginAttempts)
            {
                $this->HtmlHead();
                $this->HtmlDocHead();

                $msg1=$this->GetMessage($this->SessionMessages,
                                        "SessionBlocked1");
                $msg2=$this->GetMessage($this->SessionMessages,
                                        "SessionBlocked2");

                $msg1=preg_replace('/#MaxLoginAttempts/',$this->MaxLoginAttempts,$msg1);
                $msg2=preg_replace('/#MaxLoginAttempts/',$this->MaxLoginAttempts,$msg2);
                $this->RegisterBadLogon();

                print
                    $this->H(2,$msg1).
                    $this->H(3,$msg2);

                exit();
            }
            elseif (count($sessions)>1)
            {
                $this->MySqlDeleteItems($this->GetSessionsTable(),$where);

                $this->SetCookie("SID","",time()-$this->CookieTTL);
                $this->ResetCookieVars();
                $this->LoginForm("More than one LoginID Session!!");

                exit();
            }

            $logindata=$this->RetrieveLoginData($login);
            $this->PostInitSession($logindata);
 
            if (count($logindata)>0)
            {
                $rlogin=$logindata[ "Login" ];
                if ($rlogin==$login)
                {
                    $password=$this->GetPOST("Password");

                    $rrpassword=$password;
                    if ($this->AuthHash[ "MD5" ]==1)
                    {
                        $rrpassword=md5($password);
                    }

                    $rpassword=$logindata[ "Password" ];
                    if ($rrpassword==$rpassword)
                    {
                        $this->SetLoginData($logindata);
                        return TRUE;
                    }
               }

                $this->HtmlStatus=
                    $this->GetMessage($this->SessionMessages,
                                      "InvalidPassword");
                $this->LogMessage("Authentication","Invalid login: ".$rlogin);
                $this->RegisterBadLogon();
            }
            else
            {
                $this->HtmlStatus=
                    $this->GetMessage($this->SessionMessages,
                                      "InvalidPassword");
            }

            $this->RegisterBadLogon();
        }

        return 0;
    }

    //*
    //* function GoHTTPS, Parameter list: 
    //*
    //* Redirects to HTTPS.
    //*

    function GoHTTPS()
    {
        return;
        if (!isset($_SERVER[ "HTTPS" ]) &&
            ($this->ServerName()!='abel' &&
             $this->ServerName()!='gauss' &&
             $this->ServerName()!='ipredes.dyn-o-saur.com')
            )
        {
            header("Location: https://".$this->ServerName()."/".
                   $this->ScriptPath()."/".
                   $this->ScriptName().
                   $this->ScriptPathInfo()."?".
                   $this->QueryString());

            exit();
        }
    }

    //*
    //* function DeleteUserSession, Parameter list: $sid
    //*
    //* Deletes session associated by SID $sid, default being 
    //* $this->SessionData[ "SID" ].
    //*

    function DeleteUserSession($sid="")
    {
        if ($sid=="") { $sid=$this->SessionData[ "SID" ]; }

        //Delete entry en session table
        $this->MySqlDeleteItem($this->GetSessionsTable(),$sid,"SID");

        $this->SetCookie("SID","",time()-$this->CookieTTL);
    }

    //*
    //* function DeletePreviousUserSessions, Parameter list: $loginid
    //*
    function DeletePreviousUserSessions($loginid="")
    {
        if ($loginid=="") { $loginid=$this->LoginData[ "ID" ]; }

        //Delete all entries associated with
        //LoginID $loginid in session table
        $this->MySqlDeleteItems($this->GetSessionsTable(),"LoginID='".$loginid."'");

    }

    //*
    //* function RegisterKnownSIDSession, Parameter list: $sid
    //*
    //* Register $sid as session SID, updates session table and 
    //* sets LoginData.
    //*

    function RegisterKnownSIDSession($sid)
    {
        $uid=$this->SessionData[ "LoginID" ];
        $where=$this->AuthHash[ "IDField" ]."='".$uid."'";

        $authdata=
            $this->SelectUniqueHash($this->AuthHash[ "Table" ],$where,TRUE);
               
        $sid=$this->SessionData[ "SID" ];

        $time=time();
        $this->MySqlSetItemValue
        (
           $this->GetSessionsTable(),
           "ID",$this->SessionData[ "ID" ],
           "ATime",$time
        );

        $this->SetCookie("SID",$sid,time()+$this->CookieTTL);
        $this->SetLoginData($authdata);
     }


    //*
    //* function RegisterNewSIDSession, Parameter list: 
    //*
    //* Register $sid as session SID, updates session table and 
    //* sets LoginData.
    //*

    function RegisterNewSIDSession()
    {
        $this->DeletePreviousUserSessions($this->LoginData[ "ID" ]);

        $sid=rand().rand().rand();
        $time=time();
        $session=array
        (
           "SID"       => $sid,
           "LoginID"   => $this->LoginData[ "ID" ],
           "Login"     => $this->LoginData[ "Login" ],
           "LoginName" => $this->LoginData[ "Name" ],
           "CTime"     => $time,
           "ATime"     => $time,
           "IP"        => $_SERVER[ "REMOTE_ADDR" ],
           "Authenticated"  => 2,
           "LastAuthenticationAttempt"  => $time,
           "LastAuthenticationSuccess"  => $time,
           "NAuthenticationAttempts"  => 0,
        );

        $this->MySqlInsertItem($this->GetSessionsTable(),$session);
        $this->SetCookie("SID",$sid,$time+$this->CookieTTL);
    }

    //*
    //* function DeleteSIDSession, Parameter list: $sid
    //*
    //* Deletes $sid in sessions the table.
    //*

    function DeleteSIDSession($sid)
    {
        $this->SetCookie("SID","",time()-$this->CookieTTL);

        $this->MySqlDeleteItems($this->GetSessionsTable(),"SID='".$sid."'");

        $msg=$this->GetMessage($this->SessionMessages,"Expired");
        $this->LoginForm($msg);
    }

    //*
    //* function InitUserSession, Parameter list: 
    //*
    //* SID set:
    //*  - Call TestUserSID, verifying SID validity
    //*    - if valid: set logindata and sessiondata and return
    //*    - else: BaddGuys, exit!
    //* else:
    //* If logon requested or required:
    //* - If given, validate logondata:
    //*   - if valid:
    //*      - create SID and session
    //*      - update Session DB
    //*      - Retrieve logindata from session data
    //*   - else:
    //*      - call LoginForm and exit
    //*

    function InitUserSession()
    {
        $this->GoHTTPS();
        if (isset($_COOKIE[ "SID" ]))
        {
            $sid=$this->GetCookie("SID");
            if ($this->TestUserSID($sid))
            {
               $this->RegisterKnownSIDSession($sid);
               $SessionIntialized=1;
            }
            else
            {
                $this->DeleteSIDSession($sid);
                exit();
            }
        }
        else
        {
            $authok=0;
            $action=$this->GetGETOrPOST("Action");

            if ($action=="Logon")
            {
                //Logon requested
                $authok=$this->Authenticate();
            }
            elseif ($this->PublicAllowed)
            {
                return;
            }
            else
            {
                //Logon required
                $authok=$this->Authenticate();
            }

            if ($authok>0)
            {
                $this->RegisterNewSIDSession();
                $SessionIntialized=1;
            }
            elseif ($this->GetPOST("Login") || $this->GetPOST("Password"))
            {
                $this->HtmlStatus=
                    $this->GetMessage($this->SessionMessages,
                                      "InvalidPassword");
                $this->RegisterBadLogon();
            }
        }
    }
}

?>