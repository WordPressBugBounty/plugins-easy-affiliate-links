import React, { Component } from 'react';
import { withRouter } from 'react-router';

import '../../css/admin/manage/notices.scss';

import Api from 'Shared/Api';
import Icon from 'Shared/Icon';
import { __eafl } from 'Shared/Translations';

class Notices extends Component {
    render() {
        if ( ! eafl_admin_manage_modal.notices || ! eafl_admin_manage_modal.notices.length ) {
            return null;
        }

        return (
            <div className="eafl-admin-manage-notices">
                {
                    eafl_admin_manage_modal.notices.map((notice, index) => {
                        // Check if notice already dismissed.
                        if ( notice.dismissed ) {
                            return null;
                        }

                        // Check if notice should show up in a specific location only.
                        if ( false !== notice.location && this.props.location.pathname !== '/' + notice.location ) {
                            return null;
                        }

                        return (
                            <div className="eafl-admin-notice" key={ index }>
                                <div className="eafl-admin-notice-content">
                                    {
                                        notice.title
                                        ?
                                        <div className="eafl-admin-notice-title">{ notice.title }</div>
                                        :
                                        null
                                    }
                                    <div
                                        className="eafl-admin-notice-text"
                                        dangerouslySetInnerHTML={ { __html: notice.text } }
                                    />
                                </div>
                                {
                                    notice.dismissable
                                    &&
                                    <div className="eafl-admin-notice-dismiss">
                                        <Icon
                                            title={ __eafl( 'Remove Notice' ) }
                                            type="close"
                                            onClick={() => {
                                                Api.general.dismissNotice( notice.id );
                                                notice.dismissed = true;
                                                this.forceUpdate();
                                            }}
                                        />
                                    </div>
                                }
                            </div>
                        )
                    })
                }
            </div>
        );
    }
}

export default withRouter(Notices)