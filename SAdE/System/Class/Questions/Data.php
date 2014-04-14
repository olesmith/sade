array
(
   "ID" => array
   (
      "Name" => "ID",
      "Sql" => "INT NOT NULL PRIMARY KEY AUTO_INCREMENT",

      "Public"      => 1,
      "Person"      => 1,
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
      "GETSearchVarName"  => "Class",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Question" => array
   (
      "Name" => "Conceito",
      "Sql" => "INT",
      "SqlClass" => "ClassQuestions",
      "SqlFilter" => "#Name",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Assessment" => array
   (
      "Name" => "Semestre",
      "Sql" => "INT",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
   ),
   "Student" => array
   (
      "Name" => "Aluno(a)",
      "Sql" => "INT",
      "SqlClass" => "Students",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
   ),
   "Teacher" => array
   (
      "Name" => "Professor(a)",
      "Sql" => "INT",
      "SqlClass" => "Users",
      "SqlWhere" => "Profile_Teacher='2'",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
   ),
   "Value" => array
   (
      "Name" => "Avaliaçao No.",
      "Sql" => "ENUM",
      "Search" => FALSE,
      "Default" => "0 ",
      "Values" => array("Sim","Não","Desenvolvendo"),
      "Values_Latex" => array("Sim","Não","Des."),
      "NoSelectSort" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 2,
      "Secretary" => 2,
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
   ),
 );
