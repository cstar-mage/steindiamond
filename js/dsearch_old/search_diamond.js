/*var DIAMOND_SLIDER_ATTRIBUTES = {
  "cuts": ["EXCELLENT", "VERY GOOD", "GOOD", "FAIR"],
  "colors": ["D", "E", "F", "G", "H", "I", "J", "K", "L-Z"],
  "clarities": ["FL", "IF", "VVS1", "VVS2", "VS1", "VS2", "SI1", "SI2", "SI3", "I1"],
  "fluorescences": ["VERY STRONG", "STRONG", "MEDIUM", "FAINT", "NONE"]
};

var CACHE = {
  'waiting_response': false,
  'request_params': null
};

var lastAjax = null;
var end_scroll_top = false;*/

function check_shape(_shapes, shape_ranges) {
  var valid = false;
  var shapes = _shapes.split(',');
  for (var i = 0; i < shapes.length; i ++) {
    if (shapes[i] in shape_ranges) {
      valid = true;
      break;
    }
  }

  return valid;
}

function toggle_select(target) {
  var $select = jQuery(target);
  var shape = jQuery(target);
  if (jQuery('#shapes').val() == shape.data('shape')) {
  // only one shape left
    return false;
  }

  if ($select.attr('data-action') == 'Unselect') {
    $select.attr('data-action', 'Select');
  }
  else {
    $select.attr('data-action', 'Unselect');
  }
}

