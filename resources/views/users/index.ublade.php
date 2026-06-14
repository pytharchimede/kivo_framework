@extends('layouts.app')
@section('content')
<x-page-header title="Utilisateurs" subtitle="Gestion des comptes, rôles et accès." />
<section class="amani-card">
  <x-select-search name="service_id" label="Service" placeholder="Rechercher un service" />
</section>
@endsection
