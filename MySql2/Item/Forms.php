<?php

class ItemForms extends Fields
{

    var $AddDatas=array();
    var $ShowTimes=FALSE;
    var $SinglePrintables=FALSE;
    var $AllDatas=array();
    var $AddReloadAction="Edit";


    //*
    //* Creates item data single group table.
    //*

    function ItemTableDataSGroup($edit,$item,$group,$datas=array())
    {
        $res=$this->DataGroupIsAllowed($this->ItemDataSGroups[ $group ],$item);

        if (!$res) { return array(); }

        $rdatas=array();
        if (count($datas)>0)
        {
            $rdatas=$datas;
        }
        elseif ($group!="All")
        {
            $rdatas=$this->GetGroupDatas($group,TRUE); //use single data groups
        }
        else
        {
            $rdatas=array_keys($this->AllDatas);
        }

        foreach ($rdatas as $id => $data)
        {
            unset($this->AllDatas[ $data ]);
        }

        $rtbl=array();
        if (!empty($this->ItemDataSGroups[ $group ][ "GenTableMethod" ]))
        {
            $method=$this->ItemDataSGroups[ $group ][ "GenTableMethod" ];
            if (method_exists($this,$method))
            {
                $rtbl=$this->$method($edit,$item);
            }
            else
            {
                $this->AddMsg("SGroups '$group' GenTableMethod: ".
                              "'$method', is undefined: Ignored!");
            }
        }
        else
        {
            $rrtbl=array();
            $rtbl=$this->ItemTable($edit,$item,0,$rdatas,$rrtbl,FALSE,FALSE,FALSE);
        }

        //Add table name as H3
        $rtbl=
            $this->H(3,$this->ItemDataSGroups[ $group ][ "Name" ]).
            $this->HTML_Table("",$rtbl);


        //Make sure that $data only appears once as input field
        foreach ($rdatas as $id => $data)
        {
            $this->ItemData[ $data ][ "ReadOnly" ]=1;
            $this->ItemData[ $data ][ "AdminReadOnly" ]=1;
        }

        return array($rtbl);
    }

    //*
    //* Creates form for editing an item. If $_POST[ "Update" ]==1,
    //* calls Update.
    //*

    function EditForm($title,$item=array(),$edit=0,$noupdate=FALSE,$datas=array(),$echo=TRUE,$extrarows=array(),$formurl=NULL,$buttons="",$cgiupdatevar="Update")
    {
        if (empty($buttons)) { $buttons=$this->Buttons(); }
        $html="";
        if (count($item)==0) { $item=$this->ItemHash; }

        if ($this->GetPOST($cgiupdatevar)==1 && $edit==1 && !$noupdate)
        {
            $item=$this->TestItem($item);
            $item=$this->UpdateItem($item);
        }

        $this->LogMessage("EditForm",$item[ "ID" ].": ".$this->GetItemName($item));

        $tbl=array();
        $hiddens=array();

        $this->AllDatas=array();
        foreach ($this->AllDatas as $data)
        {
            if ($this->GetDataAccessType($data,$item)>0)
            {
                $this->AllDatas[ $data ]=TRUE;
            }
        }

        if (count($datas)>0)
        {
            $tbl=$this->ItemTable($edit,$item,FALSE,$datas);
        }
        elseif (count($this->ItemDataSGroups)>0)
        {
            //we will generate a list of tables
            $tables=array();
            $row=array();
            foreach ($this->ItemDataSGroups as $group => $groupdef)
            {
                array_push($row,$this->ItemTableDataSGroup($edit,$item,$group));

                if (count($row)==2 || !empty($groupdef[ "Single" ]))
                {
                    array_push($tables,$row);
                    $row=array();
                }
            }

            if (count($row)>0)
            {
                array_push($tables,$row);
            }

            $tbl=$tables;
        }
        else
        {
            $tbl=$this->ItemTable($edit,$item);
        }

        if ($edit==1)
        {
            array_push($tbl,$this->CompulsoryMessage());
        }

        foreach ($extrarows as $row)
        {
            array_push($tbl,$row);
        }

        $printtable="";
        if ($this->SinglePrintables)
        {
            array_unshift($tbl,$this->GenerateLatexHorMenu());
        }

        $tbl=$this->HTML_Table("",$tbl,array("ALIGN" => 'center',"FRAME" => 'box'));


        $name=$this->GetItemName($item);
        $html.=$this->H(1,$title);

        $infotables=array();

        if ($this->ShowTimes && isset($item[ "CTime" ]))
        {
            array_push
            (
               $infotables,
               array
               (
                  $this->SPAN
                  (
                     $this->GetMessage($this->ItemDataMessages,"Created").":",
                     array("CLASS" => 'searchtitle')
                  ),
                  $this->TimeStamp2Text($item[ "CTime" ])
               ),
               array
               (
                  $this->SPAN
                  (
                     $this->GetMessage($this->ItemDataMessages,"LastChange").":",
                     array("CLASS" => 'searchtitle')
                  ),
                  $this->TimeStamp2Text($item[ "MTime" ])
               )
            );
        }
       

        $html.=
            $this->Html_Table("",$infotables,array("ALIGN" => 'center',"FRAME" => 'box')).
            $this->BR();

        if ($edit==1)
        {
            $id="";
            if ($this->HashKeySetAndPositive($item,"ID"))
            {
                $id="&ID=".$item[ "ID" ];
            }

            if (!$formurl)
            {
                $formurl="?Action=".$this->DetectAction().$id;
            }

            $html.=
                $printtable.
                $this->StartForm($formurl,"post",$this->HasFileFields).
                 $buttons.
                "";
        }

        $html.=$tbl;


        if ($edit==1)
        {
            $html.=
                $buttons.
                $this->MakeHidden($cgiupdatevar,1).
                $this->MakeHidden("ID",$item[ "ID" ]).
                $this->EndForm().
                "";
        }

        if ($echo)
        {
            print $html;
            return "";
        }
        else
        {
            return $html;
        }
    }

