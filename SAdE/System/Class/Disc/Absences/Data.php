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
  "Student" => array
   (
      "Name" => "Aluno",
      "Sql" => "INT",
      "SqlClass" => "Students",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 2,
      "Coordinator" => 1,
      "Secretary" => 2,
   ),
  "Content" => array
   (
      "Name" => "Conteúdo",
      "Sql" => "INT",
      "SqlClass" => "ClassDiscContents",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
  "Weight" => array
   (
      "Name" => "Peso",
      "Sql" => "INT",
      "Size" => 2,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Semester" => array
   (
      "Name" => "Semester",
      "Sql" => "INT",
      "Size" => "1",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Month" => array
   (
      "Name" => "Mes",
      "Sql" => "INT",
      "Size" => "1",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
);
