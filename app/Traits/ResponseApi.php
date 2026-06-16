<?php
namespace App\Traits;

Trait ResponseApi{

   public function success($message=null,$data=[],$status=200)
   {
    return response()->json([
      'status'=>true,
      'message'=>$message,
      'data'=>$data
    ],$status);
   }


    public function failed($message=null,$data=[],$status=422)
   {
    return response()->json([
      'status'=>false,
      'message'=>$message,
      'data'=>$data
    ],$status);
   }
}