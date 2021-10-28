#!/bin/bash

echo "local  all  all  trust" > /var/lib/postgresql/data/pg_hba.conf
echo "host   all  all  127.0.0.1/32  trust" >> /var/lib/postgresql/data/pg_hba.conf
echo "host   all  all  ::1/128  trust" >> /var/lib/postgresql/data/pg_hba.conf
echo "host   all  all  172.0.01/8  trust" >> /var/lib/postgresql/data/pg_hba.conf

file_env 'POSTGRES_ALLOWED_CONNECTION_FROM'

if [[ -n "$POSTGRES_ALLOWED_CONNECTION_FROM" ]]; then
    echo "$POSTGRES_ALLOWED_CONNECTION_FROM" >> /var/lib/postgresql/data/pg_hba.conf
fi
