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
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Class" => array
   (
      "Name" => "Turma",
      "Sql" => "INT",
      "SqlClass" => "Classes",
      "GETSearchVarName"  => "Class",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "ClassDisc" => array
   (
      "Name" => "Disciplina",
      "Sql" => "INT",
      "GETSearchVarName"  => "Disc",
      "SqlClass" => "ClassDiscs",
      "SqlFilter" => "#Name",
      "Search" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Teacher" => array
   (
      "Name" => "Professor(a)",
      "Sql" => "INT",
      "SqlClass" => "Users",
      "SqlWhere" => "Profile_Teacher='2'",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
   ),
   "Student" => array
   (
      "Name" => "Aluno(a)",
      "Sql" => "INT",
      "SqlClass" => "Students",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
   ),
   "Status" => array
   (
      "Name" => "Status",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "Default" => 1,
      "Values" => array("Ativo","Inativo"),

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "NAssessments" => array
   (
      "Name" => "No. de Avaliaçoes",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Media" => array
   (
      "Name" => "Media",
      "Sql" => "REAL",
      "Search" => FALSE,
      "Format" => "%.1f",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "MediaFinal" => array
   (
      "Name" => "Media Final",
      "Sql" => "REAL",
      "Search" => FALSE,
      "Format" => "%.1f",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "MarkResult" => array
   (
      "Name" => "Notas, Res.",
      "Sql" => "ENUM",
      "Search" => FALSE,
      "Values" => array("RE","AP"),

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Sum" => array
   (
      "Name" => "No. de Faltas",
      "Sql" => "INT",
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,
      "Format" => "%02d",

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Percent" => array
   (
      "Name" => "Faltas, %",
      "Sql" => "REAL",
      "Search" => FALSE,
      "Format" => "%.1f",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "AbsencesResult" => array
   (
      "Name" => "Faltas, Res.",
      "Sql" => "ENUM",
      "Search" => FALSE,
      "Values" => array("RE","AP"),

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Result" => array
   (
      "Name" => "Res. Final",
      "Sql" => "ENUM",
      "Search" => FALSE,
      "Values" => array
      (
         "RPN",
         "RPF",
         "RPNF",
         "AP"
      ),

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
 );
