<?
/**
 * Classe estVideException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'une valeur est vide.
 **/
class estVideException extends Exception{
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
 * Classe clientNonTrouverException
 *
 * Cette classe est utilisée pour gérer les exceptions lorsqu'un idClient est incorrect.
 **/
class clientNonTrouverException extends Exception{
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