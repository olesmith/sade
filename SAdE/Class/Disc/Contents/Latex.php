<?php


class ClassDiscContentsLatex extends ClassDiscContentsTable
{
    var $NCharsPerLine=130;
    var $NLinesPerPage=30;

    //*
    //* function Contents2NoOfLines, Parameter list: &$content
    //*
    //* Detects number of lines to be used by Contents text.
    //*

    function Contents2NoOfLines(&$content)
    {
        $content[ "Content" ]=preg_replace('/<BR>/',"",$content[ "Content" ]);
        $content[ "Content" ]=preg_replace('/^\s/',"",$content[ "Content" ]);
        $content[ "Content" ]=preg_replace('/\s$/',"",$content[ "Content" ]);

        $words=preg_split('/\s+/',$content[ "Content" ]);
        $words=preg_grep('/\S/',$words);

        $lines=array();
        $line="";
        while (!empty($words))
        {
            $line.=array_shift($words)." ";
            if (strlen($line)>=$this->NCharsPerLine)
            {
                array_push($lines,$line);
                $line="";
            }
        }

        if (preg_match('/\S/',$line)) { array_push($lines,$line); }

        return $this->Max(1,count($lines));
    }

    //*
    //* function DaylyContentsContentLatexRow, Parameter list: $n,$content,$dates,&$lastdates
    //*
    //* Generates content row for $content.
    //*

    function DaylyContentsContentLatexRow($n,$content,$dates,&$lastdates)
    {
        $date=$dates[ $content[ "Date" ] ];
        $row=array($this->B(sprintf("%03d",$n)));

        foreach ($this->DaylyDateDatas as $data)
        {
            $cell="";
            if (!isset($lastdates[ $data ]) || $date[ $data ]!=$lastdates[ $data ])
            {
                $cell=$this->ApplicationObj->DatesObject->MakeShowField($data,$date);
            }

            array_push($row,$cell);
        }


        array_push($row,$content[ "Weight" ],$content[ "Content" ]);

        foreach (array_keys($lastdates) as $key)
        {
            $lastdates[ $key ]=$date[ $key ];
        }


        return $row;
    }

    //*
    //* function ContentsLatex, Parameter list: $class,$disc
    //*
    //* Generates Contents Latex page(s).
    //*

    function ContentsLatex($class,$disc)
    {
        $contents=$this->CGI2Contents(array(),$class,$disc);

        $dates=$this->ReadContentDates($contents);
 
        $latex="";

        $head=
            "\\LARGE{\\textbf{Aulas e Conteúdos}}\n\n".
            $this->ApplicationObj->ClassesObject->LatexTable
            (
               "",
               $this->ApplicationObj->ClassesObject->ClassDaylyInfoTable($class,$disc),
               0,
               FALSE,
               TRUE,
               0,
               FALSE //no grey rows
            ).
            "\n\n\\vspace{0.25cm}\n";

        $tail=
            "\\vspace{1cm}\n".
            $this->ApplicationObj->ClassesObject->LatexSignatureLineShort();

        $lastdateempty=array
        (
           "Year"     => -1,
           "Semester" => -1,
           "Month"    => -1,
           //"WeekDay"   => -1,
           "WeekNo"   => -1,
           "Date"     => -1,
        );

        $lastdate=$lastdateempty;

        $this->DaylyDateDatas=preg_grep('/(Semester|WeekNo)/',$this->DaylyDateDatas,PREG_GREP_INVERT);

        $nlines=0;

        $this->DaylyDateDatas=array("Date","WeekDay");
        $titles=$this->B(array("No.","Data","Dia","CH","Conteúdo"));
        $specs="|l|c|c|c|p{20cm}|";

        $ch=0;
        $n=1;
        $table=array($titles);
        foreach ($contents as $content)
        {
            //Try to predict whether we should change page or not
            $rnlines=$this->Contents2NoOfLines($content);

            if ( ($nlines+$rnlines)>$this->NLinesPerPage )
            {
                $latex.=
                    $this->LatexOnePage
                    (
                       $head.
                       $this->LatexTable("",$table,$specs).
                       ""
                    );

                $table=array($titles);
                $nlines=0;                
                $lastdate=$lastdateempty;
            }

            array_push
            (
               $table,
               $this->DaylyContentsContentLatexRow($n++,$content,$dates,$lastdate)
            );

            
            $nlines+=$rnlines;                
            $ch+=$content[ "Weight" ];
        }

        array_push($table,$this->B(array("","","\$\\Sigma\$",$ch,"")));
 
        if (count($table)>0)
        {
            $latex.=
                $this->LatexOnePage
                (
                  $head.
                   $this->LatexTable("",$table,$specs).
                   $tail.
                   ""
                );
        }

        //$this->ShowLatexCode($latex);exit();

        return $latex;
    }

    //*
    //* function PrintContentsLatex, Parameter list: $class=array(),$disc=array(),$month=""
    //*
    //* Prints and generates Contents Latex page(s).
    //*

    function PrintContentsLatex($class=array(),$disc=array(),$month="")
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        if (empty($disc))  { $disc =$this->ApplicationObj->Disc; }

        $this->InitLatexData();
        $latex=
            $this->LatexHeadLand().
            $this->ContentsLatex($class,$disc).
            $this->LatexTail().
            "";

        //$this->ShowLatexCode($latex);exit();

        $texfilename=
            "Contents.".
            $this->CurrentYear().".".
            sprintf("%02d",$this->CurrentMonth()).".".
            sprintf("%02d",$this->CurrentDate()).".".
            time().".".
            ".tex";

        $this->RunLatexPrint($texfilename,$latex);
        exit();
    }

}

?>