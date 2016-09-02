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
$app->get('/barang/{id}', 'barang_controller:get_by_id');
$app->get('/barang/kategori/{id}', 'barang_controller:get_by_category');
$app->get('/barang/pasar/{id}', 'barang_controller:get_by_pasar');
$app->get('/barang/harga/{harga}', 'barang_controller:get_by_price');//the price must be sent be like 0-25000
$app->get('/barang/status/{status}', 'barang_controller:get_by_status');
$app->post('/barang/add', 'barang_controller:insert');
$app->put('/barang/update/{id}', 'barang_controller:update');
$app->delete('/barang/delete/{id}', 'barang_controller:delete');

//Cart Routes
$app->post("cart/add", 'cart_controller:insert');
$app->get("/cart", "cart_controller:get_all");
$app->get("/cart/{id}", "cart_controller:get_by_id");
$app->get("/cart/user/{id}", "cart_controller:get_by_user");
$app->put("/cart/update/{id}", "cart_controller:update");
$app->delete("/cart/delete/{id}", "cart_controller:delete");

//Complain Routes
$app->post("/complain/add", "complain_controller:insert");
$app->get("/complain", "complain_controller:get_all");
$app->get("/complain/{id}", "complain_controller:get_by_id");
$app->get("/complain/user/{id}", "complain_controller:get_by_user");
$app->put("/complain/update/{id}", "complain_controller:update");
$app->delete("/complain/delete/{id}", "complain_controller:delete");
