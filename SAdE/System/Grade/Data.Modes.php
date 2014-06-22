array
(
   "AssessmentType" => array
   (
      "Title" => "Tipo de Avaliação",
      "Name" => "Avaliação",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "Values" => array("Quantitativa","Qualitativa","Não"),
      //"Default" => 1,
      "Size" => 3,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "MediaLimit" => array
   (
      "Title" => "Media de Aprovaçao (sem Recup.)",
      "Name" => "Media",
      "Default" => 6.0,
      "Sql" => "REAL",
      "Search" => FALSE,
      "Size" => 3,
      "Format" => "%0.1f",

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "FinalMedia" => array
   (
      "Title" => "Média Limite Final",
      "Name" => "Média Final",
      "Sql" => "REAL",
      "Search" => FALSE,
      "Size" => 1,
      "Regex" => '^\d\d?\d?$',
      "Default" => 7.0,
      "Format" => "%.1f",

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "NAssessments" => array
   (
      "Name" => "Avaliações",
      "Sql" => "ENUM",
      "Search" => FALSE,
      "Values" => array(" 1"," 2"," 3"," 4"," 5"," 6"," 7"," 8"," 9","10","11","12"),
      "Default" => 4,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "AssessmentsWeights" => array
   (
      "Title" => "Av. Pesos",
      "Name" => "Pesos",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "Values" => array("Iguais","Ponderados"),
      "Default" => 2,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "NRecoveries" => array
   (
      "Title" => "No. de Recuperações",
      "Name" => "Recups.",
      "Sql" => "ENUM",
      "Values" => array(1,2),
      "EmptyName" => "Sem",

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "AbsencesType" => array
   (
      "Name" => "Faltas",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "Values" => array("Somente Totais","Sim","Não"),
      //"Default" => 2,

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "AbsencesLimit" => array
   (
      "Name" => "Limite",
      "Title" => "Limite de Faltas",
      "Default" => 25.0,
      "Sql" => "REAL",
      "Size" => 3,
      "Search" => FALSE,
      "Format" => "%0.1f",

      "Public"      => 1,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
);
