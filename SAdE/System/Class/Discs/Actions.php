array
(
   "DiscMarks" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DiscMarks&Disc=#ID",
      "Name"    => "Avaliações",
      "Title"     => "Gerenciar Avaliações da Disciplina - todos os Alunos",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "mark_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "AccessMethod"   => "RegisterDiscMarks",
   ),
   "DiscAbsences" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DiscAbsences&Disc=#ID",
      "Name"    => "Faltas",
      "Title"     => "Cadastrar Faltas da Disciplina - todos Alunos",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "absences_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "AccessMethod"   => "RegisterDiscAbsences",
   ),
   "DiscTotals" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DiscTotals&Disc=#ID",
      "Name"    => "Totais",
      "Title"     => "Totais da Disciplina - todos Alunos",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "totals_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "AccessMethod"   => "RegisterDiscTotals",
   ),
   "DiscPrint" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DiscPrint&Latex=1&Disc=#ID",
      "Name"    => "Imprimir",
      "Title"     => "Imprimir Totais da Disciplina - todos Alunos",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "print_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
   ),
   "DeleteDisc" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DeleteDisc&Disc=#ID",
      "Name"    => "Deletar Disciplina",
      "Title"     => "Deletar Disciplina",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "delete_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      //"AltAction"   => "EditDisc",
   ),
 );
