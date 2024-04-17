FROM php:8.3-cli-bookworm

RUN apt-get update && apt-get install -y \
    git zlib1g-dev libzip-dev zip \
    libicu-dev
# git zlib1g-dev libzip-dev zip - zip libraries and extension. Needed for flex recipes
# libicu-dev - intl library and extension

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN docker-php-ext-configure intl && docker-php-ext-install intl

ARG USER_NAME=appuser
ARG USER_PASS=password
RUN useradd -d /home/$USER_NAME -m -s /bin/bash $USER_NAME && echo "$USER_NAME:$USER_PASS" | chpasswd && adduser $USER_NAME sudo
RUN chown -R $USER_NAME:$USER_NAME /home/$USER_NAME
USER $USER_NAME

COPY docker/.bash_aliases /home/$USER_NAME/.bash_aliases
COPY docker/init.sh /app/init.sh

WORKDIR /app/main

CMD /app/init.sh
