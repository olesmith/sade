array
(
      "MailList" => array
      (
        "Href"     => "",
        "HrefArgs" => "Action=MailList",
        "Title"    => "Gerar Lista de Emails",
        "Name"     => "Lista de Emails",
        "Public"   => 0,
        "Person"   => 0,
        "Admin"   => 1,
        "Handler"   => "HandleMailList",
      ),
      "Schedule" => array
      (
        "Href"     => "",
        "HrefArgs" => "Action=Schedule&Teacher=#ID",
        "Title"    => "Disciplinas e Horários do Professor",
        "Name"     => "Disciplinas",
        "Public"   => 0,
        "Person"   => 0,
        "Icon"   => "schedule_light.png",
        "Admin"   => 1,
        "Handler"   => "HandleTeacherSchedule",
        "AccessMethod" => "IsTeacher",
      ),
);
