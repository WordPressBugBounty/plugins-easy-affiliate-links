import React, { Fragment } from 'react';
import he from 'he';
 
import bulkEditCheckbox from '../general/bulkEditCheckbox';
import TextFilter from '../general/TextFilter';
import CopyToClipboardIcon from 'Shared/CopyToClipboardIcon';
import Api from 'Shared/Api';
import Icon from 'Shared/Icon';
import Tooltip from 'Shared/Tooltip';
import Media from 'Shared/Media';
import { __eafl } from 'Shared/Translations';

import '../../../css/admin/manage/links.scss';

const statusTypes = [
    {
        label: __eafl( 'OK' ),
        types: {
            'ok': __eafl( 'OK' ),
            'redirect-ok': __eafl( 'OK after Redirect' ),
        },
    },
    {
        label: __eafl( 'URL Problems' ),
        types: {
            'missing': __eafl( 'No URL set' ),
            'invalid': __eafl( 'Invalid URL' ),
            'broken': __eafl( 'URL Unreachable' ),
        },
    },
    {
        label: __eafl( 'Destination Problems' ),
        types: {
            'not-found': __eafl( 'Not Found Error' ),
            'forbidden': __eafl( 'Access Forbidden' ),
            'error': __eafl( 'Server Error' ),
            'redirect-nok': __eafl( 'Problem after Redirect' ),
        },
    },
    {
        label: __eafl( 'Other Problems' ),
        types: {
            'issue': __eafl( 'No Headers' ),
            'status-code-unknown': __eafl( 'Status Code Unkown' ),
        },
    },
];
const statusTypesFlat = statusTypes.reduce((allTypes, currGroup) => {
    return {
        ...allTypes,
        ...currGroup.types,
    };
}, {
    'unknown': __eafl( 'Status Unknown' )
} );

const amazonStatusTypes = {
    'AVAILABLE_DATE': { label: __eafl( 'Available Date' ), className: 'status-preorder' },
    'IN_STOCK': { label: __eafl( 'In Stock' ), className: 'status-in-stock' },
    'IN_STOCK_SCARCE': { label: __eafl( 'In Stock (Scarce)' ), className: 'status-in-stock' },
    'LEADTIME': { label: __eafl( 'Leadtime' ), className: 'status-preorder' },
    'OUT_OF_STOCK': { label: __eafl( 'Out of Stock' ), className: 'status-out-of-stock' },
    'PREORDER': { label: __eafl( 'Preorder' ), className: 'status-preorder' },
    'UNAVAILABLE': { label: __eafl( 'Unavailable' ), className: 'status-unavailable' },
    'UNKNOWN': { label: __eafl( 'Unknown' ), className: 'status-unknown' },
    'NOT_FOUND': { label: __eafl( 'Not Found' ), className: 'status-not-found' },
};

const getAmazonStatusProducts = (link) => {
    if ( ! link || 'amazon' !== link.type ) {
        return [];
    }

    let products = [];

    if ( link.amazon_asin ) {
        products.push({
            label: __eafl( 'Default Product' ),
            asin: link.amazon_asin,
            name: link.amazon_name || '',
            status: link.amazon_status || '',
            url: link.amazon_link || link.url || '',
        });
    }

    if ( Array.isArray( link.conditional ) ) {
        link.conditional.forEach((condition) => {
            if ( ! condition.amazon_asin ) {
                return;
            }

            const labelParts = [ condition.type, condition.value ].filter( part => part );

            products.push({
                label: labelParts.length ? `${ __eafl( 'Condition' ) }: ${ labelParts.join( ' - ' ) }` : __eafl( 'Condition' ),
                asin: condition.amazon_asin,
                name: condition.amazon_name || '',
                status: condition.amazon_status || '',
                url: condition.amazon_link || condition.url || '',
            });
        });
    }

    return products;
};

