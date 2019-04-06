var dtl = {};
dtl.symbol = '$';
dtl.currency = 1;
dtl.diamond_category = 'CONFLICT FREE DIAMONDS';
dtl.order_by = '';
dtl.order_method = '';
dtl.order_by_list = ['shape', 'carat', 'color', 'clarity', 'cut', 'collection', 'l:w', 'report', 'origin', 'price'];

dtl.on_page = '';
dtl.view_link = '';
dtl.search_path = '';
dtl.last_fetch = 0;
dtl.lastAjax = null;

dtl.row = 0;
dtl.row_pointer = 0;
dtl.data_size = 100;
dtl.page_size = 20;
dtl.total_count = 0;
dtl.diamonds = [];
dtl.similar = [];
dtl.is_pair = false;
dtl.viewparams = "";

diamondClickEEData = {};

dtl.orders = {
  'shape': ['Round', 'Princess', 'Asscher', 'Cushion', 'Radiant', 'Emerald', 'Oval', 'Pear', 'Marquise', 'Heart'],
  'color': ['J', 'I', 'H', 'G', 'F', 'E', 'D'],
  'clarity': ['SI2', 'SI1', 'VS2', 'VS1', 'VVS2', 'VVS1', 'IF', 'FL'],
  'cut': ['Fair', 'Good', 'Very Good', 'Ideal', 'Super Ideal'],
  'report': ['CDL', 'EGL', 'IGI', 'Gemscan', 'GIA', 'AGS']
};

dtl.header = '0';
dtl.header_widths = {
  '0': ['13%', '10%', '10%', '10%', '13%', '0%', '0%', '11%', '13%', '11%', '9%'],
  'c': ['12%', '8%', '8%', '9%', '12%', '12%', '0%', '9%', '11%', '10%', '8%'],
  'lw': ['12%', '9%', '9%', '10%', '12%', '0%', '7%', '10%', '12%', '10%', '8%'],
  'c_lw': ['11%', '8%', '8%', '9%', '10%', '12%', '4%', '9%', '11%', '9%', '10%']
};

