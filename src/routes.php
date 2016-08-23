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
$app->post('/auth/admin/signup','admin_auth_controller:sign_up');
$app->post('/auth/admin/signin','admin_auth_controller:sign_in');

//User Routes
$app->get('/user','user_controller:get_all');
$app->get('/user/{id}','user_controller:get_by_id');
$app->post('/user/add','user_controller:insert');
$app->put('/user/update/{id}','user_controller:update');
$app->delete('/user/delete/{id}','user_controller:delete');

//Admin Routes
$app->get('/admin','admin_controller:get_all');
$app->get('/admin/{id}','admin_controller:get_by_id');
$app->post('/admin/add','admin_controller:insert');
$app->put('/admin/update/{id}','admin_controller:update');
$app->delete('/admin/delete/{id}','admin_controller:delete');

//Pasar Routes
$app->get('/pasar','pasar_controller:get_all');
$app->get('/pasar/{id}','pasar_controller:get_by_id');
$app->post('/pasar/add','pasar_controller:insert');
$app->put('/pasar/update/{id}','pasar_controller:update');
$app->delete('/pasar/delete/{id}','pasar_controller:delete');

//Kategori Routes
$app->get('/kategori','kategori_controller:get_all');
$app->get('/kategori/{id}','kategori_controller:get_by_id');
$app->post('/kategori/add','kategori_controller:insert');
$app->put('/kategori/update/{id}','kategori_controller:update');
$app->delete('/kategori/delete/{id}','kategori_controller:delete');

//Barang Routes
$app->get('/barang', 'barang_controller:get_all');
$app->post('/barang/add', 'barang_controller:insert');
