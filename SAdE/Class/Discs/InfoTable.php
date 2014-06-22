<?php


class ClassDiscsInfoTable extends ClassDiscsDayly
{
    //*
    //* function DiscLatexInfoTable, Parameter list: $item
    //*
    //* Genmrates info table for disc.
    //*

    function DiscLatexInfoTable($item)
    {
        $teacher="-";
        if (!empty($item[ "TeacherHash" ]))
        {
            $teacher=$item[ "TeacherHash" ][ "Name" ];
        }

        $table=$this->ApplicationObj->ClassesObject->ClassHtmlInfoTable();

        array_push
        (
           $table[0],
           $this->B("Disciplina:"),
           $item[ "Name" ]
        );
        array_push
        (
            $table[1],
            $this->B("Professor(a) Disc.:"),
            $teacher
        );
        array_push
        (
           $table[2],
           $this->B("CHS/T:"),
           sprintf("%02d",$item[ "CHS" ])."/".sprintf("%03d",$item[ "CHT" ])
        );

        array_push
        (
           $table[3],
           $this->B("No. Avaliações:"),
           $item[ "NAssessments" ]
        );
        array_push
        (
           $table[4],
           $this->B("Recuperações:"),
           $item[ "NRecoveries" ]
        );
        array_push
        (
           $table[5],
           $this->B("Limite(s), Média:"),
           sprintf("%.1f",$item[ "MediaLimit" ])." (".sprintf("%.1f",$item[ "FinalMedia" ]).")"
        );

        array_push
        (
           $table[6],
           $this->B("Limite, Faltas:"),
           sprintf("%.1f",$item[ "AbsencesLimit" ])
       );

        return $this->LatexTable("",$table);
    }

    //*
    //* function InfoTable, Parameter list: $item
    //*
    //* Genmrates info table for disc.
    //*

    function InfoTable($disc)
    {
        if ($this->LatexMode())
        {
            return $this->DiscLatexInfoTable($disc);
        }

        $datas=array
        (
           array("Name","Teacher", "Daylies"),
           array("CHS" ,"Teacher1","AbsencesType"),
           array("CHT", "Teacher2","AssessmentType"),
        );

        $table=array();
        foreach ($datas as $rdatas)
        {
            $row=array();
            foreach ($rdatas as $data)
            {
                array_push
                (
                   $row,
                   $this->B($this->ItemData[ $data ][ "Name" ].":"),
                   $this->MakeShowField($data,$disc)
                );
            }

            array_push($table,$row);
        }

        return $this->FrameIt
        (
           $this->Html_Table
           (
              "",
              $table,
              array(),
              array(),
              array(),
              FALSE,
              FALSE
           )
        ).
        "<P>";
    }


    //*
    //* function ClassInfoTable, Parameter list: $class
    //*
    //* Generates info table for class.
    //*

    function ClassInfoTable($class)
    {
        if ($this->LatexMode())
        {
            return $this->DiscLatexInfoTable($class);
        }

        $teacher="-";
        if (!empty($class[ "TeacherHash" ]))
        {
            $teacher=$class[ "TeacherHash" ][ "Name" ];
        }

        $table=array();

        array_push
        (
           $table,
           array
           (
              $this->B("Turma: "),
              $class[ "Name" ],
           ),
           array
           (
              $this->B("Professor:"),
              $teacher,
           ),
           array
           (
              $this->B("CHS/T:"),
              sprintf("%02d",$class[ "CHS" ])."/".sprintf("%03d",$class[ "CHT" ]),
            )
        );

        return $this->HtmlTable("",$table);
    }
}
?>