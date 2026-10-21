
$(document).ready(function () {

    afterReady();
     var html = $('#pjax-container').html();
    
    
    
});


function afterReady() {
    

    $("#invoiceedit_revisionnumber").change(function () {
        if ($("#invoiceedit_revisionnumber").val() > 0) {
            $("#invoiceedit-parrentid").removeAttr('disabled');
        } else {
            $("#invoiceedit-parrentid").attr('disabled', 'disabled');
            $("#invoiceedit-parrentid").val(null);
        }
        ;
    });

    $(".invoice-amount-edit").click(function () {
        loadInvoiceAmountEdit($(this));
    });

    $(".select-cost-code").click(function () {
        LoadCostCodeSelectPopup($(this));
    });

    $(".invoice-document-edit").click(function () {
        loadInvoiceDocumentEdit($(this));
    });

    $("#invoiceamountedit-amountrequested1").change(function () {
        getConvertedInvoiceAmount('requested1', $("#invoiceamountedit-amountrequested1").attr('convert_url'),$(this).val());
    });

    $("#invoiceamountedit-amountapproved1").change(function () {
        getConvertedInvoiceAmount('approved1', $("#invoiceamountedit-amountapproved1").attr('convert_url'),$(this).val());
    });
    
    
    $(".btnSaveInvoiceDocumentModal").click(function () {
        sendInvoiceDocument();
    });
    
}

function getConvertedInvoiceAmount(amountType, ajax_url,enteredValue) {

    if (($("#invoiceamountedit-amounttype") == null) || ($("#invoiceamountedit-costcode") == null)) {
        return;
    }


    ajax_url = ajax_url + '&amountType=' + $("#InvoiceAmountEdit_AmountType").val() + '&nodeId=' + $("#InvoiceAmountEdit_CostCode").val()+'&enteredValue=' + enteredValue;
    $.ajax({
        url: ajax_url,
        dataType: "text"
    }).done(function (data) {
        switch (amountType) {
            case 'requested1':
                $("#invoiceamountedit-requested1cumulative").val(data);
                return;
            case 'approved1':
                $("#invoiceamountedit-approved1cumulative").val(data);
                return;
            case '':


        }


    }).error(function () {
        showMessageBox(
                "Error on connecting to server. <br />Plase login again and try later!",
                "Error",
                "err"
                );
    });
}

function SelectCostCode(nodeId) {
//بعد از کلیک بر روی کاست کد انتخاب شده قرار است که این دکمه پاپ اپ را ببندد و کد انتخاب شده را به سابفرم ویرایش مبالغ برگرداند
    $("#invoiceamountedit-costcode").val(nodeId);
    hidePopupBox();
}

function LoadCostCodeSelectPopup(obj) {

    ajaxProgressLoader('show', '.invoice-total-side');
    $.ajax({
        url: obj.attr('ajax_url'),
        dataType: "text"
    }).done(function (data) {
        showPopupBox(data, 'Select CostCode');
        ajaxProgressLoader('hide', '.invoice-total-side');
//        afterReady();
        return true;
    }).error(function () {
        showMessageBox(
                "Error on connecting to server. <br />Plase login again and try later!",
                "Error",
                "err"
                );
        ajaxProgressLoader('hide', '.invoice-total-side');
        return false;
    });

}

function loadInvoiceAmountEdit(btn) {
    var row_tr = $($(btn).parent('td')).parent('tr');
    var param = $(btn).attr('params');
    param = (param != null && param != '') ? "&" + param : param;
    var row_tr = $($(btn).parent('td')).parent('tr');
    if (row_tr.attr('load_data') == "done")
    {
        removeAllInvoiceAmountDetailRows();
        return false;
    }
    ajaxProgressLoader('show', '.invoice-total-side');


    $.ajax({
        url: btn.attr('ajax_url'),
        dataType: "text"
    }).done(function (data) {
        removeAllInvoiceAmountDetailRows();
        addInvoiceAmountDetailRow(data, row_tr);
        ajaxProgressLoader('hide', '.invoice-total-side');


        $(".select-cost-code").click(function () {
            LoadCostCodeSelectPopup($(this));
        });

        $("#invoiceamountedit-amountrequested1").change(function () {
            getConvertedInvoiceAmount('requested1', $("#invoiceamountedit-amountrequested1").attr('convert_url'),$(this).val());
        });

        $("#invoiceamountedit-amountapproved1").change(function () {
            getConvertedInvoiceAmount('approved1', $("#invoiceamountedit-amountapproved1").attr('convert_url'),$(this).val());
        });

    }).error(function () {
        showMessageBox(
                "Error on connecting to server. <br />Plase login again and try later!",
                "Error",
                "err"
                );
        ajaxProgressLoader('hide', '.invoice-total-side');
        return false;
    });

}
function addInvoiceAmountDetailRow(data, row_tr) {
    var new_row = "";
    new_row = "<tr class='invoice-amount-detail'>"
            + "<td colspan='8' style='background:#FFF0E4 !important;border: 1px solid #e7e7e2;'>"
            + data
            + "</td>"
            + "</tr>";

    $(row_tr).after(new_row);
    row_tr.attr('load_data', 'done');

    $(row_tr).find('i').removeClass('fa-caret-left s20');
    $(row_tr).find('i').addClass('fa-caret-down s20');
}




