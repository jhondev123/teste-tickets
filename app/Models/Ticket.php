<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = ['employee_id', 'quantity', 'situation'];
    protected $hidden = ['delivery_date'];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


}
