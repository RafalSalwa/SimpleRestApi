FROM eboraas/apache
RUN a2enmod setenvif headers proxy proxy_fcgi rewrite
COPY ./vhost.conf /etc/apache2/sites-enabled/000-default.conf
