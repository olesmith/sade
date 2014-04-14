<?php


global $ClassList;
array_push($ClassList,"Latex");

class Latex extends CSV
{
    var $LatexData;
    var $RunLatexThrice=FALSE;
    var $LatexDataMessages="Latex.php";

    //*
    //* Set the Latex Head, Skel and Tail attributes.
    //*

    function InitLatex($data)
    {
        $this->LatexData=$data;
    }
    
    //*
    //* Bolds text, as latex or html
    //*
    function Bold($text,$html=TRUE)
    {
        if ($html && !$this->LatexMode)
        {
           $text="<B>".$text."</B>";
        }
        else
        {
           $text="\\textbf{".$text."}";
        }
        
        return $text;
    }

    //*
    //* Italics text, as latex or html
    //*
    function Italic($text,$html=TRUE)
    {
        if ($html && !$this->LatexMode)
        {
           $text="<I>".$text."</I>";
        }
        else
        {
           $text="\\textit{".$text."}";
        }

        return $text;        
    }

    //*
    //* Colors text, as latex or html
    //*
    function Color($text,$color,$html=TRUE)
    {
        if ($html && !$this->LatexMode)
        {
            $text="<FONT COLOR='".strtoupper($color)."'>".$text."</FONT>";
        }
        else
        {
           $text="\\textcolor{".$color."}{".$text."}";
        }

        return $text;        
    }

    //*
    //*Newline, as latex or html
    //*
    function NewLine($html=TRUE)
    {
        $text="\n\n";
        if ($html && !$this->LatexMode)
        {
           $text="<BR>";
        }
        
        return $text;
    }

    function LatexSkelPath()
    {
        if ($this->LatexData[ "SkelPath" ]!="")
        {
            $path=$this->LatexData[ "SkelPath" ];
        }
        else
        {
            $path="Latex";
        }

        return $this->FilterExtraPathVars($path);
    }

    //*
    //* function GetLatexSkel , Parameter list:
    //*
    //* Returns contents of latex skel file name including SkelPath
    //* 

    function GetLatexSkel($latexdoc)
    {
        $latex=$latexdoc;
        if (is_file($latexdoc))
        {
            $latex=join("",$this->MyReadFile($latexdoc));
        }
        else
        {
            $latexdoc=join("/",array($this->LatexSkelPath(),$latexdoc));
            $latexdoc=preg_replace('/#Setup/',$this->ApplicationObj->SetupPath,$latexdoc);
            $latexdoc=preg_replace('/#Module/',$this->ModuleName,$latexdoc);


            if (is_file($latexdoc))
            {
                $latex=join("",$this->MyReadFile($latexdoc));
            }
            else
            {
                $latex=$latexdoc;
            }
        }

        return $latex;
    }

    //*
    //* function LatexTmpPath, Parameter list:
    //*
    //* Return path to temporary directory, for storing latex files generated.
    //* 

    function LatexTmpPath()
    {
        if ($this->LatexData[ "TmpPath" ]!="")
        {
            return $this->LatexData[ "TmpPath" ];
        }
        else
        {
            return "/tmp";
        }
    }


    //*
    //* function CGI2LatexDocNo , Parameter list:
    //*
    //* Retrieves LatexDoc (no) from CGI environment.
    //* 

    function CGI2LatexDocNo()
    {
        $latexdoc=$this->GetGETOrPOST("LatexDoc");
        if ($latexdoc=="" || $latexdoc<=0) { $latexdoc=1; }

        return $latexdoc-1;
    }

    //*
    //* function LatexHead, Parameter list:
    //*
    //* Prints the Latex Head (leading HEAD section and top/left of page).
    //* Stored in /usr/local/UEG/Skels/Html/Head.Head.html, resp.
    //* Stored in /usr/local/UEG/Skels/Html/Head.Body.html.
    //* 

    function LatexHead()
    {
        if ($this->LatexData[ "Head" ]!="")
        {
            return $this->GetLatexSkel($this->LatexData[ "Head" ]);
        }
        else
        {
            return $this->GetLatexSkel("Head.tex");
        }
    }


    //*
    //* function LatexHeadLand , Parameter list:
    //*
    //* Prints the Latex Head with landscape set(leading HEAD section and top/left of page).
    //* Stored in /usr/local/UEG/Skels/Html/Head.Head.html, resp.
    //* Stored in /usr/local/UEG/Skels/Html/Head.Body.html.
    //* 

