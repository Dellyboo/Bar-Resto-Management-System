<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login/login.php");
    exit;
}

// Database connection parameters
$host = 'localhost'; // Your database host
$dbname = 'g_bar';   // Your database name
$username = 'root';   // Your database username
$password = '';       // Your database password

try {
    // Establish connection with the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
}

// Fetch drink names and prices from the database
$query = "SELECT id, nom_boisson, prix_achat FROM gestion_stock_boisson";
$stmt = $pdo->query($query);
$drinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    

    <!-- jQuery (necessary for DataTables) -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    

    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


	<!-- My CSS -->
	<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_1.css">
    <link rel="stylesheet" href="style3.css">

	<title>Ku Kayaga</title>
</head>
<body>


	<!-- Sidebar -->
<section id="sidebar">
    <a href="index.php" class="brand">
        <i class='bx bxs-wine'></i> <!-- Beer icon for the bar -->
        <span class="text">KU KAYAGA</span>
    </a>
    <ul class="side-menu top">
        <li class="active">
            <a href="#" data-target="dashboard-section">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="gestion-commandes-section">
                <i class='bx bxs-cart'></i>
                <span class="text">Gestion Commandes</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="gestion-stock-section">
                <i class='bx bxs-box'></i>
                <span class="text">Gestion Stock</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="facturation-section">
                <i class='bx bxs-receipt'></i>
                <span class="text">Facturation</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="gestion-employes-section">
                <i class='bx bxs-user-detail'></i>
                <span class="text">Gestion des Employés</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="gestion-equipement-section">
                <i class='bx bxs-factory'></i>
                <span class="text">Gestion des Equipement</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="graph-section">
                <i class='bx bx-transfer-alt'></i>
                <span class="text">Contrôle des Bouteilles</span>
            </a>
        </li>
        <li>
            <a href="#" data-target="gestion-conges-container">
                <i class='bx bx-calendar'></i>
                <span class="text">Gestion des Congés</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu top">    
        <!-- New General Report Section -->
        <li>
            <a href="#" data-target="general-report-section">
                <i class='bx bxs-report'></i> <!-- Icon for reports -->
                <span class="text">Rapports Généraux</span>
            </a>
        </li>
    </ul>
</section>




	<!-- CONTENT -->
<section id="content">
            <!-- NAVBAR -->
            <nav>
        <i class='bx bx-menu'></i>
        <a href="#" class="nav-link">Categories</a>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>

        <!-- Time Section -->
        <div class="time-container">
            <i class='bx bx-time'></i> 
            <span id="currentTime"></span>
        </div>

        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        
        <a href="#" class="notification" id="notification-icon">
            <i class='bx bxs-bell'></i>
            <span class="num">8</span>
        </a>

        <!-- Notification Modal -->
        <div class="notification-modal" id="notification-modal">
            <h4>Notifications</h4>
            <ul id="notification-list">
                <!-- Notifications will be loaded here from the database -->
            </ul>
        </div>

        <a href="#" class="profile" id="profile-link">
            <i class="bx bx-log-out-circle" style="font-size: 24px; color: #333;"></i>
        </a>


    </nav>
		<!-- NAVBAR -->

		<!-- Main Content Section -->
<main id="main-content">
    <!-- Dashboard Section -->  
    <section id="dashboard-section" class="content-section">
    <h1>Dashboard</h1>  
        <p></p>
        <div class="dashboard-grid">
            <div class="dashboard-box" id="box-commandes" onclick="showSection('gestion-commandes-section')">
                <i class='bx bxs-cart'></i>
                <span>Gestion Commandes</span>
            </div>
            <div class="dashboard-box" id="box-stock" onclick="showSection('gestion-stock-section')">
                <i class='bx bxs-box'></i>
                <span>Gestion Stock</span>
            </div>
            <div class="dashboard-box" id="box-facturation" onclick="showSection('facturation-section')">
                <i class='bx bxs-receipt'></i>
                <span>Facturation</span>
            </div>
            <div class="dashboard-box" id="box-employes" onclick="showSection('gestion-employes-section')">
                <i class='bx bxs-user-detail'></i>
                <span>Gestion des Employés</span>
            </div>
            <div class="dashboard-box" id="box-equipement" onclick="showSection('gestion-equipement-section')">
                <i class='bx bxs-factory'></i>
                <span>Gestion des Equipements</span>
            </div>
            <div class="dashboard-box graph-box" id="box-graph" onclick="showSection('graph-section')">
                <i class="bx bx-transfer-alt"></i> 
                <span>Contrôle des Bouteilles</span>
            </div>

        </div>
        <section class="counter-section">
    <div id="employees-box" class="counter-box">
        <div class="counter-value" id="employee-count">0</div>
        <div class="counter-title">Total employés</div>
        <i class="bi bi-person-fill"></i> <!-- Person Icon (for Employees) -->
    </div>

    <div id="bottles-box" class="counter-box">
        <div class="counter-value" id="bottles-count">0</div>
        <div class="counter-title">Bouteilles vendues</div>
        <i class="bx bxs-wine"></i> <!-- Wine Glass Icon (for Bottles Sold) -->
    </div>

    <div id="food-box" class="counter-box">
        <div class="counter-value" id="food-count">0</div>
        <div class="counter-title">Assiettes vendues</div>
        <i class="bi bi-cup-hot-fill"></i> <!-- Fried Egg Icon (for Food Sold) -->
    </div>

    <div id="complaints-box" class="counter-box">
        <div class="counter-value" id="complaints-count">0</div>
        <div class="counter-title">Nouvelles plaintes</div>
        <i class="bi bi-activity"></i> <!-- Flag Icon (for Complaints) -->
    </div>
