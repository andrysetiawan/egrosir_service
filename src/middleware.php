<?php
// Application middleware
use App\Controllers\Token;
use Slim\Middleware\JwtAuthentication;
use Slim\Middleware\HttpBasicAuthentication;

// e.g: $app->add(new \Slim\Csrf\Guard);
$container = $app->getContainer();
$container["token"] = function ($container) {
    return new Token;
};

$container["JwtAuthentication"] = function ($container) {
    return new JwtAuthentication([
        "path" => [
                           "/user","/admin",
        		   "/pasar/add","/pasar/update/","/pasar/delete/",
        		   "/kategori/add","/kategori/update/","kategori/delete/",
                           "/barang/add", "/barang/update/", "/barang/delete/"
        ],
        "passthrough" => ["/auth/signin"],
        "secret" => "hatepnganuikihihamburadul",
        "secure" => false,
        "error" => function ($request, $response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        },
        "callback" => function ($request, $response, $arguments) use ($container) {
            $container["token"]->hydrate($arguments["decoded"]);
        }
    ]);
};

//$app->add("HttpBasicAuthentication");
$app->add("JwtAuthentication");


$container["cache"] = function ($container) {
    return new CacheUtil;
};