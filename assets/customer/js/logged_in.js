"use strict";

var success_update = false;
$(document).ready(function () {
	var is_use_home_address = true;
	$('input[name="use_home_address"]').click(function () {
		if (is_use_home_address == true) {
			is_use_home_address = false;
			$('input[name="checkout_house"]').val('');
			$('input[name="checkout_street"]').val('');
			$('input[name="checkout_zip_code"]').val('');
		} else {
			is_use_home_address = true;
			if (customer_details['shipping_address'] == null) {
				$('input[name="checkout_house"]').val(null);
				$('input[name="checkout_street"]').val(null);
			} else {
				var customer_address = customer_details['shipping_address'].split(",");
				$('input[name="checkout_house"]').val(customer_address[0]);
				$('input[name="checkout_street"]').val(customer_address[1]);
			}
			$('input[name="checkout_zip_code"]').val(customer_details['shipping_zipcode']);
		}
	});

	if (window.location.href == base_url + 'customer/view_profile') {
		var profile_data_before_edit = $('#form_edit').serialize();
		window.onbeforeunload = function (e) {
			var profile_date_now_edit = $('#form_edit').serialize;
			if (profile_data_before_edit != profile_date_now_edit) {
				if (success_update == false) {
					var confirmationMessage = 'It looks like you have been editing something. ' + 'If you leave before saving, your changes will be lost.';

					(e || window.event).returnValue = confirmationMessage; //Gecko + IE
					return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
				}
			}
		};
	}
});