    function LatexHeadLand()
    {
        if (!empty($this->LatexData[ "Head_Land" ]))
        {
            return $this->GetLatexSkel($this->LatexData[ "Head_Land" ]);
        }
        else
        {
            $head=$this->LatexHead();
            if (preg_match('/\s*(\S)documentclass\[([^\[\]]+)\]/',$head,$matches) && count($matches)>=2)
            {
                $head=preg_replace
                (
                   '/\s*(\S)documentclass\[([^\[\]]+)\]/',
                   $matches[1]."documentclass[landscape,".$matches[2]."]",
                   $head
               );
            }

            return $head;
        }
    }

    //*
    //* function LatexTail , Parameter list:
    //*
    //* Prints the Latex Tail (trailing right/bottom of the page).
    //* Stored in /usr/local/UEG/Skels/Html/Tail.html, resp.
    //* 
    //*

    function LatexTail()
    {
        if ($this->LatexData[ "Tail" ]!="")
        {
            return $this->GetLatexSkel($this->LatexData[ "Tail" ]);
        }
        else
        {
            return $this->GetLatexSkel("Tail.tex");
        }
    }


//*
//* function GetSingularLatexDoc , Parameter list:
//*
//* Returns the latex doc (singular), acording to cgi var "LatexDoc".
//* 
//*

function GetSingularLatexDoc()
{
    $latexdocno=$this->CGI2LatexDocNo();
    $latexdoc=$this->LatexData[ "SingularLatexDocs" ][ "Docs" ][ $latexdocno ][ "Glue" ];

    return $this->GetLatexSkel($latexdoc);
}



//*
//* function LatexSelectForm, Parameter list: $type,$form=FALSE
//*
//* Prints Latex miniformulario for selecting LatexDoc
//* 
//*

function LatexSelectForm($type,$form=FALSE)
{
    if (is_array($this->LatexData[ $type."LatexDocs" ][ "Docs" ]))
    {
        $docnos=array();
        $docnames=array(); //space to prevent selecting first

        if ($type=="Plural")
        {
            array_push($docnos,0);
            array_push($docnames," ");
        }
        foreach ($this->LatexData[ $type."LatexDocs" ][ "Docs" ] as $n => $def)
        {
            array_push($docnos,$n+1);
            array_push($docnames,$def[ "Name" ]);
        }


        $latexdocno=$this->GetPOST("LatexDoc");
        $nempties=$this->GetPOST("NEmptyLines");
        if ($nempties=="") { $nempties=0; }

        $print="Print";
        $href="?Action=PrintList";
        if ($type=="Singular")
        {
            $href="?Action=Print&ID=".$this->GetGETOrPOST("ID");
        }

        if (!$latexdocno) { $latexdocno=0; }
        if (count($docnames)>0)
        {
            $row=array();
            if ($form)
            {
                array_push
                (
                   $row,
                   $this->StartForm($href)
                );
            }

            array_push
            (
               $row,
               $this->MakeSelectField("LatexDoc",$docnos,$docnames,$latexdocno)
            );

            if ($type!="Singular")
            {
                array_push
                (
                   $row,
                   $this->MakeInput("NEmptyLines",$nempties,2)." extras"
                );
            }

            if ($form)
            {
                array_push
                (
                   $row,
                   $this->Button("submit","Imprimir").
                   $this->EndForm()
                );

                $row=array(join("\n",$row));
            }

            return $row;
        }
    }

    return array();
        
}




//*
//* function LatexList , Parameter list: $list,$ul
//*
//* Prints a Latex list (enumerate) with elements in $list
//* 
//*

function LatexList($list,$ul="enumerate")
{
  $tex="\\begin{".$ul."}\n";
  for ($n=0;$n<count($list);$n++)
  {
    $tex.="   \\item ".$list[$n]."\n";
  }
  $tex.="\\end{".$ul."}\n";

  return $tex;
}


//*
//* function LatexMakeRow , Parameter list: $row,$count=0
//*
//* Creates a row section in Latex table. May also
//* be called with $tdtag as TH (as in table header).
//* 
//*

function LatexMakeRow($row,$count=0)
{
    $tdoptions="";
    if ($count==0) { $count=count($row); }

    if (!empty($row[ "Row" ])) { $row=$row[ "Row" ]; }

    $nospan=FALSE;
    foreach ($row as $n => $val)
    {
        if (is_array($row[$n]) && isset($row[$n][ "Text" ]))
        {
            //We need left | in spec, when we are first column
            $align="c";
            if (!empty($row[$n][ "Options" ][ "ALIGN" ]))
            {
                $align=$row[$n][ "Options" ][ "ALIGN" ];
            }

            $spec=$align."|";
            if ($n==0) {$spec="|".$align."|"; }

            $row[ $n ]=
                "\\multicolumn{".
                $row[$n][ "Options" ][ "COLSPAN" ].
                "}{".$spec."}{".
                $row[ $n ][ "Text" ].
                "}";
            $nospan=TRUE;
        }

        if (preg_match('/\\\\multicolumn/',$row[ $n ])) { $nospan=TRUE; }
    }

    $ncount=count($row);
    if (!$nospan && $ncount>0 && $ncount<$count)
    {
        $row[ $ncount-1 ]="\\multicolumn{".($count-$ncount+1)."}{|c|}{".$row[ $ncount-1 ]."}";
    }

    $tex="   ".join(" & ",$row)."\\\\\n";

    return $tex;
}

//*
//* function LatexHeadRow, Parameter list: $row,$count=0
//*
//* Creates Table Header row - calls LatexMakeRow above.
//* 
//*

function LatexHeadRow($row,$count=0)
{
  if ($count==0) { $count=count($row); }
  for ($n=0;$n<count($row);$n++)
  {
    $row[$n]="\\textbf{".$row[$n]."}";
  }
  $tex=$this->LatexMakeRow($row,$count);

  return $tex;
}

//*
//* function LatexTableRow, Parameter list: $row,$count=0
//*
//* Creates a Table row - calls HTMLMakeRow above.
//* 
//*

function LatexTableRow($row,$count=0)
{
  if ($count==0) { $count=count($row); }
  $tex=$this->LatexMakeRow($row,$count);

  return $tex;
}

//*
//* function LatexTable, Parameter list: $titles,$rows,$tablespec=0,$footnumbers=FALSE,$hlines=TRUE,$speclen=0,$greyrows=FALSE
//*
//* Creates a LatexTable.
//* 
//*

function LatexTable($titles,$rows,$tablespec=0,$footnumbers=FALSE,$hlines=TRUE,$speclen=0,$greyrows=FALSE)
{
    $rspeclen=$speclen;
    $pagetitle="";
    if (isset($this->LatexData[ "PageTitle" ])) { $pagetitle=$this->LatexData[ "PageTitle" ]; }

    $nitemspp=50;
    if (isset($this->LatexData[ "NItemsPerPage" ])) { $nitemspp=$this->LatexData[ "NItemsPerPage" ]; }
    if ($nitemspp=="" || $nitemspp==0)
    {
        $nitemspp=50;
    }

    //Find noof columns in table
    $count=0;
    if (is_array($titles) && count($titles)>0)
    {  
        $count=count($titles);
    }
    else
    {
        //table without title row
        $count=0;
        foreach ($rows as $id => $row)
        {
            $count=$this->Max($count,count($row));
        }
    } 

    $specs=array();
    for ($n=0;$n<$count;$n++)
    {
        $specs[$n]="l";
    }

    if ($tablespec!="") { $specs=$tablespec; }
    else
    {
        $speclen=count($specs);
        $specs="|".join("|",$specs)."|";
    }

    for ($n=0;$n<count($rows);$n++)
    {
        $rcount=count($rows[$n]);
        if ($rcount>$count) { $count=$rcount; }

        if ($rspeclen>0 && $rcount>$speclen)
        {
            print "Invalid row width, ".$rcount.", row ".($n+1).", max. ".$speclen.":\n";
            var_dump($rows[$n]);
            exit();
        }
    }

    $hline="";
    if ($hlines) {$hline="\\hline\n"; }

    $greyrow=$this->ApplicationObj->LatexWhiteRows();
    $whiterow="";
    if ($greyrows)
    {
        $greyrow=$this->ApplicationObj->LatexGreyRows();
        $whiterow=$this->ApplicationObj->LatexWhiteRows();
    }

    $tablehead=
        "\\begin{small}\n".
        $greyrow.
        "\\begin{tabular}{".$specs."}\n";

    $tablefoot=
        "\\end{tabular}\n".
        $whiterow.
        "\\end{small}\n";



    $headrow="";
    if ($hlines) { $headrow=$hline."\n"; }

    if (is_array($titles) && count($titles)>0)
    {
        $headrow=$hline.$this->LatexHeadRow($titles,$count).$hline.$hline;
    }

    $npages=intval(count($rows)/$nitemspp);
    if (count($rows)-$nitemspp*$npages!=0) { $npages++; }

    //Now generate table
    $tex=
        $pagetitle.
        $tablehead.
        $headrow;

    $nn=1;
    $pageno=1;
    if ($footnumbers)
    {
        $tex.="\\cfoot{".$pageno." de ".$npages."}\n";
    }

    for ($n=0;$n<count($rows);$n++)
    {
        if (is_array($rows[$n]))
        {
            $tex.=$this->LatexTableRow($rows[$n],$count).$hline;
        }

        if ($nn==$nitemspp || !is_array($rows[$n]))
        {
            $tex.=
                $tablefoot;

            $pageno++;
            if ( ($n+1)<count($rows))
            {
                $tex.="\\newpage\n\n";

                if ($footnumbers)
                {
                    $tex.="\\cfoot{".$pageno." de ".$npages."}\n";
                }

                $tex.=
                    $pagetitle.
                    $tablehead.
                    $headrow;
            }

            $nn=0;
        }

        $nn++;

    }

    if ($nn>0)
    {
        $tex.=$tablefoot;
    }

    return $tex;
}

