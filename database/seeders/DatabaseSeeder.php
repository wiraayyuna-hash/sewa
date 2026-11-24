<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{User,Showroom,Vehicle,Booking,Review,Payout,Block};
class DatabaseSeeder extends Seeder {
  function run(): void {
    $p = User::create(['name'=>'Syiah Kuala Rental','email'=>'partner@rutekita.test','password'=>'password','role'=>'partner','phone'=>'081269001122']);
    $s = Showroom::create(['user_id'=>$p->id,'name'=>'Syiah Kuala Rental UMKM','nib'=>'9120102938102','wa'=>'081269001122','hours'=>'07:30 - 22:00 WIB',
      'address'=>'Jl. Utama Rukoh No. 12, Kawasan Darussalam, Kec. Syiah Kuala, Kota Banda Aceh, Aceh 23111',
      'description'=>'Penyedia sewa motor dan mobil terpercaya di sektor Darussalam/USK. Unit selalu terawat, helm bersih, dan melayani antar-jemput gratis ke area kampus.',
      'bank'=>'Bank Syariah Indonesia (BSI)','account_no'=>'7129384920','account_name'=>'Syiah Kuala Rental','pin'=>bcrypt('12345678')]);
    $v = [];
    foreach ([['Honda Scoopy Prestige','BL 4589 YZ','Putih Silver','Motor Matic',75000,'ready'],['Yamaha NMAX 155cc','BL 1029 KB','Hitam Doff','Motor Maxi',110000,'rented'],
      ['Toyota Avanza Veloz','BL 1892 AA','Hitam Metalik','Mobil Keluarga',350000,'ready'],['Honda Beat Street','BL 3921 OL','Hitam Doff','Motor Matic',65000,'service']] as $r)
      $v[] = Vehicle::create(['showroom_id'=>$s->id,'name'=>$r[0],'plate'=>$r[1],'color'=>$r[2],'type'=>$r[3],'price'=>$r[4],'status'=>$r[5]]);
    $c = fn($n,$e,$ph,$cat)=>User::create(['name'=>$n,'email'=>$e,'password'=>'password','phone'=>$ph,'category'=>$cat]);
    $jihan = $c('Jihan Ammara Shakila','user@rutekita.test','081269001122','Mahasiswa USK');
    $rian = $c('Rian Hidayat','rian@rutekita.test','085277112233','Wisatawan Umum');
    $fahmi = $c('Fahmi Ramadhan','fahmi@rutekita.test','081399887766','Mahasiswa UIN Ar-Raniry');
    $b = fn($code,$v,$u,$a,$z,$t,$dp,$m,$st)=>Booking::create(['code'=>$code,'showroom_id'=>$s->id,'vehicle_id'=>$v->id,'customer_id'=>$u->id,'start_date'=>$a,'end_date'=>$z,'total'=>$t,'dp'=>$dp,'method'=>$m,'status'=>$st]);
    $b('SWG-20261010-089',$v[0],$jihan,'2026-10-10','2026-10-12',225000,50000,'transfer','pending_dp');
    $r2=$b('SWG-20261009-041',$v[1],$rian,'2026-10-09','2026-10-11',220000,0,'cash','key_handed');
    $r3=$b('SWG-20261005-012',$v[2],$fahmi,'2026-10-05','2026-10-07',700000,700000,'transfer','returned');
    $b('SWG-20261001-005',$v[2],$fahmi,'2026-10-01','2026-10-03',2930000,2930000,'transfer','returned');
    $b('SWG-20261013-070',$v[2],$fahmi,'2026-10-13','2026-10-14',700000,0,'cash','dp_verified');
    $b('SWG-20261016-102',$v[3],$jihan,'2026-10-17','2026-10-19',195000,0,'transfer','pending_confirm');
    foreach ([[$v[3],'2026-10-08'],[$v[3],'2026-10-09'],[$v[1],'2026-10-15'],[$v[1],'2026-10-16']] as [$x,$d]) Block::create(['vehicle_id'=>$x->id,'date'=>$d]);
    Review::create(['booking_id'=>$r3->id,'showroom_id'=>$s->id,'customer_id'=>$jihan->id,'rating'=>5,'comment'=>'Pelayanan sangat cepat, motor matic bersih, helm harum dan dapat diskon mahasiswa USK. Mantap!']);
    Review::create(['booking_id'=>$r2->id,'showroom_id'=>$s->id,'customer_id'=>$rian->id,'rating'=>5,'comment'=>'Proses verifikasi KTP cepat dan motor ditaruh dalam kondisi bensin full. Sangat direkomendasikan!']);
    Payout::create(['code'=>'WD-20261001-01','showroom_id'=>$s->id,'amount'=>2100000]);
    Payout::create(['code'=>'WD-20261010-02','showroom_id'=>$s->id,'amount'=>1200000]);
    $s->notify('Pencairan Saldo (Payout) Berhasil','Dana sebesar Rp 2.100.000 berhasil dicairkan dan ditransfer ke rekening BSI terdaftar milik showroom Anda.','system','/partner/keuangan')->update(['read'=>true]);
    $s->notify('Pesanan Baru Masuk (#SWG-20261009-041)','Penyewa Rian Hidayat membuat reservasi sewa unit Yamaha NMAX 155cc selama 2 hari dengan metode pembayaran cash di garasi.','order','/partner/pesanan')->update(['read'=>true]);
    $s->notify('Bukti Transfer DP Baru Diunggah','Pengguna Jihan Ammara Shakila telah mengunggah resi transfer DP sebesar Rp 50.000 untuk pemesanan #SWG-20261010-089 (Honda Scoopy Prestige).','payment','/partner/pembayaran');
  }
}
