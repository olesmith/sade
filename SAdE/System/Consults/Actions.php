array
(
      "AddConsult" => array
      (
        "Href"      => "",
        "HrefArgs"  => "ModuleName=Consults&Action=Add&PID=#PID",
        "Name"      => "Consultar!",
        "Title"     => "Iniciar nova  Consulta para #Name",
        "Public"    => 1,
        "Person"    => 0,
        "Admin"     => 0,
        "Icon"      => "time.png",
        "Handler"   => "HandleAddConsult",
        "Target"   => "_AddConsult",

        "Public" => 0,
        "Person" => 0,
        "Admin" => 1,
        "Secretary" => 0,
        "Medical" => 0,
        "Nurse" => 1,
        "Receptionist" => 1,
     ),
      "Consult" => array
      (
        "Href"      => "",
        "HrefArgs"  => "ModuleName=Consults&Action=Consult&ID=#ID",
        "Name"      => "Consultar!",
        "Title"     => "Iniciar nova  Consulta para #Name",
        "Public"    => 1,
        "Person"    => 0,
        "Admin"     => 0,
        "Icon"      => "time.png",
        "Handler"   => "HandleConsult",

        "Public" => 0,
        "Person" => 0,
        "Admin" => 1,
        "Secretary" => 1,
        "Medical" => 1,
        "Nurse" => 0,
        "Receptionist" => 0,
     ),
 );
