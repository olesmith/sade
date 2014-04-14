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
   "School" => array
   (
      "Name" => "Escola",
      "Sql" => "INT",
      "SqlClass" => "Places",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "SID" => array
   (
      "Name" => "Aluno ID",
      "Sql" => "INT",
      "SqlClass" => "Students",
      "Search" => FALSE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "MatriculaDate" => array
   (
      "Name" => "Data",
      "Title" => "Matricula, Data",
      "Sql" => "BIGINT",
      "Search" => FALSE,
      "Compulsory" => TRUE,
      "IsDate" => TRUE,
      "SearchDefault"     => "__/__/____",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Matricula" => array
   (
      "Name" => "Escola",
      "Sql" => "VARCHAR(20) ",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
);
