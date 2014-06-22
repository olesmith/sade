<?php


class ProfileInit extends CGI
{
    //*
    //* function ModuleProfileFile, Parameter list: $module=""
    //*
    //* Returns name of Module profile file (Profiles.php)
    //* to read. Calls $this->ApplicationObj->ModuleProfileFile($module)
    //* for the task. Supposed as a mean to redirect this behaviour, when
    //* needed.
    //*

    function ModuleProfileFile($module="")
    {
        return $this->ApplicationObj->ModuleProfileFile($module);
    }


    //*
    //* function InitProfile, Parameter list: $module=""
    //*
    //* Initilizes Profile class, ie:
    //*
    //* Read module profile from Coomon & $this->Profile .php, into $this->ProfileHash.
    //*

    function InitProfile($module="")
    {
        if (empty($module)) { $module=$this->ModuleName; }
        $modfile=$this->ModuleProfileFile($module);
        if (!file_exists($modfile))
        {
            print "No Profiles file: ".$modfile."<BR>";exit();
        }


        $profiles=$this->ReadPHPArray($modfile);

        $this->InitProfileAccess($profiles,$module);
        $this->InitProfileActions($profiles,$module);
        $this->InitProfileMenues($profiles,$module);
    }


    //*
    //* function InitProfileAccess, Parameter list: $profiles,$module=""
    //*
    //* Initilizes Profile class, Access part.
    //*

    function InitProfileAccess($profiles,$module="")
    {
        $this->ProfileHash=array();
        $this->ProfileHash[ "Access" ]=0;
        if (
              !empty($profiles[ "Access" ][ $this->LoginType ])
              &&
              $profiles[ "Access" ][ $this->LoginType ]>0
           )
        {
            $this->ProfileHash[ "Access" ]=$profiles[ "Access" ][ $this->LoginType ];
        }

        if (
            !empty($profiles[ "Access" ][ $this->Profile ])
            &&
            $profiles[ "Access" ][ $this->Profile ]>0
           )
        {
            $this->ProfileHash[ "Access" ]=$profiles[ "Access" ][ $this->Profile ];
        }


        /* if ($profiles[ "Access" ][ $this->Profile ]>0) */
        /* { */
        /*     $this->ProfileHash[ "Access" ]=$profiles[ "Access" ][ $this->Profile ]; */
        /* } */
    }

    //*
    //* function InitProfileActions, Parameter list: $profiles,$module=""
    //*
    //* Initilizes Profile class, Actions part.
    //*

    function InitProfileActions($profiles,$module="")
    {
        $this->ProfileHash[ "Actions" ]=array();
        foreach ($profiles[ "Actions" ] as $action => $perms)
        {
            if (!isset($profiles[ "Actions" ][ $action ][ $this->LoginType ]))
            {
                $profiles[ "Actions" ][ $action ][ $this->LoginType ]=0;
            }
            $this->ProfileHash[ "Actions" ][ $action ]=$profiles[ "Actions" ][ $action ][ $this->LoginType ];

            if (isset($profiles[ "Actions" ][ $action ][ $this->Profile ]))
            {
                $this->ProfileHash[ "Actions" ][ $action ]=$profiles[ "Actions" ][ $action ][ $this->Profile ];
            }
        }
    }

    //*
    //* function InitProfileMenues, Parameter list: $profiles,$module=""
    //*
    //* Initilizes Profile class, (horisontal) Menus part.
    //*

    function InitProfileMenues($profiles,$module="")
    {
        $this->ProfileHash[ "Menues" ]=array();
        foreach (array_keys($this->DefaultMenues) as $menu)
        {
            $this->ProfileHash[ "Menues" ][ $menu ]=array();

            foreach ($this->DefaultMenues[ $menu ] as $action=> $value)
            {
                if ($value>0)
                {
                    $this->ProfileHash[ "Menues" ][ $menu ][ $action ]=$value;
                }
            }

            foreach ($profiles[ "Menues" ][ $menu ][ $this->LoginType ] as $action => $value)
            {
                if ($value>0)
                {
                    $this->ProfileHash[ "Menues" ][ $menu ][ $action ]=1;;
                }
            }

            if (!isset($profiles[ "Menues" ][ $menu ][ $this->Profile ])) { continue; }

            foreach ($profiles[ "Menues" ][ $menu ][ $this->Profile ] as $action => $value)
            {
                if ($value>0)
                {
                    $this->ProfileHash[ "Menues" ][ $menu ][ $action ]=1;
                }
            }

            $this->ProfileHash[ "Menues" ][ $menu ]=array_keys($this->ProfileHash[ "Menues" ][ $menu ]);
        }
    }
}
?>