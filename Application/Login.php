<?php

class Login extends LeftMenu
{
    var $AuthHash=array();
    var $Login,$Password,$LoginID,$Privileges;
    var $LoginData=NULL;
    var $PersonIDCol,$CoordIDCol;
    var $PublicAllowed=0;
    var $PublicInterface=0;
    var $EditGroup="";
    var $AdminGroup="";
    var $LoginMessages="Login.php";
    var $NProfiles=0;
    var $LoginPreMessage="";
    var $LoginPostMessage="";
    var $RecoverPasswordTTL=3600;

    //*
    //* function InitLogin, Parameter list: 
    //*
    //* Initilializes Login data
    //*

    function InitLogin($hash)
    {
        $this->AuthHash=$hash;
        $this->AuthHash[ "LoginData" ]=preg_split('/\s*,\s*/',$this->AuthHash[ "LoginData" ]);

        if (isset($this->AuthHash[ "SystemOpen" ]) &&
            !$this->AuthHash[ "SystemOpen" ])
        {
            $this->HtmlHead();
            $this->HtmlDocHead();

            print $this->H
            (
               2,
               $this->GetMessage($this->LoginMessages,"TemporarilyClosed")
            );
            exit();
        }
    }


    //*
    //* function DetectLoginType, Parameter list: 
    //*
    //* Detects login type (Public, Person, Admin), return
    //* values are 2,1 and 0 resp.
    //*

    function DetectLoginType()
    {
        $this->LoginType="Public";
        $res=2;

        if ($this->LoginData)
        {
            $action=$this->GetCGIVarValue("Action");
            $admin=$this->GetCGIVarValue("Admin");
        
           $this->LoginID=$this->LoginData[ "ID" ];
           $this->Login=$this->LoginData[ $this->AuthHash[ "LoginField" ] ];
           if ($action=="Admin" || $admin==1)
           {
               if ($this->MayBecomeAdmin())
               {
                   $this->LoginType="Admin";
                   $res=0;
               }
           }
           elseif ($this->LoginID>0)
           {
               $this->LoginType="Person";
               $res=1;       
           }
        }

        return $res;
    }

    //*
    //* function MayBecomeAdmin, Parameter list: 
    //*
    //* Detects if logged on user may become admin.
    //*

    function MayBecomeAdmin()
    {
        $res=FALSE;

        if ($this->LoginID>0)
        {
            if ($this->LoginData[ "Profile_Admin" ]==2)
            {
                $res=TRUE;
            }
        }

        return $res;
    }

    //*
    //* function GetLoginType, Parameter list: 
    //*
    //* Returns the actual login type, return the strings:
    //* Admin, Person or Public. Calls DetectLoginType first.
    //*

    function GetLoginType()
    {
        if (!preg_match('/^(Admin|Person|Public)$/',$this->LoginType))
        {
            $this->DetectLoginType();
        }

        $ltype="Public";
            if ($this->LoginType=="Admin")  { $ltype="Admin"; }
        elseif ($this->LoginType=="Person") { $ltype="Person"; }

        return $ltype;
    }

    //*
    //* function SetLoginData, Parameter list: $logindata
    //*
    //* Sets LoginData to $logindata. Also sets LoginData, LoginID and Login
    //*

    function SetLoginData($logindata)
    {
        if (is_array($logindata) && count($logindata)>0)
        {
            $this->LoginData=$logindata;

            $this->DetectLoginType();
        }
    }

    //*
    //* function GetLoginData, Parameter list: $data=""
    //*
    //* Returns the array LoginData.
    //*

    function GetLoginData($data="")
    {
        if (empty($data))
        {
            return $this->LoginData;
        }
        elseif (!empty($this->LoginData[ $data ]))
        {
            return $this->LoginData[ $data ];
        }
        
        return "";
    }

    //*
    //* function LoginPostMessage, Parameter list: 
    //*
    //* Returns post message to Login form.
    //*

