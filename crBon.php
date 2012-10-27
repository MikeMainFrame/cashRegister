<?php
/***
*  just translate one bon to a print friendly layout :: dec 2011
*/
  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  
  $fname = dirname(__FILE__) . '\\..\\xml\\crRoll.xml';
  $xname = dirname(__FILE__) . '\\..\\xml\\crBon.xsl';
  
  $xsl = new DOMDocument;
  $xsl->load($xname);

  $proc = new XSLTProcessor;
  $proc->importStyleSheet($xsl);
  $proc->setParameter('http://www.w3.org/1999/xhtml', 'sequenceno', '#' . $_GET['x']);  

  $xml = new DOMDocument;
  $xml->load($fname);
  
  $dom = $proc->transformToDoc($xml);
    
  echo $dom->saveXML(); 

  
?>