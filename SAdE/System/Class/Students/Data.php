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
   "Student" => array
   (
      "Name" => "Aluno(a)",
      "Sql" => "INT",
      "SqlClass" => "Students",
      "Search" => FALSE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "School" => array
   (
      "Name" => "Escolas",
      "Sql" => "INT",
      "SqlClass" => "Places",
      "SqlWhere" => "Type='4'",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
       "Coordinator" => 1,
  ),
   "Class" => array
   (
      "Name" => "Turma",
      "Sql" => "INT",
      "SqlClass" => "Classes",
      //"SqlWhere" => "Type='4'",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Grade" => array
   (
      "Name" => "Grade",
      "Sql" => "INT",
      "SqlClass" => "Grade",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "GradePeriod" => array
   (
      "Name" => "Período",
      "Sql" => "INT",
      "SqlClass" => "GradePeriods",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "FileKey" => array
   (
      "Name" => "Arq. SAdE",
      "Size" => "30",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),

   "UniqueID" => array
   (
      "Name" => "ID Antigo",
      "Size" => "10",
      "Sql" => "BIGINT",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
       "Coordinator" => 1,
  ),
  "Start" => array
   (
      "Name" => "Data de Transferência",
      "Title" => "Data Transferência Interna",
      "Sql" => "INT",
      "SqlClass" => "Dates",
      "FieldMethod" => "MakeStartEndSelect",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "End" => array
   (
      "Name" => "Transferido, Data",
      "Title" => "Data Transferido Internamente",
      "Sql" => "INT",
      "SqlClass" => "Dates",
      "FieldMethod" => "MakeStartEndSelect",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "FromSchool" => array
   (
      "Name" => "Transferido da Escola",
      "Title" => "Transferido da Escola",
      "Sql" => "INT",
      "SqlClass" => "Schools",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "FromClass" => array
   (
      "Name" => "Transferido da Turma",
      "Title" => "Transferido da Turma",
      "Sql" => "INT",
      "SqlClass" => "Classes",
      "FieldMethod" => "MakeToFromClassField",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "ToSchool" => array
   (
      "Name" => "Transferido para Escola",
      "Title" => "Transferido Para Escola",
      "Sql" => "INT",
      "SqlClass" => "Schools",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "ToClass" => array
   (
      "Name" => "Transferido para Turma",
      "Title" => "Transferido para Turma",
      "Sql" => "INT",
      "SqlClass" => "Classes",
      "FieldMethod" => "MakeToFromClassField",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "AbsencesTrans" => array
   (
      "Name" => "Faltas Trans.",
      "Title" => "Faltas Transferida",
      "Sql" => "VARCHAR(8)",
      "Name" => "Transferido para Turma",
      "Title" => "Transferido para Turma",
      "Sql" => "INT",
      "SqlClass" => "Classes",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
);
