<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';

    protected $fillable = [
        'lembaga_id',
        'nis',
        'nama',
        'email',
        'foto',
    ];

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id');
    }

    /**
     * Get URL for student photo
     */
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && file_exists(public_path('uploads/siswa/' . $this->foto))) {
            return asset('uploads/siswa/' . $this->foto);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&color=7F9CF5&background=EBF4FF';
    }
}
