<?php
class Api_model extends CI_Model
{
    //fonction pour recuperer des donnees dans la bd
    function fetch_all()
    {
        $this->db->order_by('id', 'DESC');
        //on recupere les donnes de la table tst
        $query = $this->db->get('tst');
        return $query;
    }
    //fonction pour inserer une donnee dans la bd
    function insert_api($data)
    {
        $this->db->insert('tst', $data);
    }
    //fonction pour rechercher un user par son id
    function fetch_single_user($user_id)
    {
        $this->db->where('id', $user_id);
        $query = $this->db->get('tst');
        return $query->result_array();
    }

    //fonction pour modifier les donnees dans la base de donnees
    function update_api($user_id, $data)
    {
        $this->db->where('id', $user_id); //recherche la ligne ou l'id se trouve
        $this->db->update('tst', $data);
    }

    //fonction pour supprimer un user en fonction de son id
    function delete_single_user($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->delete('tst');
    }
}
