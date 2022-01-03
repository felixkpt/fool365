<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table = 'posts';
    protected $allowedFields = ['id', 'title', 'content', 'author', 'slug',
        'post_type', 'post_status', 'updated_at'];
}