dtl.init = function(on_page, symbol, currency, diamond_category, row, order_by, order_method, data_size,
  view_link, search_path) {
	
  // alert(search_path);	
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
  if (jQuery('#collection_div').find('i.icons-checked').length > 0) {
    dtl.header = 'c';
  } else {
    dtl.header = '0';
  }

  jQuery('#search_result_header_table').find('th').each(function(index, el) {
    jQuery(el).attr('width', dtl.header_widths[dtl.header][index]);
  });

  jQuery('#search_result_header_table').find('th').show();
  jQuery('#search_result_header_table').find('th[width="0%"]').hide();
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
    }else{
      jQuery.cookie('search_params'+jQuery.trim(dtl.symbol), JSON.stringify(request_params), {path: search_path});
    }

    //var url = "/loose-diamonds/list/";
    var url = "/diamond-search/ajax/list/";
    var category_ee = 'Loose Diamonds';

    if (dtl.on_page == 'Lab Created Diamonds') {
      url = "/lab-diamonds/list/";
      category_ee = 'Lab Created Colorless Diamonds';
    } else if (dtl.on_page == 'CYO Ring' && dtl.diamond_category.startsWith('LAB')) {
      url = "/lab-diamonds/list/";
      category_ee = 'Lab Created Colorless Diamonds';
    }

    //alert(url);
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
       // alert(result);
  
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
    polishes: jQuery('#polishes').val(),
    symmetries: jQuery('#symmetries').val(),
    fluorescences: jQuery('#fluorescences').val(),
    min_carat: jQuery('#min_carat').val(),
    max_carat: jQuery('#max_carat').val(),
    min_table: 0,
    max_table: 100,
    min_depth: jQuery('#min_depth').val(),
    max_depth: jQuery('#max_depth').val(),   
    min_price: jQuery('#min_price').val(),
    max_price: jQuery('#max_price').val(),
    stock_number: jQuery('#stock_number').val(),
    shipping_day:jQuery('#stock_number').val(),
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

  request_params.min_ratio = jQuery('#min_ratio').val();
  request_params.max_ratio = jQuery('#max_ratio').val();

 /* if (jQuery('select[name=shipping_day]').val() !== '') {
    request_params.shipping_day = jQuery('select[name=shipping_day]').val();
  }*/

  if (MIN_PRICE !== "") { request_params.MIN_PRICE = MIN_PRICE; }
  if (MAX_PRICE !== "") { request_params.MAX_PRICE = MAX_PRICE; }
  if (MIN_CARAT !== "") { request_params.MIN_CARAT = MIN_CARAT; }
  if (MAX_CARAT !== "") { request_params.MAX_CARAT = MAX_CARAT; }
  if (MIN_TABLE !== "") { request_params.MIN_TABLE = MIN_TABLE; }
  if (MAX_TABLE !== "") { request_params.MAX_TABLE = MAX_TABLE; }
  if (MIN_DEPTH !== "") { request_params.MIN_DEPTH = MIN_DEPTH; }
  if (MAX_DEPTH !== "") { request_params.MAX_DEPTH = MAX_DEPTH; }


  if (is_mobile) {request_params.requestedDataSize = 10;}

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
    if (dtl.diamond_category.startsWith('LAB CREATED DIAMOND')){
      dtl.diamond_category = 'LAB CREATED DIAMOND';
    }else{
      dtl.diamond_category = 'CONFLICT FREE DIAMOND';
    }
  }else{
    if (dtl.diamond_category.startsWith('LAB CREATED DIAMOND')){
      dtl.diamond_category = 'LAB CREATED DIAMONDS';
    }else{
      dtl.diamond_category = 'CONFLICT FREE DIAMONDS';
    }
  }
  jQuery('#totalResult').html(dtl.total_count + ' ' + dtl.diamond_category);
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
      diamond_link = dtl.view_link + diamond.id + '/';
    }
    
    var tr = jQuery('<tr></tr>');
    tr.append('<input class="index" type="hidden" value="' + i + '" />');
    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['shape'] + '</td>');
    if(diamond['carat'])
    {
    	tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['carat'].toFixed(2) + '</td>');
    }	
    else
    {
    	tr.append('<td width="9%" scope="col" style="text-align: center;"></td>');
    }	
   
    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['color'] + '</td>');
    tr.append('<td width="10%" scope="col" style="text-align: center;">' + diamond['clarity'] + '</td>');
    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['cut'] + '</td>');
    tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['collection'] + '</td>');
    tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['length_width_ratio'] + '</td>');
    tr.append('<td width="11%" scope="col" style="text-align: center;">' + diamond['report'] + '</td>');
    tr.append('<td width="14%" scope="col" style="text-align: center;">' + diamond['origin'] + '</td>');
    tr.append('<td width="12%" scope="col" style="text-align: center;">' + (is_cfp ? "CALL" : dtl.symbol + number_with_commas(diamond['Price']))  + '</td>');
    //tr.append('<td width="12%" scope="col" style="text-align: center;">' + dtl.symbol + number_with_commas(diamond['price']) + '</td>');
    tr.append('<td width="9%" scope="col"><a href="' + diamond_link + '" class="view">View</a></td>');

    var tds = tr.children('td');
    for (var j = 0; j < tds.length; j ++) {
      jQuery(tds[j]).attr('width', dtl.header_widths[dtl.header][j]);
    }
    tr.children('td[width="0%"]').hide();

    jQuery('#search_result_table tbody').append(tr);
  }

  jQuery('#search_result_table tbody tr td').unbind('hover').unbind('click');
  jQuery('#search_result_table tbody tr td').bind(is_touchable ? 'click' : 'hover', function() {
    dtl.show_diamond_info(jQuery(this).parent('tr'), false);
  });

  if (is_touchable) {
    jQuery('.col-md-3 > table.search-results-outline').parent('.col-md-3').addClass('hidden-sm').addClass('hidden-xs');
    var all_view_links = jQuery('#search_result_table tbody tr td a.view');
    all_view_links.attr('onclick', 'javascript:return false;').data('clicked', false);
    jQuery('#search_result_table tbody tr td').bind(is_touchable ? 'click' : 'hover', function() {
      var target = jQuery(event.target);
      if (target.is('a.view')) {
        if (target.data('clicked') || (get_width() < 992)) {
          location.href = target.attr('href');
        } else {
          all_view_links.data('clicked', false);
          target.data('clicked', true);
        }
      }
    });
  }

};

