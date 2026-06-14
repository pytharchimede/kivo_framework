# Moteur de vues KIVO

Depuis v0.3.0, le moteur de vues est volontairement strict.

## Interdit

```php
<?php echo "test"; ?>
```

Le PHP brut est interdit dans les fichiers `.ublade.php`.

## Autorisé

```html
@extends('layouts.app')
@section('content')
@if($user)
  Bonjour {{ $user['name'] }}
@endif
<x-input name="email" label="Email" />
@endsection
```

## Composants

Les composants natifs sont déclarés dans :

```txt
core/View/ComponentRegistry.php
```

Les tags comme `<x-input />`, `<x-page-header />`, `<x-select-search />` appellent directement des classes PHP (`App\View\Components\Form`, `Ui`, etc.).

## Vérification

Avant de livrer :

```powershell
php kivo verifier
```

Cette commande vérifie la syntaxe PHP du framework et la compilation des vues.
