<?php

class Consults extends Common
{

    //*
    //* Variables of Consults class:

    var $PersonsReadData=array
    (
       "ID",
       "Name",
       "PRN","SUS","PRN1","PRN2","PRN3",
       "Phone","WorkPhone","Cell",
       "Street","Area","City",
    );
    var $PersonsTableData=array("No","AddConsult","Name","PRN","SUS","Phone","Street","Area","City",);
    var $PersonTableData=array
    (
       "Name",
       "PRN","SUS","BirthDay","Age","Sex","Race","Civil","City",
       "WorkAddress","Profession","Phone","WorkPhone","Cell",
       "Street","Area","City","Father","Mother"
     );

    var $TargetReadData=array("ID","Name","PRN","SUS","Phone","Cell","Street","Area",);
    //var $PersonSearchData=array("Name","PRN","SUS",);


    //*
    //* function Protocols, Parameter list: $args=array()
    //*
    //* Constructor.
    //*

    function Consults($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array("Name","Department","Status","StatusDate");
        $this->Sort=array("Name");
    }


    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
        foreach (array("Show","Edit","Print") as $action)
        {
            $this->Actions[ $action ][ "AccessMethod" ]="CheckEditAccess";
        }

        $this->Actions[ "Delete" ][ "AccessMethod" ]="CheckDeleteAccess";

