array
(
   "ID" => array
   (
      "Name" => "ID",
      "Sql" => "INT NOT NULL PRIMARY KEY AUTO_INCREMENT",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
      "Coordinator" => 1,
   ),
   "Clerk" => array
   (
      "Name" => "Secretario(a)/Coordenador(a)",
      "Sql" => "INT",
      "SqlClass" => "People",
      "SqlWhere" => "(Profile_Clerk='2' OR Profile_Coordinator='2')",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
      "Coordinator" => 1,
   ),
   "School" => array
   (
      "Name" => "Escola",
      "Sql" => "INT",
      "SqlClass" => "Schools",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
      "GETSearchVarName"  => "School",
   ),
);
