FROM php:7.4.9-fpm-alpine3.12

WORKDIR /var/www/html

# Set "php.ini-development" or "php.ini-production"
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Install composer, enable
RUN curl -sS https://getcomposer.org/installer | php \
        && mv composer.phar /usr/local/bin/ \
        && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer
ENV PATH="~/.composer/vendor/bin:./vendor/bin:${PATH}"

# Install XDebug, enable
RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del pcre-dev ${PHPIZE_DEPS}

# - See: /usr/local/lib/php/doc/xdebug/xdebug.ini
RUN echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/php.ini
RUN echo "xdebug.default_enable=1" >> /usr/local/etc/php/php.ini
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/php.ini
RUN echo "xdebug.remote_autostart = 1" >> /usr/local/etc/php/php.ini
RUN echo "xdebug.remote_port = 9001" >> /usr/local/etc/php/php.ini
RUN echo "xdebug.remote_handler = dbgp" >> /usr/local/etc/php/php.ini
#RUN echo "xdebug.remote_host = $(getent hosts docker.for.mac.localhost | awk '{ print $1 }')" >> /usr/local/etc/php/php.ini
RUN echo "xdebug.profiler_enable=0" >> /usr/local/etc/php/php.ini

EXPOSE 9001

# Copy source files into the image, generate other files
COPY . /var/www/html
RUN composer install --prefer-source --no-interaction

# Output versions, files
RUN composer --version
RUN ls -al
