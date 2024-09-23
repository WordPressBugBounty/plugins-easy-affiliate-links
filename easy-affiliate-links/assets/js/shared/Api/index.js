const { hooks } = EasyAffiliateLinks.shared;

import Category from './Category';
import Click from './Click';
import General from './General';
import Manage from './Manage';
import Link from './Link';
import Search from './Search';

const api = hooks.applyFilters( 'api', {
    category: Category,
    click: Click,
    general: General,
    manage: Manage,
    link: Link,
    search: Search,
} );

export default api;