array
(
   'Title' => '&nbsp;Períodos:',

   'Public' => 0,
   'Person' => 0,
   'Secretary' => 1,
   'Admin' => 1,
   'Clerk' => 1,
   'Teacher' => 1,
   "1_Classes" => array
   (
      "Name" => "Turmas",
      "Title" => "Gerenciar Turmas do Período",
      'Href' => '?Unit=#Unit&School=#School&ModuleName=Classes&Action=Search&Period=#ID',

      'Public'    => 0,
      'Person'    => 0,
      'Secretary' => 1,
      'Admin'     => 1,
      'Clerk'     => 1,
      'Teacher'   => 0,
   ),
   "2_Discs" => array
   (
      "Name" => "Disciplinas",
      "Title" => "Gerenciar Disciplinas do Período",
      'Href' => '?Unit=#Unit&School=#School&ModuleName=Periods&Action=Discs&Period=#ID',

      'Public'    => 0,
      'Person'    => 0,
      'Secretary' => 1,
      'Admin'     => 1,
      'Clerk'     => 1,
      'Teacher'   => 0,
   ),
);
