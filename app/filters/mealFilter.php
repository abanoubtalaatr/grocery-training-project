<?php
namespace App\filters;
use App\filters\QueryFilter;

class mealFilter extends QueryFilter{
    public function categoryId($value){
            $this->builder->where("category_id",$value);
    }

    public function isAvailable($value){
        $this->builder->where("is_available",$value);
    }


    public function subCategory($value){
        $this->builder->where("sub_category",$value);
    }


    public function Title($value){
        $this->builder->where("title",$value);
    }

    public function Description($value){
        $this->builder->where("Description",$value);
    }



    
}


?>