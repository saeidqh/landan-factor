$(document).ready(function(){
    $("#InvoiceEdit_ParrentID").click(function(){
        system.alert('dddd');
    });
    //@@akbar add
    $('#w0').on("pjax:send", function(){
        ajaxProgressLoader('show', '#w0');
    });
    //بجایafterAjaxupdate
    $('#w0').on("pjax:complete", function(event,data){
        ajaxProgressLoader('hide', '#w0');
//        updateTotalValues(updateTotal_url); 
    });
     $('#w0').on("pjax:success", function(a,b,c,d){
        //@TODO:[shokri]محتوای پی جکس باید از این طریق بروز شوند 
        
        ajaxProgressLoader('hide', '#w0');
//        updateTotalValues(updateTotal_url); 
    });
    
    $('#w1').on("pjax:send", function(){
        ajaxProgressLoader('show', '#w1');
    });
    $('#w1').on("pjax:complete", function(){
        ajaxProgressLoader('hide', '#w1');
    });
    $('#w2').on("pjax:send", function(){
        ajaxProgressLoader('show', '#w2');
    });
    $('#w2').on("pjax:complete", function(){
        ajaxProgressLoader('hide', '#w2');
    });
    $('#poreportPjax').on("pjax:send", function(){
        ajaxProgressLoader('show', '.invoice-total-side');
    });
    $('#poreportPjax').on("pjax:complete", function(){
        ajaxProgressLoader('hide', '.invoice-total-side');
    });
    //befor send for id=#invoiceAmountPjax
    $('#invoiceAmountPjax').on("pjax:send", function(){
        ajaxProgressLoader('show', '#invoiceAmountPjax');
    });
    

  
    //----------------------------------------------
    //befor send for id=#doclist-pjax
    $('#doclist-pjax').on('pjax:send', function(){
        ajaxProgressLoader('show', '#doclist-pjax');
    });
    //after send ajax for id=#doclist-pjax
    $('#doclist-pjax').on('pjax:complete', function(){
        ajaxProgressLoader('hide', '#doclist-pjax');
    });
    //----------------------------------------------

    $('#invoice-pjax-container').hide();
     //click in a:href View id="btn-edit-invoice"
    $('#btn-edit-invoice').click(function(){
        $('#btn-edit-invoice').hide();
        $('#invoice-info').hide();
        var url = $(this).attr('data-url');
        $.ajax({
            url:url, 
            dataType:"text" 
        }).done(function(data){
          
            $('#invoice-pjax-container').hide().html(data).fadeIn(1000);
            $('.btn-cancel').click(function(){
               $('#invoice-pjax-container').fadeOut();
               $('#invoice-info').fadeIn(1000);
               $('#btn-edit-invoice').show();
            });
            ajaxProgressLoader('hide','#invoice-pjax-container');
        }).error(function(){
            showMessageBox(
                    "Contract Status is Complete or Terminate",
                    "Error",
                    "err"
            );
            ajaxProgressLoader('hide','.invoice-total-side');
            return false;
        }); 
        return false;
    });
 
    //@@akbar added-edit
    //event click btn 
   $('.btn-edit').click(function(){
       var url = $(this).attr('data-url');
       $.ajax({
                url:url, 
                dataType:"text" 
            }).done(function(data){
                
                $('.modal-body').html(data);
                $('#myLargeModal').modal();
            }).error(function(){
                showMessageBox(
                        "Error on connecting to server. <br />Plase login again and try later!",
                        "Error",
                        "err"
                );
                ajaxProgressLoader('hide','.invoice-total-side');
                return false;
        }); 
        return false;
   }); 
   //@@akbar end
});

function updateTotalValues(url){
    ajaxProgressLoader('show','.invoice-total-side');
    $.ajax({
        url:url, //define on views/layout/dashboard 
        dataType:"text" 
    }).done(function(data){
        //$('#right-sidebar .bsAccordion').html('<img class="chat-avatar" src="data:image/png;base64,' +data +'" >');
        $('.total-box').html(data);
        ajaxProgressLoader('hide','.invoice-total-side');
    }).error(function(){
        showMessageBox(
                "Error on connecting to server. <br />Plase login again and try later!",
                "Error",
                "err"
        );
        ajaxProgressLoader('hide','.invoice-total-side');
        return false;
    });  
}

