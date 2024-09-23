export function __eafl( text, domain = 'easy-affiliate-links' ) {
    if ( eafl_admin.translations.hasOwnProperty( text ) ) {
        return eafl_admin.translations[ text ];
    } else {
        return text;
    }
};