function check_logged_in(method) {

	var is_logged_for_checkout;
	$.ajax({
		type: 'ajax',
		url: base_url + "customer/echo_is_logged",
		async: true,
		dataType: 'json',
		success: function success(data) {
			if (data['card_no'] == null) {
				$('#view_reward_points').hide();
			}
			if (method == "checkout") {

				if (data == "false" || data == null || data == undefined || data == false) {
					$('#use_home_address_input').hide();
					$('#use_home_address_label').hide();
				} else {
					customer_details = data;
					//alert(customer_details['shipping_city']);
					if (customer_details['shipping_city'] != "Paranaque") {
						$('#use_home_address_input').remove();
						$('#use_home_address_label').remove();
					}

					$('input[name="checkout_address"]').val(customer_details['shipping_address']);
					$('input[name="checkout_address"]').prop('readonly', true);
					//let customer_address = customer_details['shipping_address'].split(",");
					$('input[name="checkout_username"]').val(data['username']);
					$('input[name="checkout_username"]').prop('readonly', true);
					$('input[name="checkout_mname"]').val(data['middlename']);
					$('input[name="checkout_mname"]').prop('readonly', true);
					$('input[name="checkout_lname"]').val(data['lastname']);
					$('input[name="checkout_lname"]').prop('readonly', true);
					$('input[name="checkout_fname"]').val(data['firstname']);
					$('input[name="checkout_fname"]').prop('readonly', true);
					$('input[name="checkout_email"]').val(data['email']);
					$('input[name="checkout_email"]').prop('readonly', true);
					$('input[name="checkout_phone"]').val(data['contact_no']);
					$('input[name="checkout_phone"]').prop('readonly', true);
					//$('input[name="checkout_house"]').val(customer_address[0]);
					//$('input[name="checkout_street"]').val(customer_address[1]);
					$('input[name="checkout_zipcode"]').val(customer_details['shipping_zipcode']);
					var reward_points = Math.floor(data['receipt_price'] / 200);
					if (data['card_no'] != null) {
						$('#before_equivalent').after('<tr><td>Reward Card Points Equivalent</td><td class="font-bold" id="reward_card_equi"></td></tr>');
						if (reward_points >= 1) {
							$('#before_reward').after('<div class="row clearfix" id="current_reward"><div class="col-sm-12 inline_divs"><div class="demo-switch-title col-green font-12">Use My Reward Points</div><div class="switch"><label><input type="checkbox" name="use_reward_points_toggle" id="use_reward_points_toggle_id"><span class="lever switch-col-green"></span></label></div><p style="display: inline-block !important;">You have ' + reward_points + ' point(s) at this moment.</p></div></div>');

							$('#current_reward').after('<div class="row clearfix" id="input_reward_points"><div class="col-sm-6 col-lg-6"><div class="form-group"><div class="form-line"><input type="number" value="1" min="1" max = "' + reward_points + '"class="form-control" placeholder="Points To Be Used" name="checkout_rewards_used" /></div><span id="checkout_rewards_used_label" class="small-smaller"></span></div></div></div>');
							$('#input_reward_points').hide();
							$('#use_reward_points_toggle_id').click(function () {
								$('#input_reward_points').toggle();
							});
						}
					} else {
						$('#avail_reward_card').append('<br/><div class="row clearfix"><div class="col-sm-6 col-lg-6"><div class="form-group"><input type="checkbox" id="md_checkbox_1" class="chk-col-green filled-in" /><label for="md_checkbox_1" class="font-12 col-black">I would like to avail a GB reward card.</label></div></div>');
					}
				}
			}
		}

	});
	if (method == "manage_order") {
		$.ajax({
			type: 'ajax',
			url: base_url + "customer/get_orders",
			async: true,
			dataType: 'json',
			success: function success(data) {
				if (data == "false" || data == null || data == undefined) {} else {
					var _order_no = "";
					var price_of_order = 0;
					$('#order_table_holder').append('<table class="table table-striped orders_table_list table-hover js-basic-example dataTable" id="order_table"><thead><tr><th>Order Code</th><th class="product_cart_price">Price</th><th>Ordered Date</th><th>Status</th><th>Action</th></tr></thead><tfoot><tr><th>Order Code</th><th class="product_cart_price">Price</th><th>Ordered Date</th><th>Status</th><th>Action</th></tr></tfoot><tbody class="table-of-orders" id="table_of_orders_vw"></tbody></table>');
					data.forEach(function (element) {
						if (element['order_no'] != _order_no) {
							price_of_order = parseFloat(element['shipping_fee']);
							var temp = "";
							temp = temp + '<tr><td>' + element['order_no'] + '</td>';
							temp = temp + '<td class="product_cart_price" id="order_price_' + element['order_no'] + '"></td><td class="order_date">' + element['order_date'] + '</td><td>' + element['order_status'] + '</td><td><a href="' + base_url + 'customer/manage_order/' + element['order_no'] + '"><button type="button" class="btn bg-green btn-block btn-sm waves-effect">Manage Order</button></a></td></tr>';
							$('#table_of_orders_vw').append(temp);
							_order_no = element['order_no'];
							if (element['item_order_status'] != "Cancelled") {
								if (element['discount_percent'] == 0) {
									price_of_order = parseFloat(price_of_order + parseFloat(element['selling_price'] * element['quantity']));
								} else {
									price_of_order = parseFloat(price_of_order + parseFloat(parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100))) * element['quantity']));
								}
							}
							$('#order_price_' + element['order_no']).empty();
							$('#order_price_' + element['order_no']).append("&#8369; " + parseFloat(price_of_order).toFixed(2));
						} else {

							if (element['item_order_status'] != "Cancelled") {
								if (element['discount_percent'] == 0) {
									price_of_order = parseFloat(price_of_order + parseFloat(element['selling_price'] * element['quantity']));
								} else {
									price_of_order = parseFloat(price_of_order + parseFloat(parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100))) * element['quantity']));
								}
							}
							$('#order_price_' + element['order_no']).empty();
							$('#order_price_' + element['order_no']).append("&#8369; " + parseFloat(price_of_order).toFixed(2));
						}
					});
					$('.js-basic-example').DataTable({
						responsive: true
					});
				}
			}
		});
	} else if (method == "manage_reward") {
		$.ajax({
			type: 'ajax',
			url: base_url + "customer/getReward",
			async: true,
			dataType: 'json',
			success: function success(data) {
				if (data == "false" || data == null || data == undefined) {} else {
					$('#header_with_reward').empty();
					$('#header_with_reward').append('<p>You currently have <b>' + Math.floor(data['receipt_price'] / 200) + ' point(s)</b>.</p>');
				}
			}
		});
		$.ajax({
			type: 'ajax',
			url: base_url + "customer/get_orders_with_point",
			async: true,
			dataType: 'json',
			success: function success(data) {
				if (data == "false" || data == null || data == undefined) {} else {
					var _order_no2 = "";
					var price_of_order = 0;
					$('#order_table_holder').append('<table class="table table-striped orders_table_list table-hover js-basic-example dataTable" id="order_table"><thead><tr><th>Order Code</th><th class="product_cart_price">Price</th><th>Ordered Date</th><th>Status</th><th>Reward Transaction</th></tr></thead><tfoot><tr><th>Order Code</th><th class="product_cart_price">Price</th><th>Ordered Date</th><th>Status</th><th>Reward Transaction</th></tr></tfoot><tbody class="table-of-orders" id="table_of_orders_vw"></tbody></table>');

					data.forEach(function (element) {

						if (element['order_no'] != _order_no2) {
							price_of_order = parseFloat(element['shipping_fee']);
							var temp_1 = "";
							temp_1 = temp_1 + '<tr><td>' + element['order_no'] + '</td>';
							//temp_1 = temp_1 + '<td class="product_cart_price" id="order_price_'+element['order_no']+'"></td><td class="order_date">'+element['order_date']+'</td><td>'+element['order_status']+'</td><td style="display: inline-block !important;"><p class="col-red">-'+element['points_used']+'</p><p class="col-green">+'+element['points_equivalent']+'</p></td></tr>';
							if (element['points_used'] != null && element['points_equivalent'] != null) {
								if (element['points_equivalent'] == 0) {
									temp_1 = temp_1 + '<td class="product_cart_price" id="order_price_' + element['order_no'] + '"></td><td class="order_date">' + element['order_date'] + '</td><td>' + element['order_status'] + '</td><td style="display: inline-block !important;"><p class="col-red" style="display:inline-block !important;">-' + element['points_used'] + '</p><p class="col-yellow" style="display:inline-block !important;">' + element['points_equivalent'] + '</p></td></tr>';
								} else {
									temp_1 = temp_1 + '<td class="product_cart_price" id="order_price_' + element['order_no'] + '"></td><td class="order_date">' + element['order_date'] + '</td><td>' + element['order_status'] + '</td><td style="display: inline-block !important;"><p class="col-red" style="display:inline-block !important;" style="display:inline-block !important;">-' + element['points_used'] + '</p><p class="col-green" style="display:inline-block !important;">+' + element['points_equivalent'] + '</p></td></tr>';
								}
							} else if (element['points_used'] == null && element['points_equivalent'] != null) {
								if (element['points_equivalent'] == 0) {
									temp_1 = temp_1 + '<td class="product_cart_price" id="order_price_' + element['order_no'] + '"></td><td class="order_date">' + element['order_date'] + '</td><td>' + element['order_status'] + '</td><td style="display: inline-block !important;"><p class="col-yellow">0</p></td></tr>';
								} else {
									temp_1 = temp_1 + '<td class="product_cart_price" id="order_price_' + element['order_no'] + '"></td><td class="order_date">' + element['order_date'] + '</td><td>' + element['order_status'] + '</td><td style="display: inline-block !important;"><p class="col-green">+' + element['points_equivalent'] + '</p></td></tr>';
								}
							} else if (element['points_used'] != null && element['points_equivalent'] == null) {
								temp_1 = temp_1 + '<td class="product_cart_price" id="order_price_' + element['order_no'] + '"></td><td class="order_date">' + element['order_date'] + '</td><td>' + element['order_status'] + '</td><td style="display: inline-block !important;"><p class="col-red">-' + element['points_used'] + '</p></td></tr>';
							} else {
								temp_1 = temp_1 + '<td class="product_cart_price" id="order_price_' + element['order_no'] + '"></td><td class="order_date">' + element['order_date'] + '</td><td>' + element['order_status'] + '</td><td style="display: inline-block !important;"><p class="col-yellow">0</p></td></tr>';
							}

							$('#table_of_orders_vw').append(temp_1);
							_order_no2 = element['order_no'];
							if (element['item_order_status'] != "Cancelled") {
								if (element['discount_percent'] == 0) {
									price_of_order = parseFloat(price_of_order + parseFloat(element['selling_price'] * element['quantity']));
								} else {
									price_of_order = parseFloat(price_of_order + parseFloat(parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100))) * element['quantity']));
								}
							}
							$('#order_price_' + element['order_no']).empty();
							$('#order_price_' + element['order_no']).append("&#8369; " + parseFloat(price_of_order).toFixed(2));
						} else {

							if (element['item_order_status'] != "Cancelled") {
								if (element['discount_percent'] == 0) {
									price_of_order = parseFloat(price_of_order + parseFloat(element['selling_price'] * element['quantity']));
								} else {
									price_of_order = parseFloat(price_of_order + parseFloat(parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100))) * element['quantity']));
								}
							}
							$('#order_price_' + element['order_no']).empty();
							$('#order_price_' + element['order_no']).append("&#8369; " + parseFloat(price_of_order).toFixed(2));
						}
					});
					$('.js-basic-example').DataTable({
						responsive: true
					});
				}
			}
		});
	} else if (method == "verify_checkout") {
		var order_no_page_verify = window.location.href.substring(window.location.href.indexOf("verify_checkout") + 16);
		var keys_and_points = order_no_page_verify.split("/");
		var order_no = keys_and_points[0];
		var goto_url = base_url + 'customer/verify_order_function/' + order_no;
		var points_gained = keys_and_points[1];
		var points_used = keys_and_points[2];
		if (keys_and_points[1] == undefined) {
			points_gained = 0;
		} else {
			goto_url = goto_url + '/' + points_gained;
		}
		if (keys_and_points[2] == undefined) {
			points_used = 0;
		} else {
			goto_url = goto_url + '/' + points_used;
		}
		//alert(goto_url);
		//var order_no_page_verify = window.location.href.substring(window.location.href.indexOf("verify_checkout") + 16);
		//var order_no_page_verify = window.location.href.substring(window.location.href.indexOf("verify_checkout") + 16);
		$('.common_element_view_banner').empty();
		$('.common_element_view_banner').append('<h2>Order #' + order_no + '</h2>');

		$('#verify_my_checkout').click(function () {
			$.ajax({
				type: 'ajax',
				method: 'POST',
				url: goto_url,
				dataType: 'json',
				async: true,
				error: function error(request, _error) {},
				beforeSend: function beforeSend() {
					swal({
						title: 'Order is being verified',
						text: "Note:  If you have email address entered during checkout, we will be sending you this order's receipt.",
						imageUrl: base_url + "assets/customer/images/load_email.gif",
						showCancelButton: false,
						showConfirmButton: false
					});
				},
				complete: function complete() {
					//... your finalization code here (hide loader) ...
				},
				success: function success(msg_errors) {
					if (msg_errors == 'accepted') {
						swal({
							title: 'Checkout Verified Successfully',
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'View Orders',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
						$('.confirm').click(function () {
							window.location.replace(base_url + 'customer/view_orders');
						});
					}
				}
			});
		});
	}
}

function manage_order_page_function() {
	var order_no_page_manage = window.location.href.substring(window.location.href.indexOf("manage_order") + 13);
	$('.common_element_view_banner').empty();
	$('.common_element_view_banner').append('<h2>Order #' + order_no_page_manage + '</h2>');
	$.ajax({
		type: 'ajax',
		url: base_url + "customer/get_order_detail/" + order_no_page_manage,
		async: true,
		dataType: 'json',
		success: function success(data) {
			if (data == "false" || data == null || data == undefined) {} else {
				data.forEach(function (element) {
					var temp = "";
					var price_of_order = 0;
					temp = temp + '<tr class="Korean-Wholesome-Terno-row"><td class="product_in_cart"><div class="order-column"><div class="row products_in_orders"><div class="col-lg-4"><div class="product-pic-small-order-view"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + element['img_file_path'] + '" class="size_options"></div></div><div class="col-lg-6"><div class="product-pic-small-order-view-details">';
					if (element['discount_percent'] == 0) {
						price_of_order = parseFloat(parseFloat(element['selling_price'] * element['quantity']));
						temp = temp + '<small>' + element['name'] + '</small><br/><small>&#8369; ' + element['selling_price'] + '</small><br/><small>x ' + element['quantity'] + '</small>';
					} else {
						var n_selling_price = parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100)));
						price_of_order = parseFloat(price_of_order + parseFloat(parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100))) * element['quantity']));
						temp = temp + '<small>' + element['name'] + '</small><br/><small>&#8369; ' + n_selling_price + '</small><br/><small>x ' + element['quantity'] + '</small>';
					}
					if (element['item_order_status'] == "Cancelled") {
						temp = temp + '<br/><small class="col-red">Cancelled</small>';
					}
					temp = temp + '</div></div></div></div></td><td class="product_cart_price" id="Korean-Wholesome-Terno" data-price-price="">&#8369; ' + parseFloat(price_of_order).toFixed(2) + '</td><td>';
					if (element['order_status'] == 'PENDING') {
						if (element['item_order_status'] != "Cancelled") temp = temp + '<button type="button" class="btn bg-green cancel_items_btn  waves-effect" data-toggle="modal" data-target="#' + element['sku'] + '" data-target-modal="' + element['name'] + '" data-product="' + element['name'] + '">Cancel Item/s</button>';
					}
					temp = temp + '</td></tr>';
					$('#table_of_orders_product').append(temp);
					$("#table-of-orders-div").after('<div class="modal fade" id="' + element['sku'] + '" tabindex="-1" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header bg-green"><h5 class="modal-title col-white" id="defaultModalLabel">Cancel Item</h5><small>Item to be canceled: ' + element['name'] + '</small></div><form id="cancel_item_btn_with_reason' + element['sku'] + '"><div class="modal-body"><h5>Select Reason for Cancelation</h5><br/><select class="form-control show-tick" name="reason_1"><option value="" disabled>-- Please select --</option><option value="Changed mind">Changed mind</option><option value="Decided for alternative product">Decided for alternative product</option><option value="Fees - shipping">Fees - shipping</option><option value="Found cheaper elsewhere">Found cheaper elsewhere</option><option value="Lead time too long">Lead time too long</option></select><br/><h5>Comment (Optional)</h5><div class="input-group"><div class="form-line"><div class="hidden_input"><input type="text" value="' + order_no_page_manage + '" name="order_id" hidden /><input type="text" value="' + element['prod_id'] + '" name="order_prod_id" hidden /><input type="text" value="' + element['sku'] + '" name="order_prod_sku" hidden /></div><textarea class="form-control" rows="3" name="reason_2" placeholder="Comment goes here" autofocus></textarea></div></div></div><div class="modal-footer bg-green"><button type="submit" class="btn bg-light-green waves-effect" id="cancel_item_btn_with_reason_for_form_' + element['sku'] + '" data-form="cancel_item_btn_with_reason' + element['sku'] + '">SAVE CHANGES</button><button type="button" class="btn bg-red waves-effect" data-dismiss="modal" id="dismiss_modal_' + order_no_page_manage + '">CLOSE</button></div></div></form></div></div>');
					$('#cancel_item_btn_with_reason_for_form_' + element['sku']).click(function (e) {
						e.preventDefault();
						var form_to_serialize = $(this).data('form');
						var cancel_item_data = $('#' + form_to_serialize).serialize();
						$.ajax({
							type: 'ajax',
							method: 'POST',
							url: base_url + 'customer/cancel_item_in_order',
							data: cancel_item_data,
							async: true,
							error: function error(request, _error2) {},
							success: function success(msg_errors) {
								if (msg_errors == 'accepted') {
									swal({
										title: 'Item Cancelled Successfully',
										type: 'success',
										showCancelButton: false,
										confirmButtonColor: '#3085d6',
										confirmButtonText: 'View Orders',
										confirmButtonClass: 'btn btn-success',
										buttonsStyling: false,
										reverseButtons: true
									});
									$('.confirm').click(function () {
										location.reload();
									});
								}
							}
						});
					});
				});
			}
		}

	});
}

