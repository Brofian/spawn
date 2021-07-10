*Was ist Spawn?*
---
"Standard PHP Application without Norms"

Spawn ist ein simples PHP Framework, welches an die Vorlagen bekannter Frameworks wie Shopware, Wordpress oder Magento angelehnt ist.

Spawn selbst ist allerdings deutlich kleiner und eingeschränkter im Umfang der Funktionen, was es praktikabler und platzsparender für kleinere Privat-Projekte macht.   

Die Inhalte und Funktionen werden über sogenannte Module bereit gestellt. Diese können nach belieben eingefügt und entfernt werden.

#### Vorraussetzungen
- Ein Webserver mit mindestens PHP 7.2
- Eine möglichst moderne SQL Datenbank
- composer 2.*
- (Wenn möglich: NodeJS, npm und npx. Wird über composer hinzugefügt, wenn nicht vorhanden)

#### Installation
1) Die neueste Version von https://github.com/F-H00/spawn in das gewünschte Web-Root Verzeichnis klonen
2) Im Projekt-Root die composer Dependencies mit `composer install` installieren
3) Auf Basis der .sample Dateien die `config.php` im Projekt-Root anlegen
4) Im Projekt-Root über die CLI mit dem Befehl `bin/console migrations:execute` die Datenbank erstellen
5) Im Projekt-Root über die CLI mit dem Befehl `bin/console modules:refresh` die Module erstmals laden
6) Optional: Im Projekt-Root den Cache erstmals mit `bin/console webu:build` aufbauen