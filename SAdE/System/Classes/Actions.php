array
(
   "Import" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Import",
      "Name"    => "Importar",
      "Title"     => "Importar",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "time_light.png",
      "Handler"   => "HandleImport",
   ),
   "Student" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Student&Class=#ID&Disc=#Student",
      "Name"    => "Aluno(a)",
      "Title"     => "Tela do Aluno(a)",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "people_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      //"AltAction"   => "EditDisc",
   ),
   "Disc" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Disc&Class=#ID&Disc=#Disc",
      "Name"    => "Disciplina",
      "Title"     => "Gerenciar Disciplina",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "edit_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      //"AltAction"   => "EditDisc",
   ),
   "DeleteDisc" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DeleteDisc&Class=#ID&Disc=#Disc",
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
   "Teachers" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Teachers&Class=#ID",
      "Name"    => "Professores",
      "Title"     => "Gerenciar Professores da Turma",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "teacher_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "AltAction"   => "Teachers",
   ),
    "Discs" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Discs&Class=#ID",
      "Name"    => "Disciplinas",
      "Title"     => "Gerenciar Disciplinas da Turma",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "show_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "AltAction"   => "EditDiscs",
      "NonPostVars"   => array("Disc","Student"),
   ),
    "EditDiscs" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=EditDiscs&Class=#ID",
      "Name"    => "Editar Disciplinas",
      "Title"     => "Gerenciar Disciplinas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "show_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "AltAction"   => "Discs",
      "NonPostVars"   => array("Disc","Student"),
   ),
   "Hours" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Hours&Class=#ID",
      "Name"    => "Professores & Horários",
      "Title"     => "Gerenciar Professores & Horários da Turma",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "time_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
   ),
   "Weights" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Weights&Class=#ID",
      "Name"    => "Pesos",
      "Title"     => "Gerenciar Pesos de Notas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "time_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
   ),
   "NLessons" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=NLessons&Class=#ID",
      "Name"    => "Aulas Dadas",
      "Title"     => "Gerenciar Aulas Dadas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "time_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
   ),
   "Students" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Students&Class=#ID",
      "Name"    => "Alunos",
      "Title"     => "Gerenciar Alunos da Turma",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "students_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "AltAction"   => "EditStudents",
      "NonPostVars"   => array("Disc","Student"),
   ),
   "EditStudents" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=EditStudents&Class=#ID",
      "Name"    => "Editar Alunos",
      "Title"     => "Editar Dados dos Alunos",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "students_dark.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "AltAction"   => "Students",
      "NonPostVars"   => array("Disc","Student"),
   ),
   "PrintStudents" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=PrintStudents&Class=#ID",
      "Name"    => "Imprimir Fichas de Matricula",
      "Title"     => "Imprimir Fichas de Matricula de todos os Alunos",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "print_dark.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "AltAction"   => "Students",
      "NonPostVars"   => array("Disc","Student"),
      "NoHeads"   => 1,
      "NoInterfaceMenu"   => 1,
   ),
   "Status" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Status&Class=#ID",
      "Name"    => "Statuses",
      "Title"     => "Statuses da Turma",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "class.gif",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
   ),
   "Results" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Results&Class=#ID",
      "Name"    => "Atas",
      "Title"     => "Visualizar Atas da Turma",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "class.gif",
      "Handler"   => "HandleClass",
      "Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc","Student"),
   ),
   "Prints" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Prints&Disc=".$this->ApplicationObj->GetGET("Disc"),
      "Name"    => "Imprimíveis por Disciplina",
      "Title"     => "Gerar Imprimíveis da Turma, por Disciplina",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "mark_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc","Student"),
   ),
   "StudentsPrints" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=StudentsPrints",
      "Name"    => "Imprimíveis por Aluno",
      "Title"     => "Gerar Imprimíveis da Turma, por Aluno",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "mark_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc","Student"),
   ),
   "Daylies" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Daylies&Disc=".$this->ApplicationObj->GetGET("Disc"),
      "Name"    => "Diários Eletrônicos",
      "Title"     => "Gerenciar Diários Eletrônicos da Turma",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "schedule_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc","Student"),
   ),
   "DayliesPrints" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DayliesPrints&Class=".$this->ApplicationObj->GetGET("Class"),
      "Name"    => "Diários Eletrônicos Imprimíveis",
      "Title"     => "Imprimir Diários Eletrônicos da Turma (PDF)",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "mark_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc","Student"),
   ),
   "DayliesFlux" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DayliesFlux&Disc=".$this->ApplicationObj->GetGET("Disc"),
      "Name"    => "Visualizar Fluxo de Diários",
      "Title"     => "Visualizar Fluxo de Diários Impressos (Tela)",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      //"Icon"   => "copy_light.png",
      "Handler"   => "HandleClassDayliesFlux",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc","Student"),
   ),
  "DiscAbsences" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DiscAbsences&Disc=".$this->ApplicationObj->GetGET("Disc"),
      "Name"    => "Faltas",
      "Title"     => "Gerenciar Faltas da Disciplina - todos os Alunos",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "absences_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Student"),
      "AccessMethod"   => "RegisterDiscAbsences",
   ),
   "DiscMarks" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DiscMarks&Disc=".$this->ApplicationObj->GetGET("Disc"),
      "Name"    => "Avaliações",
      "Title"     => "Gerenciar Avaliações da Disciplina - todos os Alunos",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "mark_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Student"),
      "AccessMethod"   => "RegisterDiscMarks",
   ),
   "DiscTotals" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DiscTotals&Disc=".$this->ApplicationObj->GetGET("Disc"),
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
      "NonPostVars"   => array("Student"),
      "AccessMethod"   => "RegisterDiscTotals",
   ),
    "DiscPrint" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=DiscPrint&Latex=1&Disc=#ID",
      "Name"    => "Imprimível",
      "Title"     => "Imprimir Totais da Disciplina - todos Alunos",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "totals_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Student"),
   ),
   "StudentMarks" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=StudentMarks&Student=#Student",
      "Name"    => "Avaliações",
      "Title"     => "Gerenciar Avaliações do Aluno de todas as Disciplinas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "mark_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc"),
      "AccessMethod"   => "RegisterStudentMarks",
   ),
   "StudentAbsences" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=StudentAbsences&Student=#Student",
      "Name"    => "Faltas",
      "Title"     => "Gerenciar Faltas do Aluno - todas as Disciplinas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Icon"   => "absences_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc"),
      "AccessMethod"   => "RegisterStudentAbsences",
   ),
   "StudentTotals" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=StudentTotals&Student=#Student",
      "Name"    => "Totais",
      "Title"     => "Totais do(a) Aluno(a) - todos as Disciplinas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "totals_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc"),
      "AccessMethod"   => "RegisterStudentTotals",
   ),
   "StudentPrint" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=StudentPrint&Latex=1&Student=#Student",
      "Name"    => "Ficha de Notas",
      "Title"     => "Imprimir Ficha de Notas do(a) Aluno(a)",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "print_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc"),
   ),
   "StudentsPrint" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=StudentPrint&Latex=1&Student=All",
      "Name"    => "Fichas de Notas",
      "Title"     => "Imprimir Fichas de Nota, toda Turma",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "print_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc"),
   ),
   "StudentsPrintSimple" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=StudentPrint&Latex=1&Student=All&NoObs=1",
      "Name"    => "Fichas de Notas Simples",
      "Title"     => "Imprimir Fichas de Nota, toda Turma",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "print_light.png",
      "Handler"   => "HandleClass",
      //"Target"   => "_Classes",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc"),
   ),
   "Transfer" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Transfer",
      "Name"    => "Transferir Turma",
      "Title"     => "Transferir Alunos da Turma para Proximo Ano",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "totals_light.png",
      "Handler"   => "HandleTransfer",
      //"Target"   => "",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc"),
   ),
   "Stats" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=Stats",
      "Name"    => "Estatisticas",
      "Title"     => "Ver Estatisticas do Periodo",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "totals_light.png",
      "Handler"   => "HandleStats",
      //"Target"   => "",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc"),
   ),
   "EmptyClasses" => array
   (
      "Href"     => "",
      "HrefArgs" => "ModuleName=Classes&Action=EmptyClasses",
      "Name"    => "Turma Ociosas",
      "Title"     => "Ver e Eliminar Turma Ociosas",
      "Public"   => 0,
      "Person"   => 0,
      "Admin"   => 0,
      "Handler"   => "",
      "Icon"   => "totals_light.png",
      "Handler"   => "HandleEmptyClasses",
      //"Target"   => "",
      "Singular"   => TRUE,
      "NonPostVars"   => array("Disc"),
   ),
 );
