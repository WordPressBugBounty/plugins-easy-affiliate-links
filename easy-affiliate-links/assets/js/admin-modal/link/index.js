import React, { Component, Fragment } from 'react';

import '../../../css/admin/modal/link.scss';

import Api from 'Shared/Api';
import Loader from 'Shared/Loader';
import { __eafl } from 'Shared/Translations';

import Fields from './Fields';
import Header from '../general/Header';
import Footer from '../general/Footer';

export default class Link extends Component {
    constructor(props) {
        super(props);

        // Get link fields.
        let link = JSON.parse( JSON.stringify( eafl_admin_manage_modal.link ) );
        let loadingLink = false;

        if ( 'edit' === props.mode && ( props.args.hasOwnProperty( 'link' ) || props.args.hasOwnProperty( 'linkId' ) ) ) {
            if ( props.args.hasOwnProperty( 'link' ) ) {
                link = JSON.parse( JSON.stringify( props.args.link ) );
            } else {
                loadingLink = true;
                Api.link.get(props.args.linkId).then((data) => {
                    if ( data ) {
                        const link = JSON.parse( JSON.stringify( data.link ) );
                        this.setState({
                            link,
                            originalLink: JSON.parse( JSON.stringify( link ) ),
                            loadingLink: false,
                        });
                    }
                });
            }
        }

        // Set default field values.
        if ( 'create' === props.mode && props.args.hasOwnProperty( 'link' ) ) {
            link = {
                ...link,
                ...props.args.link,
            }
        }

        // Set initial state.
        this.state = {
            link,
            originalLink: JSON.parse( JSON.stringify( link ) ),
            saveCallback: props.args.hasOwnProperty( 'saveCallback' ) ? props.args.saveCallback : false,
            savingChanges: false,
            loadingLink,
        };

        // Bind functions.
        this.onLinkChange = this.onLinkChange.bind(this);
        this.resetLink = this.resetLink.bind(this);
        this.saveLink = this.saveLink.bind(this);
        this.allowCloseModal = this.allowCloseModal.bind(this);
    }

    onLinkChange(field, value) {
        let newLink = JSON.parse( JSON.stringify( this.state.link ) );

        newLink[field] = value;

        this.setState({
            link: newLink,
        });
    }

    resetLink() {
        if ( this.changesMade() ) {
            this.setState({
                link: JSON.parse( JSON.stringify( this.state.originalLink ) ),
            });
        }
    }

    saveLink() {
        if ( this.changesMade() ) {
            this.setState({
                savingChanges: true,
            }, () => {
                const asNewLink = 'edit' === this.props.mode ? false : true;
                Api.link.save(asNewLink, this.state.link).then((data) => {
                    let newState = {
                        savingChanges: false,
                    }

                    if ( data ) {
                        newState.link = JSON.parse( JSON.stringify( data.link ) );
                        newState.originalLink = JSON.parse( JSON.stringify( data.link ) );
                    }

                    this.setState(newState, () => {
                        if ( 'function' === typeof this.state.saveCallback ) {
                            this.state.saveCallback(this.state.link);
                        }
                        this.props.maybeCloseModal();
                    });
                });
            });
        }
    }

    allowCloseModal() {
        return ! this.state.savingChanges && ( ! this.changesMade() || confirm( __eafl( 'Are you sure you want to close without saving changes?' ) ) );
    }

    changesMade() {
        return JSON.stringify( this.state.link ) !== JSON.stringify( this.state.originalLink );
    }

    render() {
        
        return (
            <Fragment>
                <Header
                    onCloseModal={ this.props.maybeCloseModal }
                >
                    {
                        'edit' === this.props.mode
                        ?
                        `${ __eafl( 'Edit Affiliate Link' ) } ${ this.state.loadingLink ? '' : this.state.link.id }`
                        :
                        __eafl( 'Create new Affiliate Link' )
                    }
                </Header>
                <div className="eafl-admin-modal-content">
                    {
                        this.state.loadingLink
                        ?
                        <Loader/>
                        :
                        <Fields
                            link={ this.state.link }
                            onLinkChange={ this.onLinkChange }
                        />
                    }
                </div>
                <Footer
                    savingChanges={ this.state.savingChanges }
                >
                    <button
                        className="button"
                        onClick={ this.resetLink }
                        disabled={ ! this.changesMade() }
                    >
                        { __eafl( 'Cancel Changes' ) }
                    </button>
                    <button
                        className="button button-primary"
                        onClick={ this.saveLink }
                        disabled={ ! this.changesMade() }
                    >
                        { 'edit' === this.props.mode ? __eafl( 'Save Changes' ) : __eafl( 'Create Link' ) }
                    </button>
                </Footer>
            </Fragment>
        );
    }
}