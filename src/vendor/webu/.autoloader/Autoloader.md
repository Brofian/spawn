Autloader 
===

Der Autoader des WEBU Frameworks

Registrierung
---

Der Autoloader wird in der index.php als Aufruf einer Instanz der `Autloader Klasse` registriert. 


Ablauf
---
Beim Aufruf des Autoloaders wird die Funktion "autoload" aufgerufen und der Klassennamen mit dem namespace als string übergeben.


Der Namespace+Klassenname wird dann überprüft. Falls im Array `$classpaths` bereits ein Wert dafür existiert, wird die im Wert gespeicherte Datei über `require` eingebunden.

Falls im Array dieser Wert nicht existiert, wird überprüft ob der Array bereits generitert wurde:
- Ja: Der Autoloader gibt `false` zurück
- Nein: Es wird geprüft, ob die Datei `paths.txt` existiert, oder der Wert `$alwaysReload` auf true steht. Falls ja, wird die Datei über die Funktion `createPathsFile` neu generiert. Danach oder ansonsten wird die Datei ausgelesen und die Werte in den Array gespeichert. Anschließend wird erneut auf die Klasse überprüft.
