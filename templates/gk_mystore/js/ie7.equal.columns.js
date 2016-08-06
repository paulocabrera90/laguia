window.addEvent('domready', function(){
	// script to generate equal columns - unnecessary in the penguinMail template
	[document.id('gkTop1'), document.id('gkTop2')].each(function(el){
		if(el) {
			el.getElements('.gkCol').each(function(col){
				if(col.getSize().x - 1 > 0) {
					col.setStyle('width', (col.getSize().x - 1) + 'px');
				}
			});
		}
	});
	
	if(document.id('gkLeftLeft') && document.id('gkLeftRight')) {
		if(document.id('gkLeftLeft').getSize().x - 38 > 0) {
			document.id('gkLeftLeft').setStyle('width', (document.id('gkLeftLeft').getSize().x - 38) + "px");
		}
	}
	
	if(document.id('gkRightLeft') && document.id('gkRightRight')) {
		if(document.id('gkRightLeft').getSize().x - 28 > 0) {
			document.id('gkRightLeft').setStyle('width', (document.id('gkRightLeft').getSize().x - 28) + "px");
		}
	}
	
	if(document.id('gkInset1')) {
		if(document.id('gkInset1').getSize().x - 35 > 0) {
			document.id('gkInset1').setStyle('width', (document.id('gkInset1').getSize().x - 35) + "px");
		}
		if(document.id('gkContentBottom').getSize().x - 1 > 0) { 
    		document.id('gkContentBottom').setStyle('width', (document.id('gkContent').getSize().x - 1) + "px");
    	}
	}
	
	if(document.id('gkComponentWrap') && document.id('gkInset2')) {
		if(document.id('gkComponentWrap').getSize().x - 30 > 0) { 
			document.id('gkComponentWrap').setStyle('width', (document.id('gkComponentWrap').getSize().x - 30) + "px");
		}
		if(document.id('gkContentBottom').getSize().x - 1 > 0) { 
    		document.id('gkContentBottom').setStyle('width', (document.id('gkContent').getSize().x - 1) + "px");
    	}
	}
    
    if(document.id('gkContent') && document.id('gkLeft') && document.id('gkRight')) {
        if(document.id('gkContent').getSize().x - 38 > 0) { 
    		document.id('gkContent').setStyle('width', (document.id('gkContent').getSize().x - 38) + "px");
    	}
    } else if(document.id('gkContent') && document.id('gkLeft')) {
        if(document.id('gkContent').getSize().x - 10 > 0) { 
			document.id('gkContent').setStyle('width', (document.id('gkContent').getSize().x - 10) + "px");
		}
    } else if(document.id('gkContent') && document.id('gkRight')) {
        if(document.id('gkContent').getSize().x - 34 > 0) { 
			document.id('gkContent').setStyle('width', (document.id('gkContent').getSize().x - 34) + "px");
		}
    }
	
    
});