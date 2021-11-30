
(function($){
	//var baseURL = 'https://almiranezgecilie68358.ipage.com/BSIT2019/goodbuy/';
	//var baseURL = 'http://localhost/goodBuy1/';
	var baseURL = 'http://paranaque.goodbuy-bolinao.com/';
	// var baseURL = 'http://localhost/goodbuy-ph/';
	var timer = null;
	var timeout = 500;
	//var baseURL = 'https://almiranezgecilie68358.ipage.com/BSIT2019/goodbuy/';
	//sessionStorage.setItem('terminal_id',0);
	//sessionStorage.setItem('start_day_success',false);
	
	
	
	$('#btn-pay').attr("disabled", true);		
	$('#payment').on('keyup', function () {
		compute_change('#payment');
		var finalPayment = parseFloat($(this).val() || 0) + parseFloat($('#use-pts').val() || 0);
		if((parseFloat($(this).val() || 0) < parseFloat($('#grand-total').val() || 0)) || (finalPayment < parseFloat($('#grand-total').val() || 0)))
		{
			$('#btn-pay').attr("disabled", true);	
		}	
		else
		{
			$('#add-error').empty();
			$('#btn-pay').attr("disabled",false);		
		}
	});
	$("#div-prod-info").hide();	
	
	if(sessionStorage.getItem('start_day_success') == 'true')
	{
		$('#form-pos :input').removeAttr("disabled");
	}	
	else
	{
		$('#day-status').html('Start Day');
		$('#form-pos :input').attr('disabled','disabled');
		$("#cashier-mode-menu").children().prop('disabled',true);
		$("#btn-end-day").prop('disabled',true);
		
	}
	disable_rpt_type();
	$("#rpt-type").on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) {
		disable_rpt_type();
	});
		
	$("#div-card").hide();
	refreshCardList(baseURL, 'cashier/sales_management/get_membership_id');
	$('#mem-id').on('keyup', function () {
		var address = "cashier/sales_management/get_card_no";

		if($(this).val())
		{
			$('#ul-card').empty();
			$.ajax({
                type: "POST",
                url: baseURL + address,
				context: document.body,
				cache : false,
				data: $('#dp-form').serialize() + "&ajax_card=" +$(this).val(),
				dataType: 'json',
                success: function(data) {
						resultCtr = 1;
						$("#div-card").show();
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
							
							if(p)
								{
									if(resultCtr == 1)
										$('#ul-card').empty();												
									//$('#ul-prod-name').empty();	
									resultCtr++;		
									result = p.toString().split(',');
									if (result[2]) 
									{
										$("#ul-card").append('<li class="li-card li-ajax" data-value="'+result[0]+'" data-points="'+ result[1]+'">'+result[2]+'</li>');						
									}
									else
										$("#ul-card").append('<li class="li-card li-ajax" data-value="'+result[0]+'" data-points="'+ result[1]+'">'+result[0]+'</li>');						
								}	
							
							});
						});

					},
					error: function(req, textStatus, errorThrown) {
						//alert('Prod Name Keyup Error: ' + textStatus + ' ' +errorThrown);
					}
			});				
		}
		else
		{
			$('#stored-pts').html('');
		}
		$("#div-card").hide();
		
	
	});
		
	
	var href = null;
			
	if($("#rpt-type").val() == 'X')
	{
		href = 'cashier/x_report/'+$('#date-from').val()+'\\'+$('#date-to').val()+'\\'+$('#terminal').val()+'\\'+$('#cashier').val();
	}
	else if($("#rpt-type").val() == 'Z')
	{
		href = baseURL + 'cashier/z_report/'+$('#date-from').val()+'\\'+$('#date-to').val()+'\\'+$('#terminal').val()+'\\'+$('#cashier').val();
	}
	
	$("#btn-rpt").on('click', function () {
		if($("#rpt-type").val() == 'open')
		{
			href = baseURL + 'cashier/open_report/'+$('#date-from').val()+'\\'+$('#date-to').val()+'\\'+$('#terminal').val()+'\\'+$('#cashier').val();
		}
		window.open(href,'popup', 'width=800,height=650,scrollbars=no,resizable=yes');	//'width=800,height=650,scrollbars=no,resizable=yes'
	});		
	if (sessionStorage.getItem('cashier_success') == 'true') {
		showNotification('bg-black','Paid Successfully!', 'top', 'right', null, null); 
		sessionStorage.setItem('cashier_success','false');
    }
	if((window.location.href.indexOf('cashier/cashier_mode') > -1) || window.location.href.indexOf("return_item/replacement/") > -1)
		//tag_scanner(baseURL);
	refreshProdCodeList(baseURL, "cashier/sales_management/get_sale_prod_info", false);
	//refreshProdCodeList(baseURL, "admin/sales_management/get_sale_prod_info", true);
	$("#div-prod-code").hide();
	submit_form(baseURL, '#form-return', "#btn-submit-return",$('#form-return').attr('action'), 'cashier/return_item/replacement/');
	$('#prod-code').on('keyup', function () {
		var address = "cashier/sales_management/get_prod_codes";			
		clearTimeout(timer);
		timer = setTimeout(function(){
			generate_prod_codes(baseURL, address, '#prod-code');
		}, timeout);	
	});
	$('#prod-code2').on('keyup', function () {
		var address = "cashier/sales_management/get_prod_codes";			
		clearTimeout(timer);
		timer = setTimeout(function(){
			generate_prod_codes(baseURL, address, '#prod-code2');
		}, timeout);
	});
	$('.check-return').on('click', function () {
		if(window.location.href.indexOf("return_item/replacement") > -1)
		{
			var title = "Are you sure you want to leave this page and cancel return transaction?";
			var msg = "This action cannot be undone.";
			var url = baseURL + "cashier/return_item/cancel_return/"+$('#trans-id').val();
			showConfirmMessage(title, msg, url);	
			return false;					
		}	
	});	
	$('#add-prod').on('click', function () {
		get_pv_info(baseURL, 'cashier/get_pv_info');
		updateScroll();
		$("#prod-code").focus();
		return false;
	});
	$('#btn-open-sday').on('click', function () {
		submit_modal_form('#modal-start-day','#btn-start-day', '#form-start-day', $('#form-start-day').attr('action'), null);
		return false;
	});


	$('#add-prod2').on('click', function () {
		get_pv_info2(baseURL, 'cashier/get_pv_info');
		return false;
	});
	$("#cash").prop('disabled',true);	
	$('#btn-save').on('click', function (e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		$('#btn-save').attr("disabled", true); 
		$('.validation-errors').empty();
		$.ajax({
                    type: "POST",
                    url:  $('#form-replacement').attr('action'),
					context: document.body,
					cache : false,
				    data: $('#form-replacement').serialize(),
					dataType: 'json',
                    success: function(data) {
							$.each(data, function (i,p) {
								$('#'+i+'_error').html(p);
								if(data.error) 
									$('#cash_error').html(data.error_msg);
								
								
							});
							if(data.success)
							{
								window.location = baseURL + 'cashier/return_item/receipt/'+data.trans_id;
							} 
							
							
					},
					error: function(req, textStatus, errorThrown) {
						//this is going to happen when you send something different from a 200 OK HTTP
						alert('Save Error: ' + textStatus + ' ' +errorThrown);
					},
					complete: function(){
                        $('#btn-save').attr("disabled", false);
                    }
			  });	  
	});
	special_discount();
	
	$('#sdisc-type').on('changed.bs.select', function (event, clickedIndex, newValue, oldValue) {
		special_discount();
	});
	$('#discount').on('keyup', function () {
		if($("input[name='d-type']:checked").val() == 'percent' && parseFloat($(this).val() ) > 100)
		{
			$('#add-error').html('Invalid discount percent.');
			$('#add-prod').attr("disabled", true);	
		}	
		else
		{
			$('#add-error').empty();
			$('#add-prod').attr("disabled",false);		
		}
	});

	$('#receipt-discount').on('keyup', function () {
		
		
		if($("input[name='d-type2']:checked").val() == 'percent' && parseFloat($(this).val() ) > 100)
		{
			$('#d-perc-error').html('Invalid discount percent.');
			$('#btn-pay').attr("disabled", true);
		}
		else
		{
			$('#btn-pay').attr("disabled", false);
			$('#d-perc-error').empty();
			$('#new-totall').val(new_total_amt());		
			
			if(new_total_amt() == 0)
			{
				$('#grand-total').val(parseFloat($('#totall').val()));
				$('#earned-pts').html('EARNED POINTS: '+ Math.abs(parseFloat($('#totall').val())/200) );
			}
			else
			{
				$('#grand-total').val(parseFloat($('#new-totall').val()));
				$('#earned-pts').html('EARNED POINTS: '+ Math.abs(parseFloat($('#new-totall').val())/200) );
			}
			special_discount();
			compute_change('#payment');	
		}	
	});	
	$('#btn-pay').click(function(e) {	
		e.preventDefault();
		e.stopImmediatePropagation();
		$('.validation-errors').empty();
		$('#btn-pay').attr("disabled", true);           
		$.ajax({
                    type: "POST",
                    url:  $('#form-pos').attr('action'),
					context: document.body,
					cache : false,
				    data: $('#form-pos').serialize(),
					dataType: 'json',
                    success: function(data) {
							$.each(data, function (i,p) {
								$('#'+i+'_error').html(p);
								if(data.error == 'true') $('[name="'+i+'"]').val(p);
								
							});
							if(data.success)
							{
								window.location = baseURL + 'cashier/cashier_mode/'+data.invoice;
								sessionStorage.setItem('cashier_success','true');
   
							} 
							
							$(this).prop("disabled", false);

					},
					error: function(req, textStatus, errorThrown) {
						//this is going to happen when you send something different from a 200 OK HTTP
						alert('Pay Error: ' + textStatus + ' ' +errorThrown);
					},
					complete: function(){
                        $('#btn-pay').attr("disabled", false);
                    }
			  });	  
	});	
	
	$('#open-payout').on('click', function () {
		submit_modal_form('#modal-payout','#btn-payout', '#form-payout', $('#form-payout').attr('action'), null);
		return false;
	});	
	$('#open-ns').on('click', function () {
		submit_modal_form('#modal-ns','#btn-ns', '#form-ns', $('#form-ns').attr('action'), null);
		return false;
	});	
	$('.cashier-confirm').on('click', function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
		return false;
	});
	$('.open-print-window').on('click', function() {
		window.open($(this).attr('data-href'),'popup', 'width=800,height=650,scrollbars=no,resizable=yes');	//'width=800,height=650,scrollbars=no,resizable=yes'
	});	
	$('#dt-orders').DataTable({  
			"stateSave": true		
	});
	$('#dt-orders tbody').on('click', '.open-deliver-date', function () {
		//load_modal_form('#modal-add-qty','#form-add-qty', 'admin/inventory/get_prod_id/'+ $(this).attr('data-prod-id'));
		$('#modal-order-no').attr('value', $(this).attr('data-order-no'));
		$('#modal-deliver-date').modal('show');
		submit_modal_form('#modal-deliver-date','#btn-set-deliver-date', '#form-set-deliver-date', $(this).attr('data-href'), null);
		return false;
	});	
	$('#dt-orders tbody').on('click', '.confirm', function () {
		showConfirmMessage($(this).attr('data-title'), $(this).attr('data-msg'), $(this).attr('data-url'));
		
		return false;
	});
	$('#discount').attr("disabled", true);
	$('#receipt-discount').attr("disabled", true);
	$('.d-type').on('click', function () {
		if($(this).val() == 'none')
		{
			$('#discount').attr("disabled", true);
			$('#discount').val("");
		}
		else
			$('#discount').attr("disabled", false);
	
	});
	$('.d-type2').on('click', function () {
		if($(this).val() == 'none')
		{
			$('#receipt-discount').attr("disabled", true);
			$('#receipt-discount').val("");
			$('#new-totall').val(0);
			$('#grand-total').val(parseFloat($('#totall').val()));
		}
		else
			$('#receipt-discount').attr("disabled", false);
	
	});
	
	var sessionTimer = setInterval(function(){
	  $.ajax({
				url: baseURL + 'cashier/sessiontimeout',
				context: document.body,
				cache : false,
				beforeSend: function(){},
				success: function(data){
					console.info(data);
				}
			});
	},120000);	
}(jQuery));	
//https://stackoverflow.com/questions/18614301/keep-overflow-div-scrolled-to-bottom-unless-user-scrolls-up

