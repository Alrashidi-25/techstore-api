<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 👈 أضفنا هذا السطر
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory; // 👈 وهذي الكلمة السحرية اللي كانت ناقصة

    // هذي الأعمدة اللي سمحنا بإضافتها سابقاً
    protected $fillable = ['name', 'brand', 'category', 'price', 'stock', 'description', 'image'];
}