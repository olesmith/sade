<?php


class StudentsAdd extends StudentsPrints
{

    //*
    //* function MakeAddTable, Parameter list: $datas
    //*
    //* Creates the AddStudent table.
    //* 
    //*

    function MakeAddTable($datas)
    {
        if (empty($this->AddDefaults[ "MatriculaDate" ]))
        {
            $this->AddDefaults[ "MatriculaDate" ]=$this->TimeStamp2DateSort();
        }

        $this->AddDefaults[ "Status" ]=1;
        $this->AddFixedValues[ "Status" ]=1;
        $this->AddDefaults[ "School" ]=$this->ApplicationObj->School[ "ID" ];
        $this->AddFixedValues[ "School" ]=$this->ApplicationObj->School[ "ID" ];

        $edit=1;
        if (count($this->ItemDataSGroups)>0)
        {
            //we will generate a list of tables
            $tables=array();

            $row=array();
            foreach ($this->ItemDataSGroups as $group => $groupdef)
            {
                array_push($row,$this->ItemTableDataSGroup($edit,$this->AddDefaults,$group));

                if (count($row)==2 || $groupdef[ "Single" ])
                {
                    array_push($tables,$row);
                    if ($edit==1)
                    {
                        array_push($tables,$this->Buttons());
                    }
                    $row=array();
                }
            }

            if (count($row)>0)
            {
                array_push($tables,$row);
            }

            $tbl=$tables;

            if ($edit==1)
            {
                array_push($tbl,$this->CompulsoryMessage());
            }

            $hiddens=array();
            foreach ($this->AddFixedValues as $data => $value)
            {
                array_push($hiddens,$this->MakeHidden($data,$value));
            }

            array_push($tbl,join("",$hiddens));
        }
        else
        {
            $tbl=$this->ItemTable($edit,$this->AddDefaults);
        }

        return $this->HTML_Table("",$tbl,array("ALIGN" => 'center'),array(),array(),FALSE);
    }

    //*
    //* function EditForm, Parameter list: $title,$item=array(),$edit=0,$noupdate=FALSE,$datas=array(),$echo=TRUE,$extrarows=array(),$formurl=NULL,$buttons="",$cgiupdatevar="Update"
    //*
    //* Overrides EditForm, placing photo of student, if avaliable.
    //* 
    //*

    function EditForm($title,$item=array(),$edit=0,$noupdate=FALSE,$datas=array(),$echo=TRUE,$extrarows=array(),$formurl=NULL,$buttons="",$cgiupdatevar="Update")
    {
        if (empty($item)) { $item=$this->ItemHash; }
        if (!empty($item[ "Photo" ]))
        {
            print $this->Center
            (
             $this->IMG($item[ "Photo" ],"Foto do(a) Aluno(a) ".$item[ "Name" ],150,0,array("BORDER" => 1)).
             "<BR>".
             $item[ "Name" ]
            );
        }
        
        parent::EditForm($title,$item,$edit,$noupdate,$datas,$echo,$extrarows,$formurl,$buttons,$cgiupdatevar);
    }
 }

?>