    function PrintLoginPostMessage()
    {
        print 
            "<TABLE BORDER='1' WIDTH='80%' ALIGN='center'><TR><TD>\n".
            "<DIV CLASS='postloginmsg'>".
            $this->GetMessage
            (
               $this->LoginMessages,
               "LoginPostMessage"
            ).
            "</DIV>\n".
            "</TD></TR></TABLE>";

        if ($this->LoginPostMessage!="")
        {
            if (method_exists($this,$this->LoginPostMessage))
            {
                $method=$this->LoginPostMessage;
                $this->$method();
            }
            else
            {

                $this->H(2,$this->LoginPostMessage);
            }
        }
    }

    function LoginForm($msg1="",$msg2="")
    {
        $this->LoginType="Public";
        $this->LogMessage("LoginForm","-");

        $this->SetCookie("SID","",time()-$this->CookieTTL);
        $this->SetCookie("Admin","",time()-$this->CookieTTL);
        $this->SetCookie("Profile","",time()-$this->CookieTTL);
        $this->SetCookie("ModuleName","",time()-$this->CookieTTL);

        $this->ResetCookieVars();

        $this->HtmlHead();
        $this->HtmlDocHead();

        $login=$this->GetCookieOrPOST("Login");
        $table=array
        (
            array("<B>".
                  $this->GetMessage($this->LoginMessages,"LoginDataTitle").
                  ":</B>",
                  $this->MakeInput("Login",$login,35)),
            array("<B>".
                  $this->GetMessage($this->LoginMessages,"PasswordDataTitle").
                  ":</B>",$this->MakePassword("Password","")),
        );

        $premsg="";
        if ($this->LoginPreMessage!="")
        {
            if (method_exists($this,$this->LoginPreMessage))
            {
                $method=$this->LoginPreMessage;
                $premsg=$this->$method();
            }
            else
            {

                $premsg=$this->H(2,$this->LoginPreMessage);
            }
        }

        $title=$this->GetRealNameKey($this->AuthHash,"LoginFormTitle");
        $title=$this->FilterHash($title,$this->CompanyHash);
        $title=$this->FilterHash($title,$this->HtmlSetupHash);

        $formtitle=$this->GetMessage($this->LoginMessages,"Login");
        print 
            $this->H(1,$title).
            $premsg.
            $this->H(2,$formtitle).
            $this->H(3,$msg1).
            $this->H(4,$this->HtmlStatus).
            $this->StartForm("?Action=Logon").
            $this->HTMLTable("",$table).
            $this->MakeHidden("Logon",1).
            $this->Buttons($this->GetMessage($this->LoginMessages,"LoginSendButton")).
            $this->EndForm().
            $this->H(3,$msg2);


        $this->PrintLoginPostMessage();

        print "<BR><BR>";

        //exit();
    }

    //*
    //* function DoLogoff, Parameter list: $logindata
    //*
    //* Does logoff, that is, resets the SID cookie and other cookies,
    //* writes a messgae containing link to login and exits.
    //*

    function DoLogoff()
    {
        $this->LoginType="Public";
        $this->LogMessage("Logoff","Logged off");
        $this->CookieTTL=time()-60*60; //in the past to disable

        $unit=$this->GetCGIVarValue("Unit");

        $this->SetCookie("SID","",time()-$this->CookieTTL);
        $this->SetCookie("Admin","",time()-$this->CookieTTL);
        $this->SetCookie("Profile","",time()-$this->CookieTTL);
        $this->SetCookie("ModuleName","",time()-$this->CookieTTL);

        //Delete entry en session table
        if (isset($this->SessionData[ "SID" ]))
        {
            $this->DeleteUserSession($this->SessionData[ "SID" ]);
        }

        $this->ResetCookieVars();

        $this->LoginType="Public";
        $this->Profile="Public";

        $args=$this->Query2Hash();
        $args=$this->Hidden2Hash($args);
        $query=$this->Hash2Query($args);

        $this->AddCommonArgs2Hash($args);
        $args[ "Action" ]="Start";

        //Now added, reload as edit, preventing multiple adds
        header("Location: ?".$this->Hash2Query($args));

        exit();
    }

    //*
    //* function PrintPublicLink, Parameter list: $logindata
    //*
    //* If public is allowed, print a link to the public interface
    //*