    //*
    //* Runs Latex, saving $latex to $path."/".$texfilename.
    //*

    function RunLatex($texfilename,$latex,$runbibtex=FALSE)
    {
        $cwd=getcwd();

        $path=$this->LatexTmpPath();

        $latexbin="../pdflatex.sh";
        $pdffilename=$texfilename;
        $pdffilename=preg_replace('/\.tex$/',".pdf",$pdffilename);

        $latex=html_entity_decode($latex);
        $latex=$this->Text2Tex($latex);

        $this->MyWriteFile($path."/".$texfilename,$latex);

        $command="$latexbin -interaction nonstopmode ".
                 "-output-directory $path $path/$texfilename >".
                 " $path/latex.log 2>&1";
        $command="$latexbin $path $texfilename";

        //Run pdflatex first time
        $mess=system($command,$res1);

        if ($runbibtex)
        {
            $cwd=getcwd();
            chdir($path);

            $bibtex=preg_replace('/\.tex$/',"",$texfilename);
            system("/usr/bin/bibtex $bibtex > bibtex.log 2>&1");

            chdir($cwd);
        }

        if ($this->RunLatexThrice || $runbibtex)
        {
            //Make sure we run Latex 3 times, so all refs tect are updated
            $mess=system($command,$res1);
            $mess=system($command,$res1);
            $this->UnlinkFiles(array($bibtex.".bib",$bibtex.".blg",$bibtex.".bbl"),$path);
        }

        $logfile=preg_replace('/\.pdf/',".log",$pdffilename);
        $auxfile=preg_replace('/\.pdf/',".aux",$pdffilename);
        $tocfile=preg_replace('/\.pdf/',".toc",$pdffilename);

        $this->UnlinkFiles(array($auxfile,$tocfile,"latex.log"),$path);

        if (is_file($path."/".$pdffilename))
        {
            $this->UnlinkFiles(array($texfilename,$logfile),$path);
            return $pdffilename;
        }
        else
        {
            //print "Res pdflatex: $res1, $mess\n";
            return NULL;
        }
    }

