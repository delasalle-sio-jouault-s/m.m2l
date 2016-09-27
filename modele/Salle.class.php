<?php
// Projet Réservations M2L - version web mobile
// fichier : modele/Salle.class.php
// Rôle : Il s'agit des salles qui vont être réservées
// Création : 27/09/2016 par PAQUET Thibault
// Mise à jour : 27/09/2016 par PAQUET Thibault

class Salle
{
	// ------------------------------------------------------------------------------------------------------
	// ---------------------------------- Membres privés de la classe ---------------------------------------
	// ------------------------------------------------------------------------------------------------------
	
	private $id; // identifiant de la salle (numéro automatique)
	private $room_name; // Nom de la salle
	private $capacity; //  Capacité de la salle
	private $area_name; // Nom du domaine
	
	// ------------------------------------------------------------------------------------------------------
	// ----------------------------------------- Constructeur -----------------------------------------------
	// ------------------------------------------------------------------------------------------------------
	
	public function Salle($unId, $unRoom_name, $unCapacity, $unArea_name)
	{
		$this->id = $unId;
		$this->room_name = $unRoom_name;
		$this->capacity = $unCapacity;
		$this->area_name = $unArea_name;
	}
	
	// ------------------------------------------------------------------------------------------------------
	// ---------------------------------------- Getters et Setters ------------------------------------------
	// ------------------------------------------------------------------------------------------------------
	
	public function getId()	{return $this->id;}
	public function setId($unId) {$this->id = $unId;}
	
	public function getRoom_name()	{return $this->room_name;}
	public function setRoom_name($unRoom_name) {$this->room_name = $unRoom_name;}
	
	public function getCapacity()	{return $this->capacity;}
	public function setCapacity($unCapacity) {$this->capacity = $unCapacity;}
		 
	public function getArea_name()	{return $this->area_name;}
	public function setArea_name($unArea_name) {$this->area_name = $unArea_name;}

	// ------------------------------------------------------------------------------------------------------
	// ---------------------------------------- Méthodes d'instances ----------------------------------------
	// ------------------------------------------------------------------------------------------------------
	
	public function toString() {
		$msg = "Salle : <br>";
		$msg .= "id : " . $this->id . "<br>";
		$msg .= "room_name : " . $this->room_name . "<br>";
		$msg .= "capacity : " . $this->capacity . "<br>";
		$msg .= "area_name : " . $this->area_name . "<br>";
		return $msg;
	}
}

// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!