<?xml version="1.0" encoding="ISO-8859-1"?>
<!-- Edited with XML Spy v2007 (http://www.altova.com) -->
<html xsl:version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">
		<body style="font-family:Arial,helvetica,sans-serif;font-size:10pt">

				
		  <div style="margin-bottom:2em;">
			<span style="2em;font-size:14pt">
					<img src="/images/feed-icon-32x32.jpg" /><br /> <xsl:value-of select="rss/channel/title"/>
			</span>
		</div>
		  <div style="margin-bottom:2em;">
			<xsl:value-of select="rss/channel/description"/><br /><br />
			Events RSS feed.<br />
		</div>
		<hr />
<br />
	<xsl:for-each select="rss/channel/item">
        <span style="font-weight:bold">
        <xsl:value-of select="title"/></span>
        <div>
        <xsl:value-of select="pubDate"/></div>
      <div style="margin-left:20px;margin-bottom:1em;font-size:10pt">
        <xsl:value-of select="description"/>
		<span style="font-style:italic">
<a>
<xsl:attribute name='href'><xsl:value-of select="link"/></xsl:attribute>
more</a>
				
				
        </span>
      </div>
	</xsl:for-each>

<br />	
<br />	
<hr />
<br />	
<div style="background-color:#eeeeee;padding:15px">
<strong>RSS FAQ</strong><br /><br />
<strong>What does RSS do?</strong><br />
RSS allows you to see a website's latest content without having to visit the website.<br /><br />
<strong>What does RSS stand for?</strong><br />
There appears to be some doubt - Some say "Rich Site Summary", others "Really Simple Syndication".<br /><br />
<strong>How do I use RSS?</strong><br />
You need an RSS Reader (you may already have one).<br />
The following support RSS:<br /><br />
	Firefox 2<br />
	Internet Explorer 7<br />
	Outlook 2007<br />
	Thunderbird 1.5<br /><br />
There are also numerous web based RSS readers:<br />
<a href="http://www.google.com/reader/view/">Google</a><br />
<a href="http://my.yahoo.com/s/about/rss/index.html">Yahoo</a><br />
<a href="http://www.newsgator.com/home.aspx">News Gator</a><br />
      </div>

</body>

</html>
