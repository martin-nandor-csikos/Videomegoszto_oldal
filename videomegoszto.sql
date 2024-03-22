DROP TABLE video_cimke;
DROP TABLE video_kategoria;
DROP TABLE eredet;
DROP TABLE feltolto;
DROP TABLE iro;
DROP TABLE kedvenc;
DROP TABLE felhasznalo;
DROP TABLE kategoria;
DROP TABLE komment;
DROP TABLE video;
DROP TABLE cimke;


CREATE TABLE cimke (
  id NUMBER(10) NOT NULL,
  cim VARCHAR2(255) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT cim UNIQUE (cim)
);

COMMENT ON TABLE cimke IS 'A videók lehetséges címkéit tartalmazó tábla.';

CREATE SEQUENCE cimke_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER cimke_seq_tr
BEFORE INSERT ON cimke
FOR EACH ROW
WHEN (NEW.id IS NULL)
BEGIN
  SELECT cimke_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/

COMMENT ON COLUMN cimke.id IS 'A címke azonosítója (kulcs)';
COMMENT ON COLUMN cimke.cim IS 'A címke címe (egyedi)';

CREATE TABLE eredet (
  komment_id NUMBER(10) NOT NULL,
  video_id NUMBER(10) NOT NULL,
  PRIMARY KEY (komment_id, video_id)
);

COMMENT ON TABLE eredet IS 'A kommentek eredet videóját jelölő tábla.';
COMMENT ON COLUMN eredet.komment_id IS 'A komment azonosítója (kulcs)';
COMMENT ON COLUMN eredet.video_id IS 'A videó azonosítója (kulcs)';

CREATE INDEX komment_id ON eredet (komment_id);
CREATE INDEX video_id ON eredet (video_id);

CREATE TABLE felhasznalo (
  id NUMBER(10) NOT NULL,
  nev VARCHAR2(30) NOT NULL,
  email VARCHAR2(50) NOT NULL,
  jelszo VARCHAR2(255) NOT NULL,
  admin NUMBER(3) DEFAULT 0 NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT email_jelszo UNIQUE (email, jelszo)
);

COMMENT ON TABLE felhasznalo IS 'A felhasználók adatait tartalmazó tábla.';

CREATE SEQUENCE felhasznalo_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER felhasznalo_seq_tr
BEFORE INSERT ON felhasznalo
FOR EACH ROW
WHEN (NEW.id IS NULL)
BEGIN
  SELECT felhasznalo_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/

COMMENT ON COLUMN felhasznalo.id IS 'A felhasználó azonosítója (kulcs)';
COMMENT ON COLUMN felhasznalo.nev IS 'A felhasználónév';
COMMENT ON COLUMN felhasznalo.email IS 'A felhasználó email címe (jelszóval együtt egyedi)';
COMMENT ON COLUMN felhasznalo.jelszo IS 'A felhasználó jelszava titkosítva (emaillel együtt egyedi)';
COMMENT ON COLUMN felhasznalo.admin IS 'Igaz, ha a felhasználó adminisztrátor';

CREATE TABLE feltolto (
  felhasznalo_id NUMBER(10) NOT NULL,
  video_id NUMBER(10) NOT NULL,
  datum DATE NOT NULL,
  PRIMARY KEY (felhasznalo_id, video_id)
);

COMMENT ON TABLE feltolto IS 'A videók feltöltését leíró tábla.';

COMMENT ON COLUMN feltolto.felhasznalo_id IS 'A feltöltő felhasználó azonosítója (kulcs)';
COMMENT ON COLUMN feltolto.video_id IS 'A videó azonosítója (kulcs)';
COMMENT ON COLUMN feltolto.datum IS 'A videó feltöltésének dátuma';

CREATE INDEX video_id ON feltolto (video_id);
CREATE INDEX felhasznalo_id ON feltolto (felhasznalo_id);

CREATE TABLE iro (
  felhasznalo_id NUMBER(10) NOT NULL,
  komment_id NUMBER(10) NOT NULL,
  ido TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (felhasznalo_id, komment_id)
);

COMMENT ON TABLE iro IS 'A kommentek kiírását leíró tábla.';

COMMENT ON COLUMN iro.felhasznalo_id IS 'A feltöltő felhasználó azonosítója (kulcs)';
COMMENT ON COLUMN iro.komment_id IS 'A komment azonosítója (kulcs)';
COMMENT ON COLUMN iro.ido IS 'A komment kiírásának dátuma és időpontja';

CREATE INDEX felhasznalo_id ON iro (felhasznalo_id, komment_id);
CREATE INDEX komment_id ON iro (komment_id);

CREATE TABLE kategoria (
  id NUMBER(10) NOT NULL,
  cim VARCHAR2(60) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT cim_unique UNIQUE (cim)
);

COMMENT ON TABLE kategoria IS 'A videók lehetséges kategóriáit tartalmazó tábla.';

CREATE SEQUENCE kategoria_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER kategoria_seq_tr
BEFORE INSERT ON kategoria
FOR EACH ROW
WHEN (NEW.id IS NULL)
BEGIN
  SELECT kategoria_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/

COMMENT ON COLUMN kategoria.id IS 'A kategória azonosítója (kulcs)';
COMMENT ON COLUMN kategoria.cim IS 'A kategória címe (egyedi)';

CREATE TABLE kedvenc (
  felhasznalo_id NUMBER(10) NOT NULL,
  video_id NUMBER(10) NOT NULL,
  PRIMARY KEY (felhasznalo_id, video_id)
);

