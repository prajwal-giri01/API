<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'check_in_date',
        'check_out_date',
        'work_duration',
        'late_early_by',
        'office_start_time',
        'office_end_time',
        'extra',
        'user_id',

    ];

    public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
// public function daily_report(): HasOne
// {
//     return $this->hasOne(DailyReport::class,'date');
// }

}
