<?php

class Category
{
    public string $name;
    private int $id;

    public  $parent;
    public array $children = [];

    public function __construct(int $id, string $name, $parent)
    {
        $this->name = $name;
        $this->id = $id;
        $this->parent = $parent;
    }


    public function appendChild(Category $category)
    {

        return $category->getName();
    }

    public function addSubcategory($subcategory)
    {

        $subcategory->parent_id = $this->id;
        array_push($this->children, $subcategory);
    }

    public function render()
    {
        $html = '<ul>';

        $html .=  '<button type="submit" name="options" value="' . $this->id . '" > ' . $this->name . '</button>';

        if (!empty($this->children)) {
            foreach ($this->children as $child) {
                $html .= $child->render();
            }
        }

        $html .= '</li>';
        $html .= '</ul>';

        return $html;
    }

    static function getSubcategories($categories, $categoryId)
    {
        $subcategories = array();
        foreach ($categories as $category) {
            if ($category->getParent() == $categoryId) {
                // добавляем текущую категорию в список подкатегорий
                $subcategories[] = $category->getId();
                // рекурсивно вызываем функцию для получения всех подкатегорий для текущей категории
                $subcategories = array_merge($subcategories, Category::getSubcategories($categories, $category->getId()));
            }
        }
        return $subcategories;
    }



    static function filter($slave, $options)
    {
        if (in_array($options, $slave->category)) {
            echo $slave->renderAll();
            return true;
        }
    }













    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParent()
    {
        return $this->parent;
    }


    public function setName($name)
    {
        $this->name = $name;
    }

    public function setParentId($perent)
    {
        $this->parent = $perent;
    }
}
