array
(
   "PublicInterface" => array
   (
    "Name" => "Interface Público Permitido",
    "Type" => "scalar",
    "Regex" => '^[01]$',
    "Size" => 5,
   ),
   "ExtraPathVars"     => array
   (
    "Name" => "Variáveis de Extra Path Information",
    "Type" => "list",
    "Size" => 15,
   ),
   "DataGroupMenusPerLine" => array
   (
    "Name" => "No. de Itens no Menu 'Grupos de Dados'",
    "Type" => "scalar",
    "Regex" => '^[12]$',
    "Size" => 5,
   ),
   "DateLimit"     => array
   (
    "Name" => "Data Limite para abrir/fechar solicitações (YYYYMMDD)",
    "Type" => "scalar",
    "Regex" => '\d{8}',
    "Size" => 8,
   ),
   "SaveLink"     => array
   (
    "Name" => "Link 'seguro' do programa",
    "Type" => "scalar",
    "Size" => 25,
   ),
   "WeekDays"     => array
   (
    "Name" => "Dias de Semana",
    "Type" => "list",
    "Length" => 7,
    "Size" => 5,
   ),
   "Months"     => array
   (
    "Name" => "Meses do Ano",
    "Type" => "list",
    "Length" => 12,
    "Size" => 15,
   ),
   "Months_Short"     => array
   (
    "Name" => "Meses do Ano, versão curta",
    "Type" => "list",
    "Length" => 12,
    "Size" => 15,
   ),
   "States"     => array
   (
    "Name" => "Estados",
    "Type" => "list",
    "Length" => 17,
    "Size" => 10,
    "Sort" => 1,
   ),
   "States_Short"     => array
   (
    "Name" => "Estadosa",
    "Type" => "list",
    "Length" => 17,
    "Size" => 10,
    "Sort" => 1,
   ),
);
