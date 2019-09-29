# Laravel EPP
[![Latest Stable Version](https://poser.pugx.org/ywatchman/laravel-epp/v/stable)](https://packagist.org/packages/ywatchman/laravel-epp)
![StyleCI](https://github.styleci.io/repos/211557879/shield)

### Installing with auto-discovery enabled
```bash
composer require ywatchman/laravel-epp
php artisan vendor:publish --provider="YWatchman\LaravelEPP\ServiceProvider"
```

### .env
```env
EPP_SETTINGS_FILE=/etc/cyberfusion/epp.ini
```

### epp.ini
```ini
interface=eppConnection
hostname=ssl://drs.domain-registry.nl
port=700
userid=
password=
logging=false
verifypeer=true
verifypeername=true
allowselfsigned=false
```
