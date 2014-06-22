<?php


class StudentsPrints extends StudentsImport
{
    var $NCols=5;
    var $Cols=array
    (
       1 => "Name",
       2 => "Status",
       3 => "Matricula",
       4 => "MatriculaDate",
    );

    var $NStudsPP=array
    (
       1 => 45,
       2 => 30,
    );

    //*
    //* function IncludeDataField, Parameter list: $n
    //*
    //* Creates select field for include data
    //*

    function IncludeDataField($n)
    {
        $datas=array_keys($this->ItemData);
        $this->TitleKeyShortName="Title";

        $titles=array();
        foreach ($datas as $data)
        {
            if ($this->GetDataAccessType($data)>0)
            {
                $title=$this->GetDataTitle($data);
                $titles[ $title ]=$data;
            }
        }

        $names=array_keys($titles);
        sort($names);

        $datas=array();
        foreach ($names as $name)
        {
            array_push($datas,$titles[ $name ]);
        }


        $names=array(0);
        $titles=array("");

        foreach ($datas as $data)
        {
            array_push($names,$data);
            array_push($titles,$this->GetDataTitle($data));
        }

        $field="Col".$n;
        $value=$this->GetPOST($field);
        if (empty($value) && !empty($this->Cols[ $n ]))
        {
            $value=$this->Cols[ $n ];
        }

        return $this->MakeSelectField($field,$names,$titles,$value);
    }

    //*
    //* function DataToIncludeTable, Parameter list: $searchvars
    //*
    //* Creates table for selecting student data to include.
    //*

    function DataToIncludeTable($searchvars=array())
    {
        $ncols=$this->GetPOST("NCols");
        if (!empty($ncols)) { $this->NCols=$ncols; }

        $table=array
        (
           $this->H(2,"Relatório, Alunos"),
           $this->H(3,"Selecionar Colunas"),
           array
           (
              $this->B("Orientação:"),
              $this->MakeSelectField("Orientation",array(1,2),array("Retrato","Paisagem"))
           ),
           array
           (
              $this->B("Nº de Colunas:"),
              $this->MakeInput("NCols",$this->NCols,2)
           )
        );

        for ($n=1;$n<=$this->NCols;$n++)
        {
            array_push
            (
               $table,
               array
               (
                  $this->B("Coluna ".$n.":"),
                  $this->IncludeDataField($n)
               )
            );
        }

        array_push($table,$this->Button("submit","Gerar Relatório"));

        $hiddens="";
        foreach ($searchvars as $key => $value)
        {
            $hiddens.=$this->MakeHidden($key,$value);
        }

        return $this->FrameIt
        (
            $this->StartForm("?ModuleName=Classes&Latex=1").
            $this->Html_Table
            (
               "",
               $table,
               array("ALIGN" => 'center'),
               array(),
               array(),
               FALSE,FALSE
            ).
            join("",$this->SearchVarsAsHiddens()).
            $this->MakeHidden("PrintStudents",1).
            $this->EndForm()
        );
    }

    //*
    //* function PrintStudentsPage, Parameter list: $students,&$n,$datas,$titles
    //*
    //* Geneateso students table, one page.
    //*

    function PrintStudentsPage($students,&$n,$datas,$titles)
    {
        $table=array($titles);
        foreach ($students as $student)
        {
            if (isset($student[ "StudentHash" ]))
            {
                $student=$student[ "StudentHash" ];
            }

            $row=array($this->B(sprintf("%02d",$n)));
            foreach ($datas as $data)
            {
                if (!isset($student[ $data ]))
                {
                    $student[ "StudentHash" ][ $data ]=$this->MySqlItemValue
                    (
                       "",
                       "ID",$student[ "ID" ],
                       $data
                    );
                }
                array_push
                (
                   $row,
                   $this->MakeShowField($data,$student)
                );
            }

            array_push($table,$row);
            $n++;
        }


        return 
            "\\Large{\\textbf{Relatório de Alunos}}\n".
            "\\vspace{0.25cm}\n\n".
            $this->LatexTable
            (
               "",
               $this->ApplicationObj->ClassesObject->ClassInfoTable($this->ApplicationObj->Class)
            ).
            "\\vspace{0.25cm}\n\n".
            $this->LatexTable("",$table).
            "\\clearpage\n\n".
            "";
    }

    //*
    //* function PrintStudents, Parameter list: $class=array()
    //*
    //* Geneates students table.
    //*

    function PrintStudents($class=array())
    {
        $this->ApplicationObj->SetLatexMode();

        $nitemspp=0;
        $latexhead="";
        if ($this->GetPOST("Orientation")==2)
        {
            $latexhead=$this->ApplicationObj->ClassesObject->LatexHeadLand();
            $nitemspp=$this->NStudsPP[2];;
        }
        else
        {
            $latexhead=$this->ApplicationObj->ClassesObject->LatexHead();
            $nitemspp=$this->NStudsPP[1];
        }

        $table=array();

        $datas=array();
        $titles=array("No.");
        for ($n=1;$n<=$this->NCols;$n++)
        {
            $data=$this->GetPOST("Col".$n);
            if (!empty($data))
            {
                array_push($datas,$data);
                array_push($titles,$this->GetDataTitle($data));
            }
        }

        $studentpages=array();
        $n=1;
        $nn=1;
        $page=0;
        foreach ($this->ApplicationObj->Students as $student)
        {
            if (!isset($studentpages[ $page ]))
            {
                $studentpages[ $page ]=array();
            }

            array_push($studentpages[ $page ],$student);

            if ($nn==$nitemspp)
            {
                $page++;
                $nn=0;
            }
            $n++;
            $nn++;
        }

        $n=1;
        $latex="";
        $titles=$this->B($titles);
        foreach ($studentpages as $students)
        {
            if (!empty($students))
            {
                $latex.=$this->PrintStudentsPage($students,$n,$datas,$titles);
            }
        }


        $latex=
            $latexhead.
            "\\begin{center}\n".
            $latex.
            "\\end{center}\n".
            $this->ApplicationObj->ClassesObject->LatexTail().
            "";

        $texfile="Students.".time().".tex";
        $this->RunLatexPrint($texfile,$latex);
        //$this->ShowLatexCode($latex);
    }
 }

?>