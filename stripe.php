<?php

class Stripe {
    public $headers;
    public $url = 'https://api.stripe.com/v1/';
    public $method = null;
    public $fields = array();
    public $STRIPE_API_KEY ='sk_test_51IThxYKrcRlhxv1kitQYprRAKmqr3eje2YW4kQ003JSm2wsYMXa9VOlyyOecHMFkYZO6p70yLcfp5S698NhdpimB00tYnUnDQN';
    
    function __construct () {
        $this->headers = array('Authorization: Bearer '.$this->STRIPE_API_KEY,"content-type: application/x-www-form-urlencoded",); // STRIPE_API_KEY = your stripe api key
    }
    
    function call () {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        switch ($this->method){
           case "POST":
              curl_setopt($ch, CURLOPT_POST, 1);
              if ($this->fields)
                 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->fields));
              break;
           case "PUT":
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($this->fields)
                 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->fields));
              break;
           default:
              if ($this->fields)
                 $this->url = sprintf("%s?%s", $this->url, http_build_query($this->fields));
        }

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
         if(curl_errno($ch)){
            echo 'Curl error: ' . curl_error($ch);          
         }
        
        curl_close($ch);
        return json_decode($output, true); // return php array with api response
    }
    public function process_api($url,$method,$fields){
      $this->url     = $this->url.$url;
      $this->method  = $method;
      $this->fields  = $fields;
      $data=$this->call();
      return $data;
    }
}

// create customer and use email to identify them in stripe
// $s = new Stripe();
// $s->url .= 'customers';
// $s->method = "POST";
// $s->fields['email'] = $_POST['email'];
// $customer = $s->call();

// // create customer subscription with credit card and plan
// $s = new Stripe();
// $s->url .= 'customers/'.$customer['id'].'/subscriptions';
// $s->method = "POST";
// $s->fields['plan'] = $_POST['plan']; // name of the stripe plan i.e. my_stripe_plan
// // credit card details
// $s->fields['source'] = array(
//     'object' => 'card',
//     'exp_month' => $_POST['card_exp_month'],
//     'exp_year' => $_POST['card_exp_year'],
//     'number' => $_POST['card_number'],
//     'cvc' => $_POST['card_cvc']
// );
// $subscription = $s->call();