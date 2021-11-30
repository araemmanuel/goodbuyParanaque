'use strict';

current_sf();
var current_ship_fee;
$(document).ready(function () {

	$('.number_only').keydown(function (e) {
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		// Allow: Ctrl/cmd+A
		e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true) ||
		// Allow: Ctrl/cmd+C
		e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true) ||
		// Allow: Ctrl/cmd+X
		e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true) ||
		// Allow: home, end, left, right
		e.keyCode >= 35 && e.keyCode <= 39) {
			// let it happen, don't do anything
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});

	$('.number_only').keyup(function (e) {
		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		// Allow: Ctrl/cmd+A
		e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true) ||
		// Allow: Ctrl/cmd+C
		e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true) ||
		// Allow: Ctrl/cmd+X
		e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true) ||
		// Allow: home, end, left, right
		e.keyCode >= 35 && e.keyCode <= 39) {
			// let it happen, don't do anything
			return;
		}
		// Ensure that it is a number and stop the keypress
		if ((e.shiftKey || e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105)) {
			e.preventDefault();
		}
	});
});
function current_sf() {
	$.ajax({
		type: 'ajax',
		url: base_url + 'customer/get_shipping_fee',
		async: true,
		dataType: 'json',
		success: function success(data) {
			if (data != null) {
				current_ship_fee = parseFloat(data).toFixed(2);
				$('#shipping_fee_td_id').empty();
				$('#shipping_fee_td_id').append("&#8369;" + parseFloat(current_ship_fee).toFixed(2));
				$('#order_shopping_fee_td').empty();
				$('#order_shopping_fee_td').data('fee', current_ship_fee);
				$('#order_shopping_fee_td').append("&#8369;" + parseFloat(current_ship_fee).toFixed(2));
			}
		},
		error: function error(data) {}
	});
}

$('.clear_form').click(function () {
	$('input[name="login_username"]').val("");
	$('input[name="login_password"]').val("");
});

$('#search_bar_close').click(function () {
	$('#suggestion_id').hide(100);
});
$('#search_bar_open').click(function () {
	$('#suggestion_id').show(100);
});
$('#suggestion_id').hide();
$('.categ_trigger').click(function () {
	$('.post-nav-bar').slideToggle(100);
});

$('#input_search').keyup(function (e) {
	if (!e) e = window.event;
	var keyCode = e.keyCode;
	if (keyCode === 13) {
		if ($(this).val().length > 1) {
			if (/\S/.test($(this).val())) {
				//alert($(this).val());
				window.location.href = base_url + 'customer/search/' + $(this).val();
				//history.pushState({},"",base_url+ 'customer/search/'+$(this).val());
			}
		}
	}
});

$('#btn_search_items').click(function (e) {
	$('#input_search').trigger(jQuery.Event('keyup', { keyCode: 13, which: 13 }));
});

function create_bread_crumbs(type_of_page, sub_categ, categ) {
	var bread_crumb = '<ol class="breadcrumb breadcrumb-col-green"><li><a href="' + base_url + 'customer" >Home</a></li>';
	var paths = window.location.href.split('/');
	for (var cntr_path = 0; cntr_path < paths.length; cntr_path++) {
		if (paths[cntr_path] == "http" || paths[cntr_path] == "https" || paths[cntr_path] == "" || paths[cntr_path] == "localhost" || paths[cntr_path] == "goodBuy1" || paths[cntr_path] == "customer") {
			continue;
		} else {
			if (paths[cntr_path] == "view_product") {
				bread_crumb = bread_crumb + '<li><a href="' + base_url + 'customer/shop_categ/' + categ + '" >' + categ + '</a></li><li><a href="' + base_url + 'customer/shop_sub_categ/' + sub_categ + '" >' + sub_categ + '</a></li>';
			}
		}
	}
	bread_crumb = bread_crumb + "</ol>";
	return bread_crumb;
}

function filter(text_from, text_to) {
	var result_numbers = 0;
	var number_of_brands = 0;
	var brands = [];
	$('.chk-col-green:checked').each(function () {
		brands[number_of_brands] = $(this).val();
		number_of_brands++;
	});
	if (number_of_brands == 0) {
		$('.items_filter').each(function () {
			if (parseFloat($(this).data("price")) < parseFloat(text_from) || parseFloat($(this).data("price")) > parseFloat(text_to)) {
				$(this).hide('100');
			} else {
				$(this).show('100');
				result_numbers++;
				$('#header_code').empty();
				$('#header_code').append('Showing ' + result_numbers + ' Items');
			}
		});
	} else if (number_of_brands == 1) {
		$('.items_filter').each(function () {
			if (brands[0] != $(this).data("brand") || parseFloat($(this).data("price")) < parseFloat(text_from) || parseFloat($(this).data("price")) > parseFloat(text_to)) {
				$(this).hide('100');
			} else {
				$(this).show('100');
				result_numbers++;
				$('#header_code').empty();
				$('#header_code').append('Showing ' + result_numbers + ' Items');
			}
		});
	} else if (number_of_brands > 1) {
		$('.items_filter').each(function () {
			if (brands.indexOf($(this).data("brand")) < 0 || parseFloat($(this).data("price")) < parseFloat(text_from) || parseFloat($(this).data("price")) > parseFloat(text_to)) {
				$(this).hide('100');
			} else {
				$(this).show('100');
				result_numbers++;
				$('#header_code').empty();
				$('#header_code').append('Showing ' + result_numbers + ' Items');
			}
		});
	}
	if (result_numbers == 0) {
		$('#header_code').empty();
		$('#header_code').append('No Items');
	}
}

function load_categories() {
	$('.post-nav-bar').slideToggle(100);
	$.ajax({
		type: 'ajax',
		url: base_url + 'customer/getCategories',
		async: true,
		dataType: 'json',
		success: function success(data) {
			var i = void 0;
			var to_be_append = '';
			var prev_cat_name = '';
			for (var _i = 0; _i < data.length; _i++) {
				if (data[_i].cat_name != prev_cat_name && _i == 0) {
					to_be_append += '<div class="col-lg-2 col-sm-4 col-xs-6"><ul><li class="categ_name"><a href="' + base_url + 'customer/shop_categ/' + data[_i].cat_name + '">' + data[_i].cat_name + '</a></li><li><ul class="categ_subcateg_list"><li><a class="pre-nav-header-link col-green" href="' + base_url + 'customer/shop_sub_categ/' + data[_i].cat_name + '/' + data[_i].subcat_name + '">' + data[_i].subcat_name + '</a></li>';
					prev_cat_name = data[_i].cat_name;
				} else if (data[_i].cat_name != prev_cat_name && _i >= 1) {
					to_be_append += '</ul></li></ul></div><div class="col-lg-2 col-sm-4 col-xs-6"><ul><li class="categ_name"><a href="' + base_url + 'customer/shop_categ/' + data[_i].cat_name + '">' + data[_i].cat_name + '</a></li><li><ul class="categ_subcateg_list"><li><a class="pre-nav-header-link col-green" href="' + base_url + 'customer/shop_sub_categ/' + data[_i].cat_name + '/' + data[_i].subcat_name + '">' + data[_i].subcat_name + '</a></li>';
					prev_cat_name = data[_i].cat_name;
				} else if (data[_i].cat_name == prev_cat_name) {
					to_be_append += '<li><a class="pre-nav-header-link col-green" href="' + base_url + 'customer/shop_sub_categ/' + data[_i].cat_name + '/' + data[_i].subcat_name + '">' + data[_i].subcat_name + '</a></li>';
				}
			}
			to_be_append += '</ul></li></ul></div>';
			$('#categ_subcateg_nav').append(to_be_append);
		}
	});
}
load_categories();

