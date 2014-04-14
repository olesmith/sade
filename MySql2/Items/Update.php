<?php

class ItemsUpdate extends ItemsPost
{
    //*
    //* function UpdateItems, Parameter list: $items=array()
    //*
    //* Update all items in $items (or $this->ItemHashes if empty).
    //* First, detects IDs foreach item. Next, retrieves POST vars
    //* from ItemTable form, using TestUpdateItem to test
    //* whether each value has changed.
    //* If a var changes and this var has a TriggerFunction,
    //* this function is called on item.
    //* Increments HtmlStatus on change.
    //*

    function UpdateItems($items=array())
    {
        if (count($items)==0) { $items=$this->ItemHashes; }
        $this->LogMessage("UpdateItems",count($items)." items");

        //Time that form was loaded
        $formmtime=$this->GetPOST("__MTime__");

        $ritems=array();
        $origids=array();
        $n=0;
        foreach ($items as $i => $item)
        {
            $item[ "N" ]=$n;
            $ritems[ $item[ "ID" ] ]=$item;
            $origids[ $item[ "ID" ] ]=$i;
            $n++;
        }

        $ids=array_keys($ritems);
        $keys=array_keys($_POST);
        $fkeys=array_keys($_FILES);

        if (!is_array($this->HtmlStatus))
        {
            $this->HtmlStatus=array($this->HtmlStatus);
        }

        foreach ($ids as $id)
        {
            //Test if we have individual access to Edit $item
            if (!$this->ActionAllowed("Edit",$ritems[ $id ]))
            {
                continue;
            }

            $rkeys=preg_grep("/^".$id."\_/",$keys);
            $rfkeys=preg_grep("/^".$id."\_/",$fkeys);
            foreach ($rfkeys as $fkey) { array_push($rkeys,$fkey); }

            //Time that form was loaded

            $rupdate=0;
            $update=0;
            $datas=array();
            $olditem=$ritems[ $id ];

            foreach ($rkeys as $rkey)
            {
                $key=preg_replace("/^$id\_/","",$rkey);
                if (!isset($this->ItemData[ $key ])) { continue; }

                $access=$this->GetDataAccessType($key,$olditem);
                if ($access<2)
                {
                    //Not allowed to edit - ignore
                    continue;
                }
                elseif (preg_match('/^FILE$/',$this->ItemData[ $key ][ "Sql" ]))
                {
                    $res=$this->UpdateFileField($key,$rupdate,$ritems[ $id ],$rkey);
                    if (is_array($res) && $res[ "__Res__" ])
                    {
                        $ritems[ $id ]=$res;
                        $rupdate++;
                        array_push($datas,$key);

                        if ($this->TriggerFunction($key))
                        { 
                            $ritems[ $id ]=$this->ApplyTriggerFunction
                            (
                               $key,
                               $ritems[ $id ],
                               $ritems[$id][ "ID" ]."_" //prekey to POST fields
                            );
                        }

                        $update++;
                    }
                }
                elseif (
                          $this->ItemData[ $key  ][ "Derived" ]==""
                          &&
                          $this->ItemData[ $key ][ "TimeType" ]==""
                       )
                {
                    $newvalue=$this->TestUpdateItem($key,$ritems[ $id ],TRUE);
                    if (!isset($ritems[ $id ][ $key ]) || $newvalue!=$ritems[ $id ][ $key ])
                    {
                        if ($this->TriggerFunction($key))
                        { 
                            $ritems[ $id ]=$this->ApplyTriggerFunction
                            (
                               $key,
                               $ritems[ $id ],
                               $ritems[$id][ "ID" ]."_", //prekey to POST fields
                               TRUE
                            );
                        }
                        else
                        {
                            $ritems[ $id ][ $key ]=$newvalue;
                        }



                        //$ritems[ $id ][ $key ]=$newvalue;
                        $update++;
                        array_push($datas,$key);
                    }
                }
            }

            $itemname=$this->GetItemName($ritems[$id]);
            if ($update>0)
            {

                $this->MySqlUpdateItem
                (
                    $this->SqlTableName(),
                    $ritems[$id],"ID='".$ritems[$id][ "ID" ]."'",
                    $datas
                );

                //Unset this, so that post pŕocessing will recur
                unset($this->PostProcessed[ $ritems[$id][ "ID" ] ]);
                $ritems[$id]=$this->PostProcessItem($ritems[$id]);

                $n=$ritems[$id][ "N" ];
                unset($ritems[$id][ "N" ]);
                $this->LogMessage
                (
                   "UpdateItem(s)",
                   $ritems[$id][ "ID" ].": ".
                   $this->GetItemName($ritems[$id])
                );

                $ritems[$id]=$this->SetItemTime("ATime",$ritems[$id]);
                $ritems[$id]=$this->SetItemTime("MTime",$ritems[$id]);

                if (method_exists($this,"PostUpdateActions"))
                {
                    $this->PostUpdateActions($olditem,$ritems[$id]);
                }                
            }

            $origid=$origids[ $ritems[$id][ "ID" ] ];

            $items[ $origid ]=$ritems[$id];
        }


        return $items;
    }

}
?>