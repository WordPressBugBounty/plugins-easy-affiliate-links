import '../../../css/blocks/affiliate-link-inline.scss';
import '../../../css/blocks/button.scss';

const { __ } = wp.i18n;
const {
	ToolbarGroup,
	ToolbarButton,
} = wp.components;
const {
	registerFormatType,
	getTextContent,
	applyFormat,
	removeFormat,
	slice,
	create,
	insert,
} = wp.richText;
const {
	Component
} = wp.element;

// Backwards compatibility.
let BlockControls;
if ( wp.hasOwnProperty( 'blockEditor' ) ) {
	BlockControls = wp.blockEditor.BlockControls;
} else {
	BlockControls = wp.editor.BlockControls;
}


const name = 'easy-affiliate-links/affiliate-link';

registerFormatType( name, {
	title: __( 'Affiliate Link' ),
	tagName: 'a',
	className: 'eafl-link',
	attributes: {
		eaflId: 'data-eafl-id',
		eaflText: 'data-eafl-text',
		url: 'href',
		target: 'target',
	},
	edit: class LinkEdit extends Component {
		constructor() {
			super( ...arguments );

			this.addLink = this.addLink.bind( this );
			this.editLink = this.editLink.bind( this );
			this.addLinkCallback = this.addLinkCallback.bind( this );
			this.onRemoveFormat = this.onRemoveFormat.bind( this );
		}

		addLink() {
			const { value } = this.props;
			let selectedText = getTextContent( slice( value ) );

			EAFL_Modal.open('insert', {
				insertCallback: this.addLinkCallback,
				selectedText,
			});
		}

		addLinkCallback( link, text ) {
			const { value, onChange } = this.props;

			if ( ! text ) {
				text = 'affiliate link';
			}

			const format = {
				type: name,
				attributes: {
					url: link.url,
					eaflId: '' + link.id, // Make sure this is a string.
					eaflText: text,
				}
			}

			// TODO New tab and nofollow
			const toInsert = applyFormat( create( { text } ), format, 0, text.length );
			onChange( insert( value, toInsert ) );
		}

		editLink() {
			const { eaflId } = this.props.activeAttributes;

			EAFL_Modal.open('edit', {
				linkId: eaflId,
			});
		}

		onRemoveFormat() {
			const { value, onChange } = this.props;

			onChange( removeFormat( value, name ) );
		}

		render() {
			const { isActive, activeAttributes, value, onChange } = this.props;

			return (
				<BlockControls>
					<ToolbarGroup>
						{ ! isActive && <ToolbarButton
							icon="admin-links"
							className="eafl-link-button"
							label={ __( 'Affiliate Link' ) }
							onClick={ this.addLink }
						/> }
						{ isActive && <ToolbarButton
							isPressed={ true }
							icon="admin-links"
							className="eafl-link-button"
							label={ __( 'Edit Affiliate Link' ) }
							onClick={ this.editLink }
						/> }
						{ isActive && <ToolbarButton
							isPressed={ true }
							icon="editor-unlink"
							className="eafl-link-button"
							label={ __( 'Unlink Affiliate Link' ) }
							onClick={ this.onRemoveFormat }
						/> }
					</ToolbarGroup>
				</BlockControls>
			);
		}
	},
});