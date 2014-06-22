<?php

class ClassDiscsAccess extends ClassDiscsTeachers
{
    //*
    //*
    //* function CheckAccess2Dayly, Parameter list: $disc=array()
    //*
    //* Central Daylies Handler.
    //*

    function CheckAccess2Dayly($disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $res=FALSE;
        if (preg_match('/^(Admin|Secretary)$/',$this->ApplicationObj->Profile))
        {
            $res=TRUE;
        }
        elseif (preg_match('/^(Clerk|Coordinator)$/',$this->ApplicationObj->Profile))
        {
            $clerkperm=$this->ApplicationObj->ClerksObject->SelectHashesFromTable
            (
               "",
               array
               (
                  "School" => $this->ApplicationObj->School[ "ID" ],
                  "Clerk" => $this->ApplicationObj->LoginData[ "ID" ],
               ),
               array("ID")
            );

            if (!empty($clerkperm) || $this->LoginData[ "School" ]==$this->ApplicationObj->School[ "ID" ])
            {
                $res=TRUE;
            }
        }
        elseif (preg_match('/^(Teacher)$/',$this->ApplicationObj->Profile))
        {
            //Sofisticate this part.
            if (
                  $this->ApplicationObj->ClassesObject->AreWeTeacher($this->ApplicationObj->Class)
                  ||
                  $this->ApplicationObj->ClassesObject->AreWeTeacher($this->ApplicationObj->Disc)
               )
            {
                $res=TRUE;
            }
        }

        if (!$res) { print "Acesso negado..."; exit(); }
   }

    //*
    //* function CheckAccessEdit2Dayly, Parameter list: $disc=array()
    //*
    //* Returns dayly edit access. 0 for no edit, 1 for edit.
    //*

    function CheckAccessEdit2Dayly($disc=array())
    {
        if (empty($disc)) { $disc=$this->ApplicationObj->Disc; }

        $edit=0;
        if (preg_match('/^(Admin|Secretary|Clerk|Teacher)$/',$this->ApplicationObj->Profile))
        {
            $edit=1;
        }

        return $edit;
    }


    //*
    //* function MayAccess, Parameter list: $item
    //*
    //* Checks if we may access class discipline.
    //*

    function MayAccessDisc($item)
    {
        $res=FALSE;
        if ($this->ApplicationObj->Profile=="Admin") { $res=TRUE; }
        elseif ($this->ApplicationObj->Profile=="Clerk")
        {
            $res=TRUE;
        }
        elseif ($this->ApplicationObj->Profile=="Teacher")
        {
            if ($item[ "Teacher" ]==$this->ApplicationObj->LoginData[ "ID" ])
            {
                $res=TRUE;
            }
        }

        return $res;
    }

    //*
    //* function MayEdit, Parameter list: 
    //*
    //* Checks if we may edit class discipline data: marks, absences and total absence.
    //*

    function MayEditDisc($item)
    {
        $res=FALSE;
        if ($this->ApplicationObj->Profile=="Admin") { $res=TRUE; }
        elseif ($this->ApplicationObj->Profile=="Clerk")
        {
            $res=TRUE;
        }
        elseif ($this->ApplicationObj->Profile=="Teacher")
        {
            if ($item[ "Teacher" ]==$this->ApplicationObj->LoginData[ "ID" ])
            {
                $res=TRUE;
            }
        }

        return $res;
    }


    //*
    //* function MayEditDiscData, Parameter list: $item
    //*
    //* Checks if we may edit class discipline 'disc' data: statuses and marks weights.
    //*

    function MayEditDiscData($item)
    {
        $res=FALSE;
        if ($this->ApplicationObj->Profile=="Admin") { $res=TRUE; }
        elseif ($this->ApplicationObj->Profile=="Clerk")
        {
            $res=TRUE;
        }
        elseif ($this->ApplicationObj->Profile=="Teacher")
        {
        }

        return $res;
    }

    //*
    //* function MayDelete, Parameter list: $item
    //*
    //* Decides whether Grade is deletable.
    //*

    function MayDeleteDisc($item)
    {
        $res=FALSE;

        return $res;
    }

    //*
    //* function RegisterDiscAbsences, Parameter list: $disc
    //*
    //* Checks whether we should have register Absences.
    //*

    function RegisterDiscAbsences($disc)
    {
        $res=TRUE;
        if (
              !isset($disc[ "AbsencesType" ])
              ||
              intval($disc[ "AbsencesType" ])==$this->ApplicationObj->AbsencesNo
           ) { $res=FALSE; }

        return $res;
    }

    //*
    //* function RegisterDiscMarks, Parameter list: $disc
    //*
    //* Checks whether we should have register Marks.
    //*

    function RegisterDiscMarks($disc)
    {
        $res=TRUE;
        if (
              !isset($disc[ "AssessmentType" ])
              ||
              intval($disc[ "AssessmentType" ])==$this->ApplicationObj->MarksNo
              /* || */
              /* intval($disc[ "AssessmentType" ])==$this->ApplicationObj->Qualitative */
          ) { $res=FALSE; }

        return $res;
    }

    //*
    //* function RegisterDiscTotals, Parameter list: $disc
    //*
    //* Checks whether we should have register Totals.
    //*

    function RegisterDiscTotals($disc)
    {
        $res=$this->RegisterDiscMarks($disc);
        if ($res) { $res=$this->RegisterDiscAbsences($disc); }

        return $res;
    }
}

?>