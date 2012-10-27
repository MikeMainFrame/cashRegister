<?php

  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  
  $fname = dirname(__FILE__) . '\\..\\xml\\cr' . $_GET["x"] . '.xml';
  $xname = dirname(__FILE__) . '\\crRapport.xsl';
  
  $xsl = new DOMDocument;
  $xsl->load($xname);

  $proc = new XSLTProcessor;
  //$proc->setProfiling('profiling.txt');
  $proc->importStyleSheet($xsl);

  $xml = new DOMDocument;
  $xml->load($fname);
  
  $primo = saldoPrimo($_GET["x"]);
  
  //foreach ($primo as $key => $value) {
  //  $proc->setParameter('', $key, $value);
  //}
  $proc->setParameter('', $primo);
  $dom = $proc->transformToDoc($xml);
    
  echo $dom->saveXML(); 
  
  
  
/***
*  timestamp check - if included, call sumD
*/    
function saldoPrimo($aTs) {
  
  $fname = dirname(__FILE__) . '\\..\\xml\\crRoll.xml';  
  $dom = new DOMDocument; 
  $dom->load($fname, LIBXML_DTDLOAD|LIBXML_DTDATTR);
  $xpath = new DOMXpath($dom);  
  $xpath->registerNamespace('cr', "http://www.w3.org/1999/xhtml"); 
  $candidateNodes = $xpath->query("//cr:transaction");  
  $sumA = array("dankort" => 0, "cash" => 0, "kredit" => 0, "credit" => 0, "giftcard" => 0, "voucher" => 0, "check" => 0,);
     
  foreach ($candidateNodes as $child) {  
    $ts = $child->getAttribute('tstamp');
    if ($ts < $aTs) {
      if ($child->getAttribute('cash') > 0) $sumA["cash"] = $sumA["cash"] + $child->getAttribute('cash');   
      if ($child->getAttribute('dankort') > 0) $sumA["dankort"] = $sumA["dankort"] + $child->getAttribute('dankort');  
      if ($child->getAttribute('credit') > 0) $sumA["credit"] = $sumA["credit"] + $child->getAttribute('credit');  
      if ($child->getAttribute('giftcard') > 0) $sumA["giftcard"] = $sumA["giftcard"] + $child->getAttribute('giftcard');  
      if ($child->getAttribute('voucher') > 0) $sumA["voucher"] = $sumA["voucher"] + $child->getAttribute('voucher');  
      if ($child->getAttribute('check') > 0) $sumA["check"] = $sumA["check"] + $child->getAttribute('check');  
    }  
    
  }  
  return($sumA);
  
}
?>