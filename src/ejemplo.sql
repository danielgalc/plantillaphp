CREATE EXTENSION IF NOT EXISTS pgcrypto;

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios (
    id bigserial PRIMARY KEY,
    usuario varchar (255) NOT NULL UNIQUE,
    password varchar (255) NOT NULL
);

DROP TABLE IF EXISTS noticias CASCADE;

CREATE TABLE noticias (
    id BIGSERIAL primary key,
    titular varchar(255) not null,
    noticia_usuario BIGSERIAL NOT NULL REFERENCES usuarios (id),
    created_at timestamp  NOT NULL DEFAULT localtimestamp(0),
    likes varchar (255)
);

DROP TABLE IF EXISTS facturas CASCADE;

CREATE TABLE facturas (
    id         bigserial  PRIMARY KEY,
    created_at timestamp  NOT NULL DEFAULT localtimestamp,
    usuario_id bigint NOT NULL REFERENCES usuarios (id)
);

DROP TABLE IF EXISTS noticias_factura CASCADE;

CREATE TABLE noticias_facturas (
    noticia_id bigint NOT NULL REFERENCES noticias (id),
    factura_id  bigint NOT NULL REFERENCES facturas (id),
    cantidad    int    NOT NULL,
    PRIMARY KEY (noticia_id, factura_id)
);

-- Carga inicial de datos de prueba:

INSERT INTO usuarios (usuario, password)
VALUES ('admin', crypt('admin', gen_salt('bf', 10))),
       ('pepe', crypt('pepe', gen_salt('bf', 10))),
       ('dani', crypt('dani', gen_salt('bf', 10)));

INSERT INTO noticias (titular, noticia_usuario, likes, created_at)
VALUES ('Troste', 3, 0, '2007-7-7 23:26:11'),
       ('Troste', 3, 0, '2017-10-10 23:26:11'),
       ('España pierde contra Japon', 2, 0, '2012-12-12 12:12:12');