</section>



    </section>


    <!-- Gestion Commandes Section -->
    <section id="gestion-commandes-section" class="content-section">
        <h1>Gestion Commandes</h1>
        <p></p>
        <div class="gestion-commandes-grid">
            <!-- Boisson Box -->
            <div class="gestion-commandes-box" id="box-boisson" onclick="toggleBox('boisson')">
                <i class="bx bxs-wine"></i> <!-- Wine/Bar icon -->
                <span>Bar</span>
            </div>

            <!-- Kitchen Box -->
            <div class="gestion-commandes-box" id="box-kitchen" onclick="toggleBox('kitchen')">
                <i class="bx bxs-store-alt"></i> <!-- Restaurant icon -->
                <span>Restaurant</span>
            </div>
        </div>
        
        <!-- This is the container where available drinks and the order form will appear -->
        <div id="boisson-details-container" style="display: none;">
            <!-- Button to show the order section (placed below the grid) -->
            <button class="order-btn" onclick="openOrderModal()">
                <i class="bx bxs-cart-add"></i> Passez une commande boisson
            </button>
             
            <div class="grid-container">
            <?php
    // Database connection settings
    $host = 'localhost';
    $dbname = 'g_bar';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to fetch data including picture column, ordered alphabetically by nom_boisson
        $query = "SELECT nom_boisson, quantite_stock, prix_achat, picture FROM gestion_stock_boisson ORDER BY nom_boisson ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Determine image path
                $imagePath = !empty($row['picture']) ? "./pictures/" . $row['picture'] : "G_boisson/uploads/default.jpg";

                // Determine stock status and class
                if ($row['quantite_stock'] <= 1) {
                    $stockClass = 'stock-out';
                    $stockText = 'Non disponible';
                } elseif ($row['quantite_stock'] < 10) {
                    $stockClass = 'stock-low';
                    $stockText = $row['quantite_stock'];
                } else {
                    $stockClass = 'stock-high';
                    $stockText = $row['quantite_stock'];
                }
                ?>
                <div class="grid-item">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Boisson Image">
                    <div class="boisson-name"><?php echo htmlspecialchars($row['nom_boisson']); ?></div>
                    <div class="boisson-quantity <?php echo $stockClass; ?>"><?php echo htmlspecialchars($stockText); ?></div>
                    <div class="boisson-price"><?php echo htmlspecialchars($row['prix_achat']); ?> FBU</div>
                </div>
                <?php
            }
        } else {
            echo '<p class="stock-out">Non disponible</p>';
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
?>




</div>




            
            <!-- Drink Order Modal -->
<div id="drink-order-modal" class="drink-order-modal">
    <div class="drink-modal-content">
        <span class="drink-close" onclick="closeOrderModal()">&times;</span>
        
        <!-- Step Indicator -->
        <div class="drink-step-indicator">
            <span id="drink-step-circle">1</span>
        </div>

        <!-- Step 1: Select Drinks -->
        <div id="drink-step-1">
            <h2>Passer une commande boisson</h2>
            
            <table id="drink-table">
                <thead>
                    <tr>
                        <th>Boisson</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be added dynamically -->
                </tbody>
            </table>
            
            <button onclick="addDrinkRow()">+ Ajouter une boisson</button>
            
            <!-- Total Price -->
            <div class="drink-total-price-box">
                <strong>Total: </strong> <span id="drink-total-price">0 FBU</span>
            </div>
            
            <!-- Table & Server Details -->
            <label for="drink-table-number">Numéro de Table:</label>
            <input type="text" id="drink-table-number" required>

            <label for="drink-server-number">Numéro du Serveur:</label>
            <input type="text" id="drink-server-number" required>

            <!-- Navigation -->
            <button onclick="nextStep()">
                Next <i class="bx bx-chevron-right"></i>
            </button>
        </div>
        
        <!-- Step 2: Confirm Order -->
         <div id="drink-step-2" style="display: none;">
            <div id="drink-order-summary">
                <!-- Order summary will be populated here -->
            </div>
            
            <!-- Navigation Buttons -->
             <button onclick="prevStep()">
                <i class="bx bx-chevron-left"></i> Retour
            </button>
            <button onclick="submitOrder()">
                <i class="bx bxs-badge-check"></i> Confirmer la commande
            </button>
        </div>

    </div>
</div>

        </div>

        <!-- This is the container where available kitchen items and the order form will appear -->
        <div id="kitchen-details-container" style="display: none;">
            <!-- Kitchen specific content can go here -->
            <button class="order-btn" onclick="openKitchenOrderModal()">
                <i class="bx bxs-cart-add"></i> Passez une commande cuisine
            </button>
            
            <div class="grid-container">
    <?php
    // Database connection settings
    $host = 'localhost';
    $dbname = 'g_bar';
    $username = 'root';
    $password = '';
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Query to fetch data including availability and picture columns, ordered alphabetically by nom_plat
        $query = "SELECT nom_ingredient, availability, prix_achat, picture FROM gestion_stock_kitchen ORDER BY nom_ingredient ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Set color based on availability value
                $availabilityColor = ($row['availability'] === 'non-disponible') ? 'red' : 'green';
                
                // Set image path (assuming images are stored in 'G_boisson/uploads/')
                $imagePath = !empty($row['picture']) ? "./pictures/" . htmlspecialchars($row['picture']) : "G_boisson/uploads/default.jpg";
                
                echo '<div class="grid-item">';
                echo '<img src="' . htmlspecialchars($imagePath) . '" alt="Plat Image">';
                echo '<div class="plat-name" style="font-weight: bold;">' . htmlspecialchars($row['nom_ingredient']) . '</div>';
                echo '<div class="plat-availability" style="color:' . $availabilityColor . '; font-weight: normal;">' . htmlspecialchars($row['availability']) . '</div>';
                echo '<div class="plat-price">' . htmlspecialchars($row['prix_achat']) . ' FBU</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No kitchen items available.</p>';
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    ?>
</div>



    <!-- Kitchen Order Modal -->
<div id="kitchen-order-modal" class="kitchen-order-modal" style="display: none;">
    <div class="kitchen-modal-content">
        <span class="kitchen-close" onclick="closeKitchenOrderModal()">&times;</span>
        
        <!-- Step Indicator -->
        <div class="kitchen-step-indicator">
            <span id="kitchen-step-circle">1</span>
        </div>

        <!-- Step 1: Select Kitchen Items -->
        <div id="kitchen-step-1">
        <h2>Passer une commande cuisine</h2>
            
            <table id="kitchen-table">
                <thead>
                    <tr>
                        <th>Plat</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be added dynamically -->
                </tbody>
            </table>
            
            <button onclick="addKitchenRow()">+ Ajouter un plat</button>
            
            <!-- Total Price -->
            <div class="kitchen-total-price-box">
                <strong>Total: </strong> <span id="kitchen-total-price">0 FBU</span>
            </div>
            
            <!-- Table & Server Details -->
            <label for="kitchen-table-number">Numéro de Table:</label>
            <input type="text" id="kitchen-table-number" required>

            <label for="kitchen-server-number">Numéro du Serveur:</label>
            <input type="text" id="kitchen-server-number" required>

            <!-- Navigation -->
            <button onclick="nextKitchenStep()">
                next <i class="bx bx-chevron-right"></i>
            </button>
        </div>
        
        <!-- Step 2: Confirm Kitchen Order -->
        <div id="kitchen-step-2" style="display: none;">
            <div id="kitchen-order-summary"></div>
            
            <!-- Retour Button with bx Icon -->
            <button onclick="prevKitchenStep()">
                <i class="bx bx-chevron-left"></i> Retour
            </button>

            <!-- Confirmer la commande Button with bx Icon -->
            <button onclick="submitKitchenOrder()">
                <i class="bx bxs-badge-check"></i> Confirm order
            </button>
        </div>
    </div>
</div>

</div>

    </section>












    <!-- Gestion Stock Section -->
    <section id="gestion-stock-section" class="content-section">
        <h1>Gestion Stock</h1>
        <p></p>

        <div class="gestion-stock-grid">
        <div class="gestion-stock-box" id="box-supplies" onclick="showBoissonDetails()">
            <i class="bx bxs-wine"></i> <!-- Box icon for supplies -->
            <span>Boisson</span>
        </div>
        <div class="gestion-stock-box" id="box-equipment" onclick="showKitchenDetails()">
            <i class="bx bxs-store-alt"></i> <!-- Wrench icon for equipment -->
            <span>Cuisines</span>
        </div>
    </div>


    
    </section>


    <section id="gestion-boisson-section" class="content-section">
        <h1>Gestion Boissons</h1>
        <p></p>


        <button id="add-stock-btn" class="add-stock-btn">
        <i class='bx bx-plus-circle'></i>
        Ajouter Stock
    </button>

    <!-- Modal/Dialog -->
    <div id="stock-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter produit</h2>
            
            

            <form id="stock-form" method="POST" enctype="multipart/form-data">
    
    
    <div class="form-group">
        <label for="item-name">Nom:</label>
        <input type="text" id="item-name" name="name" required>
    </div>

    <div class="form-group">
        <label for="item-quantity">Quantite:</label>
        <input id="item-quantity" name="quantity" required>
    </div>

    <div class="form-group">
        <label for="item-price">Prix d'achat:</label>
        <input type="number" step="0.01" id="item-price" name="price" required>
    </div>

    <div class="form-group">
                <label for="ingredient-picture">Image de la boisson: </label>
                <input type="file" id="boisson-picture" name="picture" accept="image/*">
            </div>

            <div class="form-group">
                <img id="image-preview" src="" alt="Aperçu de l'image" style="max-width: 100px; display: none;">
            </div>

    <button type="submit" class="submit-btn">Ajouter produit</button>
</form>
        </div>
    </div>

    <div class="table-container" style="margin-top: 10px;">
    <table id="stock-table" class="display">
        <thead>
            <tr>
                <th></th>
                <th>Nom</th>
                <th>Quantité</th>
                <th>Prix d'achat</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here -->
        </tbody>
    </table>
</div>

<div id="stock-modal-update" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Modifier le produit</h2>

        <form id="stock-form-update" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="item-name">Nom:</label>
                <input type="text" id="item-name-update" name="name" required>
            </div>

            <div class="form-group">
                <label for="item-quantity">Quantité:</label>
                <input id="item-quantity-update" name="quantity" required>
            </div>

            <div class="form-group">
                <label for="item-price">Prix d'achat:</label>
                <input type="number" step="0.01" id="item-price-update" name="price" required>
            </div>

            <div class="form-group">
                <label>Image Actuelle:</label>
                <img id="current-image-preview-boisson" src="" alt="Aucune image" style="max-width: 200px; display: block; margin-top: 10px;">
            </div>

            <!-- File Input for New Image -->
            <div class="form-group">
                <label for="boisson-picture-update">Changer l'image:</label>
                <input type="file" id="boisson-picture-update" name="picture" accept="image/*">
            </div>

            <div class="form-group">
                <img id="image-preview-update-boisson" src="" alt="Aperçu de l'image" style="max-width: 100px; display: none;">
            </div>

            <button type="submit" class="submit-btn">Mettre à jour</button>
        </form>
    </div>
</div>
    </section>


    <section id="gestion-cuisine-section" class="content-section">

    <h1>Gestion Cuisines</h1>
        <p></p>

            <div style="display: flex; align-items: center; justify-content: space-between">
            <button id="add-stock-kitchen-btn" class="add-stock-btn">
                <i class='bx bx-plus-circle'></i>
                Ajouter Stock
                </button>


                <button id="add-category-btn" class="add-stock-btn">
            <i class='bx bx-plus-circle'></i>
            Ajouter Categorie
        </button>
            </div>
        
            <div class="table-container" style="margin-top: 10px;">
    <table id="stock-kitchen-table" class="display">
        <thead>
            <tr>
                <th></th>
                <th>Nom du plat</th>
                <th>Prix d'achat</th>
                <th>Categorie</th>
                <th>Sous Categorie</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here -->
        </tbody>
    </table>
</div>
    </section>

    

    <!-- Modal for Adding Category or Subcategory -->
    <div id="category-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Ajouter une Catégorie</h2>

        <form id="category-form" method="POST">
            <div class="form-group">
                <label for="category-type">Type:</label>
                <select id="category-type" name="category_type" required>
                    <option value="cuisine_categorie">Catégorie</option>
                    <option value="sous_categorie">Sous-Catégorie</option>
                </select>
            </div>

            <div id="parent-category-group" class="form-group" style="display: none;">
                <label for="parent-category">Catégorie Parent:</label>
                <select id="parent-category" name="parent_category"></select>
            </div>

            <div class="form-group">
                <label for="category-name">Nom:</label>
                <input type="text" id="category-name" name="category_name" required>
            </div>

            <button type="submit" class="submit-btn">Ajouter</button>
        </form>
    </div>
</div>



<!-- Modal to Add Kitchen Stock -->
<div id="stock-kitchen-modal" class="modal">
    <div class="modal-content">
        <span class="close" id="close-stock-kitchen-modal">&times;</span>
        <h2>Ajouter au stock de cuisine</h2>

        <form id="stock-kitchen-form" method="POST" enctype="multipart/form-data">
            <!-- Category Selection -->
            <div class="form-group">
                <label for="category">Catégorie:</label>
                <select id="category" name="cuisine_categorie_id" required>
                    <option value="">Sélectionner une catégorie</option>
                    <!-- Categories will be loaded here -->
                </select>
            </div>

            <!-- Sub-Category Selection (Initially hidden) -->
            <div class="form-group" id="sub-category-group" style="display: none;">
                <label for="sub-category">Sous-Catégorie:</label>
                <select id="sub-category" name="sous_categorie_id">
                    <option value="">Sélectionner une sous-catégorie</option>
                    <!-- Sub-categories will be loaded here -->
                </select>
            </div>

            <div class="form-group">
                <label for="ingredient-name">Nom du plat</label>
                <input type="text" id="ingredient-name" name="nom_ingredient" required>
            </div>


            <div class="form-group">
                <label for="ingredient-price">Prix d'achat:</label>
                <input type="number" step="0.01" id="ingredient-price" name="prix_achat" required>
            </div>

            <div class="form-group">
                <label for="ingredient-picture">Image du produit:</label>
                <input type="file" id="ingredient-picture" name="picture" accept="image/*">
            </div>

            <div class="form-group">
                <img id="image-preview" src="" alt="Aperçu de l'image" style="max-width: 100px; display: none;">
            </div>


            <button type="submit" class="submit-btn">Ajouter au stock</button>
        </form>
    </div>
</div>




<div id="stock-kitchen-modal-update" class="modal">
    <div class="modal-content">
        <span class="close" id="close-stock-kitchen-modal">&times;</span>
        <h2 id="modal-title">Ajouter au stock de cuisine</h2>

        <form id="stock-kitchen-form-update" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="category">Catégorie:</label>
                <select id="category-update" name="cuisine_categorie_id" required>
                    <option value="">Sélectionner une catégorie</option>
                </select>
            </div>

            <div class="form-group" id="sub-category-group">
                <label for="sub-category">Sous-Catégorie:</label>
                <select id="sub-category-update" name="sous_categorie_id">
                    <option value="">Sélectionner une sous-catégorie</option>
                </select>
            </div>

            <div class="form-group">
                <label for="ingredient-name">Nom du plat</label>
                <input type="text" id="ingredient-name-update" name="nom_ingredient" required>
            </div>

            <div class="form-group">
                <label for="ingredient-price">Prix d'achat:</label>
                <input type="number" step="0.01" id="ingredient-price-update" name="prix_achat" required>
            </div>

            <div class="form-group">
            <label for="availability">Disponibilité:</label>
            <select id="availability" class="form-control">
                <option value="1">Disponible</option>
                <option value="0">Non-disponible</option>
            </select>
            </div>

            <div class="form-group">
                <label>Image Actuelle:</label>
                <img id="current-image-preview" src="" alt="Aucune image" style="max-width: 200px; display: block; margin-top: 10px;">
            </div>

            <!-- File Input for New Image -->
            <div class="form-group">
                <label for="ingredient-picture-update">Changer l'image:</label>
                <input type="file" id="ingredient-picture-update" name="picture" accept="image/*">
            </div>

            <div class="form-group">
                <img id="image-preview-update" src="" alt="Aperçu de l'image" style="max-width: 100px; display: none;">
            </div>

            <input type="hidden" id="item-id" name="id">
            <button type="submit" class="submit-btn">Modifier</button>
        </form>
    </div>
</div>


















    <section id="facturation-section" class="content-section">
        <h1>Facturation</h1>
        <p></p>
        
        <div class="facturation-container">
            <div class="facturation-box bar" onclick="toggleSection('bar-content')">
                <i class='bx bxs-wine'></i>
                <span>Bar</span>
            </div>
            <div class="facturation-box restaurant" onclick="toggleSection('restaurant-content')">
                <i class='bx bxs-store-alt'></i>
                <span>Restaurant</span>
            </div>
        </div>

        <div id="bar-content" class="facturation-content">
            <h2>Facturation du bar</h2>
            <!-- Custom Date Range Section -->
            <div id="custom-date-range" style="display:none;">
                <label for="start-date">Start Date:</label>
                <input type="date" id="start-date">
    
                <label for="end-date">End Date:</label>
                <input type="date" id="end-date">
    
                <button id="show-button">Show</button>
            </div>
            <div id="time-filter">
                <label for="time-range">Trier par:</label>
                <select id="time-range" onchange="filterBillingData()">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="custom">Custom Date Range</option>
                </select>
            </div>
            <div id="bar-billing-grid" class="billing-grid"></div>
        </div>
    
        <div id="restaurant-content" class="facturation-content"> 
            <h2>Facturation des restaurants</h2>
            <div id="restaurant-time-filter">
                <label for="restaurant-time-range">Trier par:</label>
                <select id="restaurant-time-range" onchange="filterRestaurantBillingData()">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="1month">Last 1 Month</option>
                    <option value="2months">Last 2 Months</option>
                    <option value="3months">Last 3 Months</option>
                    <option value="4months">Last 4 Months</option>
                    <option value="5months">Last 5 Months</option>
                    <option value="6months">Last 6 Months</option>
                    <option value="7months">Last 7 Months</option>
                    <option value="8months">Last 8 Months</option>
                    <option value="9months">Last 9 Months</option>
                    <option value="10months">Last 10 Months</option>
                    <option value="11months">Last 11 Months</option>
                    <option value="12months">Last 12 Months</option>
                </select>
            </div>
            <div id="restaurant-billing-grid" class="billing-grid"></div>
            <div id="no-data-message" style="display: none; color: red; text-align: center;">No data found for the selected date range.</div>
        </div>
    </section>





    <!-- Gestion des Employés Section -->
    <section id="gestion-employes-section" class="content-section">
        <h1>Gestion des Employés</h1>
        <p></p>

        <div style="display: flex; align-items: center; justify-content: space-between">

        <button id="add-employee-btn" class="add-stock-btn">
        <i class='bx bx-plus-circle'></i>
        Ajouter un employe
    </button>

    <button id="presence-employee-btn" class="add-stock-btn">
        <i class='bx bx-plus-circle'></i>
        Presence des employes
    </button>

        </div>


        <div id="attendance-modal" style="display: none;" class="modal">
    <div class="modal-content">
        <h2>Presence des Employés</h2>

        <!-- Tab navigation -->
        <div class="tab-nav">
            <button class="tab-btn active" data-tab="make-attendance">Faire l'Attendance</button>
            <button class="tab-btn" data-tab="view-attendance">Voir l'Attendance</button>
        </div>

        <!-- Tabs content -->
        <div id="make-attendance" class="tab-content active">
            <form id="attendance-form">
            <table id="employee-attendance-manage-table">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Attendance</th>
                            
                        </tr>
                    </thead>
                    <tbody id="manage-employee-list">
                        <!-- Employee list for managing attendance will be dynamically generated here -->
                    </tbody>
                </table>
                <button type="submit" class="save-btn">Save Attendance</button>
            </form>
        </div>

        <div id="view-attendance" class="tab-content">
            <label for="attendance-date">Select Date:</label>
            <input type="date" id="attendance-date" name="attendance-date">
            <button id="view-attendance-btn" class="save-btn">View Attendance</button>

            <table id="employee-attendance-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Attendance Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Attendance data will be dynamically loaded here -->
                </tbody>
            </table>
        </div>

        <button id="close-modal-btn" class="close-btn">Close</button>
    </div>
</div>



        <!--Employee modal-->
        <div id="employee-modal" class="modal">
        <div class="modal-content">
            <span id="close-employee" class="close">&times;</span>
            <h2>Ajouter un employe</h2>
            
            

            <form id="employee-form" method="POST">
    
    
    <div class="form-group">
        <label for="item-name">Nom:</label>
        <input type="text" id="nom" name="nom" required>
    </div>

    <div class="form-group">
        <label for="item-name">Prenom:</label>
        <input type="text" id="prenom" name="prenom" required>
    </div>

    <div class="form-group">
        <label for="item-name">Poste:</label>
        <input type="text" id="poste" name="poste" required>
    </div>


    <div class="form-group">
        <label for="item-price">Salaire:</label>
        <input type="number" step="0.01" id="salaire" name="salaire" required>
    </div>

    <div class="form-group">
        <label for="item-price">Numero du serveur:</label>
        <input type="number" id="numero_serveur" name="numero_serveur">
    </div>

    <div class="form-group">
        <label for="item-name">Date d'embauche:</label>
        <input type="date" id="date_embauche" name="date_embauche" required>
    </div>


    <button type="submit" class="submit-btn">Ajouter employe</button>
</form>
        </div>
    </div>



    <div id="employee-modal-update" class="modal">
        <div class="modal-content">
            <span id="close-employee" class="close">&times;</span>
            <h2>Mettre a jour les details</h2>
            
            

            <form id="employee-form-update" method="POST">
    
    
    <div class="form-group">
        <label for="item-name">Nom:</label>
        <input type="text" id="nom-update" name="nom" required>
    </div>

    <div class="form-group">
        <label for="item-name">Prenom:</label>
        <input type="text" id="prenom-update" name="prenom" required>
    </div>

    <div class="form-group">
        <label for="item-name">Poste:</label>
        <input type="text" id="poste-update" name="poste" required>
    </div>


    <div class="form-group">
        <label for="item-price">Salaire:</label>
        <input type="number" step="0.01" id="salaire-update" name="salaire" required>
    </div>

    <div class="form-group">
        <label for="item-price">Numero du serveur:</label>
        <input type="number" id="numero_serveur-update" name="numero_serveur">
    </div>

    <div class="form-group">
        <label for="item-name">Date d'embauche:</label>
        <input type="date" id="date_embauche-update" name="date_embauche" required>
    </div>


    <button type="submit" class="submit-btn">Mettre a jour</button>
</form>
        </div>
    </div>


    <div class="table-container" style="margin-top: 10px;">
    <table id="employee-table" class="display">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Poste</th>
                <th>Numero du serveur</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here -->
        </tbody>
    </table>
</div>

    </section>







    <section id="gestion-equipement-section" class="content-section">
        <h1>Gestion des Equipements</h1>
        <p></p>

        <div>
        <div style="display: flex; align-items: center; justify-content: space-between">
        <button id="add-equipment-btn" class="add-stock-btn">
                <i class='bx bx-plus-circle'></i>
                Ajouter equipement
                </button>

                <button id="add-complaints-btn" class="add-stock-btn" onclick="showGestionPlaintes()">
                <i class='bx bx-show'></i>
                    Voir rapport des equippements
                </button>

        </div>

                <div class="table-container" style="margin-top: 10px;">
    <table id="equipement-table" class="display">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Quantite</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here -->
        </tbody>
    </table>
</div>

        </div>





    <hr style="margin-top: 20px"/>


        <div>

        <div style="display: flex; align-items: center; justify-content: space-between">
        <button id="add-equipment-damaged-btn" class="add-stock-btn">
                <i class='bx bx-plus-circle'></i>
                Ajouter equipement endommage
                </button>

        </div>

                <div class="table-container" style="margin-top: 10px;">
    <table id="equipement-endommage-table" class="display">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Quantite</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here -->
        </tbody>
    </table>
</div>

        </div>


        <div id="equipement-damaged-modal" class="modal">
        <div class="modal-content">
            <span id="close-equipment" class="close">&times;</span>
            <h2>Ajouter un equipement endommage</h2>
            
            

            <form id="equipement-damaged-form" method="POST">
    
    
    <div class="form-group">
        <label for="item-name">Choisir l'equipement:</label>
        <select type="text" id="nom_equipement" name="nom_equipement" required>
            <option>Choisir l'equipement</option>
    </select>
    </div>



    <div class="form-group">
        <label for="item-quantite">Quantite:</label>
        <input type="number" id="quantite" name="quantite" required>
    </div>



    <button type="submit" class="submit-btn">Ajouter l'equipement</button>
</form>
        </div>
    </div>


















<div id="equipement-modal" class="modal">
        <div class="modal-content">
            <span id="close-equipment" class="close">&times;</span>
            <h2>Ajouter un equipement</h2>
            
            

            <form id="equipement-form" method="POST">
    
    
    <div class="form-group">
        <label for="item-name">Nom de l'equipement:</label>
        <input type="text" id="nom_equipement" name="nom_equipement" required>
    </div>



    <div class="form-group">
        <label for="item-price">Quantite:</label>
        <input type="number" step="0.01" id="quantite" name="quantite" required>
    </div>



    <button type="submit" class="submit-btn">Ajouter equipement</button>
</form>
        </div>
    </div>




    <div id="equipement-modal-update" class="modal">
        <div class="modal-content">
            <span id="close-equipment" class="close">&times;</span>
            <h2>Ajouter un equipement</h2>
            
            

            <form id="equipement-form-update" method="POST">
    
    
    <div class="form-group">
        <label for="item-name">Nom de l'equipement:</label>
        <input type="text" id="nom_equipement-update" name="nom_equipement-update" required>
    </div>



    <div class="form-group">
        <label for="item-price">Quantite:</label>
        <input type="number" id="quantite-update" name="quantite-update" required>
    </div>



    <button type="submit" class="submit-btn">Modifier equipement</button>
</form>
        </div>
    </div>
    </section>

    <!--Complaints section-->

    <section id="complaints-section" class="content-section">
        <h1>Rapports des plaintes</h1>
        <div id="complaints-grid" class="complaints-grid">
        <p>Chargement des plaintes...</p>
    </div>


    <!-- Complaint Modal -->
    <div id="complaint-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000;">
    <div style="position: relative; background-color: #fff; margin: 50px auto; padding: 30px; width: 90%; max-width: 600px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); animation: slideIn 0.3s ease-out;">
        <span id="close-complaint" style="position: absolute; right: 20px; top: 20px; font-size: 24px; cursor: pointer; color: #666; transition: color 0.2s;">&times;</span>
        
        <h2 style="color: #2c3e50; margin: 0 0 30px 0; font-size: 24px; font-weight: 600;">Détails de la Réclamation</h2>
        
        <form id="complaint-form">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #4a5568; font-weight: 500; font-size: 14px;">Nom du Responsable:</label>
                <input type="text" id="complaint-manager" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #f8fafc; color: #2d3748; font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s;">
            </div>
            
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #4a5568; font-weight: 500; font-size: 14px;">Élément Spécifique:</label>
                <input type="text" id="complaint-item" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #f8fafc; color: #2d3748; font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #4a5568; font-weight: 500; font-size: 14px;">Impact:</label>
                <input type="text" id="complaint-impact" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #f8fafc; color: #2d3748; font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #4a5568; font-weight: 500; font-size: 14px;">Description:</label>
                <textarea id="complaint-description" readonly style="width: 100%; min-height: 100px; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #f8fafc; color: #2d3748; font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s; resize: vertical;"></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #4a5568; font-weight: 500; font-size: 14px;">Date de Soumission:</label>
                    <input type="text" id="complaint-date" readonly style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #f8fafc; color: #2d3748; font-size: 14px; transition: border-color 0.2s, box-shadow 0.2s;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #4a5568; font-weight: 500; font-size: 14px;">Statut:</label>
                    <div id="complaint-status" style="display: inline-block; padding: 6px 12px; border-radius: 9999px; font-size: 14px; font-weight: 500; background-color: #fef9c3; color: #854d0e;"></div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes slideIn {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@media (max-width: 640px) {
    #complaint-modal .modal-content {
        margin: 20px;
        padding: 20px;
        width: auto;
    }
    
    #complaint-modal .two-columns {
        grid-template-columns: 1fr;
    }
}
</style>


    </section>










    <!-- General Report Section (Hidden by Default) -->
