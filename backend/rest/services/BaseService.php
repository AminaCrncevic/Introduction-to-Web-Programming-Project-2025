
<?php
//require_once 'BaseDao.php';
require_once __DIR__ . '/../dao/BaseDao.php';
class BaseService {
   protected $dao;
   public function __construct(BaseDao $dao) {
       $this->dao = $dao;
   }
   public function getAll() {
       return $this->dao->getAll();
   }
   public function getById($id) {
       return $this->dao->getById($id);
   }
   public function create($data) {
       return $this->dao->insert($data);
   }
   public function update($id, $data) {
       return $this->dao->update($id, $data);
   }
   public function delete($id) {
       return $this->dao->delete($id);
   }
   /*ADDED METHODS FOR MILESTONE 4*/

    public function add($entity)
    {
        return $this->dao->add($entity);
    }


 public function update1($entity, $id, $id_column = "id")
    {
        return $this->dao->update($entity, $id, $id_column);
    }


    /************************************************ */
}
