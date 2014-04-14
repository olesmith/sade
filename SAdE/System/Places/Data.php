array
(
   "ID" => array
   (
      "Name" => "ID",
      "Sql" => "INT NOT NULL PRIMARY KEY AUTO_INCREMENT",
      "Search" => FALSE,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Department" => array
   (
      "Name" => "Secretaria",
      "Title" => "Secretaria do Prédio",
      "Sql" => "INT",
      "SqlClass" => "Departments",
      "Search" => TRUE,
      //"SearchDefault" => $this->ApplicationObj->GetLoginData("Department"),
      "Compulsory" => TRUE,
      //"NoSearchEmpty" => TRUE,
      "GETSearchVarName" => "Department",

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

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
      "Title" => "Nome do Prédio",
      "Size" => "50",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Phone" => array
   (
      "Name" => "Fone",
      "Sql" => "CHAR(255)",
      "Size" => 15,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "Fax" => array
   (
      "Name" => "Fax",
      "Sql" => "CHAR(255)",
      "Size" => 15,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "Email" => array
   (
      "Name" => "Email de Contato",
      "ShortName" => "Email",
      "Size" => "50",
      "Sql" => "VARCHAR(255)",
      "Iconify" => TRUE,
      "Compulsory" => FALSE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "WWW" => array
   (
      "Name" => "Sítio",
      "ShortName" => "Sítio Oficial",
      "Size" => 25,
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "HRefIt" => FALSE,
      "Iconify" => TRUE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Street" => array
   (
      "Name" => "Rua/Av.",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "StreetNumber" => array
   (
      "Name" => "No.",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "StreetCompletion" => array
   (
      "Name" => "Complemento",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "Area" => array
   (
      "Name" => "Bairro",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "City" => array
   (
      "Name" => "Cidade",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "State" => array
   (
      "Name" => "UF",
      "Sql" => "ENUM",
      "Values" => array(),
      "NoSelectSort" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "ZIP" => array
   (
      "Name" => "CEP",
      "Sql" => "CHAR(255)",
      "Size" => 8,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "Type" => array
   (
      "Name" => "Tipo",
      "Search" => "TRUE",
      "Sql" => "ENUM",
      "Values" => array("Prefeitura","Hospital","Posto de Saúde","Local de Ensino","Garagem"),
      "GETSearchVarName" => "Type",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
);