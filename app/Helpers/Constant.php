<?php

namespace App\Helpers;

interface Constant
{
    const DEFAULT_PER_PAGE = 50;
    const DEFAULT_PER_PAGE_HOME = 36;

    const REFERER = 'referer';

    const CURRENT_PAGE = 'current';

    const PAGE_SIZE = 'page_size';


    const LIST_DOMAIN_MANGA_CACHE_KEY = 'list_domain_manga_cache_key';

    const DEFAULT_PASSWORD_SOCIALITE = 'Manga@socialite1!';

    const SOCIALITE_FACEBOOK = 'facebook';
    const SOCIALITE_GOOGLE = 'google';
    const EMAIL = 'email';

    const SOCIALITE_LIST = [
        self::SOCIALITE_GOOGLE,
        self::SOCIALITE_FACEBOOK,
    ];
}
