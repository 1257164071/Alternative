<?php
namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getCategoryTree($parentId = null, $allCategories = null)
    {
        if (is_null($allCategories)) {
            $allCategories = Category::all();
        }
        return $allCategories->where('parent_id', $parentId)
            ->map(function (Category $category) use ($allCategories) {
                $data = $category->toArray();
                if ($category->is_directory) {
                    return $data;
                }
                $data['children'] = $this->getCategoryTree($category->id, $allCategories)->merge([]);
                return $data;
            })->merge([]);
    }
}
