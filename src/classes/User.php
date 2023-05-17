<?php
class User {
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $password;
    private $role; // 'admin', 'gestionnaire', 'etudiant'
    private $activationStart; //Date d'activation du compte
    private $activationEnd; //Date de fin du compte
    private $class; //(L1,L2,L3,M1,M2,D) 
    private $city; 

    public function __construct($id, $firstName, $lastName, $email, $phone, $password, $role, $activationStart, $activationEnd, $class, $city) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->role = $role;
        $this->activationStart = $activationStart;
        $this->activationEnd = $activationEnd;
        $this->class = $class;
        $this->city = $city;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRole() {
        return $this->role;
    }

    public function getActivationStart() {
        return $this->activationStart;
    }

    public function getActivationEnd() {
        return $this->activationEnd;
    }

    public function getClass() {
        return $this->class;
    }
    public function getCity(){
        return $this->city;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setActivationStart($activationStart) {
        $this->activationStart = $activationStart;
    }

    public function setActivationEnd($activationEnd) {
        $this->activationEnd = $activationEnd;
    }

    public function setClass($class){
        $this->class = $class;
    }

    public function setCity($city){
        $this->city = $city;
    }
}
?>
