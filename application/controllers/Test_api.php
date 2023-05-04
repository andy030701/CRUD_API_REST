<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test_api extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
  }
  function index()
  {
    # code...
    //chargement de api_view.php  
    $this->load->view('api_view');
  }
  //fonction action qui va afficher les donnees,inserer,modifier,supprimer les donnees venant de la base de donnees
  function action()
  {
    # code...
    if ($this->input->post('data_action')) {
      # code...
      $data_action = $this->input->post('data_action');
      //si l'action est de supprimer les donnees
      if ($data_action == "Delete") {
        # code...
        $api_url = "http://localhost/Formation_Code_igniter/Creation_api_rest/index.php/Api/delete";
        //form_data indique les paramatres a entrer pour effectuer l'action
        $form_data = array(
          'id' => $this->input->post('user_id')
        );

        $client = curl_init($api_url);
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($client); //on execute la requete
        curl_close($client);
        echo $response;
      }
      //si l'action est de modifier les donnees : prenom et nom
      if ($data_action == "Edit") {
        # code...
        $api_url = "http://localhost/Formation_Code_igniter/Creation_api_rest/index.php/Api/update";

        //un array pour stocker les changements obtenues par l'user
        $form_data = array(
          'first_name' => $this->input->post('first_name'),
          'last_name' => $this->input->post('last_name'),
          'id' => $this->input->post('user_id')
        );

        $client = curl_init($api_url); //$client est une instance de l'objet cURL qui est un client 
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

        //reponse de la requete http
        $response = curl_exec($client);
        curl_close($client); //obligatoire: il faut fermer la liaison avec l'api

        echo $response;
      }
      //si l'action sera l'edition du prenom ou du nom de l'user
      if ($data_action == "fetch_single") {
        # code...
        $api_url = "http://localhost/Formation_Code_igniter/Creation_api_rest/index.php/Api/fetch_single";
        $form_data = array('id' => $this->input->post('user_id'));
        $client = curl_init($api_url);
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, $form_data);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($client);
        curl_close($client);
        echo $response; //envoyer les donnees pour Ajax
      }
      //si l'action a effectuer est une insertion
      if ($data_action == "Insert") {
        # code...
        $api_url = "http://localhost/Formation_Code_igniter/Creation_api_rest/index.php/Api/insert";
        //stockage des donnees du formulaire dans le tableau form_data
        $form_data = array(
          'first_name' => $this->input->post('first_name'),
          'last_name' => $this->input->post('last_name')
        );

        //appel de l'url de l'api
        $client = curl_init($api_url);

        //requete http pour poster les donnees des forumulaires
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, $form_data); //transformation en JSON des donnees venant de $form_data
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($client);

        curl_close($client);
        echo $response;
      }

      // echo $response;
      //si l'action a effectuer est un affichage des donnees
      if ($data_action == "fetch_all") {
        # code...
        $api_url = "http://localhost/Formation_Code_igniter/Creation_api_rest/index.php/Api";
        $client = curl_init($api_url); //initialisation du lien vers cet url
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true); //transfert de donnees venant de l'API enclecher
        $response = curl_exec($client); //execution de la requete http
        curl_close($client); //fermeture de la liaison avec le client
        $result = json_decode($response);
        $output = '';
        //on verifier si le resultat n'a rien donne ou non
        //si le resultat
        if (count($result) > 0) {
          # code...
          //concatenation des donnees dans l'API dans la variable output pour avoir un contenu HTML
          foreach ($result as $row) {
            $output .= '
            <tr>
               <td>' . $row->first_name . '</td>        
               <td>' . $row->last_name . '</td>        
               <td><button type="button" name="edit" class="btn btn-warning btn-xs edit" id="' . $row->id . '">Edit</button></td>        
               <td><button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' . $row->id . '">Delete</td>        
            </tr>
               ';
          }
        }
        //si la requete http n'a rien donne
        else {
          $output = '
          <tr>
             <td colspan="4" align="center">No Data Found</td>
          </tr>
          ';
        }
        echo $output;
      }
    }
  }
}
