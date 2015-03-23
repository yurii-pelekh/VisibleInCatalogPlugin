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
        $result = $this->query("SELECT shop_category.id, shop_category.name, shop_category.full_url
                                FROM shop_category_images, shop_category
                                WHERE shop_category_images.in_catalog='1'
                                AND shop_category_images.category_id = shop_category.id
                                AND shop_category.depth='0'");
        $data = $result->fetchAll();
        return $data;
    }

    public function getVisibleSubCategoriesInfo($id_parent, $url_parrent)
    {
        $result = $this->query("SELECT shop_category.id, shop_category.name, shop_category.full_url, shop_category_images.image
                                FROM shop_category_images, shop_category
                                WHERE shop_category.parent_id = i:id
                                AND shop_category.id = shop_category_images.category_id
                                AND shop_category_images.in_catalog='1'", array('id' => $id_parent));
        $data = $result->fetchAll();

        $data = $this->reformatFields($data, $url_parrent);

        return $data;
    }

    /**
     * buildRightFields
     *
     * Function reformats fields such as image, full_url in order to use them in html-page
     *
     * @param $data_array   Array which fields(image, full_url) must be changed
     * @param $url_parrent  Url of top category
     * @return  Array with new genetated fields
     */
    private function reformatFields($data_array, $url_parrent)
    {
        foreach ($data_array as $key => $item) {
            if ($item['image'] != '') {
                $data_array[$key]['image'] = '/wa-data/public/shop/data/catalog/' . $data_array[$key]['image'];
                $data_array[$key]['full_url'] = $url_parrent . '/' . $data_array[$key]['full_url'];
            }
        }
        return $data_array;
    }
}