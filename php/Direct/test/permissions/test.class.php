<?php
  class TestSQL extends DatabaseAdaptor
{
  public function __construct()
  {
    parent::__construct();  
  }
  
  public function doResetRoles()
  {
    $sql = "SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE `Roles`; ALTER TABLE `Roles` AUTO_INCREMENT = 0; INSERT INTO `Roles` (`id`, `project_id`, `rolename`, `priv_bit_mask`)
      VALUES
      	(0, 0, 'System Administrator', 4080),
      	(1, 0, 'Project Manager', 2000),
      	(2, 0, 'Developer', 1872),
      	(3, 0, 'Subject Matter Expert', 1360),
      	(4, 0, 'Functional Assurance Officer', 1360),
      	(5, 0, 'Systems Analyst', 4080),
      	(6, 0, 'Development Lead', 2000),
      	(7, 0, 'Developer', 1472),
      	(8, 0, 'Quality Assurance Officer', 1088),
      	(9, 0, 'Specialist - Deployment', 1088),
      	(10, 0, 'Specialist - Training', 1040),
      	(11, 0, 'Reviewer', 520); SET FOREIGN_KEY_CHECKS = 1;
      ";
    $stmt = $this->dbh->prepare($sql);
    
    if(!$stmt->execute())
      print_r($stmt->errorInfo());
  }
  
  public function doResetUsers($numberOfTestUsers)
  {
    $sql = "SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE `Users`; ALTER TABLE `Users` AUTO_INCREMENT = 0; INSERT INTO `Users` (`id`, `username`, `displayname`, `password`)
      VALUES
      	(0, 'root', 'System Administrator', '291fc808a8daad540e3de52aa1449186eeeb0316'),";
      	
    for($i = 1; $i < $numberOfTestUsers; $i++)
    {
      $sql .= "(NULL, 'user$i', 'Test User $i', '".sha1('user'.$i)."'),";
    }
    
    $sql .= "(NULL, 'User $numberOfTestUsers', 'Test User $numberOfTestUsers', '".sha1('user'.$numberOfTestUsers)."'); SET FOREIGN_KEY_CHECKS = 1;";
          	
    $stmt = $this->dbh->prepare($sql);
    
    if(!$stmt->execute())
      print_r($stmt->errorInfo());
  
  }
  
  public function doResetObjects($numberOfTestObjects, $numberOfLoops = 10)
  {
    $sql = "SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE `PermObjectsMappings`; ALTER TABLE `PermObjectsMappings` AUTO_INCREMENT = 0; INSERT INTO `PermObjectsMappings` (`id`, `userid`, `table`, `oid`, `value`)
      VALUES
      	(0, '0', 'Users', '0', 3048),";
      	
    $NL = $numberOfTestObjects*$numberOfLoops;
    for($i = 1; $i < $NL; $i++)
    {
      $v = mt_rand(16,2048);
      $m = $i % $numberOfTestObjects;
      $sql .= "(NULL, '$m', 'Tasks', '$m', '$v'),";
    }
    
    $sql .= "(NULL, '$numberOfTestObjects', 'Tasks', '$numberOfTestObjects', '9999'); SET FOREIGN_KEY_CHECKS = 1;";
          	
    $stmt = $this->dbh->prepare($sql);
    
    if(!$stmt->execute())
      print_r($stmt->errorInfo()); 
      
  }
  
  public function doResetRoleMappings($numberOfRoles)
  {
    $sql = "SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE `PermAssignmentMappings`; ALTER TABLE `PermAssignmentMappings` AUTO_INCREMENT = 0; INSERT INTO `PermAssignmentMappings` (`id`, `userid`, `projectid`, `role`)
    VALUES
  	(NULL, 0, 0, 0),";
  	
    for($i = 0; $i < $numberOfRoles; $i++)
    {
      $m = $i % $numberOfRoles;
      $z = mt_rand(1,12);
      $sql .= "(NULL, $i, 0, $z),";
    }
    
    $c = $numberOfRoles+1;
    $sql .= "(NULL, '$numberOfRoles', '$numberOfRoles', 0); DELETE FROM `PermAssignmentMappings` WHERE `id`=$c; SET FOREIGN_KEY_CHECKS = 1;";

    $stmt = $this->dbh->prepare($sql);
    
    if(!$stmt->execute()){
      print_r($stmt->errorInfo()); }
  }
  
  public function doResetProjectDefault()
  {
    $sql = "SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE `PermProjectDefaults`; ALTER TABLE `PermProjectDefaults` AUTO_INCREMENT = 0; INSERT INTO `PermProjectDefaults` (`id`, `projectid`, `everyonepermissions`, `defaultroleid`)
    VALUES (NULL, 0, 1000, 1); SET FOREIGN_KEY_CHECKS = 1;";

    $stmt = $this->dbh->prepare($sql);
    
    if(!$stmt->execute()){
      print_r($stmt->errorInfo()); }
  }
}
