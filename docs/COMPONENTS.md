# KIVO Components

KIVO Framework intègre deux formes de composants.

## 1. Composants PHP natifs

```php
use App\View\Components\Form;
use App\View\Components\Ui;

echo Ui::pageHeader('Personnel', 'Gestion RH', [
    'eyebrow' => 'Ressources humaines',
    'actions' => Ui::button('Ajouter', '/rh/personnel/create', 'accent'),
]);

echo Form::input('last_name', 'Nom', $user['last_name'] ?? '', ['required' => true]);
echo Form::selectSearch('service_id', 'Service', $services, $serviceId, ['placeholder' => 'Rechercher un service']);
echo Form::dropzone('photo', 'Photo du personnel', ['accept' => 'image/*']);
```

## 2. Composants de vues `.ublade.php`

```html
<x-page-header title="Personnel" subtitle="Gestion RH" />
<x-input name="last_name" label="Nom" />
<x-select-search name="service_id" label="Service" :options="$services" />
<x-dropzone name="photo" label="Photo du personnel" />
```

## Composants disponibles

- `Html::attrs()`
- `Html::classes()`
- `Form::field()`
- `Form::input()`
- `Form::textarea()`
- `Form::select()`
- `Form::selectSearch()`
- `Form::checkbox()`
- `Form::hidden()`
- `Form::dropzone()`
- `Ui::pageHeader()`
- `Ui::section()`
- `Ui::button()`
- `Ui::badge()`
- `Ui::emptyState()`

Les composants utilisent le préfixe CSS `kivo-*` et le script `/assets/js/kivo-components.js`.
