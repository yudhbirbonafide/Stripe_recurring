<?php
include_once('stripe.php');
$stripe = new Stripe();

//creating payment method
$fields = ['type'=>'card','card[number]'=>'4242424242424242','card[exp_month]'=>'4','card[exp_year]'=>'2023','card[cvc]'=>314];
$payment_methods=$stripe->process_api($url='payment_methods',$method='POST',$fields);

debug($payment_methods);

//creating customer
$stripe = new Stripe();
$fields = ['email'=>'jonny6@yopmail.com','name'=>'jonny6','payment_method'=>$payment_methods['id'],'invoice_settings'=>['default_payment_method'=>$payment_methods['id']]];
$customer=$stripe->process_api($url='customers',$method='POST',$fields);
debug($customer);


//creating Subscription
$stripe = new Stripe();
$fields = ['customer'=>$customer['id'],'items[0][price]'=>'price_1KjdqGKrcRlhxv1kehzLF1ZV','default_payment_method'=>$payment_methods['id']];
$subscriptions=$stripe->process_api($url='subscriptions',$method='POST',$fields);
debug($subscriptions);
//creating PaymentIntent (If you want charge the user at same time with subscription creation.)
$stripe = new Stripe();
$fields = ['amount'=>'1000','currency'=>'usd','payment_method_types[]'=>'card','confirm'=>'true','customer'=>$customer['id'],'payment_method'=>$payment_methods['id']]; //amount'=>'1000' = 1 USD
$payment_intents=$stripe->process_api($url='payment_intents',$method='POST',$fields);
debug($payment_intents);

function debug($data,$flag=0){
	echo "<pre>";print_r($data);echo "</pre>";
	if(!empty($flag)){die;}
}
