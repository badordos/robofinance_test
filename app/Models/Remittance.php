<?php

namespace App\Models;

use App\Models\Interfaces\IRemittance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remittance extends Model implements IRemittance
{
    use HasFactory;

    const STATUS_CREATED = 'created';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'payer_id',
        'recipient_id',
        'value',
        'do_at',
        'status'
    ];

    protected $with = [
        'payer',
        'recipient'
    ];

    //GETTERS|SETTERS

    public function getDoAt()
    {
        return $this->do_at;
    }

    public function getPayerId()
    {
        return $this->payer_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    //RELATIONS

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
