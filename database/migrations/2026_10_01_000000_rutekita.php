<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
  function up(): void {
    Schema::table('users', function (Blueprint $t) {
      $t->string('role')->default('customer'); $t->string('phone')->nullable(); $t->string('category')->nullable();
    });
    Schema::create('showrooms', function (Blueprint $t) {
      $t->id(); $t->foreignId('user_id'); $t->string('name'); $t->string('nib')->nullable();
      $t->string('wa')->nullable(); $t->string('hours')->nullable(); $t->text('address')->nullable();
      $t->text('description')->nullable(); $t->string('bank')->nullable(); $t->string('account_no')->nullable();
      $t->string('account_name')->nullable(); $t->string('pin')->nullable();
      $t->unsignedInteger('fee_campus')->default(15000); $t->unsignedInteger('fee_out')->default(25000); $t->timestamps();
    });
    Schema::create('vehicles', function (Blueprint $t) {
      $t->id(); $t->foreignId('showroom_id'); $t->string('name'); $t->string('plate'); $t->string('color');
      $t->string('type'); $t->unsignedInteger('price'); $t->string('status')->default('ready');
      $t->string('image')->nullable(); $t->boolean('hidden')->default(false); $t->timestamps();
    });
    Schema::create('bookings', function (Blueprint $t) {
      $t->id(); $t->string('code')->unique(); $t->foreignId('showroom_id'); $t->foreignId('vehicle_id');
      $t->foreignId('customer_id'); $t->date('start_date'); $t->date('end_date'); $t->unsignedInteger('total');
      $t->unsignedInteger('dp')->default(0); $t->string('method')->default('transfer');
      $t->string('proof')->nullable(); $t->string('status')->default('pending_confirm'); $t->timestamps();
    });
    Schema::create('blocks', function (Blueprint $t) {
      $t->id(); $t->foreignId('vehicle_id'); $t->date('date'); $t->unique(['vehicle_id','date']);
    });
    Schema::create('payouts', function (Blueprint $t) {
      $t->id(); $t->string('code'); $t->foreignId('showroom_id'); $t->unsignedInteger('amount'); $t->timestamps();
    });
    Schema::create('reviews', function (Blueprint $t) {
      $t->id(); $t->foreignId('booking_id'); $t->foreignId('showroom_id'); $t->foreignId('customer_id');
      $t->unsignedTinyInteger('rating'); $t->text('comment'); $t->text('reply')->nullable(); $t->timestamps();
    });
    Schema::create('notices', function (Blueprint $t) {
      $t->id(); $t->foreignId('showroom_id'); $t->string('type')->default('system'); $t->string('title');
      $t->text('body'); $t->string('link')->nullable(); $t->boolean('read')->default(false); $t->timestamps();
    });
  }
};
