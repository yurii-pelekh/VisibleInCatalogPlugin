<?php

class shopVisibleincatalogPluginFrontendSubcatController extends waJsonController
{
    public function execute()
    {
        $id_categoryDelete = trim(waRequest::post('delid'));
        $id_category = trim(waRequest::post('id'));

        if ($id_category) {
            $model = new shopCategoryModel();
            $category = $model->getById($id_category);
            $data = $model->getSubcategories($category);
            $c = 0;
            foreach ($data as $dt) {
                $dat[$c] = $dt;
                $c++;
            }
            $this->response['data'] = $dat;
        }

        if ($id_categoryDelete) {
            shopVisibleincatalogPlugin::deleteImage($id_categoryDelete);
        }
    }
}