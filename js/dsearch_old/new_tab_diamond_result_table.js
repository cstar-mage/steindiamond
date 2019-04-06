function getCulet(short_form){
	var abbr = {
		"EX": "EXCELLENT",
		"VS": "VERY STRONG",
		"S": "STRONG",
		"M": "MEDIUM",
		"F": "FAINT",
	    "N": "NONE",
	};
	if(abbr[short_form]){
		return abbr[short_form];
	}else{
		return short_form;	
	}
}


/*var dtl = {};

dtl.symbol = '$';

dtl.currency = 1;

dtl.diamond_category = 'CONFLICT FREE DIAMONDS';

dtl.order_by = '';

dtl.order_method = '';

dtl.order_by_list = ['shape', 'carat', 'color', 'clarity', 'fluorescence', 'cut', 'collection', 'l:w', 'report', 'price'];



dtl.on_page = '';

dtl.view_link = '';

dtl.search_path = '';

dtl.last_fetch = 0;

dtl.lastAjax = null;



dtl.row = 0;

dtl.recent_row = 0;

dtl.cmp_row = 0;

dtl.row_pointer = 0;

dtl.data_size = 100;

dtl.page_size = 20;

dtl.total_count = 0;

dtl.diamonds = [];

dtl.similar = [];

dtl.is_pair = false;

dtl.viewparams = "";



dtl.orders = {

  'shape': ['Round', 'Princess', 'Asscher', 'Cushion', 'Radiant', 'Emerald', 'Oval', 'Pear', 'Marquise', 'Heart'],

  'color': ["D", "E", "F", "G", "H", "I", "J", "K", "L-Z"],

  'clarity': ["FL", "IF", "VVS1", "VVS2", "VS1", "VS2", "SI1", "SI2", "SI3", "I1"],

  'fluorescence': ["VERY STRONG", "STRONG", "MEDIUM", "FAINT", "NONE"],

  'cut': ['Fair', 'Good', 'Very Good', 'Excellent'],

  'report': ['GIA', 'EGL', 'HRD', 'IGI', 'AGS', 'Non-Certified']

};



dtl.header = '0';

dtl.header_widths = {

  '0': ['10%', '10%', '10%', '9%', '9%', '13%', '0%', '0%', '9%', '10%', '11%', '10%', '9%'],

  'c': ['7%', '7%', '8%', '9%', '10%', '10%', '10%', '0%', '9%', '9%', '9%', '10%', '7%'],

  'lw': ['7%', '7%', '10%', '9%', '9%', '12%', '0%', '5%', '9%', '9%', '11%', '10%', '9%'],

  'c_lw': ['8%', '8%', '8%', '9%', '9%', '6%', '14%', '5%', '9%', '9%', '9%', '8%', '8%', '7%']

};

dtl.old_header_widths = {

  '0': ['13%', '13%', '10%', '10%', '10%', '13%', '0%', '0%', '11%', '13%', '11%', '0%', '9%'],

  'c': ['12%', '12%', '8%', '8%', '9%', '12%', '12%', '0%', '9%', '11%', '10%', '0%', '8%'],

  'lw': ['12%', '12%', '9%', '9%', '10%', '12%', '0%', '7%', '10%', '12%', '10%', '0%', '8%'],

  'c_lw': ['11%', '11%', '8%', '8%', '9%', '10%', '12%', '4%', '9%', '11%', '9%', '0%', '10%']

};*/

function format_mesurement(mesurevalue)

{

	var res = mesurevalue.replace(/-/g, "x");

	res = res.replace(/x/g,' x ');

	res = res.concat(' mm');

	return res;

}

dtl.init = function(on_page, symbol, currency, diamond_category, row, order_by, order_method, data_size,

  view_link, search_path) {

  dtl.on_page = on_page;

  dtl.symbol = symbol;

  dtl.currency = currency;

  dtl.diamond_category = diamond_category;

  dtl.row = row;

  dtl.order_by = order_by;

  dtl.order_method = order_method;

  dtl.data_size = data_size;

  dtl.view_link = view_link;

  dtl.search_path = search_path;



  if (dtl.order_by === '') {

    dtl.order_by = 'price';

    dtl.order_method = 'asc';

  }



  dtl.update_order_by();



  if (dtl.on_page == 'CYO Earrings') {

    dtl.is_pair = true;

    dtl.page_size = 15;

  }

};



dtl.update_order_by = function() {

  if (dtl.order_by_list.indexOf(dtl.order_by) == -1) {

    return false;

  }



  for (var i = 0; i < dtl.order_by_list.length; i ++) {

    jQuery('#order_by_' + dtl.order_by_list[i].replace(':', '_')).removeClass('icons-chevron-up');

    jQuery('#order_by_' + dtl.order_by_list[i].replace(':', '_')).removeClass('icons-chevron-down');

    jQuery('#order_by_' + dtl.order_by_list[i].replace(':', '_')).addClass('icons-chevron-down');

  }



  var target = jQuery('#order_by_' + dtl.order_by.replace(':', '_'));

  target.removeClass('icons-chevron-up');

  target.removeClass('icons-chevron-down');

  if (dtl.order_method == 'asc') {

    target.addClass('icons-chevron-up');

  } else {

    target.addClass('icons-chevron-down');

  }

};



dtl.set_header = function() {

  if (jQuery('#adv_bar_fluorescence').children('.icons-checked').length === 0 && jQuery('#adv_bar_ratio').children('.icons-checked').length === 0) {

    dtl.header = '0';

  } else if (jQuery('#adv_bar_fluorescence').children('.icons-checked').length === 0) {

    dtl.header = 'lw';

  } else if (jQuery('#adv_bar_ratio').children('.icons-checked').length === 0) {

    dtl.header = 'c';

  } else {

    dtl.header = 'c_lw';

  }

  if (dtl.on_page == 'CYO Ring' && dtl.diamond_category.startsWith('LAB')) {

    jQuery('#search_result_header_table').find('th').each(function(index, el) {

      jQuery(el).attr('width', dtl.old_header_widths[dtl.header][index]);

    });

  } else{

    jQuery('#search_result_header_table').find('th').each(function(index, el) {

        jQuery(el).attr('width', dtl.header_widths[dtl.header][index]);

      });

  }

  jQuery('#search_result_header_table').find('th').show();

  jQuery('#search_result_header_table').find('th[width="0%"]').hide();

  rc_set_header('recently_viewed_table');

  fill_recently_viewed_table('recently_viewed_table', recently_viewed_diamonds);

  rc_set_header('comparison_table');

  fill_recently_viewed_table('comparison_table', comparison_diamonds);

};



dtl.fetch_result = function() {

  dtl.set_header();

  clearTimeout(dtl.last_fetch);

  dtl.last_fetch = setTimeout(function () {

    dtl.show_loading();

    if (dtl.on_page == 'CYO Earrings') {

      dtl.fetch_diamonds_pair_result();

    } else {

      dtl.fetch_diamonds_result();

    }

  }, 500);

};



dtl.re_fetch_result = function() {

  dtl.row = 0;

  dtl.fetch_result();

};



dtl.fetch_diamonds_result = function() {

    var request_params = dtl.build_request_params();



    // save request params to cookie with path.

    var simi_key = 'similar_diamonds'+jQuery.trim(curr_symbol);

    if (jQuery.cookie(simi_key)){

        jQuery.cookie(simi_key, '', {path: '/'});

    } else {

      jQuery.cookie('search_params'+jQuery.trim(dtl.symbol), JSON.stringify(request_params), {path: search_path});

    }



    //var url = "/loose-diamonds/list/";

	//var url = "list.json.txt";

	var url = host_url+"diamond-search/ajax/list/";

    if (dtl.on_page == 'Lab Created Diamonds') {

      url = host_url+"lab-diamonds/list/";

    } else if (dtl.on_page == 'CYO Ring' && dtl.diamond_category.startsWith('LAB')) {

      url = host_url+"lab-diamonds/list/";

    }



    if (dtl.lastAjax) {

      dtl.lastAjax.abort();

    }



    dtl.lastAjax = jQuery.ajax({

      url: url,

      type: 'GET',

      dataType: "json",

      data: jQuery.param(request_params),

      cache: true,

      success: function(result) {

        dtl.fill_result(result);

        dtl.similar.sort(dtl.sort_diamond);

      }

    });



};



dtl.fetch_diamonds_pair_result = function() {

  var request_params = dtl.build_request_params();



  // save request params to cookie with path.

  jQuery.cookie('search_params'+jQuery.trim(dtl.symbol), JSON.stringify(request_params), {path: search_path});



  var url = "/loose-diamonds/list-pair/";

  if (dtl.lastAjax) {

    dtl.lastAjax.abort();

  }



  dtl.lastAjax = jQuery.get(url, request_params, function(data) {

    dtl.fill_result(data);

  }, 'json');

};



