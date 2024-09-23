import React from 'react';
import he from 'he';
 
import CopyToClipboardIcon from '../../shared/CopyToClipboardIcon';
import TextFilter from '../../admin-manage/general/TextFilter';
import Icon from 'Shared/Icon';
import { __eafl } from 'Shared/Translations';

export default {
    selects: [],
    getColumns( links ) {
        let categories = eafl_admin_manage_modal.categories.map(cat => { return {id: cat.term_id, label: `${cat.name} (${cat.count})` } } );
        categories.sort((a,b) => a.label.localeCompare(b.label));

        let columns = [{
            Header: __eafl( 'Date' ),
            id: 'date',
            accessor: 'date',
            width: 90,
            Filter: (props) => (<TextFilter {...props}/>),
            Cell: row => {
                const parts = row.value.split(' ');
                return (
                    <div>{ parts[0] }</div>
                )
            },
        },{
            Header: __eafl( 'Categories' ),
            id: 'categories',
            accessor: 'categories',
            sortable: false,
            Filter: ({ filter, onChange }) => (
                <select
                    onChange={event => onChange(event.target.value)}
                    style={{ width: '100%', fontSize: '1em' }}
                    value={filter ? filter.value : 'all'}
                >
                    <optgroup label={ __eafl( 'General' ) }>
                            <option value="all">{ __eafl( 'All Categories' ) }</option>
                            <option value="none">{ __eafl( 'No Categories' ) }</option>
                            <option value="any">{ __eafl( 'Any Categories' ) }</option>
                    </optgroup>
                    <optgroup label={ __eafl( 'Terms' ) }>
                        {
                            categories.map((term, index) => (
                                <option value={term.id} key={index}>{ he.decode(term.label) }</option>
                            ))
                        }
                    </optgroup>
                </select>
            ),
            Cell: row => {
                const names = row.value.map(t => t.name);
    
                return (
                    <div>{ he.decode( names.join(', ') ) }</div>
                )
            },
            width: 200,
        },{
            Header: __eafl( 'Name' ),
            id: 'name',
            accessor: 'name',
            width: 250,
            Filter: (props) => (<TextFilter {...props}/>),
        },{
            Header: __eafl( 'Text' ),
            id: 'text',
            accessor: 'text',
            width: 250,
            Filter: (props) => (<TextFilter {...props}/>),
            Cell: row => {
                const insertLinkText = (index) => {
                    let text;

                    if ( 'custom' === index ) {
                        text = prompt( `${ __eafl( 'Link text to use for:' ) } ${row.original.name}` );
                    } else if ( 'selected' === index ) {
                        text = links.state.selectedText;
                    } else {
                        text = row.value[ index ];
                    }

                    if ( null !== text ) {
                        links.insertLink( row.original, text );
                    }
                };

                return (
                    <div className="eafl-admin-table-insert-container">
                        <button
                            className="button button-primary eafl-admin-table-insert-button"
                            onClick={() => {
                                if ( 'html' === row.original.type ) {
                                    links.insertLink( row.original, `${ __eafl( 'Affiliate HTML Code' ) } "${ row.original.name ? row.original.name : row.original.id }"` );
                                } else {
                                    insertLinkText( this.selects[ row.original.id ].value );
                                }
                            }}
                        >
                            <Icon
                                type={ 'html' === row.original.type ? 'code' : 'link' }
                                title={ __eafl( 'Insert Link' ) }
                            />
                        </button>
                        {
                            'html' !== row.original.type
                            &&
                            <select
                                ref={(ref) => { this.selects[ row.original.id ] = ref; } }
                                onChange={event => { insertLinkText( event.target.value ); }}
                            >
                                {
                                    links.state.selectedText
                                    ?
                                    <option value="selected">{ links.state.selectedText }</option>
                                    :
                                    null
                                }
                                {
                                    row.value.map((text, index) => (
                                        <option value={index} key={index}>{ text }</option>
                                    ))
                                }
                                <option value="custom">{ __eafl( '...or use a custom text' ) }</option>
                            </select>
                        }
                    </div>
                )
            },
        },{
            Header: __eafl( 'Shortlink' ),
            id: 'shortlink',
            accessor: 'shortlink',
            width: 400,
            Filter: (props) => (<TextFilter {...props}/>),
            Cell: row => {
                const cloak = eafl_admin_manage_modal.options.cloak.find((option) => option.value === row.original.cloak );
                if ( cloak && 'no' === cloak.actual ) {
                    return null;
                }

                return (
                    <div className="eafl-admin-table-url-container">
                        <CopyToClipboardIcon text={row.value} />
                        <a href={row.value} target="_blank" className="eafl-admin-table-links-shortlink">{row.value}</a>
                    </div>
                )
            },
        },{
            Header: __eafl( 'URL' ),
            id: 'url',
            accessor: 'url',
            width: 400,
            Filter: (props) => (<TextFilter {...props}/>),
            Cell: row => {
                if ( ! row.value ) {
                    return null;
                }

                return (
                    <div className="eafl-admin-table-url-container">
                        <CopyToClipboardIcon text={row.value} />
                        <a href={row.value} target="_blank" className="eafl-admin-table-links-url">{row.value}</a>
                    </div>
                )
            },
        }];

        return columns;
    }
};