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
		->default("matteociaroni-unshort-urls.domains", "")
		->default("matteociaroni-unshort-urls.timeout", 2)
		->default("matteociaroni-unshort-urls.max_iterations", 1),

	(new Extend\Event)
		->listen(Saving::class, ChangePostText::class),
];
