<?php

class Select {
  /*
  this function returns the connection to the db
  */
  public static function connect(){
    $mysql_host = "localhost";
    $mysql_database = "wolf4656_subzerodata";
    $mysql_user = "wolf4656_1";
    $mysql_password = "Rubiks24";

    //create connection
    $connection = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_database);

    //check connection
    if ($connection->connect_error) {
      die("CANNOT CONNECT!:" . $connection->connect_error);
    } else {
      return $connection;
    }
 }

 public static function addSmasher($tag, $name, $region, $phoneNumber, $meleeSingles, $meleeDoubles, $meleeDoublesPartner,
                                   $wiiuSingles, $wiiuDoubles, $wiiuDoublesPartner, $meleeSetup, $wiiuSetup){
   $connection = select::connect();
   //prepare
   if(!$statement = $connection->prepare(
   "INSERT INTO participants (tag, name, region, phoneNumber, meleeSingles, meleeDoubles, meleeDoublesPartner,
     wiiuSingles, wiiuDoubles, wiiuDoublesPartner, meleeSetup, wiiuSetup)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)")){
      die ("Smasher entry failed: " . $connection->error);
    }
    //Bind
    if(!$statement->bind_param("ssssssssssss", $tag, $name, $region, $phoneNumber, $meleeSingles, $meleeDoubles, $meleeDoublesPartner,
                        $wiiuSingles, $wiiuDoubles, $wiiuDoublesPartner, $meleeSetup, $wiiuSetup))
      die("Smasher bind failed: " . $statement->error);
    //execute
    if(!$statement->execute()) {
      die("Smasher execute failed: " . $statement->error);
   }
   return true;
 }

 public static function smashers(){
   $connection = Select::connect();

   $sql = "SELECT ID, tag, region, meleeSingles, meleeDoubles, meleeDoublesPartner, wiiuSingles, wiiuDoubles, wiiuDoublesPartner
           FROM participants";
   $smashers = $connection->query($sql);
   $connection->close();
   return $smashers;
 }

 public static function contact($name, $email, $phoneNumber, $contact){
   $connection = Select::connect();
   //prepare
   if(!$statement = $connection->prepare(
   "INSERT INTO contact (name, email, phoneNumber, contact)
    VALUES (?,?,?,?)")){
      die ("Contact entry failed: " . $connection->error);
    }
    //Bind
    if(!$statement->bind_param("ssss", $name, $email, $phoneNumber, $contact)){
      die("Contact bind failed: " . $statement->error);
    }
    //execute
    if(!$statement->execute()){
      die("Contact execute failed: " . $statement->error);
    }
   return true;
 }

 public static function signIn($adminName, $password){
   $connection = Select::connect();
   $statement = $connection->prepare("
     SELECT adminPass FROM admins WHERE adminName = ?
   ");

   if(!$statement->bind_param("s", $adminName)){
    die("User bind failed: " . $statement->error);
  }
   if(!$statement->execute()){
     die("User execute failed: " . $statement->error);
   }
   $statement->bind_result($hashpassword);
   $statement->fetch();
   if($hashpassword === $password) {
     return true;
   }else{
     return false;
     }
   }


 public static function participants($query){
   $connection = Select::connect();

   $sql = "SELECT tag, region, meleeSingles, meleeDoubles, meleeDoublesPartner, wiiuSingles, wiiuDoubles, wiiuDoublesPartner
           FROM participants
           WHERE tag
           LIKE '%$query%'";
   $participant = $connection->query($sql);
   $connection->close();
   return $participant;
 }


 public static function participantByID($ID){
   $connection = Select::connect();
   $sql = "SELECT *
           FROM participants
           WHERE ID = $ID";
   $participant = $connection->query($sql);
   $participant = $participant->fetch_assoc();
   $connection->close();
   return $participant;
 }

 public static function events(){
 $connection = Select::connect();

 $sql = "SELECT *
         FROM events";
 $events = $connection->query($sql);
 $connection->close();
 return $events;
 }

 public static function eventsSearch($query){
   $connection = Select::connect();

   $sql = "SELECT ID, name, startTime, endTime, entryFee, playerCap
           FROM events
           WHERE name
           LIKE '%$query%'";
   $event = $connection->query($sql);
   $connection->close();
   return $event;
 }

 public static function eventByID($ID){
   $connection = Select::connect();
   $sql = "SELECT *
           FROM events
           WHERE ID = $ID";
   $event = $connection->query($sql);
   $event = $event->fetch_assoc();
   $connection->close();
   return $event;
 }

 public static function addEvent($name, $startTime, $endTime, $entryFee, $playerCap){
   $connection = Select::connect();
   //prepare
   if(!$statement = $connection->prepare(
   "INSERT INTO events (name, startTime, endTime, entryFee, playerCap)
    VALUES (?,?,?,?,?)")){
      die ("Event entry failed: " . $connection->error);
    }
    //Bind
    if(!$statement->bind_param("sssss", $name, $startTime, $endTime, $entryFee, $playerCap))
      die("Event bind failed: " . $statement->error);
    //execute
    if(!$statement->execute()) {
      die("Event execute failed: " . $statement->error);
   }
   return true;
 }

 public static function contacts(){
   $connection = Select::connect();
   $sql = "SELECT *
           FROM contact";
   $contacts = $connection->query($sql);
   $connection->close();
   return $contacts;
 }

 public static function messages($query){
   $connection = Select::connect();

   $sql = "SELECT ID, name, email, phoneNumber, contact
           FROM contact
           WHERE name
           LIKE '%$query%'";
   $contact = $connection->query($sql);
   $connection->close();
   return $contact;
 }

 public static function addMeleeSetup($tag){
   $connection = Select::connect();
   if(!$statement = $connection->prepare(
   "INSERT INTO setups (playerTag, event)
    VALUES (?, 'Melee')")){
      die ("Setup entry failed: " . $connection->error);
   }
   if(!$statement->bind_param("s", $tag)) {
     die ("Setup bind failed: " . $connection->error);
   }
   if(!$statement->execute()){
     die("Setup execute failed: " . $connection->error);
   }
   return true;
 }

 public static function addWiiuSetup($tag){
   $connection = Select::connect();
   if(!$statement = $connection->prepare(
   "INSERT INTO setups (playerTag, event)
    VALUES (?, 'WiiU')")){
      die ("Setup entry failed: " . $connection->error);
   }
   if(!$statement->bind_param("s", $tag)) {
     die ("Setup bind failed: " . $connection->error);
   }
   if(!$statement->execute()){
     die("Setup execute failed: " . $connection->error);
   }
   return true;
 }

 public static function setups(){
   $connection = Select::connect();
   $sql = "SELECT *
           FROM setups";
   $setups = $connection->query($sql);
   $connection->close();
   return $setups;
 }

 public static function setupByID($ID){
   $connection = Select::connect();
   $sql = "SELECT *
           FROM setups
           WHERE ID = $ID";
   $setup = $connection->query($sql);
   $setup = $setup->fetch_assoc();
   $connection->close();
   return $setup;
 }

 public static function setupSearch($query){
   $connection = Select::connect();

   $sql = "SELECT ID, playerTag, event
           FROM setups
           WHERE playerTag
           LIKE '%$query%'";
   $setup = $connection->query($sql);
   $connection->close();
   return $setup;
 }
}
 ?>
