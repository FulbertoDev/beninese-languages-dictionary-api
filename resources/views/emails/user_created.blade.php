@component('mail::message')
    # Vous avez été invité à rejoindre l'équipe {{ config('app.name') }}

    Bonjour {{ $username }} ({{ $email }}),

    Vous avez reçu une invitation, de {{ config('app.name') }}, pour rejoindre
    l'équipe de contributeur.

    Voici les détails:<br>
    Nom: {{ $username }}<br>
    Email: {{ $email }}<br>
    Mot de passe: {{ $password }}<br>

    Ce mot de passe, est un mot de passe par défaut. Vous pourrez le modifier une fois connecté.

    @lang('Cordialement'),<br>
    {{ config('app.name') }}
@endcomponent
