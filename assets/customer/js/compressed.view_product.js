function getProductDetailedInfo(categ_page)
{$.ajax({type:'ajax',url:base_url+'customer/getProductDetailed/'+categ_page,async:true,dataType:'json',success:function(data)
{if(data===null)
{$('#product_body').empty();$('#product_body').append('<div class="product_view_banner" id="product_view_b"><h2>No Product Exists!</h2></div>');}
else
{$('#product_view_b').empty();let bread_crumb=create_bread_crumbs('Product',data['subcat_name'],data['cat_name']);$('#product_view_b').append('<h2>'+data['name']+'</h2>'+bread_crumb);$('#product_pic_holder').attr('src',base_url+data['img_file_path']);$('#stocks_on_hand').text("Stock On Hand : "+data['quantity']);p_quantity=data['quantity'];if(data['discount_percent']=="0")
{$('#cart-list-span-counter').remove();$('#product_orig_price_id').remove();$('#product_price_id').show();$('#product_price_id').append('&#8369; '+data['selling_price']);}
else
{let new_price=parseFloat(data['selling_price']-((data['discount_percent']/100)*data['selling_price'])).toFixed(2);$('#product_pic').append('<span class="label-count" id="cart-list-span-counter"></span>');$('#cart-list-span-counter').append(data['discount_percent']+'% OFF');$('#cart-list-span-counter').show();$('#product_price_id').show();$('#product_price_id').append('&#8369; '+new_price);$('#product_price_id').after('<h2 class="product_view_price_before font-line-through" id="product_orig_price_id">&#8369; '+data['selling_price']+'</h2>');}
$('#prod_desc').empty();$('#prod_desc').append("<p>"+data['description']+"</p>");}}});var query_for_stock_type;$.ajax({type:'ajax',url:base_url+'customer/getOptions/'+categ_page,async:true,dataType:'json',success:function(data)
{let i;let values_to_append='';let prev_group='';let prev_name='';for(i=0;i<data.length;i++)
{if(data[i].opt_grp_name=="Color")
{let var_opt_color_class='.color-'+data[i].opt_name;if($(var_opt_color_class).length)
{}
else
{if(categ_page==data[i].sku)
{$('#has_colors').append('<div class="product-pic-small color-'+data[i].opt_name+'" ><img src="'+base_url+data[i].img_file_path+'" class="color_options clicked_opt" data-image-holder="'+base_url+data[i].img_file_path+'" data-opt-id="'+data[i].option_id+'"/></div>');opt_color_id=data[i].option_id;}
else
{$('#has_colors').append('<div class="product-pic-small color-'+data[i].opt_name+'" ><img src="'+base_url+data[i].img_file_path+'" class="color_options" data-image-holder="'+base_url+data[i].img_file_path+'" data-opt-id="'+data[i].option_id+'"/></div>');}}}
else if(data[i].opt_grp_name=="Size")
{let var_opt_size_class='.size-'+data[i].opt_name.toLowerCase();if($(var_opt_size_class).length)
{}
else
{$('#has_sizes').append('<div class="product-pic-small size_option size-'+data[i].opt_name.toLowerCase()+'" data-size-label="'+data[i].opt_name+'" data-opt-id="'+data[i].option_id+'"><p>'+data[i].opt_name+'</p></div>');}
if(categ_page==data[i].sku)
{opt_size_id=data[i].option_id;let size=data[i].opt_name.toLowerCase();$('.size-'+size).show();$('.size-'+size).children().addClass('clicked_opt');}}}
$('.color_options').click(function(){$('#product_pic_holder').attr('src',$(this).data('image-holder'));opt_color_id=$(this).data('opt-id');if($('#has_sizes').is(":visible"))
{query_for_stock_type="with_size";query_for_stock(query_for_stock_type);}
else
{query_for_stock_type="without_size";query_for_stock(query_for_stock_type);}
$('.color_options').removeClass('clicked_opt');$(this).addClass('clicked_opt');});$('.size_option').click(function(){opt_size_id=$(this).data('opt-id');if($('#has_colors').is(":visible"))
{query_for_stock_type="with_color";query_for_stock(query_for_stock_type);}
$('.size_option').children().removeClass('clicked_opt');$(this).children().addClass('clicked_opt');});}});}
function query_for_stock(query_type)
{if(query_type=="with_size"||query_type=="with_color")
{$.ajax({type:'ajax',url:base_url+"customer/getStock/"+categ_page+"/"+opt_color_id+"/"+opt_size_id,async:true,dataType:'json',success:function(data)
{if(data=="No Such Product")
{$('#stocks_on_hand').text("Stock On Hand : 0");p_quantity=0;$("#p_quantity_row").prop('disabled',true);$("#p_quantity_row").children().prop('disabled',true);$('#add_to_cart_vw').prop('disabled',true);$('#p_quantity').prop('disabled',true);$('#spinner_up').prop('disabled',true);$('#spinner_down').prop('disabled',true);}
else
{$('#stocks_on_hand').text("Stock On Hand : "+data[0].quantity);let stringer=$('#add_to_cart_vw').attr('onclick');let stringed=stringer.replace("addProductToCart('"+categ_page+"',","");let will_stringed=stringed.replace(')','');let int_qnty=parseInt(will_stringed);categ_page=data[0].sku;$('#add_to_cart_vw').attr('onclick',"addProductToCart('"+categ_page+"',"+int_qnty+')');p_quantity=data[0].quantity;$("#p_quantity_row").prop('disabled',false);$("#p_quantity_row").children().prop('disabled',false);$('#add_to_cart_vw').prop('disabled',false);$('#p_quantity').prop('disabled',false);$('#spinner_up').prop('disabled',false);$('#spinner_down').prop('disabled',false);if(data[0].discount_percent=="0")
{$('#cart-list-span-counter').remove();$('#product_orig_price_id').remove();$('#product_price_id').show();$('#product_price_id').empty();$('#product_price_id').append('&#8369; '+data[0].selling_price);$('.product_view_price_before').remove();}
else
{let new_price=parseFloat(data[0].selling_price-((data[0].discount_percent/100)*data[0].selling_price)).toFixed(2);$('#product_pic').append('<span class="label-count" id="cart-list-span-counter"></span>');$('#cart-list-span-counter').empty();$('#cart-list-span-counter').append(data[0].discount_percent+'% OFF');$('#cart-list-span-counter').show();$('#product_price_id').show();$('#product_price_id').empty();$('#product_price_id').append('&#8369; '+new_price);$('#product_price_id').after('<h2 class="product_view_price_before font-line-through" id="product_orig_price_id">&#8369; '+data[0].selling_price+'</h2>');}}}});}
else
{$.ajax({type:'ajax',url:base_url+"customer/getStock/"+categ_page+"/"+opt_color_id,async:true,dataType:'json',success:function(data)
{if(data['quantity']==0)
{$('#stocks_on_hand').text("Stock On Hand : 0");p_quantity=0;$('#p_quantity_row').hide();}
else
{let stringer=$('#add_to_cart_vw').attr('onclick');let stringed=stringer.replace("addProductToCart('"+categ_page+"',","");let will_stringed=stringed.replace(')','');let int_qnty=parseInt(will_stringed);categ_page=data['sku'];$('#add_to_cart_vw').attr('onclick',"addProductToCart('"+categ_page+"',"+int_qnty+')');$('#stocks_on_hand').text("Stock On Hand : "+data['quantity']);p_quantity=data['quantity'];$('#p_quantity_row').show();}}});}}
$(document).ready(function(){$('#spinner_up').click(function()
{let stringer=$('#add_to_cart_vw').attr('onclick');let stringed=stringer.replace("addProductToCart('"+categ_page+"',",'');let will_stringed=stringed.replace(')','');let int_qnty=parseInt(will_stringed);if(p_quantity==0)
{document.getElementById('p_quantity').value=0;}
else
{if(int_qnty==p_quantity)
{swal({title:'Error!',text:"The quantity you selected exceeds the stock on hand!",type:'error',showCancelButton:true,});$('#add_to_cart_vw').attr('onclick',"addProductToCart('"+categ_page+"',"+p_quantity+')');document.getElementById('p_quantity').value=p_quantity-1;}
else
{int_qnty=int_qnty+1;$('#add_to_cart_vw').attr('onclick',"addProductToCart('"+categ_page+"',"+int_qnty+')');document.getElementById('p_quantity').value=int_qnty-1;}}});$('#spinner_down').click(function()
{let stringer=$('#add_to_cart_vw').attr('onclick');let stringed=stringer.replace("addProductToCart('"+categ_page+"',",'');let will_stringed=stringed.replace(')','');let int_qnty=parseInt(will_stringed);if(int_qnty==1)
{int_qnty=1;}
else
{int_qnty=int_qnty-1;}
$('#add_to_cart_vw').attr('onclick',"addProductToCart('"+categ_page+"',"+int_qnty+')');});});$('#add_to_cart_vw').attr('onclick',"addProductToCart('"+categ_page+"',1)");check_if_product_has_size_or_color(categ_page);$('#cart-list-span-counter').empty();$('#product_orig_price_id').empty();$('#product_price_id').empty();$('#cart-list-span-counter').remove();$('#product_orig_price_id').remove();getProductDetailedInfo(categ_page);