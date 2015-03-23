<?php

class shopCategoryImageModel extends waModel
{
    protected $table = 'shop_category_images';

    public function getByCategoryId($category_id)
    {
        return $this->getByField('category_id', $category_id);
    }

    public function getVisibleTopCategoriesInfo()
    {
        $result = $this->query("SELECT shop_category.id, shop_category.name FROM shop_category_images, shop_category
                                WHERE shop_category_images.in_catalog='1'
                                AND shop_category_images.category_id = shop_category.id
                                AND shop_category.depth='0'");
        $data = $result->fetchAll();
        return $data;
    }

    public function getVisibleSubCategoriesInfo($id_parent)
    {
        $result = $this->query("SELECT shop_category.id, shop_category.name, shop_category.url, shop_category_images.image FROM shop_category_images, shop_category
                                WHERE shop_category.parent_id = i:id
                                AND shop_category.id = shop_category_images.category_id
                                AND shop_category_images.in_catalog='1'", array('id' => $id_parent));
        $data = $result->fetchAll();
        return $data;
    }
}