"use strict";

var p_quantity;
var prod_ofthis_name;

function search_all_related_items(prod_name, categ_page) {
	$.ajax({
		type: 'ajax',
		url: base_url + 'customer/search_products_related/' + prod_name + '/' + categ_page,
		async: true,
		dataType: 'json',
		success: function success(data) {
			var i;
			var values_to_append = '';
			if (data == 'no') {
				$('#product_rel_header').after('<h3 class="col-black" style="margin-top: -8rem !important; margin-bottom: 8rem !important;" >NO RELATED PRODUCTS.</h3>');
			} else {
				for (var _i = 0; _i < data.length; _i++) {
					if (data[_i].discount_percent == "0") {
						$('#owl_carousel_related_items').append('<div class="col-lg-2 col-xs-12 col-sm-12 col-md-2"><div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i].img_file_path + '"><p>' + data[_i].name.trunc(15) + '</p><h4>&#8369; ' + data[_i].selling_price + '</h4><h5>Stock Available : ' + data[_i].quantity + ' </h5></a></div></div></div>');
					} else {
						var new_price = parseFloat(data[_i].selling_price - data[_i].discount_percent / 100 * data[_i].selling_price).toFixed(2);
						$('#owl_carousel_related_items').append('<div class="col-lg-2 col-xs-12 col-sm-12 col-md-2"><div class="card card-product"><span class="label-count" id="cart-list-span">' + data[_i].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i].img_file_path + '"><p>' + data[_i].name.trunc(15) + '</p><h4 class="before-price font-line-through">&#8369; ' + data[_i].selling_price + '</h4><h4>&#8369; ' + new_price + '</h4><h5>Stock Available : ' + data[_i].quantity + '</h5></a></div></div></div>');
					}
				}
			}
			$('.owl-carousel').owlCarousel({
				loop: true,
				margin: 10,
				responsiveClass: true,
				nav: false,
				responsive: {
					0: {
						items: 2
					},
					100: {
						items: 2
					},
					600: {
						items: 4
					},
					1000: {
						items: 7
					}
				}
			});
		}
	});
	$('#sz_chart').hide();
	$('#vw_chart').click(function () {
		$('#sz_chart').fadeToggle();
	});
}

