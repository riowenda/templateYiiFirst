$('#pr_rel').change(function(){
    var pr_no=document.getElementById( "pr_rel" ).value;
    $(".node").each( function() {
         //Get the links
         var links = $(this).children(".list").html();
         //Remove the links from the .links div
         $(this).children(".list").remove();
         //Append the links to the .submitted div
         $(this).children(".node").append(links);
    });
    $.ajax({
      url:"https://192.168.4.111/it-inventory/web/index.php?r=purchase-req/ajax-pr",
      type: "post",
      dataType:"json",
      data: {
        pr_no:pr_no,
      },
      success: function(response){
        var data = response;
        var html = "";

        html += '<div class="col-md-6 list"';
        html += '<label>Item</label>'
        html += '<select class="form-control locationMultiple" name="PurchaseInv[item_id]" required id="item-select2">';
        html += '<option selected disabled="" value="">Select Item</option>';
          for(i=0; i<data.length; i++){
            html += '<option value="'+data[i].id+'">'+data[i].name+'</option>';
          }
          html += '</select>';
          html += '</div>';
          $('#default-item').remove();

        $('#newList').append(html);
        $('#item-select2').change(function(){
            var item=document.getElementById( "item-select2" ).value;

            $.ajax({
              url:"https://192.168.4.111/it-inventory/web/index.php?r=item/ajax-item",
              type: "post",
              dataType:"json",
              data: {
                item:item,
                pr_no:pr_no,
              },
              success: function(response){
                var data = response;
                $("#category-auto").val(data[0].cat);
                $("#purchaseinv-qty").val(data[0].qty);
                $("#purchaseinv-qty").attr('readonly','');
                $("#purchaseinv-qty").attr('disabled','false');
             },
             error: function(response){
             alert("tidak dapat memproses");
           }
          });
        });

          // $("#purchaseinv-qty").val(response[i].qty);
          // $("#purchaseinv-qty").attr("disabled", true);
          // $('#cat-select').removeClass().addClass('form-control');
          // $("#cat-select").val(response[0].cat);
          //
          // $("#purchaseinv-order_by").val(response[0].pemohon);
          // $("#purchaseinv-order_by").attr("disabled", true);
          // $(".i100 span").hide();

     },
     error: function(response){
     alert("tidak dapat memproses");
   }
  });
});
