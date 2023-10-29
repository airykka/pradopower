@component('mail::message')

 #Hi {{$details['last_name']}}
<br> 
<p>Your purchase of {{$details['units']}} of power was {{$details['status']}}. Find below details:</p>

@if ($details['status'] === 'successful')
<div>
  <strong>Meter Number: </strong> {{$details['meterNo']}} <br>
  <strong>Token:</strong> {{$details['token']}}<br>
  <strong>Units:</strong> {{$details['units']}}<br>
  <strong>Amount:</strong> {{$details['amount']}}<br>
  <strong>Currency:</strong> {{$details['currency']}}<br>
  <strong>Reference:</strong> {{$details['reference']}}
</div>    
@endif

<p>
  Thank you for using our service. For compliants and inquiry, contact our support team at {{config('settings.support_email')}}
</p>

Regards
@endcomponent