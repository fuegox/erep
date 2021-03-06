<?php

use \App\Controllers\User;
use \App\Controllers\Home;
use \App\Controllers\Company;
use \App\Controllers\Market;
use \App\Controllers\WorkOffers;

$ensureLogged = function($request, $response, $next) use ($app)
{
    $app->getContainer()->get("session")->ensureLogged();

    return $next($request, $response);
};

$app->get('/', function($request, $response, $args) use ($app) {
    $ct = new Home($app, $response);
    $ct->exec('showHomepage');
})->setName('home')->add($ensureLogged);

$app->get('/login', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->exec('showLogin');
})->setName('login');

$app->post('/login', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->json('doLogin');
});

$app->get('/logout', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->exec('logout');
})->setName('logout');

$app->get('/signup', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->exec('showSignup');
})->setName('signup');

$app->post('/signup', function($request, $response, $args) use ($app) {
    $ct = new User($app, $response);
    $ct->json('signup');
});


$app->group('', function () use ($app)
{
    $app->get('/work-offers', function($request, $response, $args) use ($app) {
        $ct = new WorkOffers($app, $response);
        $ct->exec('showList');
    })->setName('workOffers');

    $app->get('/wars', function($request, $response, $args) use ($app) {
        $ct = new Home($app, $response);
        $ct->exec('showWarList');
    })->setName('wars');

    $app->get('/mycompanies', function($request, $response, $args) use ($app) {
        $ct = new Company($app, $response);
        $ct->exec('showMyCompanies');
    })->setName('myCompanies');

    $app->get('/create-company', function($request, $response, $args) use ($app) {
        $ct = new Company($app, $response);
        $ct->exec('showCreate');
    })->setName('createCompany');

    $app->get('/storage', function($request, $response, $args) use ($app) {
        $ct = new User($app, $response);
        $ct->exec('showStorage');
    })->setName('storage');

    $app->get('/gyms', function($request, $response, $args) use ($app) {
        $ct = new User($app, $response);
        $ct->exec('showGyms');
    })->setName('gyms');

    $app->group('/marketplace', function () use ($app)
    {
        $app->get('', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->exec('showMarketplaceHome');
        })->setName('marketplace');

        $app->get('/{item}/{quality}', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->exec('showItemOffers', $args["item"], $args["quality"]);
        });
    });

})->add($ensureLogged);


/**
 * API calls, all outputs are expected to be JSON & formatted like:
 * {error: integer, result: mixed}
 * Controller.php#98 will take care of that automatically
 */
$app->group('/api', function () use ($app)
{
    $app->group('/user', function () use ($app)
    {
        $app->post('/work', function($request, $response, $args) use ($app) {
            $ct = new User($app, $response);
            $ct->json('work');
        });

        $app->post('/train', function($request, $response, $args) use ($app) {
            $ct = new User($app, $response);
            $ct->json('train');
        });
    });

    $app->group('/work_offers', function () use ($app)
    {
        $app->get('/list', function($request, $response, $args) use ($app) {
            $ct = new WorkOffers($app, $response);
            $ct->json('getList');
        });
        $app->post('/create', function($request, $response, $args) use ($app) {
            $ct = new WorkOffers($app, $response);
            $ct->json('create');
        });
        $app->post('/accept', function($request, $response, $args) use ($app) {
            $ct = new WorkOffers($app, $response);
            $ct->json('accept');
        });
        $app->post('/delete', function($request, $response, $args) use ($app) {
            $ct = new WorkOffers($app, $response);
            $ct->json('delete');
        });
    });

    $app->group('/company', function () use ($app)
    {
        $app->get('/list', function($request, $response, $args) use ($app) {
            $ct = new Company($app, $response);
            $ct->json('getList');
        });
        $app->post('/create', function($request, $response, $args) use ($app) {
            $ct = new Company($app, $response);
            $ct->json('create');
        });
        $app->post('/sell', function($request, $response, $args) use ($app) {
            $ct = new Company($app, $response);
            $ct->json('accept');
        });
        $app->post('/delete', function($request, $response, $args) use ($app) {
            $ct = new Company($app, $response);
            $ct->json('delete');
        });
    });

    $app->group('/marketplace', function () use ($app)
    {
        $app->get('/list', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->json('getList');
        });
        $app->post('/sell', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->json('sell');
        });
        $app->post('/buy', function($request, $response, $args) use ($app) {
            $ct = new Market($app, $response);
            $ct->json('buy');
        });
    });

})->add($ensureLogged);

