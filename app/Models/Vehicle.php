<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Vehicle extends Model {
  protected $guarded = [];
  function showroom() { return $this->belongsTo(Showroom::class); }
  function bookings() { return $this->hasMany(Booking::class); }
  function img() { return $this->image ? asset('storage/'.$this->image) : 'https://placehold.co/600x300/0b4d3c/ffffff?text='.urlencode($this->name); }
}
