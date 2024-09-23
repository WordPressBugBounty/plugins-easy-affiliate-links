const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const {
    Disabled,
	ToolbarGroup,
	ToolbarButton,
} = wp.components;
const { Fragment } = wp.element;

// Backwards compatibility.
let InspectorControls;
let BlockControls;
let AlignmentToolbar;
if ( wp.hasOwnProperty( 'blockEditor' ) ) {
	InspectorControls = wp.blockEditor.InspectorControls;
    BlockControls = wp.blockEditor.BlockControls;
    AlignmentToolbar = wp.blockEditor.AlignmentToolbar;
} else {
	InspectorControls = wp.editor.InspectorControls;
    BlockControls = wp.editor.BlockControls;
    AlignmentToolbar = wp.editor.AlignmentToolbar;
}

let ServerSideRender;
if ( wp.hasOwnProperty( 'serverSideRender' ) ) {
    ServerSideRender = wp.serverSideRender;
} else {
    ServerSideRender = wp.components.ServerSideRender;
}

import '../../../css/blocks/affiliate-link.scss';

registerBlockType( 'easy-affiliate-links/easy-affiliate-link', {
    title: __( 'Easy Affiliate Link' ),
    description: __( 'Display an EAFL affiliate link.' ),
    icon: 'admin-links',
    keywords: [ 'eafl', 'affiliate', 'link' ],
    category: 'common',
    supports: {
        html: false,
    },
    transforms: {
        from: [
            {
                type: 'shortcode',
                tag: 'eafl',
                attributes: {
                    id: {
                        type: 'string',
                        shortcode: ( { named: { id = '' } } ) => {
                            return id.replace( 'id', '' );
                        },
                    },
                    text: {
                        type: 'string',
                        shortcode: ( { named: { text = '' } } ) => {
                            return text.replace( 'text', '' );
                        },
                    },
                },
            },
        ]
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;

        const selectAffiliateLink = () => {
            EAFL_Modal.open('insert', {
				insertCallback: ( link, text) => {
                    if ( ! text ) {
                        text = 'affiliate link';
                    }
        
                    setAttributes({
                        id: '' + link.id,
                        type: link.type,
                        text,
                        updated: Date.now(),
                    });
                },
				selectedText: '',
			});
        }

        // Open modal if no ID is selected yet.
        if ( ! attributes.id ) {
            selectAffiliateLink();
        }
        
        return (
            <Fragment>
                <BlockControls>
                    <AlignmentToolbar
                        value={ attributes.textAlign }
                        onChange={ ( nextAlign ) => {
                            setAttributes( { textAlign: nextAlign } );
                        } }
                    />
                    <ToolbarGroup>
						<ToolbarButton
							icon="admin-links"
                            className="eafl-link-button"
                            label={ __( 'Edit Affiliate Link' ) }
                            onClick={ () => {
                                EAFL_Modal.open('edit', {
                                    linkId: attributes.id,
                                    saveCallback: () => {
                                        setAttributes({
                                            updated: Date.now(),
                                        });
                                    },
                                });
                            } }
						/>
                        {
                            'text' === attributes.type
                            &&
                            <ToolbarButton
                                icon="edit"
                                label={ __( 'Edit Link Text' ) }
                                onClick={ () => {
                                    EAFL_Modal.open('text', {
                                        linkId: attributes.id,
                                        text: attributes.text,
                                        changeCallback: function(newText, id) {
                                            setAttributes({
                                                text: newText,
                                                updated: Date.now(),
                                            });
                                        },
                                    });
                                } }
                            />
                        }
                        <ToolbarButton
							icon="update"
                            label={ __( 'Change Affiliate Link' ) }
                            onClick={ selectAffiliateLink }
						/>
					</ToolbarGroup>
                </BlockControls>
                <Disabled>
                    <ServerSideRender
                        block="easy-affiliate-links/easy-affiliate-link"
                        attributes={ attributes }
                    />
                </Disabled>
            </Fragment>
        )
    },
    save: (props) => {
        return null;
    },
} );