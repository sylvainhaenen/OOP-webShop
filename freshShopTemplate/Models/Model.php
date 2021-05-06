<?php
namespace App\Models;

use App\Db\Db;

class Model extends Db
{
    // Table de la base de donnée
    protected $table;

    // Instance de Db
    private $db;

    public function findAll()
    {
        $query = $this->query('SELECT * FROM ' . $this->table);
        return $query->fetchAll();
    }

    public function findBy(array $criteria)
    {
        $fields = [];
        $values = [];

        foreach($criteria as $field => $value){
            $fields[] = "$field = ?";
            $values[] = $value;
        }

        // On transforme le tableau "fields" en une chaine de caractères
        $fields_list = implode(' AND ', $fields);

        // On exécute la requête
        return $this->query('SELECT * FROM ' .$this->table. ' WHERE ' . $fields_list, $values)->fetchAll();
    
    }

    public function find(int $id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
    }

    public function create(Model $model)
    {
        $fields = [];
        $inter = [];
        $values = [];

        // On boucle pour éclater le tableau
        foreach($model as $field => $value){
            // INSERT INTO (firstname, lastname, email, password VALUES(?, ?, ?, ?))
            if($value !== null && $champ != 'db' && $champ != 'table'){
                $fields[] = "$field";
                $inter[] = "?";
                $values[] = $value;

            }
        }

        // On transforme le tableau "fields" en une chaine de caractères
        $fields_list = implode(', ', $fields);
        $inter_list = implode(', ', $inter);

        // On exécute la requête
        return $this->query('INSERT INTO ' .$this->table.' (' . $fields_list. ') VALUES('.$inter_list.')', $values); 
    }

    public function update(int $id, Model $model)
    {
        $fields = [];
        $values = [];

        // On boucle pour éclater le tableau
        foreach($model as $field => $value){
            // UPDATE register SET firstname = ?, lastname = ?, email = ?, password = ?, WHERE id= ?
            if($value !== null && $champ != 'db' && $champ != 'table'){
                $fields[] = "$field = ?";
                $values[] = $value;

            }
        }
        $values[] = $id;

        // On transforme le tableau "fields" en une chaine de caractères
        $fields_list = implode(', ', $fields);

        // On exécute la requête
        return $this->query('UPDATE ' .$this->table.' SET '.$fields_list.' WHERE id = ?', $values); 
    }


    public function query(string $sql, array $attributes = null)
    {
        // On récupère l'instance de Db
        $this->db = Db::getInstance();

        // On vérifie si on a des attributs
        if($attributes !== null){
            // Requête préparée
            $query = $this->db->prepare($sql);
            $query->execute($attributes);
            return $query;
        }else{
            // Requête simple
            return $this->db-query($sql);
        }
    }

    public function hydrate(array $datas)
    {
        foreach($datas as $key => $value){
            // On récupère le nom du setter correspondant à la clé
            // titre -> setTitre
            $setter = 'set'.ucfirst($key);

            // On vérifie si le setter existe
            if(method_exists($this, $setter)){
                //On appelle le setTitre
                $this->$setter($value);
            }
        }
        return $this;
    }

}