    //*
    //* Trims Latex code.
    //*

    function TrimLatex($latex=array())
    {
        if (is_array($latex)) { $latex=join("",$latex); }

        $latexfilter=array();
        if (!empty($this->ApplicationObj->LatexFilters))
        {
            foreach ($this->ApplicationObj->LatexFilters as $filter => $hash)
            {
                $rfilter=array();
                if (!empty($this->$filter))
                {
                    $rfilter=$this->$filter;
                }
                elseif (!empty($this->ApplicationObj->$filter))
                {
                    $rfilter=$this->ApplicationObj->$filter;
                }

                $pre=$hash[ "PreKey" ];

                if (!empty($hash[ "Object" ]))
                {
                    $obj=$hash[ "Object" ];
                    $this->ApplicationObj->$obj->DatasRead=array_keys($rfilter);
                    $rfilter=$this->ApplicationObj->$obj->ApplyAllEnums($rfilter);
                }

                foreach ($rfilter as $key => $value)
                {
                    $latexfilter[ $pre.$key ]=$value;
                }
            }
        }

        $latex=$this->FilterHash($latex,$latexfilter);
        if (!empty($this->UnitHash))
        {
            $unit=$this->UnitHash;
        }
        elseif (!empty($this->ApplicationObj->UnitHash))
        {
            $unit=$this->ApplicationObj->UnitHash;
        }

        if (!empty($unit))
        {
            $latex=$this->FilterHash($latex,$unit);
        }

        if (
              isset($this->LatexData[ "Filter" ])
              &&
              is_array($this->LatexData[ "Filter" ])
           )
        {
            $latex=$this->FilterHash($latex,$this->LatexData[ "Filter" ]);
        }

        $latex=$this->FilterHash($latex,$this->CompanyHash);
        $latex=$this->FilterObject($latex);


        $latex=preg_replace("/&#92;/",'\\\\',$latex);
        $latex=preg_replace("/&amp;/",'&',$latex);
        $latex=preg_replace("/&nbsp;/",'',$latex);
        //$latex=preg_replace('/&#92;/','\\\\',$latex);
        $latex=preg_replace('/\\"/','"',$latex);
        //$latex=preg_replace('/%/','\%',$latex);
        $latex=preg_replace('/\r/','',$latex);

        $latex=$this->Html2Text($latex);

        return $latex;
    }


