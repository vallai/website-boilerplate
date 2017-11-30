<?php

namespace Sources\Models;

use Symfony\Component\Security\Core\User\UserInterface;
use Exception;
use PDO;

class Utilisateur implements UserInterface {

    /**
     * ID de l'utilisateur
     * @var int
     */
    protected $_id_utilisateur;

    /**
     * Email de l'utilisateur
     * @var string
     */
    protected $_email;

    /**
     * Username de l'utilisateur
     * @var string
     */
    protected $_username;

    /**
     * Nom de l'utilisateur
     * @var string
     */
    protected $_nom;

    /**
     * Prénom de l'utilisateur
     * @var string
     */
    protected $_prenom;
	
    /**
     * Rôle(s) de l'utilisateur
     * @var array<string>
     */
    private $_roles;

    /**
     * Mot de passe de l'utilisateur
     * @var string
     */
    private $_password;

    /**
     * Constructeur de l'utilisateur
     * @param string $id_utilisateur
     * @param string $email
     * @param string $nom
     * @param string $prenom
     */
    function __construct($id_utilisateur = null, $email = '', $username = '', $nom = '', $prenom = '', $roles = array(), $password = '') {
        // Représentation de Utilisateur
        $this->setId_utilisateur($id_utilisateur);
        $this->setEmail($email);
        $this->setUsername($username);
        $this->setNom($nom);
        $this->setPrenom($prenom);
        $this->setPassword($password);
        $this->setRoles($roles);
    }

