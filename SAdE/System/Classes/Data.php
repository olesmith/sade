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
   "Number" => array
   (
      "Name" => "Ordem",
      "Sql" => "INT",
      "Search" => FALSE,
      "Default" => 1,
      "Size" => 1,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Status" => array
   (
      "Name" => "Status",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "Values" => array("Ativo","Inativo"),
      "Default" => 1,
      "SearchDefault" => 1,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "FileKey" => array
   (
      "Name" => "Arquivo, SAdE",
      "Size" => "10",
      "Sql" => "VARCHAR(256)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "NameKey" => array
   (
      "Name" => "Chave",
      "Size" => "10",
      "Sql" => "VARCHAR(55)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      
      
      
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Title" => "Nome da Turma",
      "Size" => "30",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
      
      
      
   ),
   "Title" => array
   (
      "Name" => "Titulo",
      "Size" => "30",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      
      
      
   ),
   "School" => array
   (
      "Name" => "Escola",
      "Sql" => "INT",
      "SqlClass" => "Places",
      "SqlWhere" => "Type='4'",
      "GETSearchVarName"  => "School",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      
      
      
   ),
   "Period" => array
   (
      "Name" => "Período",
      "Sql" => "INT",
      "SqlClass" => "Periods",
      "SqlSortReverse" => TRUE,
      "Search" => TRUE,
      "SqlDerivedData" => array("Name","Year","Type","Semester","StartDate","EndDate",),
      "FieldMethod" => "GenPeriodSelect",
      "GETSearchVarName"  => "Period",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
      
      
      
   ),
   "Grade" => array
   (
      "Name" => "Grade",
      "Sql" => "INT",
      "SqlClass" => "Grade",
      "Search" => TRUE,
      "SqlDerivedData" => array("Name","Mode"),
      "FieldMethod" => "GenGradeSelect",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      
      
      
   ),
   "GradePeriod" => array
   (
      "Name" => "Periodo",
      "Sql" => "INT",
      "SqlClass" => "GradePeriods",
      "SqlDerivedData" => array("Name","NAssessments"),
      "Search" => TRUE,
      "FieldMethod" => "GenGradePeriodSelect",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
      
      
      
   ),
   "Year" => array
   (
      "Name" => "Year",
      "Size" => "5",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      
      
      
   ),
   "Semester" => array
   (
      "Name" => "Sem",
      "Size" => "5",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Coordinator" => 1,
      "Secretary" => 1,
      
      
      
   ),
   "Shift" => array
   (
      "Name" => "Turno",
      "Sql" => "ENUM",
      "Values" => array("Matutino","Vespertino","Noturno"),
      "Search" => TRUE,
      "Default" => 1,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
      
      
      
   ),
   "TeacherID" => array
   (
      "Name" => "Prof.",
      "Size" => "10",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Teacher" => array
   (
      "Name" => "Professor(a)",
      "Sql" => "INT",
      "SqlClass" => "Users",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Teacher1" => array
   (
      "ShortName" => "Prof.(a) Apoio",
      "Name" => "Professor(a) Apoio",
      "Sql" => "INT",
      "SqlClass" => "Users",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Teacher2" => array
   (
      "ShortName" => "Prof.(a) Recursos",
      "Name" => "Professor(a) Recursos",
      "Sql" => "INT",
      "SqlClass" => "Users",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "NStudents" => array
   (
      "Name" => "Alunos",
      "Title" => "Alunos Ativos",
      "Size" => "10",
      "Sql" => "INT",
      "Search" => FALSE,
      "Default" => " 0",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "NInactive" => array
   (
      "Name" => "Assombrados",
      "Title" => "No. de Alunos Assombrados",
      "Size" => "10",
      "Sql" => "INT",
      "Search" => FALSE,
      "Default" => " 0",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      
      
      
   ),


   "NumberOfAlunos" => array
   (
      "Name" => "No. de Alunos",
      "Size" => "10",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      
      
      
   ),
   "NumberOfInvisibleAlunos" => array
   (
      "Name" => "No. de Alunos Sombrados",
      "Size" => "10",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      
      
      
   ),

   "CHT" => array
   (
      "Name" => "CHT",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "CHS" => array
   (
      "Name" => "CHS",
      "Sql" => "INT",
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
