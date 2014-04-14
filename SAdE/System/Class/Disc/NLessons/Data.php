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
   "ClassDisc" => array
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
   "Assessment" => array
   (
      "Name" => "No.",
      "Sql" => "INT",
      "Size" => "2",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "NLessons" => array
   (
      "Name" => "Aulas Dadas",
      "Sql" => "INT",
      "Size" => "2",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Sql" => "VARCHAR(10)",
      "Size" => "2",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "SecEdit" => array
   (
      "Name" => "Editável",
      "Sql" => "ENUM",
      "Values" => array("Não","Sim"),
      "Default" => 2,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
);
