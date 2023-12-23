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
class soldeInsuffisantException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Aucun client trouvé")
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
class existingAccountException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Aucun client trouvé")
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
class clientIncorrectException extends Exception{
    /**
     * Constructeur de la classe
     *
     * @param string $message Message d'erreur à afficher (par défaut : "Aucun client trouvé")
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