import React, { Fragment } from 'react';

import { __eafl } from 'Shared/Translations';
 
const Totals = (props) => {
    if ( ! props.filtered && ! props.total ) {
        return <div className="eafl-admin-table-totals">&nbsp;</div>;
    }

    const isFiltered = false !== props.filtered && props.filtered != props.total;

    return (
        <div className="eafl-admin-table-totals">
            {
                props.total
                ?
                <Fragment>
                    {
                    isFiltered
                    ?
                    `${ __eafl( 'Showing' ) } ${ Number(props.filtered).toLocaleString() } ${ __eafl( 'filtered of' ) } ${ Number(props.total).toLocaleString() } ${ __eafl( 'total' ) }`
                    :
                    `${ __eafl( 'Showing' ) } ${ Number(props.total).toLocaleString() } ${ __eafl( 'total' ) }`
                }
                </Fragment>
                :
                `${ Number(props.filtered).toLocaleString() } ${ __eafl( 'rows' ) }`
            }
        </div>
    );
}
export default Totals;