check_logged_in_sign_up();
function check_logged_in_sign_up() {

	var is_logged_for_checkout;
	$.ajax({
		type: 'ajax',
		url: base_url + "customer/echo_is_logged",
		async: true,
		dataType: 'json',
		success: function success(data) {
			if (data['card_no'] == null) {
				$('#view_reward_points').hide();
			}
			if (data == "false" || data == null || data == undefined) {} else {
				$('input[name="profile_fname"]').val(data['firstname']);
				$('input[name="profile_mname"]').val(data['middlename']);
				$('input[name="profile_lname"]').val(data['lastname']);
				$('input[name="profile_email"]').val(data['email']);
				$('input[name="profile_uname"]').val(data['username']);
				$('input[name="profile_dob"]').val(data['DOB']);
				if (data['gender'] == "Male" || data['gender'] == "male") {
					$('#male').attr('checked', 'checked');
				} else {
					$('#female').attr('checked', 'checked');
				}
				$('#male').prop('disabled', true);
				$('#female').prop('disabled', true);
				$('input[name="profile_address"]').val(data['shipping_address']);
				$('input[name="profile_city"]').val(data['shipping_city']);
				$('input[name="profile_phone"]').val(data['contact_no']);
				$('input[name="profile_state"]').val(data['shipping_state']);
				$('input[name="profile_country"]').val(data['shipping_country']);
				$('input[name="profile_zipcode"]').val(data['shipping_zipcode']);
				if (data['shipping_address'] == null) {
					//let customer_address = data['shipping_address'].split(",");
					$('input[name="profile_house_number"]').val(null);
					$('input[name="profile_street"]').val(null);
				} else {
					var customer_address = data['shipping_address'].split(",");
					$('input[name="profile_house_number"]').val(customer_address[0]);
					$('input[name="profile_street"]').val(customer_address[1]);
				}
			}
		}
	});

	$('input[name="track_order_order_id"').keyup(function (e) {
		//name="track_order_order_id"
		//name="track_order_email"
		if (!e) e = window.event;
		var keyCode = e.keyCode;
		if (keyCode === 13) {
			if ($(this).val().length > 0) {
				if (/\S/.test($(this).val())) {
					$('#track_my_order_btn').trigger('click');
				}
			}
		}
	});

	$('input[name="track_order_email"]').keyup(function (e) {
		//name="track_order_order_id"
		//name="track_order_email"
		if (!e) e = window.event;
		var keyCode = e.keyCode;
		if (keyCode === 13) {
			if ($('input[name="track_order_order_id"]').val() != null) {
				if ($('input[name="track_order_order_id"]').val().length > 0) {
					if (/\S/.test($('input[name="track_order_order_id"]').val())) {
						$('#track_my_order_btn').trigger('click');
					}
				}
			}
		}
	});

	//track_my_order
	$('#track_my_order_btn').click(function (e) {
		e.preventDefault();
		var track_order_data = $('#form_track_order').serialize();
		$.ajax({
			type: 'ajax',
			method: 'POST',
			url: base_url + 'customer/track_order_func',
			data: track_order_data,
			async: true,
			success: function success(msg_errors) {
				if (msg_errors == 'accepted') {
					get_order();
				} else {
					var parse_errors = JSON.parse(msg_errors);

					$.each(parse_errors, function (key, value) {
						$('#' + key + "_label").empty();
					});

					$.each(parse_errors, function (key, value) {
						$('#' + key + "_label").append(value);
					});
				}
			}
		});
	});
	function get_order() {
		var track_my_order_data = $('#form_track_order').serialize();
		$.ajax({
			type: 'ajax',
			method: 'POST',
			url: base_url + 'customer/track_order_function',
			data: track_my_order_data,
			async: true,
			success: function success(msg_error) {
				if (msg_error == '"no result"') {
					$('#card_message_error_handler').empty();
					card_message_error_handler;
					$('#card_message_error_handler').append('<p id="error_m" style="color: red !important;">No order matched, please try again.</p>');
				} else {
					$('#card_message_error_handler_2').show(100);
					$('#error_m').remove();
					$('#form_track_order').hide(100);
					$('#form_track_order').remove();
					$('#message_1').empty();
					$('#message_1').text('Order Found');
					$('#message_2').empty();
					$('#message_2').text('We found the order you have made.  You can now check the status of your order and the items included in the order');
					$('#table_for_order').append('<div class="col-lg-8 col-sm-12"><div class="table-responsive"><table class="table js-basic-example" id="order_table"><thead><tr><th>Order Code</th><th class="product_cart_price">Price</th><th>Ordered Date</th><th>Status</th><th>Action</th></tr></thead><tfoot><tr><th>Order Code</th><th class="product_cart_price">Price</th><th>Ordered Date</th><th>Status</th><th>Action</th></tr></tfoot><tbody class="table-of-orders" id="table_of_orders_vw"></tbody></table></div></div>');
					var order_no = "";
					var price_of_order = 0;
					var data = JSON.parse(msg_error);
					data.forEach(function (element) {
						if (element['order_no'] != order_no) {
							price_of_order = parseFloat(element['shipping_fee']);
							$('#message_s_o').text('Status        : ' + element['order_status']);
							$('#message_d_o').text('Date Ordered  : ' + element['order_date']);
							order_no = element['order_no'];
							if (element['item_order_status'] != "Cancelled") {
								if (element['discount_percent'] == 0) {
									price_of_order = parseFloat(price_of_order + parseFloat(element['selling_price'] * element['quantity']));
								} else {
									var n_selling_price = parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100)));
									price_of_order = parseFloat(price_of_order + parseFloat(parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100))) * element['quantity']));
								}
							}
							var sringed = 'Total Cost    : ' + "&#8369;" + parseFloat(price_of_order).toFixed(2);
							$('#message_t_c').empty();
							$('#message_t_c').append(sringed);
						} else {
							if (element['item_order_status'] != "Cancelled") {
								if (element['discount_percent'] == 0) {
									price_of_order = parseFloat(price_of_order + parseFloat(element['selling_price'] * element['quantity']));
								} else {
									var _n_selling_price = parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100)));
									price_of_order = parseFloat(price_of_order + parseFloat(parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100))) * element['quantity']));
								}
							}
							var _sringed = 'Total Cost    : ' + "&#8369;" + parseFloat(price_of_order).toFixed(2);
							$('#message_t_c').empty();
							$('#message_t_c').append(_sringed);
						}
					});
					order_no = '';
					$('#card_table_of_products').show();
					data.forEach(function (element) {
						var temp = "";
						var price_of_order = 0;
						temp = temp + '<tr class="Korean-Wholesome-Terno-row"><td class="product_in_cart"><div class="order-column"><div class="row products_in_orders"><div class="col-lg-4"><div class="product-pic-small-order-view"><img alt="' + base_url + 'assets/images/no-photo.jpg' + '" onerror="this.onerror=null;this.src=this.alt;" src="' + base_url + element['img_file_path'] + '" class="size_options"></div></div><div class="col-lg-6"><div class="product-pic-small-order-view-details">';
						if (element['discount_percent'] == 0) {
							price_of_order = parseFloat(parseFloat(element['selling_price'] * element['quantity']));
							temp = temp + '<small>' + element['name'] + '</small><br/><small>&#8369; ' + element['selling_price'] + '</small><br/><small>x ' + element['quantity'] + '</small>';
						} else {
							var n_selling_price = parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100)));
							price_of_order = parseFloat(price_of_order + parseFloat(parseFloat(element['selling_price'] - parseFloat(element['selling_price'] * parseFloat(element['discount_percent'] / 100))) * element['quantity']));
							temp = temp + '<small>' + element['name'] + '</small><br/><small>&#8369; ' + n_selling_price + '</small><br/><small>x ' + element['quantity'] + '</small>';
						}
						if (element['item_order_status'] == "Cancelled") {
							temp = temp + '<br/><small class="col-red">Cancelled</small>';
						}
						temp = temp + '</div></div></div></div></td><td class="product_cart_price" id="Korean-Wholesome-Terno" data-price-price="">&#8369; ' + parseFloat(price_of_order).toFixed(2) + '</td><td>';
						if (element['order_status'] == 'PENDING') {
							if (element['item_order_status'] != "Cancelled") temp = temp + '<button type="button" class="btn bg-green cancel_items_btn  waves-effect" data-toggle="modal" data-target="#' + element['sku'] + '" data-target-modal="' + element['name'] + '" data-product="' + element['name'] + '">Cancel Item/s</button>';
						}
						temp = temp + '</td></tr>';
						$('#table_of_orders_product').append(temp);
						$("#modal_and_others").after('<div class="modal fade" id="' + element['sku'] + '" tabindex="-1" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header bg-green"><h5 class="modal-title col-white" id="defaultModalLabel">Cancel Item</h5><small>Item to be canceled: ' + element['name'] + '</small></div><form id="cancel_item_btn_with_reason' + element['sku'] + '"><div class="modal-body"><h5>Select Reason for Cancelation</h5><br/><select class="form-control show-tick" name="reason_1"><option value="" disabled>-- Please select --</option><option value="Changed mind">Changed mind</option><option value="Decided for alternative product">Decided for alternative product</option><option value="Fees - shipping">Fees - shipping</option><option value="Found cheaper elsewhere">Found cheaper elsewhere</option><option value="Lead time too long">Lead time too long</option></select><br/><h5>Comment (Optional)</h5><div class="input-group"><div class="form-line"><div class="hidden_input"><input type="text" value="' + element['order_no'] + '" name="order_id" hidden /><input type="text" value="' + element['prod_id'] + '" name="order_prod_id" hidden /><input type="text" value="' + element['sku'] + '" name="order_prod_sku" hidden /></div><textarea class="form-control" rows="3" name="reason_2" placeholder="Comment goes here" autofocus></textarea></div></div></div><div class="modal-footer bg-green"><button type="submit" class="btn bg-light-green waves-effect" id="cancel_item_btn_with_reason_for_form_' + element['sku'] + '" data-form="cancel_item_btn_with_reason' + element['sku'] + '">SAVE CHANGES</button><button type="button" class="btn bg-red waves-effect" data-dismiss="modal" id="dismiss_modal_' + element['order_no'] + '">CLOSE</button></div></div></form></div></div>');
						$('#cancel_item_btn_with_reason_for_form_' + element['sku']).click(function (e) {
							e.preventDefault();
							var form_to_serialize = $(this).data('form');
							var cancel_item_data = $('#' + form_to_serialize).serialize();
							$.ajax({
								type: 'ajax',
								method: 'POST',
								url: base_url + 'customer/cancel_item_in_order_track',
								data: cancel_item_data,
								async: true,
								error: function error(request, _error3) {},
								success: function success(msg_errors) {
									if (msg_errors == 'accepted') {
										swal({
											title: 'Item Cancelled Successfully',
											type: 'success',
											showCancelButton: false,
											confirmButtonColor: '#3085d6',
											confirmButtonText: 'View Orders',
											confirmButtonClass: 'btn btn-success',
											buttonsStyling: false,
											reverseButtons: true
										});
										$('.confirm').click(function () {
											location.reload();
										});
									}
								}
							});
						});
					});
				}
			}
		});
	}
	$('#with_username_password_div').hide();
	$('#with_username_password_id').click(function () {
		$('#with_username_password_div').toggle();
	});
	$('#update_profile_btn').click(function (e) {
		e.preventDefault();
		var profile_data = $('#form_edit').serialize();
		if ($('#with_username_password_div').is(":visible")) {
			$.ajax({
				type: 'ajax',
				method: 'POST',
				url: base_url + 'customer/update_profile_with_password',
				data: profile_data,
				async: true,
				success: function success(msg_errors) {
					if (msg_errors == '"accepted"') {
						swal({
							title: 'Profile Successfully Updated',
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Okay',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
						success_update = true;
						$('.confirm').click(function () {
							window.location.reload();
						});
					} else if (msg_errors == 'account_details_not_accepted') {
						swal({
							title: 'Profile Update Failed.  The name and birthday provided belongs to someone else.',
							type: 'error',
							showCancelButton: false,
							confirmButtonColor: '#8B0000',
							confirmButtonText: 'Okay',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
						success_update = true;
						$('.confirm').click(function () {
							swal.close();
						});
					} else if (msg_errors == "password_new_not_match") {
						$('#profile_pass_n_label').empty();
						$('#profile_pass_n_label').append('<p>The new password not match with confirm new password field!</p>');
					} else if (msg_errors == "email_not_accepted") {
						$('#profile_email_label').empty();
						$('#profile_email_label').append('<p>The email is already used!</p>');
					} else if (msg_errors == "username_not_accepted") {
						$('#profile_uname_label').empty();
						$('#profile_uname_label').append('<p>The username is already used!</p>');
					} else if (msg_errors == "old_password_not_accepted") {
						$('#profile_pass_o_label').empty();
						$('#profile_pass_o_label').append('<p>The old password is incorrect!</p>');
					} else {
						var parse_errors = JSON.parse(msg_errors);

						$.each(parse_errors, function (key, value) {
							$('#' + key + "_label").empty();
						});

						$.each(parse_errors, function (key, value) {
							$('#' + key + "_label").append(value);
						});
					}
				}
			});
		} else {
			$.ajax({
				type: 'ajax',
				method: 'POST',
				url: base_url + 'customer/update_profile',
				data: profile_data,
				async: true,
				success: function success(msg_errors) {
					if (msg_errors == '"accepted"') {
						swal({
							title: 'Profile Successfully Updated',
							type: 'success',
							showCancelButton: false,
							confirmButtonColor: '#3085d6',
							confirmButtonText: 'Okay',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
						success_update = true;
						$('.confirm').click(function () {
							window.location.reload();
						});
					} else if (msg_errors == 'account_details_not_accepted') {
						swal({
							title: 'Profile Update Failed.  The name and birthday provided belongs to someone else.',
							type: 'error',
							showCancelButton: false,
							confirmButtonColor: '#8B0000',
							confirmButtonText: 'Okay',
							confirmButtonClass: 'btn btn-success',
							buttonsStyling: false,
							reverseButtons: true
						});
						success_update = true;
						$('.confirm').click(function () {
							swal.close();
						});
					} else if (msg_errors == "email_not_accepted") {
						$('#profile_email_label').empty();
						$('#profile_email_label').append('<p>The email is already used!</p>');
					} else {
						var parse_errors = JSON.parse(msg_errors);

						$.each(parse_errors, function (key, value) {
							$('#' + key + "_label").empty();
						});

						$.each(parse_errors, function (key, value) {
							$('#' + key + "_label").append(value);
						});
					}
				}
			});
		}
	});
}