<?php
// Routes

// $app->get('/[{name}]', function ($request, $response, $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// });

//Auth Routes
$app->post('/auth/signup','auth_controller:sign_up');
$app->post('/auth/signin','auth_controller:sign_in');

//User Routes
$app->get('/user','user_controller:get_all');
$app->get('/user/{id}','user_controller:get_by_id');
$app->post('/user/add','user_controller:insert');
$app->put('/user/update/{id}','user_controller:update');
$app->delete('/user/delete/{id}','user_controller:delete');

//Barang Routes
$app->get('barang/add', 'barang_controller:insert');