    //*
    //* Runs Latex, and displays resulting pdf.
    //*

    function RunLatexPrint($texfilename,$latex,$printpdf=TRUE,$runbibtex=FALSE,$copypdfto=FALSE)
    {
        $path=$this->LatexTmpPath();
        $pdffilename=$this->RunLatex($texfilename,$this->TrimLatex($latex),$runbibtex);
        $logfilename="$path/latex.log";

        $rpdffilename=$path."/".$pdffilename;
        if ($pdffilename && is_file($rpdffilename))
        {
            if ($printpdf)
            {
                $this->SendDocHeader("pdf",$pdffilename);
                print join("",$this->MyReadFile($path."/".$pdffilename));

                if ($copypdfto)
                {
                    rename($rpdffilename,$copypdfto);
                }
                else
                {
                    unlink($path."/".$pdffilename);
                }
            }
            else
            {
                if ($copypdfto)
                {
                    rename($rpdffilename,$copypdfto);
                }
                return $path."/".$pdffilename;
            }
        }
        else
        {
            $this->ApplicationObj->HtmlHead();
            $this->ApplicationObj->HtmlDocHead();

            print "Error gerando latex ($path/$texfilename):<BR>";

            if (!file_exists($logfilename))
            {
                $logfilename=$path."/".preg_replace('/\.tex$/',".log",$texfilename);
            }

            if (file_exists($logfilename))
            {
                print
                    "Logfile:<BR>".
                    join("<BR>",$this->MyReadFile($logfilename));
            }
            else
            {       
                print "No logfile: ".$logfilename."<BR>";
            }

            print "Arquivo gerado:<BR>";
            $lines=$this->MyReadFile($path."/".$texfilename);
            $n=1;
            foreach ($lines as $line)
            {
                $line=preg_replace('/ /',"&nbsp;",$line);
                $nn=sprintf("%04d",$n);
                print $nn." ".$line."<BR>";
                $n++;
            }
        }

        return $pdffilename;
    }


    //*
    //* Trims Latex data value.
    //*

    function TrimLatexValue($value)
    {
        $value=preg_replace("/_/",'\\\\_',$value);
        return $this->Html2Text($value);
    }

    //*
    //* ScanFirstCommand
    //*
    //* Scans array $latex for first appearance of \command[optionals]{compulsories}.
    //* Adds to array the following keys:
    //*
    //* LeadingSkip: Everything before \documentclass
    //* Optional: Optional arguments to \documentclass[...]?
    //* Compulsory: Compulsory arguments to \documentclass[...]?{...}
    //* Document: Everything after \documentclass[...]?{...}
    //*