function special_discount()
{
		var total = total_amt();
		var newTotal = new_total_amt();
		
		if($('#sdisc-type').val() == 'SC' || $('#sdisc-type').val() == 'PWD')
		{
			if(newTotal == 0)
			{
				$('#totall').val(total - (total*0.20));
				$('#grand-total').val($('#totall').val());
				$('#earned-pts').html('EARNED POINTS: '+ Math.abs(parseFloat($('#totall').val())/200) );
			
			}
			else
			{
				$('#new-totall').val(newTotal - (newTotal*0.20));	
				$('#grand-total').val($('#new-totall').val());
				$('#earned-pts').html('EARNED POINTS: '+ Math.abs(parseFloat($('#new-totall').val())/200) );
			}
		}
		else
		{
			if(newTotal == 0)
			{
				$('#totall').val(total);
				$('#grand-total').val($('#totall').val());
				$('#earned-pts').html('EARNED POINTS: '+ Math.abs(parseFloat($('#totall').val())/200) );
			}
			else
			{
				$('#new-totall').val(newTotal);
				$('#grand-total').val($('#new-totall').val());
				$('#earned-pts').html('EARNED POINTS: '+ Math.abs(parseFloat($('#new-totall').val())/200) );
			
			}
									
		}
}
function compute_change(paymentSelector)
{
	var cash = parseFloat($(paymentSelector).val());
	if(new_total_amt() > 0)
		$('#change').html((cash-new_total_amt()));
	else
		$('#change').html((cash-total_amt()));		
}
function disable_rpt_type()
{
	if($("#rpt-type").val() == 'X')
		$(".rpt-date").prop('disabled',true);
	else
		$(".rpt-date").prop('disabled',false);	
}

