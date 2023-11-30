<?
class estVideException extends Exception{
    public function __construct($message="Un champ est vide, veuillez remplir tous les champs.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class incorrectLoginException extends Exception{
    public function __construct($message="Nom d'utilisateur ou mot de passe incorrect", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

class incorrectIdClientException extends Exception{
    public function __construct($message="Aucun client trouvé", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}