<?php

class App {
    // When we bootstrap our application, default the home
    // controller will be used with it's index() method and
    // no parameters.
    protected $controller = "home";

    protected $method = "index";

    protected $params = [];

    /**
     * The unsetting process is a clever way of only holding the
     * parameters in the array in the end. If the url is for example:
     * home/index/param1/param2, then first home will be checked if
     * this controller exists, if that's the case this will be unset
     * (removed from the array, but the keys of the other items will not change).
     * Then the second parameter will be checked if this is an existing method,
     * which is also the case so this item will also be unset. Then we know item
     * three and four are parameters. However, if the method for example
     * did not exist, the default method index() will be used and the array item
     * will not be unset so it stays as parameter in the array. The same applies to
     * controller, if this does not exist, it will be considered as parameter.
     * App constructor.
     */
    public function __construct()
    {

        $url = $this->parseUrl();
        if (file_exists("../app/controllers/" . $url[0] . ".php")) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once "../app/controllers/" . $this->controller . ".php";

        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // The array_values method will take all values from the array and
        // rewrite the keys to 0 =>, 1 => et cetera. There is a ternary operator
        // present because when no url is given, the $url variable will be null
        // hence the array_values can not be run on null, so then an empty array
        // will be used [].
        $this->params = $url ? array_values($url) : [];

        // This method will call the method of the given controller with the given params.
        // If no url was provided, controller holds the home controller and the index method
        // and no parameters (see attributes).
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * This method will parse the url which is exploded in array items.
     * For example: controller/method/param1/param2 will become:
     * array(0 => controller, 1 => method, 2 => param1, 3 => param2).
     * @return array with the exploded elements of the url provided by .htaccess
     */
    public function parseUrl() {
        // In the .htaccess file the url will be set in a ?url= format which
        // is then detected in this $_GET['url'].
        if (isset($_GET['url'])) {
            // rtrim will trim the / from the right of the url, if there is any.
            // filter_var will check if the url is really an url.
            // explode will make an array of each part of the url.
            return explode("/", filter_var(rtrim($_GET['url'], "/"), FILTER_SANITIZE_URL));
        }
    }
}