    function PrintPublicLink()
    {
        if ($this->PublicAllowed)
        {
            print $this->H
            (
               3,
               $this->GetMessage($this->LoginMessages,"AccessPublic").
               $this->Href
               (
                  "?Public=1&Search=1",
                  $this->GetMessage($this->LoginMessages,"ClickHere")
               )
            );
        }
    }

    //*
    //* function RetrieveLoginData, Parameter list: $login=""
    //*
    //* Retrieve login data from AuthHash[ "Table" ], given login.
    //* Tries login and in sequence $login.AuthHash[ "LoginAppend" ]
    //*

    function RetrieveLoginData($login="")
    {
        if (!isset($login) || $login=="") { $login=$_POST[ "Login" ]; }

        $atable=$this->SqlTableName($this->AuthHash[ "Table" ]);

        $where=$this->AuthHash[ "LoginField" ]."='".$login."'";
        $authdata=
            $this->SelectUniqueHash($this->AuthHash[ "Table" ],$where,TRUE);

        //No match, try to add $this->AuthHash[ 'LoginAppend' ]
        if (count($authdata)==0 && isset($this->AuthHash[ "LoginAppend" ]))
        {
            $rlogin=$login.$this->AuthHash[ "LoginAppend" ];
            $where=$this->AuthHash[ "LoginField" ]."='".$rlogin."'";

            $authdata=
                $this->SelectUniqueHash($this->AuthHash[ "Table" ],$where,TRUE);
        }
 
        if (is_array($authdata))
        {
            $rauthdata=array("ID","Login","Password"); //"Privileges","Groups");

            $nprofiles=count($this->ValidProfiles);
            if (preg_grep('/^Public$/',$this->ValidProfiles)) { $nprofiles--; }

            $rauth=array();
            foreach ($rauthdata as $id => $data)
            {
                $rauth[ $data ]=$authdata[ $this->AuthHash[ $data."Field" ] ];
            }

            foreach ($this->AuthHash[ "LoginData" ] as $id => $data)
            {
                $rauth[ $data ]=$authdata[ $data ];
            }

            for ($n=0;$n<count($this->ValidProfiles);$n++)
            {
                if ($this->ValidProfiles[$n]!="Public")
                {
                    $data="Profile_".$this->ValidProfiles[$n];
                    $rauth[ $data ]=1;
                    if (isset($authdata[ $data ]))
                    {
                        $rauth[ $data ]=$authdata[ $data ];
                    }
                }
            }

            return $rauth;
        }
        else
        {
            return array();
        }

        exit();
    }

    //*
    //* function IsAValidPassword, Parameter list: $password,&$message
    //*
    //* Tests whether $password is considered a valid password.
    //*

    function IsAValidPassword($password,&$message)
    {
        $res=TRUE;
        if (strlen($password)<8)
        {
            $message=$this->GetMessage($this->LoginMessages,"Error_PasswordNotAccepted");
            $res=FALSE;
        }

        return $res;
    }

    //*
    //* function ChangePasswordForm, Parameter list: $logindata
    //*
    //* Creates the change password form.
    //*

