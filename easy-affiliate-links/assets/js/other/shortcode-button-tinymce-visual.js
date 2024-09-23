(function() {
    tinymce.create('tinymce.plugins.easy_affiliate_links', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            ed.addButton('easy_affiliate_links', {
                title : 'Easy Affiliate Links',
                cmd : 'easy_affiliate_links',
                image : url.slice(0, -9) + '/images/icon-20x20.png'
            });

            ed.addCommand('easy_affiliate_links', function() {
                var selection = ed.selection.getContent();

                EAFL_Modal.open('insert', {
                    selectedText: selection,
                    insertCallback: function(link, text) {
                        var name = eafl_code_editor.shortcode_escape(link.name);
                        text = eafl_code_editor.shortcode_escape(text);

                        if ( ! text ) {
                            text = 'affiliate link';
                        }
                        
                        var shortcode = '[eafl id="' + link.id + '" name="' + name + '" text="' + text + '"]';
			            ed.execCommand('mceInsertContent', false, shortcode);
                    },
                });
            });
        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Easy Affiliate Links',
                author : 'Bootstrapped Ventures',
                authorurl : 'https://bootstrapped.ventures',
                infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
                version : "0.1"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'easy_affiliate_links', tinymce.plugins.easy_affiliate_links );
})();