array
(
   "Dates" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Dates&Action=Search",
      "Title"    => "Datas",
      "Name"     => "Datas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 1,
      //"Handler"   => "VerifyPeriodTables",
    ),
   /* "Verify" => array */
   /* ( */
   /*    "Href"     => "", */
   /*    "HrefArgs" => "ModuleName=Periods&Action=Verify&ID=#ID&Period=#Period", */
   /*    "Title"    => "Verificar Tabelas do Periodo", */
   /*    "Name"     => "Verificar Tabelas", */
   /*    "Public"   => 0, */
   /*    "Person"   => 0, */
   /*    "Admin"   => 1, */
   /*    "Handler"   => "VerifyPeriodTables", */
   /*  ), */
   "Classes" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Search&Period=#ID",
      "Title"    => "Turmas do Periodo",
      "Name"     => "Turmas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 1,
      "Icon"   => "schedule_light.png",
    ),
   "Discs" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Periods&Action=Discs&Period=#ID",
      "Title"    => "Disciplinas do Periodo",
      "Name"     => "Disciplinas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 1,
      "Icon"   => "history_light.png",
      "Handler"   => "HandleDiscs",
    ),
);
