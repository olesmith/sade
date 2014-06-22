array
(
   "LastStudentsLast" => array
   (
      "Name" => "Ultimos Alunos por Ultimo",
      "Size" => "10",
      "Sql" => "ENUM",
      "Values" => array("Não","Sim"),
      "Default" => 1,
      "Search" => FALSE,
      "IsDate" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "LastStudentsLastDate" => array
   (
      "Name" => "Ult. Alunos Ult., a Partir",
      "Size" => "10",
      "Sql" => "CHAR(10)",
      "Search" => FALSE,
      "IsDate" => TRUE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),

   "DayliesOrientation" => array
   (
      "Name" => "Orientaçao",
      "Size" => "10",
      "Sql" => "ENUM",
      "Values" => array("Paisagem","Retrato",),
      "Default" => 1,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      
      
      
   ),
   "DayliesNFields_1" => array
   (
      "Name" => "No. de Colunas (datas) no Diários",
      "Title" => "No. de Colunas (datas) no Diários,  Paisagem",
      "Size" => "2",
      "Sql" => "INT",
      "Default" => 40,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "DayliesNFields_2" => array
   (
      "Name" => "No. de Colunas (datas) no Diários",
      "Title" => "No. de Colunas (datas) no Diários, Retrato",
      "Size" => "2",
      "Sql" => "INT",
      "Default" => 20,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "DayliesNStudentsPP_1" => array
   (
      "Name" => "No. de Alunos por Pagina",
      "Title" => "No. de Alunos por Pagina,  Paisagem",
      "Size" => "2",
      "Sql" => "INT",
      "Default" => 30,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "DayliesNStudentsPP_2" => array
   (
      "Name" => "No. de Alunos por Pagina",
      "Title" => "No. de Alunos por Pagina, Retrato",
      "Size" => "2",
      "Sql" => "INT",
      "Default" => 40,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "DayliesNMarkFields_1" => array
   (
      "Name" => "No. de Colunas (datas) nas Fichas de Nota",
      "Title" => "No. de Colunas (datas) nas Fichas de Nota, Paisagem",
      "Size" => "2",
      "Sql" => "INT",
      "Default" => 20,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "DayliesNMarkFields_2" => array
   (
      "Name" => "No. de Colunas (datas) nas Fichas de Nota",
      "Title" => "No. de Colunas (datas) nas Fichas de Nota, Retrato",
      "Size" => "2",
      "Sql" => "INT",
      "Default" => 10,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "DayliesNSignaturesFields_1" => array
   (
      "Name" => "No. de Assinaturas nas Lista de Assinaturas",
      "Title" => "No. de Assinaturas nas Lista de Assinaturas, Paisagem",
      "Size" => "2",
      "Sql" => "INT",
      "Default" => 4,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "DayliesNSignaturesFields_2" => array
   (
      "Name" => "No. de Assinaturas nas Lista de Assinaturas",
      "Title" => "No. de Assinaturas nas Lista de Assinaturas, Retrato",
      "Size" => "2",
      "Sql" => "INT",
      "Default" => 2,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "DayliesBackPage" => array
   (
      "Name" => "Incluir Aversos",
      "Values" => array("Nao","Sim"),
      "Sql" => "ENUM",
      "Default" => 1,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   "DayliesTwoPage" => array
   (
      "Name" => "Incluir Paginas Vasios apos Disciplinas",
      "Values" => array("Nao","Sim"),
      "Sql" => "ENUM",
      "Default" => 1,
      "Search" => FALSE,

      "Public"      => 1,
      "Person"      => 1,
      "Admin"       => 2,

      "Clerk" => 2,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
);
