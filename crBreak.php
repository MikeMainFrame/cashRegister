<?php
/***
*  it is expected, that BREAK node is present
*/
  error_reporting(E_ALL);
  ini_set("display_errors", 1);  
  
  $max = "nf";
  $fname = dirname(__FILE__) . '\\..\\xml\\crRoll.xml';  
  $dom = new DOMDocument; 
  $dom->load($fname, LIBXML_DTDLOAD|LIBXML_DTDATTR);
  $root = $dom->documentElement;                     
  
  $xpath = new DOMXpath($dom);  
  $xpath->registerNamespace('cr', "http://www.w3.org/1999/xhtml"); 
  $candidateNodes = $xpath->query("//cr:break");  
  
  foreach ($candidateNodes as $child) {
    $max = $child->getAttribute('tstamp');
  }
  
  $postdata = file_get_contents("php://input"); 
  $row = $dom->createDocumentFragment();             // load the client fragment ...
  $row->appendXML($postdata);
  $root->appendChild($row);
  $dom->save($fname);                                // At this point we save it - casted !
  
  echo $max;
  
  $candidateNodes = $xpath->query("cr:break");  
  
  foreach ($candidateNodes as $child) {    
    $child->parentNode->removeChild($child);
  }  
  
  $candidateNodes = $xpath->query("cr:transaction");  
  
  foreach ($candidateNodes as $child) {    
    if ($child->getAttribute('tstamp') < $max) $child->parentNode->removeChild($child);
  }  
  
  $dom->save(dirname(__FILE__) . '\\..\\xml\\cr' . $max . '.xml');  
  
?>