    //*
    //* function InitAddDefaults, Parameter list: $hash=array()
    //*
    //* Puts some default values into the AddDefaults array.
    //* Creator is set to LoginID.
    //*

    function InitAddDefaults($hash=array())
    {
        foreach ($hash as $data => $value)
        {
            $this->AddDefaults[ $data ]=$value;
        }
        foreach ($this->AddFixedValues as $data => $value)
        {
            $this->AddDefaults[ $data ]=$value;
        }


        if ($this->LoginType!="Admin" && isset($this->ItemData[ $this->CreatorField ]))
        {
            $this->AddDefaults[ $this->CreatorField ]=$this->LoginData[ "ID" ];
            $this->AddDefaults[ $this->CreatorField."_Value" ]=$this->LoginData[ "Name" ];
        }

        foreach (array_keys($this->ItemData) as $data)
        {
            if (
                isset($this->ItemData[ $data ][ "Default" ])
                &&
                !isset($this->AddDefaults[ $data ]))
            {
                $this->AddDefaults[ $data ]=$this->ItemData[ $data ][ "Default" ];
            }

            if (isset($this->ItemData[ $data ][ "NoAdd" ]) && $this->ItemData[ $data ][ "NoAdd" ])
            {
                unset($this->AddDefaults[ $data ]);
                $this->ItemData[ $data ][ $this->Profile ]=0;
            }
        }

        $this->AddDefaults=$this->TestItem($this->AddDefaults);
    }


    //*
    //* Creates table for adding data. May be overwritten.
    //*

    function MakeAddTable($datas)
    {
         return $this->HTMLTable
        (
           "",
           $this->ItemTable
           (
              1,
              $this->AddDefaults,
              1,
              $datas
           )
        );
    }


    //*
    //* Creates form for adding an item. If $_POST[ "Update" ]==1,
    //* calls Add.
    //*

    function AddForm($title,$addedtitle,$echo=TRUE)
    {
        $this->Singular=TRUE;
        $rdatas=$this->FindAllowedData(0);
        $datas=array();
        foreach ($rdatas as $data)
        {
            if (!preg_match('/^[ACM]Time$/',$data))
            {
                array_push($datas,$data);
            }
        }

        if (is_array($this->AddDatas) && count($this->AddDatas)>0) { $datas=$this->AddDatas; }
        $this->InitAddDefaults();

        $html="";
        $action="Add";
        $action=$this->DetectAction();
        $msg="";
        if ($this->GetPOST("Add")==1)
        {
            $res=$this->Add($msg);
            if ($res)
            {
                $args=$this->Query2Hash();
                $args=$this->Hidden2Hash($args);
                $query=$this->Hash2Query($args);

                $action=$this->DetectAction();

                $this->AddCommonArgs2Hash($args);
                $args[ "Action" ]=$this->DetectAction();
                if ($args[ "Action" ]=="Add") { $args[ "Action" ]=$this->AddReloadAction; }

                $args[ "ID" ]=$this->ItemHash[ "ID" ];

                //Now added, reload as edit, preventing multiple adds
                header("Location: ?".$this->Hash2Query($args));
                exit();
            }
        }

        $this->ApplicationObj->HtmlHead();
        $this->ApplicationObj->HtmlDocHead();
        $this->LogMessage("AddForm","Load form");
        $html=
            $this->H(2,$title).
            $msg.
            $this->StartForm("?Action=".$action).
            $this->Buttons().
            $this->MakeAddTable($datas).
            $this->MakeHidden("Add",1).
            $this->Buttons().
            $this->EndForm();

        if ($echo)
        {
            $this->TInterfaceMenu(TRUE);
            print $html;
            return "";
        }
        else
        {
            return $html;
        }
   }


