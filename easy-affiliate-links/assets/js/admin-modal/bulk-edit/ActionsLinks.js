import React, { Fragment } from 'react';

import FieldCategory from '../link/field/FieldCategory';
import FieldRadio from '../link/field/FieldRadio';
import { __eafl } from 'Shared/Translations';
 
const ActionsLink = (props) => {
    const selectedAction = props.action ? props.action.type : false;
    const actionOptions = [
        { value: 'add-categories', label: __eafl( 'Add Categories' ), default: [] },
        { value: 'remove-categories', label: __eafl( 'Remove Categories' ), default: [] },
        { value: 'change-cloaking', label: __eafl( 'Change Cloaking' ), default: 'default' },
        { value: 'change-target', label: __eafl( 'Change Target' ), default: 'default' },
        { value: 'change-redirect-type', label: __eafl( 'Change Redirect Type' ), default: 'default' },
        { value: 'change-nofollow', label: __eafl( 'Change Nofollow' ), default: 'default' },
        { value: 'change-sponsored', label: __eafl( 'Change Sponsored' ), default: '0' },
        { value: 'change-ugc', label: __eafl( 'Change UGC' ), default: '0' },
        { value: 'change-status-ignore', label: `${__eafl( 'Change Status Emails' )}${ eafl_admin.addons.premium ? '' : ` (${ __eafl( 'Premium Only' ) })` }`, default: '0' },
        { value: 'reset-clicks', label: __eafl( 'Reset Clicks' ), default: false },
        { value: 'update-status', label: `${__eafl( 'Update Status' )}${ eafl_admin.addons.premium ? '' : ` (${ __eafl( 'Premium Only' ) })` }`, default: false },
        { value: 'delete', label: __eafl( 'Delete Links' ), default: false },
    ];

    return (
        <form>
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
                                    let newAction = {
                                        type: option.value,
                                        options: option.default,
                                    }

                                    if ( option.hasOwnProperty( 'required' ) ) {
                                        newAction.required = option.required;
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
                        {
                            ( 'add-categories' === selectedAction || 'remove-categories' === selectedAction )
                            &&
                            <FieldCategory
                                custom={{
                                    menuPlacement: 'top',
                                    maxMenuHeight: 250,
                                }}
                                value={props.action.options}
                                onChange={(categories) => {
                                    const newAction = {
                                        ...props.action,
                                        options: categories,
                                    }
                
                                    props.onActionChange(newAction);
                                }}
                            />
                        }
                        {
                            'change-cloaking' === selectedAction
                            &&
                            <FieldRadio
                                id="cloak"
                                options={eafl_admin_manage_modal.options.cloak}
                                value={props.action.options}
                                onChange={(value) => {
                                    const newAction = {
                                        ...props.action,
                                        options: value,
                                    }
                
                                    props.onActionChange(newAction);
                                }}
                            />
                        }
                        {
                            'change-target' === selectedAction
                            &&
                            <FieldRadio
                                id="target"
                                options={eafl_admin_manage_modal.options.target}
                                value={props.action.options}
                                onChange={(value) => {
                                    const newAction = {
                                        ...props.action,
                                        options: value,
                                    }
                
                                    props.onActionChange(newAction);
                                }}
                            />
                        }
                        {
                            'change-redirect-type' === selectedAction
                            &&
                            <FieldRadio
                                id="redirect-type"
                                options={eafl_admin_manage_modal.options.redirect_type}
                                value={props.action.options}
                                onChange={(value) => {
                                    const newAction = {
                                        ...props.action,
                                        options: value,
                                    }
                
                                    props.onActionChange(newAction);
                                }}
                            />
                        }
                        {
                            'change-nofollow' === selectedAction
                            &&
                            <FieldRadio
                                id="nofollow"
                                options={eafl_admin_manage_modal.options.nofollow}
                                value={props.action.options}
                                onChange={(value) => {
                                    const newAction = {
                                        ...props.action,
                                        options: value,
                                    }
                
                                    props.onActionChange(newAction);
                                }}
                            />
                        }
                        {
                            'change-sponsored' === selectedAction
                            &&
                            <FieldRadio
                                id="sponsored"
                                options={[
                                    {
                                        value: '0',
                                        label: __eafl( 'Not Sponsored' ),
                                    },
                                    {
                                        value: '1',
                                        label: __eafl( 'Sponsored' ),
                                    },
                                ]}
                                value={props.action.options}
                                onChange={(value) => {
                                    const newAction = {
                                        ...props.action,
                                        options: value,
                                    }
                
                                    props.onActionChange(newAction);
                                }}
                            />
                        }
                        {
                            'change-ugc' === selectedAction
                            &&
                            <FieldRadio
                                id="ugc"
                                options={[
                                    {
                                        value: '0',
                                        label: __eafl( 'Not UGC' ),
                                    },
                                    {
                                        value: '1',
                                        label: __eafl( 'UGC' ),
                                    },
                                ]}
                                value={props.action.options}
                                onChange={(value) => {
                                    const newAction = {
                                        ...props.action,
                                        options: value,
                                    }
                
                                    props.onActionChange(newAction);
                                }}
                            />
                        }
                        {
                            'change-status-ignore' === selectedAction
                            &&
                            <FieldRadio
                                id="status-ignore"
                                options={[
                                    {
                                        value: '0',
                                        label: __eafl( 'Email if broken' ),
                                    },
                                    {
                                        value: '1',
                                        label: __eafl( 'Ignore when broken' ),
                                    },
                                ]}
                                value={props.action.options}
                                onChange={(value) => {
                                    const newAction = {
                                        ...props.action,
                                        options: value,
                                    }
                
                                    props.onActionChange(newAction);
                                }}
                            />
                        }
                    </div>
                </Fragment>
            }
        </form>
    );
}
export default ActionsLink;