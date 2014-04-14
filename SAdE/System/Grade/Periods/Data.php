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
      "Coordinator" => 1,
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Size" => "10",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "SortOrder" => array
   (
      "Name" => "Ordem",
      "Size" => "2",
      "Sql" => "INT",
      "Search" => FALSE,
      "Compulsory" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Year" => array
   (
      "Name" => "Período",
      "Size" => "2",
      "Sql" => "INT",
      "Search" => FALSE,
      "Compulsory" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "NDiscs" => array
   (
      "Title" => "No. de Disciplinas",
      "Name" => "Disciplinas",
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
   "NDiscsTotal" => array
   (
      "Title" => "Com Inativos",
      "Name" => "Inativos",
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