<div id="general-report-section" class="content-container" style="display: none;">
    <h2>Rapports Généraux</h2>
    
    <div class="general-report-boxes">
        <div class="general-report-box">
            <i class='bx bxs-beer'></i>
            <span>Rapport du Bar</span>
        </div>
        <div class="general-report-box">
            <i class='bx bxs-bowl-hot'></i>
            <span>Rapport Resto</span>
        </div>
        <div class="general-report-box">
            <i class='bx bxs-bar-chart-alt-2'></i>
            <span>Stock Bouteille</span>
        </div>
        <div class="general-report-box">
            <i class='bx bxs-bell-ring'></i>
            <span>Casse ou Perte</span>
        </div>
    </div>
</div>

<!-- Add this in your HTML where you want the bar section to appear -->
<div id="bar-section" class="content-section" style="display: none;">
    <!-- Your existing bar section code -->
    <div class="section-header">
        <h2>Bar Data</h2>
        <!-- Sort by options -->
        <label for="sort-by">Trier par:</label>
        <select id="sort-by" onchange="fetchData()">
            <option value="date">Date</option>
            <option value="serveur">Serveur</option>
            <option value="nom_boisson">Boisson Name</option>
        </select>
        
        <select id="boisson-name" style="display: none;" onchange="fetchData()"></select>
    
        <!-- Date selection for sorting by date -->
        <div id="date-selection" style="display: block;">
            <label for="date-start">Date Début:</label>
            <input type="date" id="date-start">
            <label for="date-end">Date Fin:</label>
            <input type="date" id="date-end">
            
            <!-- Button to trigger data fetch -->
            <button type="button" onclick="fetchData()">
                <i class="bi bi-search">Search</i>
            </button>

            
<!-- Button to Generate Summarized Report -->
<button type="button" id="generate-summary-report-btn" class="generate-summary-report-btn">
    <i class="bx bx-file"></i> Générer un Rapport Résumé
</button>

