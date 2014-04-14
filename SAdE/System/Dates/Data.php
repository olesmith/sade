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
   "Day" => array
   (
      "Name" => "Dia",
      "Size" => "2",
      "Sql" => "INT",
      "Regex" => '^\d\d?',
      "Search" => TRUE,
      "Compulsory" => TRUE,

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
   "Month" => array
   (
      "Name" => "Mês",
      "Size" => "10",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "NoSelectSort" => TRUE,
      "AddVar"       => TRUE,

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
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "JulianDay" => array
   (
      "Name" => "Dia Juliana",
      "Size" => "4",
      "Sql" => "INT",
      "Search" => FALSE,

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
   "WeekDay" => array
   (
      "Name" => "Dia de Semena",
      "Size" => "4",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "NoSelectSort" => TRUE,

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
   "WeekNo" => array
   (
      "Name" => "Semana",
      "Size" => "2",
      "Sql" => "INT",
      "Search" => TRUE,
      "NoSelectSort" => TRUE,

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
   "Name" => array
   (
      "Name" => "Nome",
      "Size" => "4",
      "Sql" => "VARCHAR(255)",
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
   "Date" => array
   (
      "Name" => "Data",
      "Size" => "10",
      "Sql" => "VARCHAR(10)",
      "Search" => TRUE,

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
   "SortKey" => array
   (
      "Name" => "Chave",
      "Size" => "4",
      "Sql" => "VARCHAR(8)",
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
   "Type" => array
   (
      "Name" => "Tipo",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "Values"      => array("Normal","Sábado","Domingo","Feriado","Recesso"),
      "Default"      => 1,

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
   "Text" => array
   (
      "Name" => "Descrição",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Size"      => 50,

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
   "Semester" => array
   (
      "Name" => "Trimestre",
      "Sql" => "INT",
      "Search" => FALSE,
      "Size"      => 50,

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
);
