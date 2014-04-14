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
      "Coordinator" => 1,
   ),
   "Class" => array
   (
      "Name" => "Turma",
      "Sql" => "INT",
      "SqlClass" => "Classes",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Disc" => array
   (
      "Name" => "Disciplina",
      "Sql" => "INT",
      "SqlClass" => "ClassDiscs",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Semester" => array
   (
      "Name" => "Semester",
      "Sql" => "INT",
      "Size" => "1",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Sql" => "VARCHAR(64)",
      "Size" => "10",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Number" => array
   (
      "Name" => "Número",
      "Sql" => "INT",
      "Size" => "1",
      "Default" => 1,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "MaxVal" => array
   (
      "Name" => "Valor Máximo",
      "Sql" => "REAL",
      "Size" => "1",
      "Default" => 10.0,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
);
