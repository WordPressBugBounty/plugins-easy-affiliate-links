import '../../../css/blocks/button.scss';

const { __ } = wp.i18n;
const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const {
	ToolbarGroup,
	ToolbarButton,
} = wp.components;

const buttonAttributes = {
	eaflId: {
		type: 'string',
		default: '',
	},
	eaflType: {
		type: 'string',
		default: '',
	},
	eaflUrl: {
		type: 'string',
		default: '',
	},
};

// Backwards compatibility.
let BlockControls;
if ( wp.hasOwnProperty( 'blockEditor' ) ) {
	BlockControls = wp.blockEditor.BlockControls;
} else {
	BlockControls = wp.editor.BlockControls;
}

const getOptionActual = ( options, value, fallback = value ) => {
	options = options || [];

	const option = options.find( ( option ) => option.value === value );
	return option ? option.actual : fallback;
};

const getButtonText = ( text ) => {
	const element = document.createElement( 'div' );
	element.innerHTML = text || '';
	return element.textContent || element.innerText || '';
};

const getLinkRel = ( link ) => {
	const rel = [];
	const nofollow = getOptionActual( eafl_admin_manage_modal.options.nofollow, link.nofollow, link.nofollow );

	if ( 'nofollow' === nofollow ) {
		rel.push( 'nofollow' );
	}
	if ( link.sponsored ) {
		rel.push( 'sponsored' );
	}
	if ( link.ugc ) {
		rel.push( 'ugc' );
	}

	return rel.join( ' ' );
};

const getLinkUrl = ( link ) => {
	const cloak = getOptionActual( eafl_admin_manage_modal.options.cloak, link.cloak, link.cloak );

	if ( 'yes' === cloak && link.shortlink ) {
		return link.shortlink;
	}

	return link.url || '';
};

const getButtonAttributesForLink = ( link ) => {
	const target = getOptionActual( eafl_admin_manage_modal.options.target, link.target, link.target );

	return {
		eaflId: '' + link.id,
		eaflType: link.type,
		eaflUrl: getLinkUrl( link ),
		url: getLinkUrl( link ),
		linkTarget: target,
		rel: getLinkRel( link ),
	};
};

addFilter( 'blocks.registerBlockType', 'easy-affiliate-links/button-attributes', ( settings, name ) => {
	if ( 'core/button' !== name ) {
		return settings;
	}

	return {
		...settings,
		attributes: {
			...settings.attributes,
			...buttonAttributes,
		},
	};
} );

if ( wp.blocks.getBlockType ) {
	const buttonBlock = wp.blocks.getBlockType( 'core/button' );

	if ( buttonBlock ) {
		buttonBlock.attributes = {
			...buttonBlock.attributes,
			...buttonAttributes,
		};
	}
}

const withAffiliateLinkButtonControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		if ( 'core/button' !== props.name ) {
			return <BlockEdit { ...props } />;
		}

		const { attributes, isSelected, setAttributes } = props;
		const hasAffiliateLink = !! attributes.eaflId;

		const setAffiliateLink = ( link ) => {
			if ( 'html' === link.type ) {
				alert( __( 'Affiliate HTML Code links cannot be used for Button blocks.' ) );
				return;
			}

			setAttributes( getButtonAttributesForLink( link ) );
		};

		const selectAffiliateLink = () => {
			EAFL_Modal.open( 'insert', {
				insertCallback: setAffiliateLink,
				selectedText: getButtonText( attributes.text ),
				excludeHtmlLinks: true,
			} );
		};

		const editAffiliateLink = () => {
			EAFL_Modal.open( 'edit', {
				linkId: attributes.eaflId,
				saveCallback: setAffiliateLink,
			} );
		};

		const removeAffiliateLink = () => {
			setAttributes( {
				eaflId: '',
				eaflType: '',
				eaflUrl: '',
				url: '',
				linkTarget: '',
				rel: '',
			} );
		};

		return (
			<Fragment>
				<BlockEdit { ...props } />
				{
					isSelected
					&&
					<BlockControls>
						<ToolbarGroup>
							{
								! hasAffiliateLink
								&&
								<ToolbarButton
									icon="admin-links"
									className="eafl-link-button"
									label={ __( 'Affiliate Link' ) }
									onClick={ selectAffiliateLink }
								/>
							}
							{
								hasAffiliateLink
								&&
								<ToolbarButton
									isPressed={ true }
									icon="admin-links"
									className="eafl-link-button"
									label={ __( 'Edit Affiliate Link' ) }
									onClick={ editAffiliateLink }
								/>
							}
							{
								hasAffiliateLink
								&&
								<ToolbarButton
									icon="update"
									className="eafl-link-button"
									label={ __( 'Change Affiliate Link' ) }
									onClick={ selectAffiliateLink }
								/>
							}
							{
								hasAffiliateLink
								&&
								<ToolbarButton
									isPressed={ true }
									icon="editor-unlink"
									className="eafl-link-button"
									label={ __( 'Unlink Affiliate Link' ) }
									onClick={ removeAffiliateLink }
								/>
							}
						</ToolbarGroup>
					</BlockControls>
				}
			</Fragment>
		);
	};
}, 'withAffiliateLinkButtonControls' );

addFilter( 'editor.BlockEdit', 'easy-affiliate-links/button-controls', withAffiliateLinkButtonControls );
