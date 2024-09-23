const { __ } = wp.i18n;
const {
    ToggleControl,
    Popover,
	IconButton,
} = wp.components;
const {
	Component
} = wp.element;

class AffiliateLinkPopover extends Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		return (
			<Popover
                position="bottom center"
            >
                <form onSubmit={ this.submitURL }>
                    <input type="url" value={ 'test' } onChange={ this.onChangeURL } />
                    <IconButton icon="editor-break" label={ __( 'Apply' ) } type="submit" />
                </form>
            </Popover>
		);
	}
}

export default AffiliateLinkPopover;