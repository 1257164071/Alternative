<?php

namespace App\Http\Controllers\Admin;


use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(CategoryService $categoryService)
    {
        $categoryTree = $categoryService->getCategoryTree();
        return response()->json($categoryTree);
    }

    public function store(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required',
            'parent_id' => 'exists:categories,id|nullable',
            'is_directory' => 'boolean|nullable',
            'sort' => 'integer|nullable'
        ]);
        $categoryData = $request->only(['parent_id', 'name', 'is_directory', 'image', 'sort']);

        $category->fill($categoryData)->save();
        if ($parent_id = $request->post('parent_id')) {
            $category->parent()->associate($parent_id);
        }
        return response()->json($category, 201);
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required',
            'is_directory' => 'boolean|nullable',
            'sort' => 'integer|nullable'
        ]);

        $categoryData = $request->only(['name', 'is_directory', 'image', 'sort']);
        $category->update($categoryData);
        return response()->json($category ,200);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([] ,204);
    }
}
