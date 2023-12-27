dans `C:\Windows\System32\drivers\etc` dans le fichier `hosts` ajouter à la fin `127.0.0.1          dev.sprint.com`
dans `C:\xampp\apache\conf\extra` dans `httpd-vhosts.conf` ajouter le pavé suivant à la fin

<VirtualHost dev.sprint.com:80> 
    ServerName dev.sprint.com
    DocumentRoot "C:\Users\danyd\OneDrive\ALicence\Sprint\src"
    <Directory "C:\Users\danyd\OneDrive\ALicence\Sprint\src">
         Require all granted
         AllowOverride All
    </Directory>
	RewriteEngine on
	RewriteCond %{SERVER_NAME} =www.dev.sprint.com [OR]
	RewriteCond %{SERVER_NAME} =dev.sprint.com
	RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanant]
</VirtualHost>

<VirtualHost dev.sprint.com:443> 
    ServerName dev.sprint.com
    DocumentRoot "C:\Users\danyd\OneDrive\ALicence\Sprint\src"
    <Directory "C:\Users\danyd\OneDrive\ALicence\Sprint\src">
         Require all granted
         AllowOverride All
    </Directory>
	SSLEngine on
	SSLCertificateFile "cert/dev.sprint.com/server.crt"
	SSLCertificateKeyFile "cert/dev.sprint.com/server.key"
</VirtualHost>

dans `C:\xampp\apache` crée le fichier `v3.ext` avec 

subjectAltName = @alt_names
[alt_names]
DNS.1 =localhost
DNS.2 =*.dev.sprint.com
DNS.3 =dev.sprint.com
DNS.4 =127.0.0.1
DNS.5 =127.0.0.2


modifier le fichier `makercert.bat`


@echo off
set OPENSSL_CONF=./conf/openssl.cnf

if not exist .\conf\ssl.crt mkdir .\conf\ssl.crt
if not exist .\conf\ssl.key mkdir .\conf\ssl.key

bin\openssl req -new -out server.csr
bin\openssl rsa -in privkey.pem -out server.key
bin\openssl x509 -in server.csr -out server.crt -req -signkey server.key -days 365 -extfile v3.ext

set OPENSSL_CONF=
del .rnd
del privkey.pem
del server.csr

move /y server.crt .\conf\ssl.crt
move /y server.key .\conf\ssl.key

echo.
echo -----
echo Das Zertifikat wurde erstellt.
echo The certificate was provided.
echo.
pause


executé makercert.bat
dans Common Name mettre l'URL que l'on veux


prendre server.crt dans C:\xampp\apache\conf\ssl.crt
prendre server.key dans C:\xampp\apache\conf\ssl.key

crée C:\xampp\apache\cert\[nomDuSite] et les mettre dedans

installer le certificat dans pour l'ordianteur local
"placer tous les certificat dans le magain suivant" dans "autorité de certification racines de confiance"
terminer

si c'est pas bon voila la vidéo : https://www.youtube.com/watch?v=h7KXt4A4e1E