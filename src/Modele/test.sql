CREATE TABLE IF NOT EXISTS COMPTE
 (
   IDCOMPTE INTEGER(8) NOT NULL AUTO_INCREMENT ,
   IDTYPECOMPTE INTEGER(2) NOT NULL  ,
   INTITULE VARCHAR(32) NULL  ,
   SOLDE DECIMAL(13,2) NULL  ,
   DECOUVERT DECIMAL(13,2) NULL  ,
   DATECREATION DATE NULL ,
   VERSION INTEGER(8) NOT NULL DEFAULT 0
   , PRIMARY KEY (IDCOMPTE)
   , KEY (VERSION)
 );




CREATE TABLE IF NOT EXISTS HISTORIQUE_COMPTE
 (
   IDCOMPTE INTEGER(8) NOT NULL AUTO_INCREMENT ,
   ACTION ENUM ('update', 'delete') DEFAULT NULL ,
   DATEACTION DATETIME DEFAULT NULL ,
   VERSION INTEGER(8) NOT NULL DEFAULT 0,
   ORIGINAL INT(8) NOT NULL DEFAULT 0,
   SOLDE DECIMAL(13,2) NULL
   , PRIMARY KEY (IDCOMPTE)
   , KEY (ORIGINAL)
   , KEY (ACTION)
   , KEY (DATEACTION)
   , KEY (VERSION)
 );


delimiter //
CREATE TRIGGER trig_avant_update_compte
BEFORE UPDATE ON COMPTE FOR EACH ROW
  BEGIN
    SET NEW.version = OLD.version + 1;
    INSERT INTO HISTORIQUE_COMPTE
      (IDCOMPTE, action, DATEACTION, SOLDE)
    VALUES
      (OLD.IDCOMPTE, 'update', NOW(), OLD.SOLDE);
  END;
//
CREATE TRIGGER trig_apres_delete_compte
AFTER DELETE ON COMPTE FOR EACH ROW
  BEGIN
    INSERT INTO HISTORIQUE_COMPTE
      (IDCOMPTE, action, DATEACTION, SOLDE)
    VALUES
      (OLD.IDCOMPTE, 'delete', NOW(), OLD.SOLDE);
  END;
//
delimiter ;