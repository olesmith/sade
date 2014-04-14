array
(
   "Common" => array
   (
      "Name" => "Básicos",
      "Name_UK" => "Basics",
      "Data" => array("Name","Status","Department","Email","Passwd","Profession","WorkPhone","Phone","Fax","Ext","Cell"),
      "Single" => TRUE,
      "Admin" => 1,
      "Public" => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
      "Teacher" => 1,
      "Medical" => 0,
      "Nurse" => 0,
   ),
   "Recover" => array
   (
      "Name" => "Recuperação de Senha",
      "Name_UK" => "Password Recovery",
      "Data" => array("Name","Status","RecoverCode","RecoverCodeMTime"),
      "Admin" => 1,
      "Public" => 0,
      "Secretary" => 0,
      "Medical" => 0,
      "Nurse" => 0,
   ),
    "PRNs" => array
   (
      "Name" => "CPF/RG",
      "Name_UK" => "Birth",
      "Data" => array
      (
         "PIS","PRN","SUS",
         "PRN1","PRN1Date","PRN1Org","PRN1City","PRN1State",
         "PRN2","PRN2Date","PRN2Org","PRN2City","PRN2State",
         "PRN3","PRN3Date","PRN3Org","PRN3City","PRN3State",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Distributor" => 2,
      "Teacher" => 1,
      "Coordinator" => 2,
      "Public" => 1,
   ),
   "Address" => array
   (
      "Name" => "Endereço",
      "Name_UK" => "Address",
      "Data" => array("Street","StreetNumber","StreetCompletion","Area","ZIP","City","State","WorkAddress"),
      "Admin" => 1,
      "Person" => 1,
      "Distributor" => 2,
      "Teacher" => 1,
      "Coordinator" => 2,
      "Public" => 1,
   ),
   "Birth" => array
   (
      "Name" => "Nascimento",
      "Name_UK" => "Birth",
      "Data" => array("Sex","Civil","BirthDay","BirthCity","BirthState"),
      "Admin" => 1,
      "Person" => 1,
      "Distributor" => 2,
      "Teacher" => 1,
      "Coordinator" => 2,
      "Public" => 1,
   ),
   "Parents" => array
   (
      "Name" => "Pais",
      "Name_UK" => "Parents",
      "Data" => array
      (
         "Mother","MotherBirth","MotherCity","MotherState",
         "Father","FatherBirth","FatherCity","FatherState"
      ),
      "Admin" => 1,
      "Person" => 1,
      "Distributor" => 2,
      "Teacher" => 1,
      "Coordinator" => 2,
      "Public" => 1,
   ),
);