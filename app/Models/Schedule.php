<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'student_id',
        'subject_name',
        'subject_code',
        'teacher_name',
        'teacher_no',
        'room_no',
        'branch_no',
        'day',
        'time',
        'current_smtr',
        'exam_access',
        'exam_access_message',
        'online_code',
    ];
}