String.prototype.trunc = function (n) {
	return this.substr(0, n - 1) + (this.length > n ? '&hellip;' : '');
};

//this is for storing the items in the cart using COOKIES <3//
//This is not production quality, its just demo code.
var cookieList = function cookieList(cookieName) {
	//When the cookie is saved the items will be a comma seperated string
	//So we will split the cookie by comma to get the original array
	var cookie = $.cookie(cookieName);
	//Load the items or a new array if null.
	var _items = cookie ? cookie.split(/,/) : new Array();

	//Return a object that we can use to access the array.
	//while hiding direct access to the declared items array
	//this is called closures see http://www.jibbering.com/faq/faq_notes/closures.html
	return {
		"add": function add(val) {
			_items.push(val);
			//Save the items to a cookie.
			$.cookie(cookieName, _items.join(','), { path: '/' });
			load_modal_items();
			load_cart_items();
		},
		"remove": function remove(val) {
			//remove item from the cookie
			var indx = _items.indexOf(val);
			if (indx != -1) _items.splice(indx, 1);
			$.cookie(cookieName, _items.join(','), { path: '/' });
		},
		"clear": function clear() {
			_items = "";
			//clear the cookie.
			$.cookie(cookieName, "", { path: '/' });
		},
		"edit": function edit(val, quantity, prod_id) {
			//edit the value of the cookie with index
			var indx = _items.indexOf(val);
			var item_info = _items[indx];
			var item = item_info.split(":");

			var new_quantity = parseInt(item[1]) + parseInt(quantity);
			//alert(new_quantity);
			$.ajax({
				type: 'ajax',
				url: base_url + "customer/get_stock_on_hand_for_edit/" + prod_id,
				async: true,
				dataType: 'json',
				success: function success(data) {
					if (data < new_quantity) {
						swal({
							title: 'Oops, your order should not exceed the quantity of stock on hand. ',
							type: 'error',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Okay',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
						load_modal_items();
						load_cart_items();
					} else {
						_items[indx] = prod_id + ":" + new_quantity;
						$.cookie(cookieName, _items.join(','), { path: '/' });
						//alert('after edit function : ' +items);
						load_modal_items();
						load_cart_items();
					}
				}
			});
		},
		"edit_item": function edit_item(val, quantity, prod_id) {
			//edit the value of the cookie with index
			var indx = _items.indexOf(val);
			var item_info = _items[indx];
			//alert(item_info);
			var item = item_info.split(":");
			var new_quantity = parseInt(quantity);
			//alert(parseInt(item[1]));
			_items[indx] = prod_id + ":" + new_quantity;
			$.cookie(cookieName, _items.join(','), { path: '/' });
		},
		"items": function items() {
			//Get all the items.
			return _items;
		}
	};
};

var cart_list = new cookieList("cartItems");

$('#login_btn').click(function (e) {
	e.preventDefault();
	var login_data = $("#logged_in_form").serialize();
	$.ajax({
		type: 'ajax',
		url: base_url + 'customer/login',
		async: true,
		data: login_data,
		method: 'POST',
		success: function success(data) {
			if (data == '"success"') {
				location.reload();
			} else {
				$('#login_username_label').empty();
				$('#login_username_label').append('<p style="margin-left: 2rem !important" >Invalid Username or Password</p><br/>');
			}
		}
	});
});

function post_categ() {
	$.ajax({
		type: 'ajax',
		url: base_url + 'customer/getCategories',
		async: true,
		dataType: 'json',
		success: function success(data) {
			$('#categories_dropdown').empty();
			var categories = "";
			data.forEach(function (element) {
				if (element['cat_name'] != categories) {
					var temp = "";
					temp = temp + '<li class="has-children"><a href="http://codyhouse.co/?p=748">' + element['cat_name'] + '</a>';
					temp = temp + '<ul class="cd-secondary-dropdown is-hidden">';
					temp = temp + '<li class="go-back"><a href="#0">Menu</a></li>';
					temp = temp + '<li class="see-all"><a href="http://codyhouse.co/?p=748">All ' + element['cat_name'] + '</a></li><li class="has-children"><a href="http://codyhouse.co/?p=748">' + element['cat_name'] + '</a><ul class="is-hidden">';
					temp = temp + '<li class="go-back"><a href="#0"' + element['cat_name'] + '</a></li>';
					temp = temp + '<li class="see-all"><a href="http://codyhouse.co/?p=748">All ' + element['cat_name'] + '</a></li>';
					temp = temp + '<li class="has-children"><a href="#0">' + element['subcat_name'] + '</a><ul class="is-hidden">';
					temp = temp + '<li class="go-back"><a href="#0">Flats</a></li></ul></li></ul></li></ul></li>';
					$('#categories_dropdown').append(temp);
					categories = element['cat_name'];
				} else {}
			});
		},
		error: function error() {}
	});
}

function showAllProducts() {
	$.ajax({
		type: 'ajax',
		url: base_url + 'customer/get_GA_Report',
		async: true,
		dataType: 'json',
		success: function success(data) {
			if (data.length > 0) {
				var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				var d = new Date();
				$('#owl_banner').after('<div class="row main-body-products" id="pop_items_categ"><div class="col-lg-12 product-row"><h3>POPULAR ITEMS THIS MONTH</h3><h1>' + monthNames[d.getMonth()].toUpperCase() + ' ' + d.getFullYear() + '</h1><div class="row"><div class="owl-carousel owl-theme" id="product_view_holder_pop"></div></div></div></div>');
				var i;
				var values_to_append = '';
				for (var _i2 = 0; _i2 < data.length; _i2++) {
					if (data[_i2].discount_percent == "0") {
						$('#product_view_holder_pop').append('<div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i2].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i2].img_file_path + '"><p>' + data[_i2].name.trunc(15) + '</p><h4>&#8369; ' + data[_i2].selling_price + '</h4><h5>Stock Available : ' + data[_i2].quantity + ' </h5></a></div></div>');
					} else {
						var new_price = parseFloat(data[_i2].selling_price - data[_i2].discount_percent / 100 * data[_i2].selling_price).toFixed(2);
						$('#product_view_holder_pop').append('<div class="card card-product"><span class="label-count" id="cart-list-span">' + data[_i2].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i2].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i2].img_file_path + '"><p>' + data[_i2].name.trunc(15) + '</p><h4 class="before-price font-line-through">&#8369; ' + data[_i2].selling_price + '</h4><h4>&#8369; ' + new_price + '</h4><h5>Stock Available : ' + data[_i2].quantity + '</h5></a></div></div>');
					}
				}
				$('#product_view_holder_pop').owlCarousel({
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
		}
	});
	setInterval(function () {
		$('#owl_banner > div:first').fadeOut(1000).next().fadeIn(1000).end().appendTo('#owl_banner');
	}, 10000);
	$.ajax({
		type: 'ajax',
		url: base_url + 'customer/getProducts',
		async: true,
		dataType: 'json',
		success: function success(data) {
			var i;
			var values_to_append = '';
			for (var _i3 = 0; _i3 < data.length; _i3++) {
				if (data[_i3].discount_percent == "0") {
					$('#product_view_holder').append('<div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i3].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i3].img_file_path + '"><p>' + data[_i3].name.trunc(15) + '</p><h4>&#8369; ' + data[_i3].selling_price + '</h4><h5>Stock Available : ' + data[_i3].quantity + ' </h5></a></div></div>');
				} else {
					var new_price = parseFloat(data[_i3].selling_price - data[_i3].discount_percent / 100 * data[_i3].selling_price).toFixed(2);
					$('#product_view_holder').append('<div class="card card-product"><span class="label-count" id="cart-list-span">' + data[_i3].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i3].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i3].img_file_path + '"><p>' + data[_i3].name.trunc(15) + '</p><h4 class="before-price font-line-through">&#8369; ' + data[_i3].selling_price + '</h4><h4>&#8369; ' + new_price + '</h4><h5>Stock Available : ' + data[_i3].quantity + '</h5></a></div></div>');
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
		},
		error: function error() {}
	});
	$.ajax({

		type: 'ajax',
		url: base_url + 'customer/getProducts_Per_Categ',
		async: true,
		dataType: 'json',
		success: function success(data) {
			var prev_categ = '';
			var temp_arr = [];
			var cntr_prod = 1;
			for (var _i4 = 0; _i4 < data.length; _i4++) {
				if (prev_categ == data[_i4].cat_name) {
					cntr_prod++;
				} else {
					if (_i4 == 0) {
						cntr_prod++;
						prev_categ = data[_i4].cat_name;
					} else {
						if (cntr_prod >= 7) {
							temp_arr.push(prev_categ);
							cntr_prod = 1;
							prev_categ = data[_i4].cat_name;
						}
					}
				}
				if (_i4 == data.length - 1 && cntr_prod >= 7) {
					temp_arr.push(prev_categ);
				}
			}
			var prev_categ_name = "";
			var i;
			var values_to_append = '';
			var color_bg = "bg-gradient-light-grey";
			var count_per_categ_to_list = 0;
			for (var _i5 = 0; _i5 < data.length; _i5++) {
				if (temp_arr.indexOf(data[_i5].cat_name) != -1) {
					if (prev_categ_name == data[_i5].cat_name && count_per_categ_to_list > 20) {
						continue;
					} else if (prev_categ_name != data[_i5].cat_name && _i5 == 0) {
						count_per_categ_to_list++;
						$('#new_items_categ').after('<div class="' + color_bg + '" id="categ_list_' + data[_i5].cat_name + '"><div class="row main-body-products"><div class="col-lg-12 product-row"><h3></h3><h1><a href="' + base_url + '/customer/shop_categ/' + data[_i5].cat_name + '">' + data[_i5].cat_name + "'s Category" + '</a></h1><div class="row"><div class="owl-carousel owl-theme" id="categ_owl_' + data[_i5].cat_name + '"></div></div></div></div></div>');
						if (data[_i5].discount_percent == "0") {
							$('#categ_owl_' + data[_i5].cat_name).append('<div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i5].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i5].img_file_path + '"><p>' + data[_i5].name.trunc(15) + '</p><h4>&#8369; ' + data[_i5].selling_price + '</h4><h5>Stock Available : ' + data[_i5].quantity + ' </h5></a></div></div>');
						} else {
							var new_price = parseFloat(data[_i5].selling_price - data[_i5].discount_percent / 100 * data[_i5].selling_price).toFixed(2);
							$('#categ_owl_' + data[_i5].cat_name).append('<div class="card card-product"><span class="label-count" id="cart-list-span">' + data[_i5].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i5].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i5].img_file_path + '"><p>' + data[_i5].name.trunc(15) + '</p><h4 class="before-price font-line-through">&#8369; ' + data[_i5].selling_price + '</h4><h4>&#8369; ' + new_price + '</h4><h5>Stock Available : ' + data[_i5].quantity + '</h5></a></div></div>');
						}
						prev_categ_name = data[_i5].cat_name;
					} else if (prev_categ_name != data[_i5].cat_name && _i5 >= 1) {
						count_per_categ_to_list = 0;
						if (color_bg == "bg-gradient-light-grey") {
							color_bg = "bg-gradient-light-grey-l1";
						} else {
							color_bg = "bg-gradient-light-grey";
						}
						$('#categ_list_' + prev_categ_name).after('<div class="' + color_bg + '" id="categ_list_' + data[_i5].cat_name + '"><div class="row main-body-products"><div class="col-lg-12 product-row"><h3></h3><h1><a href="' + base_url + 'customer/shop_categ/' + data[_i5].cat_name + '">' + data[_i5].cat_name + "'s Category" + '</a></h1><div class="row"><div class="owl-carousel owl-theme" id="categ_owl_' + data[_i5].cat_name + '"></div></div></div></div></div>');
						if (data[_i5].discount_percent == "0") {
							$('#categ_owl_' + data[_i5].cat_name).append('<div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i5].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i5].img_file_path + '"><p>' + data[_i5].name.trunc(15) + '</p><h4>&#8369; ' + data[_i5].selling_price + '</h4><h5>Stock Available : ' + data[_i5].quantity + ' </h5></a></div></div>');
						} else {
							var _new_price = parseFloat(data[_i5].selling_price - data[_i5].discount_percent / 100 * data[_i5].selling_price).toFixed(2);
							$('#categ_owl_' + data[_i5].cat_name).append('<div class="card card-product"><span class="label-count" id="cart-list-span">' + data[_i5].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i5].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i5].img_file_path + '"><p>' + data[_i5].name.trunc(15) + '</p><h4 class="before-price font-line-through">&#8369; ' + data[_i5].selling_price + '</h4><h4>&#8369; ' + _new_price + '</h4><h5>Stock Available : ' + data[_i5].quantity + '</h5></a></div></div>');
						}

						$('#categ_owl_' + prev_categ_name).owlCarousel({
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
						prev_categ_name = data[_i5].cat_name;
					} else if (data[_i5].cat_name == prev_categ_name) {
						count_per_categ_to_list++;
						if (data[_i5].discount_percent == "0") {
							$('#categ_owl_' + data[_i5].cat_name).append('<div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i5].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i5].img_file_path + '"><p>' + data[_i5].name.trunc(15) + '</p><h4>&#8369; ' + data[_i5].selling_price + '</h4><h5>Stock Available : ' + data[_i5].quantity + ' </h5></a></div></div>');
						} else {
							var _new_price2 = parseFloat(data[_i5].selling_price - data[_i5].discount_percent / 100 * data[_i5].selling_price).toFixed(2);
							$('#categ_owl_' + data[_i5].cat_name).append('<div class="card card-product"><span class="label-count" id="cart-list-span">' + data[_i5].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[_i5].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[_i5].img_file_path + '"><p>' + data[_i5].name.trunc(15) + '</p><h4 class="before-price font-line-through">&#8369; ' + data[_i5].selling_price + '</h4><h4>&#8369; ' + _new_price2 + '</h4><h5>Stock Available : ' + data[_i5].quantity + '</h5></a></div></div>');
						}
					}
				}
			}
			$('#categ_owl_' + prev_categ_name).owlCarousel({
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
		},
		error: function error() {}
	});
}

function get_categs_with_prod(data) {
	var prev_categ = '';
	var temp_arr = [];
	var cntr = 0;
	var cntr_categ = 0;
	for (var i = 0; i < data.length; i++) {
		if (prev_categ == data[i].categ_name) {
			cntr++;
		} else {
			if (i == 0) {
				cntr++;
				prev_categ = data[i].categ_name;
			} else {
				if (cntr > 7) {
					temp_arr[cntr_categ] = prev_categ;
					cntr = 0;
					cntr_categ++;
				}
			}
		}
	}
	return temp_arr;
}

$('.categ_nav_item').click(function () {
	e.preventDefault();
});
$('#btn_submit_forget').click(function (e) {
	e.preventDefault();
	var sign_up_data = $('#submit_f_password_form').serialize();
	$.ajax({
		type: 'ajax',
		method: 'POST',
		url: base_url + 'customer/change_password',
		data: sign_up_data,
		async: true,
		error: function error(request, _error) {},
		success: function success(msg_errors) {
			if (msg_errors == 'accepted') {
				swal({
					title: 'Password Successfully Changed',
					text: "Check your email, we send your new password in your email!",
					type: 'success',
					showCancelButton: false,
					confirmButtonColor: '#3085d6',
					confirmButtonText: 'View Orders',
					confirmButtonClass: 'btn btn-success',
					buttonsStyling: false,
					reverseButtons: true
				});
				$('.confirm').click(function () {
					window.location.replace(base_url);
				});
			} else if (msg_errors == 'account_not_accepted') {
				$('#sign_up_username_label').empty();
				$('#sign_up_username_label').append('<p>The username and email provided do not match any account.</p>');
			} else if (msg_erros == 'passwords_not_accepted') {
				$('#sign_up_username_label').empty();
				$('#sign_up_username_label').append('<p>The two passwords provided do not match.</p>');
			}
		}
	});
});
$('#btn_submit_register').click(function (e) {
	e.preventDefault();
	var sign_up_data = $('#submit_register_form').serialize();
	$.ajax({
		type: 'ajax',
		method: 'POST',
		url: base_url + 'customer/register_validate',
		data: sign_up_data,
		async: true,
		error: function error(request, _error2) {},
		success: function success(msg_errors) {
			if (msg_errors == 'accepted') {
				swal({
					title: 'Successfully Signed Up',
					text: "You can now order without filling the checkout form everytime you order!",
					type: 'success',
					showCancelButton: false,
					confirmButtonColor: '#3085d6',
					confirmButtonText: 'View Orders',
					confirmButtonClass: 'btn btn-success',
					buttonsStyling: false,
					reverseButtons: true
				});
				$('.confirm').click(function () {
					window.location.replace(base_url + 'customer/login_after_register/' + $('#register_card_no_id').val());
				});
			} else if (msg_errors == 'card_already_registered') {
				$('#register_card_no_label').empty();
				$('#register_password_label').empty();
				$('#register_confirm_password_label').empty();
				$('#register_uname_label').empty();
				$('#register_card_no_label').append("<p>The card is already registered!</p>");
			} else if (msg_errors == 'username_not_accepted') {
				$('#register_card_no_label').empty();
				$('#register_password_label').empty();
				$('#register_uname_label').empty();
				$('#register_confirm_password_label').empty();
				$('#register_uname_label').append("<p>The username is already taken!</p>");
			} else if (msg_errors == 'card_not_accepted') {
				$('#register_card_no_label').empty();
				$('#register_password_label').empty();
				$('#register_uname_label').empty();
				$('#register_confirm_password_label').empty();
				$('#register_card_no_label').append("<p>The card information is incorrect!</p>");
			} else if (msg_errors == 'password_not_accepted') {
				$('#register_card_no_label').empty();
				$('#register_password_label').empty();
				$('#register_uname_label').empty();
				$('#register_confirm_password_label').empty();
				$('#register_password_label').append("<p>The passwords don/'t match!</p>");
			} else if (msg_errors == 'Username already taken!') {
				$('#register_card_no_label').empty();
				$('#register_password_label').empty();
				$('#register_uname_label').empty();
				$('#register_confirm_password_label').empty();
				$('#sign_up_username_label').append(msg_errors);
			} else {
				//$.each(msg.errors, function(key, val) {alert( key + ": " + value );})​;
				var parse_erros = JSON.parse(msg_errors);

				$.each(parse_erros, function (key, value) {
					$('#' + key + "_label").empty();
				});

				$.each(parse_erros, function (key, value) {
					$('#' + key + "_label").append(value);
				});
			}
		}
	});
});
$('#btn_submit_sign').click(function (e) {
	e.preventDefault();
	if (document.getElementById('male').checked) {
		$('input[name="sign_up_gender"]').val("male");
	} else if (document.getElementById('female').checked) {
		$('input[name="sign_up_gender"]').val("female");
	} else {
		$('input[name="sign_up_gender"]').val("");
	}

	var sign_up_data = $('#submit_sign_up_form').serialize();
	$.ajax({
		type: 'ajax',
		method: 'POST',
		url: base_url + 'customer/sign_up_validate',
		data: sign_up_data,
		async: true,
		error: function error(request, _error3) {},
		success: function success(msg_errors) {
			if (msg_errors == 'accepted') {
				swal({
					title: 'Successfully Signed Up',
					text: "We emailed you your link for verification of this account!",
					type: 'success',
					showCancelButton: false,
					confirmButtonColor: '#3085d6',
					confirmButtonText: 'Shop Now',
					confirmButtonClass: 'btn btn-success',
					buttonsStyling: false,
					reverseButtons: true
				});
				$('.confirm').click(function () {
					window.location.replace(base_url);
				});
			} else if (msg_errors == 'username_not_accepted') {
				$('#sign_up_username_label').empty();
				$('#sign_up_username_label').append("<p>The username is already taken!</p>");
			} else if (msg_errors == 'password_not_accepted') {
				$('#sign_up_password_c_label').empty();
				$('#sign_up_password_c_label').append("<p>The confirm password doesn't match the password!</p>");
			} else if (msg_errors == 'email_not_accepted') {
				$('#sign_up_email_label').empty();
				$('#sign_up_email_label').append("<p>The email is already used!</p>");
			} else if (msg_errors == 'Username already taken!') {
				$('#sign_up_username_label').empty();
				$('#sign_up_username_label').append(msg_errors);
			} else if (msg_errors == 'age_not_accepted') {
				$('#sign_up_dob_label').empty();
				$('#sign_up_dob_label').append(msg_errors);
			} else if (msg_errors == 'account_details_not_accepted') {
				$('#sign_up_firstname_label').empty();
				$('#sign_up_firstname_label').append('<p>The information provided is already registered in different account.</p>');
			} else {
				//$.each(msg.errors, function(key, val) {alert( key + ": " + value );})​;
				var parse_erros = JSON.parse(msg_errors);

				$.each(parse_erros, function (key, value) {
					$('#' + key + "_label").empty();
				});

				$.each(parse_erros, function (key, value) {
					$('#' + key + "_label").append(value);
				});
			}
		}
	});
});

function add_new_order() {
	if ($('#md_checkbox_1:checked').val() == 'on') {
		var data = $("#place_order_frm").serialize();
		$.ajax({
			type: 'ajax',
			method: 'POST',
			url: base_url + 'customer/create_card_for_user',
			data: data,
			async: true,
			beforeSend: function beforeSend() {},
			error: function error(request, _error4) {},
			success: function success(msg_errors) {
				if (msg_errors == 'accepted') {}
			}
		});
	}

	//alert($('#current_reward').length);
	//alert($('#input_reward_points').is(":visible"));
	//alert($('#checkout_username').val());
	if ($('#input_reward_points').is(":visible") && $('#current_reward').length == 1 && $('#checkout_username').val() != "") {
		var data = $("#place_order_frm").serialize();
		$.ajax({
			type: 'ajax',
			method: 'POST',
			url: base_url + 'customer/add_new_order_with_card_with_points',
			data: data,
			async: true,
			beforeSend: function beforeSend() {},
			error: function error(request, _error5) {},
			success: function success(msg_errors) {
				if (msg_errors.indexOf('accepted') >= 0) {
					var returned_val = msg_errors.split(",");
					var order_id = returned_val[1];
					var keys_gen = order_id.split("/");
					var order_no = keys_gen[0];
					cart_list.clear();
					load_cart_items();
					$('#cart-list-span').empty();
					$('#cart-list-span').append("0");
					if ($('input[name="payment_method"]').val() == 'pickup') {
						swal({
							title: 'Order Placed',
							text: "We emailed your claiming stub. Order No. is " + order_no + " . Thank you for shopping!",
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continue Shopping',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
					} else {
						swal({
							title: 'Order Placed',
							text: "We emailed the link for confirming your order.  Order No. is " + order_no + " .Thank you for shopping!",
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continue Shopping',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
					}
					$('.confirm').click(function () {
						window.location.replace(base_url + 'customer/home');
					});
				} else {
					swal.close();
					$('#largeModal_exceed_quantity').modal('show');
					load_modal_exceed_items(msg_errors);
				}
			}

		});
	} else if ($('#input_reward_points').is(":visible") == false && $('#current_reward').length == 1 && $('#checkout_username').val() != "") {
		var data = $("#place_order_frm").serialize();
		$.ajax({
			type: 'ajax',
			method: 'POST',
			url: base_url + 'customer/add_new_order_with_card_no_points',
			data: data,
			async: true,
			beforeSend: function beforeSend() {},
			error: function error(request, _error6) {},
			success: function success(msg_errors) {
				if (msg_errors.indexOf('accepted') >= 0) {
					var returned_val = msg_errors.split(",");
					var order_id = returned_val[1];
					var keys_gen = order_id.split("/");
					var order_no = keys_gen[0];
					cart_list.clear();
					load_cart_items();
					$('#cart-list-span').empty();
					$('#cart-list-span').append("0");
					if ($('input[name="payment_method"]').val() == 'pickup') {
						swal({
							title: 'Order Placed',
							text: "We emailed your claiming stub. Order No. is " + order_no + " . Thank you for shopping!",
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continue Shopping',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
					} else {
						swal({
							title: 'Order Placed',
							text: "We emailed the link for confirming your order.  Order No. is " + order_no + " .Thank you for shopping!",
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continue Shopping',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
					}
					$('.confirm').click(function () {
						window.location.replace(base_url + 'customer/home');
					});
				} else {
					swal.close();
					$('#largeModal_exceed_quantity').modal('show');
					load_modal_exceed_items(msg_errors);
				}
			}

		});
	} else if ($('#input_reward_points').is(":visible") == false && $('#current_reward').length < 1 && $('#checkout_username').val() != "") {
		//alert("Hello?");
		var data = $("#place_order_frm").serialize();
		$.ajax({
			type: 'ajax',
			method: 'POST',
			url: base_url + 'customer/add_new_order',
			data: data,
			async: true,
			beforeSend: function beforeSend() {},
			error: function error(request, _error7) {},
			success: function success(msg_errors) {
				if (msg_errors.indexOf('accepted') >= 0) {
					var returned_val = msg_errors.split(",");
					var order_id = returned_val[1];
					var keys_gen = order_id.split("/");
					var order_no = keys_gen[0];
					cart_list.clear();
					load_cart_items();
					$('#cart-list-span').empty();
					$('#cart-list-span').append("0");
					if ($('input[name="payment_method"]').val() == 'pickup') {
						swal({
							title: 'Order Placed',
							text: "We emailed your claiming stub. Order No. is " + order_no + " . Thank you for shopping!",
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continue Shopping',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
					} else {
						swal({
							title: 'Order Placed',
							text: "We emailed the link for confirming your order.  Order No. is " + order_no + " .Thank you for shopping!",
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continue Shopping',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
					}
					$('.confirm').click(function () {
						window.location.replace(base_url + 'customer/home');
					});
				} else {
					swal.close();
					$('#largeModal_exceed_quantity').modal('show');
					load_modal_exceed_items(msg_errors);
				}
			}

		});
	} else {
		var data = $("#place_order_frm").serialize();
		$.ajax({
			type: 'ajax',
			method: 'POST',
			url: base_url + 'customer/add_new_order',
			data: data,
			async: true,
			beforeSend: function beforeSend() {},
			error: function error(request, _error8) {},
			success: function success(msg_errors) {
				if (msg_errors.indexOf('accepted') >= 0) {
					var returned_val = msg_errors.split(",");
					var order_id = returned_val[1];
					var keys_gen = order_id.split("/");
					var order_no = keys_gen[0];
					cart_list.clear();
					load_cart_items();
					$('#cart-list-span').empty();
					$('#cart-list-span').append("0");
					if ($('input[name="payment_method"]').val() == 'pickup') {
						swal({
							title: 'Order Placed',
							text: "We emailed your claiming stub. Order No. is " + order_no + " . Thank you for shopping!",
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continue Shopping',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
					} else {
						swal({
							title: 'Order Placed',
							text: "We emailed the link for confirming your order.  Order No. is " + order_no + " .Thank you for shopping!",
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Continue Shopping',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
					}
					$('.confirm').click(function () {
						window.location.replace(base_url + 'customer/home');
					});
				} else {
					swal.close();
					$('#largeModal_exceed_quantity').modal('show');
					load_modal_exceed_items(msg_errors);
				}
			}

		});
	}
}

$("#place_order_btn").click(function () {
	var data = $("#place_order_frm").serialize();
	$.ajax({
		type: 'ajax',
		method: 'POST',
		url: base_url + 'customer/place_order',
		data: data,
		async: true,
		beforeSend: function beforeSend() {},
		error: function error(request, _error9) {},
		success: function success(msg_errors) {
			if (msg_errors == 'accepted') {
				/*swal({
    	title: 'Order is being placed',
    	text: "Note: We will be sending you a confirmation message in your email..",
    	imageUrl: base_url+"assets/customer/images/load_email.gif",
    	showCancelButton: false,
    	showConfirmButton: false
    });*/
				add_new_order();
			} else {
				var parse_erros = JSON.parse(msg_errors);

				$.each(parse_erros, function (key, value) {
					$('#' + key + "_label").empty();
				});

				$.each(parse_erros, function (key, value) {
					$('#' + key + "_label").append(value);
				});
			}
		}

	});
});

function load_products_categ(category, url_url) {
	$('#banner_code').append('<h2>' + category + "'s " + ' Category</h2><ol class="breadcrumb breadcrumb-col-green"><li><a href="' + base_url + 'customer" >Home</a></li><li><a href="' + base_url + 'customer/shop_categ/' + category + '" >' + category + '</a></li></ol>');
	$.ajax({
		type: 'ajax',
		url: url_url + "customer/getProducts_Categ/" + category,
		async: true,
		dataType: 'json',
		success: function success(data) {
			$('#header_code').empty();
			$('#header_code').append('Showing ' + data.length + ' Items');
			$('#body_categ').empty();
			for (var i = 0; i < data.length; i++) {
				if (data[i].discount_percent == "0") {
					$('#body_categ').append('<div class="col-lg-3 col-md-4 col-sm-6  items_filter" data-price="' + data[i].selling_price + '" data-brand="' + data[i].brand + '" data-is-sale="false"><div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[i].img_file_path + '"  ><p>' + data[i].name.trunc(15) + '</p><h4>&#8369; ' + data[i].selling_price + '</h4><h5>Stock Available : ' + data[i].quantity + ' </h5></a></div></div></div>');
				} else {
					var new_price = parseFloat(data[i].selling_price - data[i].discount_percent / 100 * data[i].selling_price).toFixed(2);
					$('#body_categ').append('<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6  items_filter" data-price="' + new_price + '" data-brand="' + data[i].brand + '" data-is-sale="true"><div class="card card-product" ><span class="label-count" id="cart-list-span">' + data[i].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[i].img_file_path + '"><p>' + data[i].name.trunc(15) + '</p><h4>&#8369; ' + data[i].selling_price + '</h4><h5>Stock Available : ' + data[i].quantity + '</h5></a></div></div></div>');
				}
			}
		},
		error: function error() {}
	});
	$.ajax({
		type: 'ajax',
		url: url_url + "customer/get_product_brands/" + category,
		async: true,
		dataType: 'json',
		success: function success(data) {
			for (var cntr = 0; cntr < data.length; cntr++) {
				if (data == []) {
					$('#brand_header').hide();
					$('#checkbox-list-brands').hide();
				}
				$('#checkbox-list-brands').append('<li><input type="checkbox" id="md_checkbox_' + data[cntr].brand + '" class="filled-in chk-col-green" value="' + data[cntr].brand + '"  /><label for="md_checkbox_' + data[cntr].brand + '">' + data[cntr].brand + '</label></li>');
			}
			$('.chk-col-green').click(function () {
				var slider = document.getElementById('nouislider_range_example');
				var val = slider.noUiSlider.get();
				var val_string = new String(val);
				var val_stringed = val_string.split(",");
				$(slider).parent().find('span.js-nouislider-value-from').text(val_stringed[0]);
				$(slider).parent().find('span.js-nouislider-value-to').text(val_stringed[1]);
				filter(val_stringed[0], val_stringed[1]);
			});
		}
	});
	$.ajax({
		type: 'ajax',
		url: url_url + "customer/get_sub_categ/" + category,
		async: true,
		dataType: 'json',
		success: function success(data) {
			for (var cntr = 0; cntr < data.length; cntr++) {
				$('#subcategory-list-subcateg').append('<a href="' + base_url + 'customer/shop_sub_categ/' + category + '/' + data[cntr].subcat_name + '"><li>' + data[cntr].subcat_name + '</li></a>');
			}
		}
	});
}

function load_products_search(category, url_url) {

	var item_brands = [];
	$.ajax({
		type: 'ajax',
		url: url_url + "customer/search_products/" + category,
		async: true,
		dataType: 'json',
		success: function success(data) {
			if (data != "no") {
				$('#body_categ').empty();
				var item_brands_cntr = 0;
				var prev_sku = "";
				var item = 0;
				for (var i = 0; i < data.length; i++) {
					if (prev_sku == "") {
						if (data[i].discount_percent == "0") {
							$('#body_categ').append('<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6  items_filter" data-price="' + data[i].selling_price + '" data-brand="' + data[i].brand + '" data-is-sale="false"><div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[i].img_file_path + '"><p>' + data[i].name.trunc(15) + '</p><h4>&#8369; ' + data[i].selling_price + '</h4><h5>Stock Available : ' + data[i].quantity + '</h5></a></div></div></div>');
						} else {
							var new_price = parseFloat(data[i].selling_price - data[i].discount_percent / 100 * data[i].selling_price).toFixed(2);
							$('#body_categ').append('<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6  items_filter" data-price="' + new_price + '" data-brand="' + data[i].brand + '" data-is-sale="true"><div class="card card-product" ><span class="label-count" id="cart-list-span">' + data[i].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[i].img_file_path + '"><p>' + data[i].name.trunc(15) + '</p><h4>&#8369; ' + data[i].selling_price + '</h4><h5>Stock Available : ' + data[i].quantity + '</h5></a></div></div></div>');
						}

						prev_sku = data[i].sku;
						item++;
					} else if (prev_sku != data[i].sku) {
						if (data[i].discount_percent == "0") {
							$('#body_categ').append('<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-6  items_filter" data-price="' + data[i].selling_price + '" data-brand="' + data[i].brand + '" data-is-sale="false"><div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[i].img_file_path + '"><p>' + data[i].name.trunc(15) + '</p><h4>&#8369; ' + data[i].selling_price + '</h4><h5>Stock Available : ' + data[i].quantity + ' </h5></a></div></div></div>');
						} else {
							var _new_price3 = parseFloat(data[i].selling_price - data[i].discount_percent / 100 * data[i].selling_price).toFixed(2);
							$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="' + _new_price3 + '" data-brand="' + data[i].brand + '" data-is-sale="true"><div class="card card-product" ><span class="label-count" id="cart-list-span">' + data[i].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[i].img_file_path + '"><p>' + data[i].name.trunc(15) + '</p><h4>&#8369; ' + data[i].selling_price + '</h4><h5>Stock Available : ' + data[i].quantity + '</h5></a></div></div></div>');
						}
						prev_sku = data[i].sku;
						item++;
					} else {}
					if (item_brands.indexOf(data[i].brand) < 0 && data[i].brand != "No Brand" && data[i].brand != "No brand" && data[i].brand != "no brand") {
						item_brands[item_brands_cntr] = data[i].brand;

						$('#checkbox-list-brands').append('<li><input type="checkbox" id="md_checkbox_' + item_brands[item_brands_cntr] + '" class="filled-in chk-col-green" value="' + item_brands[item_brands_cntr] + '"  /><label for="md_checkbox_' + item_brands[item_brands_cntr] + '">' + item_brands[item_brands_cntr] + '</label></li>');
						item_brands_cntr++;
						$('.chk-col-green').click(function () {
							var slider = document.getElementById('nouislider_range_example');
							var val = slider.noUiSlider.get();
							var val_string = new String(val);
							var val_stringed = val_string.split(",");
							$(slider).parent().find('span.js-nouislider-value-from').text(val_stringed[0]);
							$(slider).parent().find('span.js-nouislider-value-to').text(val_stringed[1]);
							filter(val_stringed[0], val_stringed[1]);
						});
					}
					if (item_brands_cntr < 1) {
						$('#brand_header').hide();
						$('#checkbox-list-brands').hide();
					} else {
						$('#brand_header').show();
						$('#checkbox-list-brands').show();
					}
				}
				$('#header_code').empty();
				$('#header_code').append('Showing ' + item + ' Items');
			} else {
				$('#filter_rows').remove();
				$('#body_categ').empty();
				$('#body_categ').append("<h1 class=" + '"col-black"' + ' style="font-size: 26px !important; margin: 1.5rem !important;">Ooops, it seems like we still ' + "don't have that product yet.</h1>");
			}
		},
		error: function error() {}
	});
	var categories = category.split("%20").join(" ");
	$('#banner_code').append('<h2>"' + categories + '" Results</h2> <ol class="breadcrumb breadcrumb-col-green"><li><a href="' + base_url + 'customer" >Home</a></li></ol>');
}

function load_products_sub_categ(categ, sub_categ, url_url) {
	var url_url_url = base_url + "customer/getProducts_SubCateg/" + categ + '/' + sub_categ;
	sub_categ = sub_categ.replace('%20', ' ');
	$.ajax({
		type: 'ajax',
		url: url_url_url,
		async: true,
		dataType: 'json',
		success: function success(data) {
			$('#header_code').empty();
			$('#header_code').append('Showing ' + data.length + ' Items');
			$('#body_categ').empty();
			for (var i = 0; i < data.length; i++) {
				if (i == 0) {
					$('#banner_code').append('<h2>' + categ + "'s " + ' Subcategory</h2><ol class="breadcrumb breadcrumb-col-green"><li><a href="' + base_url + 'customer" >Home</a></li><li><a href="' + base_url + 'customer/shop_categ/' + data[0].cat_name + '" >' + data[0].cat_name + '</a></li><li><a href="' + base_url + 'customer/shop_sub_categ/' + categ + '/' + sub_categ + '" >' + sub_categ + '</a></li></ol>');
				}
				if (data[i].discount_percent == "0") {
					$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="' + data[i].selling_price + '" data-brand="' + data[i].brand + '" data-is-sale="false"><div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[i].img_file_path + '"  ><p>' + data[i].name.trunc(15) + '</p><h4>&#8369; ' + data[i].selling_price + '</h4><h5>Stock Available : ' + data[i].quantity + ' </h5></a></div></div></div>');
				} else {
					var new_price = parseFloat(data[i].selling_price - data[i].discount_percent / 100 * data[i].selling_price).toFixed(2);
					$('#body_categ').append('<div class="col-lg-3 items_filter" data-price="' + new_price + '" data-brand="' + data[i].brand + '" data-is-sale="true"><div class="card card-product" ><span class="label-count" id="cart-list-span">' + data[i].discount_percent + '% OFF</span><div class="body"><a href="' + base_url + 'customer/view_product/' + data[i].sku + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[i].img_file_path + '"><p>' + data[i].name.trunc(15) + '</p><h4>&#8369; ' + data[i].selling_price + '</h4><h5>Stock Available : ' + data[i].quantity + '</h5></a></div></div></div>');
				}
			}
		},
		error: function error() {}
	});
	$.ajax({
		type: 'ajax',
		url: url_url + "customer/get_product_brands_sub/" + categ + '/' + sub_categ,
		async: true,
		dataType: 'json',
		success: function success(data) {
			for (var cntr = 0; cntr < data.length; cntr++) {
				$('#checkbox-list-brands').append('<li><input type="checkbox" id="md_checkbox_' + data[cntr].brand + '" class="filled-in chk-col-green" value="' + data[cntr].brand + '"  /><label for="md_checkbox_' + data[cntr].brand + '">' + data[cntr].brand + '</label></li>');
			}
			$('.chk-col-green').click(function () {
				var slider = document.getElementById('nouislider_range_example');
				var val = slider.noUiSlider.get();
				var val_string = new String(val);
				var val_stringed = val_string.split(",");
				$(slider).parent().find('span.js-nouislider-value-from').text(val_stringed[0]);
				$(slider).parent().find('span.js-nouislider-value-to').text(val_stringed[1]);
				filter(val_stringed[0], val_stringed[1]);
			});
		}
	});
}

function load_products_ordered(category, url_url) {
	var items = cart_list.items();
	$.ajax({
		type: 'ajax',
		url: url_url + "customer/getProducts_Categ/" + category,
		async: true,
		dataType: 'json',
		success: function success(data) {
			$('#body_categ').empty();
			for (var i = 0; i < data.length; i++) {
				if (data[i].discount_percent == null || data[i].discount_percent == 0 || data[i].discount_percent == "0") {
					$('#body_categ').append(' <div class="col-lg-3"><div class="card card-product"><div class="body"><a href="' + base_url + 'customer/view_product/' + data[i].prod_id + '" class="product_link"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + url_url + data[i].primary_image + '"><p>' + data[i].name + '</p><h4>&#8369; ' + data[i].selling_price + '</h4></a><button type="button" class="btn btn-success btn-block btn-add-cart waves-effect" data-prod-prod_id="' + data[i].prod_id + '" data-toggle="modal" data-target="#largeModal" onclick="addProductToCart(' + "'" + data[i].prod_id + "'" + ',1);"><i class="material-icons">add_shopping_cart</i> Add to Cart</button><button type="button" class="btn bg-green btn-block btn-add-wish waves-effect" data-prod-prod_id="' + data[i].prod_id + '"><i class="material-icons">favorite_border</i> Add to Wishlist</button></div></div></div>');
				}
			}
		},
		error: function error() {}
	});
}

var cart_list = new cookieList("cartItems");

var temp_product_all_subtotals;
var temp_product_all_quantities;
function load_modal_items() {
	$("#order_modal_items").empty();
	var i;
	var cart_items = cart_list.items();
	if (cart_items.length > 0) {
		var items = String(cart_items).split(",");
		//alert(items.length);
		for (i = 0; i < items.length; i++) {

			var item_split_info = items[i].split(":");
			//alert(item_split_info[0]);
			getProductInfo(item_split_info[0], item_split_info[1], "modal", base_url);
		}
	}
}

function load_modal_exceed_items(exceed_items) {
	$("#order_modal_exceed_items").empty();
	var i;
	var cart_items = JSON.parse(exceed_items);
	if (cart_items.length > 0) for (i = 0; i < cart_items.length; i++) {

		var item_split_info = cart_items[i].split(":");
		//alert(item_split_info[0]);
		getProductInfo(item_split_info[0], item_split_info[1], "modal_exceed", base_url);
	}
}

//The syntax below will initialize the variable in this page that will store the COOKIE//
function load_cart_items() {
	$("#cart-list-nav").empty();
	var i;
	var cart_items = cart_list.items();
	if (cart_items == null) {
		$('#cart-list-span').empty();
		$('#cart-list-span').append('0');
	} else if (cart_items.length <= 0) {
		$('#cart-list-span').empty();
		$('#cart-list-span').append('0');
		$('#cart_go_go').hide('10');
		$("#cart-list-nav").append('<li><a href="javascript:void(0)" class=" waves-effect waves-block"><div class="icon-circle"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + '"></div><div class="menu-info"><br/><h4>No Products Added to Cart</h4></div> </a></li>');
	} else if (cart_items.length > 0) {
		$('#cart_go_go').show('10');
		var items = String(cart_items).split(",");
		//alert(items.length);
		for (i = 0; i < items.length; i++) {

			var item_split_info = items[i].split(":");
			//alert(item_split_info[0]);
			getProductInfo(item_split_info[0], item_split_info[1], "navbar", base_url);
		}
	}
}
function check_if_product_has_size_or_color(prod_id) {
	$.ajax({
		type: 'ajax',
		url: base_url + "customer/getProducts_Option/" + prod_id,
		async: true,
		dataType: 'json',
		success: function success(data) {
			if (data == "has_sizes") {
				$('#has_colors').remove();
			} else if (data == "has_colors") {
				$('#has_sizes').remove();
			} else if (data == "has_colors_sizes") {} else if (data == "no_options") {
				$('#has_colors').remove();
				$('#has_sizes').remove();
			}
		}
	});
}

function load_cart_cart() {
	var i;
	var cart_items = cart_list.items();
	if (cart_items.length > 0) {
		var items = String(cart_items).split(",");
		//alert(items.length);
		for (i = 0; i < items.length; i++) {

			var item_split_info = items[i].split(":");
			getProductInfo(item_split_info[0], item_split_info[1], "for_cart_view", base_url);
		}
	}
}
//The syntax below will initialize the variable in this page that will store the COOKIE//
function load_order_summary() {
	var i;
	var cart_items = cart_list.items();
	if (cart_items.length > 0) {
		var items = String(cart_items).split(",");
		//alert(items.length);
		for (i = 0; i < items.length; i++) {

			var item_split_info = items[i].split(":");
			//alert(item_split_info[0]);
			getProductInfo(item_split_info[0], item_split_info[1], "for_checkout", base_url);
		}
	}
}

function addProductToCart(prod_id, quantity) {
	var i = 0;
	var items = cart_list.items();
	if (items.length > 0) {
		for (var _i6 = 0; _i6 < items.length; _i6++) {
			var item_prod_id = items[_i6].split(":");
			if (item_prod_id[0] == prod_id) {
				cart_list.edit(item_prod_id[0] + ":" + item_prod_id[1], quantity, prod_id);
				break;
			} else if (item_prod_id[0] != prod_id && _i6 == items.length - 1) {
				cart_list.add(prod_id + ":" + parseInt(quantity));
				break;
			}
		}
	} else {
		cart_list.add(prod_id + ":" + quantity);
	}
	temp_product_all_subtotals = 0;
	temp_product_all_quantities = 0;
}