dtl.fill_diamonds_pair_table = function() {
  jQuery('#search_result_table tbody').html('');

  var max_pointer = dtl.row_pointer + dtl.page_size > dtl.diamonds.length ? dtl.diamonds.length : dtl.row_pointer + dtl.page_size;
  for (var i = dtl.row_pointer; i < max_pointer; i ++) {
    var diamond = dtl.diamonds[i];
    var tr = jQuery('<tr></tr>');
    tr.append('<input class="index" type="hidden" value="' + i + '" />');
    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['first']['shape'] + '<br />' + diamond['second']['shape'] + '</td>');
    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['first']['carat'].toFixed(2) + '<br />' + diamond['second']['carat'].toFixed(2) + '</td>');
    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['first']['color'] + '<br />' + diamond['second']['color'] + '</td>');
    tr.append('<td width="10%" scope="col" style="text-align: center;">' + diamond['first']['clarity'] + '<br />' + diamond['second']['clarity'] + '</td>');
    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['first']['cut'] + '<br />' + diamond['second']['cut'] + '</td>');
    tr.append('<td width="11%" scope="col" style="text-align: center;">' + diamond['first']['report'] + '<br />' + diamond['second']['report'] + '</td>');
    tr.append('<td width="14%" scope="col" style="text-align: center;">' + diamond['first']['origin'] + '<br />' + diamond['second']['origin'] + '</td>');
    tr.append('<td width="12%" scope="col" style="text-align: center;">' + (is_cfp ? "CALL" : dtl.symbol + number_with_commas(diamond['Price']))  + '</td>');
    //tr.append('<td width="12%" scope="col" style="text-align: center;">' + dtl.symbol + number_with_commas(diamond['price']) + '</td>');
    tr.append('<td width="12%" scope="col"><a href="' + dtl.view_link + diamond['first']['id'] + '/' + diamond['second']['id'] + '/?sid=' + sid + '&first=&show_diamond_tab=true" class="view">View</a></td>');
    jQuery('#search_result_table tbody').append(tr);
  }

  jQuery('#search_result_table tbody tr td').unbind('hover').unbind('click');
  jQuery('#search_result_table tbody tr td').bind(is_touchable ? 'click' : 'hover', function() {
    dtl.show_diamond_info(jQuery(this).parent('tr'), false);
  });

  if (is_touchable) {
    jQuery('.col-md-3 > table.search-results-outline').parent('.col-md-3').addClass('hidden-sm').addClass('hidden-xs');
    var all_view_links = jQuery('#search_result_table tbody tr td a.view');
    all_view_links.attr('onclick', 'javascript:return false;').data('clicked', false);
    jQuery('#search_result_table tbody tr td').bind(is_touchable ? 'click' : 'hover', function() {
      var target = jQuery(event.target);
      if (target.is('a.view')) {
        if (target.data('clicked') || (get_width() < 992)) {
          location.href = target.attr('href');
        } else {
          all_view_links.data('clicked', false);
          target.data('clicked', true);
        }
      }
    });
  }
};

