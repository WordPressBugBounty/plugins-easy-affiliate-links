window.EasyAffiliateLinks.cleanUp = {
	init: () => {
		document.addEventListener( 'click', function(e) {
			for ( var target = e.target; target && target != this; target = target.parentNode ) {
				if ( target.matches( '.eafl-statistics-cleanup #remove_all' ) ) {
					EasyAffiliateLinks.cleanUp.onClickRemoveAll( target, e );
					break;
				}
			}
		}, false );
	},
	onClickRemoveAll: ( el, e ) => {
		if ( el.checked ) {
			if ( ! confirm( 'Warning: this will remove ALL our click data' ) ) {
				el.checked = false;
			}
		}		
	},
};

ready(() => {
	window.EasyAffiliateLinks.cleanUp.init();
});

function ready( fn ) {
    if (document.readyState != 'loading'){
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}