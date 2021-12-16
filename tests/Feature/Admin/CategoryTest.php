<?php

namespace Tests\Feature\Admin;


use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\AdminTestCase;

class CategoryTest extends AdminTestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_categories_tree()
    {
        $testData = $this->createCategoryTree(3);
        $auth = $this->authorization('/api/admin/categories','GET', create(Admin::class, ['id' => 2]));
        $result = $this->json('GET', '/api/admin/categories',[] ,$auth);

        $result->assertStatus(200);
        $result->assertJsonFragment([
            'id' => $testData[0]['id'],
            'name'=> $testData[0]['name'],
            'children' => [],
        ]);
    }

    /** @test */
    public function login_user_cannot_get_categories_tree()
    {
        $this->withExceptionHandling();
        $auth = $this->signJwt(create(Admin::class, ['id' => 2]));
        $this->json('GET', '/api/admin/categories',[] ,$auth)->assertStatus(403);
    }

    /** @test */
    public function create_category_item()
    {
        $auth = $this->authorization('/api/admin/categories','POST', create(Admin::class, ['id' => 2]));

        $father = $this->createCategoryTree(1);

        $data = [
            'parent_id' => $father[0]['id'],
            'name'  =>  'name1',
            'is_directory' => true,
            'image' =>  '/dsfsd/dsfsd/1.jpg',
            'sort'  =>  '99',
        ];
        $result = $this->json('POST', '/api/admin/categories' ,$data ,$auth);

        $result->assertJsonFragment($data);
        $result->assertStatus(201);
    }

    /** @test */
    public function update_category_item()
    {
        $auth = $this->authorization('/api/admin/categories/*','PUT', create(Admin::class, ['id' => 2]));

        $data = [
            'name'  =>  'name1',
            'is_directory' => true,
            'image' =>  '/dsfsd/dsfsd/1.jpg',
            'sort'  =>  '99',
        ];
        $category = create(Category::class);

        $result = $this->json('PUT', '/api/admin/categories/'.$category->id ,$data ,$auth);
        echo 'fds';
        $result->assertJsonFragment($data);
        $result->assertStatus(200);
    }

    /** @test */
    public function destroy_category_item()
    {

        $auth = $this->authorization('/api/admin/categories/*','DELETE', create(Admin::class, ['id' => 2]));
        $category = create(Category::class);

        $this->json('DELETE', '/api/admin/categories/'.$category->id, [], $auth)->assertStatus(204);
        $this->assertNull(Category::find($category->id));

    }

    public function createCategoryTree($num)
    {
        $item = [];
        $category = collect();
        for ($i=0; $i<$num; $i++) {
            $item['is_directory'] = true;
            $category[$i] = factory(Category::class)->create($item);
            $item = ['parent_id' => $category[$i]['id']];
        }
        return $category;
    }


}
