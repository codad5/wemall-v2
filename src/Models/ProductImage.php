<?php

namespace Codad5\Wemall\Models;

use Codad5\FileHelper\FileUploader;
use Codad5\Wemall\Libs\Database;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ImageException;
use Codad5\Wemall\Libs\Exceptions\ProductException;

class ProductImage
{
    readonly string $alt;
    readonly string $path;
    readonly string $file_name;
    readonly string $url;
    const HTTP_IMAGE_NAME = 'images';
    const IMAGE_PATH = "asset/images/products/";
    const TABLE = 'product_images';
    function __construct($path, $alt = ''){
        $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $this->alt = $alt;
        $this->path = $path;
        $this->file_name = basename($this->path);
        $this->url = "$protocol://".$_SERVER['HTTP_HOST']."/".self::IMAGE_PATH."/".$this->path;
    }

    /**
     * @throws ImageException
     */
    static function uploadPhotos(string $product_id, Shop $shop)
    {
        $upload_paths = (new FileUploader(self::HTTP_IMAGE_NAME, self::IMAGE_PATH))
            ->set_reporting(false, true, true)
            ->add_ext('jpg', 'jpeg', 'png', 'gif')
            ->set_sizes(1000000, 20)
            ->set_prefix($product_id)
            ->move_files()
            ->get_uploads();
        if(!$upload_paths) throw new ImageException("Error Uploading Image");
        $presql = '';
        $prebinding = [":product_id" => $product_id, ":shop_id" => $shop->shop_id];
        foreach ($upload_paths as $item => $upload_path) {
            $presql .= "INSERT INTO ".self::TABLE." (product_id, shop_id, image_path) VALUES (:product_id, :shop_id, :image_{$item});";
            $prebinding = [...$prebinding, ":image_$item" => $upload_path['name']];
        }
        return Database::query($presql, $prebinding);
    }

    /**
     * @throws CustomException
     */
    static function getImaagesFromProduct($product_id, $required = true){
        $images = Database::table(self::TABLE)->where('product_id', $product_id);
        if(!$images && $required) throw new ProductException("No image for $product_id");
        foreach ($images as $key => $image) {
            $images[$key] = new self($image['image_path']);
        }
        return $images;
    }

    public function toArray()
    {

        return [
            'alt' => $this->alt,
            'path'=> $this->path,
            'file_name' => $this->file_name,
            'url' => $this->url
        ];
    }
}