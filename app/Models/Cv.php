<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cv extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
    ];

    public function getFileUrlAttribute()
    {
        return asset('uploads/' . $this->file_path);
    }

    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    protected static function booted()
    {
        static::deleting(function (Cv $cv) {
            if ($cv->file_path) {
                Storage::disk('public')->delete($cv->file_path);
            }
        });
    }
}
