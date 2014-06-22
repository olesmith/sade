<?php
array
(
   'Access' => array
   (
      'Public' => 1,
      'Person' => 0,
      'Admin' => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   'Actions' => array
   (
      'Search' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'Add' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 1,
         "Coordinator" => 0,
      ),
      'ComposedAdd' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Copy' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Show' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
      ),
      'ShowList' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
      ),
      'Edit' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,
         //'AccessMethod' => "MayEditTeacher",

         "Clerk" => 1,
         "Teacher"     => 0,
         "Secretary" => 1,
         "Coordinator" => 1,
     ),
      'EditList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 0,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'Delete' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Latex' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'LatexList' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'Print' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
     ),
      'PrintList' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'Download' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 0,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Export' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 0,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Import' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Zip' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 0,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Process' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'SysInfo' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Profiles' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
     'Log' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Backup' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Setup' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'Periods' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
     ),
      'Schedule' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
     ),
   ),
   'Menues' => array
   (
      'Singular' => array
      (
         'Public' => array
         (
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
            'Show' => 1,
            'Edit' => 1,
            'Schedule' => 1,
            'Delete' => 1,
         ),
         'Clerk' => array
         (
            'Show' => 1,
            'Edit' => 1,
            'Schedule' => 1,
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
            'Show' => 1,
            'Edit' => 1,
            'Schedule' => 1,
            'Delete' => 1,
         ),
         'Coordinator' => array
         (
            'Show' => 1,
            'Edit' => 1,
            'Schedule' => 1,
            'Delete' => 1,
         ),
      ),
      'Plural' => array
      (
         'Public' => array
         (
            'Search' => 1,
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
            'Add' => 1,
            'Search' => 1,
            'EditList' => 1,
            'Profiles' => 1,
         ),
         'Clerk' => array
         (
            'Add' => 1,
            'Search' => 1,
            'EditList' => 1,
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
            'Add' => 1,
            'Search' => 1,
            'EditList' => 1,
         ),
         'Coordinator' => array
         (
            'Add' => 1,
            'Search' => 1,
            'EditList' => 1,
         ),
      ),
      'SingularPlural' => array
      (
         'Public' => array
         (
            'Search' => 1,
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
            'Add' => 1,
            'Search' => 1,
            'EditList' => 1,
            'Profiles' => 1,
         ),
         'Clerk' => array
         (
            'Add' => 1,
            'Search' => 1,
            'EditList' => 1,
            'Search' => 1,
         ),
         'Coordinator' => array
         (
            'Search' => 1,
            'EditList' => 1,
            'Search' => 1,
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
            'Add' => 1,
            'Search' => 1,
            'EditList' => 1,
            'Search' => 1,
         ),
      ),
      'ActionsPlural' => array
      (
         'Public' => array
         (
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
            'Import' => 1,
         ),
         'Clerk' => array
         (
         ),
          'Secretary' => array
         (
         ),
      ),
      'ActionsSingular' => array
      (
         'Public' => array
         (
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
            "Periods" => 1,
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
         ),
      ),
   ),
);
?>