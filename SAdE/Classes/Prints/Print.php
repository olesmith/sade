<?php

include_once("Classes/Prints/Print/SpecTable.php");
include_once("Classes/Prints/Print/SpecForm.php");
include_once("Classes/Prints/Print/Daylies.php");
include_once("Classes/Prints/Print/Marks.php");

class ClassesPrintsPrint extends ClassesPrintsPrintMarks
{
    //ClassesSchedule
    //*
    //* function TestIfAnythingToPrint, Parameter list: $class=array(),$months
    //*
    //* Tests if there is anything to print - if not gives a message, preventing
    //* pdflatex from giving an empty document error.
    //*

    function TestIfAnythingToPrint($class=array(),$months)
    {
        $ngenerated=0;

        $keys=array_keys($_POST);
        $daylies    = preg_grep('/^'.$this->DaylyKey.'_/',$keys);
        $marks      = preg_grep('/^'.$this->MarksKey.'_/',$keys);
        $signatures = preg_grep('/^'.$this->SignaturesKey.'_/',$keys);

        $keys=array_merge($daylies,$marks,$signatures);

        $generate=array();
        foreach ($keys as $key)
        {
            $value=$this->GetPOST($key);
            if ($value==1)
            {
                $generate[ $key ]=1;
            }
        }


        if (empty($generate))
        {
            $this->LatexMode=FALSE;
            $this->PrintDocHeadsAndLeftMenu();
            print $this->H(4,"Nenhuma Disciplina/Mes/Avaliação Selecionado!");
            return;
        }


         return $generate;

        $ngenerated=count($generate);

        return $ngenerated;
    }


     //*
    //* function PrintClassDaylies, Parameter list: $class=array(),$months
    //*
    //* Generates daylies specified on form, generating latex and in turn PDF.
    //*

    function PrintClassDaylies($class=array(),$months)
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }

        $generated=$this->TestIfAnythingToPrint($class,$months);

        if (count($generated)==0) { return; }

        $this->LatexMode=TRUE;

        $latex="";
        if ($class[ "DayliesOrientation" ]==1)
        {
            //Landscape
            $latex.=$this->ApplicationObj->ClassesObject->LatexHeadLand();
        }
        else
        {
            $latex.=$this->ApplicationObj->ClassesObject->LatexHead();
        }
        $latex.="\\begin{center}";

        foreach ($this->ApplicationObj->Discs as $disc)
        {
            $teacher="";
            if ($disc[ "Teacher" ]>0)
            {
                $teacher=$this->ApplicationObj->PeopleObject->MySqlItemValue("","ID",$disc[ "Teacher" ],"Name");
            }

            $this->DaylyHash[ "Discipline" ]=$disc[ "Name" ];
            $this->DaylyHash[ "Teacher" ]=$teacher;

            foreach ($months as $monthid => $month)
            {
                $keys=array
                (
                   $this->ClassDayliesPrintDiscMonthKey($disc[ "ID" ],$monthid),
                   $this->ClassDayliesPrintDiscMonthKey("All",$monthid),
                   $this->ClassDayliesPrintDiscMonthKey($disc[ "ID" ],"All"),
                   $this->ClassDayliesPrintDiscMonthKey("All","All"),
                );

 
                $include=FALSE;
                foreach ($keys as $key)
                {
                    if (!empty($generated [ $key ]))
                    {
                        $include=TRUE;
                     }
                }

                if ($include)
                {
                   $this->DaylyHash[ "Month" ]=$this->Months[ $monthid-1 ];
                    
                    $rlatex=$this->LatexClassDayly($class,$disc,$month);
                    $latex.=$rlatex;
                }
            }

            $types=array
            (
               "Marks"      => array
               (
                  "Key"    => "MarksKey",
                  "Method" => "LatexClassMarksSheet",
               ),
               "Signatures"     => array
               (
                  "Key"    => "SignaturesKey",
                  "Method" => "LatexSignaturesSheet",
               )
            );

            foreach ($types as $type => $hash)
            {
                $rkey=$hash[ "Key" ];
                $rkey=$this->$rkey;
                $method=$hash[ "Method" ];

                //indent
                $keys=array
                (
                   $rkey."_".$disc[ "ID" ],
                   $rkey."_All",
                );

                $include=FALSE;
                foreach ($keys as $key)
                {
                    if (!empty($generated [ $key ]))
                    {
                        $include=TRUE;
                     }
                }

                if ($include)
                {
                    $latex.=$this->$method($class,$disc);
                }
            }

            if ($class[ "DayliesBackPage" ]==1 && $class[ "DayliesTwoPage" ]==2)
            {
                $latex.="\n\n\\cleardoublepage\n\n";
            }
        }

        $latex.=
            "\\end{center}".
            $this->ApplicationObj->ClassesObject->LatexTail().
                "";


        $texfile=$this->TexFileName($class,"Diarios");

        $this->RunLatexPrint($texfile,$latex);
        exit();
    }
}

?>