<!-- Div for selecting a date, initially hidden -->
<div id="report-date-container" style="margin-bottom: 10px; display: none; position: -webkit-sticky; top: 180px; right: 40px; background-color: white; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    <label for="report-date">Sélectionnez une date: </label>
    <input type="date" id="report-date" />
</div>

<!-- Div to display the table -->
<div id="summaryReport" style="display: none;"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let isReportVisible = false;

document.getElementById('generate-summary-report-btn').addEventListener('click', function () {
    const reportDateDiv = document.getElementById('report-date-container');
    const selectedDate = document.getElementById('report-date').value;

    // Toggle the visibility of the date input field
    if (reportDateDiv.style.display === 'none') {
        reportDateDiv.style.display = 'block';
    } else {
        reportDateDiv.style.display = 'none';
    }

    // If a date is selected, add it to the query string for filtering
    const url = selectedDate
        ? `G_boisson/fetch-summary-report.php?date_commande=${selectedDate}`
        : 'G_boisson/fetch-summary-report.php'; // Default, no date filter

    if (!isReportVisible) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data === "0 results") {
                    document.getElementById('summaryReport').innerHTML = "<p>Aucun résultat trouvé.</p>";
                } else {
                    let table = `
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h2>Rapport Général des Boissons Vendus</h2>
                            <div style="display: flex;">
                                <button id="export-pdf-btn" style="padding: 8px 16px; margin-right: 10px; background-color: #808080; color: white; border: none; border-radius: 5px; cursor: pointer;" onmouseover="this.style.backgroundColor='#696969'" onmouseout="this.style.backgroundColor='#808080'">
                                    <i class="bx bxs-file-export" style="margin-right: 8px;"></i> Exporter en PDF
                                </button>
                                <button id="export-excel-btn" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;" onmouseover="this.style.backgroundColor='#45a049'" onmouseout="this.style.backgroundColor='#4CAF50'">
                                    <i class="bx bx-spreadsheet" style="margin-right: 8px;"></i> Exporter en Excel
                                </button>
                            </div>
                        </div>
                        <table id="summary-table">
                            <tr><th>Nom Boisson</th><th>Quantité Vendus</th><th>Montant des Ventes</th></tr>
                    `;

                    let grandTotal = 0;

                    // Loop through fetched data and create the table rows
                    data.forEach(item => {
                        table += `<tr><td>${item.nom_boisson}</td><td>${item.total_quantite}</td><td>${item.total_price}</td></tr>`;
                        grandTotal += parseFloat(item.total_price);
                    });

                    table += `
                        <tr style="background-color: #dff0d8; font-weight: bold;">
                            <td colspan="2" style="text-align: right; padding: 10px; border: 1px solid #ddd;">Total Général des boissons</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">${grandTotal.toFixed(2)} FBU</td>
                        </tr>
                    </table>`;

                    document.getElementById('summaryReport').innerHTML = table;

                    // Export to PDF logic
                    document.getElementById('export-pdf-btn').addEventListener('click', function () {
                        Swal.fire({
                            title: 'Êtes-vous sûr?',
                            text: "Voulez-vous vraiment exporter le rapport en PDF?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Oui, exporter!',
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const { jsPDF } = window.jspdf;
                                const doc = new jsPDF('landscape');
                                const pageWidth = doc.internal.pageSize.width;

                                doc.setFont("helvetica", "bold");
                                doc.setFontSize(16);
                                doc.setTextColor(0);
                                doc.text("KU KAYAGA BAR", 15, 15);

                                doc.setFont("helvetica", "italic");
                                doc.setFontSize(12);
                                doc.setTextColor(0);
                                doc.text("Rapport de Vente des Boissons", 15, 22);

                                const now = new Date();
                                const formattedDate = now.toLocaleDateString('fr-FR');
                                const formattedTime = now.toLocaleTimeString('fr-FR');

                                doc.setFont("helvetica", "normal");
                                doc.setFontSize(10);
                                doc.setTextColor(0);
                                doc.text(`Date: ${formattedDate} | Heure: ${formattedTime}`, pageWidth - 60, 20);

                                doc.setFont("helvetica", "bold");
                                doc.setFontSize(18);
                                doc.setTextColor(0, 0, 0);
                                doc.text("Billan Général des Boissons Vendues", pageWidth / 2, 35, { align: "center" });

                                doc.autoTable({
                                    html: "#summary-table",
                                    startY: 45,
                                    theme: 'grid',
                                    styles: {
                                        fontSize: 12,
                                        cellPadding: 6,
                                        valign: "middle",
                                        textColor: 0
                                    },
                                    headStyles: {
                                        fillColor: [34, 139, 34],
                                        textColor: 255,
                                        fontSize: 14,
                                        fontStyle: "bold"
                                    },
                                    alternateRowStyles: { fillColor: [245, 245, 245] },
                                    tableWidth: 'auto',
                                    margin: { left: 15, right: 15 },
                                    columnStyles: {
                                        0: { cellWidth: "auto", halign: "left" },
                                        1: { cellWidth: "auto", halign: "center" },
                                        2: { cellWidth: "auto", halign: "center", fontStyle: "bold", textColor: [0, 0, 0] }
                                    }
                                });

                                doc.setFont("helvetica", "italic");
                                doc.setFontSize(12);
                                doc.setTextColor(0);
                                doc.text("Signature du Responsable: ___________________", 15, doc.internal.pageSize.height - 15);

                                doc.save(`Rapport Boissons Vendus_${formattedDate}.pdf`);
                            }
                        });
                    });

                    // Export to Excel logic
                    document.getElementById('export-excel-btn').addEventListener('click', function () {
                        Swal.fire({
                            title: 'Êtes-vous sûr?',
                            text: "Voulez-vous vraiment exporter le rapport en Excel?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Oui, exporter!',
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const now = new Date();
                                const formattedDate = now.toLocaleDateString('fr-FR');
                                const formattedTime = now.toLocaleTimeString('fr-FR');

                                const ws = XLSX.utils.aoa_to_sheet([["Rapport Général des Boissons Vendus"], ["Generé le: " + formattedDate + " à " + formattedTime], [], ["Nom Boisson", "Quantité Vendus", "Montant des Ventes"]]);

                                ws['A1'].s = { font: { bold: true } };
                                ws['A2'].s = { font: { italic: true } };

                                data.forEach(item => {
                                    XLSX.utils.sheet_add_aoa(ws, [[item.nom_boisson, item.total_quantite, item.total_price]], { origin: -1 });
                                });

                                XLSX.utils.sheet_add_aoa(ws, [[]], { origin: -1 });

                                XLSX.utils.sheet_add_aoa(ws, [["Total Général des boissons", "", grandTotal.toFixed(2) + " FBU"]], { origin: -1 });

                                const wscols = [
                                    { wch: 30 },
                                    { wch: 20 },
                                    { wch: 25 }
                                ];
                                ws['!cols'] = wscols;

                                const wb = XLSX.utils.book_new();
                                XLSX.utils.book_append_sheet(wb, ws, "Rapport des Ventes");
                                XLSX.writeFile(wb, `Rapport_Boissons_Vendus_${formattedDate}.xlsx`);
                            }
                        });
                    });
                }

                document.getElementById('summaryReport').style.display = 'block';
                isReportVisible = true;
                this.innerHTML = '<i class="bx bx-hide"></i> Cacher le Rapport';
            })
            .catch(error => console.error('Error:', error));
    } else {
        document.getElementById('summaryReport').style.display = 'none';
        isReportVisible = false;
        this.innerHTML = '<i class="bx bxs-file-export"></i> Générer un Rapport Résumé';
    }
});

// Dynamic report generation when date is selected
document.getElementById('report-date').addEventListener('change', function () {
    const selectedDate = this.value;
    const url = selectedDate
        ? `G_boisson/fetch-summary-report.php?date_commande=${selectedDate}`
        : 'G_boisson/fetch-summary-report.php'; // Default, no date filter

    if (selectedDate) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data === "0 results") {
                    document.getElementById('summaryReport').innerHTML = "<p>Aucun résultat trouvé.</p>";
                } else {
                    let table = `
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h2>Rapport Général des Boissons Vendus</h2>
                            <div style="display: flex;" id="export-buttons-container">
                                <!-- Export buttons will be added here dynamically -->
                            </div>
                        </div>
                        <table id="summary-table">
                            <tr><th>Nom Boisson</th><th>Quantité Vendus</th><th>Montant des Ventes</th></tr>
                    `;

                    let grandTotal = 0;

                    // Loop through fetched data and create the table rows
                    data.forEach(item => {
                        table += `<tr><td>${item.nom_boisson}</td><td>${item.total_quantite}</td><td>${item.total_price}</td></tr>`;
                        grandTotal += parseFloat(item.total_price);
                    });

                    table += `
                        <tr style="background-color: #dff0d8; font-weight: bold;">
                            <td colspan="2" style="text-align: right; padding: 10px; border: 1px solid #ddd;">Total Général des boissons</td>
                            <td style="padding: 10px; border: 1px solid #ddd;">${grandTotal.toFixed(2)} FBU</td>
                        </tr>
                    </table>`;

                    document.getElementById('summaryReport').innerHTML = table;
                    document.getElementById('summaryReport').style.display = 'block';

                    // Create Export Buttons dynamically after the table is generated
                    const exportButtonsContainer = document.getElementById('export-buttons-container');

                    // Export to PDF button
                    const exportPdfButton = document.createElement('button');
                    exportPdfButton.id = 'export-pdf-btn';
                    exportPdfButton.innerHTML = `<i class="bx bxs-file-export" style="margin-right: 8px;"></i> Exporter en PDF`;
                    exportPdfButton.style.padding = '8px 16px';
                    exportPdfButton.style.marginRight = '10px';
                    exportPdfButton.style.backgroundColor = '#808080';
                    exportPdfButton.style.color = 'white';
                    exportPdfButton.style.border = 'none';
                    exportPdfButton.style.borderRadius = '5px';
                    exportPdfButton.style.cursor = 'pointer';
                    exportPdfButton.addEventListener('mouseover', () => { exportPdfButton.style.backgroundColor = '#696969'; });
                    exportPdfButton.addEventListener('mouseout', () => { exportPdfButton.style.backgroundColor = '#808080'; });

                    exportButtonsContainer.appendChild(exportPdfButton);

                    // Export to Excel button
                    const exportExcelButton = document.createElement('button');
                    exportExcelButton.id = 'export-excel-btn';
                    exportExcelButton.innerHTML = `<i class="bx bx-spreadsheet" style="margin-right: 8px;"></i> Exporter en Excel`;
                    exportExcelButton.style.padding = '8px 16px';
                    exportExcelButton.style.backgroundColor = '#4CAF50';
                    exportExcelButton.style.color = 'white';
                    exportExcelButton.style.border = 'none';
                    exportExcelButton.style.borderRadius = '5px';
                    exportExcelButton.style.cursor = 'pointer';
                    exportExcelButton.addEventListener('mouseover', () => { exportExcelButton.style.backgroundColor = '#45a049'; });
                    exportExcelButton.addEventListener('mouseout', () => { exportExcelButton.style.backgroundColor = '#4CAF50'; });

                    exportButtonsContainer.appendChild(exportExcelButton);

                    // Export to PDF logic
                    exportPdfButton.addEventListener('click', function () {
                        Swal.fire({
                            title: 'Êtes-vous sûr?',
                            text: "Voulez-vous vraiment exporter le rapport en PDF?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Oui, exporter!',
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const { jsPDF } = window.jspdf;
                                const doc = new jsPDF('landscape');
                                const pageWidth = doc.internal.pageSize.width;

                                doc.setFont("helvetica", "bold");
                                doc.setFontSize(16);
                                doc.setTextColor(0);
                                doc.text("KU KAYAGA BAR", 15, 15);

                                doc.setFont("helvetica", "italic");
                                doc.setFontSize(12);
                                doc.setTextColor(0);
                                doc.text("Rapport de Vente des Boissons", 15, 22);

                                const now = new Date();
                                const formattedDate = now.toLocaleDateString('fr-FR');
                                const formattedTime = now.toLocaleTimeString('fr-FR');

                                doc.setFont("helvetica", "normal");
                                doc.setFontSize(10);
                                doc.setTextColor(0);
                                doc.text(`Date: ${formattedDate} | Heure: ${formattedTime}`, pageWidth - 60, 20);

                                doc.setFont("helvetica", "bold");
                                doc.setFontSize(18);
                                doc.setTextColor(0, 0, 0);
                                doc.text("Billan Général des Boissons Vendues", pageWidth / 2, 35, { align: "center" });

                                doc.autoTable({
                                    html: "#summary-table",
                                    startY: 45,
                                    theme: 'grid',
                                    styles: {
                                        fontSize: 12,
                                        cellPadding: 6,
                                        valign: "middle",
                                        textColor: 0
                                    },
                                    headStyles: {
                                        fillColor: [34, 139, 34],
                                        textColor: 255,
                                        fontSize: 14,
                                        fontStyle: "bold"
                                    },
                                    alternateRowStyles: { fillColor: [245, 245, 245] },
                                    tableWidth: 'auto',
                                    margin: { left: 15, right: 15 },
                                    columnStyles: {
                                        0: { cellWidth: "auto", halign: "left" },
                                        1: { cellWidth: "auto", halign: "center" },
                                        2: { cellWidth: "auto", halign: "center", fontStyle: "bold", textColor: [0, 0, 0] }
                                    }
                                });

                                doc.setFont("helvetica", "italic");
                                doc.setFontSize(12);
                                doc.setTextColor(0);
                                doc.text("Signature du Responsable: ___________________", 15, doc.internal.pageSize.height - 15);

                                doc.save(`Rapport Boissons Vendus_${formattedDate}.pdf`);
                            }
                        });
                    });

                    // Export to Excel logic
                    exportExcelButton.addEventListener('click', function () {
                        Swal.fire({
                            title: 'Êtes-vous sûr?',
                            text: "Voulez-vous vraiment exporter le rapport en Excel?",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Oui, exporter!',
                            cancelButtonText: 'Annuler'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const now = new Date();
                                const formattedDate = now.toLocaleDateString('fr-FR');
                                const formattedTime = now.toLocaleTimeString('fr-FR');

                                const ws = XLSX.utils.aoa_to_sheet([["Rapport Général des Boissons Vendus"], ["Generé le: " + formattedDate + " à " + formattedTime], [], ["Nom Boisson", "Quantité Vendus", "Montant des Ventes"]]);

                                ws['A1'].s = { font: { bold: true } };
                                ws['A2'].s = { font: { italic: true } };

                                data.forEach(item => {
                                    XLSX.utils.sheet_add_aoa(ws, [[item.nom_boisson, item.total_quantite, item.total_price]], { origin: -1 });
                                });

                                XLSX.utils.sheet_add_aoa(ws, [[]], { origin: -1 });

                                XLSX.utils.sheet_add_aoa(ws, [["Total Général des boissons", "", grandTotal.toFixed(2) + " FBU"]], { origin: -1 });

                                const wscols = [
                                    { wch: 30 },
                                    { wch: 20 },
                                    { wch: 25 }
                                ];
                                ws['!cols'] = wscols;

                                const wb = XLSX.utils.book_new();
                                XLSX.utils.book_append_sheet(wb, ws, "Rapport des Ventes");
                                XLSX.writeFile(wb, `Rapport_Boissons_Vendus_${formattedDate}.xlsx`);
                            }
                        });
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        document.getElementById('summaryReport').style.display = 'none';
    }
});

