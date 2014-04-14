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
   ),
   "Status" => array
   (
      "Name" => "Status",
      "Sql" => "ENUM",
      "Values" => array("Ativo","Inativo"),
      "Search" => TRUE,
      "Default" => 1,
      "SearchDefault" => 1,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "Grade" => array
   (
      "Name" => "Grade",
      "Sql" => "INT",
      "SqlClass" => "Grade",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
   ),
   "GradePeriod" => array
   (
      "Name" => "Período",
      "Sql" => "INT",
      "SqlClass" => "GradePeriods",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Size" => "30",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "NickName" => array
   (
      "Name" => "Apelido",
      "Size" => "10",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "CHS" => array
   (
      "Name" => "CHS",
      "Sql" => "INT",
      "Search" => FALSE,
      "Default" => 6,
      "Size" => 3,
      "Regex" => '^\d\d?\d?$',

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "CHT" => array
   (
      "Name" => "CHT",
      "Sql" => "INT",
      "Search" => FALSE,
      "Size" => 3,
      "Regex" => '^\d\d?\d?$',
      "Default" => 120,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "NCHT" => array
   (
      "Name" => "NCHT",
      "Sql" => "VARCHAR(8)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
   ),
   "Chave" => array
   (
      "Name" => "Chave",
      "Sql" => "VARCHAR(32)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
   ),
);