    function ChangePasswordForm()
    {
        $password=$this->GetPOST("Password");

        $message="";
        if ($this->GetPOST("Update")==1 && $password!="")
        {
            $logindata=$this->LoginData;
            if (count($this->LoginData)>0)
            {
                $rlogin=$this->LoginData[ $this->AuthHash[ "LoginField" ] ];
                $rpassword=$this->LoginData[ $this->AuthHash[ "PasswordField" ] ];

                $rrpassword=$password;
                if ($this->CheckHashKeyValue($this->AuthHash,"MD5",1))
                {
                    $rrpassword=md5($password);
                }

                if ($rrpassword==$rpassword)
                {
                    $pwd1=$this->GetPOST("Password1");
                    $pwd2=$this->GetPOST("Password2");
                    if ($pwd1==$pwd2)
                    {
                        if ($this->IsAValidPassword($pwd1,$message)>=8)
                        {
                            $rpwd=$pwd1;
                            if ($this->AuthHash[ "MD5" ])
                            {
                                $rpwd=md5($rpwd);
                            }

                            $this->MySqlSetItemValue
                            (
                               $this->SqlTableName($this->AuthHash[ "Table" ]),
                               $this->AuthHash[ "IDField" ],
                               $this->LoginData[ "ID" ],
                               $this->AuthHash[ "PasswordField" ],
                               $rpwd
                            );

                            $message=$this->GetMessage($this->LoginMessages,"Password_Updated"); 
                        }
                    }
                    else { $message=$this->GetMessage($this->LoginMessages,"Error_PasswordMismatch"); }
                }
                else { $message=$this->GetMessage($this->LoginMessages,"Error_LoginIvalid1"); }
            }
            else { $message=$this->GetMessage($this->LoginMessages,"Error_LoginIvalid2"); }
        }
        elseif ( $password!="") { $message=$this->GetMessage($this->LoginMessages,"Error_NoPassword"); }

        $this->HtmlHead();
        $this->HtmlDocHead();

        print 
            $this->H(1,$this->GetMessage($this->LoginMessages,"Update_Password_Title")).
            $this->H(4,$message).
            $this->H(2,$this->GetMessage($this->LoginMessages,"Update_Password_Msg")).
            $this->StartForm("?Action=NewPassword").
            $this->HTMLTable
            (
               "",
               array
               (
                  array
                  (
                     $this->B($this->GetMessage($this->LoginMessages,"Login_Name").":"),
                     $this->LoginData[ "Name" ]
                  ),
                  array
                  (
                     $this->B($this->GetMessage($this->LoginMessages,"Login_User").":"),
                     $this->LoginData[ "Email" ]
                  ),
                  array
                  (
                     $this->B($this->GetMessage($this->LoginMessages,"Login_OldPassword").":"),
                     $this->MakePassword("Password","",10)
                  ),
                  array
                  (
                     $this->B($this->GetMessage($this->LoginMessages,"Login_Password1").":"),
                     $this->MakePassword("Password1","",10)
                  ),
                  array
                  (
                     $this->B($this->GetMessage($this->LoginMessages,"Login_Password2").":"),
                     $this->MakePassword("Password2","",10)
                  ),
               )
            ).
            $this->MakeHidden("Update",1).
            $this->MakeHiddenFields().
            $this->Buttons().
            $this->EndForm();


        exit();

    }

    //*
    //* function InitRecoverPasswordForm, Parameter list: $logindata
    //*
    //* Creates solicitation of reset pássword form.
    //*

    function InitRecoverPasswordForm()
    {
        print 
            $this->H(2,$this->GetMessage($this->LoginMessages,"Recover_Password_Title")).
            $this->StartForm().
            $this->H
            (
               3,
               $this->GetMessage($this->LoginMessages,"Recover_Password_SubTitle")." ".
               $this->MakeInput
               (
                   "Recover_Login",
                   $this->GetPOST("Recover_Login"),
                   25
               ).
               $this->Button
               (
                  "submit",
                  $this->GetMessage($this->LoginMessages,"Recover_Password_Button")
               ).
               $this->MakeHidden("Update",1)
            ).
            $this->EndForm();
    }

    //*
    //* function FinalRecoverPasswordForm, Parameter list:
    //*
    //* Final recover password dialogue. Tests if Login and Code are given,
    //* and if they are, prints the newpassword and repeat password fields.
    //* If Update is set, and passwords match, changes the password and resets
    //* the access code.
    //*

