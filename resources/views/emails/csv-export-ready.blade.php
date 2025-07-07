@component('mail::message')
# Your Export is Ready

Click the button below to download your complaint CSV file.

@component('mail::button', ['url' => $downloadUrl])
Download CSV
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