dtl.fill_similar_table = function() {
  jQuery('#search_result_table tbody').html('');

  for (var i = 0; i < dtl.similar.length; i ++) {
    var diamond = dtl.similar[i];
    var diamond_link = '';
    if (dtl.on_page == 'CYO Ring') {
      diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=' + first_tab + '&show_diamond_tab=true';
    } else if (dtl.on_page == 'CYO Pendant'){
      diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&show_diamond_tab=true';
    } else {
      diamond_link = dtl.view_link + diamond.id + '/';
    }

    var tr = jQuery('<tr></tr>');
    tr.append('<input class="index" type="hidden" value="' + i + '" />');
    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['shape'] + '</td>');
    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['carat'].toFixed(2) + '</td>');
    tr.append('<td width="9%" scope="col" style="text-align: center;">' + diamond['color'] + '</td>');
    tr.append('<td width="10%" scope="col" style="text-align: center;">' + diamond['clarity'] + '</td>');
    tr.append('<td width="12%" scope="col" style="text-align: center;">' + diamond['cut'] + '</td>');
    tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['collection'] + '</td>');
    tr.append('<td width="0%" scope="col" style="text-align: center;">' + diamond['length_width_ratio'] + '</td>');
    tr.append('<td width="11%" scope="col" style="text-align: center;">' + diamond['report'] + '</td>');
    tr.append('<td width="14%" scope="col" style="text-align: center;">' + diamond['origin'] + '</td>');
    
    tr.append('<td width="12%" scope="col" style="text-align: center;">' + (is_cfp ? "CALL" : dtl.symbol + number_with_commas(diamond['Price']))  + '</td>');
    //tr.append('<td width="12%" scope="col" style="text-align: center;">' + dtl.symbol + number_with_commas(diamond['price']) + '</td>');
    tr.append('<td width="9%" scope="col"><a href="' + diamond_link + '" class="view">View</a></td>');

    var tds = tr.children('td');
    for (var j = 0; j < tds.length; j ++) {
      jQuery(tds[j]).attr('width', dtl.header_widths[dtl.header][j]);
    }
    tr.children('td[width="0%"]').hide();

    jQuery('#search_result_table tbody').append(tr);
  }

  jQuery('#search_result_table tbody tr td').unbind('hover').unbind('click');
  jQuery('#search_result_table tbody tr td').bind(is_touchable ? 'click' : 'hover', function() {
    dtl.show_diamond_info(jQuery(this).parent('tr'), true);
  });

  if (is_touchable) {
    jQuery('.col-md-3 > table.search-results-outline').parent('.col-md-3').addClass('hidden-sm').addClass('hidden-xs');
    var all_view_links = jQuery('#search_result_table tbody tr td a.view');
    all_view_links.attr('onclick', 'javascript:return false;').data('clicked', false);
    jQuery('#search_result_table tbody tr td').bind(is_touchable ? 'click' : 'hover', function() {
      var target = jQuery(event.target);
      if (target.is('a.view')) {
        if (target.data('clicked') || (get_width() < 992)) {
          location.href = target.attr('href');
        } else {
          all_view_links.data('clicked', false);
          target.data('clicked', true);
        }
      }
    });
  }
};

dtl.update_table = function(_row) {
  dtl.row_pointer = _row - dtl.row;
  dtl.fill_table();
};

dtl.update_scrollbar = function() {
  
  var not_init = jQuery.trim(jQuery("#minimal-vertical").html()) === '';
  if (not_init) {
    dtl.recreate_scrollbar();
  } else {
    jQuery("#minimal-vertical").val(dtl.row + dtl.row_pointer);
  }
};

