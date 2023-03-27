<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    public static function getAllWithTopView()
    {
        return DB::table('products')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->select('products.*', 'images.file_name')
            ->where("images.type", "=", 'top_view')
            ->get();
    }

    public static function getAlWithPng()
    {
        return DB::table('products')
            ->join('images', 'products.id', '=', 'images.product_id')
            ->select('products.*', 'images.file_name')
            ->where("images.type", "=", 'cropped_view')
            ->get();
    }
}
