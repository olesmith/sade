array
(
   "Common" => array
   (
      "Name" => "Básicos",
      "Data" => array("School","Grade","GradePeriod","Period","Shift","Name","NickName",),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 1,
   ),
   "DiscList" => array
   (
      "Name" => "Básicos",
      "Data" => array
      (
         "No",
         "Name","NickName",
         "Daylies","AssessmentType","AbsencesType",
         "CHS","CHT",
         "Teacher","Teacher1","Teacher2",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 1,
   ),
   "Grade" => array
   (
      "Name" => "Grade Info",
      "Data" => array
      (
         "No","Edit","DeleteDisc","DiscMarks","DiscAbsences","DiscTotals","Dayly",
         "Name",
         "Daylies","AssessmentType","AbsencesType",
         "CHS","CHT",
         "Grade","GradePeriod","GradeDisc",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 1,
   ),
    "DiscWeights" => array
   (
      "Name" => "Avaliações e Pesos",
      "PreMethod" => "InitWeightsGroup",
      "Data" => array
      (
         "AssessmentType","NAssessments","AssessmentsWeights","NRecoveries","MediaLimit","FinalMedia",
         "AbsencesType","AbsencesLimit",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 1,
   ),
   "Teachers" => array
   (
      "Name" => "Professores",
      "Data" => array("Teacher","Teacher1","Teacher2",),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 1,
   ),
   "HoursDef" => array
   (
      "Name" => "Horários",
      "GenTableMethod" => "GenerateLessonsTable",
      "Data" => array
      (
         "Teacher","Name","CHS","CHT",
      ),
      "Admin" => 1,
      "Person" => 1,
      "Public" => 1,
   ),
   /* "CommonBogus" => array */
   /* ( */
   /*    "Name" => "Leftovers", */
   /*    "Data" => array("No","FileKey","StartPeriod","StartYear","StartSemester","Stat","TeacherID","TeacherAssistID","TeacherRecoursesID","NumberOfAlunos","NumberOfInvisibleAlunos"), */
   /*    "Admin" => 0, */
   /*    "Person" => 0, */
   /*    "Public" => 0, */
   /* ), */
);
