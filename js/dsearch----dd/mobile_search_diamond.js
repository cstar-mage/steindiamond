/*dtl.fetch_result = function() {};
dtl.re_fetch_result = function() {};
*/
function change_view_to_list() {
  jQuery('.search-diamonds > div.your-search-results').css('display', 'block');
  jQuery('#mobile-reset-order-bar').css('display', 'block');
  jQuery(".search-diamonds > .search-diamonds-panel").css('display', 'none');
  jQuery(".search-diamonds > .advanced-search-wrapper").css('display', 'none');
  jQuery(".search-diamonds > .mobile-diamond-search-button").css('display', 'none');
  jQuery(".search-diamonds > div.your-search-results").removeClass('table-responsive');
}

function get_search_path() {
	//var url = "/loose-diamonds/list/";
	var url = ajax_list_url+"diamond-search/ajax/list/";
	
  if (dtl.on_page == 'Lab Created Diamonds') {
    //url = "/lab-diamonds/list/";
    url = ajax_list_url+"diamond-search/ajax/list/";
  } else if (dtl.on_page == 'CYO Earrings') {
    url = ajax_list_url+"loose-diamonds/list-pair/";
  } else if (dtl.on_page == 'CYO Ring' && dtl.diamond_category.startsWith('LAB')) {
    //url = "/lab-diamonds/list/";
    url = ajax_list_url+"diamond-search/ajax/list/";
  }

  return url;
}

function mobile_search_diamond() {
  var request_params = dtl.build_request_params();

  if (dtl.on_page.startsWith('CYO')) {
    request_params['cyo'] = dtl.on_page;
  }
  request_params['is_mobile'] = true; //Just for nginx cache to distinguish from desktop version.

  jQuery("#mobile-ajax-loading").css("display", "block");

  // save request params to cookie with path.
  //jQuery.cookie('search_params'+jQuery.trim(dtl.symbol), JSON.stringify(request_params), {path: search_path});
	jQuery.cookie('search_params'+jQuery.trim(dtl.symbol), JSON.stringify(request_params), {path: '/'});

  var url = get_search_path();

  if (dtl.lastAjax) {
    dtl.lastAjax.abort();
  }

  if (mobile_diamond_version == 'new') request_params['version'] = 'new';

  jQuery.ajax({
    url: url,
    data: request_params,
    cache: true,
    success: function(data) {
      jQuery('div#listContainer').html('');
      jQuery("#mobile-ajax-loading").css("display", "none");
      change_view_to_list();
      jQuery('div#listContainer').append(data);

      //jQuery('#totalResult').html(jQuery('.freshTotalOnPage').val() + ' ' + dtl.diamond_category);
      jQuery('#totalResult').html(jQuery('.freshTotalOnPage').val() + ' DIAMONDS FOUND');

      window.scrollTo(0, 0);
      push_ee_data_to_dataLayer();
    },
    error: function(data) {
      jQuery('#paramsPage').css('opacity', 1);
    },
    complete: function() {
      jQuery('#searching-diamond-loader').css('display', 'none');
      jQuery('.search-diamonds > div.your-search-results').css('display', 'block');
    }
  });
}

function getPairsWithout(pairs, exclude) {
  var new_qs = [];
  for (var index = 0; index < pairs.length; index ++) {
    var keyValuePair = pairs[index].split('=');
    var key = keyValuePair[0];
    var value = keyValuePair[1];
    if (jQuery.inArray(key, exclude) > -1) {
      continue;
    } else {
      new_qs[index] = key + '=' + value;
    }
  }

  return new_qs;
}

function showMoreDiamonds(params) {
  jQuery(".mobile-ajax-loading").css('display', 'block');
  jQuery('.loading_icon').css('display', 'block');
  var url = get_search_path();
  var qsComponents = params['formerUrl'].split(/&amp;/g);
  var new_qs = getPairsWithout(qsComponents, ['row', 'requestedDataSize', 'page', 'show_more', '']);
  new_qs.push('row=' + params['row']);
  new_qs.push('requestedDataSize=' + params['requestedDataSize']);
  new_qs.push('show_more=' + params['show_more']);
  var url_with_params = url + new_qs.join("&");

  //console.log(url + " == " + url_with_params);
  ///diamond-search/ajax/list/ == /diamond-search/ajax/list/?shapes=All&cuts&row=10&requestedDataSize=10&show_more=True	  
  
  nextPageLock = false;
  jQuery.ajax({
    url: url_with_params,
    success: function(data) {
      jQuery(".mobile-ajax-loading").remove();
      jQuery('.loading_icon').css('display', 'none');
      jQuery('div#listContainer').append(data);
      nextPageLock = true;
      push_ee_data_to_dataLayer();
    },
    error: function() {
      jQuery('#showMore').css('display', 'block');
    },
    complete: function() {
      jQuery('#showMoreLoader').css('display', 'none');
      show = true;
    }
  });
}

function sortDiamonds(params){
  jQuery(".mobile-ajax-loading").css('display', 'block');

  var url = get_search_path();

  jQuery('div#listContainer').css('opacity', 0.5);

  var request_params = dtl.build_request_params();
  request_params['row'] = '0';
  request_params['requestedDataSize'] = '10';
  request_params['order_by'] = params['order_by'];
  request_params['order_method'] = params['order_method'];
  request_params['version'] = params['version'];

  if (dtl.on_page.startsWith('CYO')) {
    request_params['cyo'] = dtl.on_page;
  }
  request_params['is_mobile'] = true; //Just for nginx cache to distinguish from desktop version.

  jQuery.ajax({
    url: url,
    data: request_params,
    success: function(data) {
      jQuery(".mobile-ajax-loading").remove();
      jQuery('div#listContainer').html(data);
      jQuery("#mobile-reset-order-bar button div.pull-left").html(params['text']);
      push_ee_data_to_dataLayer();
    },
    complete: function() {
      jQuery(".mobile-ajax-loading").css('display', 'none');
      jQuery('div#listContainer').css('opacity', 1);
    }
  });
}

function push_ee_data_to_dataLayer() {
  var product_ee_data_list = [];
  jQuery(".ee-diamond").each(function (idx, elem) {
    var e = jQuery(elem);
    var position = idx + 1;
    var product_ee_data = e.data('ee-data');
    product_ee_data['list'] = "Listing pages";
    product_ee_data['position'] = position;
    product_ee_data_list.push(product_ee_data);
  });
  if (product_ee_data_list.length) {
    var data_obj = {event: "ListingPageAjax", ecommerce: {impressions: product_ee_data_list}};
    dataLayer.push(data_obj);
  }
}

jQuery(document).on("click",".ee-diamond a", function() {
  var e = jQuery(this).parents('.ee-diamond');
  var ee_data = e.data('ee-data');
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