function updateScroll(){
    var element = document.getElementById("item-list");
    element.scrollTop = element.scrollHeight;
}
function generate_prod_codes(baseURL, address, selector)
{
	$( ".validation-errors" ).empty();
		if($(selector).val())
		{
			$('#ul-prod-code').empty();
			$.ajax({
                type: "POST",
                url: baseURL + address,
				context: document.body,
				cache : false,
				data: $('#dp-form').serialize() + "&ajax_prod_code=" +$(selector).val(),
				dataType: 'json',
                success: function(data) {
						resultCtr = 1;
						
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
							
							$("#div-prod-code").show();	
							//end
							
							if(p)
								{	
									if(resultCtr == 1)
										$('#ul-prod-name').empty();
									
									resultCtr++;				
									dropdownArray = p.toString().split('DIV');
									
									if(dropdownArray[0] == 0)
										$("#ul-prod-code").append('<li class="li-prod-code li-ajax" style="color:red;" data-value="'+dropdownArray[2]+'">'+dropdownArray[1]+'</li>');						
									else
										$("#ul-prod-code").append('<li class="li-prod-code li-ajax" data-value="'+dropdownArray[2]+'">'+dropdownArray[1]+'</li>');						
								}	
							
							});
						});

					},
					error: function(req, textStatus, errorThrown) {
						//alert('Generate Prod. Code Error: ' + textStatus + ' ' +errorThrown);
					}
			});				
		}
		$("#div-prod-code").hide();
}
function refreshCardList(baseURL, address) {
    $('#ul-card li').off(); 

	$("#ul-card").on("click", '.li-card' , function(){ 
		card = $(this).attr('data-value');
		if(card.length == 11)
		{
				$.ajax({
					type: "POST",
					url: baseURL + address,
					data: $('#dp-form').serialize() + "&ajax_card=" +card,
					dataType: 'json',
					success: function(data) {
							$("#div-card").show();		
							$.each(data, function(i, p) {
								$.each(p, function(i, p) {			
								if(p)
									{	
										result = p.toString().split(',');
										$('#mem-id').val(result[0]);
										$('#stored-pts').html('<b> STORED POINTS: '+result[1]+'</b>');
										$("#div-card").hide();
									}	     
								});
							});

						},
						error: function(req, textStatus, errorThrown) {
							//alert('Refresh Card Error: ' + textStatus + ' ' +errorThrown);
						}
				});							
		}
		else
		{
			$('#mem-id').val( $(this).attr('data-value'));
			$('#stored-pts').html('<b>STORED PTS: </b>' +$(this).attr('data-points'));
		}
		$("#div-card").hide();
	});
}
function refreshProdCodeList(baseURL, address, replacement) {
    $('#ul-prod-code li').off(); 

	$("#ul-prod-code").on("click", '.li-prod-code' , function(){ 
		sku = $(this).attr('data-value');
		$("#prod-code").val($(this).attr('data-value'));
		$("#prod-code2").val($(this).attr('data-value'));
		$("#prod-qty2").val(1);
		$("#prod-qty").val(1);
		
		$.ajax({
                type: "POST",
                url: baseURL + address,
				context: document.body,
				cache : false,
				data: $('#dp-form').serialize() + "&ajax_sku=" +sku,
				dataType: 'json',
                success: function(data) {
						$("#div-prod-info").show();	
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {			
							if(p)
								{			
									//console.log(i.toString() + " " + p.toString());
									if(i.toString() == 'sku')
									{
										
										$("#prod-code").val(p.toString());
										$("#prod-qty").val(1);	
										$("#prod-code2").val(p.toString());
										$("#prod-qty2").val(1);
										//console.log(p.toString());
									}	
									$('#'+i.toString()).val(p.toString());
								}	     
							});
						});

					},
					error: function(req, textStatus, errorThrown) {
						//alert('Refresh Prod. Code Error: ' + textStatus + ' ' +errorThrown);
					}
			});				

		$("#div-prod-code").hide();
	});
}