function removeAllInvoiceAmountDetailRows() {
    $('tr').each(function () {
        var cls = $(this).attr('class') + "";
        if (cls.indexOf('invoice-amount-detail') != -1)
        {
            $(this).remove();
        }
        var ld = $(this).attr('load_data') + "";

        var btn = $(this).find('.invoice-amount-edit');

        $(btn).find('i').removeClass('fa-caret-down s20');
        $(btn).find('i').addClass('fa-caret-left s20');


        if (ld.indexOf('done') != -1)
        {
            $(this).attr('load_data', '');
        }
    });
}




function sendInvoiceAmount() {
    var data = $('#invoice-amount-edit-form').serialize();

    $.ajax({
        type: 'POST',
        url: $('#btnSaveInvoiceAmount').attr('ajaxUrl'),
        data: data,
        success: function (data) {
            $.fn.yiiGridView.update('invoice-amount-grid');
        },
        error: function (data) {
            alert("Error");
        },
        dataType: 'html',
    })

}




function sendInvoiceDocument() {

    var data = $('#invoice-document-edit-form').serialize();
alert(data);
    $.ajax({
        type: 'POST',
        url: $('#btnSaveInvoiceDocument').attr('ajaxUrl'),
        data: data,
        success: function (data) {
            $.fn.yiiGridView.update('invoice-document-grid');
        },
        error: function (data) {
            alert("Error");
        },
        dataType: 'html',
    })
}





function loadInvoiceDocumentEdit(btn) {

//    var row_tr = $($(btn).parent('td')).parent('tr');
//    var param = $(btn).attr('params');
//    param = (param != null && param != '') ? "&" + param : param;
    var row_tr = $($(btn).parent('td')).parent('tr');
    if (row_tr.attr('load_data') == "done")
    {
        removeAllInvoiceDocumentDetailRows();
        return false;
    }

    ajaxProgressLoader('show', '.invoice-total-side');
    $.ajax({
        url: btn.attr('ajax_url'),
        dataType: "text"
    }).done(function (data) {
        removeAllInvoiceDocumentDetailRows();
        addInvoiceDocumentDetailRow(data, row_tr);
        ajaxProgressLoader('hide', '.invoice-total-side');

    }).error(function () {
        showMessageBox(
                "Error on connecting to server. <br />Plase login again and try later!",
                "Error",
                "err"
                );
        ajaxProgressLoader('hide', '.invoice-total-side');
        return false;
    });

}
function addInvoiceDocumentDetailRow(data, row_tr) {
    var new_row = "";
    new_row = "<tr class='invoice-document-detail'>"
            + "<td colspan='8' style='background:#FFF0E4 !important;border: 1px solid #e7e7e2;'>"
            + data
            + "</td>"
            + "</tr>";

    $(row_tr).after(new_row);
    row_tr.attr('load_data', 'done');

    $(row_tr).find('i').removeClass('fa-caret-left s20');
    $(row_tr).find('i').addClass('fa-caret-down s20');
}




function removeAllInvoiceDocumentDetailRows() {
    $('tr').each(function () {
        var cls = $(this).attr('class') + "";
        if (cls.indexOf('invoice-document-detail') != -1)
        {
            $(this).remove();
        }
        var ld = $(this).attr('load_data') + "";

        var btn = $(this).find('.invoice-document-edit');

        $(btn).find('i').removeClass('fa-caret-down s20');
        $(btn).find('i').addClass('fa-caret-left s20');


        if (ld.indexOf('done') != -1)
        {
            $(this).attr('load_data', '');
        }
    });
}


