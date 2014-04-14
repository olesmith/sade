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
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Name" => array
   (
      "Name" => "Nome Completa",
      "Size" => "50",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "ShortName" => array
   (
      "Name" => "Nome Breve",
      "Size" => "10",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Secretary" => array
   (
      "Name" => "Secretário",
      "Sql" => "INT",
      "SqlClass" => "People",
      "Search" => FALSE,
      "Compulsory" => FALSE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Email" => array
   (
      "Name" => "Email Oficial",
      "ShortName" => "Email",
      "Size" => "50",
      "Sql" => "VARCHAR(255)",
      "Iconify" => TRUE,
      "Compulsory" => FALSE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Phone" => array
   (
      "Name" => "Fone",
      "ShortName" => "Fone",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => TRUE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Fax" => array
   (
      "Name" => "Fax",
      "ShortName" => "Fax",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => TRUE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "WWW" => array
   (
      "Name" => "Site",
      "ShortName" => "Sítio",
      "Size" => 25,
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => TRUE,
      "HRefIt" => TRUE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
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
      "Size" => 15,

      "Public" => 1,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "City" => array
   (
      "Name" => "Cidade",
      "Sql" => "CHAR(255)",
      "Size" => 15,

      "Public" => 1,
      "Admin" => 2,
      "Person" => 0,
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

      "Public" => 1,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
   ),
   "ZIP" => array
   (
      "Name" => "CEP",
      "Sql" => "VARCHAR(10)",
      "Search" => FALSE,
      "Compulsory" => TRUE,
      "Size" => 50,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
);