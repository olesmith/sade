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
   "Name" => array
   (
      "Name" => "Nome Completo",
      "Size" => "30",
      "Sql" => "VARCHAR(256)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "ShortName" => array
   (
      "Name" => "Nome",
      "Size" => "15",
      "Sql" => "VARCHAR(32)",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Mode" => array
   (
      "Name" => "Modalidade",
      "Values" => array("Anual","Semestral"),
      "Default" => 1,
      "Sql" => "ENUM",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "NextGrade" => array
   (
      "Name" => "Prox. Grade",
      "Sql" => "INT",
      "SqlClass" => "Grade",
      "FieldMethod" => "NextGradeSelect",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "NPeriods" => array
   (
      "Title" => "No. de Períodos",
      "Name" => "Períodos",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "NDiscs" => array
   (
      "Title" => "No. de Disciplinas",
      "Name" => " Disciplinas",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "NDiscsTotal" => array
   (
      "Title" => "Com Inativos*",
      "Name" => "Inativos*",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
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
   ),
   "SortOrder" => array
   (
      "Name" => "Ordem",
      "Sql" => "INT",
      "Search" => FALSE,
      "Size" => 2,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
);
