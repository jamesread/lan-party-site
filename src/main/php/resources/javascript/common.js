function makeTableSortable() {
	$('table.sortable').dataTable({
		'aaSorting': [[ 3, "asc" ]],
		'sPaginationType': 'two_button',
		'sDom': 'flpitpil',
		'iDisplayLength': 20
	});
}