function get_pv_info2(baseURL, address)
{
	
	$.ajax({
                type: "POST",
                url: baseURL + address,
				context: document.body,
				cache : false,
				data: $('#dp-form').serialize() + "&ajax_sku=" +$('#prod-code2').val(),
				dataType: 'json',
                success: function(data) {
						resultCtr = 1;
						sku = stock = price = null;
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
								if(p)
									{
										result = p.toString().split('#')
										sku = result[0];
										name = result[1];
										price = result[2];
										stock = result[3];
										options = result[4];	
										//console.log(p.toString());
										
									}	
							
							});
						});
						sku = $('#prod-code2').val();			
						qty = parseInt($('#prod-qty2').val(), 10);
						
						$('.validation-errors').empty();
						if((!qty ||  qty == 0) && !sku)
						{
							$('#add-error').html('Error: Product code and quantity are required.');
						}	
						else if(!sku)
						{
							$('#add-error').html('Error: Product code is required.');
						}
						else if(!qty ||  qty == 0)
						{
							$('#add-error').html('Error: Product quantity is required.');
						}	
						else if( qty <= 0 || stock < qty || stock == 0)
						{
							$('#add-error').html('Error: Invalid Product Quantity.');
						}
						else
						{
							discount = 0;
							amt = qty * price;
							
							var index = getIndex('input[name^="sku[]"]', sku);
							
							if(index < 0)
								clientQty = qty;
							else
								clientQty = parseInt($('input[name^="qty[]"]').eq(index).val(), 10) + qty;
				
							if(!clientQty)
								clientQty = 0;
							//console.log('SKU: '+ sku + ' QTY: ' + clientQty + ' INDEX: '+ index);
							invalid_qty(baseURL, 'return', sku, clientQty, name + '('+options+')', price, discount, qty, amt, index);							
							$('#prod-code2').val(null);
							$('#prod-qty2').val(null);
							$("#div-prod-info").hide();		
								
						}
						

					},
					error: function(req, textStatus, errorThrown) {
						alert('Prod Name Keyup Error: ' + textStatus + ' ' +errorThrown);
					}
			});		
}
function get_pv_info(baseURL, address)
{
	
	$.ajax({
                type: "POST",
                url: baseURL + address,
				context: document.body,
				cache : false,
				data: $('#dp-form').serialize() + "&ajax_sku=" +$('#prod-code').val(),
				dataType: 'json',
                success: function(data) {
						resultCtr = 1;
						sku = stock = price = null;
						$.each(data, function(i, p) {
							$.each(p, function(i, p) {
								if(p)
									{
										result = p.toString().split('#')
										sku = result[0];
										name = result[1];
										price = result[2];
										stock = result[3];	
										options = result[4];	
									}	
							
							});
						});
						sku = $('#prod-code').val();
						qty = parseInt($('#prod-qty').val(), 10);
						$('.validation-errors').empty();
						if(!sku)
						{
							$('#add-error').html('Error: Product code is required.');
						}
						else if(!qty ||  qty == 0)
						{
							$('#add-error').html('Error: Product quantity is required.');
						}	
						else if( qty <= 0 || stock < qty || stock == 0)
						{
							$('#add-error').html('Error: Invalid Product Quantity.');
						}
						else
						{
							if($("input[name='d-type']:checked").val() == 'amount')
							{
								if($('#discount').val())
								{
									discount = (price * qty ) - parseFloat($('#discount').val());
									amt = parseFloat($('#discount').val());
								}
								else
								{
									discount = 0;
									amt = price;
								}
							}
							else if($("input[name='d-type']:checked").val() == 'percent')
							{
								if($('#discount').val())
								{
									amt = (price * qty) - ((price * qty) * (parseFloat($('#discount').val()) / 100));
									discount = (price * qty) - amt;
								}
								else
								{
									discount = 0;
									amt = price;
								}
								//discount = price - (qty *(price * (parseFloat($('#discount').val()) / 100)));
							}
							else 
							{
								discount = 0;
								amt = qty * price;
							}
							var index = getIndex('input[name^="sku[]"]', sku);
							if(index < 0)
								clientQty = qty;
							else
								clientQty = parseInt($('input[name^="qty[]"]').eq(index).val(), 10) + qty;
							//console.log('Index: '+index + ' Qty: ' + clientQty);
							if(!clientQty)
								clientQty = 0;
							
							invalid_qty(baseURL, 'cashier_mode',sku, clientQty, name + '('+options+')', price, discount, qty, amt, index);
													
							//End invalid quantity		
							$('#prod-code').val(null);
							$('#prod-qty').val(1);
							//$('#receipt-discount').val(null);
							$('#discount').val(null);
							$(".non").prop("checked", true);
							$("#div-prod-info").hide();		
						}
					
						//console.log(sku);
						
					},
					error: function(req, textStatus, errorThrown) {
						alert('Prod Name Keyup Error: ' + textStatus + ' ' +errorThrown);
					}
			});		
}														
function invalid_qty(baseURL, caller, sku, clientQty, name, price, discount, qty, amt, index)
{
	$.ajax({
				type: "POST",
				url: baseURL + 'cashier/sales_management/check_qty',
				context: document.body,
				cache : false,
				data: $('#dp-form').serialize() + "&ajax_sku=" + sku + "&ajax_qty=" +clientQty,
				dataType: 'json',
				success: function(data) {
							$.each(data, function (i,p) {
								if(data.error == true)
								{
									if(caller == 'return')
										$('#cash_error').html(data.msg);
									else
										$('#add-error').html(data.msg);
									hasError = true;
								}
								else
								{
									
									if(caller == 'return')
										addToReplacementBox(sku, name, price, qty, amt, index);
									else
										addToInvoiceBox(sku, name, price, discount, qty, amt, index);
									refreshRemoveItem();
									total_amt_qty();
									$('#totall').val(total_amt());
									$('#earned-pts').html('EARNED POINTS: '+ total_amt()/200);
									$('#grand-total').val($('#totall').val());
									special_discount();
									compute_change('#payment');
								}
							});	
						},
						error: function(req, textStatus, errorThrown) {
							alert('Check Qty Error: ' + textStatus + ' ' +errorThrown);
				}
		});	
}

