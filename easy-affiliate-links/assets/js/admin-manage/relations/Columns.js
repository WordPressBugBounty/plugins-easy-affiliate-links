import React from 'react';
import he from 'he';
 
import TextFilter from '../general/TextFilter';
import { __eafl } from 'Shared/Translations';
import Icon from 'Shared/Icon';

export default {
    getColumns( datatable ) {
        let columns = [
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
                    <div></div>
                ),
            },{
                Header: __eafl( 'Post ID' ),
                id: 'post_id',
                accessor: 'post_id',
                width: 65,
                Filter: (props) => (<TextFilter {...props}/>),
            },{
                Header: __eafl( 'Post Type' ),
                id: 'post_type',
                accessor: 'post',
                filterable: false,
                sortable: false,
                Cell: row => {
                    return (
                        <div>{ row.value ? row.value.post_type : 'n/a' }</div>
                    )
                },
                width: 120,
            },{
                Header: __eafl( 'Post Name' ),
                id: 'post_name',
                accessor: 'post',
                filterable: false,
                sortable: false,
                Cell: row => {
                    const parent_post = row.value;
                    const view_url = row.original.post_url ? he.decode( row.original.post_url ) : false;
                    const edit_url = row.original.post_edit_url ? he.decode( row.original.post_edit_url ) : false;
            
                    if ( ! parent_post ) {
                        return (
                            <div>n/a</div>
                        );
                    }

                    return (
                        <div className="eafl-admin-manage-relations-post-container">
                            {
                                view_url
                                &&
                                <a href={ view_url } target="_blank">
                                    <Icon
                                        type="eye"
                                        title={ __eafl( 'View Post' ) }
                                    />
                                </a>
                            }
                            {
                                edit_url
                                &&
                                <a href={ edit_url } target="_blank">
                                    <Icon
                                        type="pencil"
                                        title={ __eafl( 'Edit Post' ) }
                                    />
                                </a>
                            }
                            { he.decode( row.value.post_title ) }
                        </div>
                    );
                },
                width: 300,
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
                        <div>{ row.value ? row.value.name : 'n/a' }</div>
                    )
                },
                width: 300,
            },{
                Header: __eafl( 'Occurrences' ),
                id: 'occurrences',
                accessor: 'occurrences',
                Filter: (props) => (<TextFilter {...props}/>),
                width: 100,
            }
        ];

        return columns;
    }
};