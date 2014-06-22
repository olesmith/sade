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
   "School" => array
   (
      "Name" => "Escola",
      "Sql" => "INT",
      "SqlClass" => "Places",
      "GETSearchVarName"  => "School",
      "SqlWhere" => "Type='4'",
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
      "Search" => TRUE,
      "SqlDerivedData" => array("Name","Year","Type","Semester"),

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

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
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "GradePeriod" => array
   (
      "Name" => "Período",
      "Sql" => "INT",
      "SqlClass" => "GradePeriods",
      "Search" => TRUE,
      "SqlDerivedData" => array("NAssessments","AssessmentWeights","NRecoveries","AbsencesType","AssessmentType"),
      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Class" => array
   (
      "Name" => "Turma",
      "Sql" => "INT",
      "SqlClass" => "Classes",
      "GETSearchVarName"  => "Class",
      "Search" => TRUE,
      "SqlDerivedData" => array("Name","NameKey"),
      "SqlFilter" => "#NameKey",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,      
   ),
   "GradeDisc" => array
   (
      "Name" => "Disciplina",
      "Sql" => "INT",
      "SqlClass" => "GradeDiscs",
      "SqlDerivedData" => array
      (
          "Name","Status","NickName","CHS","CHT","GradePeriod",
      ),
      "SqlFilter" => "#Name, #GradePeriod",
      //"SqlWhere" => "(Grade='#Grade' AND GradePeriod='#GradePeriod')",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Teacher" => array
   (
      "Name" => "Professor(a)",
      "Sql" => "INT",
      "SqlClass" => "Users",
      "SqlWhere" => "Profile_Teacher='2'",
      "Search" => FALSE,
      "FieldMethod"  => "MakeTeacherSelect",

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
      "Name" => "Professor(a) Apoio",
      "Sql" => "INT",
      "SqlClass" => "Users",
      "SqlWhere" => "Profile_Teacher='2'",
      "Search" => FALSE,
      "FieldMethod"  => "MakeTeacherSelect",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
   ),
   "Teacher2" => array
   (
      "Name" => "Professor(a) Recursos",
      "Sql" => "INT",
      "SqlClass" => "Users",
      "SqlWhere" => "Profile_Teacher='2'",
      "FieldMethod"  => "MakeTeacherSelect",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Coordinator" => 1,
      "Secretary" => 2,
   ),

   "Name" => array
   (
      "Name" => "Nome",
      "Size" => "30",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "NickName" => array
   (
      "Name" => "Apelido",
      "Title" => "Titulo Saindo em Atas, etc.",
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
   "CHS" => array
   (
      "Name" => "CHS",
      "Sql" => "INT",
      "Search" => FALSE,
      "Size" => 1,
      "Regex" => '^\d\d?\d?$',

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "CHT" => array
   (
      "Name" => "CHT",
      "Sql" => "INT",
      "Search" => FALSE,
      "Size" => 2,
      "Regex" => '^\d\d?\d?$',

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "FileKey" => array
   (
      "Name" => "Arq. SAdE",
      "Size" => "30",
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
   "Daylies" => array
   (
      "Name" => "Diário Eletrônico",
      "Title" => "Periodo Ativa Diário Eletrônico?",
      "Size" => "10",
      "Sql" => "ENUM",
      "Values" => array("Não","Sim","Não Spec.",),
      "NoSelectSort" => TRUE,
      "Default" => 1,
      "Search" => FALSE,
      "Public"      => 1,
      "Teacher"     => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),

   //Obsoletes
    "TeacherID" => array
   (
      "Name" => "Prof.",
      "Size" => "10",
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
 );
