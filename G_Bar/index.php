<?php
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


	<!-- My CSS -->
	<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_1.css">

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
    </ul>

    <ul class="side-menu top">
        <li>
            <a href="#" data-target="rapports-section">
                <i class='bx bxs-report'></i>
                <span class="text">Rapports</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li>
            <a href="#" class="logout" id="logout-link">
                <i class='bx bxs-log-out'></i> <!-- Logout icon -->
                <span class="text">Logout</span>
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
                
                <input type="checkbox" id="switch-mode" hidden>
                <label for="switch-mode" class="switch-mode"></label>
                <a href="#" class="notification">
                    <i class='bx bxs-bell'></i>
                    <span class="num">8</span>
                </a>
                
                <a href="#" class="profile" id="profile-link">
                    <img src="img/people.png" alt="Profile Picture">
                </a>
                
                <!-- Profile Modal -->
                <div id="profile-modal" class="profile-modal">
                    <div class="profile-modal-content">
                        <span class="close-profile-modal">&times;</span>
                        <div class="profile-details">
                            <img id="profile-pic" src="users/Profiles/default.png" alt="Profile Picture">
                            <h3 id="profile-username"><i class='bx bxs-user'></i> Username</h3>
                            <p id="profile-email"><i class='bx bxs-envelope'></i> Email</p>
                            <span id="profile-role"><i class='bx bxs-briefcase'></i> Role</span>
                        </div>
                        <button class="logout-btn">
                            <i class='bx bxs-log-out'></i> Logout
                        </button>
                    </div>
                </div>
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
            <div class="dashboard-box" id="box-rapports" onclick="showSection('rapports-section')">
                <i class='bx bxs-report'></i>
                <span>Rapports</span>
            </div>
        </div>
    </section>


    <!-- Gestion Commandes Section -->
    <section id="gestion-commandes-section" class="content-section">
        <h1>Gestion Commandes</h1>
        <p></p>
        <div class="gestion-commandes-grid">
            <!-- Boisson Box -->
            <div class="gestion-commandes-box" id="box-boisson" onclick="toggleBox('boisson')">
                <i class="bx bxs-wine"></i> <!-- Wine/Bar icon -->
                <span>Boisson</span>
            </div>

            <!-- Kitchen Box -->
            <div class="gestion-commandes-box" id="box-kitchen" onclick="toggleBox('kitchen')">
                <i class="bx bxs-store-alt"></i> <!-- Restaurant icon -->
                <span>Cuisines</span>
            </div>
        </div>
        
        <!-- This is the container where available drinks and the order form will appear -->
        <div id="boisson-details-container" style="display: none;">
            <!-- Button to show the order section (placed below the grid) -->
            <button class="order-btn" onclick="openOrderModal()">
                <i class="bx bxs-cart-add"></i> Passez une commande boisson
            </button>
             
            <table id="available-drinks-table">
    <thead>
        <tr>
            <th>Nom Boisson</th>
            <th>Image</th>
            <th>Quantité en Stock</th>
            <th>Prix d'Achat</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Database connection settings
        $host = 'localhost'; 
        $dbname = 'g_bar'; 
        $username = 'root'; 
        $password = ''; 

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            // Query to fetch data including picture column
            $query = "SELECT nom_boisson, quantite_stock, prix_achat, picture FROM gestion_stock_boisson";
            $stmt = $conn->prepare($query);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Change color based on quantite_stock value
                    $quantiteColor = $row['quantite_stock'] > 100 ? 'green' : 'red';

                    // Set image path (assuming images are stored in 'G_boisson/uploads/')
                    $imagePath = !empty($row['picture']) ? "G_boisson/uploads/" . $row['picture'] : "G_boisson/uploads/default.jpg";

                    echo '<tr>';
                    echo '<td style="text-decoration: none;">' . $row['nom_boisson'] . '</td>';
                    echo '<td><img src="' . $imagePath . '" alt="Boisson Image" style="width: 50px; height: 50px; border-radius: 5px;"></td>';
                    echo '<td style="color:' . $quantiteColor . ';">' . $row['quantite_stock'] . '</td>';
                    echo '<td>' . $row['prix_achat'] . ' FBU</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No drinks available.</td></tr>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        $conn = null;
        ?>
    </tbody>
</table>

            
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
                            next <i class="bx bx-chevron-right"></i>
                        </button>
                    </div>
                    
                    <!-- Step 2: Confirm Order -->
                    <div id="drink-step-2" style="display: none;">
                        <h2>Vérification de la commande</h2>
                        <div id="drink-order-summary"></div>
                        
                        <!-- Retour Button with bx Icon -->
                        <button onclick="prevStep()">
                            <i class="bx bx-chevron-left"></i> Retour
                        </button>

                        <!-- Confirmer la commande Button with bx Icon -->
                        <button onclick="submitOrder()">
                            <i class="bx bxs-badge-check"></i> Confirm order
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
            
            <table id="available-kitchen-table">
                <thead>
                    <tr>
                        <th>Nom Plat</th>
                        <th>Quantité en Stock</th>
                        <th>Prix d'Achat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection settings for Kitchen items
                    $host = 'localhost'; 
                    $dbname = 'g_bar'; 
                    $username = 'root'; 
                    $password = ''; 

                    try {
                         $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
                    // Query to fetch data from the kitchen menu database
                    $query = "SELECT nom_plat, quantite_stock, prix_achat FROM gestion_stock_kitchen";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Change color based on quantite_stock value
                            $quantiteColor = $row['quantite_stock'] > 100 ? 'green' : 'red';
                            echo '<tr>';
                            echo '<td style="text-decoration: none;">' . $row['nom_plat'] . '</td>';
                            echo '<td style="color:' . $quantiteColor . ';">' . $row['quantite_stock'] . '</td>';
                            echo '<td>' . $row['prix_achat'] . ' FBU</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3">No kitchen items available.</td></tr>';
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                $conn = null;
                ?>
                </tbody>
            </table>

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
            <h2>Vérification de la commande cuisine</h2>
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
        <p>Manage your stock here.</p>
    </section>

    <!-- Facturation Section -->
    <section id="facturation-section" class="content-section">
        <h1>Facturation</h1>
        <p>Manage billing here.</p>
    </section>

    <!-- Gestion des Employés Section -->
    <section id="gestion-employes-section" class="content-section">
        <h1>Gestion des Employés</h1>
        <p>Manage your employees here.</p>
    </section>

    <!-- Gestion des Employés Section -->
    <section id="gestion-equipement-section" class="content-section">
        <h1>Gestion des Equipements</h1>
        <p>Manage your equipments here.</p>
    </section>

    <!-- Rapports Section -->
    <section id="rapports-section" class="content-section">
        <h1>Rapports</h1>
        <p>View reports here.</p>
    </section>

</main>
</section>
	<!-- CONTENT -->
	
	<script src="script.js"></script>
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
</body>
</html>