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