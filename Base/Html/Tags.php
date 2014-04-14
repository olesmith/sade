<?php

global $NForms,$NFields;
$NForms=0;
$NFields=0;

class HtmlTags extends HtmlCSS
{
    //*
    //* sub HtmlTag, Parameter list: $tag,$contents,$options=array()
    //*
    //* Returns only opening html tag <$tag options> and appends $contents.
    //*
    //*

    function HtmlTag($tag,$contents="",$options=array())
    {
        if (is_array($contents))
        {
            $contents=join("\n",$contents);
        }

        return 
            "<".strtoupper($tag).
            $this->Hash2Options($options).">".
            $contents;
    }
    //*
    //* sub HtmlCloseTag, Parameter list: $tag
    //*
    //* Returns only closing html tag </$tag>
    //*
    //*

    function HtmlCloseTag($tag)
    {
        return "</".strtoupper($tag).">";
    }

    //*
    //* sub HtmlTags, Parameter list: $tag,$contents,$options=array()
    //*
    //* Returns matching html tags <$tag>$contents</$tag>
    //*
    //*

    function HtmlTags($tag,$contents,$options=array())
    {
        $tag=strtoupper($tag);
        if (is_array($contents)) { $contents="\n".join("\n",$contents); }

        return 
            "<".$tag.
            $this->Hash2Options($options).">".
            $contents.
            "</".$tag.">";
    }

    //*
    //* sub HtmlTagList, Parameter list: $list,$tag,$options=array()
    //*
    //* Returns matching html tags of list $list.
    //*
    //*

    function HtmlTagList($list,$tag,$options=array())
    {
        foreach ($list as $id => $item)
        {
            $list[ $id ]=$this->HtmlTags($tag,$item,$options);
        }

        return $list;
    }

    //*
    //* sub Hash2Options, Parameter list: $options
    //*
    //* Converts an associative array to an options string.
    //* As in HTML entities OPTION1='value1',... .
    //*
    //*

    function Hash2Options($options)
    {
        $optionstring="";
        foreach ($options as $option => $value)
        {
            if (preg_match('/^\s+$/',$option))
            {
                $optionstring.=$value;
            }
            elseif (preg_match('/^\d+$/',$option))
            {
                $optionstring.=
                    " ".strtolower($option)."=\"".
                    preg_replace('/"/',"",$value).
                    "\"";
            }
            elseif (empty($value))
            {
                //if ($value=="0") { var_dump($value); }
                //else { var_dump("no$value"); }

                if ($value=="0")
                {
                    $optionstring.=
                        " ".strtolower($option)."=\"".
                        preg_replace('/"/',"",$value).
                        "\"";
                }
                elseif (!preg_match('/^(CLASS|TITLE)$/i',$option))
                {
                    $optionstring.=" ".strtolower($option);
                }
            }
            else
            {
                $optionstring.=
                    " ".strtolower($option)."=\"".
                    preg_replace('/"/',"",$value).
                    "\"";
            }
         }

        return $optionstring; 
    }

    //*
    //* sub Hash2Args, Parameter list: $options
    //*
    //* Converts an associative array to an options string.
    //* As in URLs: OPTIONS1='value1'&OPTIONS2=,... .
    //*
    //*

    function Hash2Args_deprecated($options)
    {
        $roptions=array();
        foreach ($options as $option => $value)
        {
            if (preg_match('/\s/',$value)) { $value="'".$value."'"; }
            array_push($roptions,$option."=".$value);
        }

        if (count($roptions)>0) { array_push($roptions,""); }
        
        return join("&",$roptions); 
    }

    //*
    //* function IMG , Parameter list: $list,$src
    //*
    //* Creates Image, IMG
    //* 
    //*

    function IMG($src,$alttext="",$height=0,$width=0,$options=array("BORDER" => "0"))
    {
        if (!preg_match('/^(http|\/|\.)/',$src) && !file_exists($src))
        {
            $src=$this->FindIconsPath()."/".$src;
        }

        $options[ "SRC" ]=$src;
        $options[ "ALT" ]=$alttext;
        $options[ "BORDER" ]=0;

        if ($height>0) { $options[ "HEIGHT" ]=$height."px"; }
        if ($width>0)  { $options[ "WIDTH" ]=$width."px"; }


        return $this->HtmlTag("IMG","",$options);
    }


    //*
    //* function H, Parameter list: $n,$text,$options=array()
    //*
    //* Creates a Header N tag (H2,H3,...).
    //* 
    //*

    function H($n,$text,$options=array())
    {
        if ($this->LatexMode)
        {
            $sizes=array("","Huge","huge","LARGE","Large","large");
            return "\\".$sizes[ $n ]."{\\textbf{".$text."}}\n\n";
        }
        else
        {
            return $this->HtmlTags("H".$n,$text,$options);;
        }
    }

    //*
    //* sub DIV, Parameter list: $name,$contents,$options=array()
    //*
    //* Returns matching html tags <$name>$contents</$name>
    //*
    //*

