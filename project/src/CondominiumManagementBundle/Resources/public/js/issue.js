var Issue = (function(){
    "use strict";

    const FORM_ID = '#condominium_issue_';

    var autocomplete = function() {
        var supplierType = $(FORM_ID + 'supplierType').val();
        $(FORM_ID + 'supplierName').autocomplete({
            source: function( request, response ) {
                $.ajax( {
                    url: route + "?supplierType="+supplierType,
                    async:true,
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        response(data);
                    }
                });
            },
            minLength: 0,
            select: function( event, ui ) {
                $(FORM_ID + 'supplierId').val(ui.item.id);
                show_info_supplier(ui.item.id);

            },
            change: function(event, ui) {
                if(ui.item == null) {
                    clearSupplierIDName();
                }
            }
        }).focus(function() {
            $(this).autocomplete('search');
        });
    }

    var channge_supplier_type = function() {
        clearSupplierIDName();
        autocomplete();
    }

    var show_popup_modal = function(elem) {
        var actionType = $(elem).attr('action-type');
        $(FORM_ID + 'actionType').val(actionType);
        $('#modal-action').modal();
    }

    var clearSupplierIDName = function() {
        $(FORM_ID + 'supplierId').val('');
        $(FORM_ID + 'supplierName').val('');
    }

    var show_info_supplier = function(id) {
        $.ajax( {
                url: route_info,
                async:true,
                dataType: "json",
                data: {
                    'id':id
                },
                success: function( data ) {
                    $('#sumplier-email').text(data.supplierGmail);
                    $('#supplier-phone').text(data.supplierPhone);
                    $('#supplier-address').text(data.supplierAddress);
                }
            });
}

    return {
        autocomplete: autocomplete,
        channge_supplier_type: channge_supplier_type,
        show_popup_modal: show_popup_modal
    };
}());

document.addEventListener('DOMContentLoaded', Issue.autocomplete, false);
