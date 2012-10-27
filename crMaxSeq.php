<?php  
  
  $fname = dirname(__FILE__) . '\\..\\xml\\crRoll.xml';  
  $dom = new DOMDocument; 
  $dom->load($fname, LIBXML_DTDLOAD|LIBXML_DTDATTR);  
  $xpath = new DOMXpath($dom);  
  $xpath->registerNamespace('cr', "http://www.w3.org/1999/xhtml"); 
  $candidateNodes = $xpath->query("//cr:transaction");  
  $maxSequenceNo = "#0000000";
  foreach ($candidateNodes as $child) {
    if ($child->getAttribute('sequenceno') > $maxSequenceNo) $maxSequenceNo = $child->getAttribute('sequenceno');
  }
  echo $maxSequenceNo;
?>