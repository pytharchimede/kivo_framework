<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? config('app.name', 'KIVO Framework') }}</title>
  <link rel="stylesheet" href="/assets/css/kivo.css">
</head>
<body>
  <header class="kivo-topbar">
    <div class="kivo-topbar-inner">
      <a href="/" class="kivo-brand"><span class="kivo-brand-mark">K</span><span>KIVO Framework</span></a>
      <nav>ERP-first • API Mobile • Components</nav>
    </div>
  </header>
  <main class="kivo-container">
    @yield('content')
  </main>
  <script src="/assets/js/kivo-components.js"></script>
</body>
</html>
