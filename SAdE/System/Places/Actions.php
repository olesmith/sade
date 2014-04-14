array
(
      "Consults" => array
      (
        "Href"     => "",
        "HrefArgs" => "ModuleName=Consults&Action=Search&".
                      "Protocol=".$this->GetGET("Protocol")."&".
                      "Place=#ID&Status=1",
        "Name"    => "Consultas",
        "Title"     => "Consultas de #Name",
        "Public"   => 0,
        "Person"   => 0,
        "Admin"   => 0,
        "Handler"   => "",
        "Icon"   => "time.png",
        //"Target"   => "_Consults",
     ),
      "Teachers" => array
      (
        "Href"     => "",
        "HrefArgs" => "School=#ID&ModuleName=Users&Action=Search",
        "Name"    => "Professores",
        "Public"   => 0,
        "Person"   => 0,
        "Admin"   => 0,
        "Handler"   => "",
        "Icon"   => "people.png",
        //"Target"   => "_People",
     ),
      "Students" => array
      (
        "Href"     => "",
        "HrefArgs" => "School=#ID&ModuleName=Students&Action=Search",
        "Name"    => "Alunos",
        "Title"     => "Alunos de #Name",
        "Public"   => 0,
        "Person"   => 0,
        "Admin"   => 0,
        "Handler"   => "",
        "Icon"   => "people.png",
        //"Target"   => "_Students",
     ),
      "Classes" => array
      (
        "Href"     => "",
        "HrefArgs" => "School=#ID&ModuleName=Classes&Action=Search",
        "Name"    => "Turmas",
        "Title"     => "Turmas de #Name",
        "Public"   => 0,
        "Person"   => 0,
        "Admin"   => 0,
        "Handler"   => "",
        "Icon"   => "class.gif",
        //"Target"   => "_Students",
     ),
 );
