FROM node:14-alpine

WORKDIR /var/www/html

RUN apk add --update alpine-sdk && \
    apk add libffi-dev openssl-dev python2 && \
    apk --no-cache --update add build-base

COPY ./src/package.json ./src/yarn.lock ./
RUN yarn install

COPY ./src .

RUN yarn build

ENV HOST 0.0.0.0
