<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    use HasFactory;

    protected $table = 'category_post';
    protected $fillable = ['post_id', 'category_id'];
    public $timestamps = false; // 2024_04_04_104522_create_category_post_table で　$table->id $table->timestamp を削除したからfalse

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
