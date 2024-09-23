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
            bulkEditCheckbox( datatable, 'term_id' ),
            {
                Header: __eafl( 'Sort:' ),
                id: 'actions',
                headerClassName: 'eafl-admin-table-help-text',
                sortable: false,
                width: 100,
                Filter: () => (
                    <div>
                        { __eafl( 'Filter:' ) }
                    </div>
                ),
                Cell: row => (
                    <div className="eafl-admin-manage-actions">
                        <Icon
                            type="edit"
                            title={ __eafl( 'Rename Category' ) }
                            onClick={() => {
                                let newName = prompt( `${ __eafl( 'What do you want to be the new name for' ) } "${row.original.name}"?`, row.original.name );
                                if( newName && newName.trim() ) {
                                    Api.category.rename(row.original.term_id, newName).then(() => datatable.refreshData());
                                }
                            }}
                        />
                        <Icon
                            type="merge"
                            title={ __eafl( 'Merge into another category' ) }
                            onClick={() => {
                                let newId = prompt( `${ __eafl( 'What is the ID of the category you want the merge' ) } "${row.original.name}" ${ __eafl( 'into' ) }?` );
                                if( newId && newId != row.original.term_id && newId.trim() ) {
                                    Api.category.get(newId).then(newCategory => {
                                        if ( newCategory ) {
                                            if ( confirm( `${ __eafl( 'Are you sure you want to merge' ) } "${row.original.name}" ${ __eafl( 'into' ) } "${newCategory.name}"?` ) ) {
                                                Api.category.merge(row.original.term_id, newId).then(() => datatable.refreshData());
                                            }
                                        } else {
                                            alert( __eafl( 'We could not find a category with that ID.' ) );
                                        }
                                    });
                                }
                            }}
                        />
                        <Icon
                            type="delete"
                            title={ __eafl( 'Delete Category' ) }
                            onClick={() => {
                                if ( confirm( `Are you sure you want to delete the "${row.original.name}" category?` ) ) {
                                    Api.category.delete(row.original.term_id).then(() => datatable.refreshData());
                                }
                            }}
                        />
                    </div>
                ),
            },{
                Header: __eafl( 'ID' ),
                id: 'id',
                accessor: 'term_id',
                width: 65,
                Filter: (props) => (<TextFilter {...props}/>),
            },{
                Header: __eafl( 'Name' ),
                id: 'name',
                accessor: 'name',
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => he.decode(row.value),
            },{
                Header: __eafl( '# Links' ),
                id: 'count',
                accessor: 'count',
                filterable: false,
                width: 65,
            }
        ];

        return columns;
    }
};