<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Rapport</title>
<style>
 @media screen {
    html, body, 
    table, tr,
    th, td      { margin: 0;  padding: 0;  color: #000 ;  font-family: verdana, sans-serif ; font-size: 11px ; padding: 5px }
    th          { font-weight: 100; color: #000 ; background-color: #aaa }    
    td,
    th          { border: solid 1px #bbb; }
    table       { border-collapse:collapse ; border:solid 1px #fff ; background-color: #fff}}
    body        { background-color:   #fff ; background: #fff }
    thead       { text-align: center}
    tbody       { text-align:right }
    body        { background-color: #000 }
    }
  @media print {
    table       { border-collapse:collapse ; border:solid thin #fff}
    th, td      { margin: 0;  padding: 0;  color: #000 ;  font-family: verdana, sans-serif ; font-size: 8pt ; padding: 4pt }
    td,
    th          { border-bottom: solid thin #ccc }
    thead       { text-align: center}
    tbody       { text-align:right }
   
  }
</style>
</head>
<body>
<table>   
<thead>
<tr><th rowspan="2">Nr</th><th rowspan="2">Bon</th><th rowspan="2">Tidsstempel</th><th colspan="4">Summa</th></tr>
<tr><th>Dankort</th><th>Kontant</th><th>Kredit</th><th>Check</th></tr>
</thead>
<tbody>

<?php
  error_reporting(E_ALL);
  ini_set("display_errors", 1);  
  
  $sum = array("kontant" => 0, "voucher" => 0, "kredit" => 0, "dankort" => 0);
  $summa = 0; $pType = ""; $sequenceno = ""; $tstamp = ""; $noBon = 0; $html = ""; 
  
  $fname = dirname(__FILE__) . '\\..\\xml\\crRoll.xml';  
  $dom = new DOMDocument; 
  $dom->load($fname, LIBXML_DTDLOAD|LIBXML_DTDATTR);
  $xpath = new DOMXpath($dom);  
  $xpath->registerNamespace('cr', "http://www.w3.org/1999/xhtml"); 
  $candidateNodes = $xpath->query("//cr:*");  
  
  foreach ($candidateNodes as $child) {  
    if ($child->nodeName == 'break') {
      if ($summa > 0) detail($pType,$summa);            
      echo "<tr><th colspan='3'>" .  $child->getAttribute('tstamp') . "</th><th>" 
      . number_format($sum["dankort"] , 2, ',', '.') . "</th><th>" 
      . number_format($sum["kontant"] , 2, ',', '.') . " </th><th> " 
      . number_format($sum["kredit"] , 2, ',', '.') . "</th><th>" 
      . number_format($sum["voucher"] , 2, ',', '.') . "</th></tr>";
      
      $sum["kontant"] = 0; $sum["voucher"] = 0; $sum["kredit"] = 0; $sum["dankort"] = 0; $noBon = 0; $summa = 0;
    }    
    if ($child->nodeName == 'transaction') {
      if ($summa > 0) detail($pType,$summa);
      $noBon++;         
      echo "<tr><th>" . $noBon . "</th><td>" . $child->getAttribute('sequenceno') . "</td><td>" . $child->getAttribute('tstamp') . "</td>";      
      $summa = 0;
      $pType = $child->getAttribute('paytype');        
     }   
    if ($child->nodeName == 'row') {
      $temp = ($child->getAttribute('price') * $child->getAttribute('quantum')); 
      $summa = $summa + $temp;        
      switch ($pType) {
        case '118':     $sum["dankort"] = $sum["dankort"] + $temp;  break;          
        case 'dankort': $sum["dankort"] = $sum["dankort"] + $temp;  break;          
        case '119':     $sum["kontant"] = $sum["kontant"] + $temp;  break;          
        case 'cash':    $sum["kontant"] = $sum["kontant"] + $temp;  break;          
        case '120':     $sum["kredit"] = $sum["kredit"] + $temp;  break;          
        case 'credit':  $sum["kredit"] = $sum["kredit"] + $temp;  break;          
        case 'check':   $sum["voucher"] = $sum["voucher"] + $temp;  break;          
        default;           break;          
      }    
    }    
  }
  if ($summa > 0) detail($pType,$summa);      
  
  echo "<tr><th colspan='3'>dummy</th><th>"   
      . number_format($sum["dankort"] , 2, ',', '.') . "</th><th>" 
      . number_format($sum["kontant"] , 2, ',', '.') . " </th><th> " 
      . number_format($sum["kredit"] , 2, ',', '.') . "</th><th>" 
      . number_format($sum["voucher"] , 2, ',', '.') . "</th></tr>";  

function detail($pType, $summa) {
  $display99 = number_format($summa, 2, ',', '.');
  switch ($pType) {
    case '118':     echo "<td>" . $display99 . "</td><td></td><td></td><td></td></tr>"; break;
    case 'dankort': echo "<td>" . $display99 . "</td><td></td><td></td><td></td></tr>"; break;
    case '119':     echo "<td></td><td>" . $display99 . "</td><td></td><td></td></tr>"; break;
    case 'cash':    echo "<td></td><td>" . $display99 . "</td><td></td><td></td></tr>"; break;
    case '120':     echo "<td></td><td></td><td>" . $display99 . "</td><td></td></tr>"; break;
    case 'credit':  echo "<td></td><td></td><td>" . $display99 . "</td><td></td></tr>"; break;          
    case 'check':   echo "<td></td><td></td><td></td><td>" . $display99 . "</td></tr>"; break;
    default: break;
  }
}  
?>
</body></table></body></html>