function getIndex(selector, value)
{
	var ctr = -1;
	var finalCtr = -1;
	$(selector).each(function() {
		ctr++;
		if(value == $(this).val())
		{
			finalCtr = ctr;
			return;
		}
	});
	if(finalCtr != -1)
		return finalCtr;
	else
		return -1;	
}
function total_amt_qty()
{
	$('#total-amt').empty();
	$('#totall').empty();
	
	var total = 0;
	$('input[name^="amt[]"]').each(function() {
		total = total + parseFloat($(this).val());
	});
	var returnAmt = parseFloat($('#return-amt').val());
	$('#total-amt').html(total);
	$('#totall').val(total);	
	$('#balance').html();
	//Math.abs(total - returnAmt)
	$('.input-total-amt').val(total);
	$('.input-total-amt').html('₱ ' + total);

	var balance =  returnAmt - total;
	$('.input-balance').val(balance);
	$('.input-balance').html('₱ ' + balance);
	
	/*
	let balance = Math.abs(total - returnAmt);
	if(total < returnAmt)
	{
		$('.input-balance').val(balance);
		$('.input-balance').html('₱ ' + balance);
	
	}
	else
	{
		$('.input-balance').val(0);	
		$('.input-balance').html('₱ ' + 0);
	}
	*/
	if(total > returnAmt)
	{
		$("#cash").prop('required',true);
		$("#cash").prop('disabled',false);	
	}
	else
	{
		$("#cash").prop('required',false);
		$("#cash").prop('disabled',true);	
		$("#cash").val( 0);	
	}
	$('#total-qty').empty();
    total = 0;
	$('input[name^="qty[]"]').each(function() {
		total = total + parseInt($(this).val(), 10);
	});
	$('#total-qty').html(total);
	
}

