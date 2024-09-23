window.EasyAffiliateLinks = window.EasyAffiliateLinks || {};

window.EasyAffiliateLinks.clicks = {
    init: () => {
        // On Click Direct Link
		document.addEventListener( 'click', ( e ) => {
            for ( let target = e.target; target && target != this; target = target.parentNode ) {
                if ( target.classList && target.classList.contains( 'eafl-link-direct' ) ) {
                    window.EasyAffiliateLinks.clicks.onClickDirectLink( target, e );
                    break;
                }
            }
        }, false );
        
        // Check any HTML links.
        const htmlLinks = [ ...document.querySelectorAll( '.eafl-link-html' ) ];
        for ( let htmlLink of htmlLinks ) {
            window.EasyAffiliateLinks.clicks.initHtmlLink( htmlLink );
        }
    },
    initHtmlLink: ( container ) => {
        const eaflId = parseInt( container.dataset.eaflId );

        if ( 0 < eaflId ) {
            // Check for links inside first.
            const links = [ ...container.querySelectorAll('a') ];
            if ( 0 < links.length ) { 
                for ( let link of links ) {
                    link.addEventListener( 'click', ( e ) => {
                        window.EasyAffiliateLinks.clicks.register( eaflId );
                    } );
                }
            } else {
                // No links? Check for iframe.
                const iframes = [ ...container.querySelectorAll('iframe') ];

                for ( let iframe of iframes ) {
                    window.EasyAffiliateLinks.clicks.iframeListen( eaflId, iframe );
                }
            }
        }
    },
    iframeElems: [],
    iframeIds: [],
    iframeLastClicked: false,
    iframeTimeout: false,
    iframeListen: ( eaflId, elem ) => {
        const clicks = window.EasyAffiliateLinks.clicks;

        if ( false === clicks.iframeTimeout ) {
            // Start listening for iframe clicks.
            clicks.iframeTimeout = setInterval( () => {
                const elem = document.activeElement;
                if ( elem && elem.tagName == 'IFRAME' && clicks.iframeElems.includes( elem ) ) {
                    const index = clicks.iframeElems.indexOf( elem );
                    const eaflIdClicked = clicks.iframeIds[ index ];

                    if ( eaflIdClicked && eaflIdClicked !== clicks.iframeLastClicked ) {
                        clicks.register( eaflIdClicked );
                        clicks.iframeLastClicked = eaflIdClicked;
                    }
                }
            }, 250 );
        }

        // Add elem to iframe elems.
        clicks.iframeElems.push( elem );
        clicks.iframeIds.push( eaflId );
    },
    onClickDirectLink: ( elem, e ) => {
        let id = elem.dataset.eaflId;

        if ( ! id ) {
            id = elem.dataset.eaflGridId;
        }

        if ( id ) {
            window.EasyAffiliateLinks.clicks.register( id );
        }
    },
    register: ( id ) => {
        id = parseInt( id );

        if ( 0 < id ) {
            fetch( eafl_public.ajax_url, {
                method: 'POST',
                credentials: 'same-origin',
                body: 'action=eafl_register_click&link=' + id + '&security=' + eafl_public.nonce,
                headers: {
                    'Accept': 'application/json, text/plain, */*',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
                },
            } );
        }
    },
};

ready(() => {
    window.EasyAffiliateLinks.clicks.init();
});

function ready( fn ) {
    if (document.readyState != 'loading'){
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}