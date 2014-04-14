<?php
array
(
   'Access' => array
   (
      'Public' => 0,
      'Person' => 0,
      'Secretary' => 1,
      'Teacher' => 1,
      'Clerk' => 1,
      'Teacher' => 1,
      'Admin' => 1,
   ),
   'Actions' => array
   (
      'Search' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 1,
         'Clerk' => 1,
         'Teacher' => 0,
         'Admin' => 1,
      ),
      'Add' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 1,
         'Clerk' => 0,
         'Teacher' => 0,
         'Admin' => 1,
      ),
      'Copy' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         'Admin' => 1,
      ),
      'Show' => array
      (
         'Public' => 0,
         'Person' => 1,
         'Secretary' => 1,
         'Clerk' => 1,
         'Teacher' => 1,
         'Admin' => 1,
      ),
      'ShowList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 1,
         'Admin' => 1,
      ),
      'Edit' => array
      (
         'Public' => 0,
         'Person' => 1,
         'Secretary' => 1,
         'Secretary' => 1,
         'Clerk' => 1,
         'Teacher' => 1,

         'Admin' => 1,
      ),
      'EditList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 1,
         'Admin' => 1,
      ),
      'Delete' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         
         
         'Admin' => 0,
         
      ),
      'Latex' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         
         
         'Admin' => 1,
         
      ),
      'LatexList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         
         
         'Admin' => 1,
         
      ),
      'Print' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         
         
         'Admin' => 1,
         
      ),
      'PrintList' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         'Secretary' => 0,
         
         
         'Admin' => 1,
         
      ),
      'Download' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         
         
         'Admin' => 0,
         
      ),
      'Export' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
          'Clerk' => 0,
         'Teacher' => 0,
        
         
         'Admin' => 0,
         
      ),
      'Zip' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         
         
         'Admin' => 0,
         
      ),
      'NewPassword' => array
      (
         'Public' => 0,
         'Person' => 1,
         'Secretary' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         
         
         'Admin' => 1,
      ),
      'MailList' => array
      (
         'Public' => 0,
         'Person' => 1,
         'Secretary' => 0,
          'Clerk' => 0,
         'Teacher' => 0,
        
         
         'Admin' => 1,
         
      ),
      'Process' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         
         
         'Admin' => 1,
         
      ),
      'Profiles' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Secretary' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         
         
         'Admin' => 1,
         
      ),
      'ComposedAdd' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         
         
         
         'Secretary' => 0,
         'Admin' => 0,
      ),
      'Import' => array
      (
         'Public' => 0,
         'Person' => 0,
         
         
         
         'Secretary' => 0,
         'Admin' => 1,
      ),
      'SysInfo' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Clerk' => 0,
         'Teacher' => 0,
         

         
         'Secretary' => 0,
         'Admin' => 1,
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
         'Secretary' => array
         (
            'Show' => 1,
            'Edit' => 1,
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
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
         'Secretary' => array
         (
            'Search' => 1,
            'EditList' => 1,
            'Add' => 1,
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Admin' => array
         (
            'Add' => 1,
            'Search' => 1,
            'EditList' => 1,
            'Profiles' => 1,
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
         'Secretary' => array
         (
            'Search' => 1,
            'EditList' => 1,
            'Add' => 1,
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Admin' => array
         (
            'Add' => 1,
            'Search' => 1,
            'EditList' => 1,
            'Profiles' => 1,
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
         'Secretary' => array
         (
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
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
         'Secretary' => array
         (
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Admin' => array
         (
         ),
      ),
   ),
);
?>