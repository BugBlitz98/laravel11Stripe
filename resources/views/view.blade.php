<!DOCTYPE html>
<html>
<head>
    <title>Laravel - Stripe Payment Gateway Integration Example </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>

<div class="container">
    
    <div class="flex justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-md">
            <div class="flex justify-between items-center px-4 py-3 bg-gray-100 rounded-t-lg">
                <h3 class="text-lg font-semibold">Laravel 11 - Stripe Payment</h3>
            </div>
            <div class="p-6">
                @if (Session::has('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif

                <form role="form" 
                action="{{ route('payment.procceed') }}"
                 method="post" class="require-validation" 
                 data-cc-on-file="false" 
                 data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" 
                 id="payment-form">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2" for="name">Name on Card</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="name" placeholder="Enter name on card">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2" for="cardNumber">Card Number</label>
                        <input class="card-number shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="cardNumber" placeholder="Enter card number">
                    </div>

                    <div class="flex mb-4">
                        <div class="w-1/3 pr-2">
                            <label class="block text-gray-700 font-semibold mb-2 required" for="cvc">CVC</label>
                            <input class="card-cvc shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="cvc" placeholder="CVC">
                        </div>
                        <div class="w-1/3 px-2">
                            <label class="block text-gray-700 font-semibold mb-2 required" for="expiryMonth">Exp Month</label>
                            <input class=" card-expiry-month shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="expiryMonth" placeholder="MM">
                        </div>
                        <div class="w-1/3 pl-2">
                            <label class="block text-gray-700 font-semibold mb-2 required" for="expiryYear">Exp Year</label>
                            <input class="card-expiry-year shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" id="expiryYear" placeholder="YYYY">
                        </div>
                    </div>

                    <div class="mb-4 hidden">
                        <div class="bg-red-100 error border border-red-400 text-red-700 px-4 py-3 rounded">
                            Please correct the errors and try again.
                        </div>
                    </div>

                    <div>
                        <button class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            Pay Now ($100)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
        
</div>
    
</body>
    
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    
<script type="text/javascript">
  
$(function() {
  
    /*------------------------------------------
    --------------------------------------------
    Stripe Payment Code
    --------------------------------------------
    --------------------------------------------*/
    
    var $form = $(".require-validation");
     
    $('form.require-validation').bind('submit', function(e) {
        var $form = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid = true;
        $errorMessage.addClass('hide');
    
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
          var $input = $(el);
          if ($input.val() === '') {
            $input.parent().addClass('has-error');
            $errorMessage.removeClass('hide');
            e.preventDefault();
          }
        });
     
        if (!$form.data('cc-on-file')) {
          e.preventDefault();
          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
          Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
          }, stripeResponseHandler);
        }
    
    });
      
    /*------------------------------------------
    --------------------------------------------
    Stripe Response Handler
    --------------------------------------------
    --------------------------------------------*/
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            /* token contains id, last4, and card type */
            var token = response['id'];
                 
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }
     
});
</script>
</html>