array
(
   "ID" => array
   (
      "Name" => "ID",
      "Sql" => "INT NOT NULL PRIMARY KEY AUTO_INCREMENT",
      "ShowIDCols" => array(""),
      "EditIDCols" => array(""),
      "Search" => FALSE,
      "Admin" => 1,
      "Public" => 0,
      "Person" => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      "Clerk" => 1,
      "Teacher"   => 1,
   ),
   "Status" => array
   (
      "Name" => "Status",
      "Sql" => "ENUM",
      "Values" => array
      (
         "Ativo",
         "Inativo",
       ),
      "Default" => 1,
      "Search" => TRUE,
      "SearchDefault"  => 1,
      "SearchCheckBox" => TRUE,

      "Public" => 1,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 1,
    ),
   "Profession" => array
   (
      "Name" => "Professão",
      "Sql" => "VARCHAR(255)",
      "ShowIDCols" => array(""),
      "EditIDCols" => array(""),
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "Size" => 35,

      "Admin" =>  2,
      "Person" => 0,
      "Public" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 1,
   ),
    "Department" => array
    (
      "Name" => "Secretaria",
      "Sql" => "INT",
      "SqlClass" => "Departments",

      "Search" => TRUE,
      "Compulsory" => FALSE,

      "Admin" => 2,
      "Person" => 1,
      "Public" => 0,
      "Clerk" => 2,
      "Teacher"   => 1,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
   ),
   "School" => array
   (
      "Name" => "Escola",
      "Sql" => "INT",
      "SqlClass" => "Places",
      //"SqlWhere" => "Type='4'",
      "Search" => TRUE,
      "GETSearchVarName"  => "School",
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Coordinator" => 1,
      "Clerk" => 1,
      "Teacher"   => 1,
      "Secretary" => 2,
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Sql" => "VARCHAR(255)",
      "ShowIDCols" => array(""),
      "EditIDCols" => array(""),
      "Search" => TRUE,
      "Compulsory" => TRUE,
      "Size" => 35,

      "Admin" =>  2,
      "Person" => 0,
      "Public" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Teacher"   => 1,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "Email" => array
   (
      "CGIName" => "CGIEmail",
      "Name" => "Email (Nome de Usuário)",
      "ShortName" => "Email",
      "Sql" => "CHAR(255)",
      "ShowIDCols" => array(""),
      "EditIDCols" => array(""),
      "Unique" => 1,
      "Iconify" => 1,
      "Compulsory" => "1",
      "Regexp" => "^\S+@\S+$",
      "RegexpText" => "Formato: someone@somewhere.com",
      "RegexpText_UK" => "Format: someone@somewhere.com",
      "Search" => TRUE,
      "Compulsory" => FALSE,
      //"TriggerFunction" => "SendEmailChangeMail",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Clerk" => 2,
      "Teacher" => 1,
   ),
   "Passwd" => array
   (
      "CGIName" => "CGIPasswd",
      "Name" => "Senha de Acesso",
      "ShortName" => "Senha",
      "MD5" => 1,
      "Password" => 1,
      "Sql" => "VARCHAR(32)",
      "ShowIDCols" => array(""),
      "EditIDCols" => array(""),
      "Compulsory" => FALSE,
      //"TriggerFunction" => "SendPasswordChangeMail",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Clerk" => 2,
      "Teacher" => 0,
   ),
   "WorkPhone" => array
   (
      "Name" => "Fone Trabalho",
      "Sql" => "CHAR(255)",
      "Size" => 15,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Clerk" => 2,
      "Teacher" => 2,
   ),
   "Phone" => array
   (
      "Name" => "Fone Casa",
      "Sql" => "CHAR(255)",
      "Size" => 15,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "Fax" => array
   (
      "Name" => "Fax",
      "Sql" => "CHAR(255)",
      "Size" => 15,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "Cell" => array
   (
      "Name" => "Celular",
      "Sql" => "CHAR(255)",
      "Size" => 15,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
    "RecoverCode" => array
   (
      "Name" => "Código de recuperação de senha",
      "Sql" => "VARCHAR(20)",
      "ShowIDCols" => array(""),
      "EditIDCols" => array(""),
      "Search" => FALSE,
      "Hidden" => TRUE,
      "ReadOnly" => TRUE,
      "AdminReadOnly" => TRUE,

      "Admin" =>  0,
      "Person" => 0,
      "Public" => 0,
      "Secretary" => 0,
      "Coordinator" => 0,
      "Clerk" => 0,

      
   ),
   "RecoverMTime" => array
   (
      "Name" => "Horário da Geração do Código de Recuperação",
      "Sql" => "INT",
      "ShowIDCols" => array(""),
      "EditIDCols" => array(""),
      "Search" => FALSE,
      "Hidden" => TRUE,
      "ReadOnly" => TRUE,
      "AdminReadOnly" => TRUE,
      "Default" => 0,

      "Admin" =>  0,
      "Person" => 0,
      "Public" => 0,
      "Secretary" => 0,
      "Clerk" => 0,
      "Coordinator" => 0,

      
   ),
   "Street" => array
   (
      "Name" => "Rua/Av.",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "StreetNumber" => array
   (
      "Name" => "No.",
      "Title" => "Rua No.",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "StreetCompletion" => array
   (
      "Name" => "Complemento",
      "Title" => "Rua, Complemento",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "Area" => array
   (
      "Name" => "Bairro",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "City" => array
   (
      "Name" => "Cidade",
      "Sql" => "CHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
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
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "ZIP" => array
   (
      "Name" => "CEP",
      "Sql" => "CHAR(255)",
      "Size" => 8,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "WorkAddress" => array
   (
      "Name" => "Local de Trabalho",
      "Sql" => "VARCHAR(255)",
      "ShowIDCols" => array(""),
      "EditIDCols" => array(""),
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "Size" => 35,

      "Admin" =>  2,
      "Person" => 0,
      "Public" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "Sex" => array
   (
      "Name" => "Gênero",
      "Sql" => "ENUM",
      "Values" => array("Feminino","Masculino"),
      "Default" => 1,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "Civil" => array
   (
      "Name" => "Estado Civil",
      "Sql" => "ENUM",
      "Values" => array("Solteiro(a)","Casado(a)","Divorceado(a)","Viúvo(a)"),
      "Default" => 1,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "Age" => array
   (
      "Name" => "Idade",
      "Sql" => "INT",
      "Search" => TRUE,
      "Compulsory" => FALSE,
      "Size" => 3,

      "Public" => 0,
      "Admin" => 1,
      "Person" => 0,
      "Secretary" => 1,
      "Clerk" => 1,
      "Teacher" => 2,

      "Medical"   => 1,
      "Nurse"     => 1,
      "Receptionist" => 2,
   ),
   "Race" => array
   (
      "Name" => "Cor",
      "Sql" => "ENUM",
      "Values" => array("Pardo","Negro","Branco","Asiático"),
      "Default" => 1,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "BirthDay" => array
   (
      "Name" => "Nascimento",
      "Sql" => "INT",
      "Search" => FALSE,
      //Should turn back on
      "Compulsory" => FALSE,
      "IsDate" => TRUE,
      "SearchDefault"     => "__/__/____",

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "BirthCity" => array
   (
      "Name" => "Cidade de Nasc.",
      "Sql" => "VARCHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "BirthState" => array
   (
      "Name" => "Estado de Nasc.",
      "Sql" => "ENUM",
      "NoSelectSort" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Coordinator" => 1,
      "Clerk" => 2,
      "Teacher" => 2,

      
   ),
   "Mother" => array
   (
      "Name" => "Nome da Mãe",
      "Sql" => "VARCHAR(255)",
      "Size" => 35,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "MotherBirth" => array
   (
      "Name" => "Nascimento da Mãe",
      "Sql" => "VARCHAR(255)",
      "IsDate" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Teacher" => 2,

      
   ),
   "MotherCity" => array
   (
      "Name" => "Mãe, Cidade de Nasc.",
      "Sql" => "VARCHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "MotherState" => array
   (
      "Name" => "Mãe, Estado de Nasc.",
      "Sql" => "ENUM",
      "NoSelectSort" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "Father" => array
   (
      "Name" => "Nome do Pai",
      "Sql" => "VARCHAR(255)",
      "Size" => 35,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Teacher" => 2,

      
   ),
   "FatherBirth" => array
   (
      "Name" => "Nascimento da Pai",
      "Sql" => "VARCHAR(255)",
      "IsDate" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "FatherCity" => array
   (
      "Name" => "Pai, Cidade de Nasc.",
      "Sql" => "VARCHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,

      
   ),
   "FatherState" => array
   (
      "Name" => "Pai, Estado de Nasc.",
      "Sql" => "ENUM",
      "NoSelectSort" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "SUS"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "SUS",
      "Name_UK"  => "SUS",
      "ShortName"  => "SUS",
      "ShorName_UK"  => "SUS",
      "Size" => "15",
      "Search" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "PIS"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "PIS/PASEP",
      "Name_UK"  => "PIS/PASEP",
      "ShortName"  => "PIS",
      "ShorName_UK"  => "PIS",
      "Size" => "15",
      "Search" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "PRN" => array
   (
      "Name" => "CPF",
      "Sql" => "VARCHAR(20)",
      "Search" => TRUE,
      "TriggerFunction" => "VerifyPRN",
      "FieldMethod" => "MakePRNField",
      "Size" => "15",
      "CGIName" => "PrnCgi",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "PRN1" => array
   (
      "Name" => "RG",
      "Sql" => "VARCHAR(25)",
      "Search" => TRUE,
      "Size" => "15",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Teacher" => 2,
      "Clerk" => 2,

      
   ),
   "PRN1Org" => array
   (
      "Name" => "RG, Orgão Expedidor",
      "Sql" => "VARCHAR(25)",
      "Size" => "25",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "PRN1Date"       => array
   (
      "Sql" => "INT",
      "Name"  => "RG, Data Exp.",
      "Name_UK"  => "Emitted Date",
      "Size" => "15",
      "IsDate" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "PRN1City" => array
   (
      "Name" => "RG, Cidade",
      "Sql" => "VARCHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "PRN1State"       => array
   (
      "Sql" => "ENUM",
      "NoSelectSort" => TRUE,
      "Name"  => "RG, Estado",
      "Name_UK"  => "Emitted State",
      "NoSelectSort" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
    ),
   "PRN2"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "Título Eleitor",
      "Name_UK"  => "",
      "Size" => "15",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "PRN2Org" => array
   (
      "Name" => "Título Eleitor, Orgão Expedidor",
      "Sql" => "VARCHAR(25)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "PRN2Date"       => array
   (
      "Sql" => "INT",
      "Name"  => "Título Eleitor, Data Exp.",
      "Name_UK"  => "Emitted Date",
      "IsDate" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,

      
   ),
   "PRN2City" => array
   (
      "Name" => "Título Eleitor, Cidade",
      "Sql" => "VARCHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,

      
   ),
   "PRN2Zone" => array
   (
      "Name" => "Título Eleitor, Zona",
      "Sql" => "VARCHAR(25)",

      "Public"      => 0,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "PRN2Section"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "Título Eleitor, Seção",

      "Public"      => 0,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "PRN2State"       => array
   (
      "Sql" => "ENUM",
      "Name"  => "Título Eleitor, Estado",
      "Name_UK"  => "Emitted State",
      "NoSelectSort" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
    ),
   "PRN3"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "Serv. Exêrcito",
      "Name_UK"  => "",
      "Size" => "15",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "PRN3Org" => array
   (
      "Name" => "Serv. Exêrcito, Orgão Expedidor",
      "Sql" => "VARCHAR(25)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "PRN3Date"       => array
   (
      "Sql" => "INT",
      "Name"  => "Serv. Exêrcito, Data Exp.",
      "Name_UK"  => "Emitted Date",
      "IsDate" => TRUE,

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "PRN3City" => array
   (
      "Name" => "Serv. Exêrcito, Cidade",
      "Sql" => "VARCHAR(255)",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
   ),
   "PRN3State"       => array
   (
      "Sql" => "ENUM",
      "Name"  => "Serv. Exêrcito, Estado",
      "Name_UK"  => "Emitted State",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 2,
    ),
   "TID"       => array
   (
      "Sql" => "VARCHAR(55)",
      "Name"  => "Old TID/Esc.",

      "Public" => 0,
      "Admin" => 2,
      "Person" => 0,
      "Secretary" => 2,
      "Clerk" => 2,
      "Coordinator" => 1,
      "Teacher" => 1,
    ),
 );