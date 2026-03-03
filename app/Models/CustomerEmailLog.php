<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerEmailLog extends Model
{
    protected $table = 'customer_email_logs';

    protected $fillable = [
        'customer_id',
        'direction',
        'from_email',
        'to_email',
        'subject',
        'message',
        'attachment',
        'message_id',
        'in_reply_to',
        'thread_id',
        'status',
        'error'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
