"use strict";

$('.spin-up').click(function () {
	//alert($(this).data('id_input'));
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

function change_things(el_id, n_qnty, prod_id) {
	var price_of_sub_total = parseFloat($(el_id).data('id_price'));
	var price_of_item = parseFloat(price_of_sub_total * n_qnty).toFixed(2);
	var p_el_id = '#price_of_sub_total_' + prod_id;
	$(p_el_id).empty();
	$(p_el_id).append('&#8369; ' + price_of_item);
	$(p_el_id).data('id_input_price', price_of_item);
	var input_r = '#input_remove_' + prod_id;
	$(input_r).attr("onclick", "remove_clicked('" + prod_id + "'," + n_qnty + ")");
	var i = 0;
	var items = cart_list.items();
	for (var _i = 0; _i < items.length; _i++) {
		var item_prod_id = items[_i].split(":");
		if (item_prod_id[0] == prod_id) {
			cart_list.edit_item(item_prod_id[0] + ":" + item_prod_id[1], n_qnty, prod_id);
			break;
		}
	}
	load_cart_items();
	var temp_product_all_quantities = 0;
	$('.quantity-input').each(function () {
		temp_product_all_quantities = temp_product_all_quantities + parseInt($(this).val());
	});
	var temp_product_all_subtotals = parseFloat(0);

	$('.product_cart_item_price').each(function () {
		var stringer = parseFloat($(this).data("id_input_price"));
		temp_product_all_subtotals = temp_product_all_subtotals + stringer;
	});
	$("#order_sub_total").empty();
	$("#order_sub_total").append('&#8369;' + parseFloat(temp_product_all_subtotals).toFixed(2));
	$("#total_items_count").empty();
	$("#total_items_count").append(temp_product_all_quantities);
	var total_totalled = parseFloat(+temp_product_all_subtotals + parseFloat(current_ship_fee)).toFixed(2);
	$("#total_total_total").empty();
	$("#total_total_total").append('&#8369;' + total_totalled);
}

function onkey_down(evt, prod_id) {
	var el_id = '#input_qnty_' + prod_id;
	var qnty = parseInt($(el_id).val());
	var qnty_mx = parseInt($(el_id).data('id_input_mx'));
	var n_qnty = 0;

	evt = evt ? evt : window.event;
	var charCode = evt.which ? evt.which : evt.keyCode;
	if ($(el_id).val().length == 0) {
		if (charCode < 49 || charCode == 127 || charCode == 8) {
			$(el_id).val(1);
			n_qnty = 1;
			change_things(el_id, n_qnty, prod_id);
			return false;
		} else if (charCode == 48) {
			$(el_id).val(1);
			n_qnty = 1;
			change_things(el_id, n_qnty, prod_id);
			return false;
		} else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		} else {
			var entered_q = parseInt(String.fromCharCode(charCode));
			if (entered_q > qnty_mx) {
				$(el_id).val(qnty_mx);
				n_qnty = qnty_mx;
				change_things(el_id, n_qnty, prod_id);
				return false;
			} else {
				n_qnty = entered_q;
				$(el_id).val(n_qnty);
				change_things(el_id, n_qnty, prod_id);
				return false;
			}
		}
	} else if ($(el_id).val().length == 1) {
		if (charCode == 8) {
			$(el_id).val(1);
			n_qnty = 1;
			change_things(el_id, n_qnty, prod_id);
			return true;
		} else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		} else {
			var _entered_q = parseInt($(el_id).val() + String.fromCharCode(charCode));
			if (_entered_q > qnty_mx) {
				$(el_id).val(qnty_mx);
				n_qnty = qnty_mx;
				change_things(el_id, n_qnty, prod_id);
				return false;
			} else {
				n_qnty = _entered_q;
				$(el_id).val(n_qnty);
				change_things(el_id, n_qnty, prod_id);
				return false;
			}
		}
	} else {

		if (charCode == 8) {
			var new_q = parseInt($(el_id).val().slice(0, -1));
			n_qnty = new_q;
			$(el_id).val(n_qnty);
			change_things(el_id, n_qnty, prod_id);
			return false;
		} else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		} else {
			var _entered_q2 = parseInt($(el_id).val() + String.fromCharCode(charCode));
			if (_entered_q2 > qnty_mx) {
				$(el_id).val(qnty_mx);
				n_qnty = qnty_mx;
				change_things(el_id, n_qnty, prod_id);
				return false;
			} else {
				n_qnty = _entered_q2;
				$(el_id).val(n_qnty);
				change_things(el_id, n_qnty, prod_id);
				return false;
			}
		}
	}
}

