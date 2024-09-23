import React, { Fragment } from 'react';

import { __eafl } from 'Shared/Translations';
 
const ActionsCategories = (props) => {
    const selectedAction = props.action ? props.action.type : false;
    const actionOptions = [
        { value: 'delete', label: __eafl( 'Delete Categories' ), default: false },
    ];

    return (
        <Fragment>
            <div className="eafl-admin-modal-bulk-edit-label">{ __eafl( 'Select an action to perform:' ) }</div>
            <div className="eafl-admin-modal-bulk-edit-actions">
                {
                    actionOptions.map((option) => (
                        <div className="eafl-admin-modal-bulk-edit-action" key={option.value}>
                            <input
                                type="radio"
                                value={option.value}
                                name={`eafl-admin-radio-bulk-edit-action`}
                                id={`eafl-admin-radio-bulk-edit-action-${option.value}`}
                                checked={selectedAction === option.value}
                                onChange={() => {
                                    const newAction = {
                                        type: option.value,
                                        options: option.default,
                                    }
                
                                    props.onActionChange(newAction);
                                }}
                            /><label htmlFor={`eafl-admin-radio-bulk-edit-action-${option.value}`}>{ option.label }</label>
                        </div>
                    ))
                }
            </div>
            {
                selectedAction && false !== props.action.options
                &&
                <Fragment>
                    <div className="eafl-admin-modal-bulk-edit-label">{ __eafl( 'Action options:' ) }</div>
                    <div className="eafl-admin-modal-bulk-edit-options">
                    </div>
                </Fragment>
            }
        </Fragment>
    );
}
export default ActionsCategories;