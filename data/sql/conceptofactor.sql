
-- DROP TABLE IF EXISTS  confactornombrecomun;
DROP TABLE IF EXISTS  conceptofactor;
DROP TABLE IF EXISTS  grupoconceptofactor;
DROP TABLE IF EXISTS  simulacioncompensacion;

CREATE TABLE simulacioncompensacion(
    id serial,
    codigo integer NOT NULL,
    idnombrecomun character(4) NOT NULL,
    cantidadnombrecomun integer NOT NULL,
    fecharegistro TIMESTAMP DEFAULT (now()),
    valor numeric NOT NULL,
    CONSTRAINT simulacioncompensacionn_pkey PRIMARY KEY (id),
    CONSTRAINT U_idnombrecomun_codigo UNIQUE (idnombrecomun, codigo),
    CONSTRAINT FK_b_nombrecomun foreign key (idnombrecomun) REFERENCES b_nombrecomun(idnombrecomun)
        ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE INDEX index_simulacioncompensacion ON simulacioncompensacion USING btree (codigo);


CREATE TABLE b_nombrecomun (
    idnombrecomun character(4) NOT NULL,
    nombrecomun character(65) NOT NULL,
    nombrecientifico character(3) NOT NULL,
    familia character(3) NOT NULL,
    vegetacion character(1) NOT NULL,
    nombredd character(23) NOT NULL,
    CONSTRAINT b_nombrecomun_pkey PRIMARY KEY (idnombrecomun)
);

INSERT INTO b_nombrecomun(idnombrecomun, nombrecomun, nombrecientifico, familia, vegetacion, nombredd) 
VALUES ('1','Acacia amarilla, Acacia siamea','1','32','1','Acacia amarilla, sia');
INSERT INTO b_nombrecomun(idnombrecomun, nombrecomun, nombrecientifico, familia, vegetacion, nombredd) 
VALUES ('10  ','Achiote, Achote','10 ','13 ','2','Achiote, Achote');
INSERT INTO b_nombrecomun(idnombrecomun, nombrecomun, nombrecientifico, familia, vegetacion, nombredd) 
VALUES ('100','Ciruelo del gobernador, Uva lisa','100','62 ','1','Ciruelo del gobernad');
INSERT INTO b_nombrecomun(idnombrecomun, nombrecomun, nombrecientifico, familia, vegetacion, nombredd) 
VALUES ('101','Ciruelo, Hobo, Jobo ','101','4  ','1','Ciruelo, Hobo, Jobo');
INSERT INTO b_nombrecomun(idnombrecomun, nombrecomun, nombrecientifico, familia, vegetacion, nombredd) 
VALUES ('102','Clavellino, Mariposa','102','32 ','2','Clavellino, Mariposa');
INSERT INTO b_nombrecomun(idnombrecomun, nombrecomun, nombrecientifico, familia, vegetacion, nombredd) 
VALUES ('103','Clemon, Algodon de arbol','103','42 ','1','Clemon, Algodon d ar');
INSERT INTO b_nombrecomun(idnombrecomun, nombrecomun, nombrecientifico, familia, vegetacion, nombredd) 
VALUES ('104','Coca','104','30 ','2','Coca');
INSERT INTO b_nombrecomun(idnombrecomun, nombrecomun, nombrecientifico, familia, vegetacion, nombredd) 
VALUES ('105','Cocobolo','105','32 ','1','Cocobolo');
INSERT INTO b_nombrecomun(idnombrecomun, nombrecomun, nombrecientifico, familia, vegetacion, nombredd) 
VALUES ('106','Cojón de Cabrito, Pepa de Cabra, Cajeto, Enebro','106','6','2','Cojon de cabrito');


CREATE TABLE grupoconceptofactor
(
    id serial,
    nombre character varying(500) NOT NULL,
    descripcion text,
    CONSTRAINT grupoconceptofactor_pkey PRIMARY KEY (id)

);
CREATE UNIQUE INDEX index_grupoconceptofactor ON grupoconceptofactor USING btree (nombre);


CREATE TABLE conceptofactor
(
    id serial,
    codigo character varying(200) NOT NULL,
    codigoequivalencia integer ,
    nombre character varying(500) NOT NULL,
    grupoconceptofactor_id integer NOT NULL,
    descripcion text,
    factor numeric NOT NULL,
    CONSTRAINT conceptofactor_pkey PRIMARY KEY (id),
    CONSTRAINT FK_grupoconceptofactor  foreign key (grupoconceptofactor_id) REFERENCES grupoconceptofactor(id)
        ON DELETE NO ACTION ON UPDATE CASCADE

);

CREATE UNIQUE INDEX index_conceptofactor ON conceptofactor USING btree (codigo);
CREATE INDEX index_conceptofactor_codigoequivalencia ON conceptofactor USING btree (codigoequivalencia);


-- CREATE TABLE confactornombrecomun
-- (
--     id serial,
--     nombrecomun_id character(4) NOT NULL,
--     conceptofactor_id integer NOT NULL,
--     descripcion text,
--     CONSTRAINT U_conceptofactor_nombrecomun UNIQUE (nombrecomun_id, conceptofactor_id),
--     CONSTRAINT FK_b_nombrecomun  foreign key (nombrecomun_id) REFERENCES b_nombrecomun(idnombrecomun)
--         ON DELETE NO ACTION ON UPDATE CASCADE,
--     CONSTRAINT FK_conceptofactor  foreign key (conceptofactor_id) REFERENCES conceptofactor(id)
--         ON DELETE NO ACTION ON UPDATE CASCADE
-- );

INSERT INTO grupoconceptofactor(nombre, descripcion) VALUES ('FIEoP ecológica registrada', 'FIEoP ecológica registrada');
INSERT INTO grupoconceptofactor(nombre, descripcion) VALUES ('L. registrada', 'L. registrada');
INSERT INTO grupoconceptofactor(nombre, descripcion) VALUES ('Estado fitosanitario', 'Estado fitosanitario');
INSERT INTO grupoconceptofactor(nombre, descripcion) VALUES ('Afectación a infraestructura', 'Afectación a infraestructura');

CREATE TABLE b_infraestructura
(
  idinterferencia character(1) NOT NULL,
  nombreinterferencia character(40) NOT NULL,
  CONSTRAINT b_infraestructura_pkey PRIMARY KEY (idinterferencia)
);

cREATE TABLE d_interferencia
(
  idinterferencia character(13) NOT NULL,
  descripinterferencia character(1) NOT NULL,
  idarbol2 bigint, -- llave alterna
  CONSTRAINT d_interferencia_pkey PRIMARY KEY (idinterferencia, descripinterferencia),
  CONSTRAINT d_interferencia_descripinterferencia_pkey FOREIGN KEY (descripinterferencia)
      REFERENCES b_infraestructura (idinterferencia) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT d_interferencia_idinterferencia_pkey FOREIGN KEY (idinterferencia)
      REFERENCES d_arbol (idarbol) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

  
INSERT INTO b_infraestructura(idinterferencia, nombreinterferencia)
VALUES ('1', 'Infraestructura Habitacional o Comercial');
INSERT INTO b_infraestructura(idinterferencia, nombreinterferencia)
VALUES ('2', 'Infraestructura Vial');
INSERT INTO b_infraestructura(idinterferencia, nombreinterferencia)
VALUES ('3', 'Redes de servicios');
INSERT INTO b_infraestructura(idinterferencia, nombreinterferencia)
VALUES ('4', 'Mobiliario Urbano');
INSERT INTO b_infraestructura(idinterferencia, nombreinterferencia)
VALUES ('5', 'Ninguna');

INSERT INTO d_interferencia(idinterferencia, descripinterferencia, idarbol2)
VALUES ('0201G25077421','3',5077421);
INSERT INTO d_interferencia(idinterferencia, descripinterferencia, idarbol2)
VALUES ('0201G25077423','4',5077423);
INSERT INTO d_interferencia(idinterferencia, descripinterferencia, idarbol2)
VALUES ('0201G34059971','3',4059971);
INSERT INTO d_interferencia(idinterferencia, descripinterferencia, idarbol2)
VALUES ('0201G39062451','5',9062451);



update d_arbol set fact_imp_eco = 1 where idarbol = '0201G25077423';
update d_arbol set idarbol2 = 5077423 where idarbol = '0201G25077423';

update d_arbol set idarbol2 = 9062493 where idarbol = '0201G39062493';
update d_arbol set fact_imp_eco = 1 where idarbol = '0201G39062493';

update d_arbol set fact_imp_eco = 1 where idarbol = '0201G25077421';
update d_arbol set idarbol2 = 5077421 where idarbol = '0201G25077421';

--Procedimiento para que se pueda comprar un entero con una cada de texto

CREATE OPERATOR PUBLIC.~~ (LEFTARG=integer, RIGHTARG=text, PROCEDURE=my_like);

CREATE OR REPLACE FUNCTION PUBLIC.my_like(leftop integer, rightop text)
RETURNS boolean
LANGUAGE sql
AS $function$
SELECT $1::text LIKE $2;
$function$;