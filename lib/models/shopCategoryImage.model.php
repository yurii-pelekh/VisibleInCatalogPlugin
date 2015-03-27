<?php

class shopCategoryImageModel extends waModel
{
    protected $table = 'shop_category_images';

    /**
     * reformatFields
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

    public function getByCategoryId($category_id)
    {
        return $this->getByField('category_id', $category_id);
    }

    public function getVisibleTopCategoriesInfo()
    {
        $result = $this->query("SELECT c.id, c.name, c.full_url
                                FROM shop_category c
                                INNER JOIN shop_category_images i
                                ON i.category_id = c.id
                                WHERE c.depth='0'
                                AND i.in_catalog = '1'");
        $data = $result->fetchAll();
        return $data;
    }

    public function getVisibleSubCategoriesInfo($id_parent, $url_parrent)
    {
        $result = $this->query("SELECT c.id, c.name, c.full_url, i.image
                                FROM shop_category c
                                INNER JOIN shop_category_images i
                                ON i.category_id = c.id
                                WHERE c.parent_id = i:id
                                AND i.in_catalog='1'", array('id' => $id_parent));
        $data = $result->fetchAll();

        $data = $this->reformatFields($data, $url_parrent);

        return $data;
    }
}