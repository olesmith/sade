array
(
   'Title' => '&nbsp;Turmas:',

   'Public' => 0,
   'Person' => 0,
   'Secretary' => 1,
   'Coordinator' => 1,
   'Admin' => 1,
   'Clerk' => 1,
   'Teacher' => 1,
   "0_Class" => array
   (
      "Name" => "Turma",
      "Title" => "Editar Dados da Turma",
      'Href' => '?Unit=#Unit&School=#School&Period=#Period&ModuleName=Classes&Action=Edit&Class='.$this->Class[ "ID" ],

      'Public'    => 0,
      'Person'    => 0,
      'Secretary' => 1,
      'Coordinator' => 1,
      'Admin'     => 1,
      'Clerk'     => 1,
      'Teacher'   => 0,
   ),
   "1_Students" => array
   (
      "Name" => "Alunos",
      "Title" => "Alunos da Turma",
      'Href' => '?Unit=#Unit&School=#School&Period=#Period&ModuleName=Classes&Action=Students&Class='.$this->Class[ "ID" ],

      'Public'    => 0,
      'Person'    => 0,
      'Secretary' => 1,
      'Coordinator' => 1,
      'Admin'     => 1,
      'Clerk'     => 1,
      'Teacher'   => 0,
   ),
   "2_Discs" => array
   (
      "Name" => "Disciplinas",
      "Title" => "Disciplinas da Turma",
      'Href' => '?Unit=#Unit&School=#School&Period=#Period&ModuleName=Classes&Action=Discs&Class='.$this->Class[ "ID" ],

      'Public'    => 0,
      'Person'    => 0,
      'Secretary' => 1,
      'Coordinator' => 1,
      'Admin'     => 1,
      'Clerk'     => 1,
      'Teacher'   => 0,
   ),
   "3_Teachers" => array
   (
      "Name" => "Professores",
      "Title" => "Horários e Professores da Turma",
      'Href' => '?Unit=#Unit&School=#School&Period=#Period&ModuleName=Classes&Action=Hours&Class='.$this->Class[ "ID" ],

      'Public'    => 0,
      'Person'    => 0,
      'Secretary' => 1,
      'Coordinator' => 1,
      'Admin'     => 1,
      'Clerk'     => 1,
      'Teacher'   => 0,
   ),
   "4_Daylies" => array
   (
      "Name" => "Diários",
      "Title" => "Diários Eletrônicos",
      'Href' => '?Unit=#Unit&School=#School&Period=#Period&ModuleName=Classes&Action=Daylies&Class='.$this->Class[ "ID" ],

      'Public'    => 0,
      'Person'    => 0,
      'Secretary' => 1,
      'Coordinator' => 1,
      'Admin'     => 1,
      'Clerk'     => 1,
      'Teacher'   => 0,
   ),
);
