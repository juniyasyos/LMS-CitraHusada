<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivationQuote extends Model
{
    protected $table = 'motivation_quotes';
    protected $primaryKey = 'quote_id';
    protected $fillable = ['judul', 'deskripsi', 'kondisi'];
}
