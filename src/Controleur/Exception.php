<?php
/**
 * Classe isEmptyException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'une valeur est vide.
 **/
class isEmptyException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Un champ est vide, veuillez remplir tous les champs.")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Un champ est vide, veuillez remplir tous les champs.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}










/**
 * Classe incorrectLoginException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'un login est incorrect.
 **/
class incorrectLoginException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Nom d'utilisateur ou mot de passe incorrect")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Nom d'utilisateur ou mot de passe incorrect", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}










/**
 * Classe notFoundClientException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'un idClient est incorrect.
 **/
class notFoundClientException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Aucun client trouvé")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Aucun client trouvé", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}















/**
 * Classe notFoundAccountException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'un débit est supérieur au solde et au découvert.
 **/
class soldeInsuffisantException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Vous ne pouvez pas débiter plus que le solde et le découvert")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Vous ne pouvez pas débiter plus que le solde et le découvert", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}


















/**
 * Classe notFoundAccountException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'un client veut créer un compte déjà existant.
 **/
class existingAccountException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Compte déjà existant")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Compte déjà existant", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
















/**
 * Classe notFoundAccountException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'un client veut créer un compte ou un contrat avec lui même.
 **/
class clientIncorrectException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Vous ne pouvez pas créer un compte avec vous même")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Vous ne pouvez pas créer un compte avec vous même", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}




/**
 * Classe appointmentHoraireException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'un RDV est pris à une heure où il y a déjà un événement.
 **/
class appointmentHoraireException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Vous ne pouvez pas créer un rendez-vous à cette heure")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Vous ne pouvez pas créer un rendez-vous à cette heure", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}


/**
 * Classe HoraireException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'il y a un problème avec l'horaire
 **/
class HoraireException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Vous ne pouvez pas finir un rendez-vous avant de l'avoir commencé")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Vous ne pouvez pas finir un rendez-vous avant de l'avoir commencé", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}


/**
 * Classe TAHoraireException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'une TA est pris à une heure où il y a déjà un événement
 **/
class TAHoraireException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Vous ne pouvez pas créer une TA à cette heure")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Vous ne pouvez pas créer une TA à cette heure", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}



/**
 * Classe existingTypeAccountException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsque l'on veut créer un type de compte déjà existant
 **/
class existingTypeAccountException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Vous ne pouvez pas créer une deuxième fois le même type de compte")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Vous ne pouvez pas créer une deuxième fois le même type de compte", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

/**
 * Classe existingTypeContractException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsque l'on veut créer un type de contrat déjà existant
 **/
class existingTypeContractException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Vous ne pouvez pas créer une deuxième fois le même type de contrat")
     * @param int $code Code d'erreur (par défaut : 0)
     * @return void
     */
    public function __construct($message="Vous ne pouvez pas créer une deuxième fois le même type de contrat", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    /**
     * Fonction qui permet d'afficher l'erreur
     *
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}






