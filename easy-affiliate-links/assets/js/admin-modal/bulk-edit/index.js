import React, { Component, Fragment } from 'react';

import '../../../css/admin/modal/bulk-edit.scss';

import Api from 'Shared/Api';
import { __eafl } from 'Shared/Translations';
import Button from 'Shared/Button';

import Header from '../general/Header';
import Footer from '../general/Footer';

import ActionsCategories from './ActionsCategories';
import ActionsClicks from './ActionsClicks';
import ActionsLink from './ActionsLinks';

let actions = {
    'categories': {
        label: __eafl( 'Categories' ),
        elem: ActionsCategories,
    },
    'clicks': {
        label: __eafl( 'Clicks' ),
        elem: ActionsClicks,
    },
    'links': {
        label: __eafl( 'Links' ),
        elem: ActionsLink,
    },
};

export default class BulkEdit extends Component {
    constructor(props) {
        super(props);

        this.state = {
            route: props.args.hasOwnProperty( 'route' ) ? props.args.route : 'links',
            type: props.args.hasOwnProperty( 'type' ) ? props.args.type : 'links',
            ids: props.args.hasOwnProperty( 'ids' ) ? props.args.ids : [],
            action: false,
            savingChanges: false,
            result: false,
        };

        // Bind functions.
        this.onBulkEdit = this.onBulkEdit.bind(this);
        this.allowCloseModal = this.allowCloseModal.bind(this);
    }

    onBulkEdit() {
        if ( this.state.action ) {
            this.setState({
                savingChanges: true,
            }, () => {
                Api.manage.bulkEdit(this.state.route, this.state.type, this.state.ids, this.state.action).then((data) => {
                    let result = false;
                    if ( data.hasOwnProperty('result') )  {
                        result = data.result;
                    }

                    this.setState({
                        savingChanges: false,
                        result,
                    }, () => {
                        if ( 'function' === typeof this.props.args.saveCallback ) {
                            this.props.args.saveCallback();
                        }
                        if ( ! result ) {
                            this.props.maybeCloseModal();
                        }
                    });
                });
            });
        }
    }

    allowCloseModal() {
        return ! this.state.savingChanges;
    }

    changesMade() {
        if ( ! this.state.action || ! this.state.action.type ) {
            return false;
        } else {
            return Array.isArray( this.state.action.options ) && this.state.action.options.length === 0 ? false : true;
        }
    }

    render() {
        const action = actions.hasOwnProperty( this.state.type ) ? actions[ this.state.type ] : false;

        if ( ! action ) {
            return null;
        }

        const Actions = action.elem;
        const bulkEditLabel = `${ __eafl( 'Bulk Edit' ) } ${ this.state.ids.length } ${ action.label }`;

        return (
            <Fragment>
                <Header
                    onCloseModal={ this.props.maybeCloseModal }
                >
                    { bulkEditLabel  }
                </Header>
                <div className="eafl-admin-modal-bulk-edit-container">
                    {
                        false === this.state.result
                        ?
                        <Actions
                            action={ this.state.action }
                            onActionChange={ (action) => {
                                this.setState({
                                    action,
                                });
                            } }
                        />
                        :
                        <div dangerouslySetInnerHTML={ { __html: this.state.result } } />
                    }
                </div>
                <Footer
                    savingChanges={ this.state.savingChanges }
                >
                    {
                        false === this.state.result
                        ?
                        <Button
                            isPrimary
                            required={ this.state.action && this.state.action.hasOwnProperty( 'required' ) ? this.state.action.required : null }
                            onClick={this.onBulkEdit}
                            disabled={ ! this.changesMade() }
                        >{ bulkEditLabel }</Button>
                        :
                        <Button
                            isPrimary
                            onClick={ this.props.maybeCloseModal }
                        >{ __eafl( 'Close' ) }</Button>
                    }
                </Footer>
            </Fragment>
        );
    }
}
