<?php

include_once("../MySql2/Unique.php");


class ClassDiscWeights extends Unique
{

    //*
    //* Variables of ClassDiscWeights class:
    //*
    var $WeightsDataGroup="Common";
    var $DiscsActions=array
    (
     "Edit","DiscMarks","DiscAbsences","DiscTotals",
    );

    var $DiscsData=array
    (
       "Name","CHS","CHT","Teacher","Teacher1","Teacher2"
    );




    //*
    //*
    //* Constructor.
    //*

    function ClassDiscWeights($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->Sort=array("Assessment");
        $this->UniqueKeys=array("Class","ClassDisc","Assessment");
    }


    //*
    //* function SqlTableName, Parameter list: $table=""
    //*
    //* Returns fully qualified and filtered name of table.
    //* Uses default value if $table is not given.
    //* Overrides MySql2::SqlTableName.
    //*

    function SqlTableName($table="")
    {
        return $this->ApplicationObj->SchoolPeriodSqlTableName($this->ModuleName);
    }

    //*
    //* function PostProcessItemData, Parameter list:
    //*
    //* Post process item data; this function is called BEFORE
    //* any updating DB cols, so place any additonal data here.
    //*

    function PostProcessItemData()
    {
    }

    //*
    //* function PostInit, Parameter list:
    //*
    //* Runs right after module has finished initializing.
    //*

    function PostInit()
    {
        $this->ApplicationObj->ReadSchool();
    }


    //*
    //* function PostProcess, Parameter list: $item
    //*
    //* Item post processor. Called after read of each item.
    //*

    function PostProcess($item)
    {
        $module=$this->GetGET("ModuleName");
        if (!preg_match('/^Class/',$module))
        {
            return $item;
        }

        return $item;
    }


    //*
    //* function MayDelete, Parameter list: $item
    //*
    //* Decides whether Grade is deletable.
    //*

    function MayDelete($item)
    {
        $res=FALSE;

        return $res;
    }





    //*
    //* function WeightFieldCGIVar, Parameter list: $class,$disc,$assessment
    //*
    //* Returns the name associated with disc nlessons no $n.
    //*

    function WeightFieldCGIVar($class,$disc,$assessment)
    {
        return 
            "Weight_".
            $class[ "ID" ]."_".
            $disc[ "ID" ]."_".
            $assessment;
    }

    //*
    //* function GetWeight, Parameter list: $disc,$n
    //*
    //* Returns wieght value.
    //*

    function GetWeight($disc,$n)
    {
        $res="";
        if (isset($disc[ "Weights" ][ $n-1 ][ "Weight" ]))
        {
            $res=sprintf("%.1f",$disc[ "Weights" ][ $n-1 ][ "Weight" ]);
        }

        return $res;
    }

    //*
    //* function WeightField, Parameter list: $edit,$class,$disc,$n,$tab=1
    //*
    //* Genrates Weight field.
    //*

    function WeightField($edit,$class,$disc,$n,$tab=1)
    {
        //if ($edit==1) { $edit=$this->ItemData[ "Weight" ][ "Coordinator" ]-1; }

        if ($edit==0) { return $this->GetWeight($disc,$n); }

        return $this->MakeInput
        (
           $this->WeightFieldCGIVar($class,$disc,$n),
           $this->GetWeight($disc,$n),
           2,
           array
           (
              "TABINDEX" => $tab,
           )
        );
    }

    //*
    //* function SumWeights, Parameter list: $disc=array()
    //*
    //* Makes Marks HTML table for disc $disc.
    //*

    function SumWeights($disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $ntotal=0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            $ntotal+=$this->GetWeight($disc,$n);
        }