//if ctr
function total_qty()
{	
	var total = 0;
	$('input[name^="qty[]"]').each(function() {
		total = total + parseInt($(this).val(), 10);
	});
	return total;
}
function total_amt()
{
	var total = 0;
	$('input[name^="amt[]"]').each(function() {
		total = total + parseFloat($(this).val());
	});
	return total;	
}
function total_discount()
{
	var total = 0;
	$('input[name^="discount[]"]').each(function() {
		total = total + parseFloat($(this).val());
	});
	return total;	
}
function new_total_amt()
{
	var amt = 0;
	var total = total_amt();
	var receiptDiscount = $('#receipt-discount').val();
	if($("input[name='d-type2']:checked").val() == 'amount')
	{
		amt = receiptDiscount;
	}
	else if($("input[name='d-type2']:checked").val() == 'percent')
	{
		amt =  total_amt() - ( total_amt() * (parseFloat(receiptDiscount) / 100));
	}
	else 
	{
		amt = total_amt();
	}
	return amt || 0;
	
}
function addToReplacementBox(sku, name, price, qty, amt, index)
{
	if(index != -1)
	{
		//$('#'+sku).attr('value', );
		var currentAmt = parseFloat($('input[name^="amt[]"]').eq(index).val()) + amt;
		var currentQty = parseInt($('input[name^="qty[]"]').eq(index).val(), 10) +qty;		
		$('input[name^="amt[]"]').eq(index).val(currentAmt);
		$('input[name^="qty[]"]').eq(index).val(currentQty);
		
		$('#amt-'+sku).empty();
		$('#qty-'+sku).empty();
		$('#amt-'+sku).html(currentAmt);
		$('#qty-'+sku).html(currentQty);
	}
	else
	{	
		$('.remove-item').last().remove();
		$("#dt-invoice").find('tbody')
				.append($('<tr>')
					.append($('<td>')
						.append($('<p>'+name+'</p>')
						)
						
						.append($('<input style="border: none;border-color: transparent;" readonly>')
							.attr('type', 'hidden')	
							.attr('value', sku)
							.attr('name', 'sku[]')
						)
					)
					.append($('<td>')
						.append($('<p>'+price+'</p>')
						)
					)
					.append($('<td>')
						.append($('<div id="qty-'+sku+'">'+qty+'</div>')
						)
					)
					
					.append($('<td>')
							.append($('<div id="amt-'+sku+'">'+amt+'</div>')
						)
					)			
					.append($('<td>')
						.append($('<button>')
							.attr('class', 'btn btn-xs bg-red waves-effect remove-item')
							.attr('type', 'button')
							.attr('id', sku)
							.text('x')
						)
						
						.append($('<input style="border: none;border-color: transparent;">')
							.attr('type', 'hidden')	
							.attr('value', qty)
							.attr('name', 'qty[]')
						)
						.append($('<input style="border: none;border-color: transparent;">')
							.attr('type', 'hidden')	
							.attr('value', amt)
							.attr('name', 'amt[]')
						)
					)
				);			
	}
}


function addToInvoiceBox(sku, name, price, discount, qty, amt, index)
{
	if(index != -1)
	{
		//$('#'+sku).attr('value', );
		var currentAmt = parseFloat($('input[name^="amt[]"]').eq(index).val()) + amt;
		var currentDiscount = parseFloat($('input[name^="discount[]"]').eq(index).val()) + discount;
		var currentQty = parseInt($('input[name^="qty[]"]').eq(index).val(), 10) +qty;	
		
		$('input[name^="amt[]"]').eq(index).val(currentAmt);
		$('input[name^="discount[]"]').eq(index).val(currentDiscount);
		$('input[name^="qty[]"]').eq(index).val(currentQty);
		
		$('#amt-'+sku).empty();
		$('#qty-'+sku).empty();
		$('#discount-'+sku).empty();
		
		$('#amt-'+sku).html(currentAmt);
		$('#qty-'+sku).html(currentQty);
		$('#discount-'+sku).html(currentDiscount);
	}
	else
	{	
		$('.remove-item').last().remove();
		$("#dt-invoice").find('tbody')
				.append($('<tr>')
					.append($('<td>')
						.append($('<p>'+name+'</p>')
						)
						
						.append($('<input style="border: none;border-color: transparent;" readonly>')
							.attr('type', 'hidden')	
							.attr('value', sku)
							.attr('name', 'sku[]')
						)
					)
					.append($('<td>')
						.append($('<p>'+price+'</p>')
						)
					)
					.append($('<td>')
						.append($('<div id="discount-'+sku+'">'+discount+'</div>')
						)
					)
					.append($('<td>')
						.append($('<div id="qty-'+sku+'">'+qty+'</div>')
						)
					)
					
					.append($('<td>')
							.append($('<div id="amt-'+sku+'">'+amt+'</div>')
						)
					)			
					.append($('<td>')
						.append($('<button>')
							.attr('class', 'btn btn-xs bg-red waves-effect remove-item')
							.attr('type', 'button')
							.attr('id', sku)
							.text('x')
						)
						
						.append($('<input style="border: none;border-color: transparent;">')
							.attr('type', 'hidden')	
							.attr('value', qty)
							.attr('name', 'qty[]')
						)
						.append($('<input style="border: none;border-color: transparent;">')
							.attr('type', 'hidden')	
							.attr('value', amt)
							.attr('name', 'amt[]')
						)
						.append($('<input style="border: none;border-color: transparent;">')
							.attr('type', 'hidden')	
							.attr('value', discount)
							.attr('name', 'discount[]')
						)
						
					)
				);			
	}
}

