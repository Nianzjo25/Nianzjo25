<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Liste des enseignants</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="fonts/boxicons.css" />
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="css/styles-dashboard.css" />

</head>

<?php
    require_once 'includes/db.php';
    require_once 'models/enseignant.php';
    require_once 'models/etudiant.php';
    require_once 'includes/functions.php';
    startSession();
    verify_access('Administrateur');


    //Traitement de demande

    if(!empty($_POST['id'])){
        $id = $_POST['id'];

        //Check de sécurité
        $foundAccout = Enseignant::getUsingId($id);
        if($foundAccout)
        {
            //Compte trouvé, procéder à la suppression
            $reqDelCompte = $pdo->prepare("DELETE FROM enseignant WHERE code_ens = ?");
            $reqDelCompte->execute([$id]);
                
            if($reqDelCompte->rowCount() == 1)
            {
                $_SESSION['flash']['success'] = 'Enseignant supprimé avec succès';
            }
            else
            {
                $_SESSION['flash']['danger'] = "Une erreur s'est produite, veuillez réessayer";
            }
        }
    }


    //Récupération des étudiants
    $enseignants = Enseignant::getRecordsCustomCondition('WHERE est_admin = 0 ORDER BY nom_ens, prenom_ens ASC');

    loadWarnings();

?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="dash_admin.php" class="app-brand-link">
                    <span>
                    <img src="images/logoinit.png" style="width:120px; height:120px" />
                </span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="dash_admin.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div>Tableau de bord</div>
                        </a>
                    </li>

                    <li class="menu-item active">
                        <a href="enseignants.php" class="menu-link">
                            <i class="menu-icon fas fa-user-tie"></i>
                            <div>Enseignants</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="filieres.php" class="menu-link">
                            <i class="menu-icon fas fa-sheet-plastic"></i>
                            <div>Filières</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="classes.php" class="menu-link">
                            <i class="menu-icon fas fa-school fa-icon"></i>
                            <div>Classes</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="cours.php" class="menu-link">
                            <i class="menu-icon fas fa-book"></i>
                            <div>Cours</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="etudiants.php" class="menu-link">
                            <i class="menu-icon fas fa-user-graduate"></i>
                            <div>Etudiants</div>
                        </a>
                    </li>            <li class="menu-item">
              <a
                href="stats.php"
                class="menu-link"
              >
                <i class="menu-icon fas fa-chart-pie"></i>
                <div>Statistiques</div>
              </a>
            </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Compte</span>
                    </li>

                    <li class="menu-item">
                        <a href="settings_admin.php" class="menu-link">
                            <i class="menu-icon fas fa-gear"></i>
                            <div>Paramètres</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="disconnect.php" class="menu-link">
                            <i class="menu-icon fas fa-right-from-bracket"></i>
                            <div>Déconnexion</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Enseignants /</span> Liste des
                            enseignants
                        </h4>

                        <div class="row">
                            <div class="d-flex justify-content-end">
                                <a href="add_enseignant.php">
                                    <div class="btn btn-primary mb-4">Nouvel enseignant<i class="ms-2 fas fa-plus"></i>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="card">
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Nom</th>
                                            <th>Contact</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php
                                            if(count($enseignants) > 0){
                                                $contenu = '';
                                                foreach($enseignants as $admin)
                                                {
                                                    //Remplissage du tableau avec les informations de la requête
                                                    $contenu .= '<tr>';
                                                    $contenu .= '<td>'. $admin->id . '</td>';
                                                    $contenu .= '<td>'. $admin->nom . ' ' . $admin->prenom . '</td>';
                                                    $contenu .= '<td>'. $admin->contact . '</td>';

                                                    $contenu .= '<td>';

                                                    $contenu .= '<button type="button" class="btn btn-icon btn-danger" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" data-bs-original-title="Supprimer" 
                                                    onclick="javascript:sendForm(\''. $admin->id . '\')">';
                                                    $contenu .= '<span class="tf-icons bx bx-trash">';
                                                    $contenu .= '</button>';

    
                                                    $contenu .= '</td>';
                                                    $contenu .= '</tr>';
    
    
                                                }
                                            }
                                            else{
                                                $contenu = '<tr><td></td><td></td><td></td><td></td></tr>';
                                            }

                                            echo $contenu;
                                            
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--/ Basic Bootstrap Table -->

                    </div>
                    <form action="" class="hidden" method="post" id="vForm">
                        <input type="text" value="" name="id" id="id">
                    </form>
                    <!-- / Content -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <script src="js/bootstrap.bundle.min.js"></script>

    <script>
    function sendForm(id) {
        document.getElementById("id").value = id;
        document.getElementById("vForm").submit();
    }
    </script>
</body>

</html>