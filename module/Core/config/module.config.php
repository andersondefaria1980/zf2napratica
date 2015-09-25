<?php
return array(
    'di' => array(),
    'view_helpers' => array(
        'invokables'=> array(
            'session' => 'Core\View\Helper\Session',
            'cachedPartial' => 'Core\View\Helper\CachedPartial',
        )
    ),
);
