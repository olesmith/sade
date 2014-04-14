<?php

include_once("Class/Disc/Contents/Search.php");

include_once("Class/Disc/Contents/Calendar.php");
include_once("Class/Disc/Contents/Row.php");
include_once("Class/Disc/Contents/Titles.php");
include_once("Class/Disc/Contents/Table.php");
include_once("Class/Disc/Contents/Latex.php");
include_once("Class/Disc/Contents/CGI.php");
include_once("Class/Disc/Contents/Update.php");
include_once("Class/Disc/Contents/Handle.php");

class ClassDiscContents extends ClassDiscContentsHandle
{
    var $Dates="Common";
    var $Date2ContentsData=array("SortKey","Month","Semester");
    var $ReadDateDatas=array("Year","Semester","Month","WeekNo","WeekDay","Date","SortKey");
    var $DaylyDateDatas=array("Year","Semester","Month","WeekNo","WeekDay");
    var $DaylyContentDatas=array("Date","Weight","Content");


    //*
    //* Variables of ClassDiscNLessons class:
    //*

    //*
    //*
    //* Constructor.
    //*

    function ClassDiscContents($args=array())
    {
        $this->Hash2Object($args);
        $this->AlwaysReadData=array();
        $this->Sort=array("DateKey");
    }


    //*
    //* function Content2Trimester, Parameter list: &$content,$period=array()
    //*
    //* Retrieves Content Date hash, and detects trimester.
    //* Updates $content and returns trimester.
    //*

    function Content2Trimester(&$content,$period=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period; }

        $date=$this->ApplicationObj->DatesObject->SelectUniqueHash
        (
           "",
           array("ID" => $content[ "Date" ]),
           FALSE,
           array("ID","SortKey")
        );

        $semester=$this->ApplicationObj->PeriodsObject->Date2Trimester($period,$date);

        if ($semester!=$content[ "Semester" ])
        {
            $this->MySqlSetItemValue
            (
               "",
               "ID",
               $content[ "ID" ],
               "Semester",
               $semester
            );

            $content[ "Semester" ]=$semester;
        }

