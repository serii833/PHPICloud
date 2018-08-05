# PHPICloud

Very basic functionality for Apple ICloud services:

* Find My iPhone service
* Account service

#### Find My iPhone service

* get list of devices, status, locations
* send message
* play sound

#### Account service

* get list of devices with serial number (only last 4 digits)

<br/>
##### Example:

```php
$icloud = new PHPICloud\PHPICloud('apple_id', 'pass');

$devices = $icloud->getFindMyiPhoneService()->devices();
foreach($devices as $d) {
	print $d->id . "\n";
}

print ($icloud->getAccountService()->devices()[0]->serialNumber);
```

<br/><br/>

Inspired by [pyicloud](https://github.com/picklepete/pyicloud) library.
