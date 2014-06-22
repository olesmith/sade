array
(
   "ID" => array
   (
      "Name" => "ID",
      "Sql" => "INT NOT NULL PRIMARY KEY AUTO_INCREMENT",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Debug" => array
   (
      "Name" => "Nível Debug",
      "Sql" => "INT",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Year" => array
   (
      "Name" => "Ano",
      "Sql" => "INT",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Month" => array
   (
      "Name" => "Mês",
      "Sql" => "INT",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Date" => array
   (
      "Name" => "Data",
      "Sql" => "INT",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Login" => array
   (
      "Name" => "Usuário",
      "Sql" => "INT",
      "SqlClass" => "Users",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Profile" => array
   (
      "Name" => "Perfil",
      "Sql" => "VARCHAR(16)",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Message" => array
   (
      "Name" => "Mensagem",
      "Sql" => "VARCHAR(1024)",
      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "IP" => array
   (
      "Name" => "IP",
      "Sql" => "VARCHAR(16)",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0.
   ),
   "ModuleName" => array
   (
      "Name" => "Módulo",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Action" => array
   (
      "Name" => "Action",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Unit" => array
   (
      "Name" => "Unidade",
      "Sql" => "INT",
      "Search" => FALSE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "School" => array
   (
      "Name" => "Escola",
      "Sql" => "INT",
      "SqlClass" => "Schools",
      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Period" => array
   (
      "Name" => "Período",
      "Sql" => "INT",
      "SqlClass" => "Periods",
      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Class" => array
   (
      "Name" => "Turma",
      "Sql" => "INT",
      "SqlClass" => "Classes",
      "Search" => FALSE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Disc" => array
   (
      "Name" => "Disciplina",
      "Sql" => "INT",
      "SqlClass" => "ClassDiscs",
      "Search" => FALSE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Student" => array
   (
      "Name" => "Aluno",
      "Sql" => "INT",
      "SqlClass" => "ClassStudents",
      "Search" => FALSE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "Teacher" => array
   (
      "Name" => "Aluno",
      "Sql" => "INT",
      "SqlClass" => "ClassStudents",
      "Search" => FALSE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "POST_Edit" => array
   (
      "Name" => "Edit",
      "Sql" => "INT",
      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "POST_Update" => array
   (
      "Name" => "Update",
      "Sql" => "INT",
      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "POST_Transfer" => array
   (
      "Name" => "Update",
      "Sql" => "INT",
      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
   "POST_Save" => array
   (
      "Name" => "Save",
      "Sql" => "INT",
      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 1,
      "Coordinator" => 0,
   ),
);