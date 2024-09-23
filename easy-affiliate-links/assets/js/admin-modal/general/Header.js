import React from 'react';

import { __eafl } from 'Shared/Translations';
import Icon from 'Shared/Icon';
 
const Header = (props) => {
    return (
        <div className="eafl-admin-modal-header">
            <h2>{ props.children }</h2>
            <div
                className="eafl-admin-modal-close"
                onClick={props.onCloseModal}
            >
                <Icon
                    type="close"
                    title={ __eafl( 'Close' ) }
                />
            </div>
        </div>
    );
}
export default Header;