dtl.build_request_params = function() {

  var request_params = {

    shapes: jQuery('#shapes').val(),
    special: jQuery('#specialshapes').val(),
    cuts: jQuery('#cuts').val(),

    colors: jQuery('#colors').val(),

    fancycolors: jQuery('#fancycolors').val(),

    clarities: jQuery('#clarities').val(),

    fluorescences: jQuery('#fluorescences').val(),

    min_carat: jQuery('#min_carat').val(),

    max_carat: jQuery('#max_carat').val(),

    min_price: jQuery('#min_price').val(),

    max_price: jQuery('#max_price').val(),

    stock_number: jQuery('#stock_number').val(),

    is_fancy: (jQuery(".active_slider").hasClass("fancycolor") ? 1 : 0),

    row: dtl.row,

    requestedDataSize: dtl.data_size,

    order_by: dtl.order_by,

    order_method: dtl.order_method,

    currency: currency_symbol,

  };



  if (dtl.on_page != 'Loose Diamonds' && dtl.on_page != 'Lab Created Diamonds') {

    request_params['sid'] = sid;

  }

  var stock_number_div = jQuery('#stock_number_div');

    if (stock_number_div.length > 0) {

      var stock_number = [];

      stock_number_div.find('.icons-checked').each(function(index, el) {

        stock_number.push(jQuery(el).parent().parent().find('input').val());

     });

     stock_number = stock_number.join(',');

     if (stock_number) { request_params.stock_number = stock_number; }

   }



  var collections_div = jQuery('#collection_div');

  if (collections_div) {

    var collections = [];

    collections_div.find('.icons-checked').each(function(index, el) {

      collections.push(jQuery(el).parent().parent().find('input').val());

    });

    collections = collections.join(',');

    if (collections) { request_params.collections = collections; }

  }



  if (dtl.on_page != 'Lab Created Diamonds' && !(dtl.on_page == 'CYO Ring' && dtl.diamond_category.startsWith('LAB'))) {

    var origins_div = jQuery('#origin_div');

    if (origins_div.length > 0) {

      var origins = [];

      origins_div.find('.icons-checked').each(function(index, el) {

        origins.push(jQuery(el).parent().parent().find('input').val());

      });

      origins = origins.join(',');

      if (origins) { request_params.origins = origins; }

    }

  }



  if (dtl.on_page != 'Lab Created Diamonds' && !(dtl.on_page == 'CYO Ring' && dtl.diamond_category.startsWith('LAB'))) {

    var reports_div = jQuery('#report_div');

    if (reports_div.length > 0) {

      var reports = [];

      reports_div.find('.icons-checked').each(function(index, el) {

        reports.push(jQuery(el).parent().parent().find('input').val());

      });

      reports = reports.join(',');

      if (reports) { request_params.reports = reports; }

    }

  }



  if (jQuery('#adv_bar_ratio').find('.icons-checked').length > 0) {

    request_params.min_ratio = jQuery('#min_ratio').val();

    request_params.max_ratio = jQuery('#max_ratio').val();

  }

  

  /*if (jQuery('select[name=shipping_day]').val() !== '' && jQuery('#is_not_advanced').val() == 'true' ) {

    request_params.shipping_day = jQuery('select[name=shipping_day]').val();

  }else if (jQuery('select[name=shipping_day_adv]').val() !== '') {

    request_params.shipping_day = jQuery('select[name=shipping_day_adv]').val();

  }*/

  

  if (jQuery('input[name=stock_number]').val() !== '') {

    request_params.shipping_day = jQuery('input[name=stock_number]').val();

  }



  if (MIN_PRICE !== "") { request_params.MIN_PRICE = MIN_PRICE; }

  if (MAX_PRICE !== "") { request_params.MAX_PRICE = MAX_PRICE; }

  if (MIN_CARAT !== "") { request_params.MIN_CARAT = MIN_CARAT; }

  if (MAX_CARAT !== "") { request_params.MAX_CARAT = MAX_CARAT; }



  if (Sys.is_android || Sys.is_iphone) {request_params.requestedDataSize = 10;}



  return request_params;

};



dtl.show_loading = function() {

  jQuery('.loading').removeClass('hidden');

};



dtl.hide_loading = function() {

  jQuery('.loading').addClass('hidden');

};



dtl.fill_result = function(data) {

  dtl.diamonds = data['diamonds'];

  if (dtl.row <= dtl.page_size) {

    dtl.row_pointer = 0;

  } else {

    dtl.row_pointer = dtl.page_size;

  }



  if (data['similar']) {

    dtl.similar = data['similar'];

  } else {

    dtl.similar = [];

  }



  dtl.reset_similar();



  if (dtl.total_count == data['total_count']) {

    dtl.update_scrollbar();

  } else {

    dtl.total_count = data['total_count'];

    dtl.recreate_scrollbar();

  }



  dtl.fill_total_count();

  dtl.fill_table();

  dtl.hide_loading();

};



dtl.fill_total_count = function() {

  if (dtl.total_count == 1){

    if (dtl.diamond_category == 'LAB CREATED DIAMONDS'){

      dtl.diamond_category = 'LAB CREATED DIAMOND';

    }else if (dtl.diamond_category == 'CONFLICT FREE DIAMONDS'){

      dtl.diamond_category = 'CONFLICT FREE DIAMONDS';

    }

  }else{

    if (dtl.diamond_category == 'LAB CREATED DIAMOND'){

      dtl.diamond_category = 'LAB CREATED DIAMONDS';

    }

    else if (dtl.diamond_category != 'LAB CREATED DIAMOND' && dtl.diamond_category != 'LAB CREATED DIAMONDS') {

      dtl.diamond_category = 'CONFLICT FREE DIAMONDS';

    }

  }

  jQuery('#totalResult').html(dtl.total_count);

};



dtl.fill_table = function() {

  if (dtl.total_count === 0) {

    jQuery('.search-nothing-tips').removeClass('hidden');

    jQuery('.search-nothing-tips').show();

    jQuery('#listContainer').addClass('hidden');

    jQuery('#search_result_table').addClass('hidden');

  } else {

    jQuery('.search-nothing-tips').addClass('hidden');

    jQuery('#listContainer').removeClass('hidden');

    jQuery('#search_result_table').removeClass('hidden');

  }



  if (dtl.on_page == 'CYO Earrings') {

    dtl.fill_diamonds_pair_table();

  } else {

    dtl.fill_diamonds_table();

  }

};



dtl.fill_diamonds_table = function() {

  jQuery('#search_result_table tbody').html('');



  var max_pointer = dtl.row_pointer + dtl.page_size > dtl.diamonds.length ? dtl.diamonds.length : dtl.row_pointer + dtl.page_size;

  for (var i = dtl.row_pointer; i < max_pointer; i ++) {

    var diamond = dtl.diamonds[i];

    var diamond_link = '';

    if (dtl.on_page == 'CYO Ring') {

      diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=' + first_tab + '&show_diamond_tab=true';

    } else if (dtl.on_page == 'CYO Pendant'){

      diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=&show_diamond_tab=true';

    }else {

      diamond_link = dtl.view_link + diamond.id + '/details/'+diamond['Carat'].toFixed(2)+'-'+diamond['Shape']+'-'+diamond['Cut']+'-'+diamond['Report']+'-'+diamond['Color']+'-'+diamond['Clarity']+'-diamond-stock-'+diamond['stock_number']+'-cert-'+diamond['CertNumber'];

	  diamond_link = diamond_link.replace(" ","_");

    }





    var tr = jQuery('<tr></tr>');

    tr.append('<input class="index" type="hidden" value="' + i + '" />');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['stock_number'] + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Shape'] +'</td>');

    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['Carat'].toFixed(2) + '</td>');

	tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['Color'] + '</td>');

    tr.append('<td width="10%" scope="col" style="text-align: center;">' + diamond['Clarity'] + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Cut'] + '</td>');

    //tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['Collection'] + '</td>');

    tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['Fluorescence'] + '</td>');

    tr.append('<td width="0%" scope="col" style="text-align: center;">' + parseFloat(diamond['length_width_ratio']).toFixed(2) + '</td>');

    tr.append('<td width="11%" scope="col" style="text-align: center;">' + diamond['Report'] + '</td>');

    if(showcolumn_inhouse) tr.append('<td width="14%" scope="col" style="text-align: center;">' + diamond['Origin'] + '</td>');

    

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + (is_cfp ? "CALL" : dtl.symbol + number_with_commas(diamond['Price']))  + '</td>');

	if(showcolumn_rapdiscount) tr.append('<td width="8%" scope="col" style="text-align: center;">' + diamond['Rap Percent'] + '</td>');

    if(showcolumn_availability) tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Availability'] + '</td>');

    if(showcolumn_dimensions) tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Measurements'] + '</td>');

    if(showcolumn_depth) tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Depth'] + '</td>');

    if(showcolumn_tabl) tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Table'] + '</td>');

    tr.append('<td data-id=' + diamond['id'] + ' width="10%" scope="col" style="text-align: center;"><div class="checkbox checkbox-ty4"><label><i class="icons-checkbox"></i></label></div></td>');

    tr.append('<td width="9%" scope="col" style="text-align: left;"><a href="' + diamond_link + '" class="view">View</a></td>');



    var tds = tr.children('td');

    for (var j = 0; j < tds.length; j ++) {

      jQuery(tds[j]).attr('width', dtl.header_widths[dtl.header][j]);

    } // adjust to header width



    tr.children('td[width="0%"]').hide();

    if (dtl.on_page == 'CYO Ring' && dtl.diamond_category.startsWith('LAB')) {

      for (var j = 0; j < tds.length; j ++) {

        jQuery(tds[j]).attr('width', dtl.old_header_widths[dtl.header][j]);

      }

      tr.children('td[data-id='+ diamond['id'] +']').hide();

    } // lab diamonds has no recently viewed tab and comparison tab



    jQuery('#search_result_table tbody').append(tr);

    jQuery('#totalResult').html(dtl.total_count);

  }



  jQuery('#search_result_table tbody tr').unbind('hover');

  jQuery('#search_result_table tbody tr td:not(:nth-child(2))').hover(function() {

    dtl.show_diamond_info(jQuery(this).parent('tr'), false);

  });// unbind hover function for first column (ipad)



  jQuery('#search_result_table tbody tr').unbind('click');

  for (var i = comparison_ids.length - 1; i >= 0; i--) {

    jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[0]).removeClass('icons-checkbox').addClass('icons-checked');

    jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[1]).removeClass('icons-checkbox').addClass('icons-checked');

  };// init the checkbox

};