</script>

<!-- Styling for the Report Table -->
<style>
#summaryReport h2 {
    margin-bottom: 10px;
    text-align: left;
    font-weight: bold;
}

#summaryReport {
    margin: 20px auto; /* Center the div */
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 1200px;
}

#summary-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    table-layout: fixed;
}

#summary-table th {
    background-color: #4CAF50;
    color: white;
    padding: 12px;
    text-align: center;
    font-weight: bold;
    border-bottom: 1px solid #ddd; /* Add border to header */
    border-right: 1px solid #ccc; /* Add gray line between columns */
}

#summary-table th:last-child {
    border-right: none; /* Remove border from last column */
}

#summary-table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
    border-right: 1px solid #ccc; /* Add gray line between columns */
}

#summary-table td:last-child {
    border-right: none; /* Remove border from last column */
}

#summary-table tr:hover {
    background-color: #f2f2f2;
}

#summary-table tr:last-child td {
    border-bottom: none; /* Remove border from last row */
}

#summaryReport p {
    font-style: italic;
    color: #888;
    text-align: center;
    margin-bottom: 20px; /* Add some space below the message */
}
</style> 

        </div>
        
<!-- Export buttons -->
<div id="export-buttons" style="margin-top: 10px; display: none; float: right;">
    <button id="export-pdf-btn" style="padding: 8px 16px; margin-right: 10px; margin-top: -80px; background-color: #808080; color: white; border: none; border-radius: 5px; cursor: pointer;" onmouseover="this.style.backgroundColor='#696969'" onmouseout="this.style.backgroundColor='#808080'">
        <i class="bx bxs-file-export" style="margin-right: 8px;"></i> Exporter en PDF
    </button>
    <button id="export-excel-btn" style="padding: 8px 16px; background-color: #4CAF50; margin-top: -80px; color: white; border: none; border-radius: 5px; cursor: pointer;" onmouseover="this.style.backgroundColor='#45a049'" onmouseout="this.style.backgroundColor='#4CAF50'">
        <i class="bx bx-spreadsheet" style="margin-right: 8px;"></i> Exporter en Excel
    </button>
</div>

<!-- Add these script tags in the head section of your HTML -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.5/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

<script>
    // Function to export table data to Excel with SweetAlert2 confirmation
    function exportToExcel() {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Voulez-vous vraiment exporter les données en Excel?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Oui, exporter',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                let table = document.getElementById('bar-data-table');
                let rows = table.querySelectorAll('tr');
                let csvContent = [];

                // Manually set headers for Excel export
                const headers = ['N° SERVEUR', 'BOISSON', 'QUANTITÉ', 'PRIX', 'TOTAL', 'STATUT', 'DATE'];
                csvContent.push(headers.join(',')); // Add headers to the first row

                rows.forEach(row => {
                    let rowData = [];
                    row.querySelectorAll('th, td').forEach(cell => {
                        rowData.push(cell.innerText);
                    });
                    csvContent.push(rowData.join(',')); // Add the row data
                });

                let csvString = csvContent.join('\n');
                let blob = new Blob([csvString], { type: 'text/csv' });
                let link = document.createElement('a');
                
                link.href = URL.createObjectURL(blob);
                link.download = 'bar_data_export.csv';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        });
    }

    function exportToPdf() {
    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Voulez-vous vraiment exporter les données en PDF?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, exporter',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            // Get the table data
            let table = document.getElementById('bar-data-table');
            let rows = table.querySelectorAll('tr');

            // Prepare the data for PDF export
            let tableData = [];
            rows.forEach(row => {
                let rowData = [];
                row.querySelectorAll('th, td').forEach(cell => {
                    rowData.push(cell.innerText);
                });
                tableData.push(rowData);
            });

            // Create a new jsPDF instance with landscape orientation
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape'); // 'landscape' orientation

            // Add title (Bar Data) and timestamp with reduced font size
            const title = "KU KAYAGA BAR | RAPPORT PARTIEL";
            const dateTime = new Date().toLocaleString(); // Current date and time

            // Title settings
            doc.setFontSize(14); // Set font size for the title
            doc.setFont("helvetica", "bold"); // Set font to bold
            doc.text(title, 13, 20); // Title at the top left

            // Date settings (right-aligned)
            doc.setFontSize(10); // Set font size for the date
            doc.setFont("helvetica", "normal"); // Set font to normal
            doc.text(dateTime, 282, 20, { align: "right" }); // Date on the right side

            // Add table to PDF using autoTable plugin
            doc.autoTable({
                startY: 30, // Start the table below the title and timestamp
                head: [tableData[0]], // The first row as the header
                body: tableData.slice(1), // All rows except the header
                margin: { top: 20 }, // Adjust top margin
                styles: {
                    fontSize: 10, // Set a smaller font size for table content
                    cellPadding: 5, // Add some padding to the table cells
                    halign: 'center', // Center align the content in each cell
                    valign: 'middle', // Vertically center the content in each cell
                    lineWidth: 0.1, // Set the line width for borders
                    lineColor: [0, 0, 0, 0] // Set transparent borders (RGBA)
                },
                headStyles: {
                    fillColor: [41, 128, 185], // Set the header background color
                    textColor: [255, 255, 255], // Set the header text color (white)
                    fontSize: 11, // Slightly larger font size for header
                    halign: 'center', // Center align the header text
                    lineWidth: 0.1, // Add thin line between header and body
                    lineColor: [0, 0, 0, 0] // Transparent line color
                },
                bodyStyles: {
                    fillColor: [255, 255, 255], // Set the table body background color (white)
                    textColor: [0, 0, 0], // Set the body text color (black)
                    lineWidth: 0.3, // Add lines between rows
                    lineColor: [211, 211, 211], // Set light gray lines for row and column division
                },
                theme: 'grid', // Use grid theme to display borders between rows/columns
            });

            // Save the generated PDF
            doc.save('bar_data_export.pdf');
        }
    });
}


    // Add event listeners to export buttons
    document.getElementById('export-pdf-btn').addEventListener('click', exportToPdf);
    document.getElementById('export-excel-btn').addEventListener('click', exportToExcel);
</script>





        <!-- Table to display data -->
        <table id="bar-data-table">
            <thead>
                <tr id="table-header">
                    <!-- Dynamic headers will be added here -->
               </tr>
            </thead>
            <tbody>
                <!-- Data will be populated here -->
            </tbody>
        </table>
    </div>
</div>
    <!-- ... rest of your bar section code ... -->
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const generalReportBoxes = document.querySelectorAll('.general-report-box');
    const generalReportSection = document.getElementById('general-report-section');
    const barSection = document.getElementById('bar-section');

    // Handle click on Rapport du Bar box
    generalReportBoxes[0].addEventListener('click', function(e) {
        e.preventDefault();
        generalReportSection.style.display = 'none';
        barSection.style.display = 'block';
        fetchData(); // Load initial data
    });
});

// Function to return to general reports
function showGeneralReports() {
    document.getElementById('general-report-section').style.display = 'block';
    document.getElementById('bar-section').style.display = 'none';
}

</script>














<!-- Resto Data Section -->
<div id="resto-section" class="content-section" style="display: none;">
    <div class="section-header">
        <h2>Resto Data</h2>
    </div>
    
    <div class="filter-options" style="margin-bottom: 17px; display: flex; align-items: center;">
        <div style="margin-right: 10px;">
            <label for="sort-resto" class="filter-label" style="font-weight: bold;">Trier par:</label>
            <select id="sort-resto" class="sort-dropdown" style="font-size: 14px;">
                <option value="date">Date</option>
                <option value="server">Serveur</option>
                <option value="dish-name">Plat name</option>
            </select>
        </div>

        <!-- Dish Name Dropdown (Initially Hidden) -->
        <div id="dish-dropdown-container" style="display: none;">
            <label for="dish-name-resto" class="filter-label" style="font-weight: bold;">Plat Name:</label>
            <select id="dish-name-resto" class="sort-dropdown" style="font-size: 14px;">
                <!-- Options will be populated dynamically -->
            </select>
        </div>
    </div>
    
    <div class="date-filters" style="margin-bottom: 17px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <label for="start-date-resto" class="date-label" style="white-space: nowrap;">Date Début:</label>
        <input type="date" id="start-date-resto" class="date-input" style="max-width: 150px;">
    
        <label for="end-date-resto" class="date-label" style="white-space: nowrap;">Date Fin:</label>
        <input type="date" id="end-date-resto" class="date-input" style="max-width: 150px;">
    
        <button id="search-resto" class="btn btn-primary" style="white-space: nowrap;">
            <i class="fas fa-search"></i> <em>Search</em>
        </button>
    
        <button id="generate-report-resto" class="btn btn-primary" style="white-space: nowrap;">
            <i class="fas fa-file-alt"></i> Générer un Rapport Résumé
        </button>
    </div>
    
    <div id="report-content" style="display:none; margin-top: 20px;">
    <h2>Rapport Général des Assiettes Vendus</h2>
    <!-- Report Date will be shown here -->
    <div id="report-date" style="font-weight: bold; margin-bottom: 10px;"></div>
    
    <!-- Table to display the fetched data -->
    <table id="report-table" class="table table-bordered">
        <thead>
            <tr>
                <th>Nom du Plat</th>
                <th>Total Quantité</th>
                <th>Total Prix</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here -->
        </tbody>
        <tfoot>
            <tr id="grand-total-row" style="font-weight: bold; background-color:rgba(76, 175, 79, 0.33);">
                <td>Total</td>
                <!-- Removed the quantity total -->
                <td></td> <!-- This cell will remain empty for quantity -->
                <td id="grand-total-price">0</td>
            </tr>
        </tfoot>

    </table>
