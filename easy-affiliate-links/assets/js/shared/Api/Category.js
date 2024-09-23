const categoryEndpoint = eafl_admin.endpoints.category;
const manageEndpoint = eafl_admin.endpoints.manage;

import ApiWrapper from '../ApiWrapper';

export default {
    get(id) {
        return ApiWrapper.call( `${categoryEndpoint}/${id}` );
    },
    create(name) {
        const data = {
            name,
        };

        return ApiWrapper.call( `${categoryEndpoint}`, 'POST', data );
    },
    delete(id) {
        return ApiWrapper.call( `${categoryEndpoint}/${id}?force=true`, 'DELETE' );
    },
    rename(id, name) {
        const data = {
            name,
        };

        return ApiWrapper.call( `${categoryEndpoint}/${id}`, 'POST', data );
    },
    merge(oldId, newId) {
        const data = {
            oldId,
            newId,
        };

        return ApiWrapper.call( `${manageEndpoint}/taxonomy/merge`, 'POST', data );
    },
};