dtl.fill_diamonds_pair_table = function() {

  jQuery('#search_result_table tbody').html('');



  var max_pointer = dtl.row_pointer + dtl.page_size > dtl.diamonds.length ? dtl.diamonds.length : dtl.row_pointer + dtl.page_size;

  for (var i = dtl.row_pointer; i < max_pointer; i ++) {

    var diamond = dtl.diamonds[i];

    var tr = jQuery('<tr></tr>');

    tr.append('<input class="index" type="hidden" value="' + i + '" />');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['first']['stock_number'] + '<br />' + diamond['second']['stock_number'] + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['first']['Shape'] + '<br />' + diamond['second']['Shape'] + '</td>');

    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['first']['Carat'].toFixed(2) + '<br />' + diamond['second']['Carat'].toFixed(2) + '</td>');

    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['first']['Color'] + '<br />' + diamond['second']['Color'] + '</td>');

    tr.append('<td width="10%" scope="col" style="text-align: center;">' + diamond['first']['Clarity'] + '<br />' + diamond['second']['Clarity'] + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['first']['Cut'] + '<br />' + diamond['second']['Cut'] + '</td>');

    tr.append('<td width="11%" scope="col" style="text-align: center;">' +  diamond['first']['Report'] + '<br />' + diamond['second']['Report'] + '</td>');

    //tr.append('<td width="14%" scope="col" style="text-align: center;">' + diamond['first']['Origin'] + '<br />' + diamond['second']['Origin'] + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + dtl.symbol + number_with_commas(diamond['pair_price']) + '</td>');

    if(showcolumn_rapdiscount) tr.append('<td width="8%" scope="col" style="text-align: center;">' + diamond['first']['Rap Percent'].toFixed(2) + '<br />' + diamond['second']['Rap Percent'].toFixed(2) + '</td>');

    if(showcolumn_availability) tr.append('<td width="11%" scope="col" style="text-align: center;">' + diamond['first']['Availability'] + '<br />' + diamond['second']['Availability'] + '</td>');

    if(showcolumn_dimensions) tr.append('<td width="11%" scope="col" style="text-align: center;">' + diamond['first']['Measurements'] + '<br />' + diamond['second']['Measurements'] + '</td>');

    if(showcolumn_depth) tr.append('<td width="11%" scope="col" style="text-align: center;">' + diamond['first']['Depth'] + '<br />' + diamond['second']['Depth'] + '</td>');

    if(showcolumn_tabl) tr.append('<td width="11%" scope="col" style="text-align: center;">' + diamond['first']['Table'] + '<br />' + diamond['second']['Table'] + '</td>');

	

    tr.append('<td width="9%" scope="col" style="text-align: left;"><a href="' + dtl.view_link + diamond.diamond1 + '/' + diamond.diamond2 + '/?sid=' + sid + '&first=&show_diamond_tab=true" class="view">View</a></td>');

    jQuery('#search_result_table tbody').append(tr);

  }



  jQuery('#search_result_table tbody tr').unbind('hover');

  jQuery('#search_result_table tbody tr').hover(function() {

    dtl.show_diamond_info(jQuery(this), false);

  });

};



dtl.fill_similar_table = function() {

  jQuery('#search_result_table tbody').html('');



  for (var i = 0; i < dtl.similar.length; i ++) {

    var diamond = dtl.similar[i];

    var diamond_link = '';

    if (dtl.on_page == 'CYO Ring') {

      diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=' + first_tab + '&show_diamond_tab=true';

    } else {

      diamond_link = dtl.view_link + diamond.id + '/';

    }



    var tr = jQuery('<tr></tr>');

    tr.append('<input class="index" type="hidden" value="' + i + '" />');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['stock_number'] + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Shape'] + '</td>');

    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['Carat'].toFixed(2) + '</td>');

    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['Color'] + '</td>');

    tr.append('<td width="10%" scope="col" style="text-align: center;">' + diamond['Clarity'] + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Cut'] + '</td>');

    //tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['Collection'] + '</td>');

    tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['Fluorescence'] + '</td>');

    tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['length_width_ratio'] + '</td>');

    tr.append('<td width="11%" scope="col" style="text-align: center;">' + diamond['Report'] + '</td>');

    //tr.append('<td width="14%" scope="col" style="text-align: center;">' + diamond['Origin'] + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + (is_cfp ? "CALL" : dtl.symbol + number_with_commas(diamond['Price']))  + '</td>');

    //tr.append('<td width="12%" scope="col" style="text-align: center;">' + dtl.symbol + number_with_commas(diamond['Price']) + '</td>');

    if(showcolumn_rapdiscount) tr.append('<td width="8%" scope="col" style="text-align: center;">' + diamond['Rap Percent'] + '</td>');

    if(showcolumn_availability) tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Availability'] + '</td>');

    if(showcolumn_dimensions) tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Measurements'] + '</td>');

    if(showcolumn_depth) tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Depth'] + '</td>');

    if(showcolumn_tabl) tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['Table'] + '</td>');

	

    tr.append('<td data-id=' + diamond['id'] + ' width="10%" scope="col" style="text-align: center;"><div class="checkbox checkbox-ty4"><label><i class="icons-checkbox"></i></label></div></td>');

    tr.append('<td width="9%" scope="col" style="text-align: left;"><a href="' + diamond_link + '" class="view">View</a></td>');



    var tds = tr.children('td');

    for (var j = 0; j < tds.length; j ++) {

      jQuery(tds[j]).attr('width', dtl.header_widths[dtl.header][j]);

    }

    tr.children('td[width="0%"]').hide();

    if (dtl.on_page == 'CYO Ring' && dtl.diamond_category.startsWith('LAB')) {

      for (var j = 0; j < tds.length; j ++) {

        jQuery(tds[j]).attr('width', dtl.old_header_widths[dtl.header][j]);

      }

      tr.children('td[data-id='+ diamond['id'] +']').hide();

    }



    jQuery('#search_result_table tbody').append(tr);

  }



  jQuery('#search_result_table tbody tr').unbind('hover');

  jQuery('#search_result_table tbody tr td:not(:nth-child(2))').hover(function() {

    dtl.show_diamond_info(jQuery(this).parent('tr'), true);

  });



  jQuery('#search_result_table tbody tr').unbind('click');

  jQuery('#totalResult').html(dtl.similar.length);

  for (var i = comparison_ids.length - 1; i >= 0; i--) {

    jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[0]).removeClass('icons-checkbox').addClass('icons-checked');

    jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[1]).removeClass('icons-checkbox').addClass('icons-checked');

  };// init the checkbox

};



dtl.update_table = function(_row) {

  dtl.row_pointer = _row - dtl.row;

  dtl.fill_table();

};



dtl.update_recent_table = function() {

  var _row = dtl.recent_row ? dtl.recent_row : 0;

  fill_recently_viewed_table('recently_viewed_table', recently_viewed_diamonds);

}



dtl.update_cmp_table = function() {

  var _row = dtl.cmp_row ? dtl.cmp_row : 0;

  fill_recently_viewed_table('comparison_table', comparison_diamonds);

}



dtl.update_scrollbar = function() {

  var not_init = jQuery("#result-scrollbar .minimal-vertical").html().trim() === '';

  if (not_init) {

    dtl.recreate_scrollbar();

  } else {

    jQuery("#result-scrollbar .minimal-vertical").val(dtl.row + dtl.row_pointer);

  }

};



dtl.update_recent_scrollbar = function() {

  var not_init = jQuery('#recent-scrollbar .minimal-vertical').html().trim() === '';

  if (not_init) {

    dtl.recreate_recent_scrollbar();

  } else {

    jQuery("#recent-scrollbar .minimal-vertical").val(dtl.recent_row);

  }

}



