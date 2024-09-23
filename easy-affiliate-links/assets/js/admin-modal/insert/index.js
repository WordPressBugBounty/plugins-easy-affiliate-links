import React, { Component, Fragment } from 'react';
import ReactTable from 'react-table';
import 'react-table/react-table.css';

import '../../../css/admin/modal/insert.scss';

import Api from 'Shared/Api';
import { __eafl } from 'Shared/Translations';

import Columns from './Columns';
import Header from '../general/Header';

export default class Insert extends Component {
    constructor(props) {
        super(props);

        const selectedText = props.args.hasOwnProperty( 'selectedText' ) ? props.args.selectedText : false;

        this.state = {
            data: [],
            filtered: selectedText ? [
                {
                    id: 'name',
                    value: selectedText,
                }
            ] : [],
            pages: null,
            loading: true,
            isFirstLoad: true,
            columns: Columns.getColumns( this ),
            insertCallback: props.args.hasOwnProperty( 'insertCallback' ) ? props.args.insertCallback : false,
            selectedText,
        }

        // Bind functions.
        this.refreshData = this.refreshData.bind(this);
        this.fetchData = this.fetchData.bind(this);
        this.insertLink = this.insertLink.bind(this);
    }

    refreshData() {
        this.refReactTable.fireFetchData();
    }

    fetchData(state, instance) {
        this.setState({ loading: true });

        Api.manage.getData({
            route: 'links',
            type: 'links',
            pageSize: state.pageSize,
            page: state.page,
            sorted: state.sorted,
            filtered: state.filtered,
        }).then(data => {
            if ( data ) {
                let newState = {
                    data: data.rows,
                    pages: data.pages,
                    loading: false,
                    isFirstLoad: false,
                };

                if ( this.state.isFirstLoad && this.state.selectedText && 0 === data.filtered ) {
                    // Our selected text didn't have a match, redo the search without it.
                    newState = {
                        filtered: [],
                        data: [],
                        columns: Columns.getColumns( this ), // Need to set this again to remove the filter.
                        pages: null,
                        loading: true,
                        isFirstLoad: false,
                    }

                    // Set unfiltered state and refresh table.
                    this.setState(newState, () => {
                        setTimeout(() => {
                            this.refreshData();
                        }, 500);
                    } );
                } else {
                    // We had results, just set state.
                    this.setState(newState);
                }
            }
        });
    }

    insertLink( link, text ) {
        if ( 'function' === typeof this.state.insertCallback ) {
            this.state.insertCallback( link, text );
        }

        this.props.maybeCloseModal();
    }

    render() {
        const { columns, data, pages, loading } = this.state;

        return (
            <Fragment>
                <Header
                    onCloseModal={ this.props.maybeCloseModal }
                >
                    <button
                        className="button button-primary"
                        onClick={() => {
                            this.props.maybeCloseModal(() => {
                                const defaults = this.state.selectedText ? { text: [this.state.selectedText] } : {};

                                EAFL_Modal.open('create', {
                                    link: defaults,
                                    saveCallback: (link) => {
                                        const text = this.state.selectedText ? this.state.selectedText : link.text[0];
                                        this.insertLink( link, text );
                                    }
                                });
                            });
                        }}
                    >{ __eafl( 'Create new Affiliate Link' ) }</button>{ __eafl( 'or insert an existing link' ) }
                </Header>
                <div className="eafl-admin-modal-content">
                    <ReactTable
                        ref={(refReactTable) => {this.refReactTable = refReactTable;}}
                        manual
                        columns={columns}
                        data={data}
                        pages={pages}
                        loading={loading}
                        onFetchData={this.fetchData}
                        defaultPageSize={25}
                        defaultSorted={[{
                            id: "id",
                            desc: true
                        }]}
                        filterable
                        filtered={this.state.filtered}
                        onFilteredChange={ filtered => { this.setState( { filtered } ); } }
                        resizable={false}
                        className="eafl-admin-modal-table eafl-admin-table -highlight"
                    />
                </div>
            </Fragment>
        );
    }
}