function spin_up_clicked(prod_id) {
	var el_id = '#input_qnty_' + prod_id;
	var qnty = parseInt($(el_id).val());
	var qnty_mx = parseInt($(el_id).data('id_input_mx'));
	var n_qnty = parseInt(qnty + 1);
	if (n_qnty > qnty_mx) {
		$(el_id).val(qnty);
		var input_r = '#input_remove_' + prod_id;
		$(input_r).attr("onclick", "remove_clicked('" + prod_id + "'," + qnty + ")");
	} else {
		var price_of_sub_total = parseFloat($(el_id).data('id_price'));
		var price_of_item = parseFloat(price_of_sub_total * n_qnty).toFixed(2);
		var p_el_id = '#price_of_sub_total_' + prod_id;
		$(p_el_id).empty();
		$(p_el_id).append('&#8369; ' + price_of_item);
		$(p_el_id).data('id_input_price', price_of_item);
		$(el_id).val(n_qnty);
		var _input_r = '#input_remove_' + prod_id;
		$(_input_r).attr("onclick", "remove_clicked('" + prod_id + "'," + n_qnty + ")");
		var i = 0;
		var items = cart_list.items();
		for (var _i2 = 0; _i2 < items.length; _i2++) {
			var item_prod_id = items[_i2].split(":");
			if (item_prod_id[0] == prod_id) {
				cart_list.edit_item(item_prod_id[0] + ":" + item_prod_id[1], n_qnty, prod_id);
				break;
			}
		}
		load_cart_items();
	}
	var temp_product_all_quantities = 0;
	$('.quantity-input').each(function () {
		temp_product_all_quantities = temp_product_all_quantities + parseInt($(this).val());
	});
	var temp_product_all_subtotals = parseFloat(0);

	$('.product_cart_item_price').each(function () {
		var stringer = parseFloat($(this).data("id_input_price"));
		temp_product_all_subtotals = temp_product_all_subtotals + stringer;
	});
	$("#order_sub_total").empty();
	$("#order_sub_total").append('&#8369;' + parseFloat(temp_product_all_subtotals).toFixed(2));
	$("#total_items_count").empty();
	$("#total_items_count").append(temp_product_all_quantities);
	var total_totalled = parseFloat(+temp_product_all_subtotals + parseFloat(current_ship_fee)).toFixed(2);
	$("#total_total_total").empty();
	$("#total_total_total").append('&#8369;' + total_totalled);
}

