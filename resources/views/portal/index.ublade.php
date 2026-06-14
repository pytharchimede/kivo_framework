@extends('layouts.app')
@section('content')
<x-page-header title="Portail de sélection" subtitle="Choisissez un module métier pour continuer." />
<div class="kivo-grid">
  <x-module-card title="RH" description="Personnel, services, fonctions et documents RH" href="/rh" />
  <x-module-card title="Utilisateurs" description="Comptes, profils et accès" href="/users" />
  <x-module-card title="Permissions" description="Rôles, droits et matrice ACL" href="/permissions" />
  <x-module-card title="Site web" description="Pages publiques, contact et contenus" href="/site-admin" />
</div>
@endsection
