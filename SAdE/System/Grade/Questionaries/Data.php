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
      "Size" => 50,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "Number" => array
   (
      "Name" => "Ordem",
      "Size" => "5",
      "Sql" => "VARCHAR(5)",
      "Compulsory" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
);
