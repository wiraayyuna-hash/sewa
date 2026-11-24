<?php
namespace App\Http\Controllers;
use App\Models\{Vehicle,Booking,Review};
use Illuminate\Http\Request;
class StoreController extends Controller {
  function home(Request $r) {
    $q = Vehicle::where('hidden', false)->with('showroom');
    if ($r->type) $q->where('type', $r->type);
    if ($r->q) $q->where('name', 'like', "%{$r->q}%");
    return view('store.home', ['vehicles'=>$q->get()]);
  }
  function show(Vehicle $vehicle) { return view('store.show', compact('vehicle')); }
  function overlap($vid, $a, $z) {
    return Booking::where('vehicle_id',$vid)->whereIn('status',['awaiting_payment','pending_dp','dp_verified','key_handed'])
      ->where('start_date','<=',$z)->where('end_date','>=',$a)->exists()
      || \App\Models\Block::where('vehicle_id',$vid)->whereBetween('date',[$a,$z])->exists();
  }
  function book(Request $r, Vehicle $vehicle) {
    $d = $r->validate(['start_date'=>'required|date|after_or_equal:today','end_date'=>'required|date|after_or_equal:start_date','method'=>'required|in:transfer,cash']);
    if ($this->overlap($vehicle->id, $d['start_date'], $d['end_date'])) return back()->withErrors(['start_date'=>'Unit sudah terisi pada tanggal tersebut']);
    $days = \Carbon\Carbon::parse($d['start_date'])->diffInDays($d['end_date']) + 1;
    $total = $days * $vehicle->price;
    $b = Booking::create($d + ['code'=>'SWG-'.date('Ymd').'-'.rand(100,999),'showroom_id'=>$vehicle->showroom_id,'vehicle_id'=>$vehicle->id,
      'customer_id'=>auth()->id(),'total'=>$total,'dp'=>$d['method']==='transfer' ? (int)($total*0.3) : 0]);
    $vehicle->showroom->notify('Pesanan Baru Masuk (#'.$b->code.')', auth()->user()->name.' memesan '.$vehicle->name.' selama '.$days.' hari ('.$d['method'].').','order','/partner/pesanan');
    return redirect()->route('mine')->with('ok','Booking dibuat, menunggu konfirmasi partner.');
  }
  function mine() { return view('store.mine', ['bookings'=>Booking::with('vehicle','review')->where('customer_id',auth()->id())->latest()->get()]); }
  function pay(Request $r, Booking $booking) {
    abort_unless($booking->customer_id === auth()->id() && $booking->status === 'awaiting_payment', 403);
    $r->validate(['proof'=>'required|image|max:4096']);
    $booking->update(['proof'=>$r->file('proof')->store('proofs','public'),'status'=>'pending_dp']);
    $booking->showroom->notify('Bukti Transfer DP Baru Diunggah', auth()->user()->name.' mengunggah resi DP Rp '.number_format($booking->dp,0,',','.').' untuk #'.$booking->code.' ('.$booking->vehicle->name.').','payment','/partner/pembayaran');
    return back()->with('ok','Bukti DP terkirim.');
  }
  function review(Request $r, Booking $booking) {
    abort_unless($booking->customer_id === auth()->id() && $booking->status === 'returned' && !$booking->review, 403);
    $d = $r->validate(['rating'=>'required|integer|min:1|max:5','comment'=>'required']);
    Review::create($d + ['booking_id'=>$booking->id,'showroom_id'=>$booking->showroom_id,'customer_id'=>auth()->id()]);
    return back()->with('ok','Terima kasih atas ulasan Anda.');
  }
}
