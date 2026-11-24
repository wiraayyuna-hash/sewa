<?php
namespace App\Http\Controllers;
use App\Models\{Vehicle,Booking,Notice,Review,Block,Payout};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class PartnerController extends Controller {
  function sr() { return auth()->user()->showroom; }
  function dashboard() {
    $s = $this->sr(); $n = $s->vehicles()->count();
    $fav = Booking::where('showroom_id',$s->id)->whereNotIn('status',['rejected'])->selectRaw('vehicle_id, count(*) c')->groupBy('vehicle_id')->orderByDesc('c')->with('vehicle')->take(3)->get();
    $week = collect(range(3,0))->map(fn($i)=>(int)$s->bookings()->whereIn('status',['key_handed','returned'])->whereBetween('start_date',[now()->subWeeks($i+1)->toDateString(),now()->subWeeks($i)->toDateString()])->sum('total')/1000);
    return view('partner.dashboard', [
      'occ'=>$n ? round($s->vehicles()->where('status','rented')->count()/$n*100) : 0, 'net'=>(int)($s->gross()*0.9),
      'pending'=>$s->bookings()->where('status','pending_dp')->get(), 'rating'=>round($s->reviews()->avg('rating') ?: 0,1), 'rc'=>$s->reviews()->count(),
      'fav'=>$fav, 'week'=>$week, 'latest'=>$s->bookings()->with('vehicle','customer')->latest()->take(5)->get()]);
  }
  function fleet() { $s=$this->sr(); return view('partner.fleet',['s'=>$s,'vehicles'=>$s->vehicles]); }
  function vehicleStore(Request $r) {
    $d = $r->validate(['name'=>'required','plate'=>'required','color'=>'required','type'=>'required','price'=>'required|integer','image'=>'nullable|image']);
    if ($r->hasFile('image')) $d['image'] = $r->file('image')->store('vehicles','public');
    $this->sr()->vehicles()->create($d); return back();
  }
  function vehicleUpdate(Request $r, Vehicle $vehicle) {
    abort_unless($vehicle->showroom_id === $this->sr()->id, 403);
    $vehicle->update($r->validate(['name'=>'required','price'=>'required|integer','status'=>'required|in:ready,rented,service'])); return back();
  }
  function vehicleToggle(Vehicle $vehicle) { abort_unless($vehicle->showroom_id === $this->sr()->id, 403); $vehicle->update(['hidden'=>!$vehicle->hidden]); return back(); }
  function fee(Request $r) { $this->sr()->update($r->validate(['fee_campus'=>'required|integer','fee_out'=>'required|integer'])); return back(); }
  function calendar(Request $r) {
    $start = \Carbon\Carbon::parse($r->get('start','2026-10-08')); $days = collect(range(0,8))->map(fn($i)=>$start->copy()->addDays($i));
    $vs = $this->sr()->vehicles()->get(); $bk = Booking::whereIn('vehicle_id',$vs->pluck('id'))->whereIn('status',['awaiting_payment','pending_dp','dp_verified','key_handed'])->get();
    $bl = Block::whereIn('vehicle_id',$vs->pluck('id'))->get();
    $cell = function($v,$d) use ($bk,$bl) {
      if ($bk->where('vehicle_id',$v->id)->filter(fn($b)=>$d->between($b->start_date,$b->end_date))->count()) return 'busy';
      return $bl->where('vehicle_id',$v->id)->where('date',$d->toDateString())->count() ? 'service' : 'ok';
    };
    return view('partner.calendar', compact('start','days','vs','cell'));
  }
  function block(Request $r) {
    $v = Vehicle::findOrFail($r->vehicle_id); abort_unless($v->showroom_id === $this->sr()->id, 403);
    $b = Block::where('vehicle_id',$v->id)->where('date',$r->date); $b->exists() ? $b->delete() : Block::create(['vehicle_id'=>$v->id,'date'=>$r->date]); return back();
  }
  function notices() { return view('partner.notices', ['all'=>$this->sr()->notices, 'tab'=>request('tab','all')]); }
  function read(Notice $notice) { abort_unless($notice->showroom_id === $this->sr()->id, 403); $notice->update(['read'=>true]); return back(); }
  function readAll() { $this->sr()->notices()->update(['read'=>true]); return back(); }
  function incoming() { return view('partner.incoming', ['rows'=>$this->sr()->bookings()->with('vehicle','customer')->whereIn('status',['pending_confirm','dp_verified'])->get()]); }
  function payments(Request $r) {
    $q = $this->sr()->bookings()->with('vehicle','customer')->latest();
    $f = ['dp'=>'pending_dp','sewa'=>['dp_verified','key_handed'],'selesai'=>'returned'][$r->tab] ?? null;
    if ($f) $q->whereIn('status', (array)$f); if ($r->q) $q->where('code','like',"%{$r->q}%");
    return view('partner.payments', ['rows'=>$q->get(),'all'=>$this->sr()->bookings, 'tab'=>$r->tab]);
  }
  function action(Booking $booking, $action) { $b = $booking;
    $s = $this->sr(); abort_unless($b->showroom_id === $s->id, 403);
    match ($action) {
      'accept' => $b->update(['status'=>$b->method==='cash' ? 'dp_verified' : 'awaiting_payment']),
      'reject' => $b->update(['status'=>'rejected']),
      'verify' => $b->update(['status'=>'dp_verified']),
      'key' => [$b->update(['status'=>'key_handed']), $b->vehicle->update(['status'=>'rented'])],
      'return' => [$b->update(['status'=>'returned']), $b->vehicle->update(['status'=>'ready'])],
      default => abort(404) };
    return back();
  }
  function finance() { $s=$this->sr(); $g=$s->gross(); return view('partner.finance',['s'=>$s,'gross'=>$g,'fee'=>(int)($g*0.1),'bal'=>$s->balance(),'logs'=>$s->payouts()->latest()->get()]); }
  function payout(Request $r) {
    $s=$this->sr(); $d=$r->validate(['amount'=>'required|integer|min:50000','pin'=>'required']);
    if (!Hash::check($d['pin'],$s->pin)) return back()->withErrors(['pin'=>'PIN salah']);
    if ($d['amount'] > $s->balance()) return back()->withErrors(['amount'=>'Melebihi saldo bersih']);
    Payout::create(['code'=>'WD-'.date('Ymd').'-'.str_pad($s->payouts()->count()+1,2,'0',STR_PAD_LEFT),'showroom_id'=>$s->id,'amount'=>$d['amount']]);
    $s->notify('Pencairan Saldo (Payout) Berhasil','Dana sebesar Rp '.number_format($d['amount'],0,',','.').' berhasil dicairkan ke rekening '.$s->bank.'.','system','/partner/keuangan');
    return back()->with('ok','Payout berhasil diproses.');
  }
  function reviews() { $s=$this->sr(); return view('partner.reviews',['s'=>$s,'avg'=>round($s->reviews()->avg('rating')?:0,1),'rows'=>$s->reviews()->with('customer')->latest()->get()]); }
  function reply(Request $r, Review $review) { abort_unless($review->showroom_id === $this->sr()->id, 403); $review->update(['reply'=>$r->validate(['reply'=>'required'])['reply']]); return back(); }
  function clients() {
    $rows = $this->sr()->bookings()->with('vehicle','customer')->where('status','!=','rejected')->get()->groupBy('customer_id')->map(fn($g)=>['u'=>$g[0]->customer,'n'=>$g->count(),'fav'=>$g->groupBy('vehicle_id')->sortByDesc(fn($x)=>count($x))->first()[0]->vehicle->name]);
    return view('partner.clients', compact('rows'));
  }
  function settings() { return view('partner.settings',['s'=>$this->sr()]); }
  function saveProfile(Request $r) { $this->sr()->update($r->only('name','nib','wa','hours','address','description')); return back()->with('ok','Profil disimpan.'); }
  function saveBank(Request $r) { $this->sr()->update($r->only('bank','account_no','account_name')); return back()->with('ok','Rekening diperbarui.'); }
}
