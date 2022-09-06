@component('mail::message')
# Reset Password <br>
Hi {{$user->name}}<br>
You have requested reset password. Please click  the button to reset your password. If u didn't request just ignore this email.<br>
This will be expired for 120 seconds<br>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
Reset Password
@endcomponent

Thanks,<br>
<strong style="color:'red'">Adike</strong>
@endcomponent
