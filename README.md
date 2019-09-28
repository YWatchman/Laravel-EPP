# Laravel EPP

### Installing with auto-discovery enabled
```bash
composer require ywatchman/laravel-epp
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
