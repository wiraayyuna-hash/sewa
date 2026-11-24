<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Booking extends Model {
  protected $guarded = [];
  protected $casts = ['start_date' => 'date', 'end_date' => 'date'];
  const STATUS = [
    'pending_confirm' => ['Menunggu Konfirmasi','bg-amber-100 text-amber-700'],
    'awaiting_payment' => ['Menunggu Bayar DP','bg-slate-100 text-slate-600'],
    'pending_dp' => ['Menunggu Cek DP','bg-amber-100 text-amber-700'],
    'dp_verified' => ['DP Diverifikasi','bg-emerald-100 text-emerald-700'],
    'key_handed' => ['Kunci Diserahkan','bg-emerald-100 text-emerald-700'],
    'returned' => ['Unit Kembali','bg-sky-100 text-sky-700'],
    'rejected' => ['Ditolak','bg-red-100 text-red-600'],
  ];
  function label() { return self::STATUS[$this->status][0]; }
  function badge() { return self::STATUS[$this->status][1]; }
  function days() { return $this->start_date->diffInDays($this->end_date) + 1; }
  function vehicle() { return $this->belongsTo(Vehicle::class); }
  function customer() { return $this->belongsTo(User::class, 'customer_id'); }
  function showroom() { return $this->belongsTo(Showroom::class); }
  function review() { return $this->hasOne(Review::class); }
}