dtl.update_cmp_scrollbar = function() {

  dtl.recreate_cmp_scrollbar();

}



dtl.recreate_scrollbar = function() {

  var max_range = dtl.total_count - dtl.page_size > 0 ? dtl.total_count - dtl.page_size : 0;

  if (max_range !== 0) {

    jQuery('#result-scrollbar').removeClass('hidden');

    if (jQuery("#result-scrollbar .minimal-vertical").html().trim() === '') {

      jQuery("#result-scrollbar .minimal-vertical").noUiSlider({

        range: [0, max_range],

        start: dtl.row + dtl.row_pointer,

        step: 1,

        handles: 1,

        orientation: "vertical",

        slide: function() {

          var _row = parseInt(jQuery(this).val(), 10);

          if (_row >= dtl.row && _row <= dtl.row + dtl.diamonds.length - dtl.page_size) {

            // scroll in the result set, update table, otherwise do nothing

            dtl.update_table(_row);

          }

        }

      }).change(function(event) {

        var _row = parseInt(jQuery(this).val(), 10);

        if (!(_row >= dtl.row && _row <= dtl.row + dtl.diamonds.length - dtl.page_size)) {

          // scroll in the result set is cover by slide, otherwise fetch new result

          if (_row - dtl.page_size >= 0) {

              dtl.row = _row - dtl.page_size;

          } else {

              dtl.row = 0;

          }

          dtl.fetch_result();

        }

      });

    } else {

      jQuery("#result-scrollbar .minimal-vertical").noUiSlider({

        range: [0, max_range - 1],

        start: dtl.row + dtl.row_pointer,

      }, true);

    }

  } else {

    jQuery('#result-scrollbar').addClass('hidden');

  }

};



dtl.recreate_recent_scrollbar = function() {

  var max_range = recently_viewed_totalcount - dtl.page_size > 0 ? recently_viewed_totalcount - dtl.page_size : 0;

  if (max_range !== 0) {

    jQuery('#recent-scrollbar').removeClass('hidden');

    if (jQuery("#recent-scrollbar .minimal-vertical").html().trim() === '') {

      jQuery("#recent-scrollbar .minimal-vertical").noUiSlider({

        range: [0, recently_viewed_totalcount - 1],

        start: dtl.recent_row,

        step: 1,

        handles: 1,

        orientation: "vertical",

        set: function() {

          var _row = parseInt(jQuery(this).val(), 10);

          if (_row != dtl.recent_row) {

            // scroll in the result set, update table, otherwise do nothing

            dtl.recent_row = _row;

            dtl.update_recent_table();

          }

        }

      })

    } else {

      jQuery("#recent-scrollbar .minimal-vertical").noUiSlider({

        range: [0, recently_viewed_totalcount - 1],

        start: dtl.recent_row,

      }, true);

    }

  } else {

    jQuery('#recent-scrollbar').addClass('hidden');

  }

}



dtl.recreate_cmp_scrollbar = function() {

  var total_count = comparison_ids.length;

  var max_range = total_count - dtl.page_size > 0 ? total_count - dtl.page_size : 0;

  if (max_range !== 0) {

    jQuery('#cmp-scrollbar').removeClass('hidden');

    if (jQuery("#cmp-scrollbar .minimal-vertical").html().trim() === '') {

      jQuery("#cmp-scrollbar .minimal-vertical").noUiSlider({

        range: [0, total_count - 1],

        start: dtl.cmp_row,

        step: 1,

        handles: 1,

        orientation: "vertical",

        set: function() {

          var _row = parseInt(jQuery(this).val(), 10);

          if (_row != dtl.cmp_row) {

            // scroll in the result set, update table, otherwise do nothing

            dtl.cmp_row = _row;

            dtl.update_cmp_table();

          }

        }

      })

    } else {

      jQuery("#cmp-scrollbar .minimal-vertical").noUiSlider({

        range: [0, total_count - 1],

        start: dtl.cmp_row,

      }, true);

    }

  } else {

    jQuery('#cmp-scrollbar').addClass('hidden');

  }

}






dtl.show_diamond_info = function(target, similar_flag) {

  if (dtl.on_page == 'CYO Earrings') {

    dtl.show_diamond_pair_info(target);

  } else {

    dtl.show_diamond_single_info(target, similar_flag);

  }

};



