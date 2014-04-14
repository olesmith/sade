<?php

class ItemsGroupTable extends ItemsTable
{

    //*
    //* function ItemsTableDataGroup, Parameter list: $title,$edit=0,$group="",$items=array(),$titles=array(),$cgiupdatevar="Update"
    //*
    //* Creates data group table, group $group. If $group=="", calls GetActualDataGroup to detect it.
    //* $title, $edit and $items are transferred calling ItemTable.
    //*

    function ItemsTableDataGroup($title,$edit=0,$group="",$items=array(),$titles=array(),$cgiupdatevar="Update")
    {
        if (empty($items)) { $items=$this->ItemHashes; }

        if (empty($this->Actions))
        {
            $this->InitActions();
        }

        if ($group=="") { $group=$this->GetActualDataGroup(); }
        if (!empty($this->ItemDataGroups[ $group ][ "PreMethod" ]))
        {
            $method=$this->ItemDataGroups[ $group ][ "PreMethod" ];
            $this->$method();
        }

        $datas=$this->GetGroupDatas($group);

        if (!empty($this->ItemDataGroups[ $group ][ "GenTableMethod" ]))
        {
            $method=$this->ItemDataGroups[ $group ][ "GenTableMethod" ];
            if (method_exists($this,$method))
            {
                return $this->$method($edit);
            }
            else
            {
                print "ItemsTableDataGroup: No such method: $method";
                exit();
            }
        }

        $countdef=array();
        if (
              isset($this->ItemDataGroups[ $group ][ "SubTable" ])
              &&
              is_array($this->ItemDataGroups[ $group ][ "SubTable" ])
           )
        {
            $countdef=$this->ItemDataGroups[ $group ][ "SubTable" ];
        }


        if (count($titles)==0)
        {
            $titles=$datas;
        }

        if (!empty($this->ItemDataGroups[ $group ][ "PreProcessor" ]))
        {
            $method=$this->ItemDataGroups[ $group ][ "PreProcessor" ];
            if (count($items)==0) { $items=$this->ItemHashes; }

            foreach ($items as $id => $item)
            {
                $this->$method($items[ $id ]);
            }
        }

        if (
            isset($this->ItemDataGroups[ $group ][ "NoTitleRow" ])
            &&
            $this->ItemDataGroups[ $group ][ "NoTitleRow" ]
           )
        {
            $titles=array();
        }

        return $this->ItemsTable($title,$edit,$datas,$items,$countdef,$titles,TRUE,$cgiupdatevar);
    }

    //*
    //* function ItemsTableDataGroupWithAddRow, Parameter list: 
    //*
    //* Creates ItemTableDataGroup table with add row. Update and adding row called on the way.
    //*

    function ItemsTableDataGroupWithAddRow($title,$group,$cgiupdatevar,$cgiprekey,$newitem,$postmethod=FALSE,$updatekey="AddRow",$nempties=0)
    {
        $datas=$this->ItemDataGroups[ $group ][ "Data" ];
        $added=FALSE;
        if ($this->GetPOST($cgiupdatevar)==1 && $this->GetPOST($updatekey)==1)
        {
            $newitem=$this->UpdateAddRow($cgiprekey,$newitem,$datas,$updatekey);
        }

        $this->ReadItems("",$datas,TRUE,FALSE,2);
 
        if ($postmethod)
        {
            $newitem=$this->$postmethod($newitem);
        }


        $table=$this->ItemsTableDataGroup
        (
           $title,
           1,
           $group,
           $this->ItemHashes,
           array(),
           $cgiupdatevar
        );

        array_push
        (
           $table,
           $this->AddRow($cgiprekey,$newitem,$datas,!$added,$nempties)
        );

        return $table;
   }
}
?>