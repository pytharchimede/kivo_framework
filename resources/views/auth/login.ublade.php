@extends('layouts.app')
@section('content')
<section class="kivo-auth-card">
  <div>
    <p class="kivo-eyebrow">ERP Starter</p>
    <h1>Connexion</h1>
    <p>Connectez-vous pour accéder au portail de sélection des modules.</p>
  </div>

  <form method="post" action="/login" class="kivo-form-stack">
    <x-input name="email" label="Adresse email" type="email" value="admin@demo.local" required="required" />
    <x-input name="password" label="Mot de passe" type="password" value="admin1234" required="required" />
    <button class="kivo-action-btn kivo-action-btn--accent" type="submit">Se connecter</button>
  </form>
</section>
@endsection