function getProductDetailedInfo(categ_page) {

	$.ajax({
		type: 'ajax',
		url: base_url + 'customer/getProductDetailed/' + categ_page,
		async: true,
		dataType: 'json',
		success: function success(data) {
			if (data === null) {
				$('#product_body').empty();
				$('#product_body').append('<div class="product_view_banner" id="product_view_b"><h2>No Product Exists!</h2></div>');
			} else {
				$('#sz_chart').hide();
				$('#men_chrt_h').hide();
				$('#men_chrt_tbl').hide();
				$('#wmen_chrt_h').hide();
				$('#wmen_chrt_tbl').hide();
				$('#un_chrt_h').hide();
				$('#un_chrt_tbl').hide();
				$('#brand_prod').text(data['brand']);
				$('#properties_prod').text(data['Properties']);
				$('#product_view_b').empty();
				var bread_crumb = create_bread_crumbs('Product', data['subcat_name'], data['cat_name']);
				if (data['cat_name'] == 'MEN') {
					$('#men_chrt_h').show();
					$('#men_chrt_tbl').show();
				} else if (data['cat_name'] == 'WOMEN') {
					$('#wmen_chrt_h').show();
					$('#wmen_chrt_tbl').show();
				} else if (data['cat_name'] == 'UNISEX') {
					$('#un_chrt_h').show();
					$('#un_chrt_tbl').show();
				} else {
					$('#vw_chart').hide();
				}
				prod_ofthis_name = data['name'];
				$('#product_rel_header').empty();
				$('#product_rel_header').append(prod_ofthis_name);
				search_all_related_items(prod_ofthis_name, categ_page);
				$('#product_view_b').append('<h2>' + data['name'] + '</h2>' + bread_crumb);
				if (data['img_file_path'] === null) {
					$('#product_pic_holder').attr('src', base_url + 'assets/images/no-photo.jpg');
				} else {
					$('#product_pic_holder').attr('src', base_url + data['img_file_path']);
				}
				$('#stocks_on_hand').text("Stock On Hand : " + data['quantity']);
				p_quantity = data['quantity'];
				if (data['discount_percent'] == "0") {
					$('#cart-list-span-counter').remove();
					$('#product_orig_price_id').remove();
					$('#product_price_id').show();
					$('#product_price_id').append('&#8369; ' + data['selling_price']);
				} else {
					var new_price = parseFloat(data['selling_price'] - data['discount_percent'] / 100 * data['selling_price']).toFixed(2);
					$('#product_pic').append('<span class="label-count" id="cart-list-span-counter"></span>');
					$('#cart-list-span-counter').append(data['discount_percent'] + '% OFF');
					$('#cart-list-span-counter').show();
					$('#product_price_id').show();
					$('#product_price_id').append('&#8369; ' + new_price);
					$('#product_price_id').after('<h2 class="product_view_price_before font-line-through" id="product_orig_price_id">&#8369; ' + data['selling_price'] + '</h2>');
				}
				$('#prod_desc').empty();
				$('#prod_desc').append("<p>" + data['description'] + "</p>");
			}
		}
	});
	$('#hip_field').hide();
	$('#waist_field').hide();
	$('#inseam_field').hide();
	$('#chest_field').hide();
	$('#body_length_field').hide();
	$('#shoulder_length_field').hide();
	$('#shoulder_hole_field').hide();
	$('#has_size_calculations').hide();
	/*var sizes =[];
 $.ajax({
 	type: 'ajax',
        url: base_url + 'customer/get_option_sizes_with_measures/' + categ_page,
        async: true,
        dataType: 'json',
        success: function(data)
        {
 		if(data === null || data == null || data == undefined || data == [] || data.length == 0)
 		{
 			
 		}
 		else
 		{
 			if($('#has_sizes').is(":visible"))
 			{
 				$('#has_size_calculations').show();
 				sizes = data;
 				data.forEach(function(element){
 					if(parseInt(element['hip_max_cm']) < 0 || parseInt(element['hip_min_cm']) > 0 ||element['hip_min_cm'] != null || element['hip_max_cm'] != null)
 					{
 						$('#hip_field').show();
 					}
 					else
 					{
 						$('input[name="calculate_hips"]').val(element['hip_min_cm']);
 					}
 					if(parseInt(element['waist_max_cm']) < 0 || parseInt(element['waist_min_cm']) > 0 ||element['waist_min_cm'] != null || element['waist_max_cm'] != null)
 					{
 						//alert(parseInt(element['waist_max_cm']));
 						$('#waist_field').show();
 					}
 					else
 					{
 						$('input[name="calculate_waist"]').val(element['waist_min_cm']);
 					}
 					if(parseInt(element['inseam_max_cm']) < 0 || parseInt(element['inseam_min_cm']) > 0 ||element['inseam_min_cm'] != null || element['inseam_max_cm'] != null)
 					{
 						$('#inseam_field').show();
 					}
 					else
 					{
 						$('input[name="calculate_inseam"]').val(element['inseam_min_cm']);
 					}
 					if(parseInt(element['chest_max_cm']) < 0 || parseInt(element['chest_min_cm']) > 0 ||element['chest_min_cm'] != null || element['chest_max_cm'] != null)
 					{
 						$('#chest_field').show();
 					}
 					else
 					{
 						$('input[name="calculate_chest"]').val(element['chest_min_cm']);
 					}
 					if(parseInt(element['body_length_max_cm']) < 0 || parseInt(element['body_length_min_cm']) > 0 ||element['body_length_min_cm'] != null || element['body_length_max_cm'] != null)
 					{
 						$('#body_length_field').show();
 					}
 					else
 					{
 						$('input[name="calculate_body_length"]').val(element['body_length_min_cm']);
 					}
 					if(parseInt(element['shoulder_length_max_cm']) < 0 || parseInt(element['shoulder_length_min_cm']) > 0 ||element['shoulder_length_min_cm'] != null || element['shoulder_length_max_cm'] != null)
 					{
 						$('#shoulder_length_field').show();
 					}
 					else
 					{
 						$('input[name="calculate_shoulder_length"]').val(element['shoulder_hole_min_cm']);
 					}
 					if(parseInt(element['shoulder_hole_max_cm']) < 0 || parseInt(element['shoulder_hole_min_cm']) > 0 ||element['shoulder_hole_min_cm'] != null || element['shoulder_hole_max_cm'] != null) 
 					{
 						$('#shoulder_hole_field').show();
 					}
 					else
 					{
 						$('input[name="calculate_shoulder_hole"]').val(element['shoulder_hole_min_cm']);
 					}
 				});
 			}
 		}
 	}
 });
 $('#inch').click(function(){
 	$('input[name="mesure_system"]').val("inch");
 });
 $('#centimeter').click(function(){
 	$('input[name="mesure_system"]').val("centimeter");
 });
 $('#calculate_size_btn').click(function(){
 	alert($('input[name="mesure_system"]').val());
 	if($('input[name="mesure_system"]').val() == "inch")
 	{
 		let hips_i   = ($('input[name="calculate_hips"]').val() * 2.54).toFixed(0);
 		let waist_i  = ($('input[name="calculate_waist"]').val() * 2.54).toFixed(0);
 		let inseam_i = ($('input[name="calculate_inseam"]').val() * 2.54).toFixed(0);
 		let chest_i  = ($('input[name="calculate_chest"]').val() * 2.54).toFixed(0);
 		let body_length_i  = ($('input[name="calculate_body_length"]').val() * 2.54).toFixed(0);
 		let shoulder_length_i = ($('input[name="calculate_shoulder_length"]').val() * 2.54).toFixed(0);
 		let shoulder_hole_i  = ($('input[name="calculate_shoulder_hole"]').val() * 2.54).toFixed(0);
 		alert("Hips Calc = "+($('input[name="calculate_hips"]').val() * 2.54).toFixed(0));
 		if($('input[name="calculate_hips"]').val() == null || $('input[name="calculate_hips"]').val() == 0 )
 		{
 			hips_i = $('input[name="calculate_hips"]').val();
 		}
 		if($('input[name="calculate_waist"]').val() == null || $('input[name="calculate_waist"]').val() == 0 )
 		{
 			waist_i = $('input[name="calculate_waist"]').val();
 		}
 		if($('input[name="calculate_inseam"]').val() == null || $('input[name="calculate_inseam"]').val() == 0 )
 		{
 			inseam_i = $('input[name="calculate_inseam"]').val();
 		}
 		if($('input[name="calculate_chest"]').val() == null || $('input[name="calculate_chest"]').val() == 0 )
 		{
 			chest_i = $('input[name="calculate_chest"]').val();
 		}
 		if($('input[name="calculate_body_length"]').val() == null || $('input[name="calculate_body_length"]').val() == 0 )
 		{
 			body_length_i = $('input[name="calculate_body_length"]').val();
 		}
 		if($('input[name="calculate_shoulder_length"]').val() == null || $('input[name="calculate_shoulder_length"]').val() == 0 )
 		{
 			shoulder_length_i = $('input[name="calculate_shoulder_length"]').val();
 		}
 		if($('input[name="calculate_shoulder_hole"]').val() == null || $('input[name="calculate_shoulder_hole"]').val() == 0 )
 		{
 			shoulder_hole_i = $('input[name="calculate_shoulder_hole"]').val();
 		}
 		sizes.forEach(function(element){
 			//alert("Chest : " + chest_i + " Max : " + element['chest_max_cm'] + " Min : " + element['chest_min_cm']);
 			/*if((hips_i <= element['hip_max_cm'] && hips_i >= element['hip_min_cm']))
 			{
 				alert("Hips : " + hips_i + " Max : " + element['hip_max_cm'] + " Min : " + element['hip_min_cm']);
 				}
 			if(waist_i <= element['waist_max_cm'])
 			{
 				alert("Waist : " + waist_i + " Max : " + element['waist_max_cm']);
 			}
 			if(waist_i >= parseInt(element['waist_min_cm']))
 			{
 				alert("Waist : " + waist_i  + " Min : " + element['waist_min_cm']);
 			}
 			if(chest_i <= element['chest_max_cm'] && chest_i >= element['chest_min_cm'])
 			{
 				alert("Chest : " + chest_i + " Max : " + element['chest_max_cm'] + " Min : " + element['chest_min_cm']);
 			}
 			if(inseam_i <= element['inseam_max_cm'] && inseam_i >= element['inseam_min_cm'])
 			{
 				alert("Inseam : " + inseam_i + " Max : " + element['inseam_max_cm'] + " Min : " + element['inseam_min_cm']);
 			}
 			if(body_length_i <= element['body_length_max_cm'] && body_length_i >= element['body_length_min_cm'])
 			{
 				alert("Body Length : " + body_length_i + " Max : " + element['body_length_max_cm'] + " Min : " + element['body_length_min_cm']);
 			}
 			if(shoulder_length_i <= element['shoulder_length_max_cm'] && shoulder_length_i >= element['shoulder_length_min_cm'])
 			{
 				alert("Shoulder Length : " + shoulder_length_i + " Max : " + element['shoulder_length_max_cm'] + " Min : " + element['shoulder_length_min_cm']);
 			}
 			if(shoulder_hole_i <= element['shoulder_hole_max_cm'] && shoulder_hole_i >= element['shoulder_hole_min_cm'])
 			{
 				alert("Shoulder Hole : " + shoulder_hole_i + " Max : " + element['shoulder_hole_max_cm'] + " Min : " + element['shoulder_hole_min_cm']);
 			}
 			if((hips_i <= element['hip_max_cm'] && hips_i >= element['hip_min_cm'])  && (waist_i <= parseInt(element['waist_max_cm']) && waist_i >= parseInt(element['waist_min_cm'])) && (chest_i <= parseInt(element['chest_max_cm']) && chest_i >= parseInt(element['chest_min_cm'])) && (inseam_i <= element['inseam_max_cm'] && inseam_i >= element['inseam_min_cm']) && (body_length_i <= element['body_length_max_cm'] && body_length_i >= element['body_length_min_cm']) && (shoulder_length_i <= element['shoulder_length_max_cm'] && shoulder_length_i >= element['shoulder_length_min_cm']) && (shoulder_hole_i <= element['shoulder_hole_max_cm'] && shoulder_hole_i >= element['shoulder_hole_min_cm']))
 			{
 				sku = element['sku'];
 				alert(sku);
 				sku_options.forEach(function(element_1){
 					alert(element_1['sku']);
 					if(sku == element_1['sku'])
 					{
 						
 						if(element_1['opt_grp_name'] == "Size")
 						{
 							alert('Size matched');
 							$('.size-'+element_1['opt_name'].toLowerCase()).trigger("click");
 						}
 					}
 				});
 			}
 		});
 		
 	}
 	else if($('input[name="mesure_system"]').val() == "centimeter")
 	{
 		let hips_i   = parseInt($('input[name="calculate_hips"]').val());
 		let waist_i  = parseInt($('input[name="calculate_waist"]').val());
 		let inseam_i = parseInt($('input[name="calculate_inseam"]').val());
 		let chest_i  = parseInt($('input[name="calculate_chest"]').val());
 		let body_length_i  = parseInt($('input[name="calculate_body_length"]').val());
 		let shoulder_length_i = parseInt($('input[name="calculate_shoulder_length"]').val());
 		let shoulder_hole_i  = parseInt($('input[name="calculate_shoulder_hole"]').val());
 		let sku = 0;
 		if($('input[name="calculate_hips"]').val() == null || $('input[name="calculate_hips"]').val() == 0 )
 		{
 			hips_i = $('input[name="calculate_hips"]').val();
 		}
 		if($('input[name="calculate_waist"]').val() == null || $('input[name="calculate_waist"]').val() == 0 )
 		{
 			waist_i = $('input[name="calculate_waist"]').val();
 		}
 		if($('input[name="calculate_inseam"]').val() == null || $('input[name="calculate_inseam"]').val() == 0 )
 		{
 			inseam_i = $('input[name="calculate_inseam"]').val();
 		}
 		if($('input[name="calculate_chest"]').val() == null || $('input[name="calculate_chest"]').val() == 0 )
 		{
 			chest_i = $('input[name="calculate_chest"]').val();
 		}
 		if($('input[name="calculate_body_length"]').val() == null || $('input[name="calculate_body_length"]').val() == 0 )
 		{
 			body_length_i = $('input[name="calculate_body_length"]').val();
 		}
 		if($('input[name="calculate_shoulder_length"]').val() == null || $('input[name="calculate_shoulder_length"]').val() == 0 )
 		{
 			shoulder_length_i = $('input[name="calculate_shoulder_length"]').val();
 		}
 		if($('input[name="calculate_shoulder_hole"]').val() == null || $('input[name="calculate_shoulder_hole"]').val() == 0 )
 		{
 			shoulder_hole_i = $('input[name="calculate_shoulder_hole"]').val();
 		}
 		
 		sizes.forEach(function(element){
 			/*if((hips_i <= element['hip_max_cm'] && hips_i >= element['hip_min_cm']))
 			{
 				alert("Hips : " + hips_i + " Max : " + element['hip_max_cm'] + " Min : " + element['hip_min_cm']);
 				}
 			alert("Waist : " + waist_i + " Max : " + element['waist_max_cm'] + " Min : " + element['waist_min_cm']);
 			if(waist_i <= element['waist_max_cm'])
 			{
 				alert("Waist : " + waist_i + " Max : " + element['waist_max_cm']);
 			}
 			if(waist_i >= parseInt(element['waist_min_cm']))
 			{
 				alert("Waist : " + waist_i  + " Min : " + element['waist_min_cm']);
 			}
 			if(chest_i <= element['chest_max_cm'] && chest_i >= element['chest_min_cm'])
 			{
 				alert("Chest : " + chest_i + " Max : " + element['chest_max_cm'] + " Min : " + element['chest_min_cm']);
 			}
 			if(inseam_i <= element['inseam_max_cm'] && inseam_i >= element['inseam_min_cm'])
 			{
 				alert("Inseam : " + inseam_i + " Max : " + element['inseam_max_cm'] + " Min : " + element['inseam_min_cm']);
 			}
 			if(body_length_i <= element['body_length_max_cm'] && body_length_i >= element['body_length_min_cm'])
 			{
 				alert("Body Length : " + body_length_i + " Max : " + element['body_length_max_cm'] + " Min : " + element['body_length_min_cm']);
 			}
 			if(shoulder_length_i <= element['shoulder_length_max_cm'] && shoulder_length_i >= element['shoulder_length_min_cm'])
 			{
 				alert("Shoulder Length : " + shoulder_length_i + " Max : " + element['shoulder_length_max_cm'] + " Min : " + element['shoulder_length_min_cm']);
 			}
 			if(shoulder_hole_i <= element['shoulder_hole_max_cm'] && shoulder_hole_i >= element['shoulder_hole_min_cm'])
 			{
 				alert("Shoulder Hole : " + shoulder_hole_i + " Max : " + element['shoulder_hole_max_cm'] + " Min : " + element['shoulder_hole_min_cm']);
 			}
 			if((hips_i <= element['hip_max_cm'] && hips_i >= element['hip_min_cm'])  && (waist_i <= parseInt(element['waist_max_cm']) && waist_i >= parseInt(element['waist_min_cm'])) && (chest_i <= parseInt(element['chest_max_cm']) && chest_i >= parseInt(element['chest_min_cm'])) && (inseam_i <= element['inseam_max_cm'] && inseam_i >= element['inseam_min_cm']) && (body_length_i <= element['body_length_max_cm'] && body_length_i >= element['body_length_min_cm']) && (shoulder_length_i <= element['shoulder_length_max_cm'] && shoulder_length_i >= element['shoulder_length_min_cm']) && (shoulder_hole_i <= element['shoulder_hole_max_cm'] && shoulder_hole_i >= element['shoulder_hole_min_cm']))
 			{
 				sku = element['sku'];
 				alert(sku);
 				sku_options.forEach(function(element_1){
 					alert(element_1['sku']);
 					if(sku == element_1['sku'])
 					{
 						
 						if(element_1['opt_grp_name'] == "Size")
 						{
 							alert('Size matched');
 							$('.size-'+element_1['opt_name'].toLowerCase()).trigger("click");
 						}
 					}
 				});
 			}
 		});
 		
 		
 	}
 		});
 	*/
	var sku_options = [];
	var query_for_stock_type;
	$.ajax({
		type: 'ajax',
		url: base_url + 'customer/getOptions/' + categ_page,
		async: true,
		dataType: 'json',
		success: function success(data) {
			var i = void 0;
			var values_to_append = '';
			var prev_group = '';
			var prev_name = '';
			for (var _i2 = 0; _i2 < data.length; _i2++) {
				sku_options = data;
				if (data[_i2].opt_grp_name == "Color") {

					var var_opt_color_class = '.color-' + data[_i2].opt_name;
					var_opt_color_class = var_opt_color_class.replace(' ', '');
					if ($(var_opt_color_class).length > 0) {
						if (categ_page == data[_i2].sku) {
							$('#' + data[_i2].opt_id).addClass('clicked_opt');
							opt_color_id = data[_i2].opt_id;
						}
					} else {
						if (categ_page == data[_i2].sku) {
							$('#has_colors').append('<div class="product-pic-small color-' + data[_i2].opt_name.replace(' ', '') + '" ><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i2].img_file_path + '" class="color_options clicked_opt ' + data[_i2].opt_name.replace(' ', '') + '" data-image-holder="' + base_url + data[_i2].img_file_path + '" data-opt-id="' + data[_i2].opt_id + '" id="' + data[_i2].opt_id + '"/></div>');
							opt_color_id = data[_i2].opt_id;
						} else {
							$('#has_colors').append('<div class="product-pic-small color-' + data[_i2].opt_name.replace(' ', '') + '" ><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i2].img_file_path + '" class="color_options ' + data[_i2].opt_name.replace(' ', '') + '" data-image-holder="' + base_url + data[_i2].img_file_path + '" data-opt-id="' + data[_i2].opt_id + '" id="' + data[_i2].opt_id + '"/></div>');
						}
					}
				} else if (data[_i2].opt_grp_name == "Size") {
					var var_opt_size_class = '.size-' + data[_i2].opt_name.toLowerCase();
					if ($(var_opt_size_class).length) {} else {
						$('#has_sizes').append('<div class="product-pic-small size_option size-' + data[_i2].opt_name.toLowerCase() + '" data-size-label="' + data[_i2].opt_name + '" data-opt-id="' + data[_i2].opt_id + '"><p>' + data[_i2].opt_name + '</p></div>');
					}
					if (categ_page == data[_i2].sku) {
						opt_size_id = data[_i2].opt_id;
						var size = data[_i2].opt_name.toLowerCase();
						$('.size-' + size).show();
						$('.size-' + size).children().addClass('clicked_opt');
					}
				}
			}
			$('.color_options').click(function () {
				$('#product_pic_holder').attr('src', $(this).data('image-holder'));
				opt_color_id = $(this).data('opt-id');
				if ($('#has_sizes').is(":visible")) {
					query_for_stock_type = "with_size";
					query_for_stock(query_for_stock_type);
				} else {
					query_for_stock_type = "without_size";
					query_for_stock(query_for_stock_type);
				}
				$('.color_options').removeClass('clicked_opt');
				$(this).addClass('clicked_opt');
			});
			$('.size_option').click(function () {
				opt_size_id = $(this).data('opt-id');
				if ($('#has_colors').is(":visible")) {
					query_for_stock_type = "with_color";
					query_for_stock(query_for_stock_type);
				}
				$('.size_option').children().removeClass('clicked_opt');
				$(this).children().addClass('clicked_opt');
			});
		}
	});

	/*$.ajax({
 	type: 'ajax',
 	url: base_url + 'customer/getRelatedItems/' + categ_page,
 	async: true,
 	dataType: 'json',
 	success: function(data)
 	{
 		let i;
            let values_to_append = '';
 		let prev_group = '';
 		let prev_name = '';
            for(let i = 0; i<data.length;i++)
 		{
 			if(data[i].opt_grp_name == "Color")
 			{
 				
 				let var_opt_color_class = '.color-'+data[i].opt_name;
 				if($(var_opt_color_class).length)
 				{
 					
 				}
 				else
 				{
 					if(categ_page == data[i].sku)
 					{
 						$('#has_colors').append('<div class="product-pic-small color-'+data[i].opt_name+'" ><img alt="'+base_url+'assets/images/no-photo.jpg'+'" onerror="this.onerror=null;this.src=this.alt;" src="'+base_url+data[i].img_file_path+'" class="color_options clicked_opt" data-image-holder="'+base_url+data[i].img_file_path+'" data-opt-id="'+data[i].opt_id+'"/></div>');
 						opt_color_id = data[i].opt_id;
 					}
 					else
 					{
 						$('#has_colors').append('<div class="product-pic-small color-'+data[i].opt_name+'" ><img alt="'+base_url+'assets/images/no-photo.jpg'+'" onerror="this.onerror=null;this.src=this.alt;" src="'+base_url+data[i].img_file_path+'" class="color_options" data-image-holder="'+base_url+data[i].img_file_path+'" data-opt-id="'+data[i].opt_id+'"/></div>');
 					}
 				}
 				
 			}
 			else if(data[i].opt_grp_name == "Size")
 			{
 				let var_opt_size_class = '.size-'+data[i].opt_name.toLowerCase();
 				if($(var_opt_size_class).length)
 				{
 					
 				}
 				else
 				{
 					$('#has_sizes').append('<div class="product-pic-small size_option size-'+data[i].opt_name.toLowerCase()+'" data-size-label="'+data[i].opt_name+'" data-opt-id="'+data[i].opt_id+'"><p>'+data[i].opt_name+'</p></div>');
 				}
 				if(categ_page == data[i].sku)
 				{
 					opt_size_id = data[i].opt_id;
 					let size = data[i].opt_name.toLowerCase();
 					$('.size-'+size).show();
 					$('.size-'+size).children().addClass('clicked_opt');
 				}
 			}
 		}
 	}
 });
 */
}

function isNumber(evt) {
	evt = evt ? evt : window.event;
	var charCode = evt.which ? evt.which : evt.keyCode;
	if ($('#p_quantity').val().length < 1) {
		if (charCode == 48) {
			$('#p_quantity').val(1);
			$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + 1 + ')');
			return false;
		} else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		} else {
			var entered_q = parseInt(String.fromCharCode(charCode));
			if (entered_q > p_quantity) {
				$('#p_quantity').val(p_quantity);
				$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + p_quantity + ')');
				return false;
			} else {
				$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + entered_q + ')');
				return true;
			}
		}
	} else if ($('#p_quantity').val().length == 1) {
		if (charCode == 8) {
			$('#p_quantity').val(1);
			$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + 1 + ')');
			return true;
		} else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		} else {
			var _entered_q = parseInt($('#p_quantity').val() + String.fromCharCode(charCode));
			if (_entered_q > p_quantity) {
				$('#p_quantity').val(p_quantity);
				$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + p_quantity + ')');
				return false;
			} else {
				$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + _entered_q + ')');
				return true;
			}
		}
	} else {

		if (charCode == 8) {
			var new_q = parseInt($('#p_quantity').val().slice(0, -1));
			$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + new_q + ')');
			return true;
		} else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		} else {
			var _entered_q2 = parseInt($('#p_quantity').val() + String.fromCharCode(charCode));
			if (_entered_q2 > p_quantity) {
				$('#p_quantity').val(p_quantity);
				$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + p_quantity + ')');
				return false;
			} else {
				$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + _entered_q2 + ')');
				return true;
			}
		}
	}
}