dtl.show_diamond_single_info = function(target, similar_flag) {

  var special_class = is_touchable ? ' hide' : '';

  var diamond = null;

  if (!similar_flag) {

    diamond = dtl.diamonds[target.children('.index').val()];

  } else {

    diamond = dtl.similar[target.children('.index').val()];

  }



  var bank_wire_price = '';

  if (diamond['Price'] > 1000){

    var f_x = parseFloat((diamond['Price'] * 0.985).toFixed(2));

    bank_wire_price =  dtl.symbol  + number_with_commas(Math.round(Math.round(f_x)/5)*5);

  }

  jQuery('.no-info').hide();

  var diamond_link = '';

  if (dtl.on_page == 'CYO Ring') {

    diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=' + first_tab + '&show_diamond_tab=true';

  } else if (dtl.on_page == 'CYO Pendant'){

    diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=&show_diamond_tab=true';

  }else {

    //diamond_link = dtl.view_link + diamond.id + '/';

  	diamond_link = dtl.view_link + diamond.id + '/details/'+diamond['Carat'].toFixed(2)+'-'+diamond['Shape']+'-'+diamond['Cut']+'-'+diamond['Report']+'-'+diamond['Color']+'-'+diamond['Clarity']+'-diamond-stock-'+diamond['stock_number']+'-cert-'+diamond['CertNumber'];

	diamond_link = diamond_link.replace(" ","_");

  }



  jQuery('#diamond_info_panel').html(

    '<a class="btn btn-lg btn-success' + special_class + '" href='+ diamond_link +' >' +

    'view diamond' +

    '</a>' +

    '<div class="row cs-row">' +

      '<div class="col-md-12 col-sm-12">' +

        '<dl>' +

        '</dl>' +

      '</div>' +

      '<div class="col-md-12 col-sm-12">' +

        '<dl>' +

        '</dl>' +

      '</div>' +

    '</div>' +

    '<div class="row cs-row">' +

      '<div class="col-md-12">' +

        '<dl>' +

        '</dl>' +

      '</div>' +

    '</div>'

  );

function getAbbr(short_form){
	var abbr = {
		"VG": "VERY GOOD",
		"G": "GOOD",
		"F": "FAIR",
		"I": "IDEAL",
	    "EX": "EXCELLENT",
		"N": "NONE",
	};
	if(abbr[short_form]){
		return abbr[short_form];
	}else{
		return short_form;	
	}
}

function getAbbrflour(short_form){
	var abbr = {
		"VS": "VERY STRONG",
		"S": "STRONG",
		"F": "FAINTH",
		"EX": "EXCELLENT",
	    "EX": "EXCELLENT",
		"N": "NONE",
		"M":"MEDIUM",   
		 
	};
	if(abbr[short_form]){
		return abbr[short_form];
	}else{
		return short_form;	
	}
}

function getculet(short_form){
	var abbr = {
		"L": " LARGE",
		"M": " MEDIUM",
		"N": "NONE",
		"S": " SMALL",
	    "SL": "SLIGHTLY LARGE",
		"VS": "VERY SMALL",
		 
	};
	if(abbr[short_form]){
		return abbr[short_form];
	}else{
		return short_form;	
	}
}

function getGridle(short_form){
	var abbr = {
		"M-VTK": "Medium to Very Thick",
		"STK-TK": "Slightly Thick to Thick",
		"M-M": "Medium",
		"STK-VTK": "Slightly Thick to Very Thick",
		"TN-TK": "Thin to Thick",
	    "TK-ETK": "Thick to Extremely Thick",
	    "VTN-STK":"Very Thin to Slightly Thick",
	    "TK":"Thick",
	    "M-STK":"Medium to Slightly Thick",
	    "TN":"Thin",
	    "M":"Medium",
	    "M-STK":"Medium to Slightly Thick",
	    "M-TK":"Medium to Thick",
	    "TN-STK":"Thin to Slightly Thick",
	    "M-TK" :"Medium to Thick",
	    "TN-M":"Thin to Medium",
	    "TN-VTK":"Thin to Very Thick",
	    "TK-VTK":"Thick to Very Thick",
	    "VTN-TN":"Very Thin to Thin",
	    "VTN-TK":"Very Thin to Thick",
	    "TK-TK":"Thick to Thick",
	    "ETN-STK":"Extremely Thin to Slightly Thick",
	    "STK-ETK":"Slightly Thick to Extremely Thick",
	    "VTK-ETK":"Very Thick to Extremely Thick",
	    "SLIGHTLY THIN - SLIGHTLY THIN":"Slightly Thin to Slightly Thin",
	    "SLIGHTLY THIN - SLIGHTLY THICK":"Slightly Thin to Slightly Thick",
	    "SLIGHTLY THIN - MEDIUM":"Slightly Thin to Medium",
	    "SLIGHTLY THICK - VERY THICK":"Slightly Thick to Very Thick",
	    "SLIGHTLY THICK - THIN":"Slightly Thick to Thin",
	    "SLIGHTLY THICK - THICK":"Slightly Thick to Thick",
	    "SLIGHTLY THICK - SLIGHTLY THICK":"Slightly Thick to Slightly Thick",
	    "SLIGHTLY THICK - MEDIUM":"Slightly Thick to Medium",
	    "SLIGHTLY THICK - EXTRA. THICK":"Slightly Thick to Extr. Thick",
	    "MEDIUM - VERY THICK":"Medium to Very Thick",
	    "MEDIUM - THIN":"Medium - Thin",
	    "MEDIUM - THICK":"Medium - Thick",
	    "MEDIUM - SLIGHTLY THIN": "Medium to Slightly Thin",
	    "MEDIUM - SLIGHTLY THICK":"Medium - Slightly Thick", 
	    "MEDIUM - MEDIUM":"Medium",
	    "MEDIUM - EXTR. THIN":"Medium to Extremely Thin",
	    "MEDIUM - EXTR. THICK":"Medium to Extremely Thick",
	    "EXTR. THIN - VERY THIN":"Extremely Thin to Very Thin",
	    "EXTR. THIN - VERY THICK":"Extremely Thin to Very Thick",
	    "EXTR. THIN - THIN":"Extr. Thin to Thin",	
		   
	};  
	if(abbr[short_form]){
		return abbr[short_form];
	}else{
		return short_form;	
	}
}


     
    	//alert("BBB" + position_avilability);
			var l_dl = jQuery('#diamond_info_panel > div').eq(0).children('div').eq(0).children('dl').eq(0);
			
			jQuery.each(attribute_position, function(index, element) {
				if( (element.avail!=1 && element.avail!=null ))
				{   return true;}
				if((diamond[ element.forjs ]=='') || (diamond[ element.forjs] == undefined))
				{  return true;   }
				if( (element.forjs=='Cut') && (diamond[ element.forjs ]=="N"))
				{   return true;}	
				l_dl.append('<dt class="dynamic">' + element.label + '</dt>');
				if(element.forjs=='Carat'){
					l_dl.append('<dd class="dynamic">' + diamond[ element.forjs ].toFixed(2) + '</dd>');
				}else if(element.forjs=='Measurements'){
					l_dl.append('<dd class="dynamic" style="text-transform:lowercase">' + format_mesurement(diamond[ element.forjs ]) + '</dd>');
				}else if(element.forjs=='Depth' || element.forjs=='Table'){
					l_dl.append('<dd class="dynamic">' + parseFloat(diamond[ element.forjs ]) + '%</dd>');
					
				}
				
				else if(element.forjs=='Color'){
					l_dl.append('<dd class="dynamic">' + diamond[ element.forjs ] + '</dd>');
					
				}
				else if(element.forjs=='Girdle'){
					l_dl.append('<dd class="dynamic">' + getGridle(diamond[ element.forjs ].toUpperCase())  + '</dd>');
					
				}
				else if(element.forjs=='Fluorescence'){
					l_dl.append('<dd class="dynamic">' + getAbbrflour(diamond[ element.forjs ]) + '</dd>');	
				}
				else if(element.forjs=='Culet'){ 
					l_dl.append('<dd class="dynamic">' + getculet(diamond[ element.forjs ]) + '</dd>');	
				}
				else{
					l_dl.append('<dd class="dynamic">' + getAbbr(diamond[element.forjs]) + '</dd>');
				}
				// console.log("attrcode : " + element.attrcode + "  =>   label : " + element.label + element.toSource())
				// console.log(diamond.toSource());
			});
		
			/*
			l_dl.append('<dt>-------</dt>');
			l_dl.append('<dd>-------</dd>');
		     */
		
		  
		 
		
		  /*
		  if(diamond['Cut'].toUpperCase() != 'N' && diamond['Cut'] !='')
		  {
			  if (diamond['Cut']) {
		
				  l_dl.append('<dt>Cut:</dt>');
		
				  l_dl.append('<dd>' + getAbbr(diamond['Cut']).toUpperCase() + '</dd>');
		
			  }
			  
		  }
		  
		    }
		    */
		
		
		  
		
		  var r_dl = jQuery('#diamond_info_panel > div').eq(0).children('div').eq(1).children('dl').eq(0);
		
		  
		
		
		
		  
		
		
		 
		
		  
		
		  /*if (diamond['Price'] > 1000){
		
		  	r_dl.append('<dt>BANK WIRE PRICE:</dt>');
		
			  r_dl.append('<dd>' + bank_wire_price + '</dd>');
		
			}*/
		
		 
		
		 
	 


  /*var b_dl = jQuery('#diamond_info_panel > div').eq(1).children('div').eq(0).children('dl').eq(0);

  b_dl.append('<dt class="shipping-info">SHIPPING INFORMATION</dt>');

  b_dl.append('<dt>ORDER LOOSE BY:</dt>');

  b_dl.append('<dd>' + diamond['orderby'].toUpperCase() + '</dd>');

  b_dl.append('<dt>RECEIVE LOOSE BY:</dt>');

  b_dl.append('<dd>' + diamond['receiveby'].toUpperCase() + '</dd>');*/



  jQuery('#diamond_info_panel').show();

};





dtl.hide_diamond_info = function() {

  jQuery('#diamond_info_panel').hide();

  jQuery('.no-info').show();

};



dtl.change_order_by = function(order_by) {

  if (order_by == dtl.order_by) {

    if (dtl.order_method == 'asc') {

      dtl.order_method = 'desc';

    } else {

      dtl.order_method = 'asc';

    }

  } else {

    if (dtl.order_by_list.indexOf(order_by) == -1) {

      return false;

    } else {

      dtl.order_by = order_by;

      dtl.order_method = 'desc';

    }

  }



  if (jQuery('#original_bar').length === 0 || jQuery('#original_bar:visible').length > 0) {

    dtl.update_order_by();

    dtl.fetch_result();

  } else {

    dtl.update_order_by();

    dtl.similar.sort(dtl.sort_diamond);

    dtl.fill_similar_table();

  }

};



dtl.reset_search = function() {

  jQuery('.product-specialshape li').removeClass('active');
  jQuery("#specialshapes").val('');	
   delete_cookie('spacialnew');	
 // delete_cookie('spacialnew');	
  reset_search_panel();

  dtl.row = 0;

  dtl.row_pointer = 0;

  dtl.order_by = 'price';

  dtl.order_method = 'asc';

  dtl.update_order_by();

  dtl.fetch_result();

};



dtl.view_similar = function() {

  dtl.original_or_similar_click();

  //jQuery('#original_bar').hide();

  is_similar_show = 'Y';

  jQuery('#orginal_bar_link').hide();

  jQuery('#similar_bar').show();

  jQuery('.search-nothing-tips').hide();

  jQuery('#listContainer').removeClass('hidden');

  jQuery('#search_result_table').removeClass('hidden');

  jQuery('#similar_bar_link').css('display','block');

  dtl.fill_similar_table();

};



dtl.view_original = function() {

  dtl.original_or_similar_click();

  is_similar_show = 'N';

  jQuery('#orginal_bar_link').show();

  jQuery('#similar_bar').hide();

  jQuery('#original_bar').show();

  jQuery('#similar_bar_link').css('display','none');

  jQuery('#totalResult').html(dtl.total_count);

  dtl.fill_table()

};



dtl.reset_similar = function() {

  jQuery('#similar_bar').hide();

  jQuery('#original_bar').show();

  if (dtl.similar && dtl.similar.length > 0) {

    jQuery('#similar_link').show();

  } else {

    jQuery('#similar_link').hide();

  }

};



dtl.gotoPage = function(obj) {

    var link = jQuery(obj).find("a.view").attr("href");

    location.href = link;

};



dtl.sort_diamond = function(d1, d2) {

  var result = 0;

  var order_attr = dtl.order_by[0].toUpperCase() + dtl.order_by.substring(1);



  if (dtl.orders[dtl.order_by] !== undefined) {

    result = dtl.orders[dtl.order_by].indexOf(d1[order_attr]) - dtl.orders[dtl.order_by].indexOf(d2[order_attr]);

    if (dtl.order_method == 'desc') {

      result = -result;

    }

  } else if (dtl.order_by == 'origin') {

    result = d1['Origin'] > d2['Origin'];

    if (dtl.order_method == 'desc') {

      result = !result;

    }

  } else {

    result = d1[order_attr] * 1 - d2[order_attr] * 1;

    if (dtl.order_method == 'desc') {

      result = -result;

    }

  }



  return result;

};



