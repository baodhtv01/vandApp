FROM wyveo/nginx-php-fpm:php82

MAINTAINER andy@resonancetech.co

RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg

#Install extensions
RUN apt-get update -y --fix-missing\
    && apt-get install -y php8.2-soap \
    && apt-get install -y npm \
    && apt-get install -y openssl \
    && apt-get install -y curl


#Install SSL
ARG DOMAIN

ENV DOMAIN $DOMAIN

COPY ./docker/ssl/generate.sh /etc/nginx/ssl/generate-ssl.sh

RUN chmod +x /etc/nginx/ssl/generate-ssl.sh

RUN cd /etc/nginx/ssl && ./generate-ssl.sh

#Update NodeJS
COPY ./docker/script/npm-update.sh /npm-update.sh

RUN chmod +x /npm-update.sh

RUN /npm-update.sh