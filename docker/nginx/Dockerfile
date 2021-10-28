FROM nginx:1.16-alpine

# fix a problem--#397, change application source from dl-cdn.alpinelinux.org to aliyun source.
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.aliyun.com/' /etc/apk/repositories

RUN apk update \
    && apk upgrade \
    && apk add --no-cache bash \
    && adduser -D -H -u 1000 -G www-data -s /bin/bash www-data \
    && rm /etc/nginx/conf.d/default.conf

COPY docker/nginx/nginx.conf /etc/nginx/
COPY docker/nginx/sites /etc/nginx/sites-available

COPY src /var/www/app

CMD ["nginx"]

EXPOSE 80 443