    function FinalRecoverPasswordForm()
    {
        $changed=FALSE;
        $message="";

        $login=$this->GetPOST("Login");
        $code=$this->GetPOST("Code");
        if (
            $this->GetPOST("Update")==1
            &&
            $login!="" //only POST, should com from form hidden fields
            &&
            preg_match('/^\S+\@\S+$/',$login)
            &&
            $code!=""
            &&
            preg_match('/^\d+$/',$code)
           )
        {
            $user=$this->MySqlItemValues
            (
               $this->AuthHash[ "Table" ],
               $this->AuthHash[ "LoginField" ],
               $login,
               array("ID",$this->AuthHash[ "LoginField" ],"RecoverCode","RecoverMTime") 
            );

            $dtime=time()-$user[ "RecoverMTime" ];
            if (
                preg_match('/^\d+$/',$user[ "ID" ])
                &&
                $user[ "ID" ]>0
                &&
                $code==$user[ "RecoverCode" ]
                &&
                $dtime>0
                &&
                $dtime<$this->RecoverPasswordTTL
               )
            {
                $pwd1=$this->GetPOST("Password1");
                $pwd2=$this->GetPOST("Password2");
                if ($pwd1==$pwd2)
                {
                    if ($this->IsAValidPassword($pwd1,$message)>=8)
                    {
                        $rpwd=$pwd1;
                        if ($this->AuthHash[ "MD5" ])
                        {
                            $rpwd=md5($rpwd);
                        }

                        $user[ $this->AuthHash[ "PasswordField" ] ]=$rpwd;
                        $user[ "RecoverCode" ]=0;
                        $user[ "RecoverMTime" ]=0;

                        $this->MySqlSetItemValues
                        (
                           $this->AuthHash[ "Table" ],
                           array
                           (
                              $this->AuthHash[ "PasswordField" ],
                              "RecoverCode",
                              "RecoverMTime"
                           ),
                           $user
                        );

                        $this->SendPasswordRecoveredMail($user);

                        print $this->H
                        (
                           4,
                           $this->GetMessage($this->LoginMessages,"Password_Updated")
                        );
                        exit();
                    }
                    else { $message=$this->GetMessage($this->LoginMessages,"Error_PasswordNotAccepted"); }
                }
                else { $message=$this->GetMessage($this->LoginMessages,"Error_PasswordMismatch"); }
            }
            else { $message=$this->GetMessage($this->LoginMessages,"Error_InvalidCode"); }
        }

        print 
            $this->H(1,$this->GetMessage($this->LoginMessages,"Update_Password_Title")).
            $this->H(4,$message).
            $this->H(2,$this->GetMessage($this->LoginMessages,"Update_Password_Msg")).
            $this->StartForm("?Action=Recover").
            $this->HTMLTable
            (
               "",
               array
               (
                  array
                  (
                     $this->B($this->GetMessage($this->LoginMessages,"Login_User").":"),
                     $this->GetGETOrPOST("Login")
                  ),
                  array
                  (
                     $this->B($this->GetMessage($this->LoginMessages,"Login_Password1").":"),
                     $this->MakePassword("Password1","",10)
                  ),
                  array
                  (
                     $this->B($this->GetMessage($this->LoginMessages,"Login_Password2").":"),
                     $this->MakePassword("Password2","",10)
                  ),
               )
            ).
            $this->MakeHidden("Login",$this->GetGETOrPOST("Login")).
            $this->MakeHidden("Unit",$this->Unit).
            $this->MakeHidden("Code",$this->GetGETOrPOST("Code")).
            $this->MakeHidden("Update",1).
            $this->MakeHiddenFields().
            $this->Buttons().
            $this->EndForm();


        exit();

    }


    //*
    //* function SendRecoverPasswordMail, Parameter list: $user
    //*
    //* Sends email as a response to a password reset request.
    //*

    function  SendRecoverPasswordMail($user)
    {
        $user[ "Recover_Link" ]=preg_replace
        (
            '/index\.php/',
            "",
            $this->ScriptExec
            (
               "Action=Recover&".
               "Unit=".$this->Unit."&".
               "Login=".$this->GetPOST("Recover_Login")."&".
               "Code=".$user[ "RecoverCode" ]
            )
        );
            
        $this->SendGMail
        (
           $user[ "Email" ],
           $this->Filter
           (
              $this->GetMessage($this->LoginMessages,"Recover_Password_Mail_Subject"),
              $user
           ),
           $this->Filter
           (
              $this->GetMessage($this->LoginMessages,"Recover_Password_Mail_Body"),
              $user
           ),
           "",
           "",
           "",
           array(),
           TRUE
        );
    }

    //*
    //* function SendPasswordRecoveredMail, Parameter list: $user
    //*
    //* Sends email informing that password has been changed.
    //*