export default {
    getColumns( links ) {
        let categories = eafl_admin_manage_modal.categories.map(cat => { return {id: cat.term_id, label: `${cat.name} (${cat.count})` } } );
        categories.sort((a,b) => a.label.localeCompare(b.label));

        let columns = [
            bulkEditCheckbox( links ),
            {
                Header: __eafl( 'Sort:' ),
                id: 'actions',
                headerClassName: 'eafl-admin-table-help-text',
                sortable: false,
                width: 100,
                Filter: () => (
                    <div>
                        { __eafl( 'Filter:' ) }
                    </div>
                ),
                Cell: row => (
                    <div className="eafl-admin-manage-actions">
                        <Icon
                            type="edit"
                            title={ __eafl( 'Edit Link' ) }
                            onClick={() => {
                                EAFL_Modal.open('edit', { link: row.original, saveCallback: () => links.refreshData() });
                            }}
                        />
                        <Icon
                            type="clone"
                            title={ __eafl( 'Clone Link' ) }
                            onClick={() => {
                                // Create a copy of the link data without the ID
                                const clonedLink = { ...row.original };
                                delete clonedLink.id;
                                
                                EAFL_Modal.open('create', { clone: clonedLink, saveCallback: () => links.refreshData() });
                            }}
                        />
                        <Icon
                            type="delete"
                            title={ __eafl( 'Delete Link' ) }
                            onClick={() => {
                                if( confirm( `${ __eafl( 'Are you sure you want to delete' ) } "${row.original.name}"?` ) ) {
                                    Api.link.delete(row.original.id).then(() => links.refreshData());
                                }
                            }}
                        />
                    </div>
                ),
            },{
                Header: __eafl( 'ID' ),
                id: 'id',
                accessor: 'id',
                width: 65,
                Filter: (props) => (<TextFilter {...props}/>),
            },{
                Header: __eafl( 'Active' ),
                id: 'active',
                accessor: 'active',
                width: 100,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Status' ) }</option>
                        {
                            eafl_admin_manage_modal.options.active.map((option, index) => (
                                <option value={option.value} key={index}>{ option.label }</option>
                            ))
                        }
                    </select>
                ),
                Cell: row => {
                    const option = eafl_admin_manage_modal.options.active.find((option) => option.value === row.value );
                    const isInactive = 'no' === row.value;
                    return (
                        <div style={{ color: isInactive ? 'darkred' : 'inherit' }}>
                            {
                                option
                                && option.label
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Type' ),
                id: 'type',
                accessor: 'type',
                width: 120,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Type' ) }</option>
                        {
                            eafl_admin_manage_modal.options.type.map((option, index) => (
                                <option value={option.value} key={index}>{ option.label }</option>
                            ))
                        }
                    </select>
                ),
                Cell: row => {
                    const option = eafl_admin_manage_modal.options.type.find((option) => option.value === row.value );
                    return (
                        <div>
                            {
                                option
                                && option.label
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Date' ),
                id: 'date',
                accessor: 'date',
                width: 150,
                Filter: (props) => (<TextFilter {...props}/>),
            },{
                Header: __eafl( 'Categories' ),
                id: 'categories',
                accessor: 'categories',
                sortable: false,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <optgroup label={ __eafl( 'General' ) }>
                            <option value="all">{ __eafl( 'All Categories' ) }</option>
                            <option value="none">{ __eafl( 'No Categories' ) }</option>
                            <option value="any">{ __eafl( 'Any Categories' ) }</option>
                        </optgroup>
                        <optgroup label={ __eafl( 'Terms' ) }>
                            {
                                categories.map((term, index) => (
                                    <option value={term.id} key={index}>{ he.decode(term.label) }</option>
                                ))
                            }
                        </optgroup>
                    </select>
                ),
                Cell: row => {
                    const names = row.value.map(t => t.name);
        
                    return (
                        <div>{ he.decode( names.join(', ') ) }</div>
                    )
                },
                width: 300,
            },{
                Header: __eafl( 'Name' ),
                id: 'name',
                accessor: 'name',
                width: 300,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => he.decode(row.value),
            },{
                Header: __eafl( 'Description' ),
                id: 'description',
                accessor: 'description',
                width: 300,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => {
                    if (!row.value || row.value.trim() === '') {
                        return <div></div>;
                    }
                    const decodedValue = he.decode(row.value);
                    const formattedValue = decodedValue.replace(/\n/g, '<br>');
                    return <div dangerouslySetInnerHTML={{ __html: formattedValue }} />;
                },
            },{
                Header: __eafl( 'Replacement' ),
                id: 'replacement',
                accessor: 'replacement',
                width: 200,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Links' ) }</option>
                        <option value="yes">{ __eafl( 'Has Replacement' ) }</option>
                        <option value="no">{ __eafl( 'No Replacement' ) }</option>
                    </select>
                ),
                Cell: row => {
                    const hasReplacement = false !== row.value && 0 < row.value;

                    return (
                        <div className="eafl-admin-table-replacement-container">
                            {
                                hasReplacement
                                ?
                                <Fragment>
                                    <Icon
                                        type="unlink"
                                        required="premium"
                                        title={ __eafl( 'Unlink replacement. Will use the destination of this link again.' ) }
                                        onClick={() => {
                                            Api.link.save(false, {
                                                id: row.original.id,
                                                replacement: false,
                                            }).then(() => links.refreshData());
                                        }}
                                    />{ row.value } - { row.original.replacement_name }
                                </Fragment>
                                :
                                <Icon
                                    type="link"
                                    required="premium"
                                    title={ __eafl( 'Pick a replacement to use instead of this link.' ) }
                                    onClick={() => {
                                        EAFL_Modal.open('insert', {
                                            insertCallback: ( link, text ) => {
                                                Api.link.save(false, {
                                                    id: row.original.id,
                                                    replacement: link.id,
                                                }).then(() => links.refreshData());
                                            },
                                        });
                                    }}
                                />
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Clicks' ),
                id: 'clicks',
                accessor: 'clicks',
                width: 120,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => {
                    return (
                        <div className="eafl-admin-manage-links-clicks-container">
                            <div
                                style={ 0 < row.value.all ? {} : { visibility: 'hidden' }}
                            >
                                <Icon
                                    type="delete"
                                    title={ __eafl( 'Reset Clicks' ) }
                                    onClick={() => {
                                        if(confirm(`${ __eafl( 'Are you sure you want to reset the clicks for' ) } "${row.original.name}"?`)) {
                                            Api.click.deleteFor(row.original.id).then(() => links.refreshData());
                                        }
                                    }}
                                />
                            </div>
                            <div>
                                <div>{ row.value.month } <span className="eafl-admin-manage-links-clicks-text">{ __eafl( 'This month' ) }</span></div>
                                <div>{ row.value.all } <span className="eafl-admin-manage-links-clicks-text">{ __eafl( 'Lifetime' ) }</span></div>
                            </div>
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Text' ),
                id: 'text',
                accessor: 'text',
                width: 300,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    return (
                        <div>{ row.value[0] }</div>
                    )
                },
            },{
                Header: __eafl( 'Cloaking' ),
                id: 'cloak',
                accessor: 'cloak',
                width: 250,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Cloaking' ) }</option>
                        {
                            eafl_admin_manage_modal.options.cloak.map((option, index) => (
                                <option value={option.value} key={index}>{ option.label }</option>
                            ))
                        }
                    </select>
                ),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    const option = eafl_admin_manage_modal.options.cloak.find((option) => option.value === row.value );
                    return (
                        <div>
                            {
                                option
                                && option.label
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Target' ),
                id: 'target',
                accessor: 'target',
                width: 250,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Target' ) }</option>
                        {
                            eafl_admin_manage_modal.options.target.map((option, index) => (
                                <option value={option.value} key={index}>{ option.label }</option>
                            ))
                        }
                    </select>
                ),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    const option = eafl_admin_manage_modal.options.target.find((option) => option.value === row.value );
                    return (
                        <div>
                            {
                                option
                                && option.label
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Redirect Type' ),
                id: 'redirect_type',
                accessor: 'redirect_type',
                width: 250,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Redirect Type' ) }</option>
                        {
                            eafl_admin_manage_modal.options.redirect_type.map((option, index) => (
                                <option value={option.value} key={index}>{ option.label }</option>
                            ))
                        }
                    </select>
                ),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    const option = eafl_admin_manage_modal.options.redirect_type.find((option) => option.value == row.value );
                    return (
                        <div>
                            {
                                option
                                && option.label
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Nofollow' ),
                id: 'nofollow',
                accessor: 'nofollow',
                width: 250,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Nofollow' ) }</option>
                        {
                            eafl_admin_manage_modal.options.nofollow.map((option, index) => (
                                <option value={option.value} key={index}>{ option.label }</option>
                            ))
                        }
                    </select>
                ),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    const option = eafl_admin_manage_modal.options.nofollow.find((option) => option.value === row.value );
                    return (
                        <div>
                            {
                                option
                                && option.label
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Sponsored' ),
                id: 'sponsored',
                accessor: 'sponsored',
                width: 120,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Sponsored' ) }</option>
                        <option value="0">{ __eafl( 'Not Sponsored' ) }</option>
                        <option value="1">{ __eafl( 'Sponsored' ) }</option>
                    </select>
                ),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    return (
                        <div>
                            {
                                row.value
                                ?
                                __eafl( 'Sponsored' )
                                :
                                __eafl( 'Not Sponsored' )
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'UGC' ),
                id: 'ugc',
                accessor: 'ugc',
                width: 100,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any UGC' ) }</option>
                        <option value="0">{ __eafl( 'Not UGC' ) }</option>
                        <option value="1">{ __eafl( 'UGC' ) }</option>
                    </select>
                ),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    return (
                        <div>
                            {
                                row.value
                                ?
                                __eafl( 'UGC' )
                                :
                                __eafl( 'Not UGC' )
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Shortlink' ),
                id: 'shortlink',
                accessor: 'shortlink',
                width: 400,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }

                    const cloak = eafl_admin_manage_modal.options.cloak.find((option) => option.value === row.original.cloak );
                    if ( cloak && 'no' === cloak.actual ) {
                        return null;
                    }

                    return (
                        <div className="eafl-admin-table-url-container">
                            <CopyToClipboardIcon text={row.value} />
                            <a href={row.value} target="_blank" className="eafl-admin-table-links-shortlink">{row.value}</a>
                        </div>
                    )
                },
            },{
                Header: __eafl( 'URL' ),
                id: 'url',
                accessor: 'url',
                width: 400,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    if ( ! row.value ) { return null; }

                    return (
                        <div className="eafl-admin-table-url-container">
                            <CopyToClipboardIcon text={row.value} />
                            <a href={row.value} target="_blank" className="eafl-admin-table-links-url">{row.value}</a>
                        </div>
                    )
                },
            },{
                Header: __eafl( 'HTML' ),
                id: 'html',
                accessor: 'html',
                width: 300,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => {
                    if ( 'html' !== row.original.type ) { return null; }

                    return (
                        <div className="eafl-admin-table-html-container"><pre>{ row.value }</pre></div>
                    )
                },
            },{
                Header: __eafl( 'Amazon Product' ),
                id: 'amazon_product',
                accessor: 'amazon_name',
                width: 350,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => {
                    if ( 'amazon' !== row.original.type ) { return null; }
                    if ( ! row.original.amazon_asin ) { return null; }

                    return (
                        <div className="eafl-admin-table-amazon-product">
                            {
                                row.original.amazon_image
                                &&
                                <div className="eafl-admin-table-amazon-product-image">
                                    <img
                                        src={ row.original.amazon_image }
                                        alt={ row.original.amazon_name }
                                    />
                                </div>
                            }
                            <div className="eafl-admin-table-amazon-product-details">
                                <div className="eafl-admin-table-amazon-product-name">
                                    { he.decode( row.original.amazon_name || '' ) }
                                </div>
                                {
                                    row.original.amazon_price
                                    &&
                                    <div className="eafl-admin-table-amazon-product-price">
                                        { row.original.amazon_price }
                                    </div>
                                }
                                <div className="eafl-admin-table-amazon-product-asin">
                                    ASIN: { row.original.amazon_asin }
                                </div>
                            </div>
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Amazon Product Status' ),
                id: 'amazon_status',
                accessor: row => {
                    const products = getAmazonStatusProducts( row );
                    return products.length ? products.map( product => product.status || 'UNKNOWN' ).join( '|' ) : 'empty';
                },
                width: 260,
                sortable: false,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Show All' ) }</option>
                        <option value="IN_STOCK">{ __eafl( 'In Stock' ) }</option>
                        <option value="IN_STOCK_SCARCE">{ __eafl( 'In Stock (Scarce)' ) }</option>
                        <option value="OUT_OF_STOCK">{ __eafl( 'Out of Stock' ) }</option>
                        <option value="UNAVAILABLE">{ __eafl( 'Unavailable' ) }</option>
                        <option value="PREORDER">{ __eafl( 'Preorder' ) }</option>
                        <option value="AVAILABLE_DATE">{ __eafl( 'Available Date' ) }</option>
                        <option value="LEADTIME">{ __eafl( 'Leadtime' ) }</option>
                        <option value="UNKNOWN">{ __eafl( 'Unknown' ) }</option>
                        <option value="NOT_FOUND">{ __eafl( 'Not Found' ) }</option>
                        <option value="empty">{ __eafl( 'No Amazon Product' ) }</option>
                        <option value="">----------------</option>
                        <option value="notification_statuses">{ __eafl( 'Notification statuses' ) }</option>
                        <option value="not_in_stock">{ __eafl( 'Any status except "In Stock"' ) }</option>
                    </select>
                ),
                Cell: row => {
                    const products = getAmazonStatusProducts( row.original );

                    if ( ! products.length ) {
                        return null;
                    }

                    return (
                        <div className="eafl-admin-table-amazon-status-list">
                            {
                                products.map((product, index) => {
                                    if ( ! product.status ) {
                                        return (
                                            <div className="eafl-admin-table-amazon-status-row" key={index}>
                                                <span className="eafl-admin-table-amazon-status-label">{ product.label }</span>
                                                <span className="eafl-admin-table-amazon-status-none">?</span>
                                            </div>
                                        );
                                    }

                                    const statusInfo = amazonStatusTypes[ product.status ] || { label: product.status, className: 'status-unknown' };

                                    return (
                                        <div className={ `eafl-admin-table-amazon-status-row ${ statusInfo.className }` } key={index}>
                                            <span className="eafl-admin-table-amazon-status-label">{ product.label }</span>
                                            <span className="eafl-admin-table-amazon-status-type">{ statusInfo.label }</span>
                                            {
                                                product.url
                                                &&
                                                <a
                                                    href={ product.url }
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="eafl-admin-table-amazon-status-link"
                                                    onClick={(e) => e.stopPropagation()}
                                                >
                                                    <Icon type="link" title={ __eafl( 'View product on Amazon' ) }/>
                                                </a>
                                            }
                                        </div>
                                    );
                                })
                            }
                        </div>
                    );
                },
            },{
                Header: __eafl( 'Conditional' ),
                id: 'conditional',
                accessor: 'conditional',
                width: 400,
                Filter: (props) => (<TextFilter {...props}/>),
                Cell: row => {
                    if ( ! row.value ) { return null; }

                    return (
                        <div className="eafl-admin-table-conditional-container">
                            {
                                row.value.map( ( condition ) => (
                                    <div className="eafl-admin-table-condition-container">
                                        <strong>{ condition.value } </strong>
                                        {
                                            'html' === row.original.type
                                            ?
                                            <div className="eafl-admin-table-html-container"><pre>{ condition.html }</pre></div>
                                            :
                                            <div className="eafl-admin-table-url-container">
                                                <a href={row.value} target="_blank" className="eafl-admin-table-links-url">{ condition.url }</a>
                                            </div>
                                        }
                                    </div>
                                ) )
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Status' ),
                id: 'status',
                accessor: 'status',
                width: 200,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Status' ) }</option>
                        <option value="all-good">{ __eafl( 'Any Good Status' ) }</option>
                        <option value="all-bad">{ __eafl( 'Any Bad Status' ) }</option>
                        <option value="unknown">{ __eafl( 'Status Unknown' ) }</option>
                        {
                            statusTypes.map((group, groupIndex) => (
                                <optgroup label={ group.label } key={ groupIndex }>
                                    {
                                        Object.keys( group.types ).map( (type, index) => (
                                            <option value={ type } key={ index }>{ group.types[ type ] }</option>
                                        ))
                                    }
                                </optgroup>
                            ))
                        }
                    </select>
                ),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    const type = row.value && row.value.hasOwnProperty( 'type' ) ? row.value.type : 'unknown';

                    return (
                        <div className="eafl-admin-table-status-container">
                            <Icon
                                type="reload"
                                required="premium"
                                title={ __eafl( 'Update Status for this link. Bulk Edit can be used to check for multiple links at once.' ) }
                                onClick={() => {
                                    Api.linksChecker.check( [ row.original.id ] ).then(() => links.refreshData());
                                }}
                            />
                            {
                                statusTypesFlat.hasOwnProperty( type )
                                ?
                                statusTypesFlat[ type ]
                                :
                                row.value.type
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Status Details' ),
                id: 'status_details',
                accessor: 'status',
                width: 400,
                filterable: false,
                sortable: false,
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    const type = row.value && row.value.hasOwnProperty( 'type' ) ? row.value.type : 'unknown';

                    if ( 'unknown' === type || 'issue' === type ) {
                        return null;
                    }

                    return (
                        <div>
                            {
                                typeof row.value.information === 'object'
                                ?
                                Object.keys(row.value.information).map((field, index) => (
                                    <div key={ index }><strong>{ field }:</strong> { typeof row.value.information[ field ] === 'object' ? '{}' : row.value.information[ field ].toString() }</div>
                                ))
                                :
                                row.value.information
                            }
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Status Emails' ),
                id: 'status_ignore',
                accessor: 'status_ignore',
                width: 200,
                sortable: false,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Any Status' ) }</option>
                        <option value="0">{ __eafl( 'Email if broken' ) }</option>
                        <option value="1">{ __eafl( 'Ignore when broken' ) }</option>
                    </select>
                ),
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    const ignore = row.value ? '1' : '0';

                    return (
                        <div>
                            <select
                                onChange={event => {
                                    Api.link.save(false, {
                                        id: row.original.id,
                                        status_ignore: event.target.value,
                                    }).then(() => links.refreshData());
                                }}
                                style={{ width: '100%', fontSize: '1em' }}
                                value={ ignore }
                            >
                                <option value="0">{ __eafl( 'Email if broken' ) }</option>
                                <option value="1">{ __eafl( 'Ignore when broken' ) }</option>
                            </select>
                        </div>
                    )
                },
            },{
                Header: __eafl( 'Status Updated' ),
                id: 'status_timestamp',
                accessor: 'status',
                width: 110,
                filterable: false,
                Cell: row => {
                    if ( 'html' === row.original.type ) { return null; }
                    const timestamp = row.value && row.value.hasOwnProperty( 'timestamp' ) ? row.value.timestamp : false;
                    let date;

                    if ( timestamp ) {
                        date = new Date( timestamp * 1000 );
                    }

                    return (
                        <div>
                            {
                                false === timestamp
                                ?
                                __eafl( 'Never' )
                                :
                                [
                                    date.getFullYear(),
                                    ('0' + (date.getMonth() + 1)).slice(-2),
                                    ('0' + date.getDate()).slice(-2)
                                ].join('-')
                            }
                        </div>
                    )
                },
            }
        ];

        // WP Ultimate Post Grid integration.
        if ( window.hasOwnProperty( 'wpupg_admin' ) ) {
            columns.push({
                Header: __eafl( 'Grid Image' ),
                id: 'wpupg_custom_image_id',
                accessor: 'wpupg_custom_image_id',
                width: 110,
                Filter: ({ filter, onChange }) => (
                    <select
                        onChange={event => onChange(event.target.value)}
                        style={{ width: '100%', fontSize: '1em' }}
                        value={filter ? filter.value : 'all'}
                    >
                        <option value="all">{ __eafl( 'Show All' ) }</option>
                        <option value="yes">{ __eafl( 'Has Image' ) }</option>
                        <option value="no">{ __eafl( 'Does not have Image' ) }</option>
                    </select>
                ),
                Cell: row => {
                    const selectImage = (e) => {
                        e.preventDefault();
                                
                        Media.selectImage((attachment) => {
                            Api.link.save(false, {
                                id: row.original.id,
                                wpupg_custom_image_id: attachment.id,
                            }).then(() => links.refreshData());
                        });
                    };

                    return (
                        <div className="eafl-manage-image-container">
                            {
                                row.value
                                ?
                                <div className="eafl-manage-image-preview">
                                    <Tooltip content={ __eafl( 'Edit Image' ) }>
                                        <img
                                            src={ row.original.wpupg_custom_image_url }
                                            width="80"
                                            onClick={ selectImage }
                                        />
                                    </Tooltip>
                                    <Icon
                                        type="trash"
                                        title={ __eafl( 'Remove Image' ) }
                                        onClick={ () => {
                                            Api.link.save(false, {
                                                id: row.original.id,
                                                wpupg_custom_image_id: 0,
                                            }).then(() => links.refreshData());
                                        } }
                                    />
                                </div>
                                :
                                <Icon
                                    type="photo"
                                    title={ __eafl( 'Add Image' ) }
                                    onClick={ selectImage }
                                />
                            }
                        </div>
                    )
                },
            });
        }

        return columns;
    }
};
