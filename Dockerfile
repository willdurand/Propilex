FROM debian:wheezy
MAINTAINER William Durand <william.durand1@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -y
RUN apt-get install -y nginx php5-fpm php5-sqlite php5-cli supervisor curl git-core

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Install NodeJS
RUN curl -sL https://deb.nodesource.com/setup | bash -
RUN apt-get install -y nodejs

# We do two things here. First, configuring php5-fpm and nginx to run in the
# foreground so supervisord can keep track of them later. Then we configure
# php5-fpm to run with the user as the web server, to avoid a few issues with
# file permissions.
RUN sed -e 's/;daemonize = yes/daemonize = no/' -i /etc/php5/fpm/php-fpm.conf
RUN sed -e 's/;listen\.owner/listen.owner/' -i /etc/php5/fpm/pool.d/www.conf
RUN sed -e 's/;listen\.group/listen.group/' -i /etc/php5/fpm/pool.d/www.conf
RUN echo "\ndaemon off;" >> /etc/nginx/nginx.conf

# Install a couple of configuration files.
ADD app/config/docker/vhost.conf /etc/nginx/sites-available/default
ADD app/config/docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Install Propilex
ADD . /srv/propilex
WORKDIR /srv/propilex

RUN composer install --prefer-dist --no-dev
RUN npm install
RUN ./node_modules/.bin/bower install --allow-root
RUN cp app/config/propel/runtime-conf.xml.dist app/config/propel/runtime-conf.xml
RUN cp app/config/propel/build.properties.dist app/config/propel/build.properties
RUN chmod +x vendor/propel/propel1/generator/bin/phing.php
RUN bin/bootstrap
RUN chown -R www-data:www-data app/cache

# Forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80

CMD ["/usr/bin/supervisord"]
