      var config = {
                    '.chosen-select': {
                        no_results_text: 'Company not found',
                        search_contains: 'true'
                    },
                    '.chosen-select-deselect': {
                        allow_single_deselect: true
                    },
                    '.chosen-select-no-single': {
                        disable_search_threshold: 10
                    },
                    '.chosen-select-no-results': {
                        no_results_text: 'Company not found'
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
