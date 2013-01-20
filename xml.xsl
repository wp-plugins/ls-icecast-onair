<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" version="1.0" encoding="utf-8" indent="yes"/>
<xsl:template match = "/icestats" >
<ICECAST>
	<xsl:for-each select="source">
		<SHOUTCASTSERVER>
			<MOUNT><xsl:value-of select="@mount" /></MOUNT>
			<CURRENTLISTENERS><xsl:value-of select="listeners" /></CURRENTLISTENERS>
			<PEAKLISTENERS><xsl:value-of select="listener_peak" /></PEAKLISTENERS>
			<MAXLISTENERS><xsl:value-of select="max_listeners" /></MAXLISTENERS>
			<SERVERTITLE><xsl:value-of select="server_name" /></SERVERTITLE>
			<SONG>
				<ARTIST><xsl:value-of select="artist" /></ARTIST>
				<TITLE><xsl:value-of select="title" /></TITLE>
			</SONG>
		</SHOUTCASTSERVER>
	</xsl:for-each>
</ICECAST>
</xsl:template>
</xsl:stylesheet>