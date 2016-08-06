window.addEvent('domready', function(){
	// smooth anchor scrolling
	new SmoothScroll(); 
	// style area
	if(document.id('gkStyleArea')){
		$$('#gkStyleArea a').each(function(element,index){
			element.addEvent('click',function(e){
	            e.stop();
				changeStyle(index+1);
			});
		});
	}
	// cart checker
	if($('btnCart')) {
		var gkvalue = $$('.gkPopupWrap .total_products');
		
		if(gkvalue[0]) {
			var numb = gkvalue.get('text').toString().match(/\d{1,}/g);
			document.id('gkItems').getElement('strong').innerHTML = '';
			document.id('gkItems').getElement('strong').innerHTML = (numb != null) ? numb[0] : 0;
		}
		
		(function() {
			var gkvalue = $$('.gkPopupWrap .total_products');
			if(gkvalue[0]) {
				var numb = gkvalue.get('text').toString().match(/\d{1,}/g);
				document.id('gkItems').getElement('strong').innerHTML = '';
				document.id('gkItems').getElement('strong').innerHTML = (numb != null) ? numb[0] : 0;
				if(numb!= null) {$$('.vm_cart_products')[0].addClass('gkProducts');}
			}
		}).periodical(4000);
	}
	// font-size switcher
	if(document.id('gkTools') && document.id('gkComponentWrap')) {
		var current_fs = 100;
		var content_fx = new Fx.Tween(document.id('gkComponentWrap'), { property: 'font-size', unit: '%', duration: 200 }).set(100);
		document.id('gkToolsInc').addEvent('click', function(e){ 
			e.stop(); 
			if(current_fs < 150) { 
				content_fx.start(current_fs + 10); 
				current_fs += 10; 
			} 
		});
		document.id('gkToolsReset').addEvent('click', function(e){ 
			e.stop(); 
			content_fx.start(100); 
			current_fs = 100; 
		});
		document.id('gkToolsDec').addEvent('click', function(e){ 
			e.stop(); 
			if(current_fs > 70) { 
				content_fx.start(current_fs - 10); 
				current_fs -= 10; 
			} 
		});
	}
	var login = false;
	var register = false;
	var tools = false;
	var cart = false;
	var search = false;
	var login_fx = null;
	var hlogin_fx = null;
	var register_fx = null;
	var hregister_fx = null;
	var cart_fx = null;
	var hcart_fx = null;
	var search_fx = null;
	var tools_fx = null;
	var link_login_fx = null;
	var link_reg_fx = null;
	var link_cart_fx = null;
	var link_search_fx = null;
	var login_over = false;
	var register_over = false;
	var cart_over = false;
	var opened = false;
	var opened2 = false;
	if(document.id('popupLogin')){
		login_fx = new Fx.Tween(document.id('popupLogin'), {property: 'opacity', duration:300}).set(0);
		hlogin_fx = new Fx.Tween(document.id('popupLogin'), {property: 'height', duration:300}).set(0);
		
		document.id('popupLogin').setStyle('display','block');
		document.id('btnLogin').addEvent('click', function(e){
			new Event(e).stop();
			if(!login){
				login_fx.start(1);
				var pw = $$('#popupLogin .gkPopupWrap')[0];
				hlogin_fx.start(pw.getSize().y + pw.getStyle('margin-top').toInt() * 2);
				login = true;	
				document.id('btnLogin').addClass('popup');
				if(Browser.name == 'ie') {
				    document.id('popupLogin').setStyles({
					"left" : (document.id('btnLogin').getCoordinates().left + document.id('btnLogin').getCoordinates().width - document.id('popupLogin').getCoordinates().width) - 3 + "px",
					"top" : (document.id('btnLogin').getCoordinates().top) + "px"
				});
				} else {
					document.id('popupLogin').setStyles({
					"left" : (document.id('btnLogin').getCoordinates().left + document.id('btnLogin').getCoordinates().width - document.id('popupLogin').getCoordinates().width) - 2 + "px",
					"top" : (document.id('btnLogin').getCoordinates().top) + "px"
				});
				}
			}else{
				login_fx.start(0);
				hlogin_fx.start(0);
				login = false;
				document.id('btnLogin').removeClass('popup');
			}
			if(register){
				register_fx.start(0);
				hregister_fx.start(0);
				register = false;
				document.id('btnRegister').removeClass('popup');
			}
			
			if(cart){
				cart_fx.start(0);
				hcart_fx.start(0);
				cart = false;
				document.id('btnCart').removeClass('popup');
			}
		});
		
		document.id('popupLogin').getElement('.gkPopupWrap').addEvent('mouseover',function(){login_over = true;});
		document.id('popupLogin').getElement('.gkPopupWrap').addEvent('mouseout',function(){login_over = false;});
	}
	if(document.id('popupRegister')){
		register_fx = new Fx.Tween(document.id('popupRegister'),{property:'opacity',duration:300}).set(0);
		hregister_fx = new Fx.Tween(document.id('popupRegister'),{property:'height',duration:300}).set(0);
		document.id('popupRegister').setStyle('display','block');
		document.id('btnRegister').addEvent('click', function(e){
			new Event(e).stop();
			showGKRecaptcha('gk_recaptcha',  'submit_1', 'recaptcha_required_1');
			if(!register){
				var pw = $$('#popupRegister .gkPopupWrap')[0];
				register_fx.start(1);
				hregister_fx.start(pw.getSize().y + pw.getStyle('margin-top').toInt() * 2);
				register = true;	
				document.id('btnRegister').addClass("popup");
				if(Browser.name == 'ie') {
				    document.id('popupRegister').setStyles({
					"left" : (document.id('btnRegister').getCoordinates().left + document.id('btnRegister').getCoordinates().width - document.id('popupRegister').getCoordinates().width) - 3 + "px",
					"top" : (document.id('btnRegister').getCoordinates().top) + "px"
				    });	
				} else {
					document.id('popupRegister').setStyles({
					"left" : (document.id('btnRegister').getCoordinates().left + document.id('btnRegister').getCoordinates().width - document.id('popupRegister').getCoordinates().width) - 2 + "px",
					"top" : (document.id('btnRegister').getCoordinates().top) + "px"
				    });
				}
			}else{
				register_fx.start(0);
				hregister_fx.start(0);
				register = false;
				document.id('btnRegister').removeClass("popup");
			}
			if(login){
				login_fx.start(0);
				hlogin_fx.start(0);
				login = false;
				document.id('btnLogin').removeClass("popup");
			}
			
			if(cart){
				cart_fx.start(0);
				hcart_fx.start(0);
				cart = false;
				document.id('btnCart').removeClass("popup");
			}
		});	
		document.id('popupRegister').getElement('.gkPopupWrap').addEvent('mouseover',function(){register_over = true;});
		document.id('popupRegister').getElement('.gkPopupWrap').addEvent('mouseout',function(){register_over = false;});
	}
	
	if(document.id('popupCart')){
		cart_fx = new Fx.Tween(document.id('popupCart'), {property: 'opacity', duration:300}).set(0);
		hcart_fx = new Fx.Tween(document.id('popupCart'), {property: 'height', duration:300}).set(0);
		
		document.id('popupCart').setStyle('display','block');
		document.id('btnCart').addEvent('click', function(e){
			new Event(e).stop();
			if(!cart){
				cart_fx.start(1);
				var pw = $$('#popupCart .gkPopupWrap')[0];
				hcart_fx.start(pw.getSize().y + pw.getStyle('margin-top').toInt() * 2);
				cart = true;	
				document.id('btnCart').addClass('popup');
				
				if(Browser.name == 'ie'){
				    document.id('popupCart').setStyles({
					  "left" : (document.id('btnCart').getCoordinates().left + document.id('btnCart').getCoordinates().width - document.id('popupCart').getCoordinates().width) - 3 + "px",
					  "top" : (document.id('btnCart').getCoordinates().top) + "px"
				    });
				} else {
					 document.id('popupCart').setStyles({
					  "left" : (document.id('btnCart').getCoordinates().left + document.id('btnCart').getCoordinates().width - document.id('popupCart').getCoordinates().width) - 2 + "px",
					  "top" : (document.id('btnCart').getCoordinates().top) + "px"
				    });
				}
			}else{
				cart_fx.start(0);
				hcart_fx.start(0);
				cart = false;
				document.id('btnCart').removeClass('popup');
			}
			
			if(login){
				login_fx.start(0);
				hlogin_fx.start(0);
				login = false;
				document.id('btnLogin').removeClass("popup");
			}
			if(register){
				register_fx.start(0);
				hregister_fx.start(0);
				register = false;
				document.id('btnRegister').removeClass("popup");
			}
		});
		
		document.id('popupCart').getElement('.gkPopupWrap').addEvent('mouseover',function(){cart_over = true;});
		document.id('popupCart').getElement('.gkPopupWrap').addEvent('mouseout',function(){cart_over = false;});
	}
	if(document.id('gkButtonTools')){
		opened = false;
		if(document.id('btnLogin')) link_login_fx = new Fx.Tween(document.id('btnLogin'),{property:'opacity', duration:300});
		if(document.id('btnRegister')) link_reg_fx = new Fx.Tween(document.id('btnRegister'),{property:'opacity', duration:300});
		if(document.id('btnCart')) link_cart_fx = new Fx.Tween(document.id('btnLogin'),{property:'opacity', duration:300});
		if(document.id('btnSearch')) link_search_fx = new Fx.Tween(document.id('btnSearch'),{property:'opacity', duration:300});
		
		document.id('popupTools').getParent().setProperty('class','gkHide').setStyle('display','block');
		document.id('popupTools').setStyle('display', 'block');
		tools_fx = new Fx.Tween($$('.gkHide')[0],{duration:300, property:'width'}).set(0);
		document.id('gkButtonTools').addEvent('click', function(e){
			new Event(e).stop();
			tools_fx.start((opened) ? 0 : 80);
			if(document.id('btnLogin')) link_login_fx.start((!opened) ? 0 : 1);
			if(document.id('btnRegister')) link_reg_fx.start((!opened) ? 0 : 1); 
			if(document.id('btnCart')) link_cart_fx.start((!opened) ? 0 : 1);
			opened = !opened;
			
			if(login) {
				login_fx.start(0);
				hlogin_fx.start(0);
				login = false;
				document.id('btnLogin').removeClass("popup");
			}
			if(register){
				register_fx.start(0);
				hregister_fx.start(0);
				register = false;
				document.id('btnRegister').removeClass("popup");
			}
			
			if(cart){
				cart_fx.start(0);
				hcart_fx.start(0);
				cart = false;
				document.id('btnCart').removeClass("popup");
			}
		});	
	} 
	// search button
	if(document.id('gkButtonSearch')) {
		document.id('gkSearchHide').set('tween', {duration: 250});
	
		document.id('gkButtonSearch').addEvent('click', function(e){
			e.stop();
			if(document.id('gkSearchHide').getSize().x > 0) {
				document.id('gkSearchHide').tween('width', 0);
			} else {
				document.id('gkSearchHide').tween('width', 180);
			}
		});
	}
	
	 if(document.id('gkProductTabs')) {
		
		document.id('gkComponent').addEvent('click', function(e){
		   
		   var evt = new Event(e).target;
		   
		     if((window.ie && evt.nodeName == 'SPAN') || (!window.ie && evt.get('tag') == 'span')) {
                if($(evt).getParent().getParent().getProperty('id') == 'gkProductTabs') {
                    $$('.gkProductTab').addClass('gkUnvisible');
                    $$('#gkProductTabs li').setProperty('class', '');
                    var num = 0;
                    $$('#gkProductTabs li').each(function(el, i){
                        if(el == evt.getParent()){ num = i; }
                    });
                    $$('.gkProductTab')[num].removeClass('gkUnvisible');
		            $$('#gkProductTabs li')[num].setProperty('class', 'gkProductTabActive');
                }
            } else if((window.ie && evt.nodeName == 'LI') || (!window.ie && evt.get('tag') == 'li')) {
                if($(evt).getParent().getProperty('id') == 'gkProductTabs') {
                    $$('.gkProductTab').addClass('gkUnvisible');
                    $$('#gkProductTabs li').setProperty('class', '');
                    var num = 0;
                    $$('#gkProductTabs li').each(function(el, i){
                        if(el == evt.getParent()){ num = i; }
                    });
                    $$('.gkProductTab')[num].removeClass('gkUnvisible');
		            $$('#gkProductTabs li')[num].setProperty('class', 'gkProductTabActive');
                }
            }
		});
	}
});
// function to set cookie
function setCookie(c_name, value, expire) {
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expire);
	document.cookie=c_name+ "=" +escape(value) + ((expire==null) ? "" : ";expires=" + exdate.toUTCString());
}
// Function to change styles
function changeStyle(style){
	var file1 = $GK_TMPL_URL+'/css/style'+style+'.css';
	var file2 = $GK_TMPL_URL+'/css/typography.style'+style+'.css';
	new Asset.css(file1);
	new Asset.css(file2);
	Cookie.write('gk1_style',style, { duration:365, path: '/' });
}