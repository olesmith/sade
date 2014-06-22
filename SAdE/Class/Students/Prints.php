<?php


class ClassStudentsPrints extends ClassStudentsShow
{
    var $Orientation="";
    var $PrintTypes=array
    (
       "Matricula" => array
       (
          "Title" => "Ficha de Matrícula",
          "Method" => "StudentMatricula",
          "Orientation" => "Portrait",
       ),
       "Receit" => array
       (
          "Title" => "Ficha de Notas",
          "Method" => "StudentSheet",
          "Orientation" => "Landscape",
       ),
       "ReceitSimple" => array
       (
          "Title" => "Ficha de Notas Simples",
          "Method" => "StudentSheetSimple",
          "Orientation" => "Portrait",
       ),
    );

    //*
    //* function ClassStudentsPrintCellName, Parameter list: $student,$type
    //*
    //* Returns CGI name of student print entry cell.
    //*

    function ClassStudentsPrintCellName($student,$type)
    {
        return "Include_".$student[ "StudentHash" ][ "ID" ]."_".$type;
    }

    //*
    //* function StudentMatricula, Parameter list: $student
    //*
    //* Prints Matricula sheet for student.
    //*

    function StudentMatricula($student)
    {
        if (method_exists($this->ApplicationObj->StudentsObject,"InitPrint"))
        {
            $student[ "StudentHash" ]=$this->ApplicationObj->StudentsObject->InitPrint($student[ "StudentHash" ]);
        }

        return $this->ApplicationObj->StudentsObject->LatexItem($student[ "StudentHash" ]);

    }
    //*
    //* function StudentSheet, Parameter list: $student
    //*
    //* Prints sheet for student.
    //*

    function StudentSheet($student)
    {
        $this->ApplicationObj->Student=$student;
        $tables=$this->ApplicationObj->ClassDiscsObject->GenerateTable(0,0);

        $latex=
            $this->H(1,$this->ApplicationObj->ClassDiscsObject->GetDisplayTitle()).
            "\n\\vspace{0.1cm}\n\n";

        foreach ($tables as $table)
        {
            if (is_array($table)) { $table=join("\n\n",$table); }
            $latex.=$table;
        }

        $latex.="\\clearpage";

        return $latex;
    }

    //*
    //* function StudentSheetSimple, Parameter list: $student
    //*
    //* Prints sheet for student.
    //*

    function StudentSheetSimple($student)
    {
        $this->ApplicationObj->Student=$student;
        $this->ApplicationObj->ClassDiscsObject->NoObservations=TRUE;

        $tables=$this->ApplicationObj->ClassDiscsObject->GenerateTable(0,0);

        $latex=
            $this->H(1,$this->ApplicationObj->ClassDiscsObject->GetDisplayTitle()).
            "\n\\vspace{0.1cm}\n\n".
            "";

        foreach ($tables as $table)
        {
            if (is_array($table)) { $table=join("\n\n",$table); }
            $latex.=$table;
        }

        $latex=$this->LatexOnePage($latex,"20cm",0.9);

        return $latex;
    }

    //*
    //* function ClassStudentsAnything2Print, Parameter list: 
    //*
    //* Detects whether anything to print..
    //*

    function ClassStudentsAnything2Print()
    {
        $this->Orientation="";
        foreach ($this->ApplicationObj->Students as $student)
        {
            foreach ($this->PrintTypes as $type => $def)
            {
                if (
                      $this->GetPOST($this->ClassStudentsPrintCellName($student,$type))==1
                      ||
                      $this->GetPOST("Include_All_".$type)==1
                   )
                {
                    $this->Orientation=$def[ "Orientation" ];
                    return TRUE;
                }

            }
        }

        return FALSE;
    }

    //*
    //* function ClassStudentsPrint, Parameter list: 
    //*
    //* Creates students prints.
    //*

    function ClassStudentsPrint()
    {
        $this->ApplicationObj->SetLatexMode();

        $this->ApplicationObj->ClassDiscsObject->PerDisc=FALSE;

        $this->ApplicationObj->ClassDiscsObject->ReadTable();

        $this->ApplicationObj->ClassDiscsObject->TableType="Print";
        $this->ApplicationObj->ClassDiscsObject->InitDisplayTable(0,0);


        $this->ApplicationObj->StudentsObject->InitLatexData();

        $latex="";
        if ($this->Orientation=="Landscape")
        {
            $latex.=$this->ApplicationObj->ClassesObject->LatexHeadLand();
        }
        else
        {
            $latex.=$this->ApplicationObj->ClassesObject->LatexHead();
        }

        $latex.=
            "\\begin{center}\n".
            "";

        foreach ($this->ApplicationObj->Students as $student)
        {
            foreach ($this->PrintTypes as $type => $def)
            {
                if (
                      $this->GetPOST($this->ClassStudentsPrintCellName($student,$type))==1
                      ||
                      $this->GetPOST("Include_All_".$type)==1
                   )
                {
                    $method=$def[ "Method" ];
                    $latex.=$this->$method($student);
                }
            }
        }


        $texfilename="Item";
        if ($this->ItemName) { $texfilename=$this->ItemName; }
        $texfilename.=".".time().".tex";

        $latex.=
            "\\end{center}\n".
            $this->ApplicationObj->ClassesObject->LatexTail().
            "";

        // $this->ShowLatexCode($latex);exit();
         $this->RunLatexPrint($texfilename,$latex);
    }

