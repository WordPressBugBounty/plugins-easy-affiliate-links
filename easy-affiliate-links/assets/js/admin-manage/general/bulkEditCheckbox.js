import React from 'react';

import { __eafl } from 'Shared/Translations';

const bulkEditCheckbox = ( datatable, key = 'id' ) => ({
    Header: __eafl( 'Bulk Edit' ),
    id: 'bulk_edit',
    className: 'eafl-admin-table-checkbox-container',
    headerClassName: 'eafl-admin-table-checkbox-container',
    sortable: false,
    width: 30,
    Filter: () => (
        <input
            type="checkbox"
            checked={ 1 === datatable.state.selectedAllRows }
            ref={ input => {
                if (input) {
                    input.indeterminate = datatable.state.selectedAllRows === 2;
                }
            }}
            onChange={ () => datatable.toggleSelectAll() }
        />
    ),
    Cell: (row) => (
        <input
            type="checkbox"
            checked={ true === datatable.state.selectedRows[row.original[ key ]] }
            onChange={ () => datatable.toggleSelectRow(row.original[ key ]) }
        />
    ),
});

export default bulkEditCheckbox;