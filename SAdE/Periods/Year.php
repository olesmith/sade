<?php


class PeriodsYear extends PeriodsMonths
{

    //*
    //* function HandleYear, Parameter list:
    //*
    //* Overrides Table HandleShow:
    //*
    //* 1: Checks if all dates between start and end date are in Dates table -
    //*    adding necessary dates.
    //*

    function HandleYear()
    {
        print
            $this->H(1,"Adicionar/Verificar Ano").
            $this->H(2,"Verificar Períodos e Datas no Ano, Adicionando Inexistentes").
            $this->StartForm().
            $this->Center
            (
               $this->MakeInput("Year",$this->GetPOST("Year"),4).
               $this->MakeHidden("AddYear",1).
               $this->Button("submit","GO!")
            ).
            $this->EndForm().
            "";

        $year=$this->GetPOST("Year");

        if (
              $this->GetPOST("AddYear")==1
              &&
              preg_match('/^\d\d\d\d$/',$year)
           )
        {
            //First, add all days of year - if necessary
            $this->CheckDates(array("Year" => $year));

            $table=array($this->B(array("No.","Ano","Tipo","Sub Per.","Início","Fim","Status")));
            $n=1;
            for ($type=0;$type<count($this->ItemData[ "Type" ][ "Values" ]);$type++)
            {
                $nsems=$this->ItemData[ "Type" ][ "NSemesters" ][ $type ];
                for ($sem=1;$sem<=$nsems;$sem++)
                {
                    $item=$this->SelectUniqueHash
                    (
                       "",
                       array
                       (
                          "Year" => $year,
                          "Type" => $type+1,
                          "Semester" => $sem,
                       ),
                       TRUE
                    );

                    $msg="";
                    if (empty($item))
                    {
                        $item=$this->NewPeriod($year,$type,$sem);
                        $msg="";

                        $row=array
                        (
                           $this->B($n),
                           $item[ "Year" ],
                           $this->GetEnumValue("Type",$item),
                           $item[ "Semester" ],
                           $this->DateID2Name($item[ "StartDate" ]),
                           $this->DateID2Name($item[ "EndDate" ]),
                        );

                        $res=$this->Add($msg,$item);

                        if ($res) { array_push($row,"Adicionado!"); }
                        else      { array_push($row,"Erro! ".$msg); }

                        array_push($table,$row);
                        $n++;
                    }
                    else
                    {
                        $row=array
                        (
                           $this->B($n),
                           $item[ "Year" ],
                           $this->GetEnumValue("Type",$item),
                           $item[ "Semester" ],
                           $this->DateID2Name($item[ "StartDate" ]),
                           $this->DateID2Name($item[ "EndDate" ]),
                           "Já!"
                        );

                        array_push($table,$row);
                        $n++;
                    }

                }
            }
            print
                $this->H(3,"Períodos em ".$year.":").
                $this->HtmlTable("",$table);

        }

             

    }
}

?>