        $ntotal=sprintf("%.1f",$ntotal);
        return array($this->MultiCell("",2));
    }

    //*
    //* function WeightsInputs, Parameter list: $edit=0,$disc=array(),$showtotal=TRUE
    //*
    //* Greates weight input titles.
    //*

    function WeightsInputs($edit=0,$disc=array(),$showtotal=TRUE,$colspan=1)
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }
        $showabsences=TRUE;

        if (intval($disc[ "AssessmentType" ])==$this->ApplicationObj->MarksNo)
        {
            $n=$disc[ "NAssessments" ];
            if ($showtotal) { $n++; }
            return array($this->MultiCell("",$n));
        }

        $row=array();
        $total=0.0;
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            $val=$this->GetWeight($disc,$n);
            $total+=$val;
            $cell=sprintf("%.1f",$val);
            if ($edit==1)
            {
                $cell=$this->WeightField($edit,$this->ApplicationObj->Class,$disc,$n,$n+20);
            }

            if ($colspan>1) { $cell=$this->MultiCell($cell,$colspan); }

            array_push($row,$cell);
        }

        if ($showtotal)
        {
            array_push($row,sprintf("%.1f",$total));
        }

        /* //Media */
        /* array_push($row,""); */

        return $row;
    }


    //*
    //* function UpdateWeightField, Parameter list: $class,&$disc,$assessment
    //*
    //* Updates Disc weight field.
    //*

    function UpdateWeightField($class,&$disc,$assessment)
    {
        $oldvalue=$this->GetWeight($disc,$assessment);
        $newvalue=$this->GetPOST
        (
           $this->WeightFieldCGIVar
           (
              $class,
              $disc,
              $assessment
           )
        );

        $var="Weight".$assessment;
        if ($newvalue!=$oldvalue)
        {
            $this->MySqlSetItemsValue
            (
               "",
               "ID",$disc[ "Weights" ][ $assessment-1 ][ "ID" ],
               "Weight",
               $newvalue
             );

            $disc[ "Weights" ][ $assessment-1 ][ "Weight" ]=$newvalue;
        }
    }


    //*
    //* function UpdateWeightsFields, Parameter list: $class,&$disc
    //*
    //* Updates Disc weight fields.
    //*

    function UpdateWeightFields($class,&$disc)
    {
        $keys=preg_grep('/^Weight_'.$class[ "ID" ].'_'.$disc[ "ID" ].'_/',array_keys($_POST));

        $assessments=array();
        foreach ($keys as $key)
        {
            array_push($assessments,preg_replace('/.*_/',"",$key));
        }

        foreach ($assessments as $assessment)
        {
            $this->UpdateWeightField($class,$disc,$assessment);
        }
    }
    //*
    //* function ReadClassDiscWeights, Parameter list: $item
    //*
    //* Reads CHS ClassDiscWeights entries.
    //*

    function ReadClassDiscWeights(&$item)
    {
        if (empty($item)) { return; }
        if (empty($item[ "NAssessments" ])) { return; }

        $item[ "Weights" ]=array();
        for ($n=1;$n<=$item[ "NAssessments" ]+1;$n++)
        {
            $default=1.0;
            if ($item[ "AssessmentsWeights" ]==2 && $n<=$item[ "NAssessments" ])
            {
                $default=1.0*$n;
            }

            $name="No. ".$n;
            if ($n>$item[ "NAssessments" ])
            {
                $name="Recup.";
            }

            $weight=$this->ReadOrAdd
            (
               array
               (
                  "ClassDisc" => $item[ "ID" ],
                  "Class" => $item[ "Class" ],
                  "Assessment" => $n,       
                  "Name" => $name,
                  "Weight" => $default,
               )
            );

            array_push($item[ "Weights" ],$weight);
        }
    }



    //*
    //* function ClassDiscWeightsTable, Parameter list: $item,$classid=0,&$n,&$table
    //*
    //* Generates class disc lessons table.
    //*

    function ClassDiscWeightsTable($item)
    {
        return $this->ItemsTableDataGroup
        (
           "Disciplinas",
           1,
           $this->WeightsDataGroup,
           $item[ "Weights" ]
        );
    }    

     //*
    //* function ShowClassDiscWeights, Parameter list: $item,$class=array(),&$n,&$table
    //*
    //* Displays List of class disciplines.
    //*

    function ShowClassDiscWeights($item,$class=array(),$n,&$table,$plural=TRUE)
    {
        $row=array($this->B($n));
        foreach ($this->DiscsActions as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->ActionEntry($data,$item)
            );
        }
        foreach ($this->DiscsData as $data)
        {
            array_push
            (
               $row,
               $this->MakeField(0,$item,$data,TRUE)
            );
        }

        array_push($table,$row);

        array_push
        (
           $table,
           array
           (
              "",
              $this->HtmlTable
              (
                 "",
                 $this->ClassDiscWeightsTable($item)
              )
           )
        );
    }

 
     //*
    //* function ClassDicWeightsRow, Parameter list: $disc,$class=array(),&$n
    //*
    //* Displays row of class disciplines.
    //*

    function ClassDiscWeightsRow($edit,$disc,$class=array(),$n,$plural=TRUE)
    {
        if ($edit==1 && $this->GetPOST("Update")==1)
        {
            $this->UpdateWeightFields($class,$disc);
        }

        $row=array($this->B($n));

        foreach ($this->DiscsActions as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->ActionEntry($data,$disc)
            );
        }

        foreach ($this->DiscsData as $data)
        {
            array_push
            (
               $row,
               $this->ApplicationObj->ClassDiscsObject->MakeField(0,$disc,$data,TRUE)
            );
        }

        $weight=0.0;
        for ($nn=0;$nn<$disc[ "NAssessments" ];$nn++)
        {
            array_push
            (
               $row,
               $this->WeightField($edit,$class,$disc,$nn+1)
            );

            $weight+=$disc[ "Weights" ][ $nn ][ "Weight" ];
        }
        array_push($row,sprintf("%.1f",$weight));

        for ($nn=0;$nn<$disc[ "NRecoveries" ];$nn++)
        {
            array_push
            (
               $row,
               $this->WeightField($edit,$class,$disc,$nn+1+$class[ "NAssessments"]),
               sprintf("%.1f",$disc[ "Weights" ][ $nn+$class[ "NAssessments"]  ][ "Weight" ]+1)
            );
        }

       

        return $row;

    }

    //*
    //* function ShowClassDiscsWeights, Parameter list: $edit=0,$class=array()
    //*
    //* Displays List of class disciplines.
    //*

    function ShowClassDiscsWeights($edit=0,$class=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $this->ApplicationObj->ClassDiscsObject->ReadClassDisciplines($class);

        $this->InitProfile("ClassDiscs");
        $this->ApplicationObj->ClassDiscsObject->InitActions();
        $this->PostInit();

        $titles=array_merge($this->DiscsActions,$this->DiscsData);
        $titles=$this->ApplicationObj->ClassDiscsObject->GetDataTitles($titles);
        array_unshift($titles,"");

        for ($n=1;$n<=$class[ "NAssessments" ];$n++)
        {
            array_push($titles,"Peso $n");
        }
        array_push($titles,"&Sigma;");

        for ($n=1;$n<=$class[ "NRecoveries" ];$n++)
        {
            array_push($titles,"Peso, Recup. $n");
            array_push($titles,"&Sigma;");
         }

        $table=array($this->B($titles));

        $n=1;
        foreach ($this->ApplicationObj->Discs as $disc)
        {
            if ($disc[ "AssessmentType" ]!=$this->ApplicationObj->MarksNo)
            {
                array_push($table,$this->ClassDiscWeightsRow($edit,$disc,$class,$n));
                $n++;
            }
        }


        print 
            $this->H(2,"Pesos da Turma  ".$this->ApplicationObj->Period[ "Name" ]);

        if ($edit==1)
        {
            print 
                $this->StartForm().
                $this->Buttons();
        }

        print
            $this->HtmlTable("",$table);

        
        if ($edit==1)
        {
            print 
                $this->MakeHidden("Update",1).
                $this->Buttons().
                $this->EndForm();
        }
    }

    //*
    //* function ImportDiscWeights, Parameter list: $class,$disc,&$table
    //*
    //* Importa alunos. 
    //* 
    //*

    function ImportDiscWeights($class,$disc,&$table)
    {
        for ($n=1;$n<=$disc[ "NAssessments" ];$n++)
        {
            $where=array
            (
               "Class" => $class[ "ID" ],
               "ClassDisc" => $disc[ "ID" ],
               "Assessment" => $n,
            );

            $hash=$where;
            $hash[ "Weight" ]=sprintf("%.1f",1.0*$n);

            $msg=$this->AddOrUpdate("",$where,$hash);
            array_push
            (
               $table,
               array
               (
                  "",
                  "",
                  "Import Disc Wight ".$disc[ "ID" ],
                  $msg,
                  $n.": ".$hash[ "Weight" ]
               )
            );
        }
    }
}

?>