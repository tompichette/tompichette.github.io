$(document).ready(function() {


    /* ======= Fixed header when scrolled ======= */

    $(window).bind('scroll', function() {
         if ($(window).scrollTop() > 0) {
             $('#header').addClass('header-scrolled');
         }
         else {
             $('#header').removeClass('header-scrolled');
         }
    });

    /* ======= Scrollspy ======= */
    $('body').scrollspy({ target: '#header', offset: 100});

    /* ======= ScrollTo ======= */
    $('a.scrollto').on('click', function(e){

        //store hash
        var target = this.hash;

        e.preventDefault();

		$('body').scrollTo(target, 800, {offset: -50, 'axis':'y'});
        //Collapse mobile menu after clicking
		if ($('.navbar-collapse').hasClass('show')){
			$('.navbar-collapse').removeClass('show');
		}

	});

	(function($) {

	  /**
	   * Copyright 2012, Digital Fusion
	   * Licensed under the MIT license.
	   * http://teamdf.com/jquery-plugins/license/
	   *
	   * @author Sam Sehnert
	   * @desc A small plugin that checks whether elements are within
	   *     the user visible viewport of a web browser.
	   *     only accounts for vertical position, not horizontal.
	   */

	  $.fn.visible = function(partial) {

	      var $t            = $(this),
	          $w            = $(window),
	          viewTop       = $w.scrollTop(),
	          viewBottom    = viewTop + $w.height(),
	          _top          = $t.offset().top,
	          _bottom       = _top + $t.height(),
	          compareTop    = partial === true ? _bottom : _top,
	          compareBottom = partial === true ? _top : _bottom;

	    return ((compareBottom <= viewBottom) && (compareTop >= viewTop));

	  };

	})(jQuery);

	$(window).scroll(function(event) {

	  $("#cta--animated").each(function(i, el) {
	    var el = $(el);
	    if (el.visible(true)) {
	      el.addClass("animated tada delay-1s");
	    } else {
	      el.removeClass("fadeIn");
	    }
	  });

	});


});