COMMENT ON TABLE kedvenc IS 'A felhasználók kedvelt videóit jelölő tábla.';

COMMENT ON COLUMN kedvenc.felhasznalo_id IS 'A felhasználó azonosítója (kulcs)';
COMMENT ON COLUMN kedvenc.video_id IS 'A kedvelt videó azonosítója (kulcs)';

CREATE INDEX felhasznalo_id ON kedvenc (felhasznalo_id, video_id);
CREATE INDEX video_id ON kedvenc (video_id);

CREATE TABLE komment (
  id NUMBER(10) NOT NULL,
  szoveg VARCHAR2(1000) NOT NULL,
  PRIMARY KEY (id)
);

COMMENT ON TABLE komment IS 'A kommentek szövegét tartalmazó tábla.';

CREATE SEQUENCE komment_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER komment_seq_tr
 BEFORE INSERT ON komment FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT komment_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/

COMMENT ON COLUMN komment.id IS 'A komment azonosítója (kulcs)';
COMMENT ON COLUMN komment.szoveg IS 'A komment szövege';

CREATE TABLE video (
  id number(10) NOT NULL,
  cim varchar2(255 char) NOT NULL,
  leiras varchar2(1000 char) NOT NULL,
  path varchar2(255 char) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT path UNIQUE  (path)
)  ;

COMMENT ON TABLE video IS 'A videók adatait tartalmazó tábla.';

CREATE SEQUENCE video_seq START WITH 1 INCREMENT BY 1;

CREATE OR REPLACE TRIGGER video_seq_tr
 BEFORE INSERT ON video FOR EACH ROW
 WHEN (NEW.id IS NULL)
BEGIN
 SELECT video_seq.NEXTVAL INTO :NEW.id FROM DUAL;
END;
/

COMMENT ON COLUMN video.id IS 'A videó azonosítója (kulcs)';
COMMENT ON COLUMN video.cim IS 'A videó címe';
COMMENT ON COLUMN video.leiras IS 'A videó leírása';
COMMENT ON COLUMN video.path IS 'A videó elérési útvonala (egyedi)';

CREATE TABLE video_cimke (
  video_id number(10) NOT NULL,
  cimke_id number(10) NOT NULL,
  PRIMARY KEY (video_id,cimke_id)
)  ;

COMMENT ON TABLE video_cimke IS 'A videók egy-egy címkéjét jelölő tábla.';

COMMENT ON COLUMN video_cimke.video_id IS 'A videó azonosítója (kulcs)';
COMMENT ON COLUMN video_cimke.cimke_id IS 'Egy címke azonosítója (kulcs)';

CREATE INDEX video_id ON video_cimke (video_id,cimke_id);
CREATE INDEX cimke_id ON video_cimke (cimke_id);


CREATE TABLE video_kategoria (
  video_id number(10) NOT NULL,
  kategoria_id number(10) NOT NULL,
  PRIMARY KEY (video_id,kategoria_id)
)  ;

COMMENT ON TABLE video_kategoria IS 'A videók kategoriáját jelölő tábla.';

COMMENT ON COLUMN video_kategoria.video_id IS 'A videó azonosítója (kulcs)';
COMMENT ON COLUMN video_kategoria.kategoria_id IS 'A kategória azonosítója (kulcs)';

CREATE INDEX video_id ON video_kategoria (video_id,kategoria_id);
CREATE INDEX kategoria_id ON video_kategoria (kategoria_id);

ALTER TABLE eredet
  ADD CONSTRAINT eredet_ibfk_1 FOREIGN KEY (komment_id) REFERENCES komment (id) ON DELETE CASCADE;
ALTER TABLE eredet
  ADD CONSTRAINT eredet_ibfk_2 FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE;

ALTER TABLE feltolto
  ADD CONSTRAINT feltolto_ibfk_1 FOREIGN KEY (felhasznalo_id) REFERENCES felhasznalo (id) ON DELETE CASCADE;
ALTER TABLE feltolto
  ADD CONSTRAINT feltolto_ibfk_2 FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE;

ALTER TABLE iro
  ADD CONSTRAINT iro_ibfk_1 FOREIGN KEY (felhasznalo_id) REFERENCES felhasznalo (id) ON DELETE CASCADE;
ALTER TABLE iro
  ADD CONSTRAINT iro_ibfk_2 FOREIGN KEY (komment_id) REFERENCES komment (id) ON DELETE CASCADE;

ALTER TABLE kedvenc
  ADD CONSTRAINT kedvenc_ibfk_1 FOREIGN KEY (felhasznalo_id) REFERENCES felhasznalo (id) ON DELETE CASCADE;
ALTER TABLE kedvenc
  ADD CONSTRAINT kedvenc_ibfk_2 FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE;

ALTER TABLE video_cimke
  ADD CONSTRAINT video_cimke_ibfk_1 FOREIGN KEY (cimke_id) REFERENCES cimke (id) ON DELETE CASCADE;
ALTER TABLE video_cimke
  ADD CONSTRAINT video_cimke_ibfk_2 FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE;

ALTER TABLE video_kategoria
  ADD CONSTRAINT video_kategoria_ibfk_1 FOREIGN KEY (kategoria_id) REFERENCES kategoria (id) ON DELETE CASCADE;
ALTER TABLE video_kategoria
  ADD CONSTRAINT video_kategoria_ibfk_2 FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE;
COMMIT;
