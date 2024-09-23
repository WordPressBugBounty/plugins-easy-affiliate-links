const linkEndpoint = eafl_admin.endpoints.link;

import ApiWrapper from '../ApiWrapper';

export default {
    get(id) {
        return ApiWrapper.call( `${linkEndpoint}/${id}` );
    },
    save(asNewLink, link) {
        const data = {
            'post_status': 'publish',
            link,
        };
        const url = asNewLink ? linkEndpoint : `${linkEndpoint}/${link.id}`;

        return ApiWrapper.call( url, 'POST', data );
    },
    delete(id) {
        return ApiWrapper.call( `${linkEndpoint}/${id}`, 'DELETE' );
    },
};
