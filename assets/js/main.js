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


	var mndFileds = new Array('Company', 'First Name', 'Last Name', 'Email', 'Phone');
	var fldLangVal = new Array('Company', 'First Name', 'Last Name', 'Email', 'Phone Number');
	var name = '';
	var email = '';

	/* Do not remove this code. */
	function reloadImg() {
		if (document.getElementById('imgid').src.indexOf('&d') !== -1) {
			document.getElementById('imgid').src = document.getElementById('imgid').src.substring(0, document.getElementById('imgid').src.indexOf('&d')) + '&d' + new Date().getTime();
		} else {
			document.getElementById('imgid').src = document.getElementById('imgid').src + '&d' + new Date().getTime();
		}
	}

	function checkMandatory3490434000001872023() {
		for (i = 0; i < mndFileds.length; i++) {
			var fieldObj = document.forms['WebToLeads3490434000001872023'][mndFileds[i]];
			if (fieldObj) {
				if (((fieldObj.value).replace(/^\s+|\s+$/g, '')).length == 0) {
					if (fieldObj.type == 'file') {
						alert('Please select a file to upload.');
						fieldObj.focus();
						return false;
					}
					alert(fldLangVal[i] + ' cannot be empty.');
					fieldObj.focus();
					return false;
				} else if (fieldObj.nodeName == 'SELECT') {
					if (fieldObj.options[fieldObj.selectedIndex].value == '-None-') {
						alert(fldLangVal[i] + ' cannot be none.');
						fieldObj.focus();
						return false;
					}
				} else if (fieldObj.type == 'checkbox') {
					if (fieldObj.checked == false) {
						alert('Please accept  ' + fldLangVal[i]);
						fieldObj.focus();
						return false;
					}
				}
				try {
					if (fieldObj.name == 'Last Name') {
						name = fieldObj.value;
					}
				} catch (e) {}
			}
		}
	}
	
});
