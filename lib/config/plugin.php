<?php
 
return array(
    'name' => 'Видимость категории в разделе Каталог',
    'description' => 'Позволяет определять видимость категории в разделе Каталог',
    'version' => '1.0',
    'frontend' => true,
    'handlers' => array(
        'backend_category_dialog' => 'on_backend_category_dialog',
        'category_save' => 'on_category_save',
    ),

    'img'=>'img/visibleincatalog.png',
);