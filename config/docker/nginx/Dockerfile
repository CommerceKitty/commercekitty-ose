FROM alpine:3.10

RUN apk add --update --no-cache nginx

COPY nginx.conf /etc/nginx/
COPY commercekitty.local.conf /etc/nginx/conf.d/default.conf
COPY commercekitty.local.crt /etc/ssl/certs/commercekitty.local.crt
COPY commercekitty.local.key /etc/ssl/private/commercekitty.local.key

RUN echo "upstream php-upstream { server php:9001; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -D -g '' -G www-data www-data

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
