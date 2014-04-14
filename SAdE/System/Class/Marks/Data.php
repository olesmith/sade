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
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
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
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "ClassDisc" => array
   (
      "Name" => "Disciplina",
      "Sql" => "INT",
      "GETSearchVarName"  => "Disc",
      "SqlClass" => "ClassDiscs",
      "SqlFilter" => "#Name",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
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

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
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

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
   ),
   "Assessment" => array
   (
      "Name" => "Nota No.",
      "Sql" => "INT",
      "Search" => FALSE,
      "Size" => 3,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Mark" => array
   (
      "Name" => "Nota",
      "Sql" => "VARCHAR(8)",
      "Search" => FALSE,
      "Size" => 3,
      "Regex" => '^\d(\.\d)?$',

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
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
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
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
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
 );