function query_for_stock(query_type) {
	if (query_type == "with_size" || query_type == "with_color") {
		$.ajax({
			type: 'ajax',
			url: base_url + "customer/getStock/" + categ_page + "/" + opt_color_id + "/" + opt_size_id,
			async: true,
			dataType: 'json',
			success: function success(data) {
				if (data == "No Such Product") {
					$('#stocks_on_hand').text("Stock On Hand : 0");
					p_quantity = 0;
					$("#p_quantity_row").prop('disabled', true);
					$("#p_quantity_row").children().prop('disabled', true);
					$('#add_to_cart_vw').prop('disabled', true);
					$('#p_quantity').prop('disabled', true);
					$('#spinner_up').prop('disabled', true);
					$('#spinner_down').prop('disabled', true);
				} else {
					$('#stocks_on_hand').text("Stock On Hand : " + data[0].quantity);
					var stringer = $('#add_to_cart_vw').attr('onclick');
					var stringed = stringer.replace("addProductToCart('" + categ_page + "',", "");
					var will_stringed = stringed.replace(')', '');
					var int_qnty = parseInt(will_stringed);
					categ_page = data[0].sku;
					$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + int_qnty + ')');
					p_quantity = data[0].quantity;
					$('#p_quantity').val(1);
					$("#p_quantity_row").prop('disabled', false);
					$("#p_quantity_row").children().prop('disabled', false);
					$('#add_to_cart_vw').prop('disabled', false);
					$('#p_quantity').prop('disabled', false);
					$('#spinner_up').prop('disabled', false);
					$('#spinner_down').prop('disabled', false);
					$('#brand_prod').text(data[0].brand);
					$('#properties_prod').text(data[0].Properties);
					if (data[0].discount_percent == "0") {
						$('#cart-list-span-counter').remove();
						$('#product_orig_price_id').remove();
						$('#product_price_id').show();
						$('#product_price_id').empty();
						$('#product_price_id').append('&#8369; ' + data[0].selling_price);
						$('.product_view_price_before').remove();
					} else {
						var new_price = parseFloat(data[0].selling_price - data[0].discount_percent / 100 * data[0].selling_price).toFixed(2);
						$('#product_pic').append('<span class="label-count" id="cart-list-span-counter"></span>');
						$('#cart-list-span-counter').empty();
						$('#cart-list-span-counter').append(data[0].discount_percent + '% OFF');
						$('#cart-list-span-counter').show();
						$('#product_price_id').show();
						$('#product_price_id').empty();
						$('#product_price_id').append('&#8369; ' + new_price);
						$('#product_price_id').after('<h2 class="product_view_price_before font-line-through" id="product_orig_price_id">&#8369; ' + data[0].selling_price + '</h2>');
					}
				}
			}
		});
	} else {
		$.ajax({
			type: 'ajax',
			url: base_url + "customer/getStock_Color/" + categ_page + "/" + opt_color_id,
			async: true,
			dataType: 'json',
			success: function success(data) {
				if (data == '"No Such Product"' || data == 'No Such Product') {
					$('#stocks_on_hand').text("Stock On Hand : 0");
					p_quantity = 0;
					$('#p_quantity_row').hide();
				} else {
					if (data['quantity'] == 0) {
						$('#stocks_on_hand').text("Stock On Hand : 0");
						p_quantity = 0;
						$('#p_quantity_row').hide();
					} else {
						if (data['discount_percent'] == "0") {
							$('#cart-list-span-counter').remove();
							$('#product_orig_price_id').remove();
							$('#product_price_id').show();
							$('#product_price_id').empty();
							$('#product_price_id').append('&#8369; ' + data['selling_price']);
							$('.product_view_price_before').remove();
						} else {
							var new_price = parseFloat(data['selling_price'] - data['discount_percent'] / 100 * data['selling_price']).toFixed(2);
							$('#product_pic').append('<span class="label-count" id="cart-list-span-counter"></span>');
							$('#cart-list-span-counter').empty();
							$('#cart-list-span-counter').append(data['discount_percent'] + '% OFF');
							$('#cart-list-span-counter').show();
							$('#product_price_id').show();
							$('#product_price_id').empty();
							$('#product_price_id').append('&#8369; ' + new_price);
							$('#product_price_id').after('<h2 class="product_view_price_before font-line-through" id="product_orig_price_id">&#8369; ' + data['selling_price'] + '</h2>');
						}
						var stringer = $('#add_to_cart_vw').attr('onclick');
						var stringed = stringer.replace("addProductToCart('" + categ_page + "',", "");
						var will_stringed = stringed.replace(')', '');
						var int_qnty = parseInt(will_stringed);
						categ_page = data['sku'];
						$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + int_qnty + ')');
						$('#stocks_on_hand').text("Stock On Hand : " + data['quantity']);
						p_quantity = data['quantity'];
						$('#p_quantity').val(1);
						$('#p_quantity_row').show();
					}
				}
			}
		});
	}
}
$(document).ready(function () {

	$('#');

	$('#spinner_up').click(function () {
		var stringer = $('#add_to_cart_vw').attr('onclick');
		var stringed = stringer.replace("addProductToCart('" + categ_page + "',", '');
		var will_stringed = stringed.replace(')', '');
		var int_qnty = parseInt(will_stringed);

		if (p_quantity == 0) {
			document.getElementById('p_quantity').value = 0;
		} else {
			if (int_qnty == p_quantity) {
				swal({
					title: 'Error!',
					text: "The quantity you selected exceeds the stock on hand!",
					type: 'error',
					showCancelButton: true
				});
				$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + p_quantity + ')');
				document.getElementById('p_quantity').value = p_quantity - 1;
			} else {
				int_qnty = int_qnty + 1;
				$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + int_qnty + ')');
				document.getElementById('p_quantity').value = int_qnty - 1;
			}
		}
	});
	$('#spinner_down').click(function () {
		var stringer = $('#add_to_cart_vw').attr('onclick');
		var stringed = stringer.replace("addProductToCart('" + categ_page + "',", '');
		var will_stringed = stringed.replace(')', '');
		var int_qnty = parseInt(will_stringed);
		if (int_qnty == 1) {
			int_qnty = 1;
		} else {
			int_qnty = int_qnty - 1;
		}
		$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "'," + int_qnty + ')');
	});
});
$('#add_to_cart_vw').attr('onclick', "addProductToCart('" + categ_page + "',1)");
check_if_product_has_size_or_color(categ_page);
$('#cart-list-span-counter').empty();
$('#product_orig_price_id').empty();
$('#product_price_id').empty();
$('#cart-list-span-counter').remove();
$('#product_orig_price_id').remove();
getProductDetailedInfo(categ_page);