dtl.original_or_similar_click = function(){

  if (dtl.diamond_category.startsWith('LAB')) {

  }

  else{

    jQuery(jQuery('#tab_diamonds_search').parent()).addClass('active');

    jQuery(jQuery('#tab_diamonds_recently_viewed').parent()).removeClass('active');

    jQuery(jQuery('#tab_diamonds_comparison').parent()).removeClass('active');

    jQuery('.scrollbar').css('visibility', 'hidden');

    jQuery('#result-scrollbar').css('visibility','visible');

    jQuery('#search_recently_header_table').css('display', 'none');

    jQuery('#search_comparison_header_table').css('display', 'none');

    jQuery('#search_result_header_table').css('display', '');

    jQuery('#diamonds_recently_viewed_table').css('display','none');

    jQuery('#diamonds_comparison_table').css('display','none');

    jQuery('#diamonds_search_table').css('display','block');

  }

}



is_similar_show = 'N';



recently_viewed_diamonds = [];

recently_viewed_totalcount = 0;

r_order_by = '';

r_order_mothod = '';



comparison_diamonds = [];

comparison_ids = [];

c_order_by = '';

c_order_mothod = '';



function fetch_recently_viewed_result() {

  //var url = "/loose-diamonds/list-recently-viewed/";

  //var url = "list.recentview.json.txt";

  var url = host_url+"diamond-search/ajax/listrecent/";



  var request_params = dtl.build_request_params();

  lastAjax = jQuery.get(url, request_params, function(data) {

    fill_recently_viewed_result(data);

  }, 'json');

};



function fill_recently_viewed_result(data) {

  recently_viewed_diamonds = data['diamonds']

  if (recently_viewed_totalcount == data['total_count']) {

    dtl.update_recent_scrollbar();

  } else {

    recently_viewed_totalcount = data['total_count'];

    dtl.recreate_recent_scrollbar();

  }



  jQuery('#recently_viewed_totalResult').html(recently_viewed_totalcount);

  fill_recently_viewed_table('recently_viewed_table', recently_viewed_diamonds);

};



function rc_set_header(table_name) {

  if (table_name == 'recently_viewed_table'){

    table_header_name = 'search_recently_header_table';

  }

  else if(table_name == 'comparison_table'){

    table_header_name = 'search_comparison_header_table';

  }

  table_header_name_str = '#' + table_header_name;



  jQuery(table_header_name_str).find('th').each(function(index, el) {

        jQuery(el).attr('width', dtl.header_widths[dtl.header][index]);

      });

  jQuery(table_header_name_str).find('th').show();

  jQuery(table_header_name_str).find('th[width="0%"]').hide();

}





function fill_recently_viewed_table(table_name, diamond_values) {

  var table_name_str = '#' + table_name + ' tbody';

  

  jQuery(table_name_str).html('');

  //var max_pointer = dtl.row_pointer + dtl.page_size > diamond_values.length ? diamond_values.length : dtl.row_pointer + dtl.page_size;

  var row_key = table_name == 'recently_viewed_table' ? dtl.recent_row : dtl.cmp_row;

  var _row = row_key ? row_key : 0;

  var max_length = diamond_values.length;

  if (diamond_values.length <= dtl.page_size){

    _row = 0;

  }

  var max_pointer = max_length - _row > dtl.page_size ? _row + dtl.page_size : max_length;

  

  for (var i = _row; i < max_pointer; i ++) {

    var diamond = diamond_values[i];

    var diamond_link = '';

    if (dtl.on_page == 'CYO Ring') {

      diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=' + first_tab + '&show_diamond_tab=true';

    } else if (dtl.on_page == 'CYO Pendant'){

      diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=&show_diamond_tab=true';

    }else {

      //diamond_link = dtl.view_link + diamond.id + '/';

  		diamond_link = dtl.view_link + diamond.id + '/details/'+diamond['Carat'].toFixed(2)+'-'+diamond['Shape']+'-'+diamond['Cut']+'-'+diamond['Report']+'-'+diamond['Color']+'-'+diamond['Clarity']+'-diamond-stock-'+diamond['stock_number']+'-cert-'+diamond['CertNumber'];

		diamond_link = diamond_link.replace(" ","_");

	}



    if(table_name=='comparison_table' && i == 0){

    	var table_name_str_thead = '#' + table_name + ' thead';

    	jQuery(table_name_str_thead+" tr").remove();

    	

    	var trHead = jQuery('<tr></tr>');

    	//trHead.append('<td width="12%" scope="col" style="text-align: center;">Diamond</td>');

    	trHead.append('<td width="12%" scope="col" style="text-align: center;height:120px">SKU</td>');

	    trHead.append('<td width="12%" scope="col" style="text-align: center;">Shape</td>');

	    trHead.append('<td width="9%" scope="col" style="text-align: center;">Carat</td>');

	    trHead.append('<td width="9%" scope="col" style="text-align: center;">Color</td>');

	    trHead.append('<td width="10%" scope="col" style="text-align: center;">Clarity</td>');

	    trHead.append('<td width="12%" scope="col" style="text-align: center;">Cut</td>');

	    trHead.append('<td width="0%" scope="col" style="text-align: center;">Fluorescence</td>');

	    trHead.append('<td width="0%" scope="col" style="text-align: center;">length_width_ratio</td>');

	    trHead.append('<td width="11%" scope="col" style="text-align: center;">Report</td>');

	    trHead.append('<td width="12%" scope="col" style="text-align: center;">Price</td>');

	    if(showcolumn_rapdiscount) trHead.append('<td width="8%" scope="col" style="text-align: center;">Rap Percent</td>');

	    if(showcolumn_availability) trHead.append('<td width="12%" scope="col" style="text-align: center;">Availability</td>');

	    if(showcolumn_dimensions) trHead.append('<td width="12%" scope="col" style="text-align: center;">Measurements</td>');

	    if(showcolumn_depth) trHead.append('<td width="12%" scope="col" style="text-align: center;">Depth</td>');

	    if(showcolumn_tabl) trHead.append('<td width="12%" scope="col" style="text-align: center;">Table</td>');

	    trHead.append('<td data-id=id width="10%" scope="col" style="text-align: center;">'+com_req_label+'</td>');

	    trHead.append('<td width="9%" scope="col" style="text-align: left;">View</td>');

	    var tds = trHead.children('td');

	    for (var j = 0; j < tds.length; j ++) {

	      jQuery(tds[j]).attr('width', dtl.header_widths[dtl.header][j]);

	    }

	

	    trHead.children('td[width="0%"]').hide();

	    

	    jQuery(table_name_str_thead).append(trHead);

	    

	    //ADDING REQUEST BUTTON

	    var table_name_str_tfoot = '#' + table_name + ' tfoot';

    	jQuery(table_name_str_tfoot+" tr").remove();

    	

    	var trFoot = jQuery('<tr></tr>');

    	trFoot.append('<td width="100%" scope="col" colspan="100"><a href="#">MAIL REQUESTED DIAM</a></td>');

    	jQuery(table_name_str_tfoot).append(trFoot);

    }

    

    var tr = jQuery('<tr></tr>');

    if(table_name=='comparison_table') tr = jQuery('<tr style="width:'+(100/max_pointer)+'%"></tr>');

    var checkbox_name = 'icons-checkbox';

    if(table_name=='comparison_table') checkbox_name = 'icons-checked';

    //if(table_name=='comparison_table') tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['stock_number'] + '</td>');

    tr.append('<input class="index" type="hidden" value="' + i + '" />');

    var frstRow = "";

    if(diamond['diamond_image'] != "" && table_name=='comparison_table') frstRow += "<img src='"+diamond['diamond_image']+"' width='60' /><br>";

    frstRow += diamond['stock_number'];

    var ht = "";

    if(table_name=='comparison_table') ht = "height:120px";

    tr.append('<td width="12%" scope="col" style="text-align: center;'+ht+'">' + frstRow + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + (diamond['Shape'] ? diamond['Shape'] : "-") + '</td>');

    tr.append('<td width="9%" scope="col" style="text-align: center;">' + (diamond['Carat'].toFixed(2) ? diamond['Carat'] : "-") + '</td>');

    tr.append('<td width="9%" scope="col" style="text-align: center;">' + (diamond['Color'] ? diamond['Color'] : "-") + '</td>');

    tr.append('<td width="10%" scope="col" style="text-align: center;">' + (diamond['Clarity'] ? diamond['Clarity'] : "-") + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + (diamond['Cut'] ? diamond['Cut'] : "-") + '</td>');

    //tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['Collection'] + '</td>');

    tr.append('<td width="0%" scope="col" style="text-align: center;">' + (diamond['Fluorescence'] ? diamond['Fluorescence'] : "-") + '</td>');

    tr.append('<td width="0%" scope="col" style="text-align: center;">' + (diamond['length_width_ratio'] ? diamond['length_width_ratio'] : "-") + '</td>');

    tr.append('<td width="11%" scope="col" style="text-align: center;">' + (diamond['Report'] ? diamond['Report'] : "-") + '</td>');

    if(showcolumn_inhouse) tr.append('<td width="14%" scope="col" style="text-align: center;">' + diamond['Origin'] + '</td>');

    tr.append('<td width="12%" scope="col" style="text-align: center;">' + (is_cfp ? "CALL" : dtl.symbol + number_with_commas(diamond['Price']))  + '</td>');

    //tr.append('<td width="12%" scope="col" style="text-align: center;">' + (diamond['Price'] ? (dtl.symbol + number_with_commas(diamond['Price'])) : "-") + '</td>');

    if(showcolumn_rapdiscount) tr.append('<td width="8%" scope="col" style="text-align: center;">' + (diamond['Rap Percent'] ? diamond['Rap Percent'] : "-") + '</td>');

    if(showcolumn_availability) tr.append('<td width="12%" scope="col" style="text-align: center;">' + (diamond['Availability'] ? diamond['Availability'] : "-") + '</td>');

    if(showcolumn_dimensions) tr.append('<td width="12%" scope="col" style="text-align: center;">' + (diamond['Measurements'] ? diamond['Measurements'] : "-") + '</td>');

    if(showcolumn_depth) tr.append('<td width="12%" scope="col" style="text-align: center;">' + (diamond['Depth'] ? diamond['Depth'] : "-") + '</td>');

    if(showcolumn_tabl) tr.append('<td width="12%" scope="col" style="text-align: center;">' + (diamond['Table'] ? diamond['Table'] : "-") + '</td>');

	

    tr.append('<td data-id=' + diamond['id'] + ' width="10%" scope="col" style="text-align: center;"><div class="checkbox checkbox-ty4"><label><i class='+checkbox_name+'></i></label></div></td>');

    tr.append('<td width="9%" scope="col" style="text-align: left;"><a href="' + diamond_link + '" class="view">View</a></td>');



    var tds = tr.children('td');

    for (var j = 0; j < tds.length; j ++) {

      jQuery(tds[j]).attr('width', dtl.header_widths[dtl.header][j]);

    }



    tr.children('td[width="0%"]').hide();

    jQuery(table_name_str).append(tr);

  }



  jQuery(table_name_str+' tr').unbind('hover');

  jQuery(table_name_str+' tr td:not(:nth-child(2))').hover(function() {

    new_show_diamond_info(jQuery(this).parent('tr'), table_name);

  });// unbind hover function for first column (ipad)



  jQuery(table_name_str+' tr').unbind('click');

  for (var i = comparison_ids.length - 1; i >= 0; i--) {

    jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[0]).removeClass('icons-checkbox').addClass('icons-checked');

    jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[1]).removeClass('icons-checkbox').addClass('icons-checked');

  };// init the checkbox

}



