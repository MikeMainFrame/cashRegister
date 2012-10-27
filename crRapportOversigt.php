<?php

  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  
  $fname = dirname(__FILE__) . '\\..\\xml\\crRoll.xml';
  $xname = dirname(__FILE__) . '\\..\\xml\\crRapportOversigt.xsl';
  
  $xsl = new DOMDocument;
  $xsl->load($xname);

  $proc = new XSLTProcessor;
  //$proc->setProfiling('profiling.txt');
  $proc->importStyleSheet($xsl);

  $xml = new DOMDocument;
  $xml->load($fname);
  
  $dom = $proc->transformToDoc($xml);
    
  echo $dom->saveXML(); 

?>