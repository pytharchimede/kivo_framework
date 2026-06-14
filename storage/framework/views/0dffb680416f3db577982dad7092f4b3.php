<?php $__layout="layouts.app"; ?>
<?php ob_start(); $__section="content"; ?>
<section class="amani-card">
  <h1>Site web généré par AMANI Framework</h1>
  <p>Cette base peut être remplacée par un template transit, médical, école, CRM ou entreprise.</p>
  <a class="amani-btn amani-btn-accent" href="/portal">Accéder au portail ERP</a>
</section>
<?php $__sections[$__section]=ob_get_clean(); ?>
<?php if(isset($__layout)){ echo \Core\View\View::renderLayout($__layout, get_defined_vars()); } ?>