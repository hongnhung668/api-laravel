<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $statusCode = 10000;
    protected $message = "Command completed successfully";
    protected $statusApi = true;
    protected $limit = 50;
    protected $page = 1;

    /**
     * get $paginate
     * @param int $count
     * @param int $limit
     * @param int $page
     * @return array
     */
    protected function getPaginate($count = 0,$limit = 10,$page =1){
        $paginate = array(
            'page_count' => 0,
            'current_page' => $page,
            'count' => $count,
            'has_prev_page' => false,
            'has_next_page' => false,
            'limit' => $limit
        );
        if(!empty($count)){
            $paginate['page_count'] = ceil($count/$limit);
            if($paginate['page_count']>$page){
                $paginate['has_next_page'] = true;
            }
            if($page > 1 && $paginate['page_count']>=$page){
                $paginate['has_prev_page'] = true;
            }
        }
        return $paginate;
    }
}
