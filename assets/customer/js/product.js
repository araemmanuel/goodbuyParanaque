"use strict";

var temp_product_all_subtotals = 0;
var temp_product_all_quantities = 0;

function getProductInfo(prod_id, quantity, type_of_element, url_url) {
	var items = cart_list.items();
	console.log(items.length);
	$.ajax({
		type: 'ajax',
		url: url_url + "customer/getProductInfo/" + prod_id,
		async: true,
		dataType: 'json',
		success: function success(data) {
			if (type_of_element == "navbar") {
				var new_selling_price = void 0;
				if (data[0].discount_percent == "0") {
					$("#cart-list-nav").append('<li><a href="' + base_url + 'customer/view_product/' + prod_id + '" class=" waves-effect waves-block"><div class="icon-circle"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + url_url + data[0].img_file_path + '"></div><div class="menu-info"><h4>' + data[0].name.trunc(12) + '</h4><p> &#8369;' + data[0].selling_price + '</p><p>Quantity: ' + quantity + '</p></div> </a></li>');
				} else {
					new_selling_price = parseFloat(data[0].selling_price - data[0].discount_percent / 100 * data[0].selling_price).toFixed(2);
					$("#cart-list-nav").append('<li><a href="' + base_url + 'customer/view_product/' + prod_id + '" class=" waves-effect waves-block"><div class="icon-circle"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + url_url + data[0].img_file_path + '"></div><div class="menu-info"><h4>' + data[0].name.trunc(12) + '</h4><p>&#8369;' + new_selling_price + '</p><p class="font-line-through"> &#8369;' + data[0].selling_price + '</p><p>Quantity: ' + quantity + '</p></div> </a></li>');
				}

				$("#cart-list-span").text(items.length);
			} else if (type_of_element == "modal") {
				var _new_selling_price = void 0;
				if (data[0].discount_percent == "0") {
					_new_selling_price = data[0].selling_price;
				} else {
					_new_selling_price = parseFloat(data[0].selling_price - data[0].discount_percent / 100 * data[0].selling_price).toFixed(2);
				}

				var total_item_price = parseFloat(_new_selling_price * quantity).toFixed(2);

				$("#order_modal_items").append('<tr><td class="product_in_cart"><div class="product-pic-small-cart-view"><div class="row"><div class="col-lg-4"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + url_url + data[0].img_file_path + '" class="size_options" data-image-holder="' + url_url + data[0].img_file_path + '"></div><div class="col-lg-8"><p>' + data[0].name.trunc(10) + '</p></div></div></div></td><td class="product_cart_price">&#8369; ' + _new_selling_price + '</td><td class="product_in_cart_quantity">x ' + quantity + '</td><td class="product_cart_price">&#8369; ' + total_item_price + '</td></tr>');
				temp_product_all_quantities = parseInt(+temp_product_all_quantities + +quantity);
				temp_product_all_subtotals = parseFloat(+temp_product_all_subtotals + +total_item_price).toFixed(2);
				$("#order_sub_total").empty();
				$("#order_sub_total").append('&#8369;' + temp_product_all_subtotals);
				$("#total_items_count").empty();
				$("#total_items_count").append(temp_product_all_quantities);
				var total_totalled = parseFloat(parseFloat(temp_product_all_subtotals) + parseFloat(current_ship_fee)).toFixed(2);
				$("#total_total_total").empty();
				$("#total_total_total").append('&#8369;' + total_totalled);
			} else if (type_of_element == "modal_exceed") {
				var _new_selling_price2 = void 0;
				if (data[0].discount_percent == "0") {
					_new_selling_price2 = data[0].selling_price;
				} else {
					_new_selling_price2 = parseFloat(data[0].selling_price - data[0].discount_percent / 100 * data[0].selling_price).toFixed(2);
				}

				var total_item_price = parseFloat(_new_selling_price2 * quantity).toFixed(2);

				$("#order_modal_exceed_items").append('<tr><td class="product_in_cart"><div class="product-pic-small-cart-view"><div class="row"><div class="col-lg-4"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + url_url + data[0].img_file_path + '" class="size_options" data-image-holder="' + url_url + data[0].img_file_path + '"></div><div class="col-lg-8"><p>' + data[0].name.trunc(10) + '</p></div></div></div></td><td class="product_cart_price">&#8369; ' + _new_selling_price2 + '</td><td class="product_in_cart_quantity">x ' + quantity + '</td><td class="product_in_cart_quantity">x ' + data[0].quantity + '</td></tr>');
				temp_product_all_quantities = parseInt(+temp_product_all_quantities + +quantity);
				temp_product_all_subtotals = parseFloat(+temp_product_all_subtotals + +total_item_price).toFixed(2);
				$("#order_sub_total").empty();
				$("#order_sub_total").append('&#8369;' + temp_product_all_subtotals);
				$("#total_items_count").empty();
				$("#total_items_count").append(temp_product_all_quantities);
				var total_totalled = parseFloat(parseFloat(temp_product_all_subtotals) + parseFloat(current_ship_fee)).toFixed(2);
				$("#total_total_total").empty();
				$("#total_total_total").append('&#8369;' + total_totalled);
			} else if (type_of_element == "for_cart_view") {
				var _new_selling_price3 = void 0;
				if (data[0].discount_percent == "0") {
					_new_selling_price3 = data[0].selling_price;
				} else {
					_new_selling_price3 = parseFloat(data[0].selling_price - data[0].discount_percent / 100 * data[0].selling_price).toFixed(2);
				}

				var total_item_price = parseFloat(+_new_selling_price3 * +quantity).toFixed(2);
				var temp = "";
				temp = temp + '<tr class="Korean-Wholesome-Terno-row tr_row" id="tr_' + prod_id + '"><td class="product_in_cart"><div class="order-column"><div class="row products_in_orders"><div class="col-lg-4"><div class="product-pic-small-order-view"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + data[0].img_file_path + '" class="size_options"></div></div><div class="col-lg-6"><div class="product-pic-small-order-view-details"><small>' + data[0].name.trunc(10) + '</small><br/><small>&#8369; ' + _new_selling_price3 + '</small></div></div></div></div></td>';
				temp = temp + '<td class="product_in_cart_quantity"><div class="input-group spinner" data-trigger="spinner"><div class="form-line"><input type="text" class="form-control text-center quantity-input" value="' + quantity + '" data-rule="quantity" id="input_qnty_' + prod_id + '" data-id_input_mx="' + data[0].quantity + '" data-id_price="' + _new_selling_price3 + '"  onkeydown="return onkey_down(event,' + "'" + prod_id + "'" + ');" /> ';
				temp = temp + '</div><span class="input-group-addon"><a class="spin-up" data-spin="up" data-id_input="' + prod_id + '" onclick="' + "spin_up_clicked('" + prod_id + "');" + '"><i class="glyphicon glyphicon-chevron-up"></i></a><a  class="spin-down" data-spin="down" data-id_input="' + prod_id + '" onclick="' + "spin_down_clicked('" + prod_id + "');" + '"><i class="glyphicon glyphicon-chevron-down"></i></a></span></div></td><td class="product_cart_price product_cart_item_price" data-id_input_price="' + total_item_price + '"id="price_of_sub_total_' + prod_id + '">&#8369; ' + total_item_price + '</td>';
				temp = temp + '<td><button type="button" class="btn bg-green" id="input_remove_' + prod_id + '" cancel_items_btn  waves-effect" onclick="' + "remove_clicked('" + prod_id + "'," + quantity + ");" + '">Remove from Cart</button></td></tr>';
				$("#items_in_cart").append(temp);
				temp_product_all_quantities = parseInt(+temp_product_all_quantities + +quantity);
				temp_product_all_subtotals = parseFloat(+temp_product_all_subtotals + +total_item_price).toFixed(2);
				$("#order_sub_total").empty();
				$("#order_sub_total").append('&#8369;' + temp_product_all_subtotals);
				$("#total_items_count").empty();
				$("#total_items_count").append(temp_product_all_quantities);

				var total_totalled = parseFloat(parseFloat(temp_product_all_subtotals) + parseFloat(current_ship_fee)).toFixed(2);
				$("#total_total_total").empty();
				$("#total_total_total").append('&#8369;' + total_totalled);
			} else if (type_of_element == "for_checkout") {
				var _new_selling_price4 = void 0;
				var points_equi = void 0;
				if (data[0].discount_percent == "0") {
					_new_selling_price4 = data[0].selling_price;
				} else {
					_new_selling_price4 = parseFloat(data[0].selling_price - data[0].discount_percent / 100 * data[0].selling_price).toFixed(2);
				}
				var total_item_price = parseFloat(+_new_selling_price4 * +quantity).toFixed(2);
				if (data[0].discount_percent == "0") {
					$("#order_cart_checkout_items").append('<tr><td class="product_in_cart"><div class="product-pic-small-cart-view"><div class="row"><div class="col-lg-4"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + url_url + data[0].img_file_path + '" class="size_options" data-image-holder="' + url_url + data[0].img_file_path + '"></div><div class="col-lg-8"><p>' + data[0].name.trunc(10) + '</p></div></div></div></td><td class="product_cart_price">&#8369; ' + _new_selling_price4 + '</td><td class="product_in_cart_quantity">x ' + quantity + '</td></tr>');
				} else {
					$("#order_cart_checkout_items").append('<tr><td class="product_in_cart"><div class="product-pic-small-cart-view"><div class="row"><div class="col-lg-4"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + url_url + data[0].img_file_path + '" class="size_options" data-image-holder="' + url_url + data[0].img_file_path + '"></div><div class="col-lg-8"><p>' + data[0].name.trunc(10) + '</p></div></div></div></td><td class="product_cart_price"><small>&#8369; ' + _new_selling_price4 + '</small><br/><small class="product_cart_price font-line-through">&#8369;' + data[0].selling_price + '</small></td><td class="product_in_cart_quantity">x ' + quantity + '</td></tr>');
				}

				temp_product_all_quantities = parseInt(+temp_product_all_quantities + +quantity);
				temp_product_all_subtotals = parseFloat(+temp_product_all_subtotals + +total_item_price).toFixed(2);
				points_equi = parseInt(temp_product_all_subtotals / 200);
				//reward_card_equi
				//alert(points_equi);
				$("#reward_card_equi").empty();
				$("#reward_card_equi").append(points_equi + " point(s)");
				$('input[name="checkout_reward_equivalent"]').val(temp_product_all_subtotals);
				$("#order_sub_total_checkout").empty();
				$("#order_sub_total_checkout").append('&#8369;' + temp_product_all_subtotals);
				$("#total_items_count_checkout").empty();
				$("#total_items_count_checkout").append(temp_product_all_quantities);
				$('#shipping_fee_td').empty();
				$('#shipping_fee_td').append("&#8369;" + parseFloat(current_ship_fee).toFixed(2));
				var total_totalled = parseFloat(parseFloat(temp_product_all_subtotals) + parseFloat(current_ship_fee)).toFixed(2);
				$("#total_total_total_checkout").empty();
				$("#total_total_total_checkout").append('&#8369;' + total_totalled);
			}
		},
		error: function error() {
			//alert("Oyy :(");
		}
	});
}