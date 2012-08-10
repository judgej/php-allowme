This script will allow users to add their IP address to the "allow" list 
in a .htaccess file.

The idea is for staging sites, to ensure search engines cannot access the
site and visitors do not accidentally find it. By running this script,
a visitor can add their IP address to the htaccess file, so they gain
full access without any further passwords or access problems.

This is a little "security by obscurity" at the moment - you get access to
the domain provided you know the front-door URL, but it is not intended as
a security feature - it is really only there to keep Google out, since
Google seems to find out about anything. 

Clients can access the site from dynamic home broadband IPs without the
list having to be maintained by anyone centrally. It is just a convenience.

Quickstart usage:

1. Clone this repo to a folder in the root web directory of your site:  
$ git submodule add git@github.com:judgej/php-allowme.git allowme  
or  
$ git clone git@github.com:judgej/php-allowme.git allowme

2. Make sure you have a .htaccess file in your web root folder and that 
the apache process is able to write to it.

3. Go to the "allowme" script to start:

http://your.domain.example.com/allowme

This will create a section in .htaccess that will deny access to all IP
addresses by default, but add your IP address as an exception. Every remote
IP that accesses the allowme page, will have their IP address added to the
exception list.

There is no further security on the script at present, but simple passwords
can be added if there is a demand.