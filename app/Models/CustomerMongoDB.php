<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class  CustomerMongoDB  extends  Model
{
use  HasFactory;

// the selected database as defined in /config/database.php
    protected  $connection = 'mongodb';

// equivalent to $table for MySQL
protected  $collection = 'auto_cross';

// defines the schema for top-level properties (optional).
protected  $fillable = ['brand', 'brand_cross', 'article', 'article_cross', 'name', 'counter','name_cross'];
}
