<?php

include "Data/Slaves.php";
include "Data/Categories.php";

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

$categories = Factory::create($categoriesData, 'Category');
$slaves = Factory::create($slavesData, 'Slave');

addSC($categories);
render($categories, $slaves);

function addSC($categories)
{
    $index = 1;
    foreach ($categories as $item) {

        if ($item->getParent() !== null) {
            $parent = $categories[$item->getParent()];
            $parent->addSubcategory($categories[$index]);
        }

        $index++;
    }
}

function render($content, $ent)
{
    echo ('<form method="post">');
    echo $content[1]->render();
    echo ('</form>');

    //Category::filter($content, $ent);

    if (isset($_POST['options'])) {
        $options = intval($_POST['options']);
        $options = array_merge(array($options), Category::getSubcategories($content, $options));
        foreach ($ent as $slave) {
            $inPool = false;
            foreach ($options as $item) {
                if ($inPool) {
                } else {
                    $inPool = $content[$item]->filter($slave, $item);
                }
            }
        }
    }
}
