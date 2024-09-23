import React, { Component, Fragment } from 'react';

import '../../../css/admin/modal/text.scss';

import Header from '../general/Header';
import Footer from '../general/Footer';
import { __eafl } from 'Shared/Translations';

export default class Text extends Component {
    constructor(props) {
        super(props);

        const text = props.args.hasOwnProperty( 'text' ) ? props.args.text : '';

        this.state = {
            linkId: props.args.hasOwnProperty( 'linkId' ) ? props.args.linkId : false,
            text,
            originalText: text,
            saveCallback: props.args.hasOwnProperty( 'saveCallback' ) ? props.args.saveCallback : false,
            changeCallback: props.args.hasOwnProperty( 'changeCallback' ) ? props.args.changeCallback : false,
        };

        this.inputField = React.createRef();
        this.onChangeText = this.onChangeText.bind(this);
    }

    componentDidMount() {
        // Not sure why this is required but it works. 
        setTimeout(() => {
            this.inputField.current.focus();
        }, 200);
    }

    onChangeText() {
        if ( 'function' === typeof this.state.changeCallback ) {
            this.state.changeCallback( this.state.text, this.state.linkId );
        }

        this.props.maybeCloseModal();
    }

    render() {
        return (
            <Fragment>
                <Header
                    onCloseModal={ this.props.maybeCloseModal }
                >
                    <button
                        className="button button-primary"
                        onClick={() => {
                            this.props.maybeCloseModal(() => {
                                EAFL_Modal.open('edit', {
                                    linkId: this.state.linkId,
                                    saveCallback: this.state.saveCallback,
                                });
                            });
                        }}
                    >{ __eafl( 'Edit Affiliate Link' ) }</button>{ __eafl( 'or change link text' ) }
                </Header>
                <div className="eafl-admin-modal-content">
                    <label htmlFor="eafl-admin-modal-text-input">{ __eafl( 'Link text' ) }</label>
                    <input
                        id="eafl-admin-modal-text-input"
                        type="text"
                        ref={this.inputField}
                        value={ this.state.text }
                        onChange={ (e) => {
                            this.setState({
                                text: e.target.value,
                            });
                        }}
                        onKeyDown={ (e) => {
                            const key = e.keyCode ? e.keyCode : e.which;

                            if ( 13 === key ) {
                                this.onChangeText();
                            }
                        }}
                    />
                </div>
                <Footer
                    savingChanges={ false }
                >
                    <button
                        className="button button-primary"
                        onClick={ this.onChangeText }
                        disabled={ this.state.text === this.state.originalText }
                    >
                        { __eafl( 'Change Text' ) }
                    </button>
                </Footer>
            </Fragment>
        );
    }
}