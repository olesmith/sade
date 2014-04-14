array
(
   "School" => array
   (
      "Name" => "Escola",
      "Sql" => "INT",
      "SqlClass" => "Places",
      "GETSearchVarName"  => "School",
      //"SqlWhere" => "Type='4'",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Class" => array
   (
      "Name" => "Turma",
      "Sql" => "INT",
      "SqlClass" => "Classes",
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "FieldMethod" => "StudentClass",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 1,

      "Clerk"       => 1,
      "Teacher"     => 1,
      "Secretary"   => 1,
      "Coordinator" => 1,
   ),
   "UniqueID" => array
   (
      "Name" => "UniqueID",
      "Size" => "10",
      "Sql" => "BIGINT",
      "Search" => FALSE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Photo" => array
   (
      "Name" => "Foto",
      "Size" => "10",
      "Sql" => "FILE",
      "Search" => FALSE,

      "Extensions" => array("jpg","png"),
      "Size" => "25",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "Matricula" => array
   (
      "Name" => "Matricula",
      "Size" => "10",
      "Sql" => "VARCHAR(55)",
      "Search" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "MatriculaDate" => array
   (
      "Name" => "Data",
      "Title" => "Matricula, Data",
      "Sql" => "BIGINT",
      "Search" => FALSE,
      "Compulsory" => TRUE,
      "IsDate" => TRUE,
      "SearchDefault"     => " ",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "StatusDate1" => array
   (
      "Name" => "Início",
      "Title" => "Status, Data Início",
      "Sql" => "BIGINT",
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "IsDate" => TRUE,
      "SearchDefault"     => "__/__/____",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "StatusDate2" => array
   (
      "Name" => "Fim",
      "Title" => "Status, Data Fim",
      "Sql" => "BIGINT",
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "IsDate" => TRUE,
      "SearchDefault"     => "__/__/____",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "MotherProfession"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "Profissão, Mãe",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "FatherProfession"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "Profissão, Pai",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),

   "Address" => array
   (
      "Name" => "Endereço",
      "Sql" => "VARCHAR(255)",
      "Size" => "50",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),


   "Nationality" => array
   (
      "Name" => "Nacionalidade",
      "Sql" => "VARCHAR(255)",
      "Size" => 15,
      "Default" => "Brasileira",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
   ),
   "BirthCertNo"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "Cert. de Nascimento, No.",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
    ),
   "BirthCertPage"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "Cert. de Nascimento, Folha",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
    ),
   "BirthCertBook"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "Cert. de Nascimento, Livro",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
    ),
   "BirthCertDate"       => array
   (
      "Name"  => "Cert. de Nascimento, Emissao",
       "Sql" => "BIGINT",
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "IsDate" => TRUE,
      "SearchDefault"     => "__/__/____",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
    ),
   "BirthCertCity"       => array
   (
      "Name"  => "Cert. de Nascimento, City",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "SearchDefault"     => "__/__/____",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
    ),
   "BirthCertState"       => array
   (
      "Name"  => "Cert. de Nascimento, Estado",
      "Sql" => "ENUM",
      "Values" => array(),

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
    ),



   "Ingresso"       => array
   ( //Ingresso
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 2,
      "Coordinator" => 1,
    ),
   "Financiamento"       => array
   ( //Financiamento
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "Mensalidade"       => array
   ( //Mensalidade
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "Ingresso"       => array
   (
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "SegGrau"       => array
   ( //SegGrau
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "SegGrauCidade"       => array
   ( //SegGrauCidade
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "SegGrauEstado"       => array
   ( //SegGrauEstado
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "SegGrauConclusao"       => array
   ( //SegGrauConclusao
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "Vestibular"       => array
   ( //Vestibular
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "ConclusionDate" => array
   (
      "Name" => "Conclusão, Data",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "IsDate" => FALSE,
      "SearchDefault"     => "__/__/____",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "ConclusionDate1" => array
   (
      "Name" => "Colação, Data",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "IsDate" => TRUE,
      "SearchDefault"     => "__/__/____",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "ExpeditionDate" => array
   (
      "Name" => "Expedição, Data",
      "Sql" => "VARCHAR(255)",
      "Search" => FALSE,
      "Compulsory" => FALSE,
      "IsDate" => TRUE,
      "SearchDefault"     => "__/__/____",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "ACurso"       => array
   ( //ACurso
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Ingress",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "Contratado"       => array
   ( //Contratado
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "Convenio"       => array
   ( //Convenio
      "Sql" => "VARCHAR(255)",
      "Name"  => "Ingresso",
      "Name_UK"  => "Emitted State",
      //"NoSelectSort" => TRUE,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "GovernmentProgram"       => array
   (
      "Name"  => "Programa do Governo",
      "Sql" => "VARCHAR(255)",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
    ),
   "SchoolTransportation"       => array
   (
      "Name"  => "Transporte Escolar",
      "Sql" => "INT",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "Disabled"       => array
   (
      "Name"  => "Necessidades Especiais",
      "Sql" => "ENUM",
      "Values" => array("Não","Sim"),
      "Default" => 1,

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
    ),
   "OrigFile"       => array
   (
      "Name"  => "Arquivo de Importação",
      "Sql" => "VARCHAR(255)",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 0,
      "Teacher"     => 0,
      "Secretary" => 0,
    ),
   "Map" => array
   (
      "Name" => "Pasta",
      "Sql" => "VARCHAR(255)",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "ClassStudent" => array
   (
      "Name" => "Internal",
      "Sql" => "INT",

      "Public"      => 0,
      "Person"      => 0,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
);