function new_show_diamond_info(target, name){

  var special_class = is_touchable ? ' hide' : '';

  if (name == 'recently_viewed_table'){

    diamond = recently_viewed_diamonds[target.children('.index').val()];

  }

  else if (name == 'comparison_table'){

    diamond = comparison_diamonds[target.children('.index').val()];

  }



  var bank_wire_price = '';

  if (diamond['Price'] > 1000){

    var f_x = parseFloat((diamond['Price'] * 0.985).toFixed(2));

    bank_wire_price =  dtl.symbol  + number_with_commas(Math.round(Math.round(f_x)/5)*5);

  }



  var diamond_link = '';

  if (dtl.on_page == 'CYO Ring') {

    diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=' + first_tab + '&show_diamond_tab=true';

  } else if (dtl.on_page == 'CYO Pendant'){

    diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=&show_diamond_tab=true';

  }else {

    //diamond_link = dtl.view_link + diamond.id + '/';

	diamond_link = dtl.view_link + diamond.id + '/details/'+diamond['Carat'].toFixed(2)+'-'+diamond['Shape']+'-'+diamond['Cut']+'-'+diamond['Report']+'-'+diamond['Color']+'-'+diamond['Clarity']+'-diamond-stock-'+diamond['stock_number']+'-cert-'+diamond['CertNumber'];

	diamond_link = diamond_link.replace(" ","_");

  }



  jQuery('.no-info').hide();

  jQuery('#diamond_info_panel').html(

    '<a  class="btn btn-lg btn-success' + special_class + '" href='+ diamond_link +' >' +

    'view diamond' +

    '</a>' +

    '<div class="row cs-row">' +

      '<div class="col-md-12 col-sm-12">' +

        '<dl>' +

        '</dl>' +

      '</div>' +

      '<div class="col-md-12 col-sm-12">' +

        '<dl>' +

        '</dl>' +

      '</div>' +

    '</div>' +

    '<div class="row cs-row">' +

      '<div class="col-md-12">' +

        '<dl>' +

        '</dl>' +

      '</div>' +

    '</div>'

  );



  var l_dl = jQuery('#diamond_info_panel > div').eq(0).children('div').eq(0).children('dl').eq(0);

  if (diamond['stock_number']){

    l_dl.append('<dt>STOCK NUMBER:</dt>');

    l_dl.append('<dd>' + diamond['stock_number'] + '</dd>');

  }

  if (diamond['Shape']){

    l_dl.append('<dt>SHAPE:</dt>');

    l_dl.append('<dd>' + diamond['Shape'].toUpperCase() + '</dd>');

  }

  if (diamond['Carat']){

    l_dl.append('<dt>CARAT WEIGHT:</dt>');

    l_dl.append('<dd>' + diamond['Carat'].toFixed(2) + '</dd>');

  }

  if (diamond['Color']) {

    l_dl.append('<dt>Color:</dt>');

    l_dl.append('<dd>' + diamond['Color'].toUpperCase() + '</dd>');

  }

  if (diamond['Clarity']) {

	  l_dl.append('<dt>Clarity:</dt>');

	  l_dl.append('<dd>' + diamond['Clarity'].toUpperCase() + '</dd>');

  }

  if (diamond['Cut']) {

	  l_dl.append('<dt>Cut:</dt>');

	  l_dl.append('<dd>' + diamond['Cut'].toUpperCase() + '</dd>');

  }

  if (diamond['Polish']){

    l_dl.append('<dt>POLISH:</dt>');

    l_dl.append('<dd>' + diamond['Polish'].toUpperCase() + '</dd>');

  }

  if (diamond['Symmetry']){

	  l_dl.append('<dt>SYMMETRY:</dt>');

	  l_dl.append('<dd>' + diamond['Symmetry'] + '</dd>');

  }

  if (diamond['Fluorescence']){

	  l_dl.append('<dt>FLUORESCENCE:</dt>');

	  l_dl.append('<dd>' + diamond['Fluorescence'].toUpperCase() + '</dd>');

  }

  if (diamond['Measurements']){

	  l_dl.append('<dt>MEASUREMENTS:</dt>');

	  l_dl.append('<dd>' + format_mesurement(diamond['Measurements']) + '</dd>');

  }

  if (diamond['length_width_ratio']){

	  l_dl.append('<dt>L:W Ratio:</dt>');

	  l_dl.append('<dd>' + diamond['length_width_ratio'] + '</dd>');

  }

  

  

  var r_dl = jQuery('#diamond_info_panel > div').eq(0).children('div').eq(1).children('dl').eq(0);

   

  if (diamond['Depth']){

	    r_dl.append('<dt>DEPTH:</dt>');

	    r_dl.append('<dd>' + diamond['Depth'] + '%</dd>');

	  }

  if (diamond['Table']){

	    r_dl.append('<dt>TABLE:</dt>');

	    r_dl.append('<dd>' + diamond['Table'] + '%</dd>');

	  }

 

  if (diamond['Girdle']){

	    r_dl.append('<dt>GIRDLE:</dt>');

	    r_dl.append('<dd>' + diamond[''].toUpperCase() + '</dd>');

	  }

  if (diamond['Culet']) {

	  r_dl.append('<dt>CULET:</dt>');

	  r_dl.append('<dd>' + diamond['Culet'].toUpperCase() + '</dd>');

  }

  if (diamond['Report']) {

	  r_dl.append('<dt>Report:</dt>');

	  r_dl.append('<dd>' + diamond['Report'].toUpperCase() + '</dd>');

  }

  if (diamond['Rap Percent'] && show_rapper){

    r_dl.append('<dt>RAP %:</dt>');

    r_dl.append('<dd>' + diamond['Rap Percent'] + '</dd>');

  }

  /*if (diamond['Price'] > 1000){

  	r_dl.append('<dt>BANK WIRE PRICE:</dt>');

	  r_dl.append('<dd>' + bank_wire_price + '</dd>');

	}*/

  if (diamond['Price']){

	  r_dl.append('<dt>PRICE:</dt>');

    if(is_cfp)

    	r_dl.append('<dd>CALL</dd>');

    else

    	r_dl.append('<dd>' + dtl.symbol + number_with_commas(diamond['Price']) + '</dd>');

  }

  if (diamond['Availability'] && showcolumn_availability){

	    r_dl.append('<dt class="last">AVAIL:</dt>');

	    r_dl.append('<dd class="last">' + diamond['Availability'].toUpperCase() + '</dd>');

	  }

  if (diamond['Collection']) {

    r_dl.append('<dt>Collection:</dt>');

    r_dl.append('<dd>' + diamond['Collection'] + '</dd>');

  }

  if (diamond['Supplier'] == 'Washington Diamonds') {

    r_dl.append('<dt>MADE IN THE USA</dt>');

  }



  /*var b_dl = jQuery('#diamond_info_panel > div').eq(1).children('div').eq(0).children('dl').eq(0);

  b_dl.append('<dt class="shipping-info">SHIPPING INFORMATION</dt>');

  b_dl.append('<dt>ORDER LOOSE BY:</dt>');

  b_dl.append('<dd>' + diamond['orderby'].toUpperCase() + '</dd>');

  b_dl.append('<dt>RECEIVE LOOSE BY:</dt>');

  b_dl.append('<dd>' + diamond['receiveby'].toUpperCase() + '</dd>');*/



  jQuery('#diamond_info_panel').show();

};



