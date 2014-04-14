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
   "Department" => array
   (
      "Name" => "Secretaria",
      "Sql" => "INT",
      "SqlClass" => "Departments",
      "SqlWhere" => "ID=#Department",
      "Search" => TRUE,
      "NoSearchEmpty" => TRUE,
      "SearchDefault" => "#Department",
      "Compulsory" => TRUE,
      "GETSearchVarName" => "Department",

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "Name" => array
   (
      "Name" => "Nome",
      "Size" => "50",
      "Sql" => "VARCHAR(255)",
      "Search" => TRUE,
      "Compulsory" => TRUE,

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "TargetProfile" => array
   (
      "Name" => "Grupo de Atendentes",
      "Sql" => "ENUM",
      "Search" => TRUE,
      "Compulsory" => TRUE,
      "Values" => array("Secretário(a)s","Médico(a)s"),
      "ValueOptions" => array
      (
         array
         (
            "Key" => "Profile_Secretary",
            "Name" => "Secretário(a)",
            "ConsultName" => "Cidadão",
            "SearchData" => array("Name","PRN"),
         ),
         array
         (
            "Key" => "Profile_Medical",
            "Name" => "Médico(a)",
            "ConsultName" => "Paciente",
            "SearchData" => array("Name","PRN","SUS"),
         ),
      ),

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 1,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
   "TargetPerson" => array
   (
      "Name" => "Atendente Específico",
      "ShortName" => "Atendente",
      "Sql" => "INT",
      "SqlClass" => "People",
      "Search" => TRUE,
      "NoAdd" => TRUE,
      "Compulsory" => FALSE,
      "FieldMethod" => "TargetPersonSelect",
      "TriggerFunction" => "UpdateTargetPerson",
      "SqlWhere" => "ID=#Department",

      "Public" => 0,
      "Person" => 0,
      "Admin" => 2,
      "Secretary" => 2,
      "Medical" => 1,
      "Nurse" => 1,
      "Receptionist" => 1,
   ),
);