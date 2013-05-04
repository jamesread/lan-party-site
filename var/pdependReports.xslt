<?xml version = "1.0" ?>

<xsl:stylesheet version = "1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method = "xml" doctype-system = "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" doctype-public = "-//W3C//DTD XHTML 1.0 Transitional//EN" indent = "yes" />
	<xsl:template match = "/">
	<html>
		<head>
			<title>pdepend report</title>

			<link rel = "stylesheet" type = "text/css" href = "stylesheet.css" />
		</head>

		<body>
			<xsl:apply-templates />

			<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAA438OJ5Nhur1zE7_BrT72IhSygCaNBYuonit26g7VkzkBdWMtHRSzVSY26m4NDzPDKILUpz_zelVMRA"></script>
			<script type = "text/javascript">
				google.load('jquery', '1.6.4');
			</script>

			<script type = "text/javascript">
				function hlCellClass(clazz, badLimit, warningLimit) {
					$('td.' + clazz).each(function(index, cell) {
						cell = $(cell);
						v = parseInt(cell.html(), 10);

						console.log(v);

						if (v > badLimit) {
							cell.css('background-color', 'red');
						} else if (v > warningLimit) {
							cell.css('background-color', 'orange');
						} else {
							cell.css('background-color', 'lightgreen');

						}
						
					});
				}

				hlCellClass('.ccd', 10, 5);
				hlCellClass('.ccn', 6, 3);
				hlCellClass('.eloc', 25, 10);

			</script>
		</body>
	</html>
	</xsl:template>
	
	<xsl:template match = "files">
		<table>
			<thead>
				<tr>
					<th>File</th>
					<th><abbr title = "Cyclomic Lines of Complexity">CLOC</abbr></th>
				</tr>
			</thead>

			<tbody>
			<xsl:apply-templates />
			</tbody>
		</table>
	</xsl:template>

	<xsl:template match = "files/file">
		<tr>
			<td><xsl:value-of select = "@name" /></td>
			<td class = "ccd"><xsl:value-of select = "@cloc" /></td>
		</tr>
	</xsl:template>

	<xsl:template match = "class">
		<h2>Class: <xsl:value-of select = "@name" /></h2>
		
		<table>
			<thead>
				<tr>
					<th>method</th>
					<th>ccn</th>
					<th><abbr title = "Executable lines of code">eloc</abbr></th>
				</tr>
			</thead>

			<tbody>
				<xsl:apply-templates />
			</tbody>
		</table>
	</xsl:template>

	<xsl:template match = "class/method">
		<tr>
			<td><xsl:value-of select = "@name" /></td>
			<td class = "ccn"><xsl:value-of select = "@ccn" /></td>
			<td class = "eloc"><xsl:value-of select = "@eloc" /></td>
		</tr>
	</xsl:template>
</xsl:stylesheet>
