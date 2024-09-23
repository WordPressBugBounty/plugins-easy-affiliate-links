import Api from 'Shared/Api';
import { __eafl } from 'Shared/Translations';

import ColumnsCategories from './categories/Columns';
import ColumnsClicks from './clicks/Columns';
import ColumnsLinks from './links/Columns';
import ColumnsRelations from './relations/Columns';

let datatables = {
    'links': {
        parent: __eafl( 'Links' ),
        title: __eafl( 'Overview' ),
        id: 'links',
        route: 'links',
        label: {
            singular: __eafl( 'Link' ),
            plural: __eafl( 'Links' ),
        },
        bulkEdit: {
            route: 'links',
            type: 'links',
        },
        createButton: (datatable) => {
            EAFL_Modal.open( 'create', {
                saveCallback: () => datatable.refreshData(),
            } );
        },
        selectedColumns: ['categories', 'name', 'clicks', 'shortlink', 'url'],
        columns: ColumnsLinks,
    },
    'usage': {
        parent: __eafl( 'Links' ),
        title: __eafl( 'Usage' ),
        id: 'usage',
        route: 'relations',
        selectedColumns: false,
        columns: ColumnsRelations,
    },
    'categories': {
        parent: __eafl( 'Links' ),
        title: __eafl( 'Categories' ),
        id: 'categories',
        route: 'categories',
        label: {
            singular: __eafl( 'Category' ),
            plural: __eafl( 'Categories' ),
        },
        bulkEdit: {
            route: 'categories',
            type: 'categories',
        },
        createButton: (datatable) => {
            let name = prompt( __eafl( 'What do you want to be the name of this new category?' ) );
            if( name && name.trim() ) {
                Api.category.create(name).then((data) => {
                    datatable.refreshData();
                    if ( ! data ) {
                        alert( __eafl( 'We were not able to create this category. Make sure it does not exist yet.' ) );
                    } else {
                        eafl_admin_manage_modal.categories.push({
                            term_id: data.id,
                            name: data.name,
                            count: 0,
                        });
                    }
                });
            }
        },
        selectedColumns: ['name', 'count'],
        columns: ColumnsCategories,
    },
    'clicks': {
        parent: __eafl( 'Clicks' ),
        title: __eafl( 'Overview' ),
        id: 'clicks',
        route: 'clicks',
        label: {
            singular: __eafl( 'Click' ),
            plural: __eafl( 'Clicks' ),
        },
        bulkEdit: {
            route: 'clicks',
            type: 'clicks',
        },
        selectedColumns: ['date', 'link_name', 'referer', 'user_id', 'ip', 'device_type'],
        columns: ColumnsClicks,
    }
}

export default datatables;
