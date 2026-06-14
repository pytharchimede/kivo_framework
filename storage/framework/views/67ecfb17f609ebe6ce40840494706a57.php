<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= \Core\View\View::e($title ?? config('app.name', 'AMANI Framework')) ?></title>
  <link rel="stylesheet" href="/assets/css/amani.css">
</head>
<body>
  <header class="amani-topbar">
    <div class="amani-topbar-inner">
      <a href="/" class="amani-brand"><span class="amani-brand-mark">A</span><span>AMANI Framework</span></a>
      <nav>ERP-first • API Mobile • Components</nav>
    </div>
  </header>
  <main class="amani-container">
    <?= $__sections["content"] ?? "" ?>
  </main>
</body>
</html>
<?php if(isset($__layout)){ echo \Core\View\View::renderLayout($__layout, get_defined_vars()); } ?>