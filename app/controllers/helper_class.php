<?php

namespace App\Controllers;

use Illuminate\Database\Eloquent\Builder;
use App\Controllers\controller;

/**
 * Description of helper_class
 *
 * @author Ervan
 */
class helper_class extends controller {

    private $model;

    public function paginate(Builder $model, $per_page, $current_page) {

        \Illuminate\Pagination\Paginator::currentPageResolver(function() use ($current_page) {
            return $current_page;
        });

        $this->model = $model->paginate($per_page);
        
        return $this->model;
    }

}