function init_comparson(){

  //var url = "/loose-diamonds/comparison-diamonds/";

  //var url = "comparison.diamond.json.txt";

  var url = host_url+"diamond-search/ajax/listcompare/";



  var request_params = dtl.build_request_params();

  if (jQuery.cookie('comparison_diamonds')!=undefined){

    //comparison_ids = JSON.parse(jQuery.cookie('comparison_diamonds'));

      lastAjax = jQuery.get(url, request_params, function(data) {

      init_fill_comparison_table(data);

    }, 'json');

  }

}



function init_fill_comparison_table(data){

    comparison_diamonds = data['diamonds'];

    comparison_ids = data['diamonds_id'];

    for (var i = comparison_ids.length - 1; i >= 0; i--) {

      jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[0]).removeClass('icons-checkbox').addClass('icons-checked');

      jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[1]).removeClass('icons-checkbox').addClass('icons-checked');

    };// init the checkbox

    jQuery('#comparison_totalResult').html(String(comparison_diamonds.length));

    fill_comparison_table();

}



function fill_comparison_table(){

    if (jQuery.cookie('comparison_diamonds')!=undefined){

      if (comparison_ids != []){

        jQuery('#comparison_totalResult').html(String(comparison_diamonds.length));

        fill_recently_viewed_table('comparison_table', comparison_diamonds);

      }

    }

    else if(jQuery.cookie('comparison_diamonds')==undefined){

      comparison_diamonds = [];

      comparison_ids = [];

      jQuery.cookie('comparison_diamonds', convert_array_to_str(comparison_ids), { expires: 365, path: '/',});

    }

  dtl.update_cmp_scrollbar();

};



function save_compared(e){

  var checkbox = jQuery(e).find('i')[0];



  if (checkbox.className == 'icons-checkbox'){

    jQuery(checkbox).removeClass('icons-checkbox').addClass('icons-checked');

    setCookie('comparison_diamonds', e);

  }

  else{

    jQuery(checkbox).removeClass('icons-checked').addClass('icons-checkbox');
    
    // delete_cookie()

    delCookie('comparison_diamonds', e);

  }

};





function delCookie(name, e){

  var parent_table = (jQuery(e).parents('.viewport'));

  var table_id = (parent_table.find('table'))[0].id;

  var index = jQuery(e).parent().parent().children().eq(0).attr("value");



  if (table_id == 'search_result_table'){

    value = dtl.diamonds[index];

    if (dtl.diamonds.length < 5 && is_similar_show == 'Y') {

      value = dtl.similar[index];

    }

  }

  else if(table_id == 'recently_viewed_table'){

    value = recently_viewed_diamonds[index];

  }

  else if(table_id == 'comparison_table'){

    value = comparison_diamonds[index];

  }



  jQuery((jQuery('[data-id='+ value['id'] +']').find('i'))[0]).removeClass('icons-checked').addClass('icons-checkbox');

  jQuery((jQuery('[data-id='+ value['id'] +']').find('i'))[1]).removeClass('icons-checked').addClass('icons-checkbox');





  for(var i = 0;i<comparison_ids.length;i++){

    if(value['id'] == comparison_ids[i]){

      comparison_ids.splice(i, 1);

      break;

    }

  };

  for (var i = comparison_diamonds.length - 1; i >= 0; i--) {

    if(comparison_diamonds[i]['id'] == value['id']){

      comparison_diamonds.splice(i, 1);

      break;

    }

  };

  jQuery.cookie(name, convert_array_to_str(comparison_ids), { expires: 365, path: '/',});

  fill_comparison_table();

}



function setCookie(name, e){

  var parent_table = (jQuery(e).parents('.viewport'));

  var table_id = (parent_table.find('table'))[0].id;

  var index = jQuery(e).parent().parent().children().eq(0).attr("value");



  if (table_id == 'search_result_table'){

    value = dtl.diamonds[index];

    if (dtl.diamonds.length < 5 && is_similar_show == 'Y') {

      value = dtl.similar[index];

    }

  }

  else if(table_id == 'recently_viewed_table'){

    value = recently_viewed_diamonds[index];

  }



  for (var i = comparison_ids.length - 1; i >= 0; i--) {

    jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[0]).removeClass('icons-checkbox').addClass('icons-checked');

    jQuery((jQuery('[data-id='+ comparison_ids[i] +']').find('i'))[1]).removeClass('icons-checkbox').addClass('icons-checked');

  };// init the checkbox



  for(var i = 0;i<comparison_ids.length;i++){

    if(value['id'] == comparison_ids[i]){

      return false;

    }

  }

  comparison_diamonds.push(value);

  comparison_ids.push(value['id']);

  jQuery.cookie(name, convert_array_to_str(comparison_ids), { expires: 365, path: '/',});

  fill_comparison_table();

}



function convert_array_to_str(data){

  var result = String(data[0]);

  if (data.length >= 1){

    for (var i = 1; i < data.length; i++) {

      result += String('-' + data[i]);

    };

  }

  else if (data.length == 0){

    result = String(data[0]);

  }

  return result;

}



function new_change_order_by(order_by, e){

  var choose_item = (jQuery(e))[0];

  var table_name = ((jQuery(e).parents('table')))[0].id;

  if (order_by == ''){

    return false;

  }

  if (table_name == 'search_recently_header_table'){

    for (var i = 0; i < dtl.order_by_list.length; i ++) {

      jQuery('#r_order_by_' + dtl.order_by_list[i].replace(':', '_')).removeClass('icons-chevron-up');

      jQuery('#r_order_by_' + dtl.order_by_list[i].replace(':', '_')).removeClass('icons-chevron-down');

      jQuery('#r_order_by_' + dtl.order_by_list[i].replace(':', '_')).addClass('icons-chevron-down');

    }

    if (r_order_mothod == ''){

      r_order_mothod = 'asc';

    }

    else if (r_order_mothod == 'asc'){

      r_order_mothod = 'desc';

      jQuery(choose_item).removeClass('icons-chevron-down').addClass('icons-chevron-up');

    }

    else if (r_order_mothod == 'desc'){

      r_order_mothod = 'asc';

      jQuery(choose_item).removeClass('icons-chevron-up').addClass('icons-chevron-down');

    }

    order_method = r_order_mothod;

  }

  else if (table_name == 'search_comparison_header_table'){

    for (var i = 0; i < dtl.order_by_list.length; i ++) {

      jQuery('#c_order_by_' + dtl.order_by_list[i].replace(':', '_')).removeClass('icons-chevron-up');

      jQuery('#c_order_by_' + dtl.order_by_list[i].replace(':', '_')).removeClass('icons-chevron-down');

      jQuery('#c_order_by_' + dtl.order_by_list[i].replace(':', '_')).addClass('icons-chevron-down');

    }

    if (c_order_mothod == ''){

      c_order_mothod = 'asc';

    }

    else if (c_order_mothod == 'asc'){

      c_order_mothod = 'desc';

      jQuery(choose_item).removeClass('icons-chevron-down').addClass('icons-chevron-up');

    }

    else if (c_order_mothod == 'desc'){

      c_order_mothod = 'asc';

      jQuery(choose_item).removeClass('icons-chevron-up').addClass('icons-chevron-down');

    }

    order_method = c_order_mothod;

  }

  dtl.show_loading();

  jQuery.ajax({

            //url: "/loose-diamonds/sort-rc-diamonds/",

			url: host_url+"diamond-search/ajax/sortrc/",

            type: "POST",

            data: {'order_by': order_by,'order_method': order_method, 'table_name': table_name, 'comparison_diamonds': comparison_ids, 'is_fancy': (jQuery(".active_slider").hasClass("fancycolor") ? 1 : 0)},

            dataType: 'json',

            success: function(result) {

              if (table_name == 'search_recently_header_table'){

                recently_viewed_diamonds = result['diamonds'];

                recently_viewed_totalcount = result['total_count'];

                jQuery('#recently_viewed_totalResult').html(recently_viewed_totalcount);

                fill_recently_viewed_table('recently_viewed_table', recently_viewed_diamonds);

                dtl.hide_loading();

              }

              else {

                comparison_ids = result['diamonds_id'];

                comparison_totalResult = result['total_count'];

                comparison_diamonds = result['diamonds'];

                jQuery('#comparison_totalResult').html(comparison_totalResult);

                fill_recently_viewed_table('comparison_table', comparison_diamonds);

                dtl.hide_loading();

              }

            }

        });

}

