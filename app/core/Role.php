<?php

namespace core;

class Role
{

    const _PERMISSIONS_ = [
        'article.*' => 'Article Full',
        'article.create' => 'Create Article',
        'article.delete' => 'Delete Article'
    ];


}