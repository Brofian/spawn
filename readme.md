*What is Spawn?*
---
"Standard PHP Application without Norms"


Spawn is a simple PHP framework, following the pattern of bigger frameworks like e.g. Symfony.
But Spawn itself is significantly smaller and doesnt contain as much premade content as the bigger alternatives. This makes it
much more clear to work with and smaller in size. Perfect for small private Projects, that build up nearly from scratch.
 
The individual contents are delivered by implementing so called "modules". These are small blocks, that can freely be implemented or removed from the 
project. The contents are loaded automatically over a simple xml file system.


#### System Requirements
- A Webserver with at least PHP 7.4
- An SQL Database with MySQL or MariaDB
- Any Version of composer 2
- If possible, an installation of NodeJS, npm and npx (they are added via composer, if not found in the current path)

#### Installation
1) Clone the newest stable version from https://github.com/Brofian/spawn into your desired directory
    - If you wanna use a docker setup, execute `docker-compose up -d` and afterwards `mutagen project start` inside the repository root
    - If you wanna use a real web-server, define the directory `www/public` as your web-root
2) Open a shell inside the `www` directory and execute `composer install`
3) Rename all .sample files by removing the .sample ending and adjust the contents to your fitting
4) Prepare the (empty!) database, by executing `bin/console spawn:setup` inside the www directory
5) (Optional) Prepare the cache by executing `bin/console spawn:build`
