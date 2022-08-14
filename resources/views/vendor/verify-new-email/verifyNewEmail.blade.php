@component('mail::message')
# Olá!

Clique no botão abaixo para verificar seu novo endereço de e-mail

@component('mail::button', ['url' => $url])
Verificar novo E-mail
@endcomponent

Se você não atualizou seu e-mail, favor desconsiderar este e-mail

Saudações,<br>
{{ config('app.name') }}
@endcomponent
