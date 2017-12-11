/*
SAT Vocabulary
http://satvocab.org
Author		Mark Villa
Date			12/01/2017
Website		http://www.markvilla.org
Copyright	2017 Mark Villa.
*/

$( document ).ready(function() {
	var dynatable;
	// function to create first table and update
	function uniqueCellWriter(column, record) {
    var html = column.attributeWriter(record),
				input ='',
				td = '<td';
		if (column.index % 5 === 4) {
			html = '';
			input = '<input class="recollection-id" type="hidden" value=' + record['id'] + '><input class="recollection-input" type="text" maxlength="1">';
		}
    if (column.hidden || column.textAlign) {
      td += ' style="';
      // keep cells for hidden column headers hidden
      if (column.hidden) {
        td += 'display: none;';
      }
      // keep cells aligned as their column headers are aligned
      if (column.textAlign) {
        td += 'text-align: ' + column.textAlign + ';';
      }
      td += '"';
    }
    return td + '>' + input + html + '</td>';
  };

	function createTable(response, update) {
		// dynatable for the table
		dynatable = $('.vocabulary-table').dynatable({
			dataset: {
				perPageDefault: 50,
				perPageOptions: [50,100, 200],
				records: response
			},
			writers: {
				_cellWriter: uniqueCellWriter
			}
		});

		if (update) {
			dynatable.data('dynatable').settings.dataset.originalRecords = response;
		  dynatable.data('dynatable').process();
		}
	}

	// first time pull from server and fill table
	$.ajax({
			type: 'POST',
			url: "service.php",
			data: {
							process: "firstLoad",
							submitData: ""
						},
			success: function(response){
				console.log(response);
				if (response) {
					createTable(response, false);
				}
				if (!response) {
					console.log("nothing");
				}
			}
	});

	//  update database and vocabulary table on page flip
	$(".container").on('click','a.dynatable-page-link:not(.dynatable-disabled-page):not(.dynatable-active-page)',function( event ){
		var list = [];
		$('input[type="hidden"][class="recollection-id"]').each(function( index, element ) {
			var row = new Object();
			row.id = $(element).val();
			row.input = $(element).parent().find('input.recollection-input').val();
			list[index] = row;
		});
		console.log(list);
		var submitData = JSON.stringify(list);
		$.ajax({
				type: 'POST',
				url: "service.php",
				data: {
								process: "updateDatabaseAndTable",
								submitData: submitData
							},
				success: function(response){
					console.log(response);
					createTable(response, true);
				}
		});
	});
});