function toggle_shape(target) {
  var shape = jQuery(target);
  if (jQuery('#shapes').val() == shape.data('shape')) {  // only one shape left
    return false;
  }
	//if(shape.data('shape') == "All") alert("ALL");
  shape.toggleClass('active');
  var shapes = [];
  shape.parent().children('.active').each(function() {
    shapes.push(jQuery(this).data('shape'));
  });
  jQuery('#shapes').val(shapes.join(','));

  var price_range_min = 10000000;
  var price_range_max = 0;
  for (var i = 0; i < shapes.length; i ++) {
    if (shape_ranges[shapes[i]][0] < price_range_min) {
      price_range_min = shape_ranges[shapes[i]][0];
    }
    if (shape_ranges[shapes[i]][1] > price_range_max) {
      price_range_max = shape_ranges[shapes[i]][1];
    }
  }

  MIN_PRICE = price_range_min;
  MAX_PRICE = price_range_max !== 0 ? price_range_max : 10000000;

  jQuery('#price_slider').noUiSlider({
    range: [MIN_PRICE, MAX_PRICE]
  }, true);

  var shadow_min_percent = calc_shadow_price_percent(MIN_PRICE, MAX_PRICE, jQuery('#price_slider').val()[0]);
  var shadow_max_percent = calc_shadow_price_percent(MIN_PRICE, MAX_PRICE, jQuery('#price_slider').val()[1]);
  jQuery('#shadow_price_slider').val([shadow_min_percent, shadow_max_percent]);

  var carat_range_min = 10000000;
  var carat_range_max = 0;
  for (var j = 0; j < shapes.length; j ++) {
    if (shape_ranges[shapes[j]][2] < carat_range_min) {
      carat_range_min = shape_ranges[shapes[j]][2];
    }
    if (shape_ranges[shapes[j]][3] > carat_range_max) {
      carat_range_max = shape_ranges[shapes[j]][3];
    }
  }

  MIN_CARAT = carat_range_min;
  MAX_CARAT = carat_range_max !== 0 ? carat_range_max : 20;

  jQuery('#carat_slider').noUiSlider({
    range: [MIN_CARAT, MAX_CARAT]
  }, true);

  jQuery('#carat_slider > div').append('<div class="ui-slider-left-cap"></div>');
  r_percent = 100 - (jQuery('#carat_slider').val()[0] - MIN_CARAT) * 1.00 / (MAX_CARAT - MIN_CARAT) * 100;
  jQuery('#carat_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#carat_slider > div').append('<div class="ui-slider-right-cap"></div>');
  l_percent = 100 - (MAX_CARAT - jQuery('#carat_slider').val()[1]) * 1.00 / (MAX_CARAT - MIN_CARAT) * 100;
  jQuery('#carat_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});

  dtl.re_fetch_result();
}

function init_shapes(_shapes) {
  // prevent old cookie bug
  //alert(_shapes);	 
  if (!check_shape(_shapes, shape_ranges)) {
    _shapes = 'All'; //change shape from Round ANIL
  }
  jQuery('#shapes').val(_shapes);

  jQuery('.product-shape li').click(function() {
    if(jQuery(this).data("shape") == "All")
	{
		if(jQuery(this).hasClass('active'))
		{return;}
		jQuery('.product-shape li').each(function(){
			jQuery(this).removeClass('active');
			jQuery(this).attr('data-action', 'Select');
			//toggle_shape(this);
			//toggle_select(this);
		});
		toggle_shape(this);
	    toggle_select(this);
		//toggle_all_shape(this);
	    //toggle_all_select(this);
	}
	else
	{
		jQuery('.product-shape li.all-details').removeClass('active');
		jQuery('.product-shape li.all-details').attr('data-action', 'Select');
		toggle_shape(this);
	    toggle_select(this);
	}
  });

  var shapes = _shapes.split(',');
  jQuery('.product-shape li').removeClass('active');
  jQuery('.product-shape li').each(function() {
    if (shapes.indexOf(jQuery(this).data('shape')) != -1) {
      jQuery(this).addClass('active');
      jQuery(this).attr('data-action', 'Unselect');
    }
    else{
      jQuery(this).attr('data-action', 'Select');
    }
  });

  var price_range_min = 10000000;
  var price_range_max = 0;
  for (var i = 0; i < shapes.length; i ++) {
    if (shapes[i] in shape_ranges) {
      if (shape_ranges[shapes[i]][0] < price_range_min) {
        price_range_min = shape_ranges[shapes[i]][0];
      }
      if (shape_ranges[shapes[i]][1] > price_range_max) {
        price_range_max = shape_ranges[shapes[i]][1];
      }
    }
  }
  MIN_PRICE = price_range_min;
  MAX_PRICE = price_range_max !== 0 ? price_range_max : 10000000;

  var carat_range_min = 10000000;
  var carat_range_max = 0;
  for (var j = 0; j < shapes.length; j ++) {
    if (shapes[j] in shape_ranges) {
      if (shape_ranges[shapes[j]][2] < carat_range_min) {
        carat_range_min = shape_ranges[shapes[j]][2];
      }
      if (shape_ranges[shapes[j]][3] > carat_range_max) {
        carat_range_max = shape_ranges[shapes[j]][3];
      }
    }
  }
  MIN_CARAT = carat_range_min;
  MAX_CARAT = carat_range_max !== 0 ? carat_range_max : 20;
}

function calc_shadow_price_percent(_min, _max, _current) {
  var _middle = 10000;
  if (_max <= 20000) {
    _middle = (_min + _max) / 2;
  }

  if (_current <= _middle) {
    return parseInt((_current - _min) * 1.0 / (_middle - _min) * 5000, 10);
  } else {
    return parseInt(Math.log((((_current - _middle) * 1.0 / (_max - _middle)) * (Math.E - 1)) + 1) * 5000 + 5000, 10);
  }
}

function calc_shadow_price_from_percent(_min, _max, _percent) {
  var _middle = 10000;
  if (_max <= 20000) {
    _middle = (_min + _max) / 2;
  }

  if (_percent <= 5000) {
    return parseInt(_percent * 1.0 / 5000 * (_middle - _min) + _min, 10);
  } else {
    return parseInt((Math.exp((_percent - 5000) * 1.0 / 5000) - 1) / (Math.E - 1) * (_max - _middle) + _middle, 10);
  }
}

function set_price_display(amount) {
  var _minPrice = jQuery(this).val()[0];
  jQuery('#min_price_display').val(curr_symbol + _minPrice);

  var _maxPrice = jQuery(this).val()[1];
  jQuery('#max_price_display').val(curr_symbol + _maxPrice);

  jQuery('#min_price').blur(function(event) {
    delay_run(function() {
      jQuery('#price_slider').trigger('change');
    }, {gid: 'pschange'});
  });

  jQuery('#max_price').blur(function(event) {
    delay_run(function() {
      jQuery('#price_slider').trigger('change');
    }, {gid: 'pschange'});
  });

}

function set_shadow_price(amount) {
  var _minPrice = jQuery(this).val()[0];
  var _maxPrice = jQuery(this).val()[1];

  var r_percent = 100 - _minPrice * 1.00 / 10000 * 100;
  jQuery('#shadow_price_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  var l_percent = 100 - (10000 - _maxPrice) * 1.00 / 10000 * 100;
  jQuery('#shadow_price_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
}

function init_price_range(_minPrice, _maxPrice) {
  if (_minPrice) {
    _minPrice = parseInt(_minPrice, 10);
  }

  if (_maxPrice) {
    _maxPrice = parseInt(_maxPrice, 10);
  }

  if (MIN_PRICE === "") { MIN_PRICE = _minPrice; }
  if (MAX_PRICE === "") { MAX_PRICE = _maxPrice; }

  // set price range
  if (_minPrice && _minPrice < MIN_PRICE) {
    _minPrice = MIN_PRICE;
    if (_minPrice >= _maxPrice) {
      _maxPrice = _minPrice;
    }
  }
  if (_maxPrice && _maxPrice > MAX_PRICE) {
    _maxPrice = MAX_PRICE;
    if (_maxPrice <= _minPrice) {
      _minPrice = _maxPrice;
    }
  }

  jQuery('#price_slider').noUiSlider({
    range: [MIN_PRICE, MAX_PRICE],
    start: [_minPrice, _maxPrice],
    connect: true,
    serialization: {
      resolution: 1,
      to: [[jQuery('#min_price'), set_price_display], [jQuery('#max_price'), set_price_display]]
    },
    set: function() {
      var shadow_min_percent = calc_shadow_price_percent(MIN_PRICE, MAX_PRICE, jQuery(this).val()[0]);
      var shadow_max_percent = calc_shadow_price_percent(MIN_PRICE, MAX_PRICE, jQuery(this).val()[1]);
      jQuery('#shadow_price_slider').val([shadow_min_percent, shadow_max_percent]);
      dtl.re_fetch_result();
    }
  });

  // add curr_symbol before price
  jQuery('#min_price_display').focus(function(event) {
    jQuery('#min_price_display').addClass('hidden');
    jQuery('#min_price').removeClass('hidden');
    jQuery('#min_price').focus();
  });

  jQuery('#min_price').blur(function(event) {
    jQuery('#min_price').addClass('hidden');
    jQuery('#min_price_display').removeClass('hidden');
  });

  jQuery('#max_price_display').focus(function(event) {
    jQuery('#max_price_display').addClass('hidden');
    jQuery('#max_price').removeClass('hidden');
    jQuery('#max_price').focus();
  });

  jQuery('#max_price').blur(function(event) {
    jQuery('#max_price').addClass('hidden');
    jQuery('#max_price_display').removeClass('hidden');
  });

  var shadow_min_percent = calc_shadow_price_percent(MIN_PRICE, MAX_PRICE, _minPrice);
  var shadow_max_percent = calc_shadow_price_percent(MIN_PRICE, MAX_PRICE, _maxPrice);
  jQuery('#shadow_price_slider').noUiSlider({
    range: [0, 10000],
    start: [shadow_min_percent, shadow_max_percent],
    connect: true,
    serialization: {
      resolution: 1,
      to: [[set_shadow_price], [set_shadow_price]]
    },
    slide: function() {
      var _minPrice = calc_shadow_price_from_percent(MIN_PRICE, MAX_PRICE, jQuery(this).val()[0]);
      var _maxPrice = calc_shadow_price_from_percent(MIN_PRICE, MAX_PRICE, jQuery(this).val()[1]);
      jQuery('#price_slider').val([_minPrice, _maxPrice]);
    },
    set: function() {
      var _minPrice = calc_shadow_price_from_percent(MIN_PRICE, MAX_PRICE, jQuery(this).val()[0]);
      var _maxPrice = calc_shadow_price_from_percent(MIN_PRICE, MAX_PRICE, jQuery(this).val()[1]);
      jQuery('#price_slider').val([_minPrice, _maxPrice]);
      dtl.re_fetch_result();
    }
  }).change(function() {
    delay_run(function() {
      jQuery('#price_slider').trigger('change');
    }, {gid: 'pschange'});
  });

  jQuery('#shadow_price_slider > div').append('<div class="ui-slider-left-cap"></div>');
  r_percent = (10000 - shadow_min_percent) * 1.0 / 100;
  jQuery('#shadow_price_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#shadow_price_slider > div').append('<div class="ui-slider-right-cap"></div>');
  l_percent = shadow_max_percent * 1.0 / 100;
  jQuery('#shadow_price_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
}

function set_carat_display(amount) {
  var _minCarat = jQuery(this).val()[0];
  var _maxCarat = jQuery(this).val()[1];
  var r_percent = 100 - (_minCarat - MIN_CARAT) * 1.00 / (MAX_CARAT - MIN_CARAT) * 100;
  jQuery('#carat_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  var l_percent = 100 - (MAX_CARAT - _maxCarat) * 1.00 / (MAX_CARAT - MIN_CARAT) * 100;
  jQuery('#carat_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});


  jQuery('#min_carat').blur(function(event) {
    delay_run(function() {
        jQuery('#carat_slider').trigger('change');
    }, {gid: 'cschange'});
  });

  jQuery('#max_carat').blur(function(event) {
    delay_run(function() {
        jQuery('#carat_slider').trigger('change');
    }, {gid: 'cschange'});
  });
}

function init_carat_range(_minCarat, _maxCarat) {
  if (_minCarat) {
    _minCarat = _minCarat * 1.0;
  }

  if (_maxCarat) {
    _maxCarat = _maxCarat * 1.0;
  }

  if (MIN_CARAT === "") { MIN_CARAT = _minCarat; }
  if (MAX_CARAT === "") { MAX_CARAT = _maxCarat; }

  if (_minCarat && _minCarat < MIN_CARAT) {
    _minCarat = MIN_CARAT;
    if (_minCarat >= _maxCarat) {
      _maxCarat = _minCarat;
    }
  }
  if (_maxCarat && _maxCarat > MAX_CARAT) {
    _maxCarat = MAX_CARAT;
    if (_maxCarat <= _minCarat) {
      _minCarat = _maxCarat;
    }
  }

  jQuery('#carat_slider').noUiSlider({
    range: [MIN_CARAT, MAX_CARAT],
    start: [_minCarat, _maxCarat],
    connect: true,
    serialization: {
      resolution: 0.01,
      to: [[jQuery('#min_carat'), set_carat_display], [jQuery('#max_carat'), set_carat_display]]
    },
    set: dtl.re_fetch_result
  }).change(dtl.re_fetch_result);

  jQuery('#carat_slider > div').append('<div class="ui-slider-left-cap"></div>');
  var r_percent = 100 - (_minCarat - MIN_CARAT) * 1.00 / (MAX_CARAT - MIN_CARAT) * 100;
  jQuery('#carat_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#carat_slider > div').append('<div class="ui-slider-right-cap"></div>');
  var l_percent = 100 - (MAX_CARAT - _maxCarat) * 1.00 / (MAX_CARAT - MIN_CARAT) * 100;
  jQuery('#carat_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
}

function set_cuts_value(amount) {
  var l = jQuery(this).val()[0];
  var r = jQuery(this).val()[1];
  jQuery('#cuts').val(DIAMOND_SLIDER_ATTRIBUTES.cuts.slice(l, r).join());

  var r_percent = 100 - l * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.cuts.length * 100;
  jQuery('#cut_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.cuts.length - r) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.cuts.length * 100;
  jQuery('#cut_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
}

function init_cuts_range(_cuts) {
  var cuts = _cuts.split(",");
  var _minCut = 0, _maxCut = DIAMOND_SLIDER_ATTRIBUTES.cuts.length;
  if (cuts && cuts[0] !== '') {
    _minCut = DIAMOND_SLIDER_ATTRIBUTES.cuts.indexOf(cuts[0]);
    _maxCut = DIAMOND_SLIDER_ATTRIBUTES.cuts.indexOf(cuts[cuts.length-1]) + 1;
  }
  jQuery('#cut_slider').noUiSlider({
    range: [0, DIAMOND_SLIDER_ATTRIBUTES.cuts.length],
    start: [_minCut, _maxCut],
    connect: true,
    margin: 1,
    step: 1,
    serialization: {
      resolution: 1,
      to: [set_cuts_value, set_cuts_value],
    },
    set: function() {
      jQuery('#adv_bar_cut').children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery('#adv_bar_cut').parent().attr('data-action', 'Unselect');
      dtl.re_fetch_result();
    }
  }).change(function() {
    jQuery('#adv_bar_cut').children('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_cut').parent().attr('data-action', 'Unselect');
    dtl.re_fetch_result();
  });


  jQuery('#cut_slider > div').append('<div class="ui-slider-left-cap"></div>');
  var r_percent = 100 - _minCut * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.cuts.length * 100;
  jQuery('#cut_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#cut_slider > div').append('<div class="ui-slider-right-cap"></div>');
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.cuts.length - _maxCut) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.cuts.length * 100;
  jQuery('#cut_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
  
    var cut_slider_fore = '<ol class="ui-slider-scale">';
  	for(var i=1; i<DIAMOND_SLIDER_ATTRIBUTES.cuts.length; i++)
  		cut_slider_fore += '<li class="fore'+i+'"></li>';
	cut_slider_fore += '</ol>';
  jQuery('#cut_slider > div').append(cut_slider_fore);
  //jQuery('#cut_slider > div').append('<ol class="ui-slider-scale"><li class="fore1"></li><li class="fore2"></li><li class="fore3"></li></ol>');
  
 
  jQuery('#adv_bar_cut').click(function(event) {
    jQuery('#cut_slider').val([0, DIAMOND_SLIDER_ATTRIBUTES.cuts.length]);

    if (jQuery(this).children('i').hasClass('icons-checkbox')) {
      jQuery(this).children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery(this).parent().attr('data-action', 'Unselect');
      show_adv_bar();
    } else {
      jQuery(this).children('i').removeClass('icons-checked').addClass('icons-checkbox');
      jQuery(this).parent().attr('data-action', 'Select');
    }
    dtl.re_fetch_result();
  });
}


/*function init_ratio_range(_minRatio, _maxRatio) {
  var MIN_RATIO = 1.00;
  var MAX_RATIO = 2.75;

  if (_minRatio === '' || _minRatio == '0.00') { _minRatio = MIN_RATIO; }
  if (_maxRatio === '' || _maxRatio == '0.00') { _maxRatio = MAX_RATIO; }

  jQuery('#ratio_slider').noUiSlider({
    range: [MIN_RATIO, MAX_RATIO],
    start: [_minRatio, _maxRatio],
    connect: true,
    serialization: {
      resolution: 0.01,
      to: [[jQuery('#min_ratio'), set_ratio_display], [jQuery('#max_ratio'), set_ratio_display]]
    },
    set: function() {
      jQuery('#adv_bar_ratio').children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery('#adv_bar_ratio').parent().attr('data-action', 'Unselect');
      dtl.re_fetch_result();
    }
  }).change(function() {
    jQuery('#adv_bar_ratio').children('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_ratio').parent().attr('data-action', 'Unselect');
    dtl.re_fetch_result();
  });

  jQuery('#ratio_slider > div').append('<div class="ui-slider-left-cap"></div>');
  var r_percent = 100 - (_minRatio - MIN_RATIO) * 1.00 / (MAX_RATIO - MIN_RATIO) * 100;
  jQuery('#ratio_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#ratio_slider > div').append('<div class="ui-slider-right-cap"></div>');
  var l_percent = 100 - (MAX_RATIO - _maxRatio) * 1.00 / (MAX_RATIO - MIN_RATIO) * 100;
  jQuery('#ratio_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});

  jQuery('#adv_bar_ratio').click(function(event) {
    jQuery('#ratio_slider').val([MIN_RATIO, MAX_RATIO]);

    if (jQuery(this).children('i').hasClass('icons-checkbox')) {
      jQuery(this).children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery(this).parent().attr('data-action', 'Unselect');
      show_adv_bar();
    } else {
      jQuery(this).children('i').removeClass('icons-checked').addClass('icons-checkbox');
      jQuery(this).parent().attr('data-action', 'Select');
    }
    dtl.re_fetch_result();
  });
  
}*/

function set_colors_value(amount) {
  var l = jQuery(this).val()[0];
  var r = jQuery(this).val()[1];
  jQuery('#colors').val(DIAMOND_SLIDER_ATTRIBUTES.colors.slice(l, r).join());

  var r_percent = 100 - l * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.colors.length * 100;
  jQuery('#color_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.colors.length - r) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.colors.length * 100;
  jQuery('#color_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});

/** CHANGE for color dropdowns **/
	jQuery('#min_color').val(l);
	jQuery('#max_color').val(r-1);

	jQuery('#min_color').change(function(event) {
		delay_run(function() {set_colors_dropdown()}, {gid: 'cschange'});
	});
	
	jQuery('#max_color').change(function(event) {
		delay_run(function() {set_colors_dropdown()}, {gid: 'cschange'});
	});
/** CHANGE for color dropdowns ENDS **/
}

/** NEW for color dropdowns **/
function set_colors_dropdown() {
	var l = parseInt(jQuery('#min_color').val());
	var r = parseInt(jQuery('#max_color').val())+1;

	var r_percent = 100 - l * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.colors.length * 100;
	jQuery('#color_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
	var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.colors.length - r) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.colors.length * 100;
	jQuery('#color_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
	
	jQuery("#color_slider").val([l,r]);
	
	jQuery('#color_slider').trigger('change');
}
/** NEW for color dropdowns ENDS **/


function init_colors_range(_colors) {
  var colors = _colors.split(",");
  var _minColor = 0, _maxColor = DIAMOND_SLIDER_ATTRIBUTES.colors.length;
  if (colors && colors[0] !== '') {
    _minColor = DIAMOND_SLIDER_ATTRIBUTES.colors.indexOf(colors[0]);
    _maxColor = DIAMOND_SLIDER_ATTRIBUTES.colors.indexOf(colors[colors.length-1]) + 1;
  }
  jQuery('#color_slider').noUiSlider({
    range: [0, DIAMOND_SLIDER_ATTRIBUTES.colors.length],
    start: [_minColor, _maxColor],
    connect: true,
    margin: 1,
    step: 1,
    serialization: {
      resolution: 1,
      to: [set_colors_value, set_colors_value],
    }
  }).change(dtl.re_fetch_result);

  jQuery('#color_slider > div').append('<div class="ui-slider-left-cap"></div>');
  var r_percent = 100 - _minColor * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.colors.length * 100;
  jQuery('#color_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#color_slider > div').append('<div class="ui-slider-right-cap"></div>');
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.colors.length - _maxColor) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.colors.length * 100;
  jQuery('#color_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
  
  var color_slider_fore = '<ol class="ui-slider-scale">';
  	for(var i=1; i<DIAMOND_SLIDER_ATTRIBUTES.colors.length; i++)
  		color_slider_fore += '<li class="fore'+i+'"></li>';
	color_slider_fore += '</ol>';
  jQuery('#color_slider > div').append(color_slider_fore);
  //jQuery('#color_slider > div').append('<ol class="ui-slider-scale"><li class="fore1"></li><li class="fore2"></li><li class="fore3"></li><li class="fore4"></li><li class="fore5"></li><li class="fore6"></li><li class="fore7"></li><li class="fore8"></li></ol>');
}

//--------------------------------------

function set_fancycolors_value(amount) {
  var l = jQuery(this).val()[0];
  var r = jQuery(this).val()[1];
  jQuery('#fancycolors').val(DIAMOND_SLIDER_ATTRIBUTES.fancycolors.slice(l, r).join());

  var r_percent = 100 - l * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length * 100;
  jQuery('#fancycolor_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length - r) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length * 100;
  jQuery('#fancycolor_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});

/** CHANGE for fancycolor dropdowns **/
	jQuery('#min_fancycolor').val(l);
	jQuery('#max_fancycolor').val(r-1);

	jQuery('#min_fancycolor').change(function(event) {
		delay_run(function() {set_fancycolors_dropdown()}, {gid: 'cschange'});
	});
	
	jQuery('#max_fancycolor').change(function(event) {
		delay_run(function() {set_fancycolors_dropdown()}, {gid: 'cschange'});
	});
/** CHANGE for fancycolor dropdowns ENDS **/
}

/** NEW for fancycolor dropdowns **/
function set_fancycolors_dropdown() {
	var l = parseInt(jQuery('#min_fancycolor').val());
	var r = parseInt(jQuery('#max_fancycolor').val())+1;

	var r_percent = 100 - l * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length * 100;
	jQuery('#fancycolor_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
	var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length - r) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length * 100;
	jQuery('#fancycolor_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
	
	jQuery("#fancycolor_slider").val([l,r]);
	
	jQuery('#fancycolor_slider').trigger('change');
}
/** NEW for fancycolor dropdowns ENDS **/


function init_fancycolors_range(_fancycolors) {
  var fancycolors = _fancycolors.split(",");
  var _minColor = 0, _maxColor = DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length;
  if (fancycolors && fancycolors[0] !== '') {
    _minColor = DIAMOND_SLIDER_ATTRIBUTES.fancycolors.indexOf(fancycolors[0]);
    _maxColor = DIAMOND_SLIDER_ATTRIBUTES.fancycolors.indexOf(fancycolors[fancycolors.length-1]) + 1;
  }
  jQuery('#fancycolor_slider').noUiSlider({
    range: [0, DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length],
    start: [_minColor, _maxColor],
    connect: true,
    margin: 1,
    step: 1,
    serialization: {
      resolution: 1,
      to: [set_fancycolors_value, set_fancycolors_value],
    }
  }).change(dtl.re_fetch_result);

  jQuery('#fancycolor_slider > div').append('<div class="ui-slider-left-cap"></div>');
  var r_percent = 100 - _minColor * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length * 100;
  jQuery('#fancycolor_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#fancycolor_slider > div').append('<div class="ui-slider-right-cap"></div>');
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length - _maxColor) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length * 100;
  jQuery('#fancycolor_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
  
  var fancycolor_slider_fore = '<ol class="ui-slider-scale">';
  	for(var i=1; i<DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length; i++)
  		fancycolor_slider_fore += '<li class="fore'+i+'"></li>';
	fancycolor_slider_fore += '</ol>';
  jQuery('#fancycolor_slider > div').append(fancycolor_slider_fore);
  //jQuery('#fancycolor_slider > div').append('<ol class="ui-slider-scale"><li class="fore1"></li><li class="fore2"></li><li class="fore3"></li><li class="fore4"></li><li class="fore5"></li><li class="fore6"></li><li class="fore7"></li><li class="fore8"></li></ol>');
  
  //binding color_switcher link events
  jQuery(".color_switcher").on("click", function(){
			jQuery("#div_color").toggleClass("hidden");
			jQuery("#div_fancycolor").toggleClass("hidden");
			
			jQuery(".color_switcher").removeClass("active_slider");
			if(jQuery(this).hasClass("color")) jQuery(".fancycolor.color_switcher").addClass("active_slider");
			else jQuery(".color.color_switcher").addClass("active_slider");
			dtl.re_fetch_result();
	});
}

//-----------------END---------------------

function set_clarities_value(amount) {
  var l = jQuery(this).val()[0];
  var r = jQuery(this).val()[1];
  jQuery('#clarities').val(DIAMOND_SLIDER_ATTRIBUTES.clarities.slice(l, r).join());

  var r_percent = 100 - l * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.clarities.length * 100;
  jQuery('#clarity_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.clarities.length - r) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.clarities.length * 100;
  jQuery('#clarity_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
  
  /** CHANGE for clarity dropdowns **/
	jQuery('#min_clarity').val(l);
	jQuery('#max_clarity').val(r-1);

	jQuery('#min_clarity').change(function(event) {
		delay_run(function() {set_clarities_dropdown()}, {gid: 'cschange'});
	});
	
	jQuery('#max_clarity').change(function(event) {
		delay_run(function() {set_clarities_dropdown()}, {gid: 'cschange'});
	});
	/** CHANGE for clarity dropdowns ENDS **/
}
/** NEW for clarity dropdowns **/
function set_clarities_dropdown() {
	var l = parseInt(jQuery('#min_clarity').val());
	var r = parseInt(jQuery('#max_clarity').val())+1;

	var r_percent = 100 - l * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.clarities.length * 100;
	jQuery('#clarity_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
	var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.clarities.length - r) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.clarities.length * 100;
	jQuery('#clarity_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
	
	jQuery("#clarity_slider").val([l,r]);
	
	jQuery('#clarity_slider').trigger('change');
}
/** NEW for clarity dropdowns ENDS **/

function init_clarities_range(_clarities) {
  var clarities = _clarities.split(",");
  var _minClarity = 0, _maxClarity = DIAMOND_SLIDER_ATTRIBUTES.clarities.length;
  if (clarities && clarities[0] !== '') {
    _minClarity = DIAMOND_SLIDER_ATTRIBUTES.clarities.indexOf(clarities[0]);
    _maxClarity = DIAMOND_SLIDER_ATTRIBUTES.clarities.indexOf(clarities[clarities.length-1]) + 1;
  }
  jQuery('#clarity_slider').noUiSlider({
    range: [0, DIAMOND_SLIDER_ATTRIBUTES.clarities.length],
    start: [_minClarity, _maxClarity],
    connect: true,
    margin: 1,
    step: 1,
    serialization: {
      resolution: 1,
      to: [set_clarities_value, set_clarities_value],
    }
  }).change(dtl.re_fetch_result);

  jQuery('#clarity_slider > div').append('<div class="ui-slider-left-cap"></div>');
  var r_percent = 100 - _minClarity * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.clarities.length * 100;
  jQuery('#clarity_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#clarity_slider > div').append('<div class="ui-slider-right-cap"></div>');
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.clarities.length - _maxClarity) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.clarities.length * 100;
  jQuery('#clarity_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
  
  //jQuery('#clarity_slider > div').append('<ol class="ui-slider-scale"><li class="fore1"></li><li class="fore2"></li><li class="fore3"></li><li class="fore4"></li><li class="fore5"></li><li class="fore6"></li><li class="fore7"></li><li class="fore8"></li><li class="fore9"></li></ol>');
  var clarity_slider_fore = '<ol class="ui-slider-scale">';
  	for(var i=1; i<DIAMOND_SLIDER_ATTRIBUTES.clarities.length; i++)
  		clarity_slider_fore += '<li class="fore'+i+'"></li>';
	clarity_slider_fore += '</ol>';
  jQuery('#clarity_slider > div').append(clarity_slider_fore);
}

function set_fluorescences_value(amount) {
  var l = jQuery(this).val()[0];
  var r = jQuery(this).val()[1];
  jQuery('#fluorescences').val(DIAMOND_SLIDER_ATTRIBUTES.fluorescences.slice(l, r).join());

  var r_percent = 100 - l * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length * 100;
  jQuery('#fluorescence_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length - r) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length * 100;
  jQuery('#fluorescence_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
}

function init_fluorescences_range(_fluorescences) {
  var fluorescences = _fluorescences.split(",");
  var _minFluorescence = 0, _maxFluorescence = DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length;
  if (fluorescences && fluorescences[0] !== '') {
    _minFluorescence = DIAMOND_SLIDER_ATTRIBUTES.fluorescences.indexOf(fluorescences[0]);
    _maxFluorescence = DIAMOND_SLIDER_ATTRIBUTES.fluorescences.indexOf(fluorescences[fluorescences.length-1]) + 1;
  }
  jQuery('#fluorescence_slider').noUiSlider({
    range: [0, DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length],
    start: [_minFluorescence, _maxFluorescence],
    connect: true,
    margin: 1,
    step: 1,
    serialization: {
      resolution: 1,
      to: [set_fluorescences_value, set_fluorescences_value],
    },
    set: function() {
      jQuery('#adv_bar_fluorescence').children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery('#adv_bar_fluorescence').parent().attr('data-action', 'Unselect');
      dtl.re_fetch_result();
    }
  }).change(function() {
    jQuery('#adv_bar_fluorescence').children('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_fluorescence').parent().attr('data-action', 'Unselect');
    dtl.re_fetch_result();
  });
  
  jQuery('#fluorescence_slider > div').append('<div class="ui-slider-left-cap"></div>');
  var r_percent = 100 - _minFluorescence * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length * 100;
  jQuery('#fluorescence_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#fluorescence_slider > div').append('<div class="ui-slider-right-cap"></div>');
  var l_percent = 100 - (DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length - _maxFluorescence) * 1.00 / DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length * 100;
  jQuery('#fluorescence_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});
   
   var fluorescence_slider_fore = '<ol class="ui-slider-scale">';
  	for(var i=1; i<DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length; i++)
  		fluorescence_slider_fore += '<li class="fore'+i+'"></li>';
	fluorescence_slider_fore += '</ol>';
  jQuery('#fluorescence_slider > div').append(fluorescence_slider_fore);
	//jQuery('#fluorescence_slider > div').append('<ol class="ui-slider-scale"><li class="fore1"></li><li class="fore2"></li><li class="fore3"></li><li class="fore4"></li></ol>');
  
  jQuery('#adv_bar_fluorescence').click(function(event) {
    jQuery('#fluorescence_slider').val([0, DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length]);

    if (jQuery(this).children('i').hasClass('icons-checkbox')) {
      jQuery(this).children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery(this).parent().attr('data-action', 'Unselect');
      show_adv_bar();
    } else {
      jQuery(this).children('i').removeClass('icons-checked').addClass('icons-checkbox');
      jQuery(this).parent().attr('data-action', 'Select');
    }
    dtl.re_fetch_result();
  });
  
}


/*function init_ratio_range(_minRatio, _maxRatio) {
  var MIN_RATIO = 1.00;
  var MAX_RATIO = 2.75;

  if (_minRatio === '' || _minRatio == '0.00') { _minRatio = MIN_RATIO; }
  if (_maxRatio === '' || _maxRatio == '0.00') { _maxRatio = MAX_RATIO; }

  jQuery('#ratio_slider').noUiSlider({
    range: [MIN_RATIO, MAX_RATIO],
    start: [_minRatio, _maxRatio],
    connect: true,
    serialization: {
      resolution: 0.01,
      to: [[jQuery('#min_ratio'), set_ratio_display], [jQuery('#max_ratio'), set_ratio_display]]
    },
    set: function() {
      jQuery('#adv_bar_ratio').children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery('#adv_bar_ratio').parent().attr('data-action', 'Unselect');
      dtl.re_fetch_result();
    }
  }).change(function() {
    jQuery('#adv_bar_ratio').children('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_ratio').parent().attr('data-action', 'Unselect');
    dtl.re_fetch_result();
  });

  jQuery('#ratio_slider > div').append('<div class="ui-slider-left-cap"></div>');
  var r_percent = 100 - (_minRatio - MIN_RATIO) * 1.00 / (MAX_RATIO - MIN_RATIO) * 100;
  jQuery('#ratio_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#ratio_slider > div').append('<div class="ui-slider-right-cap"></div>');
  var l_percent = 100 - (MAX_RATIO - _maxRatio) * 1.00 / (MAX_RATIO - MIN_RATIO) * 100;
  jQuery('#ratio_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});

  jQuery('#adv_bar_ratio').click(function(event) {
    jQuery('#ratio_slider').val([MIN_RATIO, MAX_RATIO]);

    if (jQuery(this).children('i').hasClass('icons-checkbox')) {
      jQuery(this).children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery(this).parent().attr('data-action', 'Unselect');
      show_adv_bar();
    } else {
      jQuery(this).children('i').removeClass('icons-checked').addClass('icons-checkbox');
      jQuery(this).parent().attr('data-action', 'Select');
    }
    dtl.re_fetch_result();
  });
  
}*/

function init_collections(_collections) {
  for (i = 0; i < _collections.length; i ++) {
    jQuery('input[name=c_collection][value="' + _collections[i] + '"]').parent().find('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('input[name=c_collection][value="' + _collections[i] + '"]').parent().attr('data-action', 'Unselect');
  }

  var collections_div = jQuery('#collection_div');
  if (collections_div.find('.icons-checked').length === 0) {
    jQuery('#adv_bar_collection').find('i').removeClass('icons-checked').addClass('icons-checkbox');
    jQuery('#adv_bar_collection').parents('div:first').attr('data-action', 'Select');
  } else {
    jQuery('#adv_bar_collection').find('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_collection').parents('div:first').attr('data-action', 'Unselect');
  }

  bind_collections_events();
}

function bind_collections_events() {
  jQuery('input[name=c_collection]').parent().find('i').click(function(event) {
    change_collection(this);
    dtl.re_fetch_result();
  });

  jQuery('#adv_bar_collection').click(function(event) {
    var collections_div = jQuery('#collection_div');
    if (jQuery(this).children('i').hasClass('icons-checkbox')) {
      jQuery(this).children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery(this).parent().attr('data-action', 'Unselect');
      collections_div.find('i').removeClass('icons-checkbox').addClass('icons-checked');
      collections_div.find('div').attr('data-action', 'Unselect');
      show_adv_bar();
    } else {
      jQuery(this).children('i').removeClass('icons-checked').addClass('icons-checkbox');
      jQuery(this).parent().attr('data-action', 'Select');
      collections_div.find('i').removeClass('icons-checked').addClass('icons-checkbox');
      collections_div.find('div').attr('data-action', 'Select');
    }
    dtl.re_fetch_result();
  });
}

function change_collection(target) {
  var o = jQuery(target);
  var $select = jQuery(o.parents('div')[0]);

  if (o.hasClass('icons-checkbox')) {
    o.removeClass('icons-checkbox').addClass('icons-checked');
    $select.attr('data-action', 'Unselect');
  } else {
    o.addClass('icons-checkbox').removeClass('icons-checked');
    $select.attr('data-action', 'Select');
  }

  var collections_div = o.parent().parent('div').parents('div:first');
  if (collections_div.find('.icons-checked').length === 0) {
    jQuery('#adv_bar_collection').find('i').removeClass('icons-checked').addClass('icons-checkbox');
    jQuery('#adv_bar_collection').parents('div:first').attr('data-action', 'Select');
  }
  else {
    jQuery('#adv_bar_collection').find('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_collection').parents('div:first').attr('data-action', 'Unselect');
    if (o.parent().parent().children('input').val() == 'All') {
      if (o.hasClass('icons-checked')) {
        collections_div.find('i').removeClass('icons-checkbox').addClass('icons-checked');
        collections_div.find('div').attr('data-action', 'Unselect');
      }
    }
    else {
      if (o.hasClass('icons-checkbox')) {
        collections_div.find('input[value=All]').parent().find('i').removeClass('icons-checked').addClass('icons-checkbox');
        collections_div.find('input[value=All]').parents('div:first').attr('data-action', 'Select');
      }
    }
  }
}

function init_origins(_origins) {
  for (i = 0; i < _origins.length; i ++) {
    jQuery('input[name=c_origin][value="' + _origins[i] + '"]').parent().find('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('input[name=c_origin][value="' + _origins[i] + '"]').parent().attr('data-action', 'Unselect');
  }

  var origins_div = jQuery('#origin_div');
  if (origins_div.find('.icons-checked').length === 0) {
    jQuery('#adv_bar_origin').find('i').removeClass('icons-checked').addClass('icons-checkbox');
    jQuery('#adv_bar_origin').parents('div:first').attr('data-action', 'Select');
  } else {
    jQuery('#adv_bar_origin').find('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_origin').parents('div:first').attr('data-action', 'Unselect');
  }

  bind_origins_events();
}

function bind_origins_events() {
  jQuery('input[name=c_origin]').parent().find('i').click(function(event) {
    change_origin(this);
    dtl.re_fetch_result();
  });

  jQuery('#adv_bar_origin').click(function(event) {
    var origins_div = jQuery('#origin_div');
    if (jQuery(this).children('i').hasClass('icons-checkbox')) {
      jQuery(this).children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery(this).parent().attr('data-action', 'Unselect');
      origins_div.find('i').removeClass('icons-checkbox').addClass('icons-checked');
      origins_div.find('div').attr('data-action', 'Unselect')
      show_adv_bar();
    } else {
      jQuery(this).children('i').removeClass('icons-checked').addClass('icons-checkbox');
      jQuery(this).parent().attr('data-action', 'Select');
      origins_div.find('i').removeClass('icons-checked').addClass('icons-checkbox');
      origins_div.find('div').attr('data-action', 'Select');
    }
    dtl.re_fetch_result();
  });
}

function change_origin(target) {
  var o = jQuery(target);
  var $select = jQuery(o.parents('div')[0]);

  if (o.hasClass('icons-checkbox')) {
    o.removeClass('icons-checkbox').addClass('icons-checked');
    $select.attr('data-action', 'Unselect');
  } else {
    o.addClass('icons-checkbox').removeClass('icons-checked');
    $select.attr('data-action', 'Select');
  }

  var origins_div = o.parent().parent('div').parents('div:first');
  if (origins_div.find('.icons-checked').length === 0) {
    jQuery('#adv_bar_origin').find('i').removeClass('icons-checked').addClass('icons-checkbox');
    jQuery('#adv_bar_origin').find('div:first').attr('data-action', 'Select');
  } else {
    jQuery('#adv_bar_origin').find('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_origin').find('div:first').attr('data-action', 'Unselect');
  }
}

function init_reports(_reports) {
  for (i = 0; i < _reports.length; i ++) {
    jQuery('input[name=c_report][value="' + _reports[i] + '"]').parent().find('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('input[name=c_report][value="' + _reports[i] + '"]').parent().attr('data-action', 'Unselect');
  }

  var reports_div = jQuery('#report_div');
  if (reports_div.find('.icons-checked').length === 0) {
    jQuery('#adv_bar_report').find('i').removeClass('icons-checked').addClass('icons-checkbox');
    jQuery('#adv_bar_report').find('div:first').attr('data-action', 'Select')
  } else {
    jQuery('#adv_bar_report').find('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_report').find('div:first').attr('data-action', 'Unselect')
  }

  bind_reports_events();
}

function bind_reports_events() {
  jQuery('input[name=c_report]').parent().children('label').click(function(event) {
    change_report(this);
    dtl.re_fetch_result();
  });

  jQuery('#adv_bar_report').click(function(event) {
    var reports_div = jQuery('#report_div');
    if (jQuery(this).children('i').hasClass('icons-checkbox')) {
      jQuery(this).children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery(this).parent().attr('data-action', 'Unselect');
      reports_div.find('i').removeClass('icons-checkbox').addClass('icons-checked');
      reports_div.find('div').attr('data-action', 'Unselect');
      show_adv_bar();
    } else {
      jQuery(this).children('i').removeClass('icons-checked').addClass('icons-checkbox');
      jQuery(this).parent().attr('data-action', 'Select');
      reports_div.find('i').removeClass('icons-checked').addClass('icons-checkbox');
      reports_div.find('div').attr('data-action', 'Select');
    }
    dtl.re_fetch_result();
  });
}

function change_report(target) {
  var o = jQuery(target);
  var $select = jQuery(o.parents('div')[0]);

  if (o.children('i').hasClass('icons-checkbox')) {
    o.children('i').removeClass('icons-checkbox').addClass('icons-checked');
    $select.attr('data-action', 'Unselect');
  } else {
    o.children('i').addClass('icons-checkbox').removeClass('icons-checked');
    $select.attr('data-action', 'Select');
  }

  var reports_div = o.parent('div').parents('div:first');
  if (reports_div.find('.icons-checked').length === 0) {
    jQuery('#adv_bar_report').find('i').removeClass('icons-checked').addClass('icons-checkbox');
    jQuery('#adv_bar_report').find('div:first').attr('data-action', 'Select');
  } else {
    jQuery('#adv_bar_report').find('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_report').find('div:first').attr('data-action', 'Unselect');
  }
}

function init_stock_number(_stock_number) {
  if (_stock_number != null) {
    if (_stock_number == 'all'){
        jQuery('input[name=c_stock_number][value="' + _stock_number + '"]').parent().find('i').removeClass('icons-checkbox').addClass('icons-checked');
    }else{
      for (i = 0; i < _stock_number.length; i ++) {
        jQuery('input[name=c_stock_number][value="' + _stock_number[i] + '"]').parent().find('i').removeClass('icons-checkbox').addClass('icons-checked');
      };
    };

  bind_stock_number_events();
  };
}

function bind_stock_number_events() {
  jQuery('input[name=c_stock_number]').parent().children('label').click(function(event) {
    change_stock_number(this);
    dtl.re_fetch_result();
  });
}

function change_stock_number(target) {
  var o = jQuery(target);
  if (o.children('i').hasClass('icons-checkbox')) {
    o.children('i').removeClass('icons-checkbox').addClass('icons-checked');
  } else {
    o.children('i').addClass('icons-checkbox').removeClass('icons-checked');
  }
  jQuery('#stock_number').val( [o.parent().children('input').val() +','+jQuery('#stock_number').val() ]);
  var stock_number_div = o.parent('div').parents('div:first');
  if (o.parent().children('input').val() == 'all') {
      jQuery('#stock_number').val('all');
      if (o.find('i').hasClass('icons-checked')) {
        stock_number_div.find('i').removeClass('icons-checkbox').addClass('icons-checked');
        stock_number_div.find('input[value=b]').parent().find('i').removeClass('icons-checked').addClass('icons-checkbox');
        stock_number_div.find('input[value=y]').parent().find('i').removeClass('icons-checked').addClass('icons-checkbox');
      }
    } else {
      if (o.find('i').hasClass('icons-checked')) {
        stock_number_div.find('input[value=all]').parent().find('i').removeClass('icons-checked').addClass('icons-checkbox');
      }
    }
}

function set_ratio_display(amount) {
  var MIN_RATIO = 1.00;
  var MAX_RATIO = 2.75;

  var _minRatio = jQuery(this).val()[0];
  var _maxRatio = jQuery(this).val()[1];
  var r_percent = 100 - (_minRatio - MIN_RATIO) * 1.00 / (MAX_RATIO - MIN_RATIO) * 100;
  jQuery('#ratio_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  var l_percent = 100 - (MAX_RATIO - _maxRatio) * 1.00 / (MAX_RATIO - MIN_RATIO) * 100;
  jQuery('#ratio_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});

  jQuery('#max_ratio').blur(function(event) {
    delay_run(function() {
        jQuery('#ratio_slider').trigger('change');
    }, {gid: 'rschange'});
  });

  jQuery('#min_ratio').blur(function(event) {
    delay_run(function() {
        jQuery('#ratio_slider').trigger('change');
    }, {gid: 'rschange'});
  });
}

function init_ratio_range(_minRatio, _maxRatio) {
  var MIN_RATIO = 1.00;
  var MAX_RATIO = 2.75;

  if (_minRatio === '' || _minRatio == '0.00') { _minRatio = MIN_RATIO; }
  if (_maxRatio === '' || _maxRatio == '0.00') { _maxRatio = MAX_RATIO; }

  jQuery('#ratio_slider').noUiSlider({
    range: [MIN_RATIO, MAX_RATIO],
    start: [_minRatio, _maxRatio],
    connect: true,
    serialization: {
      resolution: 0.01,
      to: [[jQuery('#min_ratio'), set_ratio_display], [jQuery('#max_ratio'), set_ratio_display]]
    },
    set: function() {
      jQuery('#adv_bar_ratio').children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery('#adv_bar_ratio').parent().attr('data-action', 'Unselect');
      dtl.re_fetch_result();
    }
  }).change(function() {
    jQuery('#adv_bar_ratio').children('i').removeClass('icons-checkbox').addClass('icons-checked');
    jQuery('#adv_bar_ratio').parent().attr('data-action', 'Unselect');
    dtl.re_fetch_result();
  });

  jQuery('#ratio_slider > div').append('<div class="ui-slider-left-cap"></div>');
  var r_percent = 100 - (_minRatio - MIN_RATIO) * 1.00 / (MAX_RATIO - MIN_RATIO) * 100;
  jQuery('#ratio_slider > div > .ui-slider-left-cap').css({'right': r_percent + '%'});
  jQuery('#ratio_slider > div').append('<div class="ui-slider-right-cap"></div>');
  var l_percent = 100 - (MAX_RATIO - _maxRatio) * 1.00 / (MAX_RATIO - MIN_RATIO) * 100;
  jQuery('#ratio_slider > div > .ui-slider-right-cap').css({'left': l_percent + '%'});

  jQuery('#adv_bar_ratio').click(function(event) {
    jQuery('#ratio_slider').val([MIN_RATIO, MAX_RATIO]);

    if (jQuery(this).children('i').hasClass('icons-checkbox')) {
      jQuery(this).children('i').removeClass('icons-checkbox').addClass('icons-checked');
      jQuery(this).parent().attr('data-action', 'Unselect');
      show_adv_bar();
    } else {
      jQuery(this).children('i').removeClass('icons-checked').addClass('icons-checkbox');
      jQuery(this).parent().attr('data-action', 'Select');
    }
    dtl.re_fetch_result();
  });
}

function init_shipping_date() {
  
  jQuery("#search_stock_number").on("click", function(){
		dtl.re_fetch_result();
	});
	jQuery('#stock_number').on("keypress", function(e) {
		if (e.keyCode == 13) {
			dtl.re_fetch_result();
			return false; // prevent the button click from happening
		}
	});
  /*if (jQuery('select[name=shipping_day]').length > 0) {
    jQuery('select[name=shipping_day]').change(function(event) {
      dtl.re_fetch_result();
    });
  }
  if (jQuery('select[name=shipping_day_adv]').length > 0) {
    jQuery('select[name=shipping_day_adv]').change(function(event) {
      dtl.re_fetch_result();
    });
  }*/
}

function show_adv_bar() {
  jQuery('#adv_bar_toggler').removeClass('icons-quadrate-plus').addClass('icons-quadrate-minus');
  jQuery('.advanced-search-filter').slideDown("fast");
  jQuery('#adv_bar_collapser').show();
}

function hide_adv_bar() {
  jQuery('#adv_bar_toggler').removeClass('icons-quadrate-minus').addClass('icons-quadrate-plus');
  jQuery('.advanced-search-filter').slideUp("fast");
  jQuery('#adv_bar_collapser').hide();
}

function init_adv_bar() {
  if (jQuery('.advanced-search-wrapper .advanced-search .icons-checked').length === 0) {
    hide_adv_bar();
    //show_adv_bar();
  } else {
    show_adv_bar();
  }

  jQuery('#adv_bar_toggler').click(function(event) {
    if (jQuery(this).hasClass('icons-quadrate-plus')) {
      show_adv_bar();
    } else {
      hide_adv_bar();
    }
  });

  jQuery('#adv_bar_collapser').click(function(event) {
    hide_adv_bar();
  });
}

function ajaxProxy(url, params, func) {
  CACHE.ajaxParams.url = url;
  CACHE.ajaxParams.params = params;
  CACHE.ajaxParams.func = func;

  if (!CACHE.ajaxTimer) {
    CACHE.ajaxTimer = setTimeout(function() {
      if (lastAjax) { // abort previous ajax request
        lastAjax.abort();
      }
      //display loading icon
      lastAjax = jQuery.get(CACHE.ajaxParams.url, CACHE.ajaxParams.params, CACHE.ajaxParams.func);

      CACHE.ajaxTimer = null;
    }, 1500);
  }
}

function init_special(_special)
{
	 //alert(_special);
	//alert(jQuery('#shapes').val());	
	 jQuery('#specialshapes').val(_special);
}

function init_search_panel(_shapes, _cuts, _colors, _fancycolors, _clars, _fluors, _minCarat, _maxCarat, _minPrice, _maxPrice,
  _minRatio, _maxRatio, _collections, _origins, _reports, _stock_number,_special) {
  init_shapes(_shapes);
  init_special(_special);
  init_price_range(_minPrice, _maxPrice);
  init_carat_range(_minCarat, _maxCarat);
  init_cuts_range(_cuts);
  init_colors_range(_colors);
  init_fancycolors_range(_fancycolors);
  init_clarities_range(_clars);
  init_fluorescences_range(_fluors);
  if (_collections !== null) {
    init_collections(_collections);
  }
  if (_origins !== null) {
    init_origins(_origins);
  }
  if (_reports !== null) {
    init_reports(_reports);
  }
  if (_minRatio !== null) {
    init_ratio_range(_minRatio, _maxRatio);
  }
  init_shipping_date();
  init_adv_bar();
  init_stock_number(_stock_number);
}

function reset_search_panel() {
  if(Sys.is_android || Sys.is_iphone){
    jQuery(".search-diamonds .search-diamonds-panel").css('display', 'block');
    jQuery(".search-diamonds .advanced-search-wrapper").css('display', 'block');
    jQuery(".search-diamonds .mobile-diamond-search-button").css('display', 'block');
    jQuery(".search-diamonds .your-search-results").css('display', 'none');
  }
  jQuery('#shapes').val('All');//change shape from Round ANIL
  jQuery('.product-shape li').removeClass('active');
  jQuery('.product-shape li').eq(0).addClass('active');
  jQuery('#price_slider').val([MIN_PRICE, MAX_PRICE]);
  jQuery('#shadow_price_slider').val([0, 10000]);
  jQuery('#carat_slider').val([MIN_CARAT, MAX_CARAT]);
  jQuery('#cut_slider').val([0, DIAMOND_SLIDER_ATTRIBUTES.cuts.length]);
  jQuery('#color_slider').val([0, DIAMOND_SLIDER_ATTRIBUTES.colors.length]);
  jQuery('#fancycolor_slider').val([0, DIAMOND_SLIDER_ATTRIBUTES.fancycolors.length]);
  jQuery('#clarity_slider').val([0, DIAMOND_SLIDER_ATTRIBUTES.clarities.length]);
  jQuery('#fluorescence_slider').val([0, DIAMOND_SLIDER_ATTRIBUTES.fluorescences.length]);
  jQuery('.advanced-search-wrapper').find('.icons-checked').removeClass('icons-checked').addClass('icons-checkbox');
  jQuery('#ratio_slider').val([1.00, 2.75]);
  jQuery('input[value=all]').parent().find('i').removeClass('icons-checkbox').addClass('icons-checked');
  var sd = jQuery('select[name=shipping_day]');
  sd.val('');
  sd.next().find('button').find('.filter-option').text('ON OR BEFORE');
  sd.next().find('li[class=selected]').removeClass('selected');
  sd.next().find('li[rel=0]').addClass('selected');
  var sda = jQuery('select[name=shipping_day_adv]');
  sda.val('');
  sda.next().find('button').find('.filter-option').text('ON OR BEFORE');
  sda.next().find('li[class=selected]').removeClass('selected');
  sda.next().find('li[rel=0]').addClass('selected');
  jQuery(jQuery('select[name=shipping_day]').children()[0]).attr('selected');
  jQuery(jQuery('select[name=shipping_day_adv]').children()[0]).attr('selected');

  hide_adv_bar();
}

function reset_diamonds_search_params(){
  var simi_key = 'similar_diamonds'+jQuery.trim(curr_symbol);
  var origin_key = 'search_params'+jQuery.trim(curr_symbol);
  var sp_str;
  if (jQuery.cookie(simi_key)){
      sp_str = jQuery.cookie(simi_key);
  } else {
      sp_str = jQuery.cookie(origin_key);
  }
  return sp_str;
}