    function DIV($contents,$options=array())
    {
        return $this->HtmlTags("DIV",$contents,$options);
    }

    //*
    //* sub SPAN, Parameter list: $name,$contents,$options=array()
    //*
    //* Returns matching html tags <$name>$contents</$name>
    //*
    //*

    function SPAN($contents,$options=array())
    {
        if ($this->LatexMode)
        {
            return $contents;
        }
        else
        {
            return $this->HtmlTags("SPAN",$contents,$options);
        }
    }

    //*
    //* sub FONT, Parameter list: $name,$contents,$options=array()
    //*
    //* Returns matching html tags <$name>$contents</$name>
    //*
    //*

    function FONT($contents,$options=array())
    {
        return $this->HtmlTags("FONT",$contents,$options);
    }

    //*
    //* sub CENTER, Parameter list: $contents,$options=array()
    //*
    //* Creates Centered DIV
    //*
    //*

    function CENTER($contents,$options=array())
    {
        if ($this->LatexMode)
        {
            return "\\begin{center}\n".$contents."\n\\end{center}";
        }
        else
        {
            $options[ "STYLE" ]="text-align: center;";
            return $this->HtmlTags("DIV",$contents,$options);
        }
    }

    //*
    //* sub RIGHT, Parameter list: $contents,$options=array()
    //*
    //* Creates right justified DIV.
    //*
    //*

    function RIGHT($contents,$options=array())
    {
        if ($this->LatexMode)
        {
            return "\\begin{flushright}\n".$contents."\n\\end{flushright}";
        }
        else
        {
            $options[ "STYLE" ]="text-align: right;";
            return $this->HtmlTags("DIV",$contents,$options);
        }
    }

    //*
    //* sub LEFT, Parameter list: $contents,$options=array()
    //*
    //* Creates left justified DIV.
    //*
    //*

    function LEFT($contents,$options=array())
    {
        if ($this->LatexMode)
        {
            return "\\begin{flushright}\n".$contents."\n\\end{flushright}";
        }
        else
        {
            $options[ "STYLE" ]="text-align: left;";
            return $this->HtmlTags("DIV",$contents,$options);
        }
    }

    //*
    //* sub ALIGN, Parameter list: $contents,$align="c",$options=array()
    //*
    //* Creates aligned DIV.
    //*
    //*

    function ALIGN($contents,$align="c",$options=array())
    {
            if ($align=="c") { $align="CENTER"; }
        elseif ($align=="r") { $align="RIGHT"; }
        elseif ($align=="l") { $align="LEFT"; }

        return $this->$align($contents,$options); 
   }

   //*
    //* sub A, Parameter list: $name,$contents,$options=array()
    //*
    //* Returns matching html tags <$name>$contents</$name>
    //*
    //*

    function A($href,$contents="",$options=array())
    {
        if ($this->LatexMode)
        {
            return $contents;
        }
        else
        {
            $options[ "HREF" ]=$href;
            if ($contents=="") { $contents=$href; }
            return $this->HtmlTags("A",$contents,$options);
        }
    }


    function BR()
    {
        if ($this->LatexMode)
        {
            return "\n\n";
        }
        else
        {
            return "<BR>";
        }
    }

    function B($contents="",$options=array())
    {
        if (is_array($contents))
        {
            $rcontents=array();
            foreach ($contents as $id => $rcontent)
            {
                $rcontents[ $id ]=$this->B($rcontent,$options);
            }

            return $rcontents;
        }

        if ($this->LatexMode)
        {
            return "\\textbf{".$contents."}";
        }
        else
        {
            $options[ "Class" ]='Bold';
            return $this->HtmlTags("SPAN",$contents,$options);
        }
    }

    function I($contents="",$options=array())
    {
        if ($this->LatexMode)
        {
            return "\\textit{".$contents."}";
        }
        else
        {
            $options[ "Class" ]='Italic';
            return $this->HtmlTags("SPAN",$contents,$options);
        }
    }

    function U($contents="",$options=array())
    {
        if ($this->LatexMode)
        {
            return "\\underline{".$contents."}";
        }
        else
        {
            $options[ "Class" ]='Underline';
            return $this->HtmlTags("SPAN",$contents,$options);
        }
    }

    function TextColor($color,$contents="",$options=array())
    {
        if ($this->LatexMode)
        {
            return "\\textcolor{".$color."}{".$contents."}";
        }
        else
        {
            if (is_array($color))
            {
                $options[ "STYLE" ]="color:rgb(".join(",",$color).")";
            }
            else
            {
                $options[ "STYLE" ]="color:".$color;
            }

            return $this->HtmlTags("FONT",$contents,$options);
        }
    }

    function SUB($text,$stext,$options=array())
    {
        if ($this->LatexMode)
        {
            $stext=preg_replace('/,/',"}^{",$stext);
            return $text."\$_{".$stext."}\$";
        }
        else
        {
            return $text.$this->HtmlTags("SUB",$stext,$options);
        }
    }

}


?>