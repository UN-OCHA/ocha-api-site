FROM public.ecr.aws/unocha/unified-builder:8.1-stable as builder
ARG  BRANCH_ENVIRONMENT
ENV  APP_ENV=PROD \
     PUBLIC_DIR=/srv/www/public \
     COMPOSER_MEMORY_LIMIT=-1
COPY . /srv/www
WORKDIR /srv/www
RUN apk add -U --no-cache php81-sodium
RUN composer install --no-scripts --prefer-dist --optimize-autoloader
RUN bin/console assets:install public -vvv

FROM public.ecr.aws/unocha/php-k8s:8.1-stable

ARG VCS_REF
ARG VCS_URL
ARG BUILD_DATE
ARG GITHUB_ACTOR
ARG GITHUB_REPOSITORY
ARG GITHUB_SHA
ARG GITHUB_REF

ENV  NGINX_SERVERNAME=api.unocha.org
ENV  PHP_ENVIRONMENT=production
ENV  PHP_MEMORY_LIMIT=128M
ENV  PHP_MAX_CHILDREN=16
ENV  GIT_BLAME=$GITHUB_ACTOR
ENV  GIT_REPO=$GITHUB_REPOSITORY
ENV  GIT_SHA=$GITHUB_SHA
ENV  GIT_REF=$GITHUB_REF
ENV  GIT_MESSAGE=$GITHUB_MESSAGE
ENV  LOG_URL=/var/log/php/app.log
ENV  PHP_ERROR_LOG=/var/log/php/error.log

LABEL info.humanitarianresponse.build.date=$BUILD_DATE \
     info.humanitarianresponse.build.vcs-url=$VCS_URL \
     info.humanitarianresponse.build.vcs-ref=$VCS_REF

COPY --from=builder /srv/www/bin /srv/www/bin
COPY --from=builder /srv/www/config /srv/www/config
COPY --from=builder /srv/www/migrations /srv/www/migrations
COPY --from=builder /srv/www/public /srv/www/public
COPY --from=builder /srv/www/src /srv/www/src
COPY --from=builder /srv/www/templates /srv/www/templates
COPY --from=builder /srv/www/translations /srv/www/translations
COPY --from=builder /srv/www/vendor /srv/www/vendor
COPY --from=builder /srv/www/composer.json /srv/www/composer.json
COPY --from=builder /srv/www/composer.lock /srv/www/composer.lock
COPY --from=builder /srv/www/docker/manifest.json /srv/www/public/build/manifest.json
COPY --from=builder /srv/www/docker/99-elastic-apm-custom.ini /tmp/99-elastic-apm-custom.ini

RUN  wget https://get.symfony.com/cli/installer -O - | bash -s -- --install-dir=/usr/local/bin && \
     rm -rf /srv/www/html && \
     ln -s /srv/www/public /srv/www/html && \
     mkdir -p /srv/www/var/cache /srv/www/var/log /srv/www/var/sessions /etc/nginx/custom && \
     chown appuser:appuser /srv/www/var/cache /srv/www/var/log /srv/www/var/sessions && \
     curl -L -o /tmp/apm-agent-php_1.6.1_all.apk https://github.com/elastic/apm-agent-php/releases/download/v1.6.1/apm-agent-php_1.6.1_all.apk && \
     apk add --allow-untrusted /tmp/apm-agent-php_1.6.1_all.apk && \
     rm -f /tmp/apm-agent-php_1.6.1_all.apk && \
     mv -f /tmp/99-elastic-apm-custom.ini /etc/php81/conf.d/99-elastic-apm-custom.ini

COPY --from=builder /srv/www/docker/etc/nginx/map_block_http_methods.conf /etc/nginx/map_block_http_methods.conf
COPY --from=builder /srv/www/docker/etc/nginx/apps/drupal/drupal.conf /etc/nginx/apps/drupal/drupal.conf
COPY --from=builder /srv/www/docker/etc/nginx/custom/headers.conf /etc/nginx/custom/headers.conf
