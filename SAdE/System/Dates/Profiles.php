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
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
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
      ),
      'Add' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
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

         "Clerk" => 1,
         "Teacher"     => 0,
         "Secretary" => 1,
     ),
      'EditList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 0,
         "Secretary" => 1,
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
      ),
      'LatexList' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
      ),
      'Print' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
     ),
      'PrintList' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
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
      'NewPassword' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 0,
      ),
      'YearDates' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 0,
         "Secretary" => 1,
      ),
   ),
   'Menues' => array
   (
      'Singular' => array
      (
         'Public' => array
         (
            'Show' => 1,
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
            'Show' => 1,
            'Edit' => 1,
         ),
         'Clerk' => array
         (
            'Show' => 1,
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
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
            'YearDates' => 1,
            'EditList' => 1,
            'Profiles' => 1,
         ),
         'Clerk' => array
         (
            'Search' => 1,
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
            'EditList' => 1,
            'YearDates' => 1,
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
            'Search' => 1,
         ),
        'Teacher' => array
         (
         ),
         'Secretary' => array
         (
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