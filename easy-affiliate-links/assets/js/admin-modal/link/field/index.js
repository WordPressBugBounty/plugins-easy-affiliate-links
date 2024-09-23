import React, { Component } from 'react';

import FieldCategory from './FieldCategory';
import FieldDropdown from './FieldDropdown';
import FieldRadio from './FieldRadio';
import FieldSlug from './FieldSlug';
import FieldTextVariants from './FieldTextVariants';

const { hooks } = EasyAffiliateLinks.shared;

export default class Field extends Component {
    constructor(props) {
        super(props);

        this.inputField = React.createRef();
    }

    componentDidMount() {
        if ( 'name' === this.props.id ) {
            this.inputField.current.focus();
        }
    }
    
    render() {
        let { props } = this;

        // Allow to get overwritten with hook.
        if ( props.hasOwnProperty( 'hook' ) && props.hook ) {
            const hookedFields = hooks.applyFilters( 'fields', {} );

            if ( hookedFields.hasOwnProperty( props.hook ) ) {
                const HookedField = hookedFields[ props.hook ];
                return (
                    <HookedField
                        { ...props }
                    />
                )
            }
        }

        return (
            <div className={`eafl-admin-modal-link-field-container eafl-admin-modal-link-field-container-${props.type}`}>
                <div className="eafl-admin-modal-link-field-label">{props.label}</div>
                <div className="eafl-admin-modal-link-field">
                    {
                        'text' === props.type
                        &&
                        <input
                            ref={this.inputField}
                            type="text"
                            value={props.value}
                            onChange={(e) => {
                                props.onChange(e.target.value);
                            }}
                        />
                    }
                    {
                        'textarea' === props.type
                        &&
                        <textarea
                            value={props.value}
                            onChange={(e) => {
                                props.onChange(e.target.value);
                            }}
                        />
                    }
                    {
                        'categories' === props.type
                        &&
                        <FieldCategory
                            value={props.value}
                            onChange={props.onChange}
                        />
                    }
                    {
                        'dropdown' === props.type
                        &&
                        <FieldDropdown
                            options={props.options}
                            value={props.value}
                            onChange={props.onChange}
                        />
                    }
                    {
                        'textVariants' === props.type
                        &&
                        <FieldTextVariants
                            value={props.value}
                            onChange={props.onChange}
                        />
                    }
                    {
                        'radio' === props.type
                        &&
                        <FieldRadio
                            id={props.id}
                            value={props.value}
                            options={props.options}
                            onChange={props.onChange}
                        />
                    }
                    {
                        'slug' === props.type
                        &&
                        <FieldSlug
                            value={props.value}
                            onChange={props.onChange}
                        />
                    }
                    {
                        'custom' === props.type
                        &&
                        props.children
                    }
                </div>
            </div>
        );
    }
}