function refreshRemoveItem() {
    // Remove handler from existing elements
    $('.remove-item').off(); 

    // Re-add event handler for all matching elements
		$(".remove-item").on('click', function () { 
		$('#cash_error').empty();
													
			var selector = '#' + $(this).attr('id');
			$(selector).parent().parent().remove();
			total_amt_qty();
			$('#grand-total').val($('#totall').val());
			special_discount();
			compute_change('#payment');
			$('#earned-pts').html('EARNED POINTS: '+ total_amt()/200);

	});
}
function submit_modal_form(modal, submitBtn, formSelector, submitURL, func)
{	
	$(modal).modal('show');
	//console.log($(formSelector));
	$(formSelector)[0].reset();//[0]
	
	$('.validation-errors').empty();
	$(submitBtn).click(function(e) {	
		e.preventDefault();
		e.stopImmediatePropagation();
		$.ajax({
                    type: "POST",
                    url: submitURL,
					context: document.body,
				    cache : false,
				    data: $(formSelector).serialize(),
					dataType: 'json',
                    success: function(data) {
							$.each(data, function (i,p) {
								$('#'+i+'_error').html(p);
								
								if(data.error == 'true') 
									$('[name="'+i+'"]').val(p);
								if(data.refresh == 'true')
									location.reload();
								
							});
							
							if(data.start_day_success) 
							{
								window.location.href = baseURL+'cashier/cashier_mode';
							}
							else if(data.success)
							{
								window.location.reload();
								$(formSelector)[0].reset();
								
							} 
							
							
					},
					error: function(req, textStatus, errorThrown) {
						//this is going to happen when you send something different from a 200 OK HTTP
						//alert('Submit Modal Error: ' + textStatus + ' ' +errorThrown);
						alert('An error occurred. Please try again.');
						location.reload();

					}
			  });	  
	});	
}
/*
function tag_scanner(baseURL)
{
	var successBeep = new Audio(baseURL + 'assets/beep.mp3');
	var failedBeep = new Audio(baseURL + 'assets/longBeep.mp3');
	const player = document.getElementById('player2');
	if($('#is-mobile').val())
	{
		const constraints = {
				video: true, video: { facingMode: { exact: "environment" } },	
		};

	navigator.mediaDevices.getUserMedia(constraints) 
			.then((stream) => {
								player.srcObject = stream;
		});	
	}	
	else
	{
		const constraints = {
				video: true,
		};
	navigator.mediaDevices.getUserMedia(constraints) 
			.then((stream) => {
								player.srcObject = stream;
		});	
	}

	$("#div-prod-info").hide();													
	var qr = new QCodeDecoder();

	if (!(qr.isCanvasSupported() && qr.hasGetUserMedia())) {
		alert('Your browser doesn\'t match the required specs.');
		throw new Error('Canvas and getUserMedia are required');
	}
	var video = document.getElementById('player2');			
	result = null;
	resultHandler = function(err, result) {
		
		if(result && result.indexOf('Name =') != -1)
		{	
			result = result.split('Name =');
			result = result[0].split(' = ');
			result = result[1].trim();
			var patt = new RegExp(/^[A-Z]{4}([1-9][0-9]{0,2}|9000)$/);
			var match = patt.test(result);

			if(($("#prod-code").val() != result))
			{				
				address = "cashier/sales_management/get_sale_prod_info";	
				$.ajax({
					type: "POST",
					url: baseURL + address,
					context: document.body,
				    cache : false,
					data: $('#dp-form').serialize() + "&ajax_sku=" +result,
					dataType: 'json',
					success: function(data) {
						$('.validation-errors').empty();
						//showNotification('bg-teal','Product Scanned!', 'top', 'right', null, null); 
							$.each(data, function(i, p) {
								if(i.toString() == 'error')
								{
									$('#scan-error').html(p.toString());	
									failedBeep.play();
								}
								else
								{
									$.each(p, function(i, p) {			
									if(p)
										{
												$("#div-prod-info").show();	
												$("#prod-code").val(result);
												$("#prod-code2").val(result);
												
												if(i.toString() == 'sku')
												{
													$("#prod-code").val(p.toString());
												    $("#prod-code2").val(p.toString());
												}
												else
													$("#" + i.toString()).val(p.toString());
										}	     
									});
									//if($("#prod-qty").val() >= 1)
										//$("#prod-qty").val(1+parseInt($("#prod-qty").val(), 10));
									//else	
										$("#prod-qty").val(1);
										$("#prod-qty2").val(1);
										
									successBeep.play();
								}							
							});

						},
						error: function(req, textStatus, errorThrown) {
							alert('Tag Scanner Error: ' + textStatus + ' ' +errorThrown);
						}
					});
			}	
			
		}
		else
		{
			if(result)
			{				
				successBeep.play();
				
				result = result.split(',');
				address = "cashier/sales_management/get_points";	
				var str = result[1];
				if(str.length > 15)
					result[1] = result[1].substring(0,15);
				$('#mem-id').val(result[1]);
				
				$.ajax({
					type: "POST",
					url: baseURL + address,
					context: document.body,
				    cache : false,
					data: $('#dp-form').serialize() + "&ajax_membership_id=" +result[1],
					dataType: 'json',
					success: function(data) {
						$('.validation-errors').empty();
						//showNotification('bg-teal','Product Scanned!', 'top', 'right', null, null); 
							$.each(data, function(i, p) {
							  $('#stored-pts').html('<b> STORED POINTS: '+data.points+'</b>');
							});

						},
						error: function(req, textStatus, errorThrown) {
							alert('Tag Scanner Error: ' + textStatus + ' ' +errorThrown);
						}
					});
			}
		}
	}
	qr.decodeFromCamera(video, resultHandler);	
}
*/
function showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit) {
    if (colorName === null || colorName === '') { colorName = 'bg-black'; }
    if (text === null || text === '') { text = 'Turning standard Bootstrap alerts'; }
    if (animateEnter === null || animateEnter === '') { animateEnter = 'animated fadeInDown'; }
    if (animateExit === null || animateExit === '') { animateExit = 'animated fadeOutUp'; }
    var allowDismiss = true;

    $.notify({
        message: text
    },
        {
            type: colorName,
            allow_dismiss: allowDismiss,
            newest_on_top: true,
            timer: 1000,
            placement: {
                from: placementFrom,
                align: placementAlign
            },
            animate: {
                enter: animateEnter,
                exit: animateExit
            },
            template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
}
function showConfirmMessage(t, m, url, redirectURL = null, caller = null) {
	swal({
        title: t,
        text: m,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes",
        closeOnConfirm: false
    }, function (isConfirm) {
		  if (isConfirm) {
				if(redirectURL)
					window.location.replace(redirectURL);
				else	
					window.location.replace(url);
		  }
        //swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
}
function submit_form(baseURL, formSelector, submitBtn, submitURL, redirectURL)
{
	$('.validation-errors').empty();
	$(submitBtn).click(function(e) {	
		e.preventDefault();
		e.stopImmediatePropagation();
		$(submitBtn).attr("disabled", true);		
			$.ajax({
					type: "POST",
					url: submitURL,
					context: document.body,
				    cache : false,
					data: $(formSelector).serialize(),
					dataType: 'json',
					success: function(data) {
							$.each(data, function (i,p) {
								$('#'+i+'_error').html(p);
								if(i == 'trans-url') 
								{
									window.location = baseURL+redirectURL+p.toString();
									console.log(baseURL+redirectURL+p.toString());
								}	
								if(i == 'success') 
								{
									window.location = baseURL+redirectURL;
								}	
							});
						},
						error: function(req, textStatus, errorThrown) {
							//alert('Form Submit Error: ' + textStatus + ' ' +errorThrown);
							alert('An error occurred. Please try again.');
						},
					complete: function(){
                        $(submitBtn).attr("disabled", false);
                    }						
				});
		});		
}

	/*$(".chk-sku").change(function() {
		if(this.checked) {
			parseFloat($('#'+$(".chk-sku").val()+'-amt').text());
		}
	});*/
	/*$('#sbtn-submit-return').on('click', function(e) {    
        e.preventDefault();
       $('.rowCont tr').each(function(row, tr){  
         
			if($(this).find('input').is(':checked')) {  // I have added the if condition here 

			var TableData = new Array(); 

			TableData[row] = {      
					  "sku[]" : $(tr).find('td:eq(0)').text(),    
					  "qty[]" : $(tr).find('td:eq(3)').text()
					}

			  TableData = JSON.stringify(TableData);               
			  console.log(TableData);

			  $.ajax({  
				
			   type : "POST",    
			   url : baseURL + "cashier/return_item/add",                   
			   cache : "false",
			   data :  {data:TableData},            
			   success : function(result){  
				 console.log(result);                                
			   } 
			  });
			 } // Here for the checkbox if condition 
        }); // each function
      }); // clicking orderSave button     
	*/