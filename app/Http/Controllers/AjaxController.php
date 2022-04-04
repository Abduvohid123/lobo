<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
   public function add_number(){
           return response()->json(array('msg'=> 'Успешно','color'=>'success'), 200);


   }
}
