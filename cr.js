   var datetime = {"day" : function () { return(timestamp().substr(0,8));}  ,"stamp" : function () {return (timestamp()); }};   
   var now = datetime.stamp();
   var zCash = document.getElementById("zCash");
   var zNow = document.getElementById("zNow");
   var zSumRow = document.getElementById("zSumRow");
   var zAmount = document.getElementById("zAmount");
   var zProduct = document.getElementById("zProduct");
   var zPrice = document.getElementById("zPrice");
   var zQuantum = document.getElementById("zQuantum");   
   var zSequenceNo = document.getElementById("zSequenceNo");   
   var zDankort = document.getElementById("zDankort");   
   
   zProduct.value = "*";
   zProduct.focus();
   
   zNow.innerHTML = formatT(now);
   
   getBonNumber();
   
   zQuantum.addEventListener('click', deleteNode, false);
   zQuantum.addEventListener('keypress', function (e) { if (e.keyCode === 13) sumLine();} , false);
   document.addEventListener('keyup', function (e) { if (e.keyCode === 118) makeSlot();} , false);
   
   //zQuantum.onkeypress = function (e) { if (e.keyCode === 13) sumLine(); }   // Enter key
   //document.onkeyup = function (e) { if (e.keyCode === 118) makeSlot(); } 
   
/***
* line price box - correct , to .
*/   
function formatT(base) {
  var m = "   JANFEBMARAPRMAJJUNJULAUGSEPOCTNOVDEC";
  var i = parseInt(base.substr(4,2) * 3);
  return base.substr(6,2) + m.substr(i, 3) + base.substr(0,4) + " " + base.substr(8,2) + ":"  + base.substr(10,2) + ":"  + base.substr(12,2);
}
function unifyAmount(base) {    
  var temp = base.value.split(",");
  if (temp.length > 0) base.value = temp.join(".");
  var slam = new Number(base.value);
  base.value = slam.toFixed(2);
}
/***
* sum on line with fixed deci(2)
*/
function sumLine() {
  var temp = new Number(zQuantum.value * zPrice.value);
  zSumRow.innerHTML = temp.toFixed(2);  
  if (zQuantum.value !== 0) insertLine();        
  zQuantum.value = 0;
}
/***
* intercept command line and show it ...
*/
function insertLine() {
  
  var divMain = document.createElement("div");
      divMain.className = "visibleRow";
      //divMain.setAttribute("onclick", "deleteNode(this)"); 
      divMain.addEventListener('click', deleteNode, false);
      

  var divCol1 = document.createElement("div");
      divCol1.className = "fLeft";
      divCol1.setAttribute("column", "1");
      divCol1.textContent = zProduct.value;
      
  var divSub1 = document.createElement("div"); 
      divSub1.className = "col1";       
      divSub1.appendChild(divCol1);          
      divMain.appendChild(divSub1);      
      
  
  var divCol2 = document.createElement("div");
      divCol2.className = "fRight";
      divCol2.setAttribute("column", "2");
      var temp = new Number(zPrice.value);
      divCol2.textContent = temp.toFixed(2);
      
  var divSub2 = document.createElement("div");
      divSub2.className = "col2";       
      divSub2.appendChild(divCol2);
      divMain.appendChild(divSub2);
      
  var divCol3 = document.createElement("div");
      divCol3.className = "fRight";
      divCol3.setAttribute("column", "3");
      divCol3.textContent = zQuantum.value;

  var divSub3 = document.createElement("div");
      divSub3.className = "col3"; 
      divSub3.appendChild(divCol3);
      divMain.appendChild(divSub3);    
      
  var divCol4 = document.createElement("div"); 
      divCol4.className = "format99";
      divCol4.setAttribute("column", "4");
      divCol4.textContent = zSumRow.innerHTML;
      
  var divSub4 = document.createElement("div"); 
      divSub4.className = "col4";
      divSub4.appendChild(divCol4);
      divMain.appendChild(divSub4);
      
  var br = document.createElement("br");
      divMain.appendChild(br);
  
  document.getElementById("transaction").appendChild(divMain);
  
  traverseAmount();       
}
/***
* traverse all sum boxes
*/
function traverseAmount() {
  var summa = new Number();
  var divObj = document.getElementsByTagName("div");   

  for (summa = ix = 0; ix < divObj.length; ix++) {
   if (divObj[ix].className == "format99") summa += Number(divObj[ix].textContent); 
  } 
  zAmount.innerHTML = summa.toFixed(2);       
  zDankort.value = summa.toFixed(2);       
  zProduct.value = "*";
  zProduct.focus();
}
/***
* make the final transaction - using XML
*/
function wrapUp () {   

  var transaction = document.implementation.createDocument("","", null);           
      
  var operator = document.createElement("operator");
  var textblok1 = document.createTextNode(document.getElementById("rText").value);
      operator.appendChild(textblok1);         
      
  var root = document.createElement("transaction"); 
      root.setAttribute("tstamp",  now);
      root.setAttribute("sequenceno", zSequenceNo.textContent);
      if (parseInt(document.getElementById("zDankort").value) > 0) root.setAttribute("dankort", document.getElementById("zDankort").value);        
      if (parseInt(document.getElementById("zCash").value) > 0) root.setAttribute("cash", document.getElementById("zCash").value);              
      if (parseInt(document.getElementById("zCredit").value) > 0) root.setAttribute("credit", document.getElementById("zCredit").value);              
      if (parseInt(document.getElementById("zCheck").value) > 0) root.setAttribute("check", document.getElementById("zCheck").value);              
      if (parseInt(document.getElementById("zGiftcard").value) > 0) root.setAttribute("giftcard", document.getElementById("zGiftcard").value);              
      if (parseInt(document.getElementById("zVoucher").value) > 0) root.setAttribute("voucher", document.getElementById("zVoucher").value);              
   
      root.appendChild(operator);
   
  var divObj = document.getElementsByTagName("div");   
  
/***
*  when column value is 4, then we have our data complete - next cycle 
*/
  for (ix = 0; ix < divObj.length; ix++) {     
    switch (divObj[ix].getAttribute("column")) {
     case "1": var row = document.createElement("row"); row.setAttribute("item",divObj[ix].textContent);
     case "2": row.setAttribute("price",divObj[ix].textContent);        
     case "3": row.setAttribute("quantum",divObj[ix].textContent);        
     case "4": root.appendChild(row); 
     default: break;                 
    }
  }        
  transaction.appendChild(root);
  return(transaction);
}
/***
* insertLineed (command) lines can be deleted with a click
*/ 
function deleteNode() {
  var dead = this.parentElement;
  dead.removeChild(this);
  traverseAmount();       
}
/***
*  transmit the transaction
*/
    function makeSlot() {  
      
      var xmlhttp = new XMLHttpRequest();    
      xmlhttp.onreadystatechange=function() { if (xmlhttp.readyState==4 && xmlhttp.status==200) showBon(); } 
      xmlhttp.open("POST","crMakeSlot.php",true);  
      xmlhttp.send(wrapUp());  
    }
