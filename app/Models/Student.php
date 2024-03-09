<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['nis', 'name', 'address', 'gender', 'birthday', 'religion', 'contact', 'profile', 'status'];
}
