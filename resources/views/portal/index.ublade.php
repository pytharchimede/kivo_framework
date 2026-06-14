@extends('layouts.app')
@section('content')
<x-page-header title="Portail de sélection" subtitle="Choisissez un module métier pour continuer." />
<div class="kivo-grid">
  <x-module-card title="RH" description="Personnel, services, fonctions et documents RH" href="/rh" />
  <x-module-card title="Utilisateurs" description="Comptes, profils et accès" href="/users" />
  <x-module-card title="Permissions" description="Rôles, droits et matrice ACL" href="/permissions" />
  <x-module-card title="Transit" description="Dossiers transit, colis, tracking, douane et logistique" href="/transit" />
</div>
@endsection
