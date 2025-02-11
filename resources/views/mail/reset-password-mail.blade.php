<x-mail::message>
# Reset Password

Berikut Password baru Anda untuk login ke bisionline.com

Email:  {{ $email }}<br>
Reset Link: {{ $reset_link }}


<a href="{{ $reset_link }}">Reset Password Sekarang</a>


Terimakasih,<br>
{{ config('app.name') }}
</x-mail::message>