function removeAllChilds(){
    $('.child-tr').each(function(){
        var a = $(this).remove();
        console.log(a);
    });
}



function getContractRevisionData(btn) {

    var param = $(btn).attr('params');
    param = (param != null && param != '') ? "&" + param : param;
    var row_tr = $($(btn).parent('td')).parent('tr');
    var row_index = row_tr.attr('index');
    var res = "";
    if (row_tr.attr('load_data') == "done")
    {
        removeAllDetailRows();
        return false;
    }

    removeAllDetailRows();

    ajaxProgressLoader('show', '.revisions-grid');
    $.ajax({
        url: $(btn).attr('data-url') + param,
        context: document.body
    }).done(function (data) {
        addDetailRow(data, row_tr, btn);
        ajaxProgressLoader('hide', '.revisions-grid');
    }).error(function () {
        alert('Error');
        ajaxProgressLoader('hide', '.revisions-grid');
        return false;
    });
    //var table  = $(row_tr).parent('table');

    return false;
}

function removeAllDetailRows() {
    $('tr').each(function () {
        var cls = $(this).attr('class') + "";
        if (cls.indexOf('row-detail') != -1)
        {
            $(this).remove();
        }
        var ld = $(this).attr('load_data') + "";

        var btn = $(this).find('.psa-grid-btn');

        $(btn).find('i').removeClass('fa-minus');
        $(btn).find('i').addClass('fa-plus');


        if (ld.indexOf('done') != -1)
        {
            $(this).attr('load_data', '');
        }
    });
}
function addDetailRow(data, row_tr, btn) {
    var new_row = "";
    new_row = "<tr class='row-detail'>"
            + "<td colspan='10' style='background:#FFF0E4 !important;border: 1px solid #e7e7e2;'>"
            + data
            + "</td>"
            + "</tr>";

    $(row_tr).after(new_row);
    row_tr.attr('load_data', 'done');
    $(btn).find('i').removeClass('fa-plus');
    $(btn).find('i').addClass('fa-minus');
}

//invoice Detail off tab invoice Document
function deleteInvoiceDocument(btnid){
    var id = $('.delete-document-'+btnid).attr('data-id');
    var modeinvoice = $('.delete-document-'+btnid).attr('data-mode');
    var url = $('.delete-document-'+btnid).attr('data-url');
    window.location.replace( url);

//// similar behavior as clicking on a link
//window.location.href = "http://stackoverflow.com";
    
    
    
    
    
    
//    
//    
//    
//    
//    $.ajax({
//        url: url,
//        data: {id:id},
//    }).done(function (data){
//
//        data = $.parseJSON(data) ;
//
//        if(data.result  =='true' ){
//            showMsgBoxInBottomRight(data.message,'success');
//            $.pjax.reload({
//                container:'#invoice-document-pjax',
//                timeout: false,
//                url: update_url,
//                push:false,
//                replace:false,
//                data:{
//                    mode: modeinvoice
//                }
//            });
//        }else{
//            showMsgBoxInBottomRight('Error in deleting invoice document','error','Error');
//        }
//        //return false;
//    }).error(function (){
//        showMessageBox(
//                "Error on connecting to server. <br />Plase login again and try later!",
//                "Error",
//                "err"
//                );
//        ajaxProgressLoader('hide', '.grid-box-silver');
//        return false;
//    });
    //return  false;
}


function showDocumentModal(selector){
   ajaxProgressLoader('show', '.modal-content');
    var url = $(selector).attr('data-url');
    $.ajax({
        url: url,
        dataType: 'html'
    }).done(function(data) {
        $('#invoice-document-form-pjax').html(data);
        $('#document-modal').modal('show');
        ajaxProgressLoader('hide', '.modal-content');
    }).error(function() {
        showMsgBoxInBottomRight('Limited Access or error in connecting to server','error');
        return false;
    });
    
}

//این بخش جهت رفع خطای انتخاب کننده کاست کد در زمان زدن اینتر توسط مهندس شکری نوشته شد
$(document).ajaxComplete(function(){
    $('#cccpSearch').keydown(function(e){
        if(e.which== 13){
            if(name.indexOf('ContractView[')!=-1){
                obj.change();
            }
            e.preventDefault();
            $('#cccp-filter-btn').click();
            return false;
        }
    });
});