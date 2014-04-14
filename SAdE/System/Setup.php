<?php

    array
    (
       "DefaultAction" => "Start",
       "DefaultProfile" => 4,
       "LoginPermissionVars" => array(),
       "SqlTableVars" => array("SqlVars"),
       "CommonData" => array
       (
          "Hashes" => array
          (
             "Login" => "Auth.Data.php",
             "MySql" => ".DB.php",
             "Mail" => ".Mail.php",
          ),
       ),
       "AllowedModules" => array
       (
          "People",
          "Clerks",
          "Units",
          "Departments",
          "Protocols",
          "Places",
          "Consults",

          "Dates",
          "Periods",
          "Grade",
          "GradePeriods",
          "GradeDiscs",
          "GradeQuestionaries",
          "GradeQuestions",

          "Schools",
          "Users",
          "Students",
          "Matriculas",
          "Classes",
          "ClassStudents",
          "ClassDiscs",
          "ClassDiscLessons",
          "ClassDiscWeights",
          "ClassMarks",
          "ClassAbsences",
          "ClassStatus",
          "ClassQuestions",
          "ClassObservations",
       ),
       "ModuleDependencies" => array
       (
          "People" => array("Units","Departments","Schools"),
          "Clerks" => array("People","Schools"),
          "Units" => array("People","Departments"),
          "Departments" => array("People"),
          "Places" => array("Departments"),
          //"Protocols" => array("Places","Consults"),
          //"Consults" => array("Protocols"),

          "Dates" => array("Units"),
          "Periods" => array("Dates","Schools"),

          "Grade" => array("GradePeriods"),
          "GradePeriods" => array("GradeDiscs","GradeQuestionaries","GradeQuestions"),
          "GradeDiscs" => array("Grade","GradePeriods"),
          "GradeQuestionaries" => array("Grade","GradePeriods"),
          "GradeQuestions" => array("Grade","GradePeriods","GradeQuestionaries"),

          "Schools" => array("Places","Clerks","Classes",),
          "Students" => array("Schools","Classes","Matriculas",),
          "Matriculas" => array("Units","Schools"),
          "Users" => array("Schools",),
          "Classes" => array
          (
             "Schools","Periods",
             "Grade","GradePeriods",
             "GradeDiscs","Students","Users",
             "ClassDiscs","ClassDiscLessons",
             "ClassStudents","ClassDiscWeights","ClassDiscNLessons",
             "ClassMarks","ClassAbsences","ClassStatus",
             "ClassQuestions","ClassObservations",
             "ClassDiscContents","ClassDiscAbsences","ClassDiscAssessments","ClassDiscMarks",
          ),
          "ClassStudents"    => array("Classes"),
          "ClassDiscs"       => array("Classes"),
          "ClassMarks"       => array("Classes"),
          "ClassAbsences"    => array("Classes"),
          "ClassStatus"      => array("Classes"),
          "ClassQuestions"      => array("Classes"),
          "ClassObservations"      => array("Classes"),

          "ClassDiscLessons" => array("Classes"),
          "ClassDiscNLessons" => array("Classes"),
          "ClassDiscWeights"      => array("Classes"),

          "ClassDiscContents" => array("Classes"),
          "ClassDiscAbsences" => array("Classes"),
          "ClassDiscAssessments"      => array("Classes"),
          "ClassDiscMarks"      => array("Classes"),
       ),
       "SubModulesVars" => array
       (
          "People" => array
          (
             "SqlObject" => "PeopleObject",
             "SqlClass" => "People",
             "SqlFile" => "People.php",
             "SqlHref" => TRUE,
             "SqlTable" => "People",
             "SqlDerivedData" => array("Name","Email"),
             "SqlFilter" => "#Name",
          ),
          "Clerks" => array
          (
             "SqlObject" => "ClerksObject",
             "SqlClass" => "Clerks",
             "SqlFile" => "Clerks.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Clerks",
             "SqlDerivedData" => array("Clerk","School"),
             "SqlFilter" => "#Name",
          ),
         "Units" => array
          (
             "SqlObject" => "UnitsObject",
             "SqlClass" => "Units",
             "SqlFile" => "Units.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Units",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
          ),
         "Departments" => array
          (
             "SqlObject" => "DepartmentsObject",
             "SqlClass" => "Departments",
             "SqlFile" => "Departments.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Departments",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
          ),
         "Protocols" => array
          (
             "SqlObject" => "ProtocolsObject",
             "SqlClass" => "Protocols",
             "SqlFile" => "Protocols.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Protocols",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
          ),
         "Places" => array
          (
             "SqlObject" => "PlacesObject",
             "SqlClass" => "Places",
             "SqlFile" => "Places.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Places",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
          ),
         "Consults" => array
          (
             "SqlObject" => "ConsultsObject",
             "SqlClass" => "Consults",
             "SqlFile" => "Consults.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Consults",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
          ),

          "Dates" => array
          (
             "SqlObject" => "DatesObject",
             "SqlClass" => "Dates",
             "SqlFile" => "Dates.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Dates",
             "SqlFilter" => "#Date",
             "SqlDerivedData" => array("Name","Date","SortKey"),
           ),
          "Periods" => array
          (
             "SqlObject" => "PeriodsObject",
             "SqlClass" => "Periods",
             "SqlFile" => "Periods.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Periods",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
           ),
          "Grade" => array
          (
             "SqlObject" => "GradeObject",
             "SqlClass" => "Grade",
             "SqlFile" => "Grade.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Grade",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name","Mode"),
           ),
          "GradePeriods" => array
          (
             "SqlObject" => "GradePeriodsObject",
             "SqlClass" => "GradePeriods",
             "SqlFile" => "Grade/Periods.php",
             "SqlHref" => TRUE,
             "SqlTable" => "GradePeriods",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name","NAssessments"),
           ),
          "GradeDiscs" => array
          (
             "SqlObject" => "GradeDiscsObject",
             "SqlClass" => "GradeDiscs",
             "SqlFile" => "Grade/Discs.php",
             "SqlHref" => TRUE,
             "SqlTable" => "GradeDiscs",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
          ),
          "GradeQuestionaries" => array
          (
             "SqlObject" => "GradeQuestionariesObject",
             "SqlClass" => "GradeQuestionaries",
             "SqlFile" => "Grade/Questionaries.php",
             "SqlHref" => TRUE,
             "SqlTable" => "GradeQuestionaries",
             "SqlFilter" => "#Number: #Name",
             "SqlDerivedData" => array("Number","Name"),
          ),
          "GradeQuestions" => array
          (
             "SqlObject" => "GradeQuestionsObject",
             "SqlClass" => "GradeQuestions",
             "SqlFile" => "Grade/Questions.php",
             "SqlHref" => TRUE,
             "SqlTable" => "GradeQuestions",
             "SqlFilter" => "#Number: #Name",
             "SqlDerivedData" => array("Number","Name"),
          ),

          "Schools" => array
          (
             "SqlObject" => "SchoolsObject",
             "SqlClass" => "Schools",
             "SqlFile" => "Schools.php",
             "SqlHref" => TRUE,
             "SqlWhere" => "Type='4'",
             "SqlTable" => "Places",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
           ),
          "Users" => array
          (
             "SqlObject" => "UsersObject",
             "SqlClass" => "Users",
             "SqlFile" => "Users.php",
             "SqlHref" => TRUE,
             "SqlTable" => "People",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name","Email"),
           ),
          "Students" => array
          (
             "SqlObject" => "StudentsObject",
             "SqlClass" => "Students",
             "SqlFile" => "Students.php",
             "SqlHref" => TRUE,
             "SqlTable" => "#School_Students",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
           ),
          "Matriculas" => array
          (
             "SqlObject" => "MatriculasObject",
             "SqlClass" => "Matriculas",
             "SqlFile" => "Matriculas.php",
             "SqlHref" => TRUE,
             "SqlTable" => "Matriculas",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
           ),
          "Classes" => array
          (
             "SqlObject" => "ClassesObject",
             "SqlClass" => "Classes",
             "SqlFile" => "Classes.php",
             "SqlHref" => TRUE,
             "SqlTable" => "#School_Classes",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
           ),
          "ClassDiscs" => array
          (
             "SqlObject" => "ClassDiscsObject",
             "SqlClass" => "ClassDiscs",
             "SqlFile" => "Class/Discs.php",
             "SqlHref" => TRUE,
             "SqlTable" => "ClassDiscs",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
           ),
          "ClassStudents" => array
          (
             "SqlObject" => "ClassStudentsObject",
             "SqlClass" => "ClassStudents",
             "SqlFile" => "Class/Students.php",
             "SqlHref" => TRUE,
             "SqlTable" => "#Period_ClassStudents",
             "SqlFilter" => "#Student",
             "SqlDerivedData" => array("Student"),
           ),
          "ClassMarks" => array
          (
             "SqlObject" => "ClassMarksObject",
             "SqlClass" => "ClassMarks",
             "SqlFile" => "Class/Marks.php",
             "SqlHref" => TRUE,
             "SqlTable" => "#Period_ClassMarks",
             "SqlFilter" => "#Student",
             "SqlDerivedData" => array("Student"),
           ),
          "ClassAbsences" => array
          (
             "SqlObject" => "ClassAbsencesObject",
             "SqlClass" => "ClassAbsences",
             "SqlFile" => "Class/Absences.php",
             "SqlHref" => TRUE,
             "SqlTable" => "#Period_ClassAbsences",
             "SqlFilter" => "#Student",
             "SqlDerivedData" => array("Student"),
           ),
          "ClassStatus" => array
          (
             "SqlObject" => "ClassStatusObject",
             "SqlClass" => "ClassStatus",
             "SqlFile" => "Class/Status.php",
             "SqlHref" => TRUE,
             "SqlTable" => "#Period_ClassStatus",
             "SqlFilter" => "#Student",
             "SqlDerivedData" => array("Student"),
          ),
          "ClassQuestions" => array
          (
             "SqlObject" => "ClassQuestionsObject",
             "SqlClass" => "ClassQuestions",
             "SqlFile" => "Class/Questions.php",
             "SqlHref" => TRUE,
             "SqlTable" => "#Period_ClassQuestions",
             "SqlFilter" => "#Student",
             "SqlDerivedData" => array("Student","Value"),
           ),
          "ClassObservations" => array
          (
             "SqlObject" => "ClassObservationsObject",
             "SqlClass" => "ClassObservations",
             "SqlFile" => "Class/Observations.php",
             "SqlHref" => TRUE,
             "SqlTable" => "#Period_ClassObservations",
             "SqlFilter" => "#Student",
             "SqlDerivedData" => array("Student","Value"),
           ),

          "ClassDiscLessons" => array
          (
             "SqlObject" => "ClassDiscLessonsObject",
             "SqlClass" => "ClassDiscLessons",
             "SqlFile" => "Class/Disc/Lessons.php",
             "SqlHref" => TRUE,
             "SqlTable" => "ClassDiscLessons",
             "SqlFilter" => "#Assessment, #Weight",
             "SqlDerivedData" => array("Assessment","Weight"),
           ),
          "ClassDiscNLessons" => array
          (
             "SqlObject" => "ClassDiscNLessonsObject",
             "SqlClass" => "ClassDiscNLessons",
             "SqlFile" => "Class/Disc/NLessons.php",
             "SqlHref" => TRUE,
             "SqlTable" => "ClassDiscNLessons",
             "SqlFilter" => "#Assessment, #Weight",
             "SqlDerivedData" => array("Assessment","Weight"),
           ),
          "ClassDiscWeights" => array
          (
             "SqlObject" => "ClassDiscWeightsObject",
             "SqlClass" => "ClassDiscWeights",
             "SqlFile" => "Class/Disc/Weights.php",
             "SqlHref" => TRUE,
             "SqlTable" => "ClassDiscWeights",
             "SqlFilter" => "#Assessment, #Weight",
             "SqlDerivedData" => array("Assessment","Weight"),
           ),

          "ClassDiscContents" => array
          (
             "SqlObject" => "ClassDiscContentsObject",
             "SqlClass" => "ClassDiscContents",
             "SqlFile" => "Class/Disc/Contents.php",
             "SqlHref" => TRUE,
             "SqlTable" => "ClassDiscContents",
             "SqlFilter" => "#Date",
             "SqlDerivedData" => array("Date"),
           ),
          "ClassDiscAbsences" => array
          (
             "SqlObject" => "ClassDiscAbsencesObject",
             "SqlClass" => "ClassDiscAbsences",
             "SqlFile" => "Class/Disc/Absences.php",
             "SqlHref" => TRUE,
             "SqlTable" => "ClassDiscNLessons",
             "SqlFilter" => "#Absence",
             "SqlDerivedData" => array("Absence"),
           ),
          "ClassDiscAssessments" => array
          (
             "SqlObject" => "ClassDiscAssessmentsObject",
             "SqlClass" => "ClassDiscAssessments",
             "SqlFile" => "Class/Disc/Assessments.php",
             "SqlHref" => TRUE,
             "SqlTable" => "ClassDiscAssessments",
             "SqlFilter" => "#Name",
             "SqlDerivedData" => array("Name"),
           ),
          "ClassDiscMarks" => array
          (
             "SqlObject" => "ClassDiscMarksObject",
             "SqlClass" => "ClassDiscMarks",
             "SqlFile" => "Class/Disc/Marks.php",
             "SqlHref" => TRUE,
             "SqlTable" => "ClassDiscMarks",
             "SqlFilter" => "#Mark",
             "SqlDerivedData" => array("Mark"),
           ),
        ),
       "PermissionVars" => array
       (
          "Vars" => array(),
          "People" => array
          (
          ),
          "Clerks" => array
          (
          ),
          "Units" => array
          (
          ),
          "Departments" => array
          (
          ),
          "Protocols" => array
          (
          ),
          "Places" => array
          (
          ),
          "Consults" => array
          (
          ),
          "Dates" => array
          (
          ),
          "Periods" => array
          (
          ),
          "Grade" => array
          (
          ),
          "GradePeriods" => array
          (
          ),
          "GradeDiscs" => array
          (
          ),
          "GradeQuestionaries" => array
          (
          ),
          "GradeQuestions" => array
          (
          ),

          "Matriculas" => array
          (
          ),
          "Schools" => array
          (
          ),
          "Users" => array
          (
          ),
          "Students" => array
          (
          ),
          "Classes" => array
          (
          ),
          "ClassDiscs" => array
          (
          ),
          "ClassStudents" => array
          (
          ),
          "ClassMarks" => array
          (
          ),
          "ClassAbsences" => array
          (
          ),
          "ClassStatus" => array
          (
          ),
          "ClassQuestions" => array
          (
          ),
          "ClassObservationss" => array
          (
          ),
       ),
    );


?>
