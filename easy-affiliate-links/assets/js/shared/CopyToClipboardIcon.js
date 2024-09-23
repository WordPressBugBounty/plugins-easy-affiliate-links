
import React, { Component } from 'react';
import CopyToClipboard from 'react-copy-to-clipboard';

import Icon from 'Shared/Icon';
import { __eafl } from 'Shared/Translations';

export default class CopyToClipboardIcon extends Component {
    constructor(props) {
        super(props);

        this.state = {
            copied: false,
        }
    }

    onCopy() {
        this.setState({
            copied: true,
        }, () => {
            setTimeout(() => {
                this.setState({
                    copied: false,
                });
            }, 2000);
        });
    }

    render() {
        return (
            <CopyToClipboard
                text={this.props.text}
                onCopy={this.onCopy.bind(this)}
            >
                <span
                    className="eafl-admin-table-url-container-copy"
                    style={{
                        opacity: this.state.copied ? 0.2 : 1
                    }}
                >
                    <Icon
                        type="link"
                        title={ this.state.copied ? __eafl( 'Copied!' ) : __eafl( 'Copy to clipboard' ) }
                    />
                </span>
            </CopyToClipboard>
        );
    }
}