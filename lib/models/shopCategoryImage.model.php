<?php

class shopCategoryImageModel extends waModel
{
    protected $table = 'shop_category_images';

    public function getByCategoryId($category_id)
    {
        return $this->getByField('category_id', $category_id);
    }
}