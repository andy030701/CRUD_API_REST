<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD REST API in Code igniter</title>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <br>
        <h3 align="center">Create CRUD REST API in Codeigniter</h3>
        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="panel-title">CRUD REST API in CodeIgniter</h3>
                    </div>
                    <div class="col-md-6" align="right">
                        <button type="button" id="add_button" class="btn btn-info btn-xs">Add</button>
                    </div>
                </div>
            </div>

            <div class="panel-body">
                <!--Message de succes si la requete a ete bien executee-->
                <span id="success_message"></span>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="userModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="user_form">

                <div class="modal-content">
                    <div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add User</h4>
                    </div>
                    <div class="modal-body">
                        <label for="">Enter First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control">
                        <span id="first_name_error" class="text-danger"></span>
                        <br>
                        <label for="">Enter Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control">
                        <span id="last_name_error" class="text-danger"></span>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="user_id" id="user_id">
                        <input type="hidden" name="data_action" id="data_action" value="Insert">
                        <input type="submit" name="action" value="Add" id="action" class="btn btn-success">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        $(document).ready(function() {
            function fetch_data() {
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/Test_api/action",
                    method: "POST",
                    data: {
                        data_action: 'fetch_all'
                    },
                    success: function(data) {
                        $('tbody').html(data);
                    }
                });
            }
            fetch_data();

            //lorsqu'on clique sur le bouton ajouter
            $('#add_button').click(function() {
                $('#user_form')[0].reset();
                $('.modal-title').text("Add User");
                $('#action').val('Add');
                $('#data_action').val("Insert");
                $('#userModal').modal('show');
            })

            //lorsque le document est soumis oulorsque l'user a taper sur le bouton soumission du formulaire
            $(document).on('submit', '#user_form', function(event) {
                event.preventDefault();
                $.ajax({
                    url: "<?php echo base_url() . 'index.php/Test_api/action' ?>",
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(data) {
                        //si la converion en json est reussie
                        if (data.success) {
                            $('#user_form')[0].reset();
                            $('#userModal').modal('show');
                            fetch_data();
                            //si l'action est Insert alors on cache la boite de dialogue et on met un message de succes Data Inserted
                            if ($('#data_action').val() == "Insert") {
                                $('#userModal').modal('hide');
                                setTimeout(

                                )
                                $('#success_message').html(
                                    '<div class="alert alert-success">Data Inserted</div>');
                            }
                            //si l'action est Edit alors on cache juste la boite de dialogue
                            if ($('#data_action').val() == "Edit") {
                                $('#userModal').modal('hide');
                            }
                        }
                        //si il y a eu une erreur
                        if (data.error) {
                            $('#first_name_error').html(data.first_name_error);
                            $('#last_name_error').html(data.last_name_error);
                        }
                    }
                })
            })
            //lorsque on clique sur le bouton ayant la classe '.edit'
            $(document).on('click', '.edit', function() {
                var user_id = $(this).attr('id');
                $.ajax({
                    url: "<?php echo base_url(); ?>index.php/Test_api/action",
                    method: "POST",
                    data: {
                        user_id: user_id,
                        data_action: 'fetch_single'
                    },
                    dataType: "json",
                    success: function(data) // message ou action a effectuer en cas de succes 
                    {
                        $('#userModal').modal(
                            'show'); //affichage d'une boite de dialogue avec l'user
                        $('#first_name').val(data
                            .first_name
                        ); //le champ de formulaire sera rempli avec la valeur initiale de prenom
                        $('#last_name').val(data
                            .last_name
                        ); //le champ de formulaire sera rempli avec la valeur initiale de nom
                        $('.modal-title').text('Edit User'); //le titre du modal sera Edit User
                        $('#user_id').val(user_id); //l'id de l'user sera l'id de l'element user_id
                        $('#action').val('Edit'); //l'action sera Edit
                        $('#data_action').val('Edit');
                    }
                })
            })
            $(document).on('click', '.delete', function() {
                var user_id = $(this).attr('id');
                //une boite de dialogue qui demande la confirmation de l'user apparait avec confirm()
                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        url: "<?php echo base_url(); ?>index.php/Test_api/action",
                        method: "POST",
                        data: {
                            user_id: user_id,
                            data_action: 'Delete'
                        },
                        dataType: "JSON",
                        success: function(data) {
                            //si la suppression s'est bien faite
                            if (data.success) {
                                $('#success_message').html(
                                    '<div class="alert alert-success">Data Deleted</div>'
                                );
                                fetch_data(); //on affiche la liste des users restants
                            }
                        }
                    })
                }
            })
        });
    </script>
</body>

</html>