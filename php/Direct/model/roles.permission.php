<?php
  
class RoleInformationModel
{
  /* From the Roles table */
  protected $_id;
  protected $_projectID;
  protected $_name;
  protected $_value;
  
  public function __construct($projectID, $name, $permissionsValue, $id = NULL)
  {
    $this->_id = intval($id);
    $this->_projectID = intval($projectID);
    $this->_name = $name;
    $this->_value = intval($permissionsValue);
  }
  
  public function getID()
  {
    return $this->_id;
  }
  
  public function getProjectID()
  {
    return $this->_projectID;
  }
  
  public function getName()
  {
    return $this->_name;
  }
  
  public function getPermissionsValue()
  {
    return $this->_value;
  }
}


class DBRoleInformationModel extends DatabaseAdaptor
{
  private $_roleInfo;
  
  public function __construct($tablePrimaryKey, $projectContext)
  {    
    parent::__construct();
  
    if($projectContext > -1)
    {
      $stmt = $this->dbh->prepare("SELECT * FROM `role` WHERE `id` = :guid AND `projectId` = :uid");    
  
      $stmt->bindParam(':guid', $tablePrimaryKey);
      $stmt->bindParam(':uid', $projectContext);
      
      try
      {
        if($stmt->execute()){
          $possibleMatches = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          if(count($possibleMatches) == 0){
            $this->_roleInfo = new RoleInformationModel($projectContext, NULL, NULL, NULL);
            return;
          }
          
          if(count($possibleMatches) > 1)
            JSONResponse::printErrorResponseWithHeader("Fatal: this project has more than one default!");
            
          if(count($possibleMatches) == 1)
          {
            $this->_roleInfo = new RoleInformationModel($projectContext, $possibleMatches[0]['name'], $possibleMatches[0]['priv_bit_mask'], $possibleMatches[0]['id']);
            return;         
          }        
          JSONResponse::printErrorResponseWithHeader("DBRoleInformationModel experienced an internal error."); 
        }
      }
      catch(PDOException $e)
      {
        JSONResponse::printErrorResponseWithHeader("Unable to load roles - fatal database error: ".$e);
      }      
    } 
    else
    {
      $this->_roleInfo = NULL;
    }       
  } 

  private function save($sqlQuery, $roleID)
  {
    $stmt = $this->dbh->prepare($sqlQuery);
    $stmt->bindParam(':guid', $this->_returnedRole->getID());
    $stmt->bindParam(':pid', $this->_returnedRole->getProjectID());
    $stmt->bindParam(':rname', $this->_returnedRole->getname());
    $stmt->bindParam(':perms', $this->_returnedRole->getPermissionsValue());

    try{
      if($stmt->execute())
        return true;
      else
        return false; 
    }
    catch(PDOException $e)
    {
      JSONResponse::printErrorResponseWithHeader("Unable to add or update role to object mapping - fatal database error: ".$e);
    }
  }
  
  public function createNewRole($name, $permissionsValue)
  {
      $this->_returnedRole = new RoleInformationModel($this->_returnedRole->getProjectID(), $name, $permissionsValue, NULL);
      return $this->save("INSERT INTO `role` (id, projectId, name, priv_bit_mask) VALUE (:guid, :pid, :rname, :perms)", $newRoleID);
  }
  
  public function alterExisitingRole($name, $permissionsValue)
  {
      $this->_returnedRole = new RoleInformationModel($this->_returnedRole->getProjectID(), $name, $permissionsValue, NULL);
      return $this->save("UPDATE `role` SET `name` = :rname AND `priv_bit_mask` = :perms WHERE `projectId` = :pid AND `id` = :guid", $newRoleID); 
  } 
  
  public function deleteRole()
  {
    if($this->_returnedRole == NULL)
      return false;
    
    $stmt = $this->dbh->prepare("DELETE FROM `role` WHERE `projectId` = :pid AND `id` = :guid");     
    $stmt->bindParam(':guid', $this->_returnedRole->getID());
    $stmt->bindParam(':pid', $this->_returnedRole->getProjectID());

    try{
      if($stmt->execute())
      {
        return true;
      } 
      else
      {
        return false;
      }      
    }
    catch(PDOException $e)
    {
      JSONResponse::printErrorResponseWithHeader("Unable to add or update role to object mapping - fatal database error: ".$e);
    }
  }
  
  public function getRoleInfo()
  {
    return $this->_roleInfo;
  }


}