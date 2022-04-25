<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory, Uuids;
    protected $fillable = [
        'id',
        'title',
        'description',
        'status',
        'location',
        'creator_email',
        'creator_name',
        'organizer_email',
        'organizer_name',
        'event_start',
        'event_end',
        'recurring',
        'attendees_names',
        'attendees_emails',
        'meeting_link',
    ];

    public function geteventStartAttribute($value)
    {
        return Carbon::parse($value)->format(('d/m/y H:i a'));
    }

    public function geteventEndAttribute($value)
    {
        return Carbon::parse($value)->format(('d/m/y H:i a'));
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_event');
    }
}