    /**
     * (non-PHPdoc)
     * @see iBdd::save()
     */
    public function save() {
        global $pdo;

        try {
            // Préparation de la requête
            $requete = $pdo->prepare('
                INSERT INTO `utilisateur` (`id_utilisateur`, `email`, `username`,`nom`, `prenom`, `password`, `roles`)
                VALUES (
                    :id_utilisateur,
                    :email,
            		:username,
                    :nom,
                    :prenom,
            		:password,
                    :roles
                )
                ON DUPLICATE KEY UPDATE
                    `id_utilisateur` = :id_utilisateur,
                    `email` = :email,
            		`username` = :username,
                    `nom` = :nom,
                    `prenom` = :prenom,
                    `password` = :password,
                    `roles` = :roles
	        	;');

			// Affectation des variables
            $requete->bindValue('id_utilisateur', $this->_id_utilisateur, PDO::PARAM_INT);
            $requete->bindValue('email', $this->_email, PDO::PARAM_STR);
            $requete->bindValue('username', $this->_username, PDO::PARAM_STR);
            $requete->bindValue('nom', $this->_nom, PDO::PARAM_STR);
            $requete->bindValue('prenom', $this->_prenom, PDO::PARAM_STR);
            $requete->bindValue('password', $this->_password, PDO::PARAM_STR);
            $requete->bindValue('roles', implode(",", $this->_roles), PDO::PARAM_STR);

            // Exécution de la requête
            if ($requete->execute()) {

                // Màj de l'ID de l'objet courant
                $last_id = $pdo->lastInsertId(); // vaut 0 si Update
                $this->_id_utilisateur = ($last_id == 0) ? $this->_id_utilisateur : $pdo->lastInsertId();

                return $this;
            } else {
                throw new Exception('[' . __CLASS__ . '][' . __FUNCTION__ . '][' . __LINE__ . ']L\'exécution de la requête ne s\'est pas bien passée');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * (non-PHPdoc)
     * @see iBdd::load()
     */
    public function load() {
        global $pdo;
        
        try {
            // Préparation de la requête
            $requete = $pdo->prepare('
                SELECT 
                    U.`email`, 
                    U.`username`, 
                    U.`nom`, 
                    U.`prenom`, 
                    U.`roles`, 
                    U.`password`, 
                FROM utilisateur U
				WHERE U.`id_utilisateur` = ?
				;');

            // Exécution de la requête
            if ($requete->execute(array($this->_id_utilisateur))) {

                $donnees = $requete->fetchAll();
                
                if (!empty($donnees)) {
                    foreach ($donnees as $utilisateur) {
                        $this->_email = $utilisateur['email'];
                        $this->_username = $utilisateur['username'];
                        $this->_nom = $utilisateur['nom'];
                        $this->_prenom = $utilisateur['prenom'];
                        $this->_password = $utilisateur['password'];
                        $this->_roles = explode(',', $utilisateur['roles']);
                    }
                    return $this;
                } else {
                    return false;
                }
            } else {
                throw new Exception('[' . __CLASS__ . '][' . __FUNCTION__ . '][' . __LINE__ . ']L\'exécution de la requête ne s\'est pas bien passée');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * (non-PHPdoc)
     * @see iBdd::delete()
     */
    public function delete() {
        global $pdo;

        try {

            // Préparation de la requête
            $requete = $pdo->prepare('
                DELETE FROM `utilisateur`
                WHERE `id_utilisateur` = :id_utilisateur
                ;');
            // Affectation des variables
            $requete->bindValue('id_utilisateur', $this->_id_utilisateur, PDO::PARAM_INT);

            // Exécution de la requête
            if ($requete->execute()) {

                return $this;
            } else {
                throw new Exception('[' . __CLASS__ . '][' . __FUNCTION__ . '][' . __LINE__ . ']L\'exécution de la requête ne s\'est pas bien passée');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
	
	public static function loadUserByUsername($username) {
		global $pdo;
        
		$utilisateur = new self();
		$utilisateur->setUsername($username);
		
        try {
            // Préparation de la requête
            $requete = $pdo->prepare('
                SELECT 
                    U.`id_utilisateur`, 
                    U.`email`, 
                    U.`nom`, 
                    U.`prenom`, 
                    U.`roles`, 
                    U.`password`
                FROM utilisateur U
				WHERE U.`username` = ?
				;');

            // Exécution de la requête
            if ($requete->execute(array($username))) {

                $donnees = $requete->fetchAll();
                
                if (!empty($donnees)) {
                    foreach ($donnees as $user) {
                        $utilisateur->setId_utilisateur($user['id_utilisateur']);
                        $utilisateur->setEmail($user['email']);
                        $utilisateur->setNom($user['nom']);
                        $utilisateur->setPrenom($user['prenom']);
                        $utilisateur->setPassword($user['password']);
                        $utilisateur->setRoles(explode(',', $user['roles']));
                    }
                    return $utilisateur;
                } else {
                    return false;
                }
            } else {
                throw new Exception('[' . __CLASS__ . '][' . __FUNCTION__ . '][' . __LINE__ . ']L\'exécution de la requête ne s\'est pas bien passée');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
	}
	
	

	function getId_utilisateur()
	{
		return $this->_id_utilisateur;
	}

	function getEmail()
	{
		return $this->_email;
	}

	function getUsername()
	{
		return $this->_username;
	}

	function getNom()
	{
		return $this->_nom;
	}

	function get_prenom()
	{
		return $this->_prenom;
	}

	function getRoles()
	{
		return $this->_roles;
	}

	function getPassword()
	{
		return $this->_password;
	}

	function setId_utilisateur($id_utilisateur)
	{
		$this->_id_utilisateur = $id_utilisateur;
	}

	function setEmail($email)
	{
		$this->_email = $email;
	}

	function setUsername($username)
	{
		$this->_username = $username;
	}

	function setNom($nom)
	{
		$this->_nom = $nom;
	}

	function setPrenom($prenom)
	{
		$this->_prenom = $prenom;
	}

	function setRoles($roles)
	{
		$this->_roles = $roles;
	}

	function setPassword($password)
	{
		$this->_password = $password;
	}
	

    public function getSalt() {
        return '';
    }
	
	public function eraseCredentials() {
        
    }
}
