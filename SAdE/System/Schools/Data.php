array
(
   "ShortName" => array
   (
      "Name" => "Apelido",
      "Title" => "Nome Curto da Escola",
      "Size" => "50",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Type" => array
   (
      "Name" => "Tipo",
      "Title" => "Tipo (Anual, Semestral)",
      "Sql" => "ENUM",
      "Values" => array("Anual","Semestral"),

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "DefaultLessonWeight" => array
   (
      "Name" => "Peso Default de Aulas",
      "Title" => "Peso Default de Aulas",
      "Sql" => "INT",
      "Default" => 1,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "WeekDay1" => array
   (
      "ShortName" => "2ª",
      "Name" => "Segunda",
      "Sql" => "ENUM",
      "Values" => array("Sim","Não"),
      "Default" => 1,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "WeekDay2" => array
   (
      "ShortName" => "3ª",
      "Name" => "Terça",
      "Sql" => "ENUM",
      "Values" => array("Sim","Não"),
      "Default" => 1,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "WeekDay3" => array
   (
      "ShortName" => "4ª",
      "Name" => "Quarta",
      "Sql" => "ENUM",
      "Values" => array("Sim","Não"),
      "Default" => 1,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "WeekDay4" => array
   (
      "ShortName" => "5ª",
      "Name" => "Quinta",
      "Sql" => "ENUM",
      "Values" => array("Sim","Não"),
      "Default" => 1,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "WeekDay5" => array
   (
      "ShortName" => "6ª",
      "Name" => "Sexta",
      "Sql" => "ENUM",
      "Values" => array("Sim","Não"),
      "Default" => 1,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "WeekDay6" => array
   (
      "ShortName" => "Sáb.",
      "Name" => "Sábado",
      "Sql" => "ENUM",
      "Values" => array("Sim","Não"),
      "Default" => 2,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "WeekDay7" => array
   (
      "ShortName" => "Dom.",
      "Name" => "Domingo",
      "Sql" => "ENUM",
      "Values" => array("Sim","Não"),
      "Default" => 2,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
);