</div>





    <div id="export-buttons-container-resto" style="display: none; justify-content: flex-end; gap: 10px; margin-bottom: -18px;">
    <button id="export-pdf-resto" style="padding: 8px 16px; margin-right: 10px; background-color: #808080; color: white; border: none; border-radius: 5px; cursor: pointer;" onmouseover="this.style.backgroundColor='#696969'" onmouseout="this.style.backgroundColor='#808080'">
    <i class="bx bxs-file-export" style="margin-right: 8px;"></i> Exporter en PDF
</button>

<button id="export-excel-resto" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;" onmouseover="this.style.backgroundColor='#45a049'" onmouseout="this.style.backgroundColor='#4CAF50'">
    <i class="bx bx-spreadsheet" style="margin-right: 8px;"></i> Exporter en Excel
</button>

    </div>

    <!-- Table to display results -->
    <table id="resto-table">
        <thead>
            <!-- Table headers will be dynamically generated based on sort option -->
        </thead>
        <tbody>
            <!-- Data rows will be dynamically populated here -->
        </tbody>
    </table>
</div>


<!-- CSS for Styling -->
<style>

#export-buttons-container-resto {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-bottom: 10px;
}

    .section-header h2 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 15px;
    }
    .filter-options, .date-filters {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .sort-dropdown, .date-input {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .btn {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
        font-size: 16px;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* General Table Styling */
#resto-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 16px;
    text-align: left;
}

/* Table Header Styling */
#resto-table thead {
    background-color:rgba(255, 255, 255, 0.69);
}

#resto-table th {
    padding: 10px;
    font-weight: bold;
    color: #333;
    border-bottom: 2px solid #ddd;
    text-align: left;
    text-transform: uppercase; /* Make table headers uppercase */
}

/* Table Row Styling */
#resto-table tbody tr {
    background-color: #fff;
    border-bottom: 1px solid #f0f0f0;
}

#resto-table tbody tr:hover {
    background-color: #f9f9f9;
}

/* Table Cells Styling */
#resto-table td {
    padding: 12px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1); /* Transparent border between rows */
    text-align: left; /* Align text to the left */
}

/* Transparent border between columns */
#resto-table td, #resto-table th {
    border-right: 1px solid rgba(0, 0, 0, 0.1); /* Transparent border between columns */
}

/* Remove the border on the last column */
#resto-table td:last-child, #resto-table th:last-child {
    border-right: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    #resto-table {
        font-size: 14px;
    }

    #resto-table th, #resto-table td {
        padding: 8px;
    }

    #resto-table thead {
        font-size: 14px;
    }
}

/* Specific Row Styling for Different Sort Options */
#resto-table .server-row {
    background-color: #e0f7fa;
}

#resto-table .dish-name-row {
    background-color: #e8f5e9;
}

#resto-table .date-row {
    background-color: #fff3e0;
}

/* Pagination (if necessary) */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination button {
    padding: 8px 16px;
    margin: 0 4px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
}

.pagination button:hover {
    background-color: #0056b3;
}

.pagination .active {
    background-color: #0056b3;
    font-weight: bold;
}



#report-content {
    margin: 20px auto; /* Center the div */
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 1200px;
}

#report-content h2 {
    margin-bottom: 10px;
    text-align: left;
    font-weight: bold;
}

#report-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    table-layout: fixed;
}

#report-table th {
    background-color: #4CAF50;
    color: white;
    padding: 12px;
    text-align: center;
    font-weight: bold;
    border-bottom: 1px solid #ddd; /* Add border to header */
    border-right: 1px solid #ccc; /* Add gray line between columns */
}

#report-table th:last-child {
    border-right: none; /* Remove border from last column */
}

#report-table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
    border-right: 1px solid #ccc; /* Add gray line between columns */
}

#report-table td:last-child {
    border-right: none; /* Remove border from last column */
}

#report-table tr:hover {
    background-color: #f2f2f2;
}

#report-table tr:last-child td {
    border-bottom: none; /* Remove border from last row */
}