    function ScanDocclass($latex=array(),$info=array())
    {
        $regex='(.*)\\\\documentclass(\[.*\])?(\{.*\})(.*)';

        $before=array();
        $after=array();
        $optional="";
        $compulsory="";

        $n=0;
        $done=FALSE;
        for (;$n<count($latex) && !$done;$n++)
        {
            if (preg_match('/'.$regex.'/',$latex[$n],$matches))
            {
                array_push($before,$matches[1]);
                $optional=$matches[2];
                $compulsory=$matches[3];
                array_push($after,$matches[4]);

                $done=TRUE;
            }
            else
            {
                array_push($before,$latex[$n]);
            }
        }

        for (;$n<count($latex);$n++)
        {
            array_push($after,$latex[$n]);
        }

        $info[ "LeadingSkip" ]=$before;
        $info[ "Document" ]=$after;
        $info[ "Compulsory" ]=$compulsory;
        $info[ "Optional" ]=$optional;

        return $info;
    }

    //*
    //* Scans preamble for used packages.
    //*

    function ScanPreamblePackages($latex=array(),$info=array())
    {
        $regex='\\\\usepackage(\[[^\[\]]*\])?\{([^\{\}]+)\}';

        $packagehash=array();
        for ($n=0;$n<count($latex);$n++)
        {
            if (preg_match('/'.$regex.'/',$latex[$n],$matches))
            {
                $package=$matches[2];
                $optional=$matches[1];

                $optional=preg_replace('/^\[/',"",$optional);
                $optional=preg_replace('/\]$/',"",$optional);

                $packages=preg_split('/\s*,\s*/',$package);

                foreach ($packages as $id => $package)
                {
                    $packagehash[ $package ]=array
                    (
                     "Package" => $package,
                     "Optional" => $optional,
                    );
                }
           }
        }

        $info[ "Packages" ]=$packagehash;

        return $info;
    }

    //*
    //* ScanTexEnvironment
    //*
    //* Scans array $latex for \begin, end $environment.
    //* Starting/terminating regexp are resp.:
    //*
    //* '(.*)\\\\begin\{'.$environment.'\}(.*)'
    //* '(.*)\\\\end\{'.$environment.'\}(.*)'
    //*
    //* Returns array with keys:
    //*
    //* $environment."_Start": everything that preceeds leading regexp
    //* $environment."_Content": everything between \begin{} and \end{}
    //* $environment."_End": enverything that succeeeds terminating regexp
    //* $environment."_Args": Optional args, eg: \begin{env}[optionalargs]
    //* 
    //*

    function ScanTexEnvironment($environment,$latex=array(),$info=array())
    {
        $beginregex='(.*)\\\\begin\{'.$environment.'\}(\[.*\])?(\{.*\})?(.*)';
        $endregex='(.*)\\\\end\{'.$environment.'\}(.*)';

        $begin=-1;
        $end=-1;

        $n=0;

        $start=array();
        $end=array();
        $content=array();
        $optargs="";
        $compargs="";

        $done=FALSE;
        for (;$n<count($latex) && !$done;$n++)
        {
            if (preg_match('/'.$beginregex.'/',$latex[$n],$matches))
            {
                array_push($start,$matches[1]);
                $optargs=$matches[2];
                $compargs=$matches[3];
                array_push($content,$matches[4]);              


                $optargs=preg_replace('/^\[/',"",$optargs);
                $optargs=preg_replace('/\]$/',"",$optargs);

                $compargs=preg_replace('/^\[/',"",$compargs);
                $compargs=preg_replace('/\]$/',"",$compargs);

                $done=TRUE;
            }
            else
            {
                array_push($start,$latex[$n]);
            }
        }

        $done=FALSE;
        for (;$n<count($latex) && !$done;$n++)
        {
            if (preg_match('/'.$endregex.'/',$latex[$n],$matches))
            {
                array_push($content,$matches[1]);
                array_push($end,$matches[2]);

                $done=TRUE;
            }
            else
            {
                array_push($content,$latex[$n]);
            }
        }

        for (;$n<count($latex);$n++)
        {
            array_push($end,$latex[$n]);
        }

        $info[ $environment."_Start" ]=$start;
        $info[ $environment."_Content" ]=$content;
        $info[ $environment."_End" ]=$end;
        $info[ $environment."_Optional" ]=$optargs;
        $info[ $environment."_Compulsory" ]=$optargs;

        return $info;
   }


