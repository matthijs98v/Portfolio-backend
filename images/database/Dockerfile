FROM postgres:alpine

COPY ./init/portfolio.sql /docker-entrypoint-initdb.d/

CMD ["postgres"]