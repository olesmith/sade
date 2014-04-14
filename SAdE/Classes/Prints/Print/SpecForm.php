<?php



class ClassesPrintsPrintSpecForm extends ClassesPrintsPrintSpecTable
{
    var $ClassDayliesSpecData=array
    (
       "LastStudentsLast",
       "LastStudentsLastDate",
       "DayliesBackPage",
       "DayliesTwoPage",
       "DayliesOrientation",
       array("Paisagem","Retrato"),
       array("DayliesNFields_1","DayliesNFields_2"),
       array("DayliesNStudentsPP_1","DayliesNStudentsPP_2"),
       "DayliesNMarkFields",
    );


    //*
    //* function PrintsSpecTitleCell, Parameter list: $data
    //*
    //* Geneartes title input cell.
    //*

    function PrintsSpecTitleCell($data)
    {
        if (empty($this->ItemData[ $data ])) { return ""; }

        return $this->B($this->GetDataTitle($data).":");
    }

    //*
    //* function PrintsSpecInputCell, Parameter list: $data,$class
    //*
    //* Generates input cell.
    //*

    function PrintsSpecInputCell($data,$class)
    {
        if (empty($this->ItemData[ $data ]) || !isset($class[ $data ])) { return $this->B($data); }

        return $this->MakeInputField($data,$class,$class[ $data ]);
    }


    //*
    //* function PrintsSpecForm, Parameter list: &$class
    //*
    //* Generates table with vars controlling the prints.
    //*

    function PrintsSpecForm(&$class)
    {
        $table=array(array($this->H(3,"Ajustes do Impresso")));
        foreach ($this->ClassDayliesSpecData as $data)
        {
            $row=array();
            if (is_array($data))
            {
                array_push($row,$this->PrintsSpecTitleCell($data[0]));
                foreach ($data as $rdata)
                {
                    array_push($row,$this->PrintsSpecInputCell($rdata,$class));
                }
            }
            else
            {
                array_push($row,$this->PrintsSpecTitleCell($data));
                array_push($row,$this->PrintsSpecInputCell($data,$class));
                array_push($row,"");
            }

            array_push($table,$row);
        }

        return $this->Html_Table
        (
           "",
           $table,
           array("ALIGN" => 'center',"FRAME" => 'box'),
           array(),
           array(),
           FALSE,
           FALSE
        );
    }


    //*
    //* function UpdatePrintsSpecCell, Parameter list: $data,&$class,&$updatedatas
    //*
    //* Updates data from print spec form to $class.
    //*

    function UpdatePrintsSpecCell($data,&$class,&$updatedata)
    {
        $res=FALSE;

        if (empty($this->ItemData[ $data ])) { return $res; }

        $value=$this->GetPOST($data);

        if ($data=="LastStudentsLastDate")
        {
            return $res;
        }

        if ($data=="LastStudentsLast")
        {
            if ($value==1)
            {
                if (!empty($class[ "LastStudentsLastDate" ]))
                {
                    $class[ "LastStudentsLastDate" ]="";
                    array_push($updatedata,"LastStudentsLastDate");
                    $res=TRUE;
                }
            }
            elseif ($value==2)
            {
                $today=$this->TimeStamp2DateSort();
                if ($today!=$class[ "LastStudentsLastDate" ])
                {
                    $class[ "LastStudentsLastDate" ]=$today;
                    array_push($updatedata,"LastStudentsLastDate");
                    $res=TRUE;
                }
            }
        }

        if ($value!=$class[ $data ])
        {
            $class[ $data ]=$value;
            array_push($updatedata,$data);
            $res=TRUE;
        }

        return $res;
    }


    //*
    //* function UpdatePrintsSpecForm, Parameter list: &$class
    //*
    //* Updates data from print spec form to $class.
    //*

    function UpdatePrintsSpecForm(&$class)
    {
        if ($this->GetPOST("Generate")==1)
        {
            $updatedata=array();
            foreach ($this->ClassDayliesSpecData as $data)
            {
                if (is_array($data))
                {
                    foreach ($data as $rdata)
                    {
                        $this->UpdatePrintsSpecCell($rdata,$class,$updatedata);
                    }
                }
                else
                {
                    $this->UpdatePrintsSpecCell($data,$class,$updatedata);
                }

            }

            if (count($updatedata)>0)
            {
                $this->MySqlSetItemValues("",$updatedata,$class);
            }

            $this->InitPrintDaylies($class);
        }
    }
}


?>