    //*
    //* function ClassStudentsPrintTable, Parameter list: 
    //*
    //* Creates student based print table.
    //*

    function ClassStudentsPrintTable()
    {
        $actions=array("Edit",);
        $datas=array("Name","Matricula","MatriculaDate","Status",);

        $titles=array("No.");
        foreach ($actions as $action)
        {
            array_push($titles,"");
        }

        foreach ($datas as $data)
        {
            array_push($titles,$this->ApplicationObj->StudentsObject->GetDataTitle($data));
        }

        $alls=array($this->MultiCell("Todos os Alunos:",1+count($actions)+count($datas),"r"));

        foreach ($this->PrintTypes as $type => $def)
        {
            array_push($titles,$def[ "Title" ]);
            array_push
            (
               $alls,
               $this->MakeCheckBox
               (
                  "Include_All_".$type,
                  1
               )
            );
        }


        $table=array();
        array_push
        (
           $table,
           $alls,
           $this->B($titles)
        );

        $n=1;
        foreach ($this->ApplicationObj->Students as $student)
        {
            $row=array(sprintf("%02d",$n++));

            foreach ($actions as $action)
            {
                array_push($row,$this->ApplicationObj->StudentsObject->ActionEntry($action,$student[ "StudentHash" ]));
            }

            foreach ($datas as $data)
            {
                array_push($row,$this->ApplicationObj->StudentsObject->MakeShowField($data,$student[ "StudentHash" ]));
            }

            foreach ($this->PrintTypes as $type => $name)
            {
                array_push
                (
                   $row,
                   $this->MakeCheckBox
                   (
                      $this->ClassStudentsPrintCellName($student,$type),
                      1
                   )
                );
            }

            array_push($table,$row);
        }

        return
            $this->H(2,"Imprimíveis dos Alunos").
            $this->H(3,"Selecione Componentes:").
            $this->ApplicationObj->ClassesObject->StartForm("?Latex=1").
            $this->Center
            (
               $this->Button("submit","Gerar").
               $this->HtmlTable
               (
                  "",
                  $table,
                  array("ALIGN" => 'center')
               ).
               $this->Button("submit","Gerar")
            ).
            $this->MakeHidden("Print",1).
            $this->EndForm().
            "";
    }


    //*
    //* function HandleClassStudentsPrints, Parameter list: $classid=0
    //*
    //* Creates student based print form.
    //*

    function HandleClassStudentsPrints($classid=0)
    {
        if ($classid==0) { $classid=$this->ApplicationObj->GetClass("ID"); }

        $this->ReadClassStudents($classid);

        $this->ApplicationObj->StudentsObject->InitProfile("Students");
        $this->ApplicationObj->StudentsObject->InitActions();
        $this->ApplicationObj->StudentsObject->PostInit();
        $this->ApplicationObj->StudentsObject->Actions[ "Print" ][ "Title" ]="Imprimir Matricula";

        if (!empty($this->ApplicationObj->Students))
        {
            if ($this->GetPOST("PrintStudents")==1)
            {
                $this->ApplicationObj->StudentsObject->PrintStudents();

                return;
            }

            if ($this->GetPOST("Print")==1)
            {
                if ($this->ClassStudentsAnything2Print())
                {
                    $this->ClassStudentsPrint();

                    return;
                }
                else
                {
                    $this->ApplicationObj->ClassesObject->PrintDocHeadsAndLeftMenu();
                }
            }

            $this->ApplicationObj->StudentsObject->NoPaging=TRUE;

            $this->ApplicationObj->StudentsObject->PostProcessed=array();


            print
                $this->ApplicationObj->StudentsObject->DataToIncludeTable().
                $this->ClassStudentsPrintTable();
                "";
        }
        else
        {
            print $this->H(4,"Nenhum(a) Aluno(a) na Turma");
        }
    }
}

?>