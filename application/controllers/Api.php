<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
  //fonction constructeur de la classe Api
  public function __construct()
  {
    parent::__construct();
    $this->load->model('api_model'); //chargement du model api_model
    $this->load->library('form_validation'); //chargement de la librairie form_validation
  }

  //fonction index 
  function index()
  {
    $data = $this->api_model->fetch_all();
    // var_dump($data->result()); //conversion en JSON des donnees
    echo json_encode($data->result());
  }
  function insert()
  {
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
    //si le formulaire est valide
    if ($this->form_validation->run()) {
      # code...
      //on stocke dans le tableau $data les valeurs des champs du formulaire
      $data = array(
        'first_name' => $this->input->post('first_name'),
        'last_name' => $this->input->post('last_name')
      );
      $this->api_model->insert_api($data); //on insert l'API dans la base de donnees
      $array = array(
        'success' => true
      );
    }
    //si les champs du formulaire ne sont pas rempli
    else {
      $array = array(
        'error' => true,
        'first_name_error' => 'First Name is empty',
        'last_name_error' => 'Last Name is empty'
      );
    }
    echo json_encode($array); //envoi d'une reponse sous forme de JSON
  }
  //pour rechercher une seule personne
  function fetch_single()
  {
    if ($this->input->post('id')) {
      # code...
      $data = $this->api_model->fetch_single_user($this->input->post('id'));
      //pour le prenom et pour le nom
      foreach ($data as $row) {
        $output['first_name'] = $row['first_name'];
        $output['last_name'] = $row['last_name'];
      }
      echo json_encode($output);
    }
  }

  //fonction update() pour modifier le prenom et le nom de l'user
  function update()
  {
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
    if ($this->form_validation->run()) {
      # code...
      $data = array(
        'first_name' => $this->input->post(
          'first_name'
        ),
        'last_name' => $this->input->post(
          'last_name'
        )
      );
      $this->api_model->update_api($this->input->post('id'), $data);
      $array = array(
        'success' => true
      );
    } else {
      $array = array(
        'error' => true,
        'first_name_error' => form_error('first_name'),
        'last_name_error' => form_error('last_name'),
      );
    }
    echo json_encode($array);
  }
  function delete()
  {
    if ($this->input->post('id')) {
      # code...
      $this->api_model->delete_single_user($this->input->post('id'));
      # code...
      $array = array(
        'success' => true
      );
    } else {
      $array = array(
        'error' => true
      );
    }
    echo json_encode($array);
  }
}
