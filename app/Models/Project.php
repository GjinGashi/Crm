<?php

namespace App\Models;

use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Task;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'description',
        'status',
    ];
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
