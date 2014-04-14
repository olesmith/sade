array
(
   "ID" => array
   (
      "Name" => "ID",
      "Sql" => "INT NOT NULL PRIMARY KEY AUTO_INCREMENT",
      "Search" => FALSE,

      "Public" => 0,
      "Person" => 1,
      "Admin" => 1,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Coordinator" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "Name" => array
   (
      "Name" => "Nome da Entidade",
      "Size" => 10,
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
      "Coordinator" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "Title" => array
   (
      "Name" => "Nome Completa",
      "Size" => 25,
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
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "Department" => array
   (
      "Name" => "Departamento",
      "Size" => 25,
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
      "Coordinator" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   /* "Mayor" => array */
   /* ( */
   /*    "Name" => "Prefeito", */
   /*    "Sql" => "INT", */
   /*    "SqlClass" => "People", */
   /*    "Search" => FALSE, */
   /*    "Compulsory" => FALSE, */

   /*    "Public" => 1, */
   /*    "Person" => 0, */
   /*    "Admin" => 2, */
   /*    "Secretary" => 2, */
   /*    "Medical" => 1, */
   /*    "Nurse" => 1, */
   /*    "Receptionist" => 1, */
   /*    "Clerk" => 1, */
   /*    "Teacher" => 1, */
   /* ), */
   /* "ViceMayor" => array */
   /* ( */
   /*    "Name" => "Vice-Prefeito", */
   /*    "Sql" => "INT", */
   /*    "SqlClass" => "People", */
   /*    "Search" => FALSE, */
   /*    "Compulsory" => FALSE, */

   /*    "Public" => 1, */
   /*    "Person" => 0, */
   /*    "Admin" => 2, */
   /*    "Secretary" => 2, */
   /*    "Medical" => 1, */
   /*    "Nurse" => 1, */
   /*    "Receptionist" => 1, */
   /*    "Clerk" => 1, */
   /*    "Teacher" => 1, */
   /* ), */
   "Email" => array
   (
      "Name" => "Email Oficial",
      "ShortName" => "Email",
      "Size" => 25,
      "Sql" => "VARCHAR(255)",
      "Iconify" => FALSE,
      "Compulsory" => TRUE,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
   ),
   "Phone" => array
   (
      "Name" => "Fone",
      "ShortName" => "Fone",
      "Sql" => "VARCHAR(255)",
      "Size" => 12,
      "Search" => FALSE,
      "Compulsory" => TRUE,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "Fax" => array
   (
      "Name" => "Fax",
      "ShortName" => "Fax",
      "Size" => 12,
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => TRUE,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
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
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "Address" => array
   (
      "Name" => "Endereço",
      "ShortName" => "Endereço",
      "Size" => 25,
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => TRUE,
      "Size" => 50,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
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
      "Coordinator" => 1,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "City" => array
   (
      "Name" => "Cidade",
      "Sql" => "CHAR(255)",
      "Size" => 15,

      "Public" => 1,
      "Admin" => 2,
      "Person" => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
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
      "Coordinator" => 1,
      "Medical"   => 1,
      "Nurse"     => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "ZIP" => array
   (
      "Name" => "CEP",
      "Sql" => "VARCHAR(10)",
      "Search" => FALSE,
      "Compulsory" => TRUE,
      "Size" => 10,

      "Public" => 1,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "AdmEmail" => array
   (
      "Name" => "Email Administrativa",
      "Sql" => "VARCHAR(255)",
      "TriggerFunction" => "TestUnitMailAccount",

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 0,
      "Coordinator" => 1,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
   ),
   "AdmEmailPassword" => array
   (
      "Name" => "Senha, Email Administrativa",
      "Sql" => "VARCHAR(255)",
      "Size" => 15,
      "TriggerFunction" => "TestUnitMailAccount",

      "Password" => TRUE,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 0,
      "Coordinator" => 0,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
      "Clerk" => 0,
      "Teacher" => 0,
   ),
   "CCEmail" => array
   (
      "Name" => "Email para usar em CC",
      "Sql" => "VARCHAR(255)",

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 0,
      "Coordinator" => 0,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
      "Clerk" => 0,
      "Teacher" => 0,
   ),
   "FromName" => array
   (
      "Name" => "Nome para como FROM",
      "Sql" => "VARCHAR(255)",

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 0,
      "Coordinator" => 0,
      "Medical" => 0,
      "Nurse" => 0,
      "Receptionist" => 0,
      "Clerk" => 0,
      "Teacher" => 0,
   ),
   "HtmlIcon1" => array
   (
      "Name" => "Ícone Esquerda",
      "Title" => "Ícone Esquerda - para Telas",
      "Extensions" => array("png","jpg"),
      "Sql" => "FILE",

      "Public" => 1,
      "Person" => 1,
      "Admin" => 2,
      "Secretary" => 1,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "HtmlIcon2" => array
   (
      "Name" => "Ícone Direita",
      "Title" => "Ícone Direita - para Telas",
      "Extensions" => array("png","jpg"),
      "Sql" => "FILE",

      "Public" => 1,
      "Person" => 1,
      "Admin" => 2,
      "Secretary" => 1,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "LatexIcon1" => array
   (
      "Name" => "Ícone Esquerda (Impresso)",
      "Title" => "Ícone Esquerda - para Impressos",
      "Extensions" => array("png","jpg"),
      "Sql" => "FILE",

      "Public" => 1,
      "Person" => 1,
      "Admin" => 2,
      "Secretary" => 1,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "LatexIcon2" => array
   (
      "Name" => "Ícone Direita (Impresso)",
      "Title" => "Ícone Direita - para Impressos",
      "Extensions" => array("png","jpg"),
      "Sql" => "FILE",

      "Public" => 1,
      "Person" => 1,
      "Admin" => 2,
      "Secretary" => 1,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "LogoHeight" => array
   (
      "Name" => "Altura do Logo",
      "Title" => "Altura do Logo - se Precisa",
      "Sql" => "INT",
      "Width" => 2,

      "Public" => 1,
      "Person" => 1,
      "Admin" => 2,
      "Secretary" => 1,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
   "LogoWidth" => array
   (
      "Name" => "Altura do Logo",
      "Title" => "Altura do Logo - se Precisa",
      "Sql" => "INT",
      "Width" => 2,

      "Public" => 1,
      "Person" => 1,
      "Admin" => 2,
      "Secretary" => 1,
      "Coordinator" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
      "Clerk" => 1,
      "Teacher" => 1,
   ),
);