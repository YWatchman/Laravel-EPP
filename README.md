# Laravel EPP

### Installing with auto-discovery enabled
```bash
composer config repositories.laravel-epp vcs git@vcs.cyberfusion.nl:ywatchman/laravel-epp.git
composer require ywatchman/laravel-epp
```

### .env
```env
EPP_SETTINGS_FILE=/etc/cyberfusion/epp.ini
```

### epp.ini
```ini
interface=eppConnection
hostname=ssl://testdrs.domain-registry.nl
port=700
userid=304820
password=_H(c^4-+3f)V%$,'
logging=true
verifypeer=true
verifypeername=true
allowselfsigned=false
```