    function ProcessLatexDoc($latex=array())
    {
        $info=array();

        $info=$this->ScanDocclass($latex,$info);

        $info=$this->ScanPreamblePackages($info[ "Document" ],$info);

        if ($info[ "Packages" ][ "inputenc" ] && $info[ "Packages" ][ "inputenc" ][ "Optional" ]=='latin1')
        {
            $rlatex=array();
            foreach ($info[ "Document" ] as $id => $text)
            {
                $text=iconv("ISO-8859-1", "UTF-8//TRANSLIT", $text);
                array_push($rlatex,$text);
            }

            $info[ "Document" ]=$rlatex;
        }

        $latex=$info[ "Document" ];
        $info=$this->ScanTexEnvironment("document",$latex,$info);


        $latex=$info[ "document_Content" ];
        $info=$this->ScanTexEnvironment("abstract",$latex,$info);

        $latex=$this->MergeLists($info[ "abstract_Start" ],$info[ "abstract_End" ]);

        $info=$this->ScanTexEnvironment("thebibliography",$latex,$info);


        $info[ "Body" ]=$this->MergeLists($info[ "thebibliography_Start" ],$info[ "thebibliography_End" ]);

        return $info;
    }

    function LatexTables($head,$tail,$glue,$titles,$tables,$specs="")
    {
        foreach ($tables as $id => $table)
        {
            $tables[ $id ]=
                $head.
                $this->LatexTable($titles,$table,$specs,FALSE,FALSE).
                $tail;
        }
        
        return join($glue,$tables);
    }


    function LatexOnePage($latex,$width="27.5cm",$scale=1.0)
    {
        return 
            "\\scalebox{".$scale."}{\n".
            "\\makebox[".$width."][c]{\n".
            "\\begin{minipage}[t]{".$width."}\n".
            "\\begin{center}\n".
            $latex.
            "\\end{center}\n".
            "\\end{minipage}\n".
            "}\n}\n\n";
    }

    function LatexDateSigner($width=0.5)
    {
        return "\\underline{\\hspace{".$width."cm}}/\\underline{\\hspace{".$width."cm}}/\\underline{\\hspace{".(2.0*$width)."cm}}";
    }

    function LatexAlign($content,$align="")
    {
        $latex="";
        if (!empty($align))
        {
            $latex.="\\begin{".$align."}\n";
        }

        $latex.=$content."\n";

        if (!empty($align))
        {
            $latex.="\\end{".$align."}\n";
        }

        return $latex;
    }


    function MiniPage($width,$content,$pos='t',$align="")
    {
        $latex="\\begin{minipage}[".$pos."]{".$width."cm}\n";
        if (!empty($align))
        {
            $latex.=$this->LatexAlign($content,$align=="");
        }
        $latex.="\\end{minipage}\n";

        return $latex;
    }

    function LatexBox($width,$content,$frame=FALSE,$minipage=FALSE,$pos='t',$align="")
    {
        $box="mbox";
        if ($frame) { $box="fbox"; }

        $latex="\\".$box."{\n";
        if ($minipage)
        {
            $latex.=$this->MiniPage($width,$content,$pos,$align);
        }
        else
        {
            $latex.=$content;
        }
        $latex.="}\n";
        
        return $latex;
            
    }

    function ShowLatexCode($latex)
    {
        print preg_replace
        (
           '/\n/',
           "<BR>",
           preg_replace
           (
              '/ /',
              "&nbsp;",
              $latex
           )
        );
        exit();
    }
    //* function LatexSwitchLandscape, Parameter list: $student=array()
    //*
    //* Generate newgeometry entry, switching to landscape.
    //*

    function LatexSwitchLandscape()
    {
        return
            "\\newgeometry\n".
            "{\n".
            "a4paper,\n".
            "landscape,\n".
            "left=0.5cm,\n".
            "right=0.5cm,\n".
            "top=0.75cm,\n".
            "bottom=0.75cm,\n".
            "includehead,\n".
            "includefoot,\n".
            "headheight=2.5cm,\n".
            "headsep=0.75cm\n".
            "}\n\n";
    }

    //* function LatexSwitchPortrait, Parameter list: $student=array()
    //*
    //* Generate newgeometry entry, switching to landscape.
    //*

    function LatexSwitchPortrait()
    {
        return
            "\\newgeometry\n".
            "{\n".
            "  a4paper,\n".
            "  portrait,\n".
            "  left=0.5cm,\n".
            "  right=0.5cm,\n".
            "  top=0.75cm,\n".
            "  bottom=0.75cm,\n".
            "  includehead,\n".
            "  includefoot,\n".
            "  headheight=2.5cm,\n".
            "  headsep=0.75cm\n".
            "}\n\n";
    }
}
?>