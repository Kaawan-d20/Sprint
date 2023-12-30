DROP DATABASE IF EXISTS SPRINT;

CREATE DATABASE IF NOT EXISTS SPRINT;
USE SPRINT;
# -----------------------------------------------------------------------------
#       TABLE : RDV
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS RDV
 (
   IDRDV INTEGER(8) NOT NULL AUTO_INCREMENT ,
   IDMOTIF INTEGER(2) NOT NULL  ,
   IDCLIENT INTEGER(4) NOT NULL  ,
   IDEMPLOYE INTEGER(4) NOT NULL  ,
   HORAIREDEBUT DATETIME NULL ,
   HORAIREFIN DATETIME NULL
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
   INTITULE VARCHAR(64) NULL ,
   DOCUMENT VARCHAR(128)
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
   HORAIREDEBUT DATETIME NULL  ,
  HORAIREFIN DATETIME NULL  ,
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
   NOM VARCHAR(64) NULL UNIQUE ,
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
   NOM VARCHAR(64) NULL UNIQUE ,
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
   LOGIN VARCHAR(32) NULL UNIQUE ,
   PASSWORD VARCHAR(128) NULL  ,
   COLOR VARCHAR(32) NOT NULL DEFAULT 'lush-green'
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
#       TABLE : OPERATION
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS OPERATION
  (
    IDOPERATION INTEGER(8) NOT NULL AUTO_INCREMENT ,
    IDCOMPTE INTEGER(8) NOT NULL ,
    SOURCE VARCHAR(32) NOT NULL ,
    LIBELLE VARCHAR(64) NOT NULL ,
    DATEOPERATION DATETIME NOT NULL ,
    MONTANT DECIMAL(5,2) ,
    ISCREDIT INTEGER(1),
    PRIMARY KEY (IDOPERATION)
  )
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : POSSEDECONTRAT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS POSSEDECONTRAT
 (
   IDCONTRAT INTEGER(8) NOT NULL ,
   IDCLIENT INTEGER(4) NOT NULL   
   , PRIMARY KEY (IDCONTRAT,IDCLIENT) 
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


ALTER TABLE POSSEDECOMPTE 
  ADD FOREIGN KEY FK_POSSEDECOMPTE_COMPTE (IDCOMPTE)
      REFERENCES COMPTE (IDCOMPTE) ;


ALTER TABLE POSSEDECOMPTE 
  ADD FOREIGN KEY FK_POSSEDECOMPTE_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;

ALTER TABLE OPERATION
  ADD FOREIGN KEY FK_OPERATION_COMPTE (IDCOMPTE)
      REFERENCES COMPTE (IDCOMPTE);