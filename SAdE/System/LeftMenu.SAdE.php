array
(
      "00_Personal" => array
      (
         'Title' => 'Pessoal:',

         'Public' => 1,
         'Person' => 1,
         'Secretary' => 0,
         'Admin' => 1,
         '00_Top' => array
         (
            'Name' => 'Início',
            'Title' => 'Voltar ao Início',
            'Href' => '?Action=Start',

            'Public' => 1,
            'Person' => 1,
            'Secretary' => 1,
            'Medical' => 1,
            'Nurse' => 1,
            'Receptionist' => 1,
            'Admin' => 1,
         ),
         '10_Logon' => array
         (
            'Name' => 'Logon',
            'Href' => '?Action=Logon',
            'Public' => 1,
            'Person' => 0,
            'Secretary' => 0,
            'Medical' => 0,
            'Nurse' => 0,
            'Receptionist' => 0,
            'Admin' => 0,
          ),
         '20_Recover' => array
         (
            'Name' => 'Recuperar Senha',
            'Href' => '?Action=Recover',
            'Public' => 1,
            'Person' => 0,
            'Secretary' => 0,
            'Medical' => 0,
            'Nurse' => 0,
            'Receptionist' => 0,
            'Admin' => 0,
          ),
         '30_Logoff' => array
         (
            'Name' => 'Logoff',
            'Title' => 'Efetuar Logoff do Sistema',
            'Href' => '?Action=Logoff',
            "SiPE" => 1,
            "SiDS" => 0,
            'Public'      => 0,
            'Person'      => 1,
            'Secretary'     => 0,
            'Medical' => 0,
            'Nurse' => 0,
            'Receptionist' => 0,
            'Admin'       => 1,
         ),
         '40_NewPassword' => array
         (
            'Name' => 'Alterar Senha',
            'Title' => 'Alterar minha Senha',
            'Href' => '?Action=NewPassword',
            'Public'      => 0,
            'Person'      => 0,
            'Secretary'     => 1,
            'Clerk'     => 1,
            'Medical' => 1,
            'Nurse' => 0,
            'Receptionist' => 0,
            'Admin'       => 1,
         ),
         '50_SU' => array
         (
            'Name' => 'Trocar Usuário',
            'Title' => 'Virar para outro Usuário',
            'Href' => '?Action=SU',
            'Public'      => 0,
            'Person'      => 0,
            'Secretary'     => 0,
            'Medical' => 0,
            'Nurse' => 0,
            'Receptionist' => 0,
            'Admin'       => 1,
         ),
         '60_MyData' => array
         (
            'Name' => 'Meus Dados',
            'Title' => 'Editar meus Dados Pessoais',
            'Href' => '?ModuleName=People&Action=Edit&ID=#LoginID',
            'Public'      => 0,
            'Person'      => 1,
            'Secretary'     => 0,
            'Admin'       => 0,
         ),
     ),
      "01_Profile" => array
      (
         'Title' => 'Perfís:',
         "Method" => "HtmlProfilesMenu",

         'Public' => 0,
         'Person' => 1,
         'Secretary' => 1,
         'Admin' => 1,
      ),
      "02_Actions" => array
      (
         'Title' => 'Dados Gerais:',

         'Public' => 0,
         'Person' => 1,
         'Secretary' => 0,
         'Admin' => 1,
         '010_Units' => array
         (
            'Name' => 'Unidades',
            'Title' => 'Gerenciar Unidades',
            'Href' => '?ModuleName=Units&Action=Search',

            'Public' => 0,
            'Person' => 0,
            'Admin' => 1,
            'Secretary' => 1,
         ),
         /* '03_People' => array */
         /* ( */
         /*    'Name' => 'Pessoas', */
         /*    'Href' => '?ModuleName=People&Action=Search', */

         /*    'Public' => 0, */
         /*    'Person' => 0, */
         /*    'Secretary' => 1, */
         /*    'Medical' => 0, */
         /*    'Nurse' => 0, */
         /*    'Admin' => 1, */
         /*    'Receptionist' => 1, */
         /*    'Clerk' => 0, */
         /*    'Teacher' => 0, */
         /* ), */
      ),
);
