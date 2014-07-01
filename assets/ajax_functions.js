/*
 * Devid
 */
function ajax_file(form_id,write_div,trigger_id){
 
    $(document).ready(function(){
        $('#'+trigger_id).click(function(){
                $("#"+form_id).submit();
        });
    });
    
   $("#"+form_id).submit(function(){
       
       var formData = new FormData($(this)[0]);
       var formUrl = $(this).attr("action");
       $.ajax({
           url: formUrl,
           type: 'POST',
           data: formData,
           async: false,
           
            success:function(data){
                $('#'+write_div).html(data);
            },
            cache: false,
            contentType: false,
            processData: false
       });
       return false;
   });
    
};

