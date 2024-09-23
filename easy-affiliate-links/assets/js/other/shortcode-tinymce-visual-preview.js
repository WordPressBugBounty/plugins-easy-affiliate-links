
(function() {
    tinymce.PluginManager.add('easyaffiliatelinks', function( editor, url ) {
        function replaceShortcodes( content ) {
            // Find shortcode.
            content = content.replace( /\[eafl([^\]]*)\]/g, function( match ) {
                var id = match.match(/id="?'?(\d+)/i);
                var text = match.match(/text="([^"]+)/i);
                var name = match.match(/name="([^"]+)/i);

                id = id == null ? 0 : id[1];
                text = text == null ? 'affiliate link' : text[1];
                name = name == null ? '' : name[1];

                return html( match, id, text, name );
            });

            // Find Gutenberg link.
            content = content.replace( /<a[^>]+data-eafl-id=\"(\d+)[^>]+>(.*?)<\/a>/g, function( match, id, text ) {
                return html( match, id, text, '' );
            });

            return content;
        }

        function html( original, id, text, name ) {
            data = window.encodeURIComponent( original );
            return '<span style="border-bottom: 1px dashed #2980b9; cursor: pointer;" ' +
                'data-eafl-id="' + id + '" data-eafl-text="' + text + '" data-eafl-name="' + name + '" data-eafl-shortcode="' + data + '" contentEditable="false">' + text + '</span>';
        }

        function restoreShortcodes( content ) {
            function getAttr( str, name ) {
                name = new RegExp( name + '=\"([^\"]+)\"' ).exec( str );
                return name ? window.decodeURIComponent( name[1] ) : '';
            }

            return content.replace( /(<span [^>]+>[^<]*<\/span>)/g, function( match, elem ) {
                var data = getAttr( elem, 'data-eafl-shortcode' );

                if ( data ) {
                    return data;
                }

                return match;
            });
        }

        editor.on( 'mouseup', function( event ) {
            var dom = editor.dom,
                node = event.target;

            if ( node.nodeName === 'SPAN' && dom.getAttrib( node, 'data-eafl-shortcode' ) ) {
                // Don't trigger on right-click
                if ( event.button !== 2 ) {
                    var id = dom.getAttrib( node, 'data-eafl-id' );
                    var text = dom.getAttrib( node, 'data-eafl-text' );

                    EAFL_Modal.open('text', {
                        linkId: id,
                        text: text,
                        changeCallback: function(newText, id) {
                            if ( newText && text !== newText ) {
                                newText = eafl_code_editor.shortcode_escape(newText);

                                var oldShortcode = restoreShortcodes( dom.getOuterHTML(node) );
                                var newShortcode = oldShortcode.replace(/text="([^"]+)/i, 'text="' + newText);

                                // Edit Gutenberg links as well.
                                newShortcode = newShortcode.replace('>' + text + '</a>', '>' + newText + '</a>');

                                dom.setOuterHTML(node, replaceShortcodes( newShortcode ) );
                            }
                        },
                    });
                }
            }
        });

        editor.on( 'BeforeSetContent', function( event ) {
            event.content = replaceShortcodes( event.content );
        });

        editor.on( 'PostProcess', function( event ) {
            if ( event.get ) {
                event.content = restoreShortcodes( event.content );
            }
        });
    });
})();