array
(
   "Limit" => array
   (
      "Sql" => "INT",
      "SqlClass" => "Dates",
      "FieldMethod" => "MakeDayliesDateSelect",

      "ShortName" => "Dia Limite #N",
      "Name" => "Dias Limite, #N",
      "Title" => "Limite do Trimestre #N",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2, 
      "Coordinator" => 1,
   ),
   "Closed" => array
   (
      "Sql" => "ENUM",
      "Values" => array("Não","Sim"),
      "Default" => 1,

      "ShortName" => "Fechado #N",
      "Name" => "Fechado Prof., #N",
      "Title" => " Trimestre #N, Fechado Prof.,",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 2,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "ClosedTime" => array
   (
      "Sql" => "INT",
      "TimeType" => 1,
      "Title" => 5,

      "ShortName" => "Data #N",
      "Name" => "Data Prof., #N",
      "Name" => "Trimestre #N, Fechado Prof., Data",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
);
