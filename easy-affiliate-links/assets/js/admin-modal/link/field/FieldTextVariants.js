import React, { Fragment } from 'react';
import { __eafl } from 'Shared/Translations';
 
const FieldTextVariants = (props) => {
    return (
        <Fragment>
            {
                props.value.map((text, index) => (
                    <input
                        type="text"
                        value={text}
                        onChange={(e) => {
                            let text = [...props.value];
                            text[index] = e.target.value;

                            props.onChange(text);
                        }}
                        key={index}
                    />
                ))
            }
            <a
                href="#"
                className="eafl-admin-modal-link-field-variant-add"
                onClick={(e) => {
                    e.preventDefault();

                    let text = [ ...props.value ];
                    text.push('');
                    props.onChange(text);
                }}
            >
                { __eafl( 'Add variant' ) }
            </a>
        </Fragment>
    );
}
export default FieldTextVariants;