dtl.recreate_scrollbar = function() {
  var max_range = dtl.total_count - dtl.page_size > 0 ? dtl.total_count - dtl.page_size : 0;
  if (max_range !== 0) {
    jQuery('.scrollbar').removeClass('hidden');
    if (jQuery(jQuery("#minimal-vertical").html()) === '') {
      jQuery("#minimal-vertical").noUiSlider({
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
      jQuery("#minimal-vertical").noUiSlider({
        range: [0, max_range - 1],
        start: dtl.row + dtl.row_pointer,
      }, true);
    }
  } else {
    jQuery('.scrollbar').addClass('hidden');
  }
};

dtl.show_diamond_info = function(target, similar_flag) {
  if (dtl.on_page == 'CYO Earrings') {
    dtl.show_diamond_pair_info(target);
  } else {
    dtl.show_diamond_single_info(target, similar_flag);
  }
};

dtl.show_diamond_single_info = function(target, similar_flag) {
  var diamond = null;
  if (!similar_flag) {
    diamond = dtl.diamonds[target.children('.index').val()];
  } else {
    diamond = dtl.similar[target.children('.index').val()];
  }

  var bank_wire_price = '';
  if (diamond['price'] > 1000){
    var f_x = parseFloat((diamond['price'] * 0.985).toFixed(2));
    bank_wire_price =  dtl.symbol  + number_with_commas(Math.round(Math.round(f_x)/5)*5);
  }
  var diamond_link = '';
  if (dtl.on_page == 'CYO Ring') {
    diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=' + first_tab + '&show_diamond_tab=true';
  } else if (dtl.on_page == 'CYO Pendant'){
    diamond_link = dtl.view_link + diamond.id + '/?sid=' + sid + '&first=&show_diamond_tab=true';
  }else {
    diamond_link = dtl.view_link + diamond.id + '/';
  }
  jQuery('.no-info').hide();
  jQuery('#diamond_info_panel').html(
    '<a class="btn btn-lg btn-success" href='+ diamond_link +' >' +
    'view diamond' +
    '</a>' +
    '<div class="row cs-row">' +
      '<div class="col-md-6 col-sm-6">' +
        '<dl class="mb10">' +
        '</dl>' +
      '</div>' +
      '<div class="col-md-6 col-sm-6">' +
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
  if (is_touchable) {
    // Add a button at bottom of info panel for pad horizontal.
    jQuery('#diamond_info_panel').append('<a class="btn btn-lg btn-success visible-md" href='+ diamond_link +' >' + 'view diamond</a>');
  }

  var l_dl = jQuery('#diamond_info_panel > div').eq(0).children('div').eq(0).children('dl').eq(0);
  if (diamond['upc']){
    l_dl.append('<dt>STOCK NUMBER:</dt>');
    l_dl.append('<dd>' + diamond['upc'] + '</dd>');
  }
  if (diamond['shape']){
    l_dl.append('<dt>SHAPE:</dt>');
    l_dl.append('<dd>' + diamond['shape'].toUpperCase() + '</dd>');
  }
  if (diamond['carat']){
    l_dl.append('<dt>CARAT WEIGHT:</dt>');
    if(diamond['carat'])
    { l_dl.append('<dd>' + diamond['carat'].toFixed(2) + '</dd>'); }
    else
    {l_dl.append('<dd></dd>');}	
    
  }
  if (diamond['color']) {
    l_dl.append('<dt>Color:</dt>');
    l_dl.append('<dd>' + diamond['color'].toUpperCase() + '</dd>');
  }
  if (diamond['report']) {
    l_dl.append('<dt>Report:</dt>');
    l_dl.append('<dd>' + diamond['report'].toUpperCase() + '</dd>');
  }
  if (diamond['polish']){
    l_dl.append('<dt>POLISH:</dt>');
    l_dl.append('<dd>' + diamond['polish'].toUpperCase() + '</dd>');
  }
  if (diamond['depth']){
    l_dl.append('<dt>DEPTH:</dt>');
    l_dl.append('<dd>' + diamond['depth'] + '%</dd>');
  }
  if (diamond['girdle']){
    l_dl.append('<dt>GIRDLE:</dt>');
    l_dl.append('<dd>' + diamond['girdle'].toUpperCase() + '</dd>');
  }
  if (diamond['fluorescence']){
    l_dl.append('<dt>FLUORESCENCE:</dt>');
    l_dl.append('<dd>' + diamond['fluorescence'].toUpperCase() + '</dd>');
  }
  if (diamond['length_width_ratio']){
    l_dl.append('<dt>L:W Ratio:</dt>');
    l_dl.append('<dd>' + diamond['length_width_ratio'] + '</dd>');
  }

  var r_dl = jQuery('#diamond_info_panel > div').eq(0).children('div').eq(1).children('dl').eq(0);
  if (diamond['price']){
    r_dl.append('<dt>PRICE:</dt>');
    if(is_cfp)
    	r_dl.append('<dd>CALL</dd>');
    else
    	r_dl.append('<dd>' + dtl.symbol + number_with_commas(diamond['price']) + '</dd>');
  }
  if (diamond['price'] > 1000){
    r_dl.append('<dt style="width:106px">BANK WIRE PRICE:</dt>');
    r_dl.append('<dd>' + bank_wire_price + '</dd>');
  }
  if (diamond['cut']) {
    r_dl.append('<dt>Cut:</dt>');
    r_dl.append('<dd>' + diamond['cut'].toUpperCase() + '</dd>');
  }
  if (diamond['clarity']) {
    r_dl.append('<dt>Clarity:</dt>');
    r_dl.append('<dd>' + diamond['clarity'].toUpperCase() + '</dd>');
  }
  if (diamond['measurements']){
    r_dl.append('<dt>MEASUREMENTS:</dt>');
    r_dl.append('<dd>' + diamond['measurements'] + '</dd>');
  }
  if (diamond['symmetry']){
    r_dl.append('<dt>SYMMETRY:</dt>');
    r_dl.append('<dd>' + diamond['symmetry'] + '</dd>');
  }
  if (diamond['table']){
    r_dl.append('<dt>TABLE:</dt>');
    r_dl.append('<dd>' + diamond['table'] + '%</dd>');
  }

  if (diamond['culet']) {
    r_dl.append('<dt>CULET:</dt>');
    r_dl.append('<dd>' + diamond['culet'].toUpperCase() + '</dd>');
  }

  if (diamond['collection']) {
    r_dl.append('<dt>Collection:</dt>');
    r_dl.append('<dd>' + diamond['collection'] + '</dd>');
  }

  var b_dl = jQuery('#diamond_info_panel > div').eq(1).children('div').eq(0).children('dl').eq(0);
  b_dl.append('<dt class="shipping-info">SHIPPING INFORMATION</dt>');
  b_dl.append('<dt>ORDER LOOSE BY:</dt>');
  b_dl.append('<dd>' + diamond['orderby'].toUpperCase() + '</dd>');
  b_dl.append('<dt>RECEIVE LOOSE BY:</dt>');
  b_dl.append('<dd>' + diamond['receiveby'].toUpperCase() + '</dd>');

  jQuery('#diamond_info_panel').show();
};

dtl.show_diamond_pair_info = function(target) {
  var diamond = dtl.diamonds[target.children('.index').val()];

  var bank_wire_price = '';
  if (diamond['price'] > 1000){
    var f_x = parseFloat((diamond['price'] * 0.985).toFixed(2));
    bank_wire_price = dtl.symbol + number_with_commas(Math.round(Math.round(f_x)/5)*5);
  }

  var diamond_link = '';
  if (dtl.on_page == 'CYO Earrings') {
    diamond_link = dtl.view_link + diamond['first']['id'] + '/' + diamond['second']['id'] + '/?sid=' + sid + '&first=&show_diamond_tab=true';
  }
  jQuery('.no-info').hide();
  jQuery('#diamond_info_panel').html(
    '<a  class="btn btn-lg btn-success" href='+ diamond_link +' >' +
    'view diamonds' +
    '</a>' +
    '<div class="row cs-row">' +
      '<div class="col-md-6 col-sm-6">' +
        '<dl class="mb10">' +
        '</dl>' +
      '</div>' +
      '<div class="col-md-6 col-sm-6">' +
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
  if (is_touchable) {
    // Add a button at bottom of info panel for pad horizontal.
    jQuery('#diamond_info_panel').append('<a class="btn btn-lg btn-success visible-md" href='+ diamond_link +' >' + 'view diamond</a>');
  }

  var l_dl = jQuery('#diamond_info_panel > div').eq(0).children('div').eq(0).children('dl').eq(0);
  l_dl.append('<dt>STOCK NUMBER:</dt>');
  l_dl.append('<dd style="width:100px">' + diamond['first']['upc'] + ' / ' + diamond['second']['upc'] + '</dd>');

  l_dl.append('<dt>SHAPE:</dt>');
  l_dl.append('<dd>' + diamond['first']['shape'] + ' / ' + diamond['second']['shape'].toUpperCase() + '</dd>');
  l_dl.append('<dt>CARAT WEIGHT:</dt>');
  l_dl.append('<dd>' + diamond['first']['carat'].toFixed(2) + ' / ' + diamond['second']['carat'].toFixed(2) + '</dd>');
  l_dl.append('<dt>Color:</dt>');
  l_dl.append('<dd>' + diamond['first']['color'] + ' / ' + diamond['second']['color'] +'</dd>');
  l_dl.append('<dt>Cut:</dt>');
  l_dl.append('<dd>' + diamond['first']['cut'] + ' / ' + diamond['second']['cut'] + '</dd>');
  if (diamond['first']['polish']){
    l_dl.append('<dt>POLISH:</dt>');
    l_dl.append('<dd>' + diamond['first']['polish'] + ' / ' + diamond['second']['polish'] + '</dd>');
  }
  if (diamond['first']['depth']){
    l_dl.append('<dt>DEPTH:</dt>');
    l_dl.append('<dd>' + diamond['first']['depth'] + '% / ' + diamond['second']['depth'] + '%</dd>');
  }
  if (diamond['first']['girdle']){
    l_dl.append('<dt>GIRDLE:</dt>');
    l_dl.append('<dd>' + diamond['first']['girdle'] + ' / ' + diamond['second']['girdle'] + '</dd>');
  }
  if(diamond['first']['fluorescence']){
    l_dl.append('<dt>FLUORESCENCE:</dt>');
    l_dl.append('<dd>' + diamond['first']['fluorescence'] + ' / ' + diamond['second']['fluorescence'] + '</dd>');
  }
  if (diamond['first']['length_width_ratio']){
    l_dl.append('<dt>L:W Ratio:</dt>');
    l_dl.append('<dd>' + diamond['first']['length_width_ratio'] + ' / ' + diamond['second']['length_width_ratio'] + '</dd>');
  }

  var r_dl = jQuery('#diamond_info_panel > div').eq(0).children('div').eq(1).children('dl').eq(0);
  r_dl.append('<dt>PRICE:</dt>');
  if(is_cfp)
  	r_dl.append('<dd>CALL</dd>');
  else
	  r_dl.append('<dd>' + dtl.symbol + number_with_commas(diamond['price']) + '</dd>');
  if (diamond['first']['price'] > 1000 || diamond['second']['price'] > 1000){
    r_dl.append('<dt>BANK WIRE PRICE:</dt>');
    r_dl.append('<dd>' + bank_wire_price + '</dd>');
  }
  r_dl.append('<dt>Clarity:</dt>');
  r_dl.append('<dd>' + diamond['first']['clarity'] + ' / ' + diamond['second']['clarity'] + '</dd>');
  r_dl.append('<dt>Report:</dt>');
  r_dl.append('<dd>' + diamond['first']['report'] + ' / ' + diamond['second']['report'] + '</dd>');
  r_dl.append('<dt>MEASUREMENTS:</dt>');
  r_dl.append('<dd>' + diamond['first']['measurements'] + ' / ' + diamond['second']['measurements'] + '</dd>');
  if (diamond['first']['symmetry']){
    r_dl.append('<dt>SYMMETRY:</dt>');
    r_dl.append('<dd>' + diamond['first']['symmetry'] + ' / ' + diamond['second']['symmetry'] + '</dd>');
  }
  if (diamond['first']['table']){
    r_dl.append('<dt>TABLE:</dt>');
    r_dl.append('<dd>' + diamond['first']['table'] + '% / ' + diamond['second']['table'] + '%</dd>');
  }
  if (diamond['first']['culet']){
    r_dl.append('<dt>CULET:</dt>');
    r_dl.append('<dd>' + diamond['first']['culet'] + ' / ' + diamond['second']['culet'] + '</dd>');
  }
  if (diamond['first']['collection']){
    r_dl.append('<dt>Collection:</dt>');
    r_dl.append('<dd>' + diamond['first']['collection'] + ' / ' + diamond['second']['collection'] + '</dd>');
  }

  var b_dl = jQuery('#diamond_info_panel > div').eq(1).children('div').eq(0).children('dl').eq(0);
  b_dl.append('<dt class="shipping-info">SHIPPING INFORMATION</dt>');
  b_dl.append('<dt>ORDER LOOSE BY:</dt>');
  b_dl.append('<dd>' + diamond['first']['orderby'].toUpperCase() + '</dd>');
  b_dl.append('<dt>RECEIVE LOOSE BY:</dt>');
  b_dl.append('<dd>' + diamond['first']['receiveby'].toUpperCase() + '</dd>');

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
  reset_search_panel();
  dtl.row = 0;
  dtl.row_pointer = 0;
  dtl.order_by = 'price';
  dtl.order_method = 'asc';
  dtl.update_order_by();
  dtl.fetch_result();
};

dtl.view_similar = function() {
  jQuery('#original_bar').hide();
  jQuery('#similar_bar').show();
  jQuery('.search-nothing-tips').hide();
  jQuery('#listContainer').removeClass('hidden');
  jQuery('#search_result_table').removeClass('hidden');
  dtl.fill_similar_table();
};

dtl.view_original = function() {
  jQuery('#similar_bar').hide();
  jQuery('#original_bar').show();
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


jQuery(document).on("click", "a.view", function() {
  var re = /\/\d+\//;
  var id = re.exec(jQuery(this).prop('href'))[0].replace('/', '').replace('/', '');
  var ee_data = diamondClickEEData[id];
  if (ee_data) {
    var data_obj = {
      'event': 'productClick',
      'ecommerce': {
        'click': {
          'actionField': {'list': 'Listing pages'},
          'products': [ee_data]
        }
      }
    };
    dataLayer.push(data_obj);
  }
})
