<?xml version = "1.0" ?>

<xsl:stylesheet version = "1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method = "xml" doctype-system = "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" doctype-public = "-//W3C//DTD XHTML 1.0 Transitional//EN" indent = "yes" />
	<xsl:template match = "/">

	<html>
		<head>
			<title>phpcs report</title>

			<link rel = "stylesheet" type = "text/css" href = "stylesheet.css" />
		</head>

		<body>
			<xsl:apply-templates />
		</body>
	</html>
	</xsl:template>

	<xsl:template match = "file">
		<h2>File: <xsl:value-of select = "@name" /></h2>

		<table>
			<thead>
				<tr>
					<th>Line</th>
					<th>Message</th>
					<th>Severity</th>
					<th>Rule</th>
				</tr>

			</thead>

			<tbody>
				<xsl:apply-templates />
			</tbody>
		</table>

	</xsl:template>

	<xsl:template match = "warning">
		<tr>
			<td><xsl:value-of select = "@line"/> </td>
			<td><xsl:value-of select = "." /></td>
			<td><xsl:value-of select = "@severity"/></td>
			<td class = "warning"><xsl:value-of select = "@source" /></td>
		</tr>
	</xsl:template>

	<xsl:template match = "error">
		<tr>
			<td><xsl:value-of select = "@line"/> </td>
			<td><xsl:value-of select = "." /></td>
			<td><xsl:value-of select = "@severity"/></td>
			<td class = "bad"><xsl:value-of select = "@source" /></td>
		</tr>
	</xsl:template>
	
</xsl:stylesheet>
