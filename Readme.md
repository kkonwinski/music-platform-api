If is problem with "JWT Token not found":

- inside docker bash open apache2.conf in path /etc/apache
- add to end file 

SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1

- restart apache if no reaction docker-compose up -d
