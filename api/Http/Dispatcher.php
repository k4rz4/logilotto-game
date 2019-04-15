<?php

class Dispatcher
{
    private $request;

    public function dispatch()
    {
        $this->request = new Request();
        $router = new Router($this->request);

        $router->get('/bets/list', 'MainController@getBets');
        $router->post('/bets/place', 'MainController@placeBet');

        //$router->post('/draws/last', 'MainController@placeBet');
        $router->post('/clients/validate', 'MainController@validateClient');
    }
}
?>