    function  SendPasswordRecoveredMail($user)
    {
        $this->SendGMail
        (
           $user[ "Email" ],
           $this->Filter
           (
              $this->GetMessage($this->LoginMessages,"Password_Recovered_Mail_Subject"),
              $user
           ),
           $this->Filter
           (
              $this->GetMessage($this->LoginMessages,"Password_Recovered_Mail_Body"),
              $user
           ),
           "",
           "",
           "",
           array(),
           TRUE
        );
    }

    //*
    //* function TestRecoverPassword, Parameter list: 
    //*
    //* Creates solicitation of reset pássword form.
    //*

    function TestRecoverPassword()
    {
        $user=$this->SelectUniqueHash
        (
           $this->AuthHash[ "Table" ],
           array
           (
              "Email" => $this->GetPOST("Recover_Login")
           ),
           TRUE
        );

        if (
              preg_match('/^\d+$/',$user[ "ID" ])
              &&
              $user[ "ID" ]>0
           )
        {
            $user[ "RecoverCode" ]=rand().rand();
            $user[ "RecoverMTime" ]=time();
            $this->SendRecoverPasswordMail($user);

            $this->MySqlSetItemValues
            (
               $this->AuthHash[ "Table" ],
               array("RecoverCode","RecoverMTime"),
               $user
            );
        }

        print 
            $this->H(3,$this->GetMessage($this->LoginMessages,"Recover_Password_Mail_Message"));
    }


    //*
    //* function HandleRecover, Parameter list:
    //*
    //* Handles reset password procedure.
    //*

    function HandleRecover()
    {
        $this->HtmlHead();
        $this->HtmlDocHead();

        if (
            ($this->GetPOST("Update")!=1)
            ||
            $this->GetPOST("Recover_Login")==""
           )
        {
            if (
                $this->GetGETOrPOST("Login")!=""
                &&
                preg_match('/^\S+\@\S+$/',$this->GetGETOrPOST("Login"))
                &&
                $this->GetGETOrPOST("Code")!=""
                &&
                preg_match('/^\d+$/',$this->GetGETOrPOST("Code"))
               )
            {
                $this->FinalRecoverPasswordForm();
            }
            else
            {
                $this->InitRecoverPasswordForm();
            }
        }
        else
        {
            $this->TestRecoverPassword();
        }

        exit();

    }


    //*
    //* function FindLoggedID, Parameter list: 
    //*
    //* Finds and returns login ID logged in.
    //*

    function FindLoggedID()
    {
        $loginid=0;
        if ($this->LoginID!="" && $this->LoginID>0)
        {
            $loginid=$this->LoginID;
        }
        elseif ($this->LoginData[ "ID" ]!="" && $this->LoginData[ "ID" ]>0)
        {
            $loginid=$this->LoginData[ "ID" ];
        }

        if ($loginid>0)
        {
            $this->LoginID=$loginid;
            $this->LoginData[ "ID" ]=$loginid;
        }

        return $loginid;
    }

    //*
    //* function TransferLoginData, Parameter list: $object
    //*
    //* Centralized way of transferring login data to $object.
    //*

    function TransferLoginData($object)
    {
        $object->LoginType=$this->LoginType;
        $object->LoginID=$this->LoginID;

        $object->LoginData=$this->LoginData;
        $object->AuthHash=$this->AuthHash;

        foreach ($this->ExtraPathVars as $id => $data)
        {
            $object->$data=$this->$data;      
        }
    }

    //*
    //* function BadGuy, Parameter list: $object
    //*
    //* Something's wrong, are we expired or are we being spoofed?
    //* Write message and exit.
    //*

     function BadGuy()
    {
        exit();
        $this->HtmlHead();
        $this->HtmlDocHead();

        $msg=$this->GetMessage($this->LoginMessages,"Expired");
        print $this->H(2,
                       $msg."<BR>".
                       $this->Href("?Login=1")
                      );

        $this->PrintPublicLink();

        $this->SetCookie("SID","",time()-$this->CookieTTL);
        $this->ResetCookieVars();
        exit();
    }


