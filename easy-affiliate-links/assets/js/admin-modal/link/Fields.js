import React, { Fragment } from 'react';

import Field from './field';
import { __eafl } from 'Shared/Translations';
 
const Fields = (props) => {
    const selectedCloakOption = eafl_admin_manage_modal.options.cloak.find((option) => option.value === props.link.cloak);
    const cloakedLink = 'yes' === selectedCloakOption.actual;

    return (
        <div className="eafl-admin-modal-link-fields">
            <div className="eafl-admin-modal-link-fields-group eafl-admin-modal-link-fields-group-organization">
                <div className="eafl-admin-modal-link-fields-group-header">{ __eafl( 'Organization' ) }</div>
                <div className="eafl-admin-modal-link-fields">
                    <Field
                        id="name"
                        label={ __eafl( 'Name' ) }
                        type="text"
                        value={props.link.name}
                        onChange={(value) => {
                            props.onLinkChange('name', value);
                        }}
                    />
                    <Field
                        id="description"
                        label={ __eafl( 'Description' ) }
                        type="textarea"
                        value={props.link.description}
                        onChange={(value) => {
                            props.onLinkChange('description', value);
                        }}
                    />
                    <Field
                        id="categories"
                        label={ __eafl( 'Categories' ) }
                        type="categories"
                        value={props.link.categories}
                        onChange={(value) => {
                            props.onLinkChange('categories', value);
                        }}
                    />
                </div>
            </div>
            <div className="eafl-admin-modal-link-fields-group eafl-admin-modal-link-fields-group-details">
                <div className="eafl-admin-modal-link-fields-group-header">{ __eafl( 'Details' ) }</div>
                <div className="eafl-admin-modal-link-fields">
                    <Field
                        id="type"
                        label={ __eafl( 'Link Type' ) }
                        type="dropdown"
                        value={props.link.type}
                        onChange={(value) => {
                            props.onLinkChange('type', value);
                        }}
                        options={eafl_admin_manage_modal.options.type}
                    />
                    {
                        'text' === props.link.type
                        &&
                        <Fragment>
                            <Field
                                id="url"
                                label={ __eafl( 'Link Destination URL' ) }
                                type="text"
                                value={props.link.url}
                                onChange={(value) => {
                                    props.onLinkChange('url', value);
                                }}
                            />
                            <Field
                                id="conditional"
                                hook="conditional"
                                label={ __eafl( 'Conditional URLs' ) }
                                type="custom"
                                conditional={ props.link.conditional }
                                linkType={ props.link.type }
                                onChange={ (value) => {
                                    props.onLinkChange('conditional', value);
                                } }
                            >
                                <p>{ __eafl( 'Available in Easy Affiliate Links Premium.' ) } <a href="https://bootstrapped.ventures/easy-affiliate-links/conditional-links/" target="_blank">{ __eafl( 'Learn more' ) }</a>!</p>
                            </Field>
                            <Field
                                id="cloak"
                                label={ __eafl( 'Link Cloaking' ) }
                                type="radio"
                                options={eafl_admin_manage_modal.options.cloak}
                                value={props.link.cloak}
                                onChange={(value) => {
                                    props.onLinkChange('cloak', value);
                                }}
                            />
                            {
                                cloakedLink
                                &&
                                <Field
                                    id="slug"
                                    label={ __eafl( 'Shortlink Slug' ) }
                                    type="slug"
                                    value={props.link.slug}
                                    onChange={(value) => {
                                        props.onLinkChange('slug', value);
                                    }}
                                />
                            }
                        </Fragment>
                    }
                    {
                        'html' === props.link.type
                        &&
                        <Fragment>
                            <Field
                                id="html"
                                label={ __eafl( 'HTML Code' ) }
                                type="textarea"
                                value={props.link.html}
                                onChange={(value) => {
                                    props.onLinkChange('html', value);
                                }}
                            />
                            <Field
                                id="conditional"
                                hook="conditional"
                                label={ __eafl( 'Conditional HTML Code' ) }
                                type="custom"
                                conditional={ props.link.conditional }
                                linkType={ props.link.type }
                                onChange={ (value) => {
                                    props.onLinkChange('conditional', value);
                                } }
                            >
                                <p>{ __eafl( 'Available in Easy Affiliate Links Premium.' ) } <a href="https://bootstrapped.ventures/easy-affiliate-links/conditional-links/" target="_blank">{ __eafl( 'Learn more' ) }</a>!</p>
                            </Field>
                        </Fragment>
                    }
                </div>
            </div>
            {
                'text' === props.link.type
                &&
                <Fragment>
                    <div className="eafl-admin-modal-link-fields-group eafl-admin-modal-link-fields-group-shortcode">
                        <div className="eafl-admin-modal-link-fields-group-header">{ __eafl( 'Output' ) }</div>
                        <div className="eafl-admin-modal-link-fields">
                            <Field
                                id="text"
                                label={ __eafl( 'Default Link Text' ) }
                                type="textVariants"
                                value={props.link.text}
                                onChange={(value) => {
                                    props.onLinkChange('text', value);
                                }}
                            />
                            <Field
                                id="text"
                                label={ __eafl( 'Additional CSS Classes' ) }
                                type="text"
                                value={ props.link.classes }
                                onChange={(value) => {
                                    props.onLinkChange('classes', value);
                                }}
                            />
                        </div>
                    </div>
                    <div className="eafl-admin-modal-link-fields-group eafl-admin-modal-link-fields-group-properties">
                        <div className="eafl-admin-modal-link-fields">
                            <Field
                                id="target"
                                label={ __eafl( 'Target' ) }
                                type="radio"
                                options={eafl_admin_manage_modal.options.target}
                                value={props.link.target}
                                onChange={(value) => {
                                    props.onLinkChange('target', value);
                                }}
                            />
                            <Field
                                id="redirect_type"
                                label={ __eafl( 'Redirect Type' ) }
                                type="radio"
                                options={eafl_admin_manage_modal.options.redirect_type}
                                value={props.link.redirect_type}
                                onChange={(value) => {
                                    props.onLinkChange('redirect_type', value);
                                }}
                            />
                            <div className="eafl-admin-modal-link-field-container eafl-admin-modal-link-field-container-nofollow">
                                <Field
                                    id="nofollow"
                                    label={ __eafl( 'Nofollow' ) }
                                    type="radio"
                                    options={eafl_admin_manage_modal.options.nofollow}
                                    value={props.link.nofollow}
                                    onChange={(value) => {
                                        props.onLinkChange('nofollow', value);
                                    }}
                                />
                                <div className="eafl-admin-modal-link-field">
                                    <label htmlFor="eafl-admin-modal-link-field-rel-sponsored">
                                        <input
                                            type="checkbox"
                                            id="eafl-admin-modal-link-field-rel-sponsored"
                                            checked={ props.link.sponsored }
                                            onChange={(e) => {
                                                props.onLinkChange('sponsored',  e.target.checked);
                                            }}
                                        /> { __eafl( 'Use "sponsored" attribute' ) }
                                    </label>
                                    <br/>
                                    <label htmlFor="eafl-admin-modal-link-field-rel-ugc">
                                        <input
                                            type="checkbox"
                                            id="eafl-admin-modal-link-field-rel-ugc"
                                            checked={ props.link.ugc }
                                            onChange={(e) => {
                                                props.onLinkChange('ugc',  e.target.checked);
                                            }}
                                        /> { __eafl( 'Use "ugc" attribute' ) }
                                    </label>
                                </div> 
                            </div>
                        </div>
                    </div>
                </Fragment>
            }
        </div>
    );
}
export default Fields;