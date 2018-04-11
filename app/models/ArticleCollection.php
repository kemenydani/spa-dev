<?php

namespace models;

use core\ModelCollection;
use models\Article as Article;

class ArticleCollection extends ModelCollection
{
    public function __construct(array $models)
    {
        parent::__construct(self::parseModels($models, Article::class));
    }


}