    //*
    //* function ShiftUser, Parameter list: $user
    //*
    //* Attempt to shif user in Session table.
    //*

     function ShiftUser($user,$doit=FALSE,$unset=FALSE)
    {
        if (!$doit && $this->GetPOST("Shift")!=1) { return; }
        $this->InitModule("People");

        $person=$this->PeopleObject->SelectUniqueHash
        (
           "",
           $this->PeopleObject->GetRealWhereClause("ID='".$user."'")
        );

        if (empty($person))
        {
            die("Not allowed...");
        }

        $user=$person[ "Email" ];
        $logindata=$this->RetrieveLoginData($user);

        if (is_array($logindata) && count($logindata)>0)
        {
            $session=$this->SessionData;

            if ($unset)
            {
                $session[ "SULoginID" ]=$this->LoginData[ "ID" ];
            }
            else
            {
                $session[ "SULoginID" ]=0;
            }

            $session[ "LoginID" ]=$logindata[ "ID" ];
            $session[ "Login" ]=$user;
            $session[ "LoginName" ]=$logindata[ "Name" ];
            $this->MySqlUpdateItem
            (
               $this->GetSessionsTable(),
               $session,
               "SID='".$session[ "SID" ]."'"
            );

            $this->ConstantCookieVars=preg_grep
            (
               '/^Profile$/',
               $this->ConstantCookieVars,
               PREG_GREP_INVERT
            );

            array_push($this->ConstantCookieVars,"SID");
            $this->ResetCookieVars();

            $args=$this->Query2Hash();
            $args=$this->Hidden2Hash($args);
            $query=$this->Hash2Query($args);

            $this->AddCommonArgs2Hash($args);
            $args[ "Action" ]="Start";

            //Now added, reload as edit, preventing multiple adds
            header("Location: ?".$this->Hash2Query($args));
            exit();
        }
    }


    //*
    //* function ShiftUserForm, Parameter list: 
    //*
    //* Presents Form for shifting user (admin only).
    //*

    function ShiftUserForm()
    {
        $msg="";
        if ($this->GetPOST("Shift")==1)
        {
            $user=$this->GetPOST("User");
            $this->ShiftUser($user);

            //Still here, user id invalid.
            $msg=$this->H(4,"Usário Inválido, tente de novo");
        }

        $this->InitModule("People");

        $peoplewhere=array();
        if ($this->Unit && !empty($this->PeopleObject->ItemData[ "Unit" ]))
        {
            $peoplewhere[ "Unit" ]=$this->Unit;
        }

        $people=$this->PeopleObject->SelectHashesFromTable
        (
           "",
           $this->PeopleObject->GetRealWhereClause($peoplewhere),
           array("ID","Name","Email","Profile_Admin"),
           FALSE,
           "Name"
        );

        $people=$this->PeopleObject->SortList($people,array("Name","ID"));

        $selectids=array();
        $selectnames=array();
        foreach ($people as $person)
        {
            if ($person[ "Profile_Admin" ]==2) { continue; } //newer su to admin!

            $name=preg_replace('/^\s+/',"",$person[ "Name" ])." (".$person[ "Email" ].")";

            array_push($selectids,$person[ "ID" ]);
            array_push($selectnames,$name);
        }

        $this->HtmlHead();
        $this->HtmlDocHead();

        print
            $this->H(2,"Trocar Usuário").
            $msg.
            $this->StartForm().
            $this->HtmlTable
            (
               "",
               array
               (
                  array
                  (
                     $this->B("Usuário:"),
                     $this->MakeSelectField("User",$selectids,$selectnames)
                  ),
                  array
                  (
                     $this->MakeHidden("Shift",1).
                     $this->Button("submit","GO")
                  ),
               )
            ).
            $this->EndForm().
            "";
    }

    //*
    //* function HandleUnSU, Parameter list: 
    //*
    //* Attempt to shif user in Session table.
    //*

    function HandleUnSU()
    {
        if ($this->SessionData[ "SULoginID" ]>0)
        {
            $user=$this->SessionData[ "SULoginID" ];
            $this->ShiftUser($user,TRUE);
        }
    }
}


?>