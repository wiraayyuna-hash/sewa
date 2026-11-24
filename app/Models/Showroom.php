<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Showroom extends Model {
  protected $guarded = [];
  function vehicles() { return $this->hasMany(Vehicle::class); }
  function bookings() { return $this->hasMany(Booking::class); }
  function payouts() { return $this->hasMany(Payout::class); }
  function reviews() { return $this->hasMany(Review::class); }
  function notices() { return $this->hasMany(Notice::class)->latest(); }
  function gross() { return (int) $this->bookings()->whereIn('status', ['key_handed','returned'])->sum('total'); }
  function balance() { return (int) ($this->gross() * 0.9) - (int) $this->payouts()->sum('amount'); }
  function notify($title, $body, $type = 'system', $link = null) {
    return $this->notices()->create(compact('title','body','type','link'));
  }
}
