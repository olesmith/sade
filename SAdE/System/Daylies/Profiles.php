<?php
array
(
   'Access' => array
   (
      'Public' => 0,
      'Person' => 0,
      'Admin' => 1,

      "Clerk" => 1,
      "Teacher"     => 1,
      "Secretary" => 1,
      "Coordinator" => 1,
   ),
   'Actions' => array
   (
      'Dayly' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
      "Coordinator" => 1,
      ),
      'Dayly1' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
      "Coordinator" => 1,
      ),
      'DaylyTeacher' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 0,
         "Teacher"     => 1,
         "Secretary" => 0,
      "Coordinator" => 1,
      ),
      'DaylyCalendar' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 0,
      ),
      'DaylyContents' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'DaylyContentsDates' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 0,
     ),
      'DaylyContentsPrint' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'DaylyStudents' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 0,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'DaylyAbsences' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'DaylyAbsencesPrint' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'DaylyAbsencesStats' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'DaylyAbsencesMonths' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
      "Coordinator" => 1,
      ),
      'DaylyAbsencesSemesters' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'DaylyAssessments' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'DaylyMarks' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
      "Coordinator" => 1,
      ),
      'DaylyMarksPrint' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
      'DaylyPrints' => array
      (
         'Public' => 0,
         'Person' => 0,
         'Admin' => 1,

         "Clerk" => 1,
         "Teacher"     => 1,
         "Secretary" => 1,
         "Coordinator" => 1,
      ),
    ),
   'Menues' => array
   (
      'Singular' => array
      (
         'Public' => array
         (
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
            'DaylyContentsDates' => 1,
            'DaylyContents' => 1,
            'DaylyAbsences' => 1,
            'DaylyAssessments' => 1,
            'DaylyMarks' => 1,
         ),
         'Clerk' => array
         (
            'DaylyContentsDates' => 1,
            'DaylyContents' => 1,
            'DaylyAbsences' => 1,
            'DaylyAssessments' => 1,
            'DaylyMarks' => 1,
         ),
         'Teacher' => array
         (
            'DaylyContentsDates' => 1,
            'DaylyContents' => 1,
            'DaylyAbsences' => 1,
            'DaylyAssessments' => 1,
            'DaylyMarks' => 1,
         ),
         'Secretary' => array
         (
            'DaylyContentsDates' => 1,
            'DaylyContents' => 1,
            'DaylyAbsences' => 1,
            'DaylyAssessments' => 1,
            'DaylyMarks' => 1,
         ),
         'Coordinator' => array
         (
            'DaylyContentsDates' => 1,
            'DaylyContents' => 1,
            'DaylyAbsences' => 1,
            'DaylyAssessments' => 1,
            'DaylyMarks' => 1,
         ),
      ),
      'Plural' => array
      (
         'Public' => array
         (
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
         ),
      ),
      'SingularPlural' => array
      (
         'Public' => array
         (
            'Search' => 1,
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
            'Dayly1' => 1,
            'DaylyCalendar' => 1,
            'DaylyStudents' => 1,
            //'DaylyPrints' => 1,
         ),
         'Clerk' => array
         (
            'Dayly1' => 1,
            'DaylyCalendar' => 1,
            'DaylyStudents' => 1,
            //'DaylyPrints' => 1,
         ),
         'Teacher' => array
         (
            'Dayly1' => 1,
            'DaylyCalendar' => 1,
            'DaylyStudents' => 1,
            //'DaylyPrints' => 1,
         ),
         'Secretary' => array
         (
            'Dayly1' => 1,
            'DaylyCalendar' => 1,
            'DaylyStudents' => 1,
            //'DaylyPrints' => 1,
         ),
         'Coordinator' => array
         (
            'Dayly1' => 1,
            'DaylyCalendar' => 1,
            'DaylyStudents' => 1,
            //'DaylyPrints' => 1,
         ),
      ),
      'ActionsPlural' => array
      (
         'Public' => array
         (
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
         ),
      ),
      'ActionsSingular' => array
      (
         'Public' => array
         (
         ),
         'Person' => array
         (
         ),
         'Admin' => array
         (
         ),
         'Clerk' => array
         (
         ),
         'Teacher' => array
         (
         ),
         'Secretary' => array
         (
         ),
      ),
   ),
);
?>