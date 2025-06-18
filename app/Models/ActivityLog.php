<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'laboratorium',
        'description',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id', 'id_pengguna');
    }

    public function laboratoriumModel()
    {
        return $this->belongsTo(Laboratorium::class, 'laboratorium', 'kode_lab');
    }

    public static function logActivity($action, $model = null, $oldValues = null, $newValues = null, $laboratorium = null, $description = null)
    {
        // Don't log admin activities
        if (auth()->check() && auth()->user()->isAdmin()) {
            return;
        }

        self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->getKey() : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'laboratorium' => $laboratorium,
            'description' => $description,
        ]);
    }
}