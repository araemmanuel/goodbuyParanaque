$('#search_bar_close').click(function(){$('#suggestion_id').hide(100);});$('#search_bar_open').click(function(){$('#suggestion_id').show(100);});$('#suggestion_id').hide();$('#categ_trigger').click(function(){$('.post-nav-bar').slideToggle(100);});$('#input_search').keyup(function(e){if(!e)e=window.event;var keyCode=e.keyCode;if(keyCode===13){window.location.href=base_url+'customer/search/'+$(this).val();}});function create_bread_crumbs(type_of_page,sub_categ,categ)
{let bread_crumb='<ol class="breadcrumb breadcrumb-col-green"><li><a href="'+base_url+'customer" >Home</a></li>';let paths=window.location.href.split('/');for(let cntr_path=0;cntr_path<paths.length;cntr_path++)
{if(paths[cntr_path]=="http"||paths[cntr_path]=="https"||paths[cntr_path]==""||paths[cntr_path]=="localhost"||paths[cntr_path]=="goodBuy1"||paths[cntr_path]=="customer")
{continue;}
else
{if(paths[cntr_path]=="view_product")
{bread_crumb=bread_crumb+'<li><a href="'+base_url+'customer/shop_categ/'+categ+'" >'+categ+'</a></li><li><a href="'+base_url+'customer/shop_sub_categ/'+sub_categ+'" >'+sub_categ+'</a></li>';}}}
bread_crumb=bread_crumb+"</ol>";return bread_crumb;}
function filter(text_from,text_to)
{let result_numbers=0;let number_of_brands=0;let brands=[];$('.chk-col-green:checked').each(function(){brands[number_of_brands]=$(this).val();number_of_brands++;});if(number_of_brands==0)
{$('.items_filter').each(function(){if(parseFloat($(this).data("price"))<parseFloat(text_from)||parseFloat($(this).data("price"))>parseFloat(text_to))
{$(this).hide('100');}
else
{$(this).show('100');result_numbers++;$('#header_code').empty();$('#header_code').append('Showing '+result_numbers+' Items');}});}
else if(number_of_brands==1)
{$('.items_filter').each(function(){if(brands[0]!=$(this).data("brand")||parseFloat($(this).data("price"))<parseFloat(text_from)||parseFloat($(this).data("price"))>parseFloat(text_to))
{$(this).hide('100');}
else
{$(this).show('100');result_numbers++;$('#header_code').empty();$('#header_code').append('Showing '+result_numbers+' Items');}});}
else if(number_of_brands>1)
{$('.items_filter').each(function(){if(brands.indexOf($(this).data("brand"))<0||parseFloat($(this).data("price"))<parseFloat(text_from)||parseFloat($(this).data("price"))>parseFloat(text_to))
{$(this).hide('100');}
else
{$(this).show('100');result_numbers++;$('#header_code').empty();$('#header_code').append('Showing '+result_numbers+' Items');}});}
if(result_numbers==0)
{$('#header_code').empty();$('#header_code').append('No Items');}}
function load_categories()
{$('.post-nav-bar').slideToggle(100);$.ajax({type:'ajax',url:base_url+'customer/getCategories',async:true,dataType:'json',success:function(data)
{let i;let to_be_append='';let prev_cat_name='';for(i=0;i<data.length;i++)
{if((data[i].cat_name!=prev_cat_name)&&i==0)
{to_be_append+='<div class="col-lg-2 col-sm-4 col-xs-6"><ul><li class="categ_name"><a href="'+base_url+'customer/shop_categ/'+data[i].cat_name+'">'+data[i].cat_name+'</a></li><li><ul class="categ_subcateg_list"><li><a class="pre-nav-header-link col-green" href="'+base_url+'customer/shop_sub_categ/'+data[i].subcat_name+'">'+data[i].subcat_name+'</a></li>';prev_cat_name=data[i].cat_name;}
else if((data[i].cat_name!=prev_cat_name)&&i>=1)
{to_be_append+='</ul></li></ul></div><div class="col-lg-2 col-sm-4 col-xs-6"><ul><li class="categ_name"><a href="'+base_url+'customer/shop_categ/'+data[i].cat_name+'">'+data[i].cat_name+'</a></li><li><ul class="categ_subcateg_list"><li><a class="pre-nav-header-link col-green" href="'+base_url+'customer/shop_sub_categ/'+data[i].subcat_name+'">'+data[i].subcat_name+'</a></li>';prev_cat_name=data[i].cat_name;}
else if(data[i].cat_name==prev_cat_name)
{to_be_append+='<li><a class="pre-nav-header-link col-green" href="'+base_url+'customer/shop_sub_categ/'+data[i].subcat_name+'">'+data[i].subcat_name+'</a></li>';}}
to_be_append+='</ul></li></ul></div>';$('#categ_subcateg_nav').append(to_be_append);}});}
load_categories();String.prototype.trunc=function(n){return this.substr(0,n-1)+(this.length>n?'&hellip;':'');}
var cookieList=function(cookieName){var cookie=$.cookie(cookieName);var items=cookie?cookie.split(/,/):new Array();return{"add":function(val){items.push(val);$.cookie(cookieName,items.join(','),{path:'/'});},"remove":function(val){indx=items.indexOf(val);if(indx!=-1)items.splice(indx,1);$.cookie(cookieName,items.join(','),{path:'/'});},"clear":function(){items="";$.cookie(cookieName,"",{path:'/'});},"edit":function(val,quantity,prod_id)
{indx=items.indexOf(val);var item_info=items[indx];var item=item_info.split(":");var new_quantity=parseInt(item[1])+parseInt(quantity);items[indx]=prod_id+":"+new_quantity;$.cookie(cookieName,items.join(','),{path:'/'});},"for(let i = 0":function(val,quantity,prod_id)
{indx=items.indexOf(val);var item_info=items[indx];var item=item_info.split(":");var new_quantity=parseInt(quantity);items[indx]=prod_id+":"+new_quantity;$.cookie(cookieName,items.join(','),{path:'/'});},"items":function(){return items;}}}
var cart_list=new cookieList("cartItems");$('#login_btn').click(function(e)
{e.preventDefault();var login_data=$("#logged_in_form").serialize();$.ajax({type:'ajax',url:base_url+'customer/login',async:true,data:login_data,method:'POST',success:function(data)
{if(data=='"success"')
{location.reload();}
else
{$('#login_username_label').empty();$('#login_username_label').append('<p style="margin-left: 2rem !important" >Invalid Username or Password</p><br/>');}}})})
function post_categ()
{$.ajax({type:'ajax',url:base_url+'customer/getCategories',async:true,dataType:'json',success:function(data)
{$('#categories_dropdown').empty();var categories="";data.forEach(function(element)
{if(element['cat_name']!=categories)
{var temp="";temp=temp+'<li class="has-children"><a href="http://codyhouse.co/?p=748">'+element['cat_name']+'</a>';temp=temp+'<ul class="cd-secondary-dropdown is-hidden">';temp=temp+'<li class="go-back"><a href="#0">Menu</a></li>';temp=temp+'<li class="see-all"><a href="http://codyhouse.co/?p=748">All '+element['cat_name']+'</a></li><li class="has-children"><a href="http://codyhouse.co/?p=748">'+element['cat_name']+'</a><ul class="is-hidden">';temp=temp+'<li class="go-back"><a href="#0"'+element['cat_name']+'</a></li>';temp=temp+'<li class="see-all"><a href="http://codyhouse.co/?p=748">All '+element['cat_name']+'</a></li>';temp=temp+'<li class="has-children"><a href="#0">'+element['subcat_name']+'</a><ul class="is-hidden">';temp=temp+'<li class="go-back"><a href="#0">Flats</a></li></ul></li></ul></li></ul></li>';$('#categories_dropdown').append(temp);categories=element['cat_name'];}
else
{}});},error:function()
{}})}
function showAllProducts()
{$.ajax({type:'ajax',url:base_url+'customer/getProducts',async:true,dataType:'json',success:function(data)
{var i;var values_to_append='';for(i=0;i<data.length;i++)
{if(data[i].discount_percent=="0")
{$('#product_view_holder').append('<div class="card card-product"><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img alt="'+data[i].name+'" src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : '+data[i].quantity+' </h5></a></div></div>');}
else
{let new_price=parseFloat(data[i].selling_price-((data[i].discount_percent/100)*data[i].selling_price)).toFixed(2);$('#product_view_holder').append('<div class="card card-product"><span class="label-count" id="cart-list-span">'+data[i].discount_percent+'% OFF</span><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img alt="'+data[i].name+'" src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4 class="before-price font-line-through">&#8369; '+data[i].selling_price+'</h4><h4>&#8369; '+new_price+'</h4><h5>Stock Available : '+data[i].quantity+'</h5></a></div></div>');}}
$('.owl-carousel').owlCarousel({loop:true,margin:10,responsiveClass:true,nav:false,responsive:{0:{items:2},100:{items:2},600:{items:4},1000:{items:7}}});},error:function()
{}});$.ajax({type:'ajax',url:base_url+'customer/getProducts_Per_Categ',async:true,dataType:'json',success:function(data)
{let prev_categ='';let temp_arr=[];let cntr_prod=0;let cntr_categ=0;for(let i=0;i<data.length;i++)
{if(prev_categ==data[i].cat_name)
{cntr_prod++;}
else
{if(i==0)
{cntr_prod++;prev_categ=data[i].cat_name;}
else
{if(cntr_prod>7)
{temp_arr[cntr_categ]=prev_categ;cntr_prod=0;cntr_categ++;}}}}
let prev_categ_name="";var i;var values_to_append='';let color_bg="bg-gradient-light-grey";for(i=0;i<data.length;i++)
{if(temp_arr.indexOf(data[i].cat_name)!=-1)
{if(prev_categ_name!=data[i].cat_name&&i==0)
{$('#new_items_categ').after('<div class="'+color_bg+'" id="categ_list_'+data[i].cat_name+'"><div class="row main-body-products"><div class="col-lg-12 product-row"><h3>POPULAR ITEMS THIS MONTH</h3><h1>'+data[i].cat_name+"'s Category"+'</h1><div class="row"><div class="owl-carousel owl-theme" id="categ_owl_'+data[i].cat_name+'"></div></div></div></div></div>');if(data[i].discount_percent=="0")
{$('#categ_owl_'+data[i].cat_name).append('<div class="card card-product"><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img alt="'+data[i].name+'" src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : '+data[i].quantity+' </h5></a></div></div>');}
else
{let new_price=parseFloat(data[i].selling_price-((data[i].discount_percent/100)*data[i].selling_price)).toFixed(2);$('#categ_owl_'+data[i].cat_name).append('<div class="card card-product"><span class="label-count" id="cart-list-span">'+data[i].discount_percent+'% OFF</span><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img alt="'+data[i].name+'" src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4 class="before-price font-line-through">&#8369; '+data[i].selling_price+'</h4><h4>&#8369; '+new_price+'</h4><h5>Stock Available : '+data[i].quantity+'</h5></a></div></div>');}
prev_categ_name=data[i].cat_name;}
else if(prev_categ_name!=data[i].cat_name&&i>=1)
{if(color_bg=="bg-gradient-light-grey")
{color_bg="bg-gradient-light-grey-l1";}
else
{color_bg="bg-gradient-light-grey";}
$('#categ_list_'+prev_categ_name).after('<div class="'+color_bg+'" id="categ_list_'+data[i].cat_name+'"><div class="row main-body-products"><div class="col-lg-12 product-row"><h3>POPULAR ITEMS THIS MONTH</h3><h1>'+data[i].cat_name+"'s Category"+'</h1><div class="row"><div class="owl-carousel owl-theme" id="categ_owl_'+data[i].cat_name+'"></div></div></div></div></div>');if(data[i].discount_percent=="0")
{$('#categ_owl_'+data[i].cat_name).append('<div class="card card-product"><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img alt="'+data[i].name+'" src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : '+data[i].quantity+' </h5></a></div></div>');}
else
{let new_price=parseFloat(data[i].selling_price-((data[i].discount_percent/100)*data[i].selling_price)).toFixed(2);$('#categ_owl_'+data[i].cat_name).append('<div class="card card-product"><span class="label-count" id="cart-list-span">'+data[i].discount_percent+'% OFF</span><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img alt="'+data[i].name+'" src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4 class="before-price font-line-through">&#8369; '+data[i].selling_price+'</h4><h4>&#8369; '+new_price+'</h4><h5>Stock Available : '+data[i].quantity+'</h5></a></div></div>');}
$('#categ_owl_'+prev_categ_name).owlCarousel({loop:true,margin:10,responsiveClass:true,nav:false,responsive:{0:{items:2},100:{items:2},600:{items:4},1000:{items:7}}});prev_categ_name=data[i].cat_name;}
else if(data[i].cat_name==prev_categ_name)
{if(data[i].discount_percent=="0")
{$('#categ_owl_'+data[i].cat_name).append('<div class="card card-product"><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img alt="'+data[i].name+'" src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : '+data[i].quantity+' </h5></a></div></div>');}
else
{let new_price=parseFloat(data[i].selling_price-((data[i].discount_percent/100)*data[i].selling_price)).toFixed(2);$('#categ_owl_'+data[i].cat_name).append('<div class="card card-product"><span class="label-count" id="cart-list-span">'+data[i].discount_percent+'% OFF</span><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img alt="'+data[i].name+'" src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4 class="before-price font-line-through">&#8369; '+data[i].selling_price+'</h4><h4>&#8369; '+new_price+'</h4><h5>Stock Available : '+data[i].quantity+'</h5></a></div></div>');}}}}
$('#categ_owl_'+prev_categ_name).owlCarousel({loop:true,margin:10,responsiveClass:true,nav:false,responsive:{0:{items:2},100:{items:2},600:{items:4},1000:{items:7}}});},error:function()
{}});}
function get_categs_with_prod(data)
{let prev_categ='';let temp_arr=[];let cntr=0;let cntr_categ=0;for(let i=0;i<data.length;i++)
{if(prev_categ==data[i].categ_name)
{cntr++;}
else
{if(i==0)
{cntr++;prev_categ=data[i].categ_name;}
else
{if(cntr>7)
{temp_arr[cntr_categ]=prev_categ;cntr=0;cntr_categ++;}}}}
return temp_arr;}
$('.categ_nav_item').click(function(){e.preventDefault();});$('#btn_submit_sign').click(function(e){e.preventDefault();var sign_up_data=$('#submit_sign_up_form').serialize();$.ajax({type:'ajax',method:'POST',url:base_url+'customer/sign_up_validate',data:sign_up_data,async:true,error:function(request,error)
{},success:function(msg_errors)
{if(msg_errors=='accepted')
{swal({title:'Successfully Signed Up',text:"You can now order without filling the checkout form everytime you order!",type:'success',showCancelButton:false,confirmButtonColor:'#3085d6',confirmButtonText:'View Orders',confirmButtonClass:'btn btn-success',buttonsStyling:false,reverseButtons:true});$('.confirm').click(function(){window.location.replace(base_url+'customer/login_after/'+$('#sign_up_username_id').val());});}
else if(msg_errors=='username_not_accepted')
{$('#sign_up_username_label').empty();$('#sign_up_username_label').append("<p>The username is already taken!</p>");}
else if(msg_errors=='Username already taken!')
{$('#sign_up_username_label').empty();$('#sign_up_username_label').append(msg_errors);}
else
{var parse_erros=JSON.parse(msg_errors);$.each(parse_erros,function(key,value){$('#'+key+"_label").empty();});$.each(parse_erros,function(key,value){$('#'+key+"_label").append(value);});}}});});$("#place_order_btn").click(function(){var data=$("#place_order_frm").serialize();$.ajax({type:'ajax',method:'POST',url:base_url+'customer/place_order',data:data,async:true,error:function(request,error)
{},success:function(msg_errors)
{if(msg_errors=='accepted')
{cart_list.clear();load_cart_items();$('#cart-list-span').empty();$('#cart-list-span').append("0");swal({title:'Order Placed',text:"We've sent some confirmation message in your email, check it out!",type:'success',showCancelButton:false,confirmButtonColor:'#3085d6',confirmButtonText:'View Orders',confirmButtonClass:'btn btn-success',buttonsStyling:false,reverseButtons:true});$('.confirm').click(function(){window.location.replace(base_url+'customer/view_orders');});}
else{var parse_erros=JSON.parse(msg_errors);$.each(parse_erros,function(key,value){$('#'+key+"_label").empty();});$.each(parse_erros,function(key,value){$('#'+key+"_label").append(value);});}}});});function load_products_categ(category,url_url)
{$('#banner_code').append('<h2>'+category+"'s "+' Category</h2><ol class="breadcrumb breadcrumb-col-green"><li><a href="'+base_url+'customer" >Home</a></li><li><a href="'+base_url+'customer/shop_categ/'+category+'" >'+category+'</a></li></ol>');$.ajax({type:'ajax',url:url_url+"customer/getProducts_Categ/"+category,async:true,dataType:'json',success:function(data)
{$('#header_code').empty();$('#header_code').append('Showing '+data.length+' Items');$('#body_categ').empty();for(i=0;i<data.length;i++)
{if(data[i].discount_percent=="0")
{$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="'+data[i].selling_price+'" data-brand="'+data[i].brand+'" data-is-sale="false"><div class="card card-product"><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : 3 </h5></a></div></div></div>');}
else
{let new_price=parseFloat(data[i].selling_price-((data[i].discount_percent/100)*data[i].selling_price)).toFixed(2);$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="'+new_price+'" data-brand="'+data[i].brand+'" data-is-sale="true"><div class="card card-product" ><span class="label-count" id="cart-list-span">'+data[i].discount_percent+'% OFF</span><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4 class="before-price font-line-through">&#8369; '+data[i].selling_price+'</h4><h4>&#8369; '+new_price+'</h4><h5>Stock Available : 3 </h5></a></div></div></div>');}}},error:function()
{}});$.ajax({type:'ajax',url:url_url+"customer/get_product_brands/"+category,async:true,dataType:'json',success:function(data)
{for(let cntr=0;cntr<data.length;cntr++)
{$('#checkbox-list-brands').append('<li><input type="checkbox" id="md_checkbox_'+data[cntr].brand+'" class="filled-in chk-col-green" value="'+data[cntr].brand+'"  /><label for="md_checkbox_'+data[cntr].brand+'">'+data[cntr].brand+'</label></li>');}
$('.chk-col-green').click(function(){var slider=document.getElementById('nouislider_range_example');let val=slider.noUiSlider.get();let val_string=new String(val);let val_stringed=val_string.split(",");$(slider).parent().find('span.js-nouislider-value-from').text(val_stringed[0]);$(slider).parent().find('span.js-nouislider-value-to').text(val_stringed[1]);filter(val_stringed[0],val_stringed[1]);});}});$.ajax({type:'ajax',url:url_url+"customer/get_sub_categ/"+category,async:true,dataType:'json',success:function(data)
{for(let cntr=0;cntr<data.length;cntr++)
{$('#subcategory-list-subcateg').append('<a href="'+base_url+'customer/shop_sub_categ/'+data[cntr].subcat_name+'"><li>'+data[cntr].subcat_name+'</li></a>');}}});}
function load_products_search(category,url_url)
{let item_brands=[];$.ajax({type:'ajax',url:url_url+"customer/search_products/"+category,async:true,dataType:'json',success:function(data)
{if(data!="no")
{$('#body_categ').empty();let item_brands_cntr=0;let prev_sku="";let item=0;for(i=0;i<data.length;i++)
{if(prev_sku=="")
{if(data[i].discount_percent=="0")
{$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="'+data[i].selling_price+'" data-brand="'+data[i].brand+'" data-is-sale="false"><div class="card card-product"><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : 3 </h5></a></div></div></div>');}
else
{let new_price=parseFloat(data[i].selling_price-((data[i].discount_percent/100)*data[i].selling_price)).toFixed(2);$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="'+new_price+'" data-brand="'+data[i].brand+'" data-is-sale="true"><div class="card card-product" ><span class="label-count" id="cart-list-span">'+data[i].discount_percent+'% OFF</span><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : 3 </h5></a></div></div></div>');}
prev_sku=data[i].sku;item++;}
else if(prev_sku!=data[i].sku)
{if(data[i].discount_percent=="0")
{$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="'+data[i].selling_price+'" data-brand="'+data[i].brand+'" data-is-sale="false"><div class="card card-product"><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : 3 </h5></a></div></div></div>');}
else
{let new_price=parseFloat(data[i].selling_price-((data[i].discount_percent/100)*data[i].selling_price)).toFixed(2);$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="'+new_price+'" data-brand="'+data[i].brand+'" data-is-sale="true"><div class="card card-product" ><span class="label-count" id="cart-list-span">'+data[i].discount_percent+'% OFF</span><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : 3 </h5></a></div></div></div>');}
prev_sku=data[i].sku;item++;}
else
{}
if(item_brands.indexOf(data[i].brand)<0&&(data[i].brand!="No Brand"&&data[i].brand!="No brand"&&data[i].brand!="no brand"))
{item_brands[item_brands_cntr]=data[i].brand;$('#checkbox-list-brands').append('<li><input type="checkbox" id="md_checkbox_'+item_brands[item_brands_cntr]+'" class="filled-in chk-col-green" value="'+item_brands[item_brands_cntr]+'"  /><label for="md_checkbox_'+item_brands[item_brands_cntr]+'">'+item_brands[item_brands_cntr]+'</label></li>');item_brands_cntr++;$('.chk-col-green').click(function(){var slider=document.getElementById('nouislider_range_example');let val=slider.noUiSlider.get();let val_string=new String(val);let val_stringed=val_string.split(",");$(slider).parent().find('span.js-nouislider-value-from').text(val_stringed[0]);$(slider).parent().find('span.js-nouislider-value-to').text(val_stringed[1]);filter(val_stringed[0],val_stringed[1]);});}}
$('#header_code').empty();$('#header_code').append('Showing '+item+' Items');}
else
{$('#filter_rows').remove();$('#body_categ').empty();$('#body_categ').append("<h1 class="+'"col-black"'+' style="font-size: 26px !important; margin: 1.5rem !important;">Ooops, it seems like we still '+"don't have that product yet.</h1>");}},error:function()
{}});let categories=category.split("%20").join(" ");$('#banner_code').append('<h2>"'+categories+'" Results</h2> <ol class="breadcrumb breadcrumb-col-green"><li><a href="'+base_url+'customer" >Home</a></li></ol>');}
function load_products_sub_categ(category,url_url)
{$.ajax({type:'ajax',url:url_url+"customer/getProducts_SubCateg/"+category,async:true,dataType:'json',success:function(data)
{$('#header_code').empty();$('#header_code').append('Showing '+data.length+' Items');$('#body_categ').empty();for(i=0;i<data.length;i++)
{if(i==0)
{$('#banner_code').append('<h2>'+category+"'s "+' Subcategory</h2><ol class="breadcrumb breadcrumb-col-green"><li><a href="'+base_url+'customer" >Home</a></li><li><a href="'+base_url+'customer/shop_categ/'+data[0].cat_name+'" >'+data[0].cat_name+'</a></li><li><a href="'+base_url+'customer/shop_categ/'+category+'" >'+category+'</a></li></ol>');}
if(data[i].discount_percent=="0")
{$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="'+data[i].selling_price+'" data-brand="'+data[i].brand+'" data-is-sale="false"><div class="card card-product"><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4>&#8369; '+data[i].selling_price+'</h4><h5>Stock Available : 3 </h5></a></div></div></div>');}
else
{let new_price=parseFloat(data[i].selling_price-((data[i].discount_percent/100)*data[i].selling_price)).toFixed(2);$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="'+new_price+'" data-brand="'+data[i].brand+'" data-is-sale="true"><div class="card card-product" ><span class="label-count" id="cart-list-span">'+data[i].discount_percent+'% OFF</span><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].sku+'" class="product_link"><img src="'+base_url+data[i].img_file_path+'"><p>'+data[i].name.trunc(15)+'</p><h4 class="before-price font-line-through">&#8369; '+data[i].selling_price+'</h4><h4>&#8369; '+new_price+'</h4><h5>Stock Available : 3 </h5></a></div></div></div>');}}},error:function()
{}});$.ajax({type:'ajax',url:url_url+"customer/get_product_brands_sub/"+category,async:true,dataType:'json',success:function(data)
{for(let cntr=0;cntr<data.length;cntr++)
{$('#checkbox-list-brands').append('<li><input type="checkbox" id="md_checkbox_'+data[cntr].brand+'" class="filled-in chk-col-green" value="'+data[cntr].brand+'"  /><label for="md_checkbox_'+data[cntr].brand+'">'+data[cntr].brand+'</label></li>');}
$('.chk-col-green').click(function(){var slider=document.getElementById('nouislider_range_example');let val=slider.noUiSlider.get();let val_string=new String(val);let val_stringed=val_string.split(",");$(slider).parent().find('span.js-nouislider-value-from').text(val_stringed[0]);$(slider).parent().find('span.js-nouislider-value-to').text(val_stringed[1]);filter(val_stringed[0],val_stringed[1]);});}});}
function load_products_ordered(category,url_url)
{var items=cart_list.items();$.ajax({type:'ajax',url:url_url+"customer/getProducts_Categ/"+category,async:true,dataType:'json',success:function(data)
{$('#body_categ').empty();for(i=0;i<data.length;i++)
{if(data[i].discount_percent==null||data[i].discount_percent==0||data[i].discount_percent=="0")
{$('#body_categ').append(' <div class="col-lg-3"><div class="card card-product"><div class="body"><a href="'+base_url+'customer/view_product/'+data[i].prod_id+'" class="product_link"><img src="'+url_url+data[i].primary_image+'"><p>'+data[i].name+'</p><h4>&#8369; '+data[i].selling_price+'</h4></a><button type="button" class="btn btn-success btn-block btn-add-cart waves-effect" data-prod-prod_id="'+data[i].prod_id+'" data-toggle="modal" data-target="#largeModal" onclick="addProductToCart('+"'"+data[i].prod_id+"'"+',1);"><i class="material-icons">add_shopping_cart</i> Add to Cart</button><button type="button" class="btn bg-green btn-block btn-add-wish waves-effect" data-prod-prod_id="'+data[i].prod_id+'"><i class="material-icons">favorite_border</i> Add to Wishlist</button></div></div></div>');}}},error:function()
{}});}
var cart_list=new cookieList("cartItems");function addProductToCart(prod_id,quantity)
{var i=0;var items=cart_list.items();if(items.length>0)
{for(i=0;i<items.length;i++)
{var item_prod_id=items[i].split(":");if(item_prod_id[0]==prod_id)
{cart_list.edit(item_prod_id[0]+":"+item_prod_id[1],quantity,prod_id);break;}
else if(item_prod_id[0]!=prod_id&&i==items.length-1)
{cart_list.add(prod_id+":"+quantity);}}}
else
{cart_list.add(prod_id+":"+quantity);}
temp_product_all_subtotals=0;temp_product_all_quantities=0;load_modal_items();load_cart_items();}
var temp_product_all_subtotals;var temp_product_all_quantities;function load_modal_items()
{$("#order_modal_items").empty()
var i;var cart_items=cart_list.items();if(cart_items.length>0)
{var items=String(cart_items).split(",");for(i=0;i<items.length;i++)
{var item_split_info=items[i].split(":");getProductInfo(item_split_info[0],item_split_info[1],"modal",base_url);}}}
function load_cart_items(){$("#cart-list-nav").empty()
var i;var cart_items=cart_list.items();if(cart_items==null)
{}
else if(cart_items.length>0)
{var items=String(cart_items).split(",");for(i=0;i<items.length;i++)
{var item_split_info=items[i].split(":");getProductInfo(item_split_info[0],item_split_info[1],"navbar",base_url);}}}
function check_if_product_has_size_or_color(prod_id)
{$.ajax({type:'ajax',url:base_url+"customer/getProducts_Option/"+prod_id,async:true,dataType:'json',success:function(data)
{if(data=="has_sizes")
{$('#has_colors').remove();}
else if(data=="has_colors")
{$('#has_sizes').remove();}
else if(data=="has_colors_sizes")
{}
else if(data=="no_options")
{$('#has_colors').remove();$('#has_sizes').remove();}}});}
function load_cart_cart()
{var i;var cart_items=cart_list.items();if(cart_items.length>0)
{var items=String(cart_items).split(",");for(i=0;i<items.length;i++)
{var item_split_info=items[i].split(":");getProductInfo(item_split_info[0],item_split_info[1],"for_cart_view",base_url);}}}
function load_order_summary(){var i;var cart_items=cart_list.items();if(cart_items.length>0)
{var items=String(cart_items).split(",");for(i=0;i<items.length;i++)
{var item_split_info=items[i].split(":");getProductInfo(item_split_info[0],item_split_info[1],"for_checkout",base_url);}}}
function addProductToCart(prod_id,quantity)
{var i=0;var items=cart_list.items();if(items.length>0)
{for(i=0;i<items.length;i++)
{var item_prod_id=items[i].split(":");if(item_prod_id[0]==prod_id)
{cart_list.edit(item_prod_id[0]+":"+item_prod_id[1],quantity,prod_id);break;}
else if(item_prod_id[0]!=prod_id&&i==items.length-1)
{cart_list.add(prod_id+":"+quantity);}}}
else
{cart_list.add(prod_id+":"+quantity);}
temp_product_all_subtotals=0;temp_product_all_quantities=0;load_modal_items();load_cart_items();}