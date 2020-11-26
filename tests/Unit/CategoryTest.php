<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_category()
    {
        $category1 = factory(Category::class)->create();
        $category2 = factory(Category::class)->create(['parent_id' => $category1->id]);
        $this->assertEquals('-', $category1->path);
        $this->assertEquals(0, $category1->level);
        $this->assertEquals('-'. $category1->id .'-', $category2->path);
        $this->assertEquals(1, $category2->level);
    }

    /** @test */
    public function category_has_parent()
    {
        $category  = factory(Category::class)->create();
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsTo', $category->parent());
    }

    /** @test */
    public function category_has_children()
    {
        $category  = factory(Category::class)->create();
        $this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\HasMany', $category->children());
    }

    /** @test */
    public function category_path_ids_attribute()
    {
        $keys = [1, 2, 3];
        foreach ($keys as $item){
            $categories = $this->createCategoryTree($item);
            $arr = $categories->pluck('id')->toArray();
            $key = count($arr) - 1;
            unset($arr[$key]);
            $this->assertEquals($arr, $categories[$key]->path_ids);
        }
    }

    /** @test */
    public function category_ancestors_attribute()
    {
        $categories = $this->createCategoryTree(3);
        $this->assertCount(2, $categories[2]->ancestors);
    }

    /** @test */
    public function category_full_name_attribute()
    {
        $categories = $this->createCategoryTree(3);
        $str = $categories[0]->name.'-'.$categories[1]->name.'-'.$categories[2]->name;
        $this->assertEquals($str, $categories[2]->full_name);
    }

    /** @test */
    public function can_get_categories_tree_list()
    {
        $crt = $this->createCategoryTree(3);
        $crt2 = $this->createCategoryTree(3);
        $service = (new CategoryService())->getCategoryTree();
        $this->assertEquals($crt[0]->id, $service[0]['id']);
        $this->assertEquals($crt[1]->id, $service[0]['children'][0]['id']);
        $this->assertEquals($crt2[0]->id, $service[1]['id']);
        $this->assertEquals($crt2[1]->id, $service[1]['children'][0]['id']);
    }

    public function createCategoryTree($num)
    {
        $item = [];
        $category = collect();
        for ($i=0; $i<$num; $i++) {
            $item['is_directory'] = false;
            $category[$i] = factory(Category::class)->create($item);
            $item = ['parent_id' => $category[$i]['id']];
        }
        return $category;
    }

}
