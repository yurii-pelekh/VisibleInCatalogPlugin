<?php

class shopVisibleincatalogPlugin extends shopPlugin
{
    private function uploadFile()
    {
        $file = waRequest::file('fileToUpload');
        if ($file->uploaded()) {
            // check that file is image
            try {
                // create waImage
                $file->waImage();
            } catch (Exception $e) {
                throw new Exception(_w("File isn't an image"));
            }
            $path = wa()->getDataPath('data/catalog/', true);
            $file_name = iconv('utf-8', 'windows-1251', basename($file->name, $file->extension)) . $file->extension;
            if (!file_exists($path) || !is_writable($path)) {
                throw new waException(sprintf(_wp('File could not be saved due to the insufficient file write permissions for the %s folder.'),
                    'wa-data/public/shop/data/catalog/'));
            } elseif (!$file->moveTo($path, $file_name)) {
                throw new waException(_wp('Failed to upload file.'));
            }
            return $file_name;
        }
    }

    public function on_backend_category_dialog($params)
    {
        $model = new shopCategoryImageModel();
        $id_category = $params['id'];
        $category = $model->getByCategoryId($id_category);

        $view = wa()->getView();

        if(!is_null($category)) {
            $view->assign('image', $category['image']);
            $view->assign('visible_check', $category['in_catalog']);
        }
        else {
            $view->assign('image', '');
            $view->assign('visible_check', 0);
        }
        $view->assign('id_category', $id_category);

        return $view->fetch(dirname(__FILE__).'/../templates/BackendCatDialog.html');
    }

    public function on_category_save($params)
    {
        $file_name = $this->uploadFile();
        $in_catalog = waRequest::post('show_in_catalog');
        $in_catalog =  is_null($in_catalog)? 0 : 1;
        $id_category = $params['id'];

        $data = is_null($file_name) ?
            array(
                'category_id' => $id_category,
                'in_catalog' => $in_catalog
            ) :
            array(
                'category_id' => $id_category,
                'in_catalog' => $in_catalog,
                'image' => $file_name
            );

        $model = new shopCategoryImageModel();
        $category = $model->getByCategoryId($id_category);

        //determine if record already exists
        if(is_null($category)) {
            $model->insert($data);
        }
        else {
            $model->updateById($category['id'], $data);
        }
    }

    public static function deleteImage($id_category)
    {
        $model = new shopCategoryImageModel();
        $category = $model->getByCategoryId($id_category);

        $directory_path = wa()->getDataPath('data/catalog/', true);
        $file_path = $directory_path.$category['image'];

        waFiles::delete($file_path);
        $model->updateById($category['id'], array('image' => ''));
    }

    public static function getCategories()
    {
        $model = new shopCategoryImageModel();
        $topCategories = $model->getVisibleTopCategoriesInfo();

        $result = array();
        foreach($topCategories as $topCat) {
            $subCategories = $model->getVisibleSubCategoriesInfo($topCat['id']);

            if(count($subCategories) > 0)
                $result[$topCat['name']] = $subCategories;
        }

        return $result;
    }
}