        $this->AddFixedValues[ "Department" ]=$this->ApplicationObj->GetLoginData("Department");
        foreach ($this->GetListOfProfiles() as $profile)
        {
            if ($profile!="Public")
            {
                foreach (array("MTime","CTime") as $data)
                {
                    $this->ItemData[ $data ][ $profile ]=1;
                }
            }
        }
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->Actions[ "Edit" ][ "Name" ]="Consultar";
        $this->Actions[ "Edit" ][ "ShortName" ]="Consultar";
        $this->Actions[ "Edit" ][ "Title" ]="Consultar";
        $this->Actions[ "Edit" ][ "Icon" ]="time.png";
    }

    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Postprocesses and returns $item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if ($module!="Consults")
        {
            return $item;
        }

        return $item;
    }


    //*
    //* function GetRealWhereClause, Parameter list: $where="",$data=""
    //*
    //* Returns the real overall where clause for Consults.
    //*

    function GetRealWhereClause($where="",$data="")
    {
        if (!empty($where) && !is_array($where))
        {
            $where=$this->SqlClause2Hash($where);
        }

        if ($this->LoginType=="Person")
        {
            $where[ "Department" ]=$this->LoginData[ "Department" ];
        }

        return $where;
    }

    //*
    //* function CheckEditAccess, Parameter list: $item
    //*
    //* Checks if $item may be edited. Admin may -
    //* and Person, if LoginData[ "ID" ]==$item[ "ID" ]
    //*

    function CheckEditAccess($item)
    {
        $res=FALSE;
        if (preg_match('/^(Admin)$/',$this->Profile))
        {
            $res=TRUE;
        }
        elseif (preg_match('/^(Secretary|Medical|Nurse|Receptionist)$/',$this->Profile))
        {
            if (!empty($item[ "Department" ]) && $item[ "Department" ]==$this->LoginData[ "Department" ])
            {            
                $res=TRUE;
            }
        }

        return $res;
    }

    //*
    //* function CheckDeleteAccess, Parameter list: $item
    //*
    //* Checks if $item may be deleted. Admin may -
    //* and Person, if LoginData[ "ID" ]==$item[ "ID" ]
    //*

    function CheckDeleteAccess($item)
    {
        $res=$this->CheckEditAccess($item);
        if ($res)
        {
            if (
                $item[ "StatusDate" ]!=0
                ||
                empty($item[ "Owner" ])
                ||
                $item[ "Owner" ]!=$this->LoginData[ "ID" ]
               )
            {
                $res=FALSE;
            }
        }

        return $res;
    }


    //*
    //* function PersonsSearchTable, Parameter list: 
    //*
    //* Generates People Consult search table.
    //*

    function PersonsSearchTable()
    {
        $ors=array();
        foreach ($this->ApplicationObj->ProtocolsObject->GetTargetSearchData($this->ApplicationObj->Protocol) as $data)
        {
            $value=$this->GetPOST($data);
            if ($data=="Name")
            {
                $value=preg_replace('/\s+/',"%",$value);
            }
            elseif ($data=="PRN")
            {
                $value=preg_replace('/[^\d]/',"%",$value);
            }

            if (!empty($value))
            {
                array_push($ors,$data." LIKE '%".$value."%'");
            }
        }

        //Pretend Consult Action to be part of People object actions, so we can use it's item table
        $this->ApplicationObj->PeopleObject->Actions[ "AddConsult" ]=$this->Actions[ "AddConsult" ];
        $this->ApplicationObj->PeopleObject->Actions[ "AddConsult" ][ "HrefArgs" ].=
            "&Protocol=".$this->ApplicationObj->Protocol[ "ID" ].
            "&Place=".$this->ApplicationObj->Place[ "ID" ].
            "";

        $this->ApplicationObj->Persons=$this->ApplicationObj->PeopleObject->SelectHashesFromTable
        (
           "",
           join(" AND ",$ors),
           $this->PersonsReadData,
           FALSE,
           "Name"
        );

        foreach ($this->ApplicationObj->Persons as $i => $person)
        {
            $this->ApplicationObj->Persons[ $i ][ "PID" ]=$person[ "ID" ];
        }

        $table=array();
        if (count($this->ApplicationObj->Persons)>=1)
        {
            $table=$this->ApplicationObj->PeopleObject->ItemsTable
            (
               "",
               0,
               $this->PersonsTableData,
               $this->ApplicationObj->Persons,
               array(),
               $this->B
               (
                  $this->ApplicationObj->PeopleObject->GetDataTitles($this->PersonsTableData)
               )
            );

            array_unshift($table,$this->H(5,"Pessoas Conformando com a Pesquisa"));
        }
        else
        {
            array_unshift($table,$this->H(4,"Nenhuma Pessoa Conformando com a Pesquisa..."));
        }
            
        return
            $this->HtmlTable("",$table,array("BORDER" => 1,"ALIGN" => 'center')).
            "";
    }

    //*
    //* function GetTargetPersonDataTitle, Parameter list: 
    //*
    //* Returns title of "TargetProfile" data.
    //*

    function GetTargetPersonDataTitle()
    {
        $targetkey=$this->ApplicationObj->Protocol[ "TargetProfile" ]-1;
        return
            $this->ApplicationObj->ProtocolsObject->ItemData[ "TargetProfile" ][ "ValueOptions" ][ $targetkey ][ "Name" ];
    }

    //*
    //* function GetTargetPersons, Parameter list:
    //*
    //* Returns the potencial target persons as an array.
    //*

    function GetTargetPersons()
    {
        $targetkey=$this->ApplicationObj->Protocol[ "TargetProfile" ]-1;
        $targetkey=
            $this->ApplicationObj->ProtocolsObject->ItemData[ "TargetProfile" ][ "ValueOptions" ][ $targetkey ][ "Key" ];

        return $this->ApplicationObj->PeopleObject->SelectHashesFromTable
        (
           "",
           array($targetkey => 2),
           $this->ApplicationObj->PersonsReadData,
           FALSE,
           "Name"
        );
    }

    //*
    //* function MakeTargetSelect, Parameter list: $protocol,$place
    //*
    //* Generates select for Consults search for Person in $_GET[ "ID" ]..
    //*

    function MakeTargetSelect()
    {
        $this->ApplicationObj->ReadProtocol();

        return $this->ApplicationObj->ProtocolsObject->TargetSelect
        (
           $this->ApplicationObj->TargetSearchField,           
           $this->ApplicationObj->Protocol,
           $this->GetCGIVarValue($this->ApplicationObj->TargetSearchField)
        );
    }

    //*
    //* function MakePersonSelect, Parameter list: $protocol,$place
    //*
    //* Generates select for Consults search for Person in $_GET[ "ID" ]..
    //*

    function MakePersonSelect()
    {
        $this->ApplicationObj->ReadProtocol();
        $this->ApplicationObj->ProtocolsObject->Protocol=$this->ApplicationObj->Protocol;

        return $this->ApplicationObj->ProtocolsObject->TargetPersonSelect
        (
           "Person",           
           $this->ApplicationObj->Protocol,
           1
        );
    }


    //*
    //* function ReadTarget, Parameter list:
    //*
    //* Reads target as from POST Target.
    //*

    function ReadTarget()
    {
        $id=$this->GetPOST($this->ApplicationObj->ProtocolsObject->TargetSearchField);
        $id=preg_replace('/[^\d]/',"",$id);

        $this->ApplicationObj->Target=$this->ApplicationObj->PeopleObject->SelectUniqueHash
        (
           "",
           array("ID" => $id),
           TRUE,
           array()
        );
    }

    //*
    //* function Login2Target, Parameter list:
    //*
    //* Reads target as from loginid.
    //*

    function Login2Target()
    {

        $this->ApplicationObj->Target=$this->ApplicationObj->PeopleObject->SelectUniqueHash
        (
           "",
           array("ID" => $this->LoginData[ "ID" ]),
           TRUE,
           array()
        );
    }

    //*
    //* function ConsultID2Person, Parameter list:
    //*
    //* Reads person from $this->ItemHash.
    //*

    function ConsultID2Person($item=array())
    {
        if (empty($item)) { $item=$this->ItemHash; }
        $this->ApplicationObj->Person=$this->ApplicationObj->PeopleObject->SelectUniqueHash
        (
           "",
           array("ID" => $item[ "Person" ]),
           TRUE,
           array()
        );
    }

    //*
    //* function ConsultID2Place, Parameter list:
    //*
    //* Reads person from $this->ItemHash.
    //*

    function ConsultID2Place($item=array())
    {
        if (empty($item)) { $item=$this->ItemHash; }
        $this->ApplicationObj->Place=$this->ApplicationObj->PlacesObject->SelectUniqueHash
        (
           "",
           array("ID" => $item[ "Place" ]),
           TRUE,
           array()
        );
    }

    //*
    //* function ConsultID2Place, Parameter list:
    //*
    //* Reads person from $this->ItemHash.
    //*

    function ConsultID2Protocol($item=array())
    {
        if (empty($item)) { $item=$this->ItemHash; }
        $this->ApplicationObj->Protocol=$this->ApplicationObj->ProtocolsObject->SelectUniqueHash
        (
           "",
           array("ID" => $item[ "Protocol" ]),
           TRUE,
           array()
        );

        $this->ApplicationObj->Department=$this->ApplicationObj->DepartmentsObject->SelectUniqueHash
        (
           "",
           array("ID" =>$this->ApplicationObj->Protocol [ "Department" ]),
           TRUE,
           array()
        );
    }



    //*
    //* function ConsultsSearchForm Parameter list: $protocol,$place
    //*
    //* Produces ConsultsSearchForm, with search select fields,
    //* and a table of matched persons.
    //*

    function ConsultsSearchForm($protocol,$place)
    {
        $this->ApplicationObj->Protocol=$protocol;
        $this->ApplicationObj->Place=$place;
        $this->InitActions();

        $table=array();

        array_push
        (
           $table,
           array($this->H(3,"Pesquisar Pessoas"))
        );
        
        foreach ($this->ApplicationObj->ProtocolsObject->GetTargetSearchData($this->ApplicationObj->Protocol) as $data)
        {
            array_push
            (
               $table,
               array
               (
                  $this->B
                  (
                     $this->ApplicationObj->PeopleObject->GetDataTitle($data).":"
                  ),
                  $this->MakeInput($data,$this->GetPOST($data),35)
               )
            );
        }
        array_push
        (
           $table,
           array
           (
              $this->MakeHidden
              (
                 $this->ApplicationObj->ProtocolSearchField,
                 $protocol[ "ID" ]
              ).
              $this->MakeHidden
              (
                 $this->ApplicationObj->PlaceSearchField,
                 $place[ "ID" ]
              ).
              $this->MakeHidden("Target",$this->GetPOST("Target")).
              $this->MakeHidden("Date",$this->GetPOST("Date")).
              $this->MakeHidden("Consult",1).
              $this->Button("submit","Pesquisar")
            )
        );

        $tables=array
        (
            $this->StartForm().
            $this->HtmlTable("",$table,array("BORDER" => 1,"ALIGN" => 'center')).
            $this->EndForm().
            ""
        );

        //Search vars defined?
        $res=FALSE;
        foreach ($this->ApplicationObj->ProtocolsObject->GetTargetSearchData($this->ApplicationObj->Protocol) as $data)
        {
            if ($this->GetPOST($data)!="")
            {
                $res=TRUE;
                break;
            }
        }

        if ($res)
        {
            array_push($tables,$this->PersonsSearchTable());
        }

        return $tables;
    }

    //*
    //* function UpdateConsult, Parameter list: 
    //*
    //* Does ConsultForm Updating.
    //*

    function UpdateConsult()
    {
        $this->ApplicationObj->ReadProtocol();

        $html=FALSE;
        if ($this->GetPOST("SavePerson")==1)
        {
            $this->ApplicationObj->Person=$this->ApplicationObj->PeopleObject->UpdateItem($this->ApplicationObj->Person);
        }
        else //if ($this->GetPOST("SaveConsult")==1)
        {
            if (
                  $this->GetPOST("Target")==0
                  &&
                  empty($this->ApplicationObj->Protocol[ "TargetPerson" ])
               )
            {
                $html.=$this->H
                (
                   4,
                   $this->ApplicationObj->ProtocolsObject->GetTargetName
                   (
                      $this->ApplicationObj->Protocol
                   ).
                   " não Especificado!"
                );
            }
            elseif ($this->GetPOST("Target")>0 || !empty($this->ApplicationObj->Protocol[ "TargetPerson" ]))
            {
               if ($this->GetPOST("Name")!="")
               {
                   $hour=$this->TrimHourValue($this->GetPOST("Hour"));
                   if (empty($hour))
                   {
                       $hour=sprintf("%02d",$this->CurrentHour()).":".sprintf("%02d",$this->CurrentMinute());
                   }

                   $targetid=$this->GetPOST("Target")>0;
                   if (!empty($this->ApplicationObj->Protocol[ "TargetPerson" ]))
                   {
                       $targetid=$this->ApplicationObj->Protocol[ "TargetPerson" ];
                   }

                   $item=array
                   (
                      "Owner" => $this->LoginData[ "ID" ],
                      "Protocol" => $this->ApplicationObj->Protocol[ "ID" ],
                      "Place" => $this->ApplicationObj->Place[ "ID" ],
                      "Target" => $targetid,
                      "Person" => $this->GetGET("PID"),
                      "Date" => $this->HtmlDateInputValue("AddDate"),
                      "Name" => $this->GetPOST("Name"),
                      "Hour" => $hour,
                      "Status" => 1,
                   );

                   $msg="";
                   $res=$this->Add($msg,$item);

                   if (!$res)
                   {
                       $html.=$msg;
                   }
                   else
                   {
                       $html.=$this->H(3,"Consulta adicionada com êxito!");
                   }
               }
               else
               {
                   $html.=$this->H(5,"Digitar Descrição");
               }
            }
            elseif (empty($this->ApplicationObj->Protocol[ "TargetPerson" ]))
            {
                $html.=$this->H(4,"Descrição não especificada");
            }
        }

        return $html;
    }

    //*
    //* function ConsultSearchTable, Parameter list: $fixedvars,$vars,$hiddenvars,$listdatagroup,$where=array()
    //*
    //* Generates consults search table along with list of consults.
    //*

    function ConsultSearchTable($fixedvars,$vars,$hiddenvars,$listdatagroup,$where=array(),
                                $searchtitle="",$tabletitle="",$noitemstabletitle="")
    {
        $searchtable=array();
        foreach ($fixedvars as $var => $def)
        {
            array_push
            (
               $searchtable,
               array
               (
                  $this->B($def[ "Name" ].":"),
                  $def[ "Text" ].
                  $this->MakeHidden($var,$def[ "Value" ]),
               )
            );

            if (empty($def[ "Ignore" ]))
            {
                $where[ $var ]=$def[ "Value" ];
            }

        }

        foreach ($vars as $var => $def)
        {
            $field="";
            if (isset($def[ "Method" ]))
            {
                $method=$def[ "Method" ];
                $field=$this->$method
                (
                   $var,
                   $def[ "Value" ]
                );
            }
            elseif (isset($def[ "Field" ]))
            {
                $field=$def[ "Field" ];
            }
            else
            {
                $field=$this->MakeSearchVarInputField
                (
                   $var,
                   $def[ "Value" ]
                );

                $field=preg_replace('/'.$this->ModuleName.'_'.$var.'_Search/',$var,$field);
            }

            array_push
            (
               $searchtable,
               array
               (
                  $this->B($def[ "Name" ].":"),
                  $field
               )
            );

            if (!empty($def[ "Value" ]) && empty($def[ "Ignore" ]))
            {
                 $where[ $var ]=$def[ "Value" ];
            }
        }

        $hidden="";
        foreach ($hiddenvars as $var => $def)
        {
            $hidden.=$this->MakeHidden($var,$this->GetPOST($var));
        }

        array_push
        (
           $searchtable,
           array
           (
              $hidden.
              $this->Button("submit","Pesquisar")
           )
        );

        $datas=array("ID");
        foreach ($this->AlwaysReadData as $data)
        {
            array_push($datas,$data);
        }

        foreach ($this->ItemDataGroups[ $listdatagroup ][ "Data" ] as $data)
        {
            if (!empty($this->ItemData[ $data ]))
            {
                array_push($datas,$data);
            }
        }

        $items=$this->SelectHashesFromTable
        (
           "",
           $where,
           $datas,
           FALSE
        );

        $items=$this->SortList($items,array("Date","Hour"),TRUE);

        $itemstable=array();
        if (count($items)>0)
        {
            $itemstable=$this->ItemsTableDataGroup
            (
               $this->ItemDataGroups[ $listdatagroup ][ "Name" ],
               0,
               $listdatagroup,
               $items
             );

             array_unshift($itemstable,$this->H(5,$this->ItemsName." Conformando com a Pesquisa"));
        }
        else
        {
            $itemstable=array($this->H(5,"Nenhuma ".$this->ItemName." encontrada..."));
        }

        return
            $this->StartForm().
            $this->HtmlTable
            (
               $searchtitle,
               $searchtable,array("BORDER" => 1,"ALIGN" => 'center')
            ).
            $this->EndForm().
            $this->HtmlTable
            (
               "",
               $itemstable
            ).
            "";
    }

    //*
    //* function PersonConsultsTable, Parameter list: 
    //*
    //* Generates list of consults for person $this->ApplicationObj->Person.
    //*

    function PersonConsultsTable()
    {
        $status=$this->GetSearchVarCGIValue("Status");
        if (empty($status))
        {
            $status=1;
        }

        return $this->ConsultSearchTable
        (
            array
            (
               "Protocol" => array
               (
                  "Name"  => "Protocolo",
                  "Value" => $this->ApplicationObj->Protocol[ "ID" ],
                  "Text"  => $this->ApplicationObj->Protocol[ "Name" ],
               ),
               "Place" => array
               (
                  "Name"  => "Local",
                  "Value" => $this->ApplicationObj->Place[ "ID" ],
                  "Text"  => $this->ApplicationObj->Place[ "Name" ],
               ),
           ),
            array
            (
               "Date" => array
               (
                  "Name" => "Data",
                  "Method" => "HtmlDateInputField",
                  "Value" => $this->HtmlDateInputValue("Date",FALSE),
               ),
               "Status" => array
               (
                  "Name" => "Status",
                  "Value" => $status,
               ),
            ),
            array
            (
               "Target" => array
               (
                
               ),
            ),
            "PersonList",
            array(),
            $this->H(3,"Consultas do(a) Interessado(a):").
            $this->H(5,$this->ApplicationObj->Person[ "Name" ])
        );
    }


    //*
    //* function AddConsultForm, Parameter list: 
    //*
    //* Generates consult add form.
    //*

    function AddConsultForm()
    {
        $table=array();
        $title="Selecionar ".$this->ApplicationObj->ProtocolsObject->GetTargetName($this->ApplicationObj->Protocol);
        array_push
        (
           $table,
           array
           (
              $this->B
              (
               $this->ApplicationObj->ProtocolsObject->GetTargetConsultName($this->ApplicationObj->Protocol).":"
              ),
              $this->ApplicationObj->Person[ "Name" ],
           ),
           array
           (
              $this->B
              (
                 $this->ApplicationObj->ProtocolsObject->GetTargetName($this->ApplicationObj->Protocol).":"
              ),
              $this->MakeTargetSelect(),
           )
        );

        if (TRUE)
        {
            $title="Lançar Consulta";
            array_push
            (
               $table,
               array
               (
                  $this->B("Data/Horário:"),
                  $this->HtmlDateInputField
                  (
                     "AddDate",
                     $this->HtmlDateInputValue("AddDate"),
                     array
                     (
                        "TITLE" =>'DD/MM/YYYY'
                     )
                  ).
                  $this->HtmlTimeInputField
                  (
                     "Hour",
                     "",
                     array("TITLE" => 'HH:MM - Opcional')
                  )
               ),
               array
               (
                  $this->B("Descrição da Consulta:"),
                  $this->MakeInput("Name","",50)
               )
            );
        }

        array_push
        (
           $table,
           array
           (
              $this->MakeHidden("SaveConsult",1).
              $this->Button("submit","Lançar"),
           )
        );

        array_unshift($table,$this->H(3,$title));

        return
            $this->StartForm().
            $this->HtmlTable("",$table).
            $this->EndForm();
    }

    //*
    //* function HandleAddConsult, Parameter list: 
    //*
    //* Handles AddConsult screen.
    //*

    function HandleAddConsult()
    {
        $this->ApplicationObj->ReadProtocol();
        $this->ApplicationObj->ReadPlace();
        $this->ApplicationObj->ReadPerson();

        $msg=$this->UpdateConsult();

        $table=array();

        if (count($this->ApplicationObj->Person)>0)
        {
            $table=$this->ApplicationObj->PeopleObject->ItemTable
            (
               1,
               $this->ApplicationObj->Person,
               0,
               $this->ApplicationObj->PersonTableData,
               array(),
               FALSE,
               FALSE, //no name
               FALSE //no compulsory msg
            );

            array_unshift($table,$this->H(3,"Dados do(a) Interessado(a)"));
        }
        else
        {
            array_unshift($table,$this->H(5,"Pessoa não Encontrada..."));

            print $this->HtmlTable("",$table);
            return;
        }

        array_splice
        (
           $table,
           1,0,
           $this->Buttons()
        ); 

        array_push
        (
           $table,
           $this->MakeHidden("SavePerson",1).
           $this->Buttons()
        ); 


        $tables=array
        (
            $this->H(1,"Adicionar Consulta"),
            $this->HtmlTable
            (
               "",
               array
               (
                  array
                  (
                     $this->B("Protocolo:"),
                     $this->ApplicationObj->Protocol[ "Name" ],
                  ),
                  array
                  (
                     $this->B("Local:"),
                     $this->ApplicationObj->Place[ "Name" ],
                  ),
               )
            ).
            $msg.
            $this->AddConsultForm(),

            $this->PersonConsultsTable(),

            $this->StartForm().
            $this->HtmlTable("",$table).
            $this->EndForm()
        );
           
        print $this->HtmlTable("",$tables);
    }


    //*
    //* function TargetConsultsTable, Parameter list: 
    //*
    //* Generates list of consults for target $this->ApplicationObj->Target.
    //*

    function TargetConsultsTable()
    {
        $status=$this->GetSearchVarCGIValue("Status","Status");
        if (empty($status))
        {
            $status=1;
        }

        return $this->ConsultSearchTable
        (
            array
            (
               "Protocol" => array
               (
                  "Name"  => "Protocolo",
                  "Value" => $this->ApplicationObj->Protocol[ "ID" ],
                  "Text"  => $this->ApplicationObj->Protocol[ "Name" ],
                  "Ignore"  => TRUE,
               ),
               "Place" => array
               (
                  "Name"  => "Local",
                  "Value" => $this->ApplicationObj->Place[ "ID" ],
                  "Text"  => $this->ApplicationObj->Place[ "Name" ],
                  "Ignore"  => TRUE,
               ),
               "Target" => array
               (
                  "Name"  => "Consultado(a)",
                  "Value" => $this->ApplicationObj->Target[ "ID" ],
                  "Text"  => $this->ApplicationObj->Target[ "Name" ],
               ),
               "Person" => array
               (
                  "Name"  => "Interessado(a)",
                  "Value" => $this->ApplicationObj->Person[ "ID" ],
                  "Text"  => $this->ApplicationObj->Person[ "Name" ],
               ),
            ),
            array
            (
               "Date" => array
               (
                  "Name" => "Data",
                  "Method" => "HtmlDateInputField",
                  "Value" => $this->HtmlDateInputValue("Date",FALSE),
               ),
               "Status" => array
               (
                  "Name" => "Status",
                  "Value" => $status,
               ),
            ),
            array
            (
               "Person" => array
               (
               ),
            ),
            "TargetList"
        );
    }

    //*
    //* function UpdateConsultation, Parameter list: 
    //*
    //* Updates selected consults.
    //*

    function UpdateConsultation()
    {
        if ($this->GetPOST("UpdateConsult")==1)
        {
           $this->ItemHash=$this->UpdateItem($this->ItemHash);
        }

        return "";
    }

    //*
    //* function ChangeStatus, Parameter list: $item,$data,$newvalue
    //*
    //* Test to see if we may change status. That is,
    //* if password specified matches.
    //*

    function ChangeStatus($item,$data,$newvalue)
    {
        $password=$this->GetPOST("Password");
        $password=md5($password);

        $crpassword=$this->ApplicationObj->PeopleObject->MySqlItemValue
        (
           "",
           "ID",
           $this->LoginData[ "ID" ],
           "Passwd"
         );

        if ($password==$crpassword)
        {
            $item[ $data ]=$newvalue;
            $item[ $data."Date" ]=time();
            $this->MySqlSetItemValue
            (
               "",
               "ID",
               $item[ "ID" ],
               "StatusDate",
               $item[ $data."Date" ]
            );
            print $this->H(2,"Status da Consulta Alterada");
        }
        else
        {
            print $this->H(4,"Senha Inválida - Status Inalterdada!");            
        }

        return $item;
    }


    //*
    //* function HandleEdit, Parameter list: $echo=TRUE
    //*
    //* Handles consults for target.
    //*

    function HandleEdit($echo=TRUE,$formurl=NULL,$title="")
    {
        $this->ConsultID2Person($this->ItemHash);
        $this->ConsultID2Place($this->ItemHash);
        $this->ConsultID2Protocol();
        $this->ApplicationObj->ReadPlace();
        $this->Login2Target();



        $table=array();
        $msg="";
        if (!empty($this->ItemHash))
        {
            if ($this->GetPOST("UpdateConsult")==1)
            {
                $msg=$this->UpdateConsultation();
            }

            $datas=array("Date","Hour","Person","Name","Resume","Conclusion","StatusDate","Status");
            $table=$this->ItemTable(1,array(),TRUE,$datas,array(),FALSE,FALSE,FALSE);

            array_unshift
            (
               $table,
               $this->H(3,"Relatar Consulta")
            );
            array_push
            (
               $table,
               array
               (
                  $this->B("Senha:"),
                  $this->MakePassword("Password","",10,0,array("Title" => "Digitar sua Senha para Alterar Status"))
               ),
               array
               (
                  $this->MakeHidden("UpdateConsult",1).
                  $this->MakeHidden("ID",$this->GetGET("ID")).
                  $this->Buttons()
               )
            );
        }

        $tables=array
        (
            $this->H(1,"Relatar Consultas"),
            $msg.
            $this->TargetConsultsTable(),

            $this->StartForm().
            $this->HtmlTable("",$table).
            $this->EndForm()
        );
           
        print $this->HtmlTable("",$tables);
    }

    //*
    //* function HandleAdd, Parameter list: $echo=TRUE
    //*
    //* Handles AddConsult screen.
    //*

    function HandleAdd($echo=TRUE)
    {
        $this->ApplicationObj->ReadDepartment();
        $this->ApplicationObj->ReadProtocol();
        $this->ApplicationObj->ReadPlace();

        $this->ApplicationObj->HtmlHead();
        $this->ApplicationObj->HtmlDocHead();
        $this->TInterfaceMenu();

        print
            $this->HtmlTable
            (
               "",
               array
               (
                  $this->H(2,"Protocolo"),
                  array
                  (
                     $this->B("Secretaria:"),
                     $this->ApplicationObj->Department[ "Name" ]
                  ),
                  array
                  (
                     $this->B("Protocolo:"),
                     $this->ApplicationObj->Protocol[ "Name" ]
                  ),
                  array
                  (
                     $this->B("Local:"),
                     $this->ApplicationObj->Place[ "Name" ]
                  ),
               )
            );


        $pid=$this->GetGET("PID");

        if (empty($pid))
        {
            print
                $this->HtmlTable
                (
                   "",
                   $this->ConsultsSearchForm
                   (
                      $this->ApplicationObj->Protocol,
                      $this->ApplicationObj->Place
                   )
                ).
                "";
        }
        else
        {
            $this->ApplicationObj->ReadPerson();

            $msg=$this->UpdateConsult();

            $this->ItemDataGroups[ "PersonList" ][ "Name" ]="kdkdkdk";
            $tables=array($this->PersonConsultsTable());
            array_push($tables,$this->AddConsultForm());


            $table=$this->ApplicationObj->PeopleObject->ItemTable
            (
               1,
               $this->ApplicationObj->Person,
               0,
               $this->PersonTableData,
               array(),
               FALSE,
               FALSE, //no name
               FALSE //no compulsory msg
            );

            array_push($table,array($this->Buttons()));

            print
                $this->HtmlTable("",$tables).
                $this->HtmlTable
                (
                   $this->H(3,"Editar Dados: ").
                   $this->H(5,$this->ApplicationObj->Person[ "Name" ])
                   ,
                   $table
                );

                
        }
  

    }

    //*
    //* function PrintConsult, Parameter list: $item
    //*
    //* Prints consult sheet.
    //*

    function PrintConsult($head,$tail="\\clearpage")
    {
        $items=$this->SelectHashesFromTable
        (
           "",
           array
           (
              "Person" => $this->ItemHash[ "Person" ],
              "Protocol" => $this->ItemHash[ "Protocol" ],
           ),
           array(),
           TRUE
        );

        //array_reverse($items);


        $nlines=1;

        $itemids=array_keys($items);
        sort($itemids,SORT_NUMERIC);
        $itemids=array_reverse($itemids);

        $tables=array();
        $tablehead=
            "\\begin{center}\\begin{tabular}{|p{2cm}|p{2cm}p{13cm}|}\n".
            "   \\hline\n".
            "   \\textbf{\\large{Data/Hora}}& \\multicolumn{2}{|c|}{\\textbf{\\large{Histórico}}}\\\\\n".
            "   \\hline\n";

        $tabletail=
            "\\end{tabular}\\end{center}\n";

        $table="";
        foreach ($itemids as $itemid)
        {
             $nlines+=4;
             $nlines+=strlen($items[ $itemid ][ "Resume" ])/70;
             $nlines+=strlen($items[ $itemid ][ "Conclusion" ])/70;


             if ($nlines>40)
             {
                 array_push
                 (
                    $tables,
                    $head.
                    $tablehead.
                    $table.
                    $tabletail.
                    $tail
                 );

                 $table="";
                 $nlines=1;
             }

             $timestamp="-";
             if ($items[ $itemid ][ "StatusDate" ]>0)
             {
                 $timestamp=$this->TimeStamp2Text($items[ $itemid ][ "StatusDate" ]).".";
             }

             $item=$this->ApplyAllEnums($items[ $itemid ]);

             $table.=
                "   ".
                $this->CreateDateShowField("Date",$item,$items[ $itemid ][ "Date" ]).
                " & \\textbf{Status:} & ".$item[ "Status" ].". ".
                "\\textbf{Data de Alteração:} ".$timestamp.
                "\\\\\n".
                "   \\cline{2-3}\n".
                " ".
                $items[ $itemid ][ "Hour" ].":".
                "    & \\textbf{Descrição:} & ".$item[ "Name" ]."\\\\\n".                                      
                "   \\cline{2-3}\n".
                "   & \\textbf{Resumo:} & ".$item[ "Resume" ]."\\\\\n".
                "   \cline{2-3}\n".
                "   & \\textbf{Conclusão:} & ".$item[ "Conclusion" ]."\\\\\n".
                "   \\hline\n";
        }

        if (strlen($table)>0)
        {
            array_push
            (
               $tables,
               $head.
               $tablehead.
               $table.
               $tabletail.
               $tail
            );
            
        }

        return join("\n",$tables);
    }


    //*
    //* function PrintPersonConsult, Parameter list: 
    //*
    //* Prints consult sheet.
    //*

    function PrintPersonConsult()
    {
        $this->ConsultID2Person($this->ItemHash);
        $this->ConsultID2Place($this->ItemHash);
        $this->ConsultID2Protocol();

        $target=$this->ApplicationObj->PeopleObject->ReadItem($this->ItemHash[ "Target" ]);

        $this->ApplicationObj->Person=$this->ApplicationObj->PeopleObject->ApplyAllEnums($this->ApplicationObj->Person);
        $this->ApplicationObj->Protocol=$this->ApplicationObj->ProtocolsObject->ApplyAllEnums($this->ApplicationObj->Protocol);
        $this->ApplicationObj->Place=$this->ApplicationObj->PlacesObject->ApplyAllEnums($this->ApplicationObj->Place);
        $this->ApplicationObj->Department=$this->ApplicationObj->DepartmentsObject->ApplyAllEnums($this->ApplicationObj->Department);

        $target=$this->ApplicationObj->PeopleObject->ApplyAllEnums($target);
        $ritem=$this->ApplyAllEnums($this->ItemHash);


        foreach ($ritem as $key => $value)
        {
            $target[ "Consult_".$key ]=$value;
        }

        foreach ($this->ApplicationObj->Person as $key => $value)
        {
            $target[ "Person_".$key ]=$value;
        }

        foreach ($target as $key => $value)
        {
            $target[ "Target_".$key ]=$value;
        }

        foreach ($this->ApplicationObj->Department as $key => $value)
        {
            $target[ "Department_".$key ]=$value;
        }

        foreach ($this->ApplicationObj->Protocol as $key => $value)
        {
            $target[ "Protocol_".$key ]=$value;
        }

        foreach ($this->ApplicationObj->Place as $key => $value)
        {
            $target[ "Place_".$key ]=$value;
        }

        $head=join("",$this->MyReadFile($this->ApplicationObj->SetupPath."/Latex/".$this->ModuleName."/Receit.tex"));

       $latex.=$this->PrintConsult($head);

        $latex=
            $this->GetLatexHead("Singular").          
            $latex.
            $this->GetLatexTail("Singular");



        $latex=$this->TrimLatex($latex);
        $latex=$this->FilterHash($latex,$target);
        $latex=$this->FilterObject($latex);

         $texfilename="Item";
        if ($this->ItemName) { $texfilename=$this->ItemName; }
        $texfilename.=".".time().".tex";

        //print preg_replace('/\n/',"<BR>",$latex);exit();

        return $this->RunLatexPrint($texfilename,$latex,TRUE);
    }
}


?>