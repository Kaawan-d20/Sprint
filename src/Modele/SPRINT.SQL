DROP DATABASE IF EXISTS MLR;

CREATE DATABASE IF NOT EXISTS SPRINT;
USE MLR;
# -----------------------------------------------------------------------------
#       TABLE : RDV
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS RDV
 (
   IDRDV INTEGER(8) NOT NULL AUTO_INCREMENT ,
   IDMOTIF INTEGER(2) NOT NULL  ,
   IDCLIENT INTEGER(4) NOT NULL  ,
   IDEMPLOYE INTEGER(4) NOT NULL  ,
   HORAIRE DATETIME NULL  
   , PRIMARY KEY (IDRDV) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE RDV
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_RDV_MOTIF
     ON RDV (IDMOTIF ASC);

CREATE  INDEX I_FK_RDV_CLIENT
     ON RDV (IDCLIENT ASC);

CREATE  INDEX I_FK_RDV_EMPLOYE
     ON RDV (IDEMPLOYE ASC);

# -----------------------------------------------------------------------------
#       TABLE : MOTIF
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS MOTIF
 (
   IDMOTIF INTEGER(2) NOT NULL AUTO_INCREMENT ,
   INTITULE VARCHAR(32) NULL  
   , PRIMARY KEY (IDMOTIF) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : TACHEADMIN
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TACHEADMIN
 (
   IDTA INTEGER(8) NOT NULL AUTO_INCREMENT ,
   IDEMPLOYE INTEGER(4) NOT NULL  ,
   HORAIRE DATETIME NULL  ,
   LIBELLE VARCHAR(32) NULL  
   , PRIMARY KEY (IDTA) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE TACHEADMIN
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_TACHEADMIN_EMPLOYE
     ON TACHEADMIN (IDEMPLOYE ASC);

# -----------------------------------------------------------------------------
#       TABLE : TYPECOMPTE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TYPECOMPTE
 (
   IDTYPECOMPTE INTEGER(2) NOT NULL AUTO_INCREMENT ,
   IDMOTIF INTEGER(2) NOT NULL  ,
   NOM VARCHAR(32) NULL  ,
   ACTIF BOOL NULL  
      DEFAULT 1
   , PRIMARY KEY (IDTYPECOMPTE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE TYPECOMPTE
# -----------------------------------------------------------------------------


CREATE UNIQUE INDEX I_FK_TYPECOMPTE_MOTIF
     ON TYPECOMPTE (IDMOTIF ASC);

# -----------------------------------------------------------------------------
#       TABLE : CONTRAT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CONTRAT
 (
   IDCONTRAT INTEGER(8) NOT NULL AUTO_INCREMENT ,
   IDTYPECONTRAT INTEGER(2) NOT NULL  ,
   INTITULE VARCHAR(32) NULL  ,
   TARIFMENSUEL DECIMAL(13,2) NULL  ,
   DATEOUVERTURE DATE NULL  
   , PRIMARY KEY (IDCONTRAT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE CONTRAT
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_CONTRAT_TYPECONTRAT
     ON CONTRAT (IDTYPECONTRAT ASC);

# -----------------------------------------------------------------------------
#       TABLE : CLIENT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CLIENT
 (
   IDCLIENT INTEGER(4) NOT NULL AUTO_INCREMENT ,
   IDEMPLOYE INTEGER(4) NOT NULL  ,
   NOM VARCHAR(32) NULL  ,
   PRENOM VARCHAR(32) NULL  ,
   DATENAISSANCE DATE NULL  ,
   DATECREATION DATE NULL  ,
   ADRESSE VARCHAR(128) NULL  ,
   NUMTEL VARCHAR(16) NULL  ,
   EMAIL VARCHAR(64) NULL  ,
   PROFESSION VARCHAR(32) NULL  ,
   SITUATIONFAMILIALE VARCHAR(8) NULL  ,
   CIVILITEE VARCHAR(8) NULL  
   , PRIMARY KEY (IDCLIENT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE CLIENT
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_CLIENT_EMPLOYE
     ON CLIENT (IDEMPLOYE ASC);

# -----------------------------------------------------------------------------
#       TABLE : DOCUMENT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS DOCUMENT
 (
   IDDOCUMENT INTEGER(4) NOT NULL AUTO_INCREMENT ,
   INTITULE VARCHAR(32) NULL  
   , PRIMARY KEY (IDDOCUMENT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : CATEGORIE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CATEGORIE
 (
   IDCATEGORIE INTEGER(2) NOT NULL AUTO_INCREMENT ,
   LIBELLECATEGORIE VARCHAR(32) NULL  
   , PRIMARY KEY (IDCATEGORIE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : TYPECONTRAT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS TYPECONTRAT
 (
   IDTYPECONTRAT INTEGER(2) NOT NULL AUTO_INCREMENT ,
   IDMOTIF INTEGER(2) NOT NULL  ,
   NOM VARCHAR(32) NULL  ,
   ACTIF BOOL NULL  
      DEFAULT 1
   , PRIMARY KEY (IDTYPECONTRAT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE TYPECONTRAT
# -----------------------------------------------------------------------------


CREATE UNIQUE INDEX I_FK_TYPECONTRAT_MOTIF
     ON TYPECONTRAT (IDMOTIF ASC);

# -----------------------------------------------------------------------------
#       TABLE : EMPLOYE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS EMPLOYE
 (
   IDEMPLOYE INTEGER(4) NOT NULL AUTO_INCREMENT ,
   IDCATEGORIE INTEGER(2) NOT NULL  ,
   NOM VARCHAR(32) NULL  ,
   PRENOM VARCHAR(32) NULL  ,
   LOGIN VARCHAR(32) NULL  ,
   PASSWORD VARCHAR(128) NULL  ,
   SALT VARCHAR(32) NULL  
   , PRIMARY KEY (IDEMPLOYE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE EMPLOYE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_EMPLOYE_CATEGORIE
     ON EMPLOYE (IDCATEGORIE ASC);

# -----------------------------------------------------------------------------
#       TABLE : COMPTE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS COMPTE
 (
   IDCOMPTE INTEGER(8) NOT NULL AUTO_INCREMENT ,
   IDTYPECOMPTE INTEGER(2) NOT NULL  ,
   INTITULE VARCHAR(32) NULL  ,
   SOLDE DECIMAL(13,2) NULL  ,
   DECOUVERT DECIMAL(13,2) NULL  ,
   DATECREATION DATE NULL  
   , PRIMARY KEY (IDCOMPTE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE COMPTE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_COMPTE_TYPECOMPTE
     ON COMPTE (IDTYPECOMPTE ASC);

# -----------------------------------------------------------------------------
#       TABLE : POSSEDECONTRAT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS POSSEDECONTRAT
 (
   IDCLIENT INTEGER(4) NOT NULL  ,
   IDCONTRAT INTEGER(8) NOT NULL  
   , PRIMARY KEY (IDCLIENT,IDCONTRAT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE POSSEDECONTRAT
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_POSSEDECONTRAT_CLIENT
     ON POSSEDECONTRAT (IDCLIENT ASC);

CREATE  INDEX I_FK_POSSEDECONTRAT_CONTRAT
     ON POSSEDECONTRAT (IDCONTRAT ASC);

# -----------------------------------------------------------------------------
#       TABLE : DOITAPPORTER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS DOITAPPORTER
 (
   IDMOTIF INTEGER(2) NOT NULL  ,
   IDDOCUMENT INTEGER(4) NOT NULL  
   , PRIMARY KEY (IDMOTIF,IDDOCUMENT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE DOITAPPORTER
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_DOITAPPORTER_MOTIF
     ON DOITAPPORTER (IDMOTIF ASC);

CREATE  INDEX I_FK_DOITAPPORTER_DOCUMENT
     ON DOITAPPORTER (IDDOCUMENT ASC);

# -----------------------------------------------------------------------------
#       TABLE : POSSEDECOMPTE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS POSSEDECOMPTE
 (
   IDCOMPTE INTEGER(8) NOT NULL  ,
   IDCLIENT INTEGER(4) NOT NULL  
   , PRIMARY KEY (IDCOMPTE,IDCLIENT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE POSSEDECOMPTE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_POSSEDECOMPTE_COMPTE
     ON POSSEDECOMPTE (IDCOMPTE ASC);

CREATE  INDEX I_FK_POSSEDECOMPTE_CLIENT
     ON POSSEDECOMPTE (IDCLIENT ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE RDV 
  ADD FOREIGN KEY FK_RDV_MOTIF (IDMOTIF)
      REFERENCES MOTIF (IDMOTIF) ;


ALTER TABLE RDV 
  ADD FOREIGN KEY FK_RDV_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;


ALTER TABLE RDV 
  ADD FOREIGN KEY FK_RDV_EMPLOYE (IDEMPLOYE)
      REFERENCES EMPLOYE (IDEMPLOYE) ;


ALTER TABLE TACHEADMIN 
  ADD FOREIGN KEY FK_TACHEADMIN_EMPLOYE (IDEMPLOYE)
      REFERENCES EMPLOYE (IDEMPLOYE) ;


ALTER TABLE TYPECOMPTE 
  ADD FOREIGN KEY FK_TYPECOMPTE_MOTIF (IDMOTIF)
      REFERENCES MOTIF (IDMOTIF) ;


ALTER TABLE CONTRAT 
  ADD FOREIGN KEY FK_CONTRAT_TYPECONTRAT (IDTYPECONTRAT)
      REFERENCES TYPECONTRAT (IDTYPECONTRAT) ;


ALTER TABLE CLIENT 
  ADD FOREIGN KEY FK_CLIENT_EMPLOYE (IDEMPLOYE)
      REFERENCES EMPLOYE (IDEMPLOYE) ;


ALTER TABLE TYPECONTRAT 
  ADD FOREIGN KEY FK_TYPECONTRAT_MOTIF (IDMOTIF)
      REFERENCES MOTIF (IDMOTIF) ;


ALTER TABLE EMPLOYE 
  ADD FOREIGN KEY FK_EMPLOYE_CATEGORIE (IDCATEGORIE)
      REFERENCES CATEGORIE (IDCATEGORIE) ;


ALTER TABLE COMPTE 
  ADD FOREIGN KEY FK_COMPTE_TYPECOMPTE (IDTYPECOMPTE)
      REFERENCES TYPECOMPTE (IDTYPECOMPTE) ;


ALTER TABLE POSSEDECONTRAT 
  ADD FOREIGN KEY FK_POSSEDECONTRAT_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;


ALTER TABLE POSSEDECONTRAT 
  ADD FOREIGN KEY FK_POSSEDECONTRAT_CONTRAT (IDCONTRAT)
      REFERENCES CONTRAT (IDCONTRAT) ;


ALTER TABLE DOITAPPORTER 
  ADD FOREIGN KEY FK_DOITAPPORTER_MOTIF (IDMOTIF)
      REFERENCES MOTIF (IDMOTIF) ;


ALTER TABLE DOITAPPORTER 
  ADD FOREIGN KEY FK_DOITAPPORTER_DOCUMENT (IDDOCUMENT)
      REFERENCES DOCUMENT (IDDOCUMENT) ;


ALTER TABLE POSSEDECOMPTE 
  ADD FOREIGN KEY FK_POSSEDECOMPTE_COMPTE (IDCOMPTE)
      REFERENCES COMPTE (IDCOMPTE) ;


ALTER TABLE POSSEDECOMPTE 
  ADD FOREIGN KEY FK_POSSEDECOMPTE_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;