/***
*  get the max sequence no
*/
function getBonNumber() {
  var xmlhttp = new XMLHttpRequest();  
  xmlhttp.onreadystatechange=function() { if (xmlhttp.readyState==4 && xmlhttp.status==200) increment(xmlhttp.responseText); } 
  xmlhttp.open("GET","crMaxSeq.php",true);  
  xmlhttp.send();  
}
/***
*  status break
*/
function makeBreak() {
  var root = document.createElement("break");
      root.setAttribute("tstamp", datetime.stamp()); 
      
  var breakNode = document.implementation.createDocument("","", null);     
      breakNode.appendChild(root);
      
  var xmlhttp = new XMLHttpRequest();  
  xmlhttp.onreadystatechange=function() { if (xmlhttp.readyState==4 && xmlhttp.status==200) showRapport(xmlhttp.responseText); } 
  xmlhttp.open("POST","crBreak.php",true);  
  xmlhttp.send(breakNode);  
}
/***
*  print goes to subwindow
*/
function showBon() {  
  var temp = zSequenceNo.textContent.split("#");  
  window.open("crBon.php?x=" + temp[1] ,"crSub","menubar=no,toolbar=no");
  getBonNumber();    
}
/***
*  and the result is
*/
function showRapport(tstamp) {
  document.getElementById("zMessage").innerHTML = tstamp;
  window.open("crRapport.php?x=" + tstamp ,"crSub","menubar=no,toolbar=no");
}
/***
*  previous rapports
*/
function showRapports() {  
  window.open("crRapportOversigt.php" ,"crSub","menubar=no,toolbar=no");
}
/***
*  add 1 - part of AJAX getBonNumber()
*/
function increment(textNumber) {
  var temp = textNumber.split("#");
  if (temp.length > 0) { temp[1]++ ; zSequenceNo.textContent = "#000" + temp[1]; }
  document.getElementById("transaction").innerHTML = "";
  zAmount.innerHTML = "0.00";
  zNow.innerHTML = formatT(datetime.stamp());
}
/***
*  general cronology
*/
function timestamp() {
  var arrayObj = new Array('00', '01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59');
  var today = new Date();
  var zdatetime = today.getFullYear() + arrayObj[today.getMonth() + 1] + arrayObj[today.getDate()] + arrayObj[today.getHours()] + arrayObj[today.getMinutes()] + arrayObj[today.getSeconds()];
  return (zdatetime);
}