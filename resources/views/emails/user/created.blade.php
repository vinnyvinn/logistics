@component('mail::message')

    Hello, {{ ucwords($data['name']) }},
    Your account has been created successfully on Sovereign  Logistics.
    #Account Details
    Name : {{ ucwords($data['name']) }}
    Email : {{ $data['email'] }}

    #Login Details
    Email : {{ $data['email'] }}
    Password : {{ $data['password'] }}

    DO NOT SHARE YOUR CREDENTIALS WITH ANYONE
@component('mail::button', ['url' => 'http://esl-logistics.dnsalias.com'])
        CLICK TO LOGIN
@endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
