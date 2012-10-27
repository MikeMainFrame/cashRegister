<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" 
  xmlns:cr="http://www.w3.org/1999/xhtml"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output
  method="xml"
  version="1.0"
  encoding="utf-8"
  indent="yes"
  media-type="xml/text"
  doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
  doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" />

<xsl:decimal-format name="zformat" decimal-separator="," grouping-separator="." NaN="-" />
<xsl:strip-space elements="*"/>
  <xsl:param name="dankort"></xsl:param>
  <xsl:param name="cash"></xsl:param>
  <xsl:param name="credit"></xsl:param>
  <xsl:param name="giftcard"></xsl:param>
  <xsl:param name="check"></xsl:param>
  <xsl:param name="voucher"></xsl:param>
<xsl:template match="roll">
  <xsl:variable name="min"><xsl:call-template name="bonSequenceMin" /></xsl:variable>
  <xsl:variable name="max"><xsl:call-template name="bonSequenceMax" /></xsl:variable>
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head><title>Rapport</title>
  <style>
    @media screen {
      html, body, 
      table, tr,
      td, th      { margin: 0;  padding: 0;  color: grey ;  font-family: verdana, sans-serif ; font-size: 11px }
      td, th      { padding: 12px ; text-align: right ; border: solid 1px #ddd }
      th          { font-weight: 100; text-align: center ; color: #eee}
      table       { border-collapse:collapse ; border-color: grey ; background: #fff}
      body        { background-color:   #000 ; background: #000 }
      th          { background: -moz-linear-gradient(top, #aaaaaa 0%, #666666 100%);
                    background-image: -webkit-gradient(linear, left top, left bottom,color-stop(0.00, #aaaaaa),color-stop(1.00, #666666)) ;
                    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(enabled='true', startColorstr=#aaaaaa, endColorstr=#666666, GradientType=0 )";
                    filter: progid:DXImageTransform.Microsoft.gradient( enabled='true', startColorstr=#aaaaaa, endColorstr=#666666, GradientType=0 )}
      }
    @media print {
      table       { border-collapse:collapse ; border:solid thin #fff }
      td, th      { margin: 0;  padding: 0;  color: #000 ;  font-family: verdana, sans-serif ; font-size: 8pt ; padding: 4pt ; border: solid .5pt  #888}
      th          { font-weight: 600 }
    }
  </style>
</head>
<body>

<table>   

  <tr>
  <td colspan="4" style="text-align:left ; background: #000 ; border:none">
    <h1>Rapport</h1>
  </td>
  <td colspan="5" style="background: #000; border:none">
    <pre><xsl:value-of select="//cr:operator[1]" /></pre>
  </td>
  </tr>
  <tr><th colspan="3">Bon sekvens: <xsl:value-of select="$min" /> - <xsl:value-of select="$max" /></th>
  <th>Dankort</th><th>Kontant</th><th>Kredit</th><th>Gavekort</th><th>Tilgode</th><th>Check</th></tr>
  <tr>
      <th colspan="3">Primo</th>
      <td><xsl:value-of select="format-number($dankort  ,'#.###.##0,00', 'zformat')" /></td>
      <td><xsl:value-of select="format-number($cash  ,'#.###.##0,00', 'zformat')" /></td>
      <td><xsl:value-of select="format-number($credit  ,'#.###.##0,00', 'zformat')" /></td>
      <td><xsl:value-of select="format-number($giftcard  ,'#.###.##0,00', 'zformat')" /></td>
      <td><xsl:value-of select="format-number($voucher  ,'#.###.##0,00', 'zformat')" /></td>
      <td><xsl:value-of select="format-number($check  ,'#.###.##0,00', 'zformat')" /></td>
    </tr>

    <xsl:for-each select="cr:transaction">
      <tr>
        <td><xsl:value-of select="position()" /></td>
        <td><xsl:value-of select="@sequenceno" /></td>
        <td><xsl:call-template name="fTstamp"><xsl:with-param name="dateX" select="@tstamp" /> </xsl:call-template></td>
        
        <xsl:variable name="sumline">    <xsl:call-template name="summasumarum"><xsl:with-param name="list" select="cr:row" /></xsl:call-template>  </xsl:variable>      
        
        <td><xsl:value-of select="format-number(@dankort  ,'#.###.##0,00', 'zformat')" /></td>
        <td><xsl:value-of select="format-number(@cash  ,'#.###.##0,00', 'zformat')" /></td>
        <td><xsl:value-of select="format-number(@credit  ,'#.###.##0,00', 'zformat')" /></td>
        <td><xsl:value-of select="format-number(@giftcard  ,'#.###.##0,00', 'zformat')" /></td>
        <td><xsl:value-of select="format-number(@voucher  ,'#.###.##0,00', 'zformat')" /></td>
        <td><xsl:value-of select="format-number(@check  ,'#.###.##0,00', 'zformat')" /></td>
      </tr>    
    </xsl:for-each>
    <tr>
    <td colspan="3">#</td>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$dankort + sum(//@dankort)" /> </xsl:call-template></td>      
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$cash + sum(//@cash)" /> </xsl:call-template></td>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$credit + sum(//@credit)" /> </xsl:call-template></td>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$giftcard + sum(//@giftcard)" /> </xsl:call-template></td>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$voucher + sum(//@voucher)" /> </xsl:call-template></td>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$check + sum(//@check)" /> </xsl:call-template></td>
    </tr>
    <tr>
      <th colspan="3">Ultimo</th>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$dankort + sum(//@dankort)" /> </xsl:call-template></td>      
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$cash + sum(//@cash)" /> </xsl:call-template></td>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$credit + sum(//@credit)" /> </xsl:call-template></td>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$giftcard + sum(//@giftcard)" /> </xsl:call-template></td>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$voucher + sum(//@voucher)" /> </xsl:call-template></td>
      <td><xsl:call-template name="fNumber"><xsl:with-param name="unformatted" select="$check + sum(//@check)" /> </xsl:call-template></td>
      
    </tr>
  </table>
</body></html>

</xsl:template>

<xsl:template name="fNumber">
  <xsl:param name="unformatted"/>
  <xsl:choose>
    <xsl:when test="$unformatted != 0"><xsl:value-of select="format-number($unformatted,'#.###.##0,00', 'zformat')" /></xsl:when>
    <xsl:otherwise>-</xsl:otherwise>
  </xsl:choose>  
</xsl:template>
<xsl:template name="summasumarum">
  <xsl:param name="list"/>
  <xsl:choose>
    <xsl:when test="$list">
      <xsl:variable name="first" select="$list[1]"/>
      <xsl:variable name="between">
        <xsl:call-template name="summasumarum">
          <xsl:with-param name="list" select="$list[position()!=1]"/>
        </xsl:call-template>
      </xsl:variable>
      <xsl:value-of select="$first/@quantum * $first/@price + $between"/>
    </xsl:when>
    <xsl:otherwise>0</xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="fTstamp">
  <xsl:param name="dateX" />
  <xsl:value-of select="substring($dateX,7,2)" /><xsl:value-of select="substring('  JANFEBMARAPRMAYJUNJULAUGSEPOCTNOVDEC', number(substring($dateX,5,2)) * 3, 3)" /><xsl:value-of select="substring($dateX,1,4)" />&#xa0;<xsl:value-of select="substring($dateX,9,2)" />:<xsl:value-of select="substring($dateX,11,2)" />:<xsl:value-of select="substring($dateX,13,2)" /> 
</xsl:template>

<xsl:template name="bonSequenceMin"> 
  <xsl:for-each select="cr:transaction">  
    <xsl:sort select="@sequenceno" order="ascending" />            
      <xsl:if test="position() = 1"><xsl:value-of select="@sequenceno" /></xsl:if>
  </xsl:for-each>    
</xsl:template>

<xsl:template name="bonSequenceMax"> 
  <xsl:for-each select="cr:transaction">  
    <xsl:sort select="@sequenceno" order="descending" />            
      <xsl:if test="position() = last()"><xsl:value-of select="@sequenceno" /></xsl:if>
  </xsl:for-each>    
</xsl:template>
</xsl:stylesheet>