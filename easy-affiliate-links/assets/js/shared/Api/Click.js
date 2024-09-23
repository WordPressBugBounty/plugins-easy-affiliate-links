const clickEndpoint = eafl_admin.endpoints.click;

import ApiWrapper from '../ApiWrapper';

export default {
    delete(id) {
        return ApiWrapper.call( `${clickEndpoint}/${id}`, 'DELETE' );
    },
    deleteFor(linkId) {
        return ApiWrapper.call( `${clickEndpoint}/link/${linkId}`, 'DELETE' );
    },
};
