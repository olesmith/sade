<?php
array
(
   "Head"  => "Head.Matricula.Land.tex",
   "Tail"  => "Tail.tex",
   "SingularLatexDocs" => array
   (
      "Landscape" => FALSE,
      "Docs" =>  array
      (
         array
         (
            "Landscape" => TRUE,
            "Name" => "Ficha de Matrícula",
            "Name_UK" => "Enrollment Receit",
            //"AltHandler"  => "PrintMatricula",
            "Head"  => "Head.Matricula.Land.tex",
            "Glue"  => "Students/Matricula.tex",
            "Tail"  => "Tail.tex",
         )
      ),
   ),
   "PluralLatexDocs" => array
   (
      "Landscape" => FALSE,
      "Docs" =>  array
      (
         array
         (
            "Landscape" => TRUE,
            "Name" => "Fichas de Matrícula",
            "Name_UK" => "Enrollment Receits",
            "AltHandler"  => "",//"PrintPrograms",
            "Head"  => "Head.Matricula.Land.tex",
            "Glue"  => "Students/Matricula.tex",
            "Tail"  => "Tail.tex",
         )
      ),
   ),
);
?>
