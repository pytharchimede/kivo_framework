# CHANGELOG KIVO

## v0.3.1 - Component Registry Stable

- Correction d’une boucle infinie Xdebug dans `ComponentRegistry`.
- Suppression des alias auto-récursifs `page_header`, `module_card`, `select_search`, `empty_state`.
- Ajout d’une protection anti-récursion dans le rendu des composants.
- Vérification OK avec `php kivo verifier`.
- Test manuel OK sur `portal.index`.
