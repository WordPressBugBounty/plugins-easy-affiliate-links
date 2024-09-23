const searchEndpoint = eafl_admin.endpoints.search;

import ApiWrapper from '../ApiWrapper';

export default {
    links(search) {
        const data = {
            search,
        };

        return ApiWrapper.call( `${searchEndpoint}/links`, 'POST', data );
    },
};
