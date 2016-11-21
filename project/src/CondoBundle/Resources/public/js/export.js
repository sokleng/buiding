var Export = (function() {
    'use strict'

    var do_export = function (selector, type, function_name) {
        var fileName = function_name.replace(/ /g, '');
        if(type === "csv") {
            var options = {
                fileName: fileName,
                type: type,
                ignoreColumn: ['hide-export']
            }
        }

        if(type === "pdf") {
            var options = {
                fileName: fileName,
                type: type,
                ignoreColumn: ['hide-export'],
                jspdf: {
                    orientation: 'p',
                    format: 'a3',
                    margins: {
                        left:10,
                        right:10,
                        top:20,
                        bottom:20
                    },
                    autotable: {
                        styles: {
                            overflow: 'linebreak'
                        },
                        tableWidth: 'wrap'

                    }
                }
            }
        };

        $(selector).tableExport(options);
    }

    return {
        do_export : do_export
    };
}());
