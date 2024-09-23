import React from 'react';
import he from 'he';
 
import bulkEditCheckbox from '../general/bulkEditCheckbox';
import TextFilter from '../general/TextFilter';
import Api from 'Shared/Api';
import Icon from 'Shared/Icon';
import { __eafl } from 'Shared/Translations';

export default {
    getColumns( datatable ) {
        let columns = [
            bulkEditCheckbox( datatable ),
            {
                Header: __eafl( 'Sort:' ),
                id: 'actions',
                headerClassName: 'eafl-admin-table-help-text',
                sortable: false,
                width: 40,
                Filter: () => (
                    <div>
                        { __eafl( 'Filter:' ) }
                    </div>
                ),
                Cell: row => (
                    <div className="eafl-admin-manage-actions">
                        <Icon
                            type="delete"
                            title={ __eafl( 'Delete Click' ) }
                            onClick={() => {
                                if( confirm( __eafl( 'Are you sure you want to delete this click?' ) ) ) {
                                    Api.click.delete(row.original.id).then(() => datatable.refreshData());
                                }
                            }}
                        />
                    </div>
                ),
            },{
                Header: __eafl( 'ID' ),
                id: 'id',
                accessor: 'id',
                Filter: (props) => (<TextFilter {...props}/>),
                width: 65,
            },{
                Header: __eafl( 'Date' ),
                id: 'date',
                accessor: 'date',
                width: 150,
                Filter: (props) => (<TextFilter {...props}/>),
            },{
                Header: __eafl( 'Link ID' ),
                id: 'link_id',
                accessor: 'link_id',
                Filter: (props) => (<TextFilter {...props}/>),
                width: 65,
            },{
                Header: __eafl( 'Link Name' ),
                id: 'link_name',
                accessor: 'link',
                filterable: false,
                sortable: false,
                Cell: row => {        
                    return (
                        <div>{ row.value.name }</div>
                    )
                },
                width: 250,
            },{
                Header: __eafl( 'Link Shortlink' ),
                id: 'link_shortlink',
                accessor: 'link',
                filterable: false,
                sortable: false,
                Cell: row => {
                    const cloak = eafl_admin_manage_modal.options.cloak.find((option) => option.value === row.value.cloak );
                    if ( cloak && 'no' === cloak.actual ) {
                        return null;
                    }

                    return (
                        <div className="eafl-admin-table-url-container">
                            <a href={row.value.shortlink} target="_blank">{row.value.shortlink}</a>
                        </div>
                    )
                },
                width: 250,
            },{
                Header: __eafl( 'Link URL' ),
                id: 'link_url',
                accessor: 'link',
                filterable: false,
                sortable: false,
                Cell: row => {
                    if ( ! row.value.url ) {
                        return null;
                    }

                    return (
                        <div className="eafl-admin-table-url-container">
                            <a href={row.value.url} target="_blank">{row.value.url}</a>
                        </div>
                    )
                },
                width: 250,
            },{
                Header: __eafl( 'Referer URL' ),
                id: 'referer',
                accessor: 'referer',
                width: 250,
                Cell: row => (
                    <div className="eafl-admin-table-url-container">
                        <a href={row.value} target="_blank">{row.value}</a>
                    </div>
                ),
            },{
                Header: __eafl( 'User ID' ),
                id: 'user_id',
                accessor: 'user_id',
                width: 150,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => {
                    if ( ! row.value || '0' === row.value ) {
                        return (<div></div>);
                    }

                    const label = `${ row.value } - ${ row.original.user_name ? row.original.user_name : __eafl( 'n/a' ) }`;
                    return (
                        <div>
                            {
                                row.original.user_link
                                ?
                                <a href={ he.decode( row.original.user_link ) } target="_blank">{ label }</a>
                                :
                                label
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'IP' ),
                id: 'ip',
                accessor: 'ip',
                width: 150,
                Filter: (props) => (<TextFilter {...props}/>),
            },{
                Header: __eafl( 'Device Type' ),
                id: 'device_type',
                accessor: 'is_desktop',
                width: 100,
                sortable: false,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'All Types' ) }</option>
                        <option value="mobile">{ __eafl( 'Mobile' ) }</option>
                        <option value="tablet">{ __eafl( 'Tablet' ) }</option>
                        <option value="desktop">{ __eafl( 'Desktop' ) }</option>
                    </select>
                ),
                Cell: row => {
                    let type = __eafl( 'Desktop' );

                    if ( '1' !== row.value ) {
                        type = '1' === row.original.is_tablet ? __eafl( 'Tablet' ) : __eafl( 'Mobile' );
                    }

                    return (
                        <div> { type }</div>
                    )
                },
            },{
                Header: __eafl( 'Device Agent' ),
                id: 'agent',
                accessor: 'agent',
                width: 400,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => (
                    <div className="eafl-admin-table-agent-container">{row.value}</div>
                ),
            }
        ];

        return columns;
    }
};