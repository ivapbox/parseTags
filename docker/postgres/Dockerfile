FROM postgres:11

COPY ./docker/postgres/allowed_hosts.sh /docker-entrypoint-initdb.d

CMD ["postgres"]

EXPOSE 5432
