Datenbank Klassen
===

Module können Klassen erstellen, die die Klasse `DatabaseTable` erweitern.


Um aus einer dieser Tabellen-Klassen das SQL zum erstellen der DB zu bekommen, kannst man ganz einfach die Funktion `getTableCreationSQL` aufrufen:
```
    $edb = new \modules\Berichtsheft\Database\EntryTable();
    $sql = $edb->getTableCreationSQL("db1");
```
Der Parameter sollte dafür die aktive DB sein. (TODO: Evtl aus Config auslesen!)
