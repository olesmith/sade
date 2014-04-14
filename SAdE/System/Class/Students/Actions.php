array
(
   "EditStudent" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Students&Action=Edit&ID=#Student",
      "Name"    => "Editar Aluno",
      "Title"     => "Editar Aluno Dados",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "edit.gif",
      //"Target"   => "_Students",
      "Singular"   => TRUE,
   ),
   "Remove" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=ClassStudents&Action=Remove&ID=#ID",
      "Name"    => "Matricular",
      "Title"     => "Matricular Aluno(a)",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "delete_light.png",
      "Handler"   => "HandleRemove",
   ),
);
