<?php

class shopVisibleincatalogPluginFrontendSubcatController extends waJsonController
{
    public function execute()
    {
        $id_category = trim(waRequest::post('id'));

        if($id_category){
            shopVisibleincatalogPlugin::deleteImage($id_category);
        }
    }
}