import React, { Fragment } from 'react';
import Select, { components } from 'react-select';

const { Option, SingleValue } = components;

const ImageLabel = ( props ) => (
    <Fragment>
        {
            props.data.hasOwnProperty( 'image' )
            &&
            <img
                src={ props.data.image }
                style={ { marginRight: 10 } }
                alt={ props.data.label }
            />
        }
        { props.data.label }
    </Fragment>
);
 
const FieldDropdown = (props) => {
    return (
        <Select
            className="eafl-admin-modal-link-field-dropdown"
            value={props.options.filter(({value}) => value === props.value)}
            onChange={(option) => props.onChange(option.value)}
            options={props.options}
            clearable={false}
            components={ {
                Option: (props) => (
                    <Option {...props}>
                        <ImageLabel data={ props.data } />
                    </Option>
                ),
                SingleValue: (props) => (
                    <SingleValue {...props}>
                        <ImageLabel data={ props.data } />
                    </SingleValue>
                ),
            } }
        />
    );
}
export default FieldDropdown;