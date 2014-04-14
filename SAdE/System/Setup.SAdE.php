<?php

    array
    (
       "DefaultAction" => "Start",
       "DefaultProfile" => 4,
       "LoginPermissionVars" => array(),
       "SqlTableVars" => array("SqlVars"),
       "CommonData" => array
       (
          "Hashes" => array
          (
             "Login" => "Auth.Data.php",
             "MySql" => ".DB.php",
             "Mail" => ".Mail.php",
          ),
       ),
       "AllowedModules" => array
       (
        //"People",
          "Units",
       ),
       "ModuleDependencies" => array
       (
        //"People" => array("Units"),
          "Units" => array(),
       ),
       "SubModulesVars" => array
       (
          /* "People" => array */
          /* ( */
          /*    "SqlObject" => "PeopleObject", */
          /*    "SqlClass" => "People", */
          /*    "SqlFile" => "People.php", */
          /*    "SqlHref" => TRUE, */
          /*    "SqlTable" => "People", */
          /*    "SqlDerivedData" => array("Name","Email"), */
          /*    "SqlFilter" => "#Name", */
          /* ), */
         "Units" => array
          (
             "SqlObject" => "UnitsObject",
             "SqlClass" => "Units",
             "SqlFile" => "Units.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Units",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
          ),
        ),
       "PermissionVars" => array
       (
          "Vars" => array(),
          "People" => array
          (
          ),
          "Units" => array
          (
          ),
       ),
    );


?>
