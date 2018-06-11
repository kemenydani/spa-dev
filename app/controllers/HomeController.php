<?php

namespace controllers;

use core\Config;
use models\Product;
use models\ProductCollection;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use models\ArticleCollection;
use models\SquadCollection;
use models\MatchCollection;
use models\EventCollection;
use models\PartnerCollection;

class HomeController extends ViewController
{
    public function index ( Request $request, Response $response )
    {
        $q1 = " SELECT * FROM _xyz_article art " .
              " WHERE art.highlighted = ? "      .
              " ORDER BY art.date_created DESC " .
              " LIMIT 2 "
        ;

        $hlArticles  = (ArticleCollection::queryToCollection($q1, 1))->getFormatted();

        $q3 = " SELECT * FROM _xyz_squad sq " .
	          " WHERE sq.featured = ? "       .
	          " ORDER BY sq.featured DESC "
        ;
        
        $squadCollection = SquadCollection::queryToCollection($q3, 1);

        $q4 = " SELECT * FROM _xyz_match mc " .
              " ORDER BY mc.id DESC "         .
              " LIMIT 5 "
        ;

        $q5 = " SELECT * FROM _xyz_match mc " .
            " WHERE mc.featured = ? "         .
            " ORDER BY mc.id DESC "           .
            " LIMIT 3 "
        ;

        $ltMatches = MatchCollection::queryToCollection($q4);
        $topMatches = MatchCollection::queryToCollection($q5, true);

        $q6 = " SELECT * FROM _xyz_event ev " .
              " ORDER BY ev.start_date DESC " .
              " LIMIT 5 "
        ;

        $ltEvents = EventCollection::queryToCollection($q6);

        $q7 = " SELECT * FROM _xyz_partner pt " .
              " WHERE featured_top = ? "        .
              " ORDER BY pt.id ASC"             .
              " LIMIT 4 "
        ;

        $partnersTop = PartnerCollection::queryToCollection($q7, true);

        $featuredItems = [];

        $q9 = " SELECT * FROM _xyz_product pr " .
              " WHERE featured = ? "            .
              " ORDER BY pr.id DESC "           .
              " LIMIT 10 "
        ;

        $randomFeaturedItem = null;

        if(Config::instance()->get('page_show_featured_item', 0) == 1)
        {
            $featuredProducts = ProductCollection::queryToCollection($q9, true);

            foreach($featuredProducts->getModels() as $Product) $featuredItems[] = $Product;

            if(count($featuredItems))
            {
                $randKey = array_rand($featuredItems, 1);
                $randomModel = $featuredItems[$randKey];

                if(is_a($randomModel, Product::class))
                {
                    /* @var \models\Product $Product */
                    $Product = $randomModel;
                    $randomFeaturedItem['type']   = 'product';
                    $randomFeaturedItem['name']   = $Product->getName();
                    $randomFeaturedItem['image']  = $Product->getPreviewImage()->requestImageUrl();
                    $randomFeaturedItem['teaser'] = $Product->getPrice() . ' ' . $Product->getCurrency();
                    $href = '/product/' . $Product->getId();
                    $randomFeaturedItem['button_next'] = "<a class='more-button' href='".$href."'>Buy Now</a>";
                }
            }
        }

        $ltLimit = $randomFeaturedItem ? 4 : 6;

        $q2 = " SELECT * FROM _xyz_article art " .
              " WHERE art.highlighted = ? "      .
              " ORDER BY art.date_created DESC " .
              " LIMIT $ltLimit "
        ;
        $ltArticles  = (ArticleCollection::queryToCollection($q2, 0))->getFormatted();

        $this->view->render($response, 'route.view.home.html.twig', [
            'articles' => [
                'hl' => $hlArticles,
                'lt' => $ltArticles,
            ],
            'featuredItem' => $randomFeaturedItem,
            'ltEvents' => $ltEvents,
            'topMatches' => $topMatches,
            'matches' => $ltMatches,
	        'squads' => $squadCollection,
            'partnersTop' => $partnersTop,
            //'partnersBot' => $partnersBot,
        ]);
    }
}