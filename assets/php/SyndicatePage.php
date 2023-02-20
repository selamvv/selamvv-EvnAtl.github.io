<?php
/*
Syndicate Page
Version 1.0
February 3rd, 2023
Mapping with Atlas Substack Archive PostS
https://evanatlas.substack.com/archive
*/

/* Two values that may require changing: */

$SyndicationBegin = '<!--BEGIN_SYNDICATION-->';
$SyndicationEnd = '<!--END_SYNDICATION-->';

/* End of customization section */

$synbegin = preg_quote($SyndicationBegin,'/s');
$synend = preg_quote($SyndicationEnd,'.*$/s');
$syndication_page = '';
if( ! empty($_GET['source']) ) { $syndication_page = file_get_contents($_GET['source']); }
if( ! ( preg_match("/$synbegin/",$syndication_page) and preg_match("/$synend/",$syndication_page) ) )
{
   echo "document.writeln('Either the web page was missing codes or they were incorrect. Or there was no web page.')";
   exit;
}
$syndication_page = preg_replace("/^.*$synbegin/s",'',$syndication_page);
$syndication_page = preg_replace("/$synend.*$/s",'',$syndication_page);
foreach( preg_split('/[\r\n]+/',$syndication_page) as $line )
{
   $js = str_replace("\\","\\\\",$line);
   $js = str_replace("'","\\'",$js);
   $js = str_replace("<!--","<'+'!--",$js);
   $js = str_replace("-->","--'+'>",$js);
   $js = preg_replace('/(scr)(ipt)/i','$1\'+\'$2',$js);
   $js = preg_replace('/(win)(dow)/i','$1\'+\'$2',$js);
   $js = preg_replace('/(doc)(ument)/i','$1\'+\'$2',$js);
   $js = preg_replace('/(text)(area)/i','$1\'+\'$2',$js);
   $js = preg_replace('/(fo)(rm)/i','$1\'+\'$2',$js);
   echo "document.writeln('$js');\n";
}
?>
