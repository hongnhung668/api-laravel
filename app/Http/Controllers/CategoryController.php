<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Exception;

use App\Models\Category;

class CategoryController extends Controller
{
	public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function list(Request $request)
    {	
    	$limit = $this->limit;
        $page = $this->page; 
        $paginate=[];
        $listCate = [];

        $conditions = [];
    	$data = $request->all();

    	if(isset($data['page']) && is_int(intval($data['page'])))
        {
            $page = $data['page'];
        }
        if(isset($data['limit']) && is_int(intval($data['limit'])))
        {               
            $limit = $data['limit'];
        }  

        if(isset($data['title']) && is_int(intval($data['title'])))
        {               
            $conditions['title'] = $data['title'];
        }  

        if(isset($data['slug']) && is_int(intval($data['slug'])))
        {               
            $conditions['slug'] = $data['slug'];
        }     

		$listCate1 = Category::where($conditions)->get();

		$paginate = $this->getPaginate($listCate1->count(), $limit, $page);
		$listCate = $listCate1->skip(($page-1)*$limit)->take($limit); 

        // $listCate = Category::all();
        return response()->json([
        	'status' => $this->statusApi,
        	'code' => $this->statusCode,
        	'msg'  => $this->message,
        	'data' => $listCate, 
        	'paginate' => $paginate,
        ], 200);
    }

    public function create(Request $request)
    {
    	$job = [];

    	try{
    		$data = $request->all();
    		$validator = Validator::make($data, [
	            'title' => 'required|max:255',
	            'slug' => 'required|max:255',
	            'description' => 'required|max:255',
	        ]);

	        if($validator->fails()){
	            return response(['error' => $validator->errors(), 'Validation Error']);
	        }

	        $job = Category::create($data);
	        
    	}
    	catch(Exception $e){
			$this->statusCode = 401;
            $this->statusApi = false;
            $this->message = $e->getMessage();
    	}
    	return response()->json([
        	'status' => $this->statusApi,
        	'code' => $this->statusCode,
        	'msg'  => $this->message,
        	'data' => $job, 
        ], 200);
    }
}
