<?php

/*
 * This file is part of matteociaroni/flarum-unshort-urls.
 *
 * Copyright (c) 2023 Matteo Ciaroni.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace MatteoCiaroni\UnShortURLs;

use Flarum\Extend;
use Flarum\Post\Event\Saving;

return [
    
    (new Extend\Frontend("admin"))
		->js(__DIR__."/js/dist/admin.js"),
        
    new Extend\Locales(__DIR__."/locale"),

	(new Extend\Settings)
		->default("matteociaroni-unshort-urls.domains", ""),

	(new Extend\Event)
		->listen(Saving::class, ChangePostText::class),
];
