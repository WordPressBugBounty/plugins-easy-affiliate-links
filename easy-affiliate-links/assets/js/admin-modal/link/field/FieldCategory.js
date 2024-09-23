import React, { Component } from 'react';
import he from 'he';
import CreatableSelect from 'react-select/creatable';

import { __eafl } from 'Shared/Translations';

export default class FieldCategory extends Component {
    shouldComponentUpdate(nextProps) {
        return JSON.stringify(this.props.value) !== JSON.stringify(nextProps.value);
    }

    render() {
        const categories = eafl_admin_manage_modal.categories;
        let categoryOptions = [];
        let selectedCategories = [];

        for ( let category of categories ) {
            const categoryOption = {
                value: category.term_id,
                label: he.decode( category.name ),
            };

            categoryOptions.push(categoryOption);

            if ( this.props.value.find((elem) => elem.term_id === category.term_id || elem.name === category.term_id ) ) {
                selectedCategories.push(categoryOption);
            }
        }

        const customProps = this.props.custom ? this.props.custom : {};

        return (
            <CreatableSelect
                isMulti
                options={categoryOptions}
                value={selectedCategories}
                placeholder={ __eafl( 'Select from list or type to create...' ) }
                onChange={(value) => {
                    let newValue = [];

                    for ( let category of value ) {
                        if ( category.hasOwnProperty('__isNew__') && category.__isNew__ ) {
                            eafl_admin_manage_modal.categories.push({
                                term_id: category.label,
                                name: category.label,
                            });
                        }

                        let selectedCategory = eafl_admin_manage_modal.categories.find((cat) => cat.term_id === category.value);

                        if ( selectedCategory ) {
                            newValue.push(selectedCategory);
                        }
                    }

                    this.props.onChange(newValue);
                }}
                styles={{
                    placeholder: (provided) => ({
                        ...provided,
                        color: '#444',
                        opacity: '0.333',
                    }),
                    control: (provided) => ({
                        ...provided,
                        backgroundColor: 'white',
                    }),
                    container: (provided) => ({
                        ...provided,
                        width: '100%',
                        maxWidth: this.props.width ? this.props.width : '100%',
                    }),
                }}
                { ...customProps }
            />
        );
    }
}