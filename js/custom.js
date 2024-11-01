
jQuery(document).ready(function($) {
	var table_id = $('#wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result').attr('id');
	if ( table_id == 'wcc_seo_keyword_research_valid_search_result' ) {
		$('#wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result').tablesorter({
		});
		setTimeout(function() { 
			$('#wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result tbody tr').each(function(index, value) {
	            var search_cla = $(this).attr('dis_search');
	            var cpc_cla = $(this).attr('dis_cpc');
	            var com_cla = $(this).attr('dis_com');
	            var nor_cla = $(this).attr('dis_nor');
	            var rel_cla = $(this).attr('dis_rel');
	            if ( search_cla != '' ) {
	            	$(this).addClass(search_cla);
	            }
	            if ( cpc_cla != '' ) {
	            	$(this).addClass(cpc_cla);
	            }
	            if ( com_cla != '' ) {
	            	$(this).addClass(com_cla);
	            }
	            if ( nor_cla != '' ) {
	            	$(this).addClass(nor_cla);
	            }
	            if ( rel_cla != '' ) {
	            	$(this).addClass(rel_cla);
	            }	            
	        });
		}, 200);
		
	}

	/**
	 * Search keyword
	 */
	$('#wcc_seo_keyword_research_search_form').submit(function(e) {
		var valid_num = $('#wcc_seo_keyword_research_keyword').attr('valid_number');
		var valid_disable = $('#wcc_seo_keyword_research_keyword').attr('valid_disable');
		if ( valid_disable == 'Yes' ) {
			e.preventDefault();
			alert('You have already done the maximum number of searches. If you want to search more, please contact us!');
		}
	});

	/**
	 * Click show more button to submit user information
	 */
	$('#wcc_seo_keyword_research_showmore').on('click', function() {
		$('#wcc_seo_keyword_research_modal').css('display', 'flex');
		$('body').css('overflow', 'hidden');
	});

	/**
	 * Close modal overlay
	 */
	$('#wcc_seo_keyword_research_modal_overaly').on('click', function() {
		$('#wcc_seo_keyword_research_modal').css('display', 'none');
		$('body').css('overflow', 'initial');
	});

	/**
	 * Close modal popup
	 */
	$('#wcc_seo_keyword_research_modal_close').on('click', function() {
		$('#wcc_seo_keyword_research_modal').css('display', 'none');
		$('body').css('overflow', 'initial');
	});

	/**
	 * Submit user info form to see full result
	 */ 
	$('#wcc_seo_keyword_research_user_submit').on('click', function() {
		var fname 		= $.trim( $('#wcc_seo_keyword_research_user_fname').val() );
		var lname 		= $.trim( $('#wcc_seo_keyword_research_user_lname').val() );
		var email 		= $.trim( $('#wcc_seo_keyword_research_user_email').val() );
		var phone 		= $.trim( $('#wcc_seo_keyword_research_user_phone').val() );
		var business 	= $.trim( $('#wcc_seo_keyword_research_user_business').val() );
		var website 	= $.trim( $('#wcc_seo_keyword_research_user_website').val() );
		$('.wcc_seo_keyword_research_user_item').each(function() {
			$(this).removeClass('wcc_seo_keyword_research_error');
		});
		var validate = '';
		if ( fname != '' && lname != '' && email != '' && phone != '' && business != '' && website != '' ) {
			if ( ( ( website.substr(0, 4) == 'http' && website.indexOf('://') > 0 ) || website.substr(0, 3) == 'www' ) && website.indexOf('.') > 0 ) {
		    	
		    } else {
		    	$('.wcc_seo_keyword_research_user_item_website').addClass('wcc_seo_keyword_research_error');
				$('#wcc_seo_keyword_research_user_website').focus();
				validate = 'website_error';
		    }
		    if ( !$.isNumeric( phone ) || phone.length < 6 ) {
		    	$('.wcc_seo_keyword_research_user_item_phone').addClass('wcc_seo_keyword_research_error');
				$('#wcc_seo_keyword_research_user_phone').focus();
				validate = 'phone_error';
		    }
		    if ( email.indexOf('@') <= 0 ) {
		    	$('.wcc_seo_keyword_research_user_item_email').addClass('wcc_seo_keyword_research_error');
				$('#wcc_seo_keyword_research_user_email').focus();
				validate = 'email_error';
		    }
		    console.log(validate);
		    var plug_url = $('#wcc_seo_keyword_research_blank').attr('wcc_seo_keyword_research_url');
		    var keyword = $.trim( $('#wcc_seo_keyword_research_keyword').val() );
		    if ( validate == '' ) {
		    	$.ajax({
		           	type: "POST",
		           	url: plug_url,
		           	data: {keyword: keyword, fname: fname, lname: lname, email: email, phone: phone, business: business, website: website,action:"wcc_seo_keyword_research_user"}, 
		           	success: function(response) {
		            	if ( response ) {
		            		var obj_response = JSON.parse(response);
		            		console.log(obj_response);
		            		$('#wcc_seo_keyword_research_modal').css('display', 'none');
							$('body').css('overflow', 'initial');
							$('#wcc_seo_keyword_research_showmore').css('display', 'none');
							$('#wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result').attr('id', 'wcc_seo_keyword_research_valid_search_result');
							var valid_html = '';
							$.each(obj_response, function(key, value) {
								valid_html += '<tr id="item_' + (key + 1) + '" item_num="item_' + (key + 1) + '"><td><div class="num_col"><span>' + String(key + 1).padStart(3, '0') + '</span></div></td>';
								$.each(value, function(sub_key, item) {
									valid_html += '<td><div><span>' + $.trim( item ) + '</span></div></td>'
								});
								valid_html += '</tr>';
							});
							$('#wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result tbody').html(valid_html);
							$('#wcc_seo_keyword_research_valid_search_result').tablesorter({
								
							});
							$('#wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper .function_btns').addClass('active');
		            	} else {
		            		console.log('Failed');
		            		$('#wcc_seo_keyword_research_modal').css('display', 'none');
							$('body').css('overflow', 'initial');
		            	}
		        	}
		        });
		    }
		} else {
			if ( website == '' ) {
				$('.wcc_seo_keyword_research_user_item_website').addClass('wcc_seo_keyword_research_error');
				$('#wcc_seo_keyword_research_user_website').focus();
			}
			if ( business == '' ) {
				$('.wcc_seo_keyword_research_user_item_business').addClass('wcc_seo_keyword_research_error');
				$('#wcc_seo_keyword_research_user_business').focus();
			}
			if ( phone == '' ) {
				$('.wcc_seo_keyword_research_user_item_phone').addClass('wcc_seo_keyword_research_error');
				$('#wcc_seo_keyword_research_user_phone').focus();
			}
			if ( email == '' ) {
				$('.wcc_seo_keyword_research_user_item_email').addClass('wcc_seo_keyword_research_error');
				$('#wcc_seo_keyword_research_user_email').focus();
			}
			if ( lname == '' ) {
				$('.wcc_seo_keyword_research_user_item_lname').addClass('wcc_seo_keyword_research_error');
				$('#wcc_seo_keyword_research_user_lname').focus();
			}
			if ( fname == '' ) {
				$('.wcc_seo_keyword_research_user_item_fname').addClass('wcc_seo_keyword_research_error');
				$('#wcc_seo_keyword_research_user_fname').focus();
			}
		}
	});

	/**
	 * Share full results
	 */
	$('#wcc_seo_keyword_research_share_results').on('click', function() {
		$('#wcc_seo_keyword_research_email_box').css('display', 'none');
		$('#wcc_seo_keyword_research_email_box').attr('status', 'close');
		$('#wcc_seo_keyword_research_search_box').css('display', 'none');
		$('#wcc_seo_keyword_research_search_box').attr('status', 'close');
		$('#wcc_seo_keyword_research_filter_box').css('display', 'none');
		$('#wcc_seo_keyword_research_filter_box').attr('status', 'close');
		var url = window.location.href;
		var a = $('<a>', {
	        href: url
	    });
	    var cur_url = url;
		var keyword = $('#wcc_seo_keyword_research_keyword').val();
		var plug_url = $('#wcc_seo_keyword_research_blank').attr('wcc_seo_keyword_research_url');
		var file_name = Math.floor(Math.random() * 26) + Date.now();
		var transfer_url = cur_url + '&share=' + file_name;
		var rows = {};
		rows['disabled'] = [];
		rows['disable_cpc'] = [];
		rows['disable_com'] = [];
		rows['disable_nor'] = [];
		rows['disable_rel'] = [];
    	var option = $('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]:checked').val();
    	if ( option == 'current' ) {
        	$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
                var id = $(this).attr('id');
                if ( $(this).hasClass('disabled') ) {
                	var num = parseInt( id.replace(/[^0-9]/gi, '') );
                	rows['disabled'].push(num);
                }
                if ( $(this).hasClass('disable_cpc') ) {
                	var num = parseInt( id.replace(/[^0-9]/gi, '') );
                	rows['disable_cpc'].push(num);
                }
                if ( $(this).hasClass('disable_com') ) {
                	var num = parseInt( id.replace(/[^0-9]/gi, '') );                	
                	rows['disable_com'].push(num);
                }
                if ( $(this).hasClass('disable_nor') ) {
                	var num = parseInt( id.replace(/[^0-9]/gi, '') );                	
                	rows['disable_nor'].push(num);
                }
                if ( $(this).hasClass('disable_rel') ) {
                	var num = parseInt( id.replace(/[^0-9]/gi, '') );                	
                	rows['disable_rel'].push(num);
                }
            });
        }
		$.ajax({
           	type: "POST",
           	url: plug_url,
           	data: {key: keyword, name: file_name, url: transfer_url, option: option, data: rows,action:"wcc_seo_keyword_research_share"}, 
           	success: function(response) {
            	if ( response == 'true' ) {
            		console.log('Success');
            		$('#wcc_seo_keyword_research_shared_url .wcc_seo_keyword_research_share_url a').attr('href', transfer_url);
            		$('#wcc_seo_keyword_research_shared_url .wcc_seo_keyword_research_share_url a').text(transfer_url);
            		$('#wcc_seo_keyword_research_shared_url').slideDown(400, function() {
					});
            	} else {
            		console.log('Failed');
            	}
        	}
        });
	});

	/**
	 * Toggle Email input box
	 */
	$('#wcc_seo_keyword_research_email_results').on('click', function() {
		$('#wcc_seo_keyword_research_shared_url').css('display', 'none');
		$('#wcc_seo_keyword_research_search_box').css('display', 'none');
		$('#wcc_seo_keyword_research_search_box').attr('status', 'close');
		$('#wcc_seo_keyword_research_filter_box').css('display', 'none');
		$('#wcc_seo_keyword_research_filter_box').attr('status', 'close');
		var status = $('#wcc_seo_keyword_research_email_box').attr('status');
		if ( status == 'open' ) {
			$('#wcc_seo_keyword_research_email_box').slideUp(400, function() {
			});
			$('#wcc_seo_keyword_research_email_box').attr('status', 'close');
		} else if ( status == 'close' ) {
			$('#wcc_seo_keyword_research_email_box').slideDown(400, function() {
			});
			$('#wcc_seo_keyword_research_send_email').focus();
			$('#wcc_seo_keyword_research_email_box').attr('status', 'open');
		}
		
	});
	/**
	 * Email search result
	 */
	$('#wcc_seo_keyword_research_send_email_btn').on('click', function() {
		var email = $.trim( $('#wcc_seo_keyword_research_send_email').val() );
		var keyword = $('#wcc_seo_keyword_research_keyword').val();
		var user_cus = $('#wcc_seo_keyword_research_email_box').attr('user_cus');
		var user_option = '';
		if ( user_cus == 'active' ) {
			user_option = $("input[name='wcc_seo_keyword_research_email_cus']:checked").val();
		}
		var rows = [];
    	var option = $('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]:checked').val();
    	if ( option == 'current' ) {
        	$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
                var id = $(this).attr('id');
                if ( !$(this).hasClass('disabled') && !$(this).hasClass('disable_cpc') && !$(this).hasClass('disable_com') && !$(this).hasClass('disable_nor') && !$(this).hasClass('disable_rel') ) {
                	var num = parseInt( id.replace(/[^0-9]/gi, '') );
                	rows.push(num);
                }
            });
        }
		$('#wcc_seo_keyword_research_send_email').removeClass('error')
		if ( email ) {
			if ( email.indexOf('@') < 0 ) {
		    	$('#wcc_seo_keyword_research_send_email').addClass('error');
		    	$('#wcc_seo_keyword_research_send_email').focus();
		    } else {
				var plug_url = $('#wcc_seo_keyword_research_blank').attr('wcc_seo_keyword_research_url');
				$.ajax({
		           	type: "POST",
		           	url: plug_url,
		           	data: {mail: email, key: keyword, user: user_option, option: option, data: rows,action:"wcc_seo_keyword_research_email"}, 
		           	success: function(response) {
		            	if ( response && response == 'true' ) {
		            		console.log('Email was sent successfully!');
		            		$('#wcc_seo_keyword_research_email_box').slideUp(400, function() {
							});
							$('#wcc_seo_keyword_research_email_box').attr('status', 'close');
							$('#wcc_seo_keyword_research_send_email').val('');
		            		$('#wcc_seo_keyword_research_main .mail_notification').after('<div id="email_link_success" class="wcc_seo_keyword_research_functional_area">Email was sent successfully!</div>');
							setTimeout(function() {
					        	$('#email_link_success').remove();
					     	}, 3000);
		            	} else {
		            		if ( response ) {
		            			console.log(response);
		            			$('#wcc_seo_keyword_research_email_box').slideUp(400, function() {
								});
								$('#wcc_seo_keyword_research_email_box').attr('status', 'close');
								$('#wcc_seo_keyword_research_send_email').val('');
		            			$('#wcc_seo_keyword_research_main .mail_notification').after('<div id="email_link_failed" class="wcc_seo_keyword_research_functional_area">' + response + '</div>');
								setTimeout(function() {
						        	$('#email_link_failed').remove();
						     	}, 3000);
		            		} else {
		            			console.log('Failed');
		            		}
		            	}
		        	}
		        });
		    }
		} else {
			$('#wcc_seo_keyword_research_send_email').addClass('error');
			$('#wcc_seo_keyword_research_send_email').focus();
		}
	});

	/**
	 * Toggle Search box
	 */
	$('#wcc_seo_keyword_research_search_results').on('click', function() {
		$('#wcc_seo_keyword_research_shared_url').css('display', 'none');
		$('#wcc_seo_keyword_research_email_box').css('display', 'none');
		$('#wcc_seo_keyword_research_email_box').attr('status', 'close');
		$('#wcc_seo_keyword_research_filter_box').css('display', 'none');
		$('#wcc_seo_keyword_research_filter_box').attr('status', 'close');
		var status = $('#wcc_seo_keyword_research_search_box').attr('status');
		if ( status == 'open' ) {
			$('#wcc_seo_keyword_research_search_box').slideUp(400, function() {
			});
			$('#wcc_seo_keyword_research_search_box').attr('status', 'close');
		} else if ( status == 'close' ) {
			$('#wcc_seo_keyword_research_search_box').slideDown(400, function() {
			});
			$('#wcc_seo_keyword_research_search_input').focus();
			$('#wcc_seo_keyword_research_search_box').attr('status', 'open');
		}
		
	});
	/**
	 * Ajax search table list
	 */
	$('#wcc_seo_keyword_research_search_input').on('change paste keyup', function() {
		var search = $.trim( $(this).val() );
        if ( search == '' ) {
            $('#wcc_seo_keyword_research_valid_search_result tbody tr').removeClass('disabled');
        } else {
            $('#wcc_seo_keyword_research_valid_search_result tbody tr').addClass('disabled');
            $('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
                var id = $(this).attr('id');
                $(this).find('td').each(function(key, item) {
                    if ( $(this).find('span').text().indexOf(search) >= 0 ) {
                        $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id).removeClass('disabled');
                    }
                });
            });
        }
	});
	/**
	 * Toggle Range Slider Filter box
	 */
	$('#wcc_seo_keyword_research_filter_results').on('click', function() {
		$('#wcc_seo_keyword_research_shared_url').css('display', 'none');
		$('#wcc_seo_keyword_research_email_box').css('display', 'none');
		$('#wcc_seo_keyword_research_email_box').attr('status', 'close');
		$('#wcc_seo_keyword_research_search_box').css('display', 'none');
		$('#wcc_seo_keyword_research_search_box').attr('status', 'close');
		var status = $('#wcc_seo_keyword_research_filter_box').attr('status');
		if ( status == 'open' ) {
			$('#wcc_seo_keyword_research_filter_box').slideUp(400, function() {
			});
			$('#wcc_seo_keyword_research_filter_box').attr('status', 'close');
		} else if ( status == 'close' ) {
			$('#wcc_seo_keyword_research_filter_box').slideDown(400, function() {
			});
			$('#wcc_seo_keyword_research_filter_box').attr('status', 'open');
		}
		
	});
	/**
	 * Range Slider filter function
	 */
	/* CPC filter */
	if ( $('#wcc_seo_keyword_research_cpc_filter').length ) {
		var wcc_seo_keyword_research_cpc_filter = document.getElementById('wcc_seo_keyword_research_cpc_filter');
		var range_max = 10;
		$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
		    var temp_cpc = parseFloat( $(this).find('td:nth-child(4) div span').text() );
		    if ( temp_cpc > range_max ) {
		    	range_max = temp_cpc;
		    }
	    });
	    range_max = Math.ceil( range_max );
		noUiSlider.create(wcc_seo_keyword_research_cpc_filter, {
		    start: [0, range_max],
		    step: 0.1,
		    tooltips: [wNumb({decimals: 1}), wNumb({decimals: 1})],
		    range: {
		        'min': 0,
		        'max': range_max
		    }
		});
		wcc_seo_keyword_research_cpc_filter.noUiSlider.on('update', function (values, handle) {
			var min = parseFloat( $('#wcc_seo_keyword_research_cpc_filter .noUi-handle-lower .noUi-tooltip').text() );
			var max = parseFloat( $('#wcc_seo_keyword_research_cpc_filter .noUi-handle-upper .noUi-tooltip').text() );
			$('#wcc_seo_keyword_research_valid_search_result tbody tr').addClass('disable_cpc');
	        $('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
			    var id = $(this).attr('id');
			    var value = parseFloat( $(this).find('td:nth-child(4) div span').text() );
			    if (handle) {
			        if ( min <= value && value <= values[handle] ) {
			            $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id).removeClass('disable_cpc');
			        }
			    } else {
		            if ( values[handle] <= value && value <= max ) {
		                $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id).removeClass('disable_cpc');
		            }
			    }
		    });
		});
	}
	/* Competition filter */
	if ( $('#wcc_seo_keyword_research_com_filter').length ) {
		var wcc_seo_keyword_research_com_filter = document.getElementById('wcc_seo_keyword_research_com_filter');
		noUiSlider.create(wcc_seo_keyword_research_com_filter, {
		    start: [0, 1],
		    step: 0.01,
		    tooltips: [wNumb({decimals: 2}), wNumb({decimals: 2})],
		    range: {
		        'min': 0,
		        'max': 1
		    }
		});
		wcc_seo_keyword_research_com_filter.noUiSlider.on('update', function (values, handle) {
			var min_com = parseFloat( $('#wcc_seo_keyword_research_com_filter .noUi-handle-lower .noUi-tooltip').text() );
			var max_com = parseFloat( $('#wcc_seo_keyword_research_com_filter .noUi-handle-upper .noUi-tooltip').text() );
			$('#wcc_seo_keyword_research_valid_search_result tbody tr').addClass('disable_com');
	        $('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
			    var id = $(this).attr('id');
			    var value = parseFloat( $(this).find('td:nth-child(5) div span').text() );
			    if (handle) {
			        if ( min_com <= value && value <= values[handle] ) {
			            $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id).removeClass('disable_com');
			        }
			    } else {
		            if ( values[handle] <= value && value <= max_com ) {
		                $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id).removeClass('disable_com');
		            }
			    }
		    });
		});
	}
	/**
	 * Round function with decimal fraction
	 */
	function cus_round( value, decimals ) {
		return Number( Math.round( value + 'e' + decimals ) + 'e-' + decimals );
	}
	/* Number of Results filter */
	if ( $('#wcc_seo_keyword_research_nor_filter').length ) {
		var wcc_seo_keyword_research_nor_filter = document.getElementById('wcc_seo_keyword_research_nor_filter');
		var range_max_nor = 100000000;
		$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
		    var temp_cpc_nor = parseFloat( $(this).find('td:nth-child(6) div span').text() );
		    if ( temp_cpc_nor > range_max_nor ) {
		    	range_max_nor = temp_cpc_nor;
		    }
	    });
	    range_max_nor = Math.ceil( range_max_nor );
		noUiSlider.create(wcc_seo_keyword_research_nor_filter, {
		    start: [0, range_max_nor],
		    step: 100000,
		    tooltips: [wNumb({decimals: 0}), wNumb({decimals: 0})],
		    range: {
		        'min': 0,
		        'max': range_max_nor
		    }
		});
		wcc_seo_keyword_research_nor_filter.noUiSlider.on('update', function (values, handle) {
			var min_nor = parseFloat( $('#wcc_seo_keyword_research_nor_filter .noUi-handle-lower .noUi-tooltip').text() );
			var max_nor = parseFloat( $('#wcc_seo_keyword_research_nor_filter .noUi-handle-upper .noUi-tooltip').text() );
			$('#wcc_seo_keyword_research_valid_search_result tbody tr').addClass('disable_nor');
	        $('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
			    var id = $(this).attr('id');
			    var value = parseFloat( $(this).find('td:nth-child(6) div span').text() );
			    if (handle) {
			        if ( min_nor <= value && value <= values[handle] ) {
			            $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id).removeClass('disable_nor');
			        }
			        $('#wcc_seo_keyword_research_nor_filter .noUi-handle-upper .noUi-tooltip').attr("converted", max_nor);
			        if ( max_nor >= 1000 ) {
						$('#wcc_seo_keyword_research_nor_filter .noUi-handle-upper .noUi-tooltip').attr("converted", (cus_round( (max_nor / 1000), 1 ) + 'K'));
					}
					if ( max_nor >= 1000000 ) {
						$('#wcc_seo_keyword_research_nor_filter .noUi-handle-upper .noUi-tooltip').attr("converted", (cus_round( (max_nor / 1000000), 1 ) + 'M'));
					}
					if ( max_nor >= 1000000000 ) {
						$('#wcc_seo_keyword_research_nor_filter .noUi-handle-upper .noUi-tooltip').attr("converted", (cus_round( (max_nor / 1000000000), 1 ) + 'B'));
					}
					if ( max_nor >= 1000000000000 ) {
						$('#wcc_seo_keyword_research_nor_filter .noUi-handle-upper .noUi-tooltip').attr("converted", (cus_round( (max_nor / 1000000000000), 1 ) + 'T'));
					}
			    } else {
		            if ( values[handle] <= value && value <= max_nor ) {
		                $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id).removeClass('disable_nor');
		            }
		            $('#wcc_seo_keyword_research_nor_filter .noUi-handle-lower .noUi-tooltip').attr("converted", min_nor);
			        if ( min_nor >= 1000 ) {
						$('#wcc_seo_keyword_research_nor_filter .noUi-handle-lower .noUi-tooltip').attr("converted", (cus_round( (min_nor / 1000), 1 ) + 'K'));
					}
					if ( min_nor >= 1000000 ) {
						$('#wcc_seo_keyword_research_nor_filter .noUi-handle-lower .noUi-tooltip').attr("converted", (cus_round( (min_nor / 1000000), 1 ) + 'M'));
					}
					if ( min_nor >= 1000000000 ) {
						$('#wcc_seo_keyword_research_nor_filter .noUi-handle-lower .noUi-tooltip').attr("converted", (cus_round( (min_nor / 1000000000), 1 ) + 'B'));
					}
					if ( min_nor >= 1000000000000 ) {
						$('#wcc_seo_keyword_research_nor_filter .noUi-handle-lower .noUi-tooltip').attr("converted", (cus_round( (min_nor / 1000000000000), 1 ) + 'T'));
					}
			    }
		    });
		});
	}
	/* Related Relevance filter */
	if ( $('#wcc_seo_keyword_research_rel_filter').length ) {
		var wcc_seo_keyword_research_rel_filter = document.getElementById('wcc_seo_keyword_research_rel_filter');
		noUiSlider.create(wcc_seo_keyword_research_rel_filter, {
		    start: [0, 1],
		    step: 0.01,
		    tooltips: [wNumb({decimals: 2}), wNumb({decimals: 2})],
		    range: {
		        'min': 0,
		        'max': 1
		    }
		});
		wcc_seo_keyword_research_rel_filter.noUiSlider.on('update', function (values, handle) {
			var min_rel = parseFloat( $('#wcc_seo_keyword_research_rel_filter .noUi-handle-lower .noUi-tooltip').text() );
			var max_rel = parseFloat( $('#wcc_seo_keyword_research_rel_filter .noUi-handle-upper .noUi-tooltip').text() );
			$('#wcc_seo_keyword_research_valid_search_result tbody tr').addClass('disable_rel');
	        $('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
			    var id = $(this).attr('id');
			    var value = parseFloat( $(this).find('td:nth-child(7) div span').text() );
			    if (handle) {
			        if ( min_rel <= value && value <= values[handle] ) {
			            $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id).removeClass('disable_rel');
			        }
			    } else {
		            if ( values[handle] <= value && value <= max_rel ) {
		                $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id).removeClass('disable_rel');
		            }
			    }
		    });
		});
	}

	/**
	 * View Radio option switch function
	 */
	$('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]').on('change', function(e) {
		e.preventDefault();
		var option = $('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]:checked').val();
		$('#wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper').attr('view_status', option);
	});

	/**
	 * Download Template as a CSV
	 */
	function downloadCSV( csv, filename ) {
        var csvFile;
        var downloadLink;
        // CSV file
        csvFile = new Blob([csv], {type: "text/csv"});
        // Download link
        downloadLink = document.createElement("a");
        // File name
        downloadLink.download = filename;
        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);
        // Hide download link
        downloadLink.style.display = "none";
        // Add the link to DOM
        document.body.appendChild(downloadLink);
        // Click download link
        downloadLink.click();
    }
    
    function exportTableToCSV( filename ) {
        var csv = [];
        var rows = [];
        var option = $('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]:checked').val();
        if ( option == 'current' ) {
        	rows.push(document.querySelector("table#wcc_seo_keyword_research_valid_search_result thead tr"));
        	$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
                var id = $(this).attr('id');
                if ( !$(this).hasClass('disabled') && !$(this).hasClass('disable_cpc') && !$(this).hasClass('disable_com') && !$(this).hasClass('disable_nor') && !$(this).hasClass('disable_rel') ) {
                	rows.push(document.querySelector("table#wcc_seo_keyword_research_valid_search_result tr#" + id));
                }
            });
        } else {
        	rows = document.querySelectorAll("table#wcc_seo_keyword_research_valid_search_result tr");
        }        
        console.log(rows);
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");
            for (var j = 0; j < cols.length; j++) {
            	row.push(cols[j].innerText);
            }
            csv.push(row.join(","));
        }
        // Download CSV file
        downloadCSV( csv.join("\n"), filename );
    }
	$('#wcc_seo_keyword_research_csv_export').on('click', function(e) {
    
    	e.preventDefault();
        exportTableToCSV( 'Search Result.csv' );
	});

	/**
	 * Download Template as a XLS
	 */
	$('#wcc_seo_keyword_research_xls_export').on('click', function(e) {
    
    	e.preventDefault();
    	var rows = [];
    	var option = $('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]:checked').val();
    	if ( option == 'current' ) {
        	$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
                var id = $(this).attr('id');
                if ( !$(this).hasClass('disabled') && !$(this).hasClass('disable_cpc') && !$(this).hasClass('disable_com') && !$(this).hasClass('disable_nor') && !$(this).hasClass('disable_rel') ) {
                	var num = parseInt( id.replace(/[^0-9]/gi, '') );
                	rows.push(num);
                }
            });
        }
    	var keyword = $('#wcc_seo_keyword_research_keyword').val();
        var plug_url = $('#wcc_seo_keyword_research_blank').attr('wcc_seo_keyword_research_url');
        var down_url = $('#wcc_seo_keyword_research_blank').attr('wcc_seo_keyword_research_file_url');
		$.ajax({
           	type: "POST",
           	url: plug_url,
           	data: {key: keyword, option: option, data: rows,action : "wcc_seo_keyword_research_xls_export"}, 
           	success: function(response) {
            	if ( response ) {
            		file_url = down_url + '../export/' + response;
            		console.log('success');
            		function Download(url) {
					    document.getElementById('wcc_seo_keyword_research_xls_blank').src = file_url;
					};
					Download(file_url);            		
            	} else {
            		console.log('Failed');
            	}
        	}
        });
	});
	$('#wcc_seo_keyword_research_txt_export').on('click', function(e) {
    	var temp = '';
    	temp += '#    Keyword    Search Volume    CPC    Competition    Number of Results    Related Relevance' + '\n';
    	var option = $('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]:checked').val();
    	$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {    	
        	if ( option == 'current' ) {
                var id = $(this).attr('id');
                if ( !$(this).hasClass('disabled') && !$(this).hasClass('disable_cpc') && !$(this).hasClass('disable_com') && !$(this).hasClass('disable_nor') && !$(this).hasClass('disable_rel') ) {
                	$('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id + ' td').each(function(key, item) {
                        if ( $(this).text() == '' ) {
                            temp += '          ' + '    ';
                        } else {
                            temp += $(this).text() + '    ';
                        }
                    });
                    temp += '\n';
                }
        	} else {
        		var id = $(this).attr('id');
            	$('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id + ' td').each(function(key, item) {
                    if ( $(this).text() == '' ) {
                        temp += '          ' + '    ';
                    } else {
                        temp += $(this).text() + '    ';
                    }
                });
                temp += '\n';
        	}
        });
        $(this).attr('href', 'data:text/plain;charset=UTF-8,' + encodeURIComponent( temp ));
	});

	/**
	 * Download Template as a PDF
	 */
	$('#wcc_seo_keyword_research_pdf_export').on('click', function(e) {
    
    	e.preventDefault();
    	var element = '';
    	var option = $('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]:checked').val();
    	if ( option == 'current' ) {
    		element += '<style type="text/css">#wcc_seo_keyword_research_valid_search_result th.tablesorter-header {background: #fff; padding: 15px 5px;} #wcc_seo_keyword_research_valid_search_result tbody tr td {min-width: 36px;}</style><table id="wcc_seo_keyword_research_valid_search_result" class="wcc_seo_keyword_research_search_result tablesorter tablesorter-default"><thead>' + $('#wcc_seo_keyword_research_valid_search_result thead').html() + '</thead><tbody>';
        	$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {
                var id = $(this).attr('id');
                if ( !$(this).hasClass('disabled') && !$(this).hasClass('disable_cpc') && !$(this).hasClass('disable_com') && !$(this).hasClass('disable_nor') && !$(this).hasClass('disable_rel') ) {
                	element += '<tr id="' + id + '">';
                	element += $('table#wcc_seo_keyword_research_valid_search_result tr#' + id).html();
                	element += '</tr>';
                }
            });
            element += '</tbody></table>';
        } else {
        	element += '<style type="text/css">#wcc_seo_keyword_research_valid_search_result th.tablesorter-header {background: #fff; padding: 15px 5px;} #wcc_seo_keyword_research_valid_search_result tbody tr td {min-width: 36px;}</style><table id="wcc_seo_keyword_research_valid_search_result" class="wcc_seo_keyword_research_search_result tablesorter tablesorter-default">';
        	element += $('#wcc_seo_keyword_research_valid_search_result').html();
        	element += '</table>';
        }
        console.log(element);
        html2pdf()
        	.from(element)
		    .set({ 
		    	margin: 0.672,
          		filename: 'Search Result.pdf',
          		html2canvas: { scale: 3 },
          		jsPDF: {orientation: 'portrait', unit: 'in', format: 'letter', compressPDF: true}
		    }).save();
	});

	/**
	 * Print Results
	 */
	$('#wcc_seo_keyword_research_print_result').on('click', function(e) {
    
    	e.preventDefault();
    	var plug_url = $('#wcc_seo_keyword_research_blank').attr('wcc_seo_keyword_research_url');
        var element = document.getElementById('wcc_seo_keyword_research_wrapper');
        var cus_window = window.open('', 'PRINT');
        var cus_style = 'body {-webkit-print-color-adjust: exact !important;} #wcc_seo_keyword_research_wrapper * {color: #000; line-height: 1.5; box-sizing: border-box; outline: none; font-family: Arial, sans-serif;} #wcc_seo_keyword_research_wrapper {width: 100%; padding: 30px 25px; max-width: 1400px; margin: 0 auto;} #wcc_seo_keyword_research_header {text-align: center; padding-bottom: 25px;} #wcc_seo_keyword_research_header h2 {font-size: 3em; margin: 32px 0;} #wcc_seo_keyword_research_search_form {padding: 12px 0 45px; text-align: center; margin: 0; font-size: 0;} #wcc_seo_keyword_research_search_form input {border: 1px solid #99aaaf; border-radius: 4px 0 0 4px; background: #fff; font-size: 15px; font-weight: 500; color: #333; padding: 16px 21px; display: inline-block;} #wcc_seo_keyword_research_search_form #wcc_seo_keyword_research_keyword {width: 60%; max-width: 500px;} #wcc_seo_keyword_research_search_form #wcc_seo_keyword_research_submit {font-weight: bold; border-radius: 0 4px 4px 0; margin-left: -1px; position: relative; background: #d2562c !important; text-decoration: none; text-transform: capitalize; color: #fff; cursor: pointer; border-color: transparent;} #wcc_seo_keyword_research_modal {display: none !important;} .wcc_seo_keyword_research_table_wrapper {position: relative;} .wcc_seo_keyword_research_table_wrapper .function_btns {display: flex; flex-wrap: wrap; width: 100%; padding: 0; justify-content: space-between;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper .results_btns button {display: inline-block; margin-bottom: 12px; color: #fff; text-transform: initial; border-radius: 4px; padding: 6px 12px; font-weight: normal; text-align: center; cursor: pointer; border: 1px solid transparent; text-decoration: none; margin-right: 12px; font-size: 15px; min-width: 100px;} #wcc_seo_keyword_research_main #wcc_seo_keyword_research_share_results {background: #5cb85c !important; border-color: #4cae4c !important;} #wcc_seo_keyword_research_main #wcc_seo_keyword_research_email_results {background: #17a2b8 !important; border-color: #17a2b8 !important;} #wcc_seo_keyword_research_main #wcc_seo_keyword_research_search_results {background: #dc3545 !important; border-color: #dc3545;} .wcc_seo_keyword_research_table_wrapper .export_btns a {cursor: pointer; margin-bottom: 12px; margin-left: 25px; display: inline-block;} .wcc_seo_keyword_research_table_wrapper .export_btns a img {display: block; height: 39px; width: auto;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result {font-size: 13px; margin: 32px 0 15px; background: #fff; border: none; border-radius: 5px;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result th {font-size: 14px; font-weight: 600; color: rgba(0, 0, 0, .9); border: none; height: 55px; background-color: rgba(0, 0, 0, .04); border-bottom: 1px solid rgba(0, 0, 0, .42); padding: 18px 25px 15px;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result th:first-child {padding: 18px 35px 15px;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result th.tablesorter-headerAsc, #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result th.tablesorter-headerDesc {border-bottom: 2px solid rgb(0, 188, 212);} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result td {padding: 12px 0; color: rgba(0, 0, 0, .8); border: none; border-bottom: 1px solid rgba(0, 0, 0, .08);} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result td div {padding: 0 28px;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result td .num_col span {background: rgba(252, 228, 236, 1); color: rgba(240, 98, 146, 1); border-radius: 4px; padding: 3px 9px;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result tr td:nth-child(3) div span {background-color: rgba(185, 246, 202, 1); color: rgba(102, 187, 106, 1); border-radius: 4px; padding: 3px 12px;} #wcc_seo_keyword_research_xls_blank {display: none !important;} #wcc_seo_keyword_research_showmore {display: none !important;} #wcc_seo_keyword_research_shared_url {display: none !important;} #wcc_seo_keyword_research_email_box {display: none !important;} #wcc_seo_keyword_research_search_box {display: none !important;} #wcc_seo_keyword_research_filter_box {display: none !important;} #wcc_seo_keyword_research_main #wcc_seo_keyword_research_filter_results {background: #007bff !important; border-color: #007bff !important;} #wcc_seo_keyword_research_view_radio {display: none !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result tbody tr.disable_cpc {display: none !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result tbody tr.disable_com {display: none !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result tbody tr.disable_nor {display: none !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result tbody tr.disable_rel {display: none !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_search_result tbody tr.disabled {display: none !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper[view_status="full"] .wcc_seo_keyword_research_search_result tbody tr.disable_cpc {display: table-row !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper[view_status="full"] .wcc_seo_keyword_research_search_result tbody tr.disable_com {display: table-row !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper[view_status="full"] .wcc_seo_keyword_research_search_result tbody tr.disable_nor {display: table-row !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper[view_status="full"] .wcc_seo_keyword_research_search_result tbody tr.disable_rel {display: table-row !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper[view_status="full"] .wcc_seo_keyword_research_search_result tbody tr.disabled {display: table-row !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper[share_status="open"] #wcc_seo_keyword_research_search_results {display: none !important;} #wcc_seo_keyword_research_main .wcc_seo_keyword_research_table_wrapper[share_status="open"] #wcc_seo_keyword_research_filter_results {display: none !important;} .wcc_seo_keyword_research_table_wrapper[share_status="open"] #wcc_seo_keyword_research_view_radio {display: none !important;}'
	    cus_window.document.write('<html><head><title>' + document.title  + '</title>');
	    cus_window.document.write('</head><body>');
	    cus_window.document.write('<style>' + cus_style + '</style>');
	    cus_window.document.write(element.innerHTML);
	    cus_window.document.write('</body></html>');
	    cus_window.document.close(); // necessary for IE >= 10
	    cus_window.focus(); // necessary for IE >= 10*/
	    setTimeout(function() {
		    cus_window.print();
	    	cus_window.close();
		}, 900);
	});

	/**
	 * Download Template as a Json
	 */
	$('#wcc_seo_keyword_research_json_export').on('click', function(e) {
    	var rows = {};
    	var option = $('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]:checked').val();
    	var i = 1;
    	$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {    	
        	if ( option == 'current' ) {
                var id = $(this).attr('id');
                if ( !$(this).hasClass('disabled') && !$(this).hasClass('disable_cpc') && !$(this).hasClass('disable_com') && !$(this).hasClass('disable_nor') && !$(this).hasClass('disable_rel') ) {
                	rows[i] = {};
                    $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id + ' td').each(function(key, item) {
                    	if ( key == 0 ) {
                            rows[i]['no'] = $(this).text();
                        }
                        if ( key == 1 ) {
                            rows[i]['keyword'] = $(this).text();
                        }
                        if ( key == 2 ) {
                            rows[i]['search_volume'] = $(this).text();
                        }
                        if ( key == 3 ) {
                            rows[i]['cpc'] = $(this).text();
                        }
                        if ( key == 4 ) {
                            rows[i]['competition'] = $(this).text();
                        }
                        if ( key == 5 ) {
                            rows[i]['number_of_results'] = $(this).text();
                        }
                        if ( key == 6 ) {
                            rows[i]['related_relevance'] = $(this).text();
                        }
                    });
                    i ++;
                }
        	} else {
        		var id = $(this).attr('id');
            	rows[i] = {};
                $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id + ' td').each(function(key, item) {
                	if ( key == 0 ) {
                        rows[i]['no'] = $(this).text();
                    }
                    if ( key == 1 ) {
                        rows[i]['keyword'] = $(this).text();
                    }
                    if ( key == 2 ) {
                        rows[i]['search_volume'] = $(this).text();
                    }
                    if ( key == 3 ) {
                        rows[i]['cpc'] = $(this).text();
                    }
                    if ( key == 4 ) {
                        rows[i]['competition'] = $(this).text();
                    }
                    if ( key == 5 ) {
                        rows[i]['number_of_results'] = $(this).text();
                    }
                    if ( key == 6 ) {
                        rows[i]['related_relevance'] = $(this).text();
                    }
                });
                i ++;
        	}
        });
        $(this).attr('href', 'data:application/json;charset=UTF-8,' + encodeURIComponent( JSON.stringify(rows) ));
	});

	/**
	 * Download Template as a XML
	 */
	$('#wcc_seo_keyword_research_xml_export').on('click', function(e) {
    	var rows = $.parseXML("<xml/>");
        var xml = rows.getElementsByTagName("xml")[0];
        var temp = '';
        var option = $('#wcc_seo_keyword_research_view_radio input[name="wcc_seo_keyword_research_view_cus"]:checked').val();
        
    	$('#wcc_seo_keyword_research_valid_search_result tbody tr').each(function(index, value) {    	
        	if ( option == 'current' ) {
                var id = $(this).attr('id');
                if ( !$(this).hasClass('disabled') && !$(this).hasClass('disable_cpc') && !$(this).hasClass('disable_com') && !$(this).hasClass('disable_nor') && !$(this).hasClass('disable_rel') ) {
                	temp += '<row id="' + $(this).find('.num_col').text() + '">';
                    $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id + ' td').each(function(key, item) {
                        if ( key == 1 ) {
                        	temp += '<keyword>' + $(this).text() + '</keyword>';
                        }
                        if ( key == 2 ) {
                            temp += '<search-volume>' + $(this).text() + '</search-volume>';
                        }
                        if ( key == 3 ) {
                            temp += '<cpc>' + $(this).text() + '</cpc>';
                        }
                        if ( key == 4 ) {
                            temp += '<competition>' + $(this).text() + '</competition>';
                        }
                        if ( key == 5 ) {
                            temp += '<number-of-results>' + $(this).text() + '</number-of-results>';
                        }
                        if ( key == 6 ) {
                            temp += '<related-relevance>' + $(this).text() + '</related-relevance>';
                        }
                    });
                    temp += '</row>';
                }
        	} else {
        		var id = $(this).attr('id');
            	temp += '<row id="' + $(this).find('.num_col').text() + '">';
                $('#wcc_seo_keyword_research_valid_search_result tbody tr#' + id + ' td').each(function(key, item) {
                	if ( key == 1 ) {
                    	temp += '<keyword>' + $(this).text() + '</keyword>';
                    }
                    if ( key == 2 ) {
                        temp += '<search-volume>' + $(this).text() + '</search-volume>';
                    }
                    if ( key == 3 ) {
                        temp += '<cpc>' + $(this).text() + '</cpc>';
                    }
                    if ( key == 4 ) {
                        temp += '<competition>' + $(this).text() + '</competition>';
                    }
                    if ( key == 5 ) {
                        temp += '<number-of-results>' + $(this).text() + '</number-of-results>';
                    }
                    if ( key == 6 ) {
                        temp += '<related-relevance>' + $(this).text() + '</related-relevance>';
                    }
                });
                temp += '</row>';
        	}
        });
        xml.innerHTML = temp;
        $(this).attr('href', 'data:application/octet-stream;charset=UTF-8,' + encodeURIComponent( xml.outerHTML ));
	});

});