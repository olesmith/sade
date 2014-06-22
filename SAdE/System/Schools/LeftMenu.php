array
(
         'Title' => '&nbsp;Escola:',

         'Public' => 0,
         'Person' => 0,
         'Secretary' => 1,
         'Admin' => 1,
         'Clerk' => 1,
         'Teacher' => 1,
         '0_Students' => array
         (
            'Name' => 'Cabeçalhos dos Impressos',
            'Title' => 'Cabeçalhos dos Impresso',
            'Href' => '?Unit=#Unit&ModuleName=Schools&Action=HeadTable&School=#ID',

            'Public' => 0,
            'Person' => 0,
            'Secretary' => 1,
            'Admin'     => 1,
            'Clerk'     => 0,
            'Teacher'   => 0,
         ),
         '1_Students' => array
         (
            'Name' => 'Alunos da Escola',
            'Title' => 'Gerenciar Alunos da Escola',
            'Href' => '?Unit=#Unit&ModuleName=Students&Action=Search&School=#ID',

            'Public' => 0,
            'Person' => 0,
            'Secretary' => 1,
            'Admin'     => 1,
            'Clerk'     => 1,
            'Teacher'   => 0,
         ),
         "3_Clerks" => array
         (
            "Name" => "Secretário(a)s Escolares",
            "Title" => "Secretário(a)s Escolares",
            'Href' => '?Unit=#Unit&ModuleName=Users&Clerks=1&Action=Search&School=#ID',
            'Public' => 0,
            'Person' => 0,
            'Secretary' => 1,
            'Admin'     => 1,
            'Clerk'     => 0,
            'Teacher'    => 0,
         ),
);