function spin_down_clicked(prod_id) {
	var el_id = '#input_qnty_' + prod_id;
	var qnty = parseInt($(el_id).val());
	var qnty_mx = parseInt($(el_id).data('id_input_mx'));
	var n_qnty = parseInt(qnty - 1);
	if (n_qnty < 1) {
		$(el_id).val(1);
		var input_r = '#input_remove_' + prod_id;
		$(input_r).attr("onclick", "remove_clicked('" + prod_id + "',1)");
	} else {
		var price_of_sub_total = parseFloat($(el_id).data('id_price'));
		var price_of_item = parseFloat(price_of_sub_total * n_qnty).toFixed(2);
		var p_el_id = '#price_of_sub_total_' + prod_id;
		$(p_el_id).empty();
		$(p_el_id).append('&#8369; ' + price_of_item);
		$(p_el_id).data('id_input_price', price_of_item);
		$(el_id).val(n_qnty);
		var _input_r2 = '#input_remove_' + prod_id;
		$(_input_r2).attr("onclick", "remove_clicked('" + prod_id + "'," + n_qnty + ")");
		var i = 0;
		var items = cart_list.items();
		for (var _i3 = 0; _i3 < items.length; _i3++) {
			var item_prod_id = items[_i3].split(":");
			if (item_prod_id[0] == prod_id) {
				cart_list.edit_item(item_prod_id[0] + ":" + item_prod_id[1], n_qnty, prod_id);
				break;
			}
		}
		load_cart_items();
	}
	var temp_product_all_quantities = 0;
	$('.quantity-input').each(function () {
		temp_product_all_quantities = temp_product_all_quantities + parseInt($(this).val());
	});
	var temp_product_all_subtotals = parseFloat(0);

	$('.product_cart_item_price').each(function () {
		var stringer = parseFloat($(this).data("id_input_price"));
		temp_product_all_subtotals = temp_product_all_subtotals + stringer;
	});
	$("#order_sub_total").empty();
	$("#order_sub_total").append('&#8369;' + parseFloat(temp_product_all_subtotals).toFixed(2));
	$("#total_items_count").empty();
	$("#total_items_count").append(temp_product_all_quantities);
	var total_totalled = parseFloat(+temp_product_all_subtotals + parseFloat(current_ship_fee)).toFixed(2);
	$("#total_total_total").empty();
	$("#total_total_total").append('&#8369;' + total_totalled);
}

function remove_clicked(prod_id, quantity) {
	var i = 0;
	var items = cart_list.items();
	for (var _i4 = 0; _i4 < items.length; _i4++) {
		var item_prod_id = items[_i4].split(":");
		if (item_prod_id[0] == prod_id) {
			cart_list.remove(item_prod_id[0] + ":" + quantity);
			break;
		}
	}
	load_cart_items();
	$('#tr_' + prod_id).remove();
	var temp_product_all_quantities = 0;
	$('.quantity-input').each(function () {
		temp_product_all_quantities = temp_product_all_quantities + parseInt($(this).val());
	});
	var temp_product_all_subtotals = parseFloat(0);

	$('.product_cart_item_price').each(function () {
		var stringer = parseFloat($(this).data("id_input_price"));
		temp_product_all_subtotals = temp_product_all_subtotals + stringer;
	});
	$("#order_sub_total").empty();
	$("#order_sub_total").append('&#8369;' + parseFloat(temp_product_all_subtotals).toFixed(2));
	$("#total_items_count").empty();
	$("#total_items_count").append(temp_product_all_quantities);
	var shipping_feee = parseFloat($('#order_shopping_fee_td').data('fee')).toFixed(2);
	var total_totalled = parseFloat(+temp_product_all_subtotals + +shipping_feee).toFixed(2);
	$("#total_total_total").empty();
	$("#total_total_total").append('&#8369;' + total_totalled);
	var count_row = 0;
	$('.tr_row').each(function () {
		count_row++;
	});
	if (count_row == 0) {
		$('#checkout_btn_cart_link').attr('disabled', true);
		$('#checkout_btn_cart_link').on("click", function (e) {
			e.preventDefault();
		});
		$('#checkout_btn_cart').attr('disabled', true);
		$("#order_sub_total").empty();
		$("#order_sub_total").append('&#8369;' + parseFloat(0).toFixed(2));
		$("#total_items_count").empty();
		$("#total_items_count").append(0);
		var total_totalled = parseFloat(0 + +0).toFixed(2);
		$("#total_total_total").empty();
		$("#total_total_total").append('&#8369;' + total_totalled);
		$("#cart-list-span").empty();
		$("#cart-list-span").append('0');
		$(".table-hover").after('<h2 class="text-center"> Hi, you dont have any item added in the cart </h2><br/>');
	}
}