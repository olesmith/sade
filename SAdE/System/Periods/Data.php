array
(
   "ID" => array
   (
      "Name" => "ID",
      "Sql" => "INT NOT NULL PRIMARY KEY AUTO_INCREMENT",
      "SqlDerivedData" => array(""),
      "Admin" => 1,
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
      "Size" => "20",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => TRUE,
      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Type" => array
   (
      "Name" => "Tipo",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "Compulsory" => TRUE,
      "NoSelectSort" => TRUE,
      "Values" => array("Anual","Semestral"), //"Trimestral","Bimestral","Mensal"),
      "Names" => array("Ano","Semestre"),     //"Trimestre","Bimestre","Mês"),
      "NSemesters" => array(4,2),             //,4,6,12),
      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "NPeriods" => array
   (
      "Name" => "Períodos",
      "Title" => "No. de Períodos",
      "Sql" => "INT",
      "Search" => FALSE,
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Year" => array
   (
      "Name" => "Ano",
      "Size" => "4",
      "Sql" => "INT",
      "Search" => TRUE,
      "Regex" => '^\d\d\d\d$',
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
      "Name" => "Semestre",
      "Sql" => "ENUM",
      "Search" => FALSE,
      "Default" => 1,
      "EmptyName"         => "-",
      "Values" => array(1,2,3,4,5,6,7,8,9,10,11,12),
      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "StartDate" => array
   (
      "Name" => "Início",
      "Title" => "Data Inícial",
      "Sql" => "INT",
      "SqlClass" => "Dates",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "EndDate" => array
   (
      "Name" => "Término",
      "Title" => "Data Final",
      "Sql" => "INT",
      "SqlClass" => "Dates",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Active" => array
   (
      "Name" => "Ativo (Aparece no Sistema)",
      "ShortName" => "Ativo",
      "Size" => "4",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "Values" => array("Sim","Não"),
      "Default"      => 1,
      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
   ),
   "ReadOnly" => array
   (
      "Name" => "Somente Leitura (não Altera)",
      "ShortName" => "Somente Leitura",
      "Size" => "4",
      "Sql" => "ENUM",
      "Search" => FALSE,
      "Values" => array("Não","Sim"),
      "Default"      => 1,
      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Period" => array
   (
      "Name" => "Chave",
      "Size" => "10",
      "Sql" => "VARCHAR(10)",
      "Search" => FALSE,
      //"Regex" => '^\d\d\/\d\d\/\d\d\d\d$',
      "Public"      => 1,
      "Teacher"     => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "NextPeriod" => array
   (
      "Name" => "Prox. Período",
      "Size" => "10",
      "Sql" => "VARCHAR(10)",
      "Search" => FALSE,
      //"Regex" => '^\d\d\/\d\d\/\d\d\d\d$',
      "Public"      => 1,
      "Teacher"     => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "NClasses" => array
   (
      "Name" => "Turmas",
      "Title" => "No. de Turmas",
      "Size" => "10",
      "Sql" => "INT",
      "Search" => FALSE,
      "Public"      => 1,
      "Teacher"     => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Daylies" => array
   (
      "Name" => "Diário Eletrônico",
      "Title" => "Periodo Ativa Diário Eletrônico?",
      "Size" => "10",
      "Sql" => "ENUM",
      "Values" => array("Não","Sim"),
      "Default" => 1,
      "Search" => TRUE,
      "Public"      => 1,
      "Teacher"     => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
);
