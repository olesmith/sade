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
   "Questionaire" => array
   (
      "Name" => "Questionário",
      "Sql" => "INT",
      "SqlClass" => "GradeQuestionaries",
      "Compulsory" => TRUE,
      "Search" => TRUE,
      "SqlWhere" => "GradePeriod='#GradePeriod'",
      "NumericalSort" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Size" => "30",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Size" => 75,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "Number" => array
   (
      "Name" => "No.",
      "Size" => "5",
      "Sql" => "VARCHAR(5)",
      "NumericalSort" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
);
