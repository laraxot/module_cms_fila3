# Module Cms
Modulo dedicato alla gestione dei temi applicabili al progetto

## Aggiungere Modulo nella base del progetto
Dentro la cartella laravel/Modules

```bash
git submodule add https://github.com/laraxot/module_cms_fila3.git Cms
```

## Verificare che il modulo sia attivo
```bash
php artisan module:list
```
in caso abilitarlo
```bash
php artisan module:enable Cms
```

## Eseguire le migrazioni
```bash
php artisan module:migrate Cms
```