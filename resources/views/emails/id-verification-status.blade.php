@component('mail::message')
# {{ $verification->valid_id_status === 'verified' ? 'ID Verified' : 'ID Verification Update' }}

Hi {{ $verification->user->name }},

@if ($verification->valid_id_status === 'verified')
Good news! Your submitted valid ID has been **verified**. You're all set — no further action needed on your end.
@else
We were unable to verify the ID you submitted.

@if ($verification->remarks)
**Reason:** {{ $verification->remarks }}
@endif

Please log in to your account and re-upload a clear, valid ID to continue.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
