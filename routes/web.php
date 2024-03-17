<?php
   
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\PaymentController;
  

  
Route::controller(PaymentController::class)->group(function(){
    Route::get('payment', 'stripe');
    Route::post('payment', 'payment')->name('payment.procceed');
});