    //*
    //* Creates form for copying an item. If $_POST[ "Update" ]==1,
    //* calls Copy.
    //*

    function CopyForm($title,$copiedtitle)
    {
        $this->Singular=TRUE;
        $this->NoFieldComments=TRUE;

        $action="Copy";
        $msg="";
        if ($this->GetPOST("Copy")==1)
        {
            $res=$this->Copy();
            if ($res)
            {
                $args=$this->Query2Hash();
                $args=$this->Hidden2Hash($args);
                $query=$this->Hash2Query($args);

                $action=$this->DetectAction();

                $this->AddCommonArgs2Hash($args);
                $args[ "Action" ]=$this->DetectAction();
                if ($args[ "Action" ]=="Copy") { $args[ "Action" ]="Edit"; }

                $args[ "ID" ]=$this->ItemHash[ "ID" ];
 
                //Now added, reload as edit, preventing multiple adds
                header("Location: ?".$this->Hash2Query($args));
                exit();
            }
            else
            {
                $msg=$this->H(4,$this->ItemName." não Copiado");
            }
        }

        $this->InitAddDefaults($this->ItemHash);
        
        $this->ApplicationObj->HtmlHead();
        $this->ApplicationObj->HtmlDocHead();
        $this->LogMessage("CopyForm","Form Loaded");

        $item=$this->ItemHash;
        foreach ($this->AddDefaults as $data => $value)
        {
            if ($item[ $data ]!="")
            {
                $item[ $data ]=$value;
                $item[ $data."_Value" ]=$value;
            }
        }

        $this->TInterfaceMenu(TRUE);
        print
            $this->H(2,$title).
            $msg.
            $this->H(3,$this->GetItemName($item)).
            $this->StartForm("?Action=".$action).
            $this->Buttons().
            $this->HTMLTable
            (
               "",
               $this->ItemTable
               (
                  1,
                  $item,
                  1,
                  $this->GetNonReadOnlyData()
               )
            ).
            $this->MakeHidden("Copy",1);

        print
            $this->Buttons().
            $this->EndForm();
    }


    //*
    //* Creates form for deleting an item. If $_POST[ "Delete" ] is 1,
    //* calls Delete for actual deletion.
    //*

    function DeleteForm($title,$deletedtitle,$item=array(),$echo=TRUE,$formurl="?Action=Delete",$idvar="ID")
    {
        if (! is_array($item) || count($item)==0) { $item=$this->ItemHash; }

        $this->LogMessage("DeleteForm",$item[ "ID" ].": ".$this->GetItemName($item));

        $html="";
        if ($this->GetPOST("Delete")==1)
        {
            $html=$this->Delete($item,$echo);
            $html=$this->H(2,$deletedtitle);            
        }
        else
        {
            $tbl=$this->ItemTable(0,$item);

            $name=$this->GetItemName($item);

            if (count($this->BackRefDBs)>0)
            {
                $res=$this->HandleBackRefDBs($item,$name);
                if ($res!=0) { return; }
                else
                {
                    $html.=
                        $this->H
                        (
                           3,
                           "Nenhuma ".$obj->ItemName." referencia esta ".$this->ItemName."<BR>".
                           $this->ItemName." pode ser deletada com seguran&ccedil;a"
                        );
                }
            }

            $html.=
                $this->H(2,$title).
                $this->H
                (
                   3,
                   "Tem certeza que quer deletar '".$this->ItemName.": ".$name."'?"
                ).
                $this->StartForm($formurl).
                $this->Center($this->Button("submit",">>DELETAR<<")).
                $this->HTMLTable("",$tbl).
                $this->MakeHidden($idvar,$item[ "ID" ]).
                $this->MakeHidden("Delete",1).
                $this->Center($this->Button("submit",">>DELETAR<<")).
                $this->EndForm();
        }

        if ($echo)
        {
            print $html;
            return $item;
        }
        else
        {
            return $html;
        }
    }

}
?>