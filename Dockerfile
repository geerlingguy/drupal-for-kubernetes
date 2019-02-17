# PHP Dependency install via Composer.
FROM composer as vendor

COPY composer.json composer.json
COPY composer.lock composer.lock
COPY scripts/ scripts/
COPY web/ web/

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-dev \
    --prefer-dist

# Build the Docker image for Drupal.
FROM geerlingguy/drupal:latest

ENV DRUPAL_MD5 aedc6598b71c5393d30242b8e14385e5

# Copy precompiled codebase into the container.
COPY --from=vendor /app/ /var/www/html/

# Copy other required configuration into the container.
COPY config/ /var/www/html/config/
COPY load.environment.php /var/www/html/load.environment.php
COPY pidramble.settings.php /var/www/html/web/sites/default/settings.php
RUN chown -R www-data:www-data /var/www/html/web

# Adjust the Apache docroot.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/web
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
