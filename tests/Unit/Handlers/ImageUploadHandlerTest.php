<?php

namespace Tests\Unit\Handlers;

use App\Handlers\ImageUploadHandler;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Tests\TestCase;

class ImageUploadHandlerTest extends TestCase
{
    /** @test */
    public function can_upload_an_image()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('fake.png',200,200);

        $ImageUploadHandler = new ImageUploadHandler();
        $result = $ImageUploadHandler->save($file,'test','test');
        Storage::disk('public')->assertExists($result['path']);
    }

    /** @test */
    public function can_upload_image_and_cut_image()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('fake.png',400,400);
        $this->assertEquals(400, getimagesize($file)['0']);

        $ImageUploadHandler = new ImageUploadHandler();

        $result = $ImageUploadHandler->save($file,'test','test', 200);
        Storage::disk('public')->assertExists($result['path']);
    }

    /** @test */
    public function can_edit_image_size()
    {
        Storage::fake('public');
        $path = '/fake/test';
        $file = UploadedFile::fake()->image('fake.png', 400, 400);
        $this->assertEquals(400,getimagesize($file)['0']);
        $path =$file->store($path, 'public');
        $ImageUploadHandler = new ImageUploadHandler();
        $ImageUploadHandler->reduceSize($path, 200);
        Storage::disk('public')->assertExists($path);
        $file = Storage::disk('public')->path($path);
        $this->assertEquals(200,getimagesize($file)['0']);

    }

    /** @test */
    public function cannot_upload_php_file(){
        Storage::fake('public');
        $file = UploadedFile::fake()->create('fake.php');
        $ImageUploadHandler = new ImageUploadHandler();
        $result = $ImageUploadHandler->save($file,'test','test');
        $this->assertFalse($result);
    }
}
