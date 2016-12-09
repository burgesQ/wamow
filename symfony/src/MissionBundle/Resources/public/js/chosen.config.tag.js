$(function() {
    $(".chosen-select").chosen({
	create_option: function(term){
	    var chosen = this;
	    $.post('add_term.php', {term: term}, function(data){
		chosen.append_option({
		    value: 'value-' + data.term,
		    text: data.term
		});
	    });
	}
    });
});

var config = {
    '.chosen-select': {
	search_contains: 'true',
	create_option: 'true',
	persistent_create_option: 'true',
	skip_no_results: 'true',
  create_option_text: 'Add Tag'
    },
    '.chosen-select-deselect': {
	allow_single_deselect: true
    },
    '.chosen-select-no-single': {
	disable_search_threshold: 10
    },
    '.chosen-select-no-results': {
	no_results_text: 'Tag not found'
    },
    '.chosen-select-width': {
	width: "95%"
    }
}
for (var selector in config) {
    $(selector).chosen(config[selector]);
}

$(function() {
    jQuery(document).ready(function() {
	$('select.chosen-select').chosen();
    });
})
