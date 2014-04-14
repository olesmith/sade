<?php

global $ClassList;
array_push($ClassList,"Zip");


class Zip extends Base
{
    //*
    //* Variables of Zip class:
    //*


    //*
    //* function Zip, Parameter list: 
    //*
    //* Constructor.
    //*

    function Zip()
    {
    }

    //*
    //* function InitZip, Parameter list: $hash=array()
    //*
    //* Initializer.
    //*

    function InitZip($hash=array())
    {
    }

    //*
    //* function UnZip, Parameter list: $file
    //*
    //* Unzips zip file contents.
    //*

    function UnZip($file,$outpath)
    {
        $file=realpath($file);
        $zip = zip_open($file);

        $files=array();
        if (is_resource($zip))
        {
           while ($zip_entry = zip_read($zip))
           {
               $name=zip_entry_name($zip_entry);
               if (zip_entry_open($zip, $zip_entry, "r"))
               {
                   $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

                   $outname=$outpath."/".$name;
                   $this->MyWriteFile($outname,$buf);

                   array_push($files,$outname);
                 
                   zip_entry_close($zip_entry);
               }
           }

           zip_close($zip);
           $msg="Arquivo ZIP carregado com êxito!";
           $this->HtmlStatus.=$msg."<BR>";
            print $this->H(5,$msg);
        }
        else
        {
            $msg="Erro lendo arquivo ZIP - formato incorreto?";
            $this->HtmlStatus.=$msg."<BR>";
            print $this->H(4,$msg);
        }

        return $files;
    }

    //*
    //* function ZipTree, Parameter list: $path
    //*
    //* Zip tree below path and return to browser as zip.
    //*

    function ZipTree($path)
    {
        $mtime=$this->MTime2FName().".zip";

        //Name when downloaded
        $outname=$this->GenExtraPathInfo();
        $outname=preg_replace('/^\//',"",$outname);
        $outname=preg_replace('/\//',"-",$outname);


        $outname=$this->ModuleName."-".$outname."-".$mtime;

        //Tmp file name
        $outfile="/tmp/".$mtime;

        //Create new archive
        $zip = new ZipArchive();
        if ($zip->open($outfile,ZipArchive::CREATE))
        {
            $dirs=$this->TreeSubdirs($this->GetUploadPath());
            foreach ($dirs as $id => $dir)
            {
                $zip->addEmptyDir($dir); 
            }

            $items=array();

            $files=$this->TreeFiles($this->GetUploadPath());
            foreach ($files as $id => $file)
            {
                $path=dirname($file);
                $fname=basename($file);

                $zip->addFile($file); 
            }

            $zip->close();
            $contents=$this->MyReadFile($outfile);
            $this->SendDocHeader("zip",$outname);
            print join("",$this->MyReadFile($outfile));

            unlink($outfile);
            exit();
        }
    }

}
?>