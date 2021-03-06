<?php

class FileFields extends TimeFields
{
    //*
    //* Variables of Fields class:
    //*

    //*
    //* Returns full (relative) upload path: UploadPath/Module.
    //*

    function GetUploadPath()
    {
        $path=preg_replace('/#Module/',$this->ModuleName,$this->UploadPath);

        if ($path=="") { return; }

        $path=$this->FilterObject($path);

        $comps=preg_split('/\/+/',$path);
        if (!preg_grep('/'.$this->ModuleName.'/',$comps))
        {
            array_push($comps,$this->ModuleName);
        }


        $path="";
        for ($n=0;$n<count($comps);$n++)
        {
            if ($path!="")
            {
                $path.="/";
            }

            $path.=$comps[$n];

            if (!is_dir($path))
            {
                var_dump("Creating: ".$path);
                mkdir($path);
            }
            
        }

        touch($path."/index.php");
        return $path;
    }

    //*
    //* Returns full (relative) name of uploaded file pertaining to $data.
    //*

    function GetUploadedFileName($data,$item,$ext)
    {
        $uploadpath=$this->GetUploadPath();

        //Make sure we have an index.php, so no-one may list the files
        $index=$uploadpath."/index.php";
        if (!file_exists($index))
        {
            if (is_writable($index))
            {

                $this->MyWriteFile($index,array());
            }
        }

        $uploadpath.="/";
        if ($this->UploadFilesHidden)
        {
            $uploadpath.=".";
        }

        //Make files hidden
        return $uploadpath.$data."_".$item[ "ID" ].".".$ext;
    }

    //*
    //* Create file field decorator, being a link to download the file
    //*

    function FileFieldDecorator($data,$item,$plural=FALSE,$edit=0)
    {
        $value="";
        if (isset($item[ $data ])) { $value=$item[ $data ]; }
        $extensions=$this->ItemData[ $data ][ "Extensions" ];
        if (!is_array($extensions)) { $extensions=array(); }

        //Show allowed extensions
        $rvalue="";
        if (!$plural && $edit==1 && count($extensions)>0)
        {
            $rvalue=
                "<B>".
                $this->GetMessage($this->ItemDataMessages,"PermittedFileTypes").
                ":</B> ".
                join(", ",$extensions);
        }

        //If file has been uploaded, print download link and date uploaded
        if ($value!="")
        {
            $options=array("CLASS" => "uploadmsg");
            $filetime="";
            if (file_exists($value))
            {
                $filetime=$this->TimeStamp2Text(filectime($value));

                $html="";
                if (preg_match('/\.(jpg|png)$/',$value))
                {
                    $html="<IMG SRC='".$value."' HEIGHT='50'>";
                }
                else
                {
                    $html=$this->GetMessage($this->ItemDataMessages,"VerifyIntegrity");
                }
                

                $args=$this->Query2Hash();
                $args=$this->Hidden2Hash($args);
                $this->AddCommonArgs2Hash($args);

                $args[ "ModuleName" ]=$this->ModuleName;
                $args[ "Action" ]="Download";
                $args[ "ID" ]=$item[ "ID" ];
                $args[ "Data" ]=$data;
                $rvalue.=" ".$this->A
                (
                   "?".$this->Hash2Query($args),
                   $html,
                   $options
                 );

                if ($edit==1)
                {
                    $rvalue.=": ".$filetime;
                }
            }
            else
            {
                $rvalue.="- '$value' non-existent";
            }
        }

        return $rvalue."\n";
    }

    //*
}

?>