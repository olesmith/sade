array(
   /* "Common" => array */
   /* ( */
   /*    "Name" => "Básicos", */
   /*    "Data" => array */
   /*    ( */
   /*       "No","Edit","Print","History","Matriculate", */
   /*       "Name","Matricula","MatriculaDate", */
   /*       //"Matricula", */
   /*       "Status","StatusDate1","StatusDate2", */
   /*       "Class", */
   /*     ), */
   /*    "Admin" => 1, */
   /*    "Person" => 1, */
   /*    "Public" => 0, */
   /* ), */
   "Class" => array
   (
      "Name" => "Básicos",
      "Data" => array
      (
         "No","Remove","Edit","Print",
         "StudentMarks","StudentAbsences","StudentTotals","History","Matriculate",
         "Name","Matricula","MatriculaDate",
         //"Matricula",
         "Status","StatusDate1","StatusDate2",
         "Class",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 0,
   ),
   "Contacts" => array
   (
      "Name" => "Contatos",
      "Data" => array("No","Edit","Name","Status","Phone","Cell","Email","WorkPhone"),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 0,
   ),
   "Parental" => array
   (
      "Name" => "Pais",
      "Data" => array("No","Edit","Name","Status","Sex","Civil","Mother","MotherProfession","Father","FatherProfession"),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 0,
   ),
   "Adddres" => array
   (
      "Name" => "Endereço",
      "Data" => array("No","Edit","Name","Status","Address","Area","City","ZIP","State"),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 0,
   ),
   "Birth" => array
   (
      "Name" => "Nascimento",
      "Data" => array
      (
         "No","Edit","Name","Status","Sex","Race",
         "BirthDay","BirthCity","BirthState","Nationality",
         "BirthCertNo","BirthCertPage","BirthCertBook","BirthCertDate","BirthCertCity","BirthCertState",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 0,
   ),
   "PRNs" => array
   (
      "Name" => "CPF/RGs",
      "Data" => array
      (
         "No","Edit","Name","Status",
         "PRN","RG","RG, Orgão Expedidor","PRN3","PRN2","PRN2Zone","PRN2Section","PRN2State",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 0,
   ),
   "Photos" => array
   (
      "Name" => "Fotos",
      "Data" => array
      (
         "No","Edit","Name","Status","MatriculaDate","Photo",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 0,
   ),
   "Prints" => array
   (
      "Name" => "Imprimíveis",
      "Data" => array
      (
       "No","Edit","Print","StudentPrint","StudentPrintNoObs",
         "Name","Matricula","MatriculaDate",
         "Status","StatusDate1","StatusDate2",
         "Class",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 0,
   ),
);