#report-content p {
    font-style: italic;
    color: #888;
    text-align: center;
    margin-bottom: 20px; /* Add some space below the message */
}

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
   
   document.getElementById('generate-report-resto').addEventListener('click', function() {
    const reportContent = document.getElementById('report-content');
    const reportTableBody = document.querySelector('#report-table tbody');
    const grandTotalPrice = document.getElementById('grand-total-price');
    const reportDate = document.getElementById('report-date');

    // Toggle button text between "Générer un Rapport Résumé" and "Cacher le Rapport"
    if (reportContent.style.display === 'block') {
        // If the report is already visible, hide it
        reportContent.style.display = 'none';
        document.getElementById('generate-report-resto').innerHTML = '<i class="bx bxs-file-export"></i> Générer un Rapport Résumé'; // Add icon here
    } else {
        // Otherwise, fetch data and display it
        reportContent.style.display = 'block';
        document.getElementById('generate-report-resto').innerHTML = '<i class="bx bx-hide"></i> Cacher le Rapport'; // Add icon here

        // Get the date from the input (optional)
        const startDate = document.getElementById('start-date-resto').value; // Get the start date
        const endDate = document.getElementById('end-date-resto').value; // Get the end date

        // If start date and end date are provided, construct the filter
        let dateFilter = '';
        if (startDate && endDate) {
            dateFilter = `${startDate} to ${endDate}`;
        } else {
            dateFilter = ''; // If no date, fetch all data
        }

        // Construct the URL for fetching the data, with optional date filter
        let url = 'G_Resto/fetch_report_data.php';
        if (dateFilter) {
            url += `?date_commande=${dateFilter}`;
        }

        // Fetch data from the PHP backend
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Clear previous table content
                reportTableBody.innerHTML = '';

                // Initialize grand total price
                let totalPrice = 0;

                // Populate the table with fetched data
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.nom_plat}</td>
                        <td>${item.total_quantite}</td> <!-- Display quantity -->
                        <td>${item.total_price}</td> <!-- Display price -->
                    `;
                    reportTableBody.appendChild(row);

                    // Accumulate the total price
                    totalPrice += parseFloat(item.total_price);
                });

                // Set the grand total price
                grandTotalPrice.textContent = totalPrice.toFixed(2); // Ensure to show 2 decimal places

                // Set the report date (optional)
                if (dateFilter) {
                    reportDate.textContent = 'Date du Rapport: ' + dateFilter;
                } else {
                    reportDate.textContent = 'Date du Rapport: All Data';
                }
            })
            .catch(error => {
                console.error('Error fetching report data:', error);
            });
    }
});





    document.addEventListener("DOMContentLoaded", function() {
    const generalReportBoxes = document.querySelectorAll('.general-report-box');
    const generalReportSection = document.getElementById('general-report-section');
    const restoSection = document.getElementById('resto-section');
    const sortSelect = document.getElementById("sort-resto");
    const dishDropdownContainer = document.getElementById("dish-dropdown-container");
    const dishNameSelect = document.getElementById("dish-name-resto");
    const searchButton = document.getElementById("search-resto");
    const restoTableBody = document.getElementById("resto-table").getElementsByTagName('tbody')[0];

    // Handle click on Rapport Resto box
    generalReportBoxes[1].addEventListener('click', function(e) {
        e.preventDefault();
        generalReportSection.style.display = 'none';
        restoSection.style.display = 'block';
        console.log("Resto Data section displayed.");
    });

    // Fetch dish names when "Plat name" is selected as the sorting option
    sortSelect.addEventListener('change', function() {
        const sortOption = this.value;
        if (sortOption === 'dish-name') {
            dishDropdownContainer.style.display = 'block';
            fetchDishNames();
        } else {
            dishDropdownContainer.style.display = 'none';
            fetchData(sortOption);  // Automatically fetch data when sorting by serveur or dish-name
        }
    });

    // Handle dish name selection to fetch data
    dishNameSelect.addEventListener('change', function() {
        const dishName = this.value;
        if (dishName) {
            fetchData('dish-name', dishName); // Fetch data based on selected dish name
        }
    });

    // Fetch distinct dish names from the database for "Plat name" sorting
    function fetchDishNames() {
        fetch('G_Resto/fetch_dish_names.php')
            .then(response => response.json())
            .then(data => {
                dishNameSelect.innerHTML = data.map(dish => `<option value="${dish.nom_plat}">${dish.nom_plat}</option>`).join('');
            })
            .catch(error => console.error("Error fetching dish names:", error));
    }

    // Handle Search button click
    searchButton.addEventListener("click", function() {
        const sortOption = sortSelect.value;
        const dishName = dishNameSelect.value;
        fetchData(sortOption, dishName); // Fetch data based on selected sort option and dish name
    });

    // Fetch and display data based on the selected filters
function fetchData(sortOption, dishName = '') {
    let url = '';
    
    if (sortOption === 'server') {
        url = `G_Resto/fetch_data_server.php`;
    } else if (sortOption === 'dish-name' && dishName !== '') {
        url = `G_Resto/fetch_data_dish_name.php?dishName=${dishName}`;
    } else if (sortOption === 'date') {
        const startDate = document.getElementById("start-date-resto").value;
        const endDate = document.getElementById("end-date-resto").value;
        
        // Continue without alert if startDate or endDate is missing
        if (startDate && endDate) {
            url = `G_Resto/fetch_data_date.php?startDate=${startDate}&endDate=${endDate}`;
        } else {
            // If dates are missing, don't set the URL and skip the fetch request
            url = '';  // You could optionally choose to handle this case differently
        }
    }
    
    if (url) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                displayData(data, sortOption);
            })
            .catch(error => console.error("Error fetching data:", error));
    }
}


    // Display data in the table based on the selected filter
function displayData(data, sortOption) {
    restoTableBody.innerHTML = '';

    let tableHeaders = '';
    let rows = '';

    if (sortOption === 'server') {
        tableHeaders = 
            `<th>Numero Serveur</th><th>Nom Plat</th><th>Quantité</th><th>Prix</th><th>Total</th><th>Statut</th><th>Date</th>`;
        rows = data.map(item => {
            let statutPaiementColor = '';
            if (item.statut_paiement.toLowerCase() === 'pending' || item.statut_paiement.toLowerCase() === 'unpaid') {
                statutPaiementColor = 'color: red; font-weight: bold;';  // Red and bold for pending/unpaid
            } else if (item.statut_paiement.toLowerCase() === 'paid') {
                statutPaiementColor = 'color: green; font-weight: bold;';  // Green and bold for paid
            }

            return `
                <tr>
                    <td>${item.numero_serveur}</td>
                    <td>${item.nom_plat}</td>
                    <td>${item.quantite}</td>
                    <td>${item.prix}</td>
                    <td>${item.total_price}</td>
                    <td style="${statutPaiementColor}">${item.statut_paiement}</td>
                    <td>${item.date_commande}</td>
                </tr>`;
        }).join('');
    } else if (sortOption === 'dish-name') {
        tableHeaders = 
            `<th>Nom Plat</th><th>Quantité</th><th>Prix</th><th>Total</th><th>N°Serveur</th><th>Table</th><th>Numero Facture</th><th>Statut</th><th>Date</th>`;
        rows = data.map(item => {
            let statutPaiementColor = '';
            if (item.statut_paiement.toLowerCase() === 'pending' || item.statut_paiement.toLowerCase() === 'unpaid') {
                statutPaiementColor = 'color: red; font-weight: bold;';
            } else if (item.statut_paiement.toLowerCase() === 'paid') {
                statutPaiementColor = 'color: green; font-weight: bold;';
            }

            return `
                <tr>
                    <td>${item.nom_plat}</td>
                    <td>${item.quantite}</td>
                    <td>${item.prix}</td>
                    <td>${item.total_price}</td>
                    <td>${item.numero_serveur}</td>
                    <td>${item.numero_table}</td>
                    <td>${item.numero_facture}</td>
                    <td style="${statutPaiementColor}">${item.statut_paiement}</td>
                    <td>${item.date_commande}</td>
                </tr>`;
        }).join('');
    } else if (sortOption === 'date') {
        tableHeaders = 
            `<th>Numero Serveur</th><th>Numero Table</th><th>Numero Facture</th><th>Statut</th><th>Mode Paiement</th><th>Date</th>`;
        rows = data.map(item => {
            let statutPaiementColor = '';
            if (item.statut_paiement.toLowerCase() === 'pending' || item.statut_paiement.toLowerCase() === 'unpaid') {
                statutPaiementColor = 'color: red; font-weight: bold;';
            } else if (item.statut_paiement.toLowerCase() === 'paid') {
                statutPaiementColor = 'color: green; font-weight: bold;';
            }

            return `
                <tr>
                    <td>${item.numero_serveur}</td>
                    <td>${item.numero_table}</td>
                    <td>${item.numero_facture}</td>
                    <td style="${statutPaiementColor}">${item.statut_paiement}</td>
                    <td>${item.mode_paiement}</td>
                    <td>${item.date_commande}</td>
                </tr>`;
        }).join('');
    }

    restoTableBody.innerHTML = rows;
    document.querySelector("#resto-table thead").innerHTML = tableHeaders;
}

});



document.addEventListener("DOMContentLoaded", function () {
    const sortDropdown = document.getElementById("sort-resto");
    const exportButtonsContainer = document.getElementById("export-buttons-container-resto");
    const dishDropdownContainer = document.getElementById("dish-dropdown-container");
    const dishNameDropdown = document.getElementById("dish-name-resto");

    // Listen for changes in the sort dropdown
    sortDropdown.addEventListener("change", function () {
        const selectedSortOption = sortDropdown.value;

        // Show the dish name dropdown only when "dish-name" is selected
        if (selectedSortOption === "dish-name") {
            dishDropdownContainer.style.display = "block"; // Show the Plat Name dropdown
        } else {
            dishDropdownContainer.style.display = "none"; // Hide the Plat Name dropdown if not selected
        }

        // Ensure export buttons remain hidden until a dish name is selected
        exportButtonsContainer.style.display = "none"; // Hide export buttons initially
    });

    // Listen for changes in the Plat Name dropdown
    dishNameDropdown.addEventListener("change", function () {
        // Show the export buttons only if a dish name is selected
        if (dishNameDropdown.value) {
            exportButtonsContainer.style.display = "flex"; // Show the export buttons
        } else {
            exportButtonsContainer.style.display = "none"; // Hide the export buttons if no dish is selected
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const sortDropdown = document.getElementById("sort-resto");
    const exportButtonsContainer = document.getElementById("export-buttons-container-resto");

    sortDropdown.addEventListener("change", function () {
        const selectedSortOption = sortDropdown.value;

        // Show export buttons only when "Date" or "Serveur" is selected
        if (selectedSortOption === "date" || selectedSortOption === "server") {
            exportButtonsContainer.style.display = "flex"; // Show the export buttons
        } else {
            exportButtonsContainer.style.display = "none"; // Hide export buttons when "Plat name" is selected
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const searchButton = document.getElementById("search-resto");
    const startDateInput = document.getElementById("start-date-resto");
    const endDateInput = document.getElementById("end-date-resto");

    searchButton.addEventListener("click", function (event) {
        // Check if both dates are selected
        if (!startDateInput.value || !endDateInput.value) {
            event.preventDefault(); // Prevent form submission (if inside a form)
            
            // SweetAlert2 alert with auto-close and a warning icon
            Swal.fire({
                icon: 'error', // Changed to 'warning' icon
                title: 'Attention!',
                text: 'Veuillez sélectionner à la fois la date de début et la date de fin.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#f39c12',
                background: '#fff',
                timer: 2000, // Close after 1500 milliseconds (1.5 seconds)
                timerProgressBar: true, // Show progress bar for the timer
                didClose: () => {
                    // Optional: Code that runs after the alert closes
                }
            });
        }
    });
});








document.addEventListener("DOMContentLoaded", function() {
    const exportPdfBtn = document.getElementById("export-pdf-resto");
    const exportExcelBtn = document.getElementById("export-excel-resto");

    // Export to PDF in Landscape Mode
    exportPdfBtn.addEventListener("click", function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: "landscape" }); // Set landscape mode

        doc.text("Resto Data Report", 14, 10);
        doc.autoTable({
            html: "#resto-table",
            startY: 20,
            styles: { fontSize: 10, cellPadding: 3 },
        });

        doc.save("resto_data_report.pdf");
    });

    // Export to Excel
    exportExcelBtn.addEventListener("click", function() {
        const table = document.getElementById("resto-table");
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.table_to_sheet(table);
        
        XLSX.utils.book_append_sheet(wb, ws, "Resto Data");
        XLSX.writeFile(wb, "resto_data_report.xlsx");
    });
});

</script>






















<!-- Add this in your HTML where you want the stock bouteille section to appear -->
<div id="stock-bouteille-section" class="content-section" style="display: none;">
    <h2 style="text-align: left; margin-bottom: 10px;">Bilan General_Livraison et collecte de bouteilles</h2>
    <div id="stock-bouteille-table-container"></div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
    const generalReportBoxes = document.querySelectorAll('.general-report-box');
    const generalReportSection = document.getElementById('general-report-section');
    const stockBouteilleSection = document.getElementById('stock-bouteille-section');
    const tableContainer = document.getElementById('stock-bouteille-table-container');

    // Handle click on Stock Bouteille box
    generalReportBoxes[2].addEventListener('click', function(e) {
        e.preventDefault();
        generalReportSection.style.display = 'none';
        stockBouteilleSection.style.display = 'block';
        fetchStockBouteilleData();
    });

    function fetchStockBouteilleData() {
        fetch('bottles/fetch_data.php')
            .then(response => response.json())
            .then(data => {
                generateTable(data);
                addExportButtons();
            })
            .catch(error => console.error('Error:', error));
    }

    function generateTable(data) {
    const table = document.createElement('table');
    table.id = 'stock-bouteille-table';

    // Create table headers
    const headers = ['Bouteille Reçue', 'Quantité Reçue', 'Bouteille Donnée', 'Quantité Donnée', 'Agent Recepteur', 'Agent Fournisseur', 'Entreprise Reçue', 'Entreprise Fournisseur', 'Date du Rapport'];
    const thead = document.createElement('thead');
    const tr = document.createElement('tr');
    headers.forEach(header => {
        const th = document.createElement('th');
        th.textContent = header;
        tr.appendChild(th);
    });
    thead.appendChild(tr);
    table.appendChild(thead);

    // Create table body
    const tbody = document.createElement('tbody');
    data.forEach(row => {
        const rowElement = document.createElement('tr');
        Object.values(row).forEach(value => {
            const td = document.createElement('td');
            td.textContent = value;
            rowElement.appendChild(td);
        });
        tbody.appendChild(rowElement);
    });
    table.appendChild(tbody);

    tableContainer.innerHTML = '';
    tableContainer.appendChild(table);
}


    function addExportButtons() {
        const exportContainer = document.createElement("div");
        exportContainer.className = 'export-button-container';
        exportContainer.innerHTML = `
            <button id="exportPDF" style="padding: 10px 20px; font-size: 16px; border: none; cursor: pointer; border-radius: 5px; background-color: white; color: black; border: 2px solid #ddd; transition: background-color 0.3s ease;">
                <i class="bx bxs-file-pdf" style="margin-right: 8px;"></i>Exporter vers PDF
            </button>
            <button id="exportExcel" style="padding: 5px 15px; font-size: 16px; border: none; cursor: pointer; border-radius: 5px; background-color: #4CAF50; color: white; transition: background-color 0.3s ease;">
                <i class="bx bxs-file-export" style="margin-right: 8px;"></i>Exporter vers Excel
            </button>
        `;
        tableContainer.insertBefore(exportContainer, tableContainer.firstChild);

        document.getElementById("exportExcel").addEventListener("click", exportToExcel);
        document.getElementById("exportPDF").addEventListener("click", exportToPDF);
    }

    function exportToExcel() {
    Swal.fire({
        title: "Confirmer l'exportation",
        text: "Voulez-vous vraiment exporter ce rapport vers Excel ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, exporter!",
        cancelButtonText: "Annuler"
    }).then((result) => {
        if (result.isConfirmed) {
            // Show "Generation en cours" message
            Swal.fire({
                title: "Génération en cours...",
                text: "Votre rapport est en train d'être exporté.",
                icon: "info",
                showConfirmButton: false,
                allowOutsideClick: false,  // Prevent closing of the alert
                willOpen: () => {
                    Swal.showLoading();  // Show the loading animation
                }
            });

            const table = document.getElementById('stock-bouteille-table');
            if (!table) {
                Swal.fire("Erreur", "Aucune donnée disponible à exporter!", "error");
                return;
            }

            // Excel export logic
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "General Report");
            XLSX.writeFile(wb, "General_Report.xlsx");

            // After 1500ms (1.5 seconds), show success message
            setTimeout(() => {
                Swal.fire({
                    title: "Succès!",
                    text: "Le rapport a été exporté avec succès vers Excel!",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 2000 // Auto-close after 2000ms
                });
            }, 1500); // Delay of 1500ms before showing success message
        }
    });
}



    function exportToPDF() {
    Swal.fire({
        title: "Confirmer l'exportation",
        text: "Voulez-vous vraiment exporter ce rapport en PDF ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, exporter!",
        cancelButtonText: "Annuler"
    }).then((result) => {
        if (result.isConfirmed) {
            // Show a "Generating in process" SweetAlert
            Swal.fire({
                title: "Génération en cours...",
                text: "Veuillez patienter, le PDF est en train d'être généré.",
                icon: "info",
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Show loading spinner
                }
            });

            setTimeout(() => {
                const table = document.getElementById('stock-bouteille-table');
                if (!table) {
                    Swal.fire("Erreur", "Aucune donnée disponible à exporter!", "error");
                    return;
                }

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4'); // 'l' for landscape

                // Get table headers
                const headers = [];
                table.querySelectorAll("thead tr th").forEach(th => {
                    headers.push(th.innerText.trim());
                });

                // Get table data
                const data = [];
                table.querySelectorAll("tbody tr").forEach(tr => {
                    const rowData = [];
                    tr.querySelectorAll("td").forEach(td => {
                        rowData.push(td.innerText.trim() || ""); // Avoid undefined values
                    });
                    data.push(rowData);
                });

                if (data.length === 0) {
                    Swal.fire("Erreur", "Aucune donnée disponible à exporter!", "error");
                    return;
                }

                // Add title
                doc.setFontSize(14);
                doc.text("Bilan General - Stock Bouteilles", 20, 15);

                // Add table in landscape mode
                doc.autoTable({
                    head: [headers],
                    body: data,
                    theme: 'striped',
                    startY: 25,
                    styles: { fontSize: 10, cellPadding: 2 },
                    headStyles: { fillColor: [52, 152, 219], textColor: 255 },
                    alternateRowStyles: { fillColor: [240, 240, 240] },
                    margin: { left: 10, right: 10 }
                });

                // Save PDF
                doc.save("Stock_Bouteilles_Report.pdf");

                // Close the "Generating in process" alert after 2 seconds
                setTimeout(() => {
                    // SweetAlert Success message with automatic close after 2000ms
                    Swal.fire({
                        title: "Succès!",
                        text: "Le rapport a été exporté en PDF avec succès!",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 800 // Auto-close after 2000ms
                    });
                }, 1500); // Delay for success message
            }, 500); // Small delay for stability
        }
    });
}



});

// Function to return to general reports
function showGeneralReports() {
    document.getElementById('general-report-section').style.display = 'block';
    document.getElementById('stock-bouteille-section').style.display = 'none';
}




</script>







<!-- Add this in your HTML where you want the casse section to appear -->
<div id="casse-section" class="content-section custom-complaint-form">
        <h2>Rapport de Casse ou de Perte</h2>
        <form action="complaints/submit_complaint.php" method="POST" id="complaint-form" enctype="multipart/form-data">
            <div class="form-section">
                <div class="left">
                    <label for="manager-name"><i class="bx bx-user-circle"></i> Nom du responsable :</label>
                    <input type="text" id="manager-name" name="manager_name" required>

                    <label for="specific-item"><i class="bx bx-cube"></i> Matériau ou article spécifique :</label>
                    <input type="text" id="specific-item" name="specific_item" required>

                    <label for="impact"><i class="bx bx-flag"></i> Impact :</label>
                    <input type="text" id="impact" name="impact">

                    <label for="complaint-description"><i class="bx bx-message"></i> Description de la plainte :</label>
                    <textarea id="complaint-description" name="complaint_description" rows="4" required></textarea>
                </div>

                <div class="right">
                    <label for="complaint-category"><i class="bx bx-category"></i> Catégorie de plainte :</label>
                    <select id="complaint-category" name="complaint_category" required>
                        <option value="missing_materials">Matériaux manquants</option>
                        <option value="lost_items">Articles perdus</option>
                        <option value="quality_issues">Problèmes de qualité</option>
                        <option value="customer_complaints">Plaintes des clients</option>
                        <option value="autres">Autres</option>
                    </select>
                    <label for="incident-date-time"><i class="bx bx-calendar"></i> Date et heure :</label>
                    <input type="datetime-local" id="incident-date-time" name="incident_date_time" required>

                    <label for="cause"><i class="bx bx-purchase-tag"></i> Cause (si connue) :</label>
                    <input type="text" id="cause" name="cause">
                
                    <label for="incident-location"><i class="bx bx-location-plus"></i> Lieu de l'incident :</label>
                    <input type="text" id="incident-location" name="incident_location" required>


                    <label for="employee-comments"><i class="bx bx-comment"></i> Commentaires de l'employé :</label>
                    <textarea id="employee-comments" name="employee_comments" rows="4"></textarea>
                </div>
            </div>
            <div class="button-container">
                <button type="submit"><i class="bx bx-send"></i> Soumettre le rapport</button>
            </div>
        </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const generalReportBoxes = document.querySelectorAll('.general-report-box');
    const generalReportSection = document.getElementById('general-report-section');
    const casseSection = document.getElementById('casse-section');

    // Handle click on Casse ou Perte box
    generalReportBoxes[3].addEventListener('click', function(e) {
        e.preventDefault();
        generalReportSection.style.display = 'none';
        casseSection.style.display = 'block';
    });
});

// Function to return to general reports
function showGeneralReports() {
    document.getElementById('general-report-section').style.display = 'block';
    document.getElementById('casse-section').style.display = 'none';
}


</script>










        <!-- Bottle Management Section -->
<div id="graph-section" class="section">
    <h2>Gestion des Bouteilles</h2>
    <div class="bottle-management-container">
        <!-- Received Bottles Form -->
        <div id="received-bottles-section" class="bottle-form">
            <!-- Incoming Bottle Icon -->
            <div class="icon-container">
                <i class='bx bx-down-arrow-circle icon-green'></i>
            </div>
            <h3>Bouteilles Reçues</h3>
            <form id="received-bottles-form">
                <div class="form-group">
                    <label for="received-bottle-name">
                        <i class='bx bxs-wine'></i> Type de la Bouteille
                    </label>
                    <input type="text" id="received-bottle-name" required oninput="syncBottleName()" style="text-transform: uppercase;" />
                </div>
                <div class="form-group">
                    <label for="received-quantity">
                        <i class='bx bx-list-plus'></i> Quantité Reçue
                    </label>
                    <input type="number" id="received-quantity" required />
                </div>
                <div class="form-group">
                    <label for="receiver-name">
                        <i class='bx bxs-user'></i> Agent Récepteur
                    </label>
                    <input type="text" id="receiver-name" required />
                </div>
                <div class="form-group">
                    <label for="received-company">
                        <i class='bx bxs-buildings'></i> Nom de la Compagnie Réceptrice
                    </label>
                    <input type="text" id="received-company" value="Bar-Resto" required />
                </div>
            </form>
        </div>
        <!-- Middle Transparent Vertical Line -->
        <div class="vertical-line"></div>
        
        <!-- Given Bottles Form -->
        <div id="given-bottles-section" class="bottle-form">
            <!-- Outgoing Bottle Icon -->
            <div class="icon-container">
                <i class='bx bx-up-arrow-circle icon-red'></i>
            </div>
            <h3>Bouteilles Données</h3>
            <form id="given-bottles-form">
                <div class="form-group">
                    <label for="given-bottle-name">
                        <i class='bx bxs-wine'></i> Type de la Bouteille
                    </label>
                    <input type="text" id="given-bottle-name" readonly />
                </div>
                <div class="form-group">
                    <label for="given-quantity">
                        <i class='bx bx-list-minus'></i> Quantité Donnée
                    </label>
                    <input type="number" id="given-quantity" required />
                </div>
                <div class="form-group">
                    <label for="received-person">
                        <i class='bx bxs-user'></i> Agent Fournisseur
                    </label>
                    <input type="text" id="received-person" required />
                </div>
                <div class="form-group">
                    <label for="provider-company">
                        <i class='bx bxs-buildings'></i> Nom de la Compagnie Fournisseur
                    </label>
                    <input type="text" id="provider-company" required />
                </div>
            </form>
        </div>
    </div>
    

    <button onclick="generateBottleReport()" class="report-btn">
        <i class='bx bx-transfer-alt'></i> Sauvegarder la différence
    </button>

    <!-- New Button: Gérer le Stock des Bouteilles -->
    <button onclick="openBottlesModal()" class="manage-stock-btn">
        <i class='bx bx-cog'></i> Gérer le Stock des Bouteilles
    </button>

    <!-- Modal Structure (Hidden by Default) -->
    <div id="bottlesModal" class="bottles-modal-overlay">
        <div class="bottles-modal-content">
            <span class="bottles-modal-close" onclick="closeBottlesModal()">&times;</span>
            <h2 class="bottles-modal-title">Gérer   le Stock   des  Bouteilles</h2>
            <p class="bottles-modal-text"></p>
            
            <!-- Stock Management Buttons -->
            <div class="stock-options">
                <button class="stock-btn">
                    <i class='bx bx-plus-circle'></i> Ajouter des Bouteilles
                </button>
                <button class="stock-btn">
                    <i class='bx bx-list-check'></i> Stock Disponible
                </button>
                <button class="stock-btn">
                    <i class="bx bx-bar-chart"></i> Bilan des Dettes
                </button>
                <button class="stock-btn">
                    <i class='bx bx-history'></i> Consulter l'Historique
                </button>
            </div>
            <div class="stock-content">
                <p>📦 Ici vous pouvez gérer le stock des bouteilles....</p>
            </div>
        </div>
    </div>

    <!-- Calculation Results -->
    <div id="calculation-results"></div>
</div>






<div id="gestion-conges-container" class="content-container" style="display: none;">
    <h2>Demande de Congé</h2>

    <!-- Form to Request Leave -->
    <form id="leaveRequestForm">
        <label for="employeeName">
            <i class="bx bx-user"></i> Employé:
        </label>
        <input type="text" id="employeeName" required>

        <label for="leaveType">
            <i class="bx bx-bookmark-alt"></i> Type de congé:
        </label>
        <select id="leaveType" required>
            <option value="congé autres">Autres</option>
            <option value="congé payé">Congé payé</option>
            <option value="congé maladie">Congé maladie</option>
            <option value="congé sans solde">Congé sans solde</option>
            <option value="congé maternité">Congé maternité/paternité</option>
        </select>
        
        <label for="startDate">
            <i class="bx bx-calendar"></i> Date de début:
        </label>
        <input type="date" id="startDate" required>

        <label for="endDate">
            <i class="bx bx-calendar-check"></i> Date de fin:
        </label>
        <input type="date" id="endDate" required>

        <label for="leaveReason">
            <i class="bx bx-message-square-edit"></i> Raison:
        </label>
        
        <textarea id="leaveReason" required></textarea> <!-- NEW FIELD -->
        
        <div class="total-days-container">
            <p>Total: <span id="totalDays">0</span> jours</p>
            <!-- Submit Button with BX Icon -->
            <button type="submit">
                <i class="bx bx-send"></i> <!-- bx-send icon used for submission -->
            </button>
        </div>
    </form>



    <!-- Leave Requests List -->
    <h3 style="font-size: 24px; color: #212529;">
        <i class='bx bxs-down-arrow' style="font-size: 24px; color:rgba(0, 0, 0, 0.71); margin-top: 60px;"></i>
    </h3>

    <table>
        <thead>
            <tr>
                <th>Employé</th>
                <th>Type</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Total Jours</th>
                <th>Raison</th> <!-- NEW COLUMN -->
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="leaveRequestsTable">
            <!-- Requests will be loaded here -->
        </tbody>
    </table>
</div>








</main>

</section>


	<!-- CONTENT -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <!-- Include SheetJS library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

    <!-- Include jsPDF library for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


	<script src="script_1.js"></script>
    <script src="script.js"></script>
    <script src="script2.js"></script>
    <script src="script3.js"></script>
    <script>
        
// Function to update the price and total for each row, and update the grand total
function updatePriceAndTotal(row) {
    const drinkSelect = row.querySelector(".drink-select");
    const quantityInput = row.querySelector(".quantity");
    const priceCell = row.querySelector(".unit-price");
    const totalCell = row.querySelector(".total-price");

    // Get the selected drink's price
    const selectedOption = drinkSelect.options[drinkSelect.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute("data-price"));
    const quantity = parseInt(quantityInput.value);

    // Update the price and total cells for the current row
    priceCell.textContent = price ? price + " FBU" : "0 FBU";
    totalCell.textContent = (price * quantity) + " FBU"; // Total for this row

    // Update the grand total after modifying a row
    updateGrandTotal();
}

// Function to update the total price of all drinks selected
function updateGrandTotal() {
    let grandTotal = 0;

    // Get all the total-price cells
    const totalCells = document.querySelectorAll(".total-price");

    totalCells.forEach(cell => {
        // Remove the " FBU" and parse the value to float
        const total = parseFloat(cell.textContent.replace(" FBU", ""));
        if (!isNaN(total)) {
            grandTotal += total; // Sum up the total prices
        }
    });

// Update the grand total display
document.getElementById("drink-total-price").textContent = grandTotal + " FBU";
}

// Function to add a new drink row
function addDrinkRow() {
    const tableBody = document.querySelector("#drink-table tbody");
    const newRow = document.createElement("tr");

    // Create the row content
    newRow.innerHTML = `
        <td>
            <select class="drink-select" onchange="updatePriceAndTotal(this.closest('tr'))">
                <option value="" disabled selected>-- Sélectionner --</option>
                <?php foreach ($drinks as $drink): ?>
                    <option value="<?= $drink['nom_boisson'] ?>" data-price="<?= $drink['prix_achat'] ?>"><?= htmlspecialchars($drink['nom_boisson']) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><input type="number" class="quantity" min="1" value="1" oninput="updatePriceAndTotal(this.closest('tr'))"></td>
        <td class="unit-price">0 FBU</td>
        <td class="total-price">0 FBU</td>
        <td><button onclick="removeRow(this)" style="background: none; border: none; cursor: pointer;">
        <i class="bx bxs-trash" style="font-size: 20px; color: red;"></i>
        </button></td>

    `;

    tableBody.appendChild(newRow); // Append the new row to the table body
    updateGrandTotal(); // Update the grand total after adding a row
}

// Function to remove a row and recalculate the total
function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
    updateGrandTotal(); // Recalculate the grand total after row removal
}
    </script>


<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>





</body>
</html>