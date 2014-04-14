<?php

class Help extends ApplMsgs
{
    //*
    //* function HelpFile, Parameter list: 
    //*
    //* Returns name of help file.
    //*

    function HelpFile()
    {
        $helpfile="System/Help/".$this->ModuleName.".html";
        if (file_exists($helpfile))
        {
            return $helpfile;
        }

        return FALSE;
    }

    //*
    //* function AddHelpAction, Parameter list: 
    //*
    //* Adds help action, if module helpfile exists.
    //*

    function AddHelpAction()
    {
        if ($this->HelpFile())
        {
            $this->Module->Actions[ "Help" ]=array
            (
               "Href"     => "",
               "HrefArgs" => "ModuleName=#Module&Action=Help",
               "Name"    => "Ajuda",
               "Title"     => "Ajuda",
               "Public"   => 1,
               "Person"   => 1,
               "Admin"   => 1,
               "Distributor"   => 1,
               "Coordinator"   => 1,
               "Teacher"   => 1,
               "Handler"   => "HandleHelp",
            );
        }
    }

    //*
    //* function AddHelp2Menues, Parameter list: 
    //*
    //* Adds help action to menues.
    //*

    function AddHelp2Menues()
    {
        if ($this->HelpFile())
        {
            foreach (array("Plural","Singular") as $mode)
            {
                foreach (array("ActionsActions","Actions") as $smenu)
                {
                    $menu=$smenu.$mode;
                    if (!empty($this->Module->$menu))
                    {
                        array_push($this->Module->$menu,"Help");
                        break;
                    }
                }
            }
        }
    }

    //*
    //* function HandleHelp, Parameter list:
    //*
    //* Creates Help Screen
    //*

    function HandleHelp()
    {
        if ($this->HelpFile())
        {
            print $this->HtmlTable
            (
               "",
               array
               (
                  $this->H(1,"Ajuda:").
                  join("",$this->MyReadFile($this->HelpFile())),
               )
            );
        }
    }

}
?>