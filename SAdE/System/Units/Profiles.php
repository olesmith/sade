<?php
array
(
   'Access' => array
   (
      'Public' => 1,
      'Person' => 0,
      'Secretary' => 1,
      'Coordinator' => 1,
      
      'Nurse' => 1,
      
      'Admin' => 1,
      "Clerk" => 1,
      "Teacher"     => 1,
   ),
   'Actions' => array
   (
      'Search' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
         
         
         
         'Admin' => 1,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Add' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
         
         
         
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Copy' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
         
         
         
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Show' => array
      (
         'Public' => 0,
         'Person' => 1,
         'Secretary' => 1,
         'Coordinator' => 1,
         
         
         
         'Admin' => 1,
         "Clerk" => 1,
         "Teacher"     => 1,
      ),
      'ShowList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
         
         
         
         'Admin' => 1,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Edit' => array
      (
         'Public' => 0,
         'Person' => 1,
         'Secretary' => 1,
         'Coordinator' => 1,
         
         
         
         'Admin' => 1,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'EditList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
         
         
         'Admin' => 1,
         
      ),
      'Delete' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
        
         
         
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Latex' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
         
         
         'Admin' => 1,
         
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'LatexList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
        
         
         
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Print' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
    
         
         
         'Admin' => 1,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'PrintList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
         
         
         
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Download' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 1,
         'Coordinator' => 1,
         
         
         
         'Admin' => 1,
         "Clerk" => 1,
         "Teacher"     => 1,
      ),
      'Export' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         
         
         
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Zip' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
         
         
         
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'NewPassword' => array
      (
         'Public' => 0,
         'Person' => 1,
         'Secretary' => 1,
         
         
         
         'Admin' => 1,
      ),
      'MyUnit' => array
      (
         'Public' => 1,
         'Person' => 0,
         'Coordinator' => 1,
         'Secretary' => 2,
         
         
         
         'Admin' => 1,
      ),
      'Profiles' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Coordinator' => 0,
         
         
         
         'Admin' => 1,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'ComposedAdd' => array
      (
         'Public' => 0,
         'Person' => 0,
         
         
         
         'Coordinator' => 0,
         'Secretary' => 0,
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Import' => array
      (
         'Public' => 0,
         'Person' => 0,
         
         
         
         'Coordinator' => 0,
         'Secretary' => 0,
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'Process' => array
      (
         'Public' => 0,
         'Person' => 0,
         
         
         
         'Secretary' => 0,
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
      ),
      'SysInfo' => array
      (
         'Public' => 0,
         'Person' => 0,
         

         'Coordinator' => 0,
         'Secretary' => 0,
         'Admin' => 0,
         "Clerk" => 0,
         "Teacher"     => 0,
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
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
         ),
         'Coordinator' => array
         (
         ),
         'Admin' => array
         (
            'Edit' => 1,
            'Copy' => 1,
            'Delete' => 1,
         ),
      ),
      'Plural' => array
      (
         'Public' => array
         (
         ),
         'Person' => array
         (
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Coordinator' => array
         (
         ),
         'Secretary' => array
         (
         ),
         'Admin' => array
         (
          "Search",
          "EditList",
         ),
      ),
      'SingularPlural' => array
      (
         'Public' => array
         (
         ),
         'Person' => array
         (
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Coordinator' => array
         (
         ),
         'Secretary' => array
         (
         ),
        'Admin' => array
         (
          "Search",
          "EditList",
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
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Coordinator' => array
         (
         ),
         'Secretary' => array
         (
         ),
         'Admin' => array
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
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Coordinator' => array
         (
         ),
         'Secretary' => array
         (
         ),
         'Admin' => array
         (
         ),
      ),
   ),
);
?>