<?php



class UsersAdd extends UsersImport
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

        return $this->HTML_Table("",$tbl);
    }

    //*
    //* function InitAddDefaults, Parameter list: $hash=array()
    //*
    //* Overrides MySql2::InitAddDefaults. Calls parent and sets permissions on School and Profile_Teacher
    //*

    function InitAddDefaults($hash=array())
    {
        parent::InitAddDefaults($hash);

        if (!empty($this->ApplicationObj->School))
        {
            $this->AddDefaults[ "School" ]=$this->ApplicationObj->School[ "ID" ];
        }

        if ($this->GetGET("Clerks")==1)
        {
            $this->AddDefaults[ "Profile_Teacher" ]=1;
            $this->AddDefaults[ "Profile_Clerk" ]=2;
            $this->AddFixedValues[ "Profile_Teacher" ]=1;
            $this->AddFixedValues[ "Profile_Clerk" ]=2;
            $this->ItemData[ "Profile_Teacher" ][ $this->ApplicationObj->Profile ]=1;
            $this->ItemData[ "Profile_Clerk" ][ $this->ApplicationObj->Profile ]=1;
        }
        elseif ($this->GetGET("Teachers")==1)
        {
            $this->AddDefaults[ "Profile_Teacher" ]=2;
            $this->AddDefaults[ "Profile_Clerk" ]=1;
            $this->AddFixedValues[ "Profile_Teacher" ]=2;
            $this->AddFixedValues[ "Profile_Clerk" ]=1;

            $this->ItemData[ "Profile_Teacher" ][ $this->ApplicationObj->Profile ]=1;
            $this->ItemData[ "Profile_Clerk" ][ $this->ApplicationObj->Profile ]=1;
        }
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
        
        parent::EditForm($title,$item,$edit,$noupdate,$datas,$echo,$extrarows,$formurl,$buttons,$cgiupdatevar);
    }
 }

?>