        return $semester;
   }

    //*
    //* function Contents2Trimester, Parameter list: $period=array()
    //*
    //* Updates all contents semester keys, and sorts by the same key.
    //*

    function Contents2Trimester($period=array())
    {
        if (empty($period)) { $period=$this->ApplicationObj->Period; }

        $this->ApplicationObj->Contents=$this->CGI2Contents
        (
           array("ID","Date","DateKey","Weight","Month","Semester")
        );


        $rcontents=array();
        foreach (array_keys($this->ApplicationObj->Contents) as $id)
        {
            $semester=$this->Content2Trimester
            (
               $this->ApplicationObj->Contents[ $id ],
               $period
            );

            if (empty($rcontents[ $semester ]))
            {
                $rcontents[ $semester ]=array();
            }

            array_push($rcontents[ $semester ],$this->ApplicationObj->Contents[ $id ]);
        }

        $this->ApplicationObj->Contents=$rcontents;
   }


    //*
    //* function GetContentsDateKeys, Parameter list: 
    //*
    //* Gets the dates referenced in $this->ApplicationObj->Contents.
    //*

    function GetContentsDateKeys()
    {
        $dates=array();
        foreach ($this->ApplicationObj->Contents as $semester => $contents)
        {
            foreach ($contents as $content)
            {
                $datekey=$this->ApplicationObj->DatesObject->ID2SortKey($content[ "Date" ]);
                $dates[ $datekey ]=1;
            }
        }

        $dates=array_keys($dates);
        sort($dates);

        return $dates;
    }

    //*
    //* function SplitPeriodDates, Parameter list:
    //*
    //* Splits dates.
    //*

    function SplitPeriodDates($period)
    {
        $this->Dates=$this->ApplicationObj->PeriodsObject->PeriodDayliesDates($period);

        $maxnmonths=0;
        for ($per=1;$per<=$this->ApplicationObj->Period[ "NPeriods" ];$per++)
        {
            $this->Dates[ $per ]=$this->ApplicationObj->PeriodsObject->SeparateDaysPerMonth($this->Dates[ $per ]);

            $nmonths=0;
            foreach (array_keys($this->Dates[ $per ]) as $month)
            {
                $this->Dates[ $per ][$month  ]=
                    $this->ApplicationObj->PeriodsObject->SeparateDaysPerWeekNos($this->Dates[ $per ][$month  ]);
                $nmonths++;
            }

            $maxnmonths=$this->Max($maxnmonths,$nmonths);
        }

        return $maxnmonths;
    }

    //*
    //* function ReadContentDates, Parameter list: $contents
    //*
    //* Reads dates referenced by $contents.
    //*

    function ReadContentDates($contents)
    {
        $dateids=array();
        foreach ($contents as $content)
        {
            //Omit repeated dates 
            $dateids[ $content[ "Date" ] ]=1;
        }

        $datekeys=$this->ApplicationObj->PeriodsObject->DayliesSortKeys($this->ApplicationObj->Period);

        $dates=array();
        foreach (array_keys($dateids) as $dateid)
        {
            $dates[ $dateid ]=$this->ApplicationObj->DatesObject->SelectUniqueHash
            (
               "",
               array("ID" => $dateid),
               FALSE,
               $this->ReadDateDatas
            );

            $this->ApplicationObj->PeriodsObject->Date2Trimester
            (
               $this->ApplicationObj->Period,
               $dates[ $dateid ],
               $datekeys
            );
        }

        return $dates;
    }


    //*
    //* function MakeContentsMenu, Parameter list: $disc
    //*
    //* Genrerates sub horisontal menu for Contents module.
    //*

    function MakeContentsMenu($disc)
    {
        print $this->ApplicationObj->ClassesObject->MakeActionMenu
        (
           array
           (
            //"DaylyContentsDates",
            //"DaylyContents",
              "DaylyContentsPrint",
           ),
           "ptablemenu",
           $disc[ "ID" ]
        );
    }

    //*
    //* function MayDelete, Parameter list: $content 
    //*
    //* Determines if Content mys be deleted, that is:
    //* Content field empty and no absences registered.
    //*

    function MayDelete($content)
    {
        $res=TRUE;
        if (preg_match('/\S/',$content[ "Content" ])) { $res=FALSE; }

        $nentries=$this->ApplicationObj->ClassDiscAbsencesObject->RowSum
        (
           "",
           array
           (
              "Class" => $this->ApplicationObj->Class[ "ID" ],
              "Disc" => $this->ApplicationObj->Disc[ "ID" ],
              "Content" => $content[ "ID" ],
            ),
           "Weight"
        );

        if ($nentries>0) { $res=FALSE; }

        return $res;
    }

    //*
    //* function ReadDaylyContents, Parameter list: $class=array(),$disc=array()
    //*
    //* Reads Dayly contents from DB.
    //*

    function ReadDaylyContents($class=array(),$disc=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        if (empty($disc))  { $disc =$this->ApplicationObj->Disc; }

        $chs=array
        (
           "Period" => 0,
           "SortKey" => array(),
           "Month" => array(),
           "Semester" => array()
        );

        $contents=$this->SelectHashesFromTable
        (
           "",
           array
           (
              "Class" => $class[ "ID" ],
              "Disc" => $disc[ "ID" ],
           ),
           array(),
           FALSE,
           "DateKey"
        );

        foreach ($contents as $content)
        {
            $date=$this->ApplicationObj->DatesObject->ReadDate
            (
               $content[ "Date" ],
               array(),
               array("ID","Month","SortKey")
            );

            //Per Period
            $chs[ "Period" ]+=$content[ "Weight" ];

            //Per date (SortKey)
            $key="SortKey";
            $value=$date[ $key ];
            if (!isset($chs[ $key ][ $value ]))
            {
                $chs[ $key ][ $value ]=0;
            }

            $chs[ $key ][ $value ]+=$content[ "Weight" ];

            //Per Month
            $key="Month";
            $value=$date[ $key ];
            if (!isset($chs[ $key ][ $value ]))
            {
                $chs[ $key ][ $value ]=0;
            }
            $chs[ $key ][ $value ]+=$content[ "Weight" ];

                
            //Per Semester
            $key="Semester";
            $value=$date[ $key ];
            if (!isset($chs[ $key ][ $value ]))
            {
                $chs[ $key ][ $value ]=0;
            }
            $chs[ $key ][ $value ]+=$content[ "Weight" ];

        }

        return $chs;
    }

    //*
    //* function ReadDiscContents, Parameter list: $class=array(),$disc=array()
    //*
    //* Reads Disc contents from DB.
    //*

    function ReadDiscContents($class=array(),$disc=array())
    {
        if (empty($class)) { $class=$this->ApplicationObj->Class; }
        if (empty($disc))  { $disc =$this->ApplicationObj->Disc; }

        return $this->SelectHashesFromTable
        (
           "",
           array
           (
              "Class" => $class[ "ID" ],
              "Disc" => $disc[ "ID" ],
           ),
           array(),
           FALSE,
           "DateKey,ID"
        );
    }


}

?>