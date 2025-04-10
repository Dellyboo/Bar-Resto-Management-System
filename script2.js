function openKitchenOrderModal() {
    document.getElementById("kitchen-order-modal").style.display = "block";
    loadKitchenItems(); // Load kitchen items when modal is opened
}

function closeKitchenOrderModal() {
    document.getElementById("kitchen-order-modal").style.display = "none";
}

let kitchenOrders = [];
let currentKitchenStep = 1;

// Function to fetch kitchen plats from the database
async function fetchKitchenPlats() {
    try {
        let response = await fetch("G_Resto/fetch_kitchen_plats.php"); // Fetch plats from the PHP script
        let plats = await response.json();
        return plats;
    } catch (error) {
        console.error("Error fetching plats:", error);
        return [];
    }
}

// Function to add a new row for kitchen orders
async function addKitchenRow() {
    const plats = await fetchKitchenPlats();
    const tableBody = document.querySelector("#kitchen-table tbody"); // Select table body
    const row = document.createElement("tr"); // Create new row

    // Create row content with fetched plats
    row.innerHTML = `
        <td>
            <select class="kitchen-item-name" onchange="updateKitchenPriceAndTotal(this.closest('tr'))" required>
                <option value="" disabled selected>-- Sélectionner --</option>
                ${plats.map(plat => `<option value="${plat.nom_ingredient}" data-price="${plat.prix_achat}">${plat.nom_ingredient}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" class="kitchen-item-quantity" value="1" min="1" oninput="updateKitchenPriceAndTotal(this.closest('tr'))" required></td>
        <td class="kitchen-item-price">0 FBU</td>
        <td class="kitchen-item-total">0 FBU</td>
        <td>
            <button onclick="removeKitchenRow(this)" style="background: none; border: none; cursor: pointer;">
                <i class="bx bxs-trash" style="font-size: 20px; color: red;"></i>
            </button>
        </td>
    `;

    tableBody.appendChild(row); // Append row to table body
}

// Function to update price and total for a row
function updateKitchenPriceAndTotal(row) {
    const platSelect = row.querySelector(".kitchen-item-name");
    const quantityInput = row.querySelector(".kitchen-item-quantity");
    const priceCell = row.querySelector(".kitchen-item-price");
    const totalCell = row.querySelector(".kitchen-item-total");

    // Get selected plat's price
    const selectedOption = platSelect.options[platSelect.selectedIndex];
    const price = parseFloat(selectedOption.getAttribute("data-price")) || 0;
    const quantity = parseInt(quantityInput.value) || 1;

    // Update price and total
    priceCell.textContent = price + " FBU";
    totalCell.textContent = (price * quantity) + " FBU";

    updateKitchenTotalPrice(); // Update grand total
}

// Function to update the total price of all kitchen items
function updateKitchenTotalPrice() {
    let total = 0;
    document.querySelectorAll(".kitchen-item-total").forEach(cell => {
        total += parseFloat(cell.textContent.replace(" FBU", "")) || 0;
    });
    document.querySelector("#kitchen-total-price").textContent = total + " FBU";
}

// Function to remove a row and update the total price
function removeKitchenRow(button) {
    button.closest("tr").remove();
    updateKitchenTotalPrice();
}

function nextKitchenStep() {
    document.querySelector("#kitchen-step-1").style.display = "none";
    document.querySelector("#kitchen-step-2").style.display = "block";
    document.querySelector("#kitchen-step-circle").innerText = "";
    generateKitchenOrderSummary();
}

function prevKitchenStep() {
    document.querySelector("#kitchen-step-1").style.display = "block";
    document.querySelector("#kitchen-step-2").style.display = "none";
    document.querySelector("#kitchen-step-circle").innerText = "1";
}

// Function to generate the Kitchen Order Summary
function generateKitchenOrderSummary() {
    const summaryDiv = document.getElementById("kitchen-order-summary");
    summaryDiv.innerHTML = "";  // Clear existing content

    // Business Information
    const businessInfo = `
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; margin-top: -60px;">
            <img src="logo/logo.png" alt="Logo" id="invoice-logo" style="width: 220px; height: auto;">
            <div style="text-align: right;">
                <p>Adresse: Gasenyi, Q. Taba</p>
                <p>Téléphone: +25779232041</p>
            </div>
        </div>
        <hr style="border-top: 2px solid #000;">
    `;
    summaryDiv.innerHTML += businessInfo;

    // Retrieve table number and server number from Step 1
    const tableNumber = document.getElementById("kitchen-table-number").value || "N/A";
    const serverNumber = document.getElementById("kitchen-server-number").value || "N/A";

    // Generate unique invoice number
    let invoiceNumber = `CUIS#${Math.floor(Math.random() * 1000000)}`;
    while (checkInvoiceExists(invoiceNumber)) {
        invoiceNumber = `CUIS#${Math.floor(Math.random() * 1000000)}`;
    }

    // Date and Time
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();

    // Invoice Information
    const invoiceInfo = `
        <div style="padding: 10px 0; font-size: 14px;">
            <p><strong>Date:</strong> ${date} <strong>Heure:</strong> ${time}</p>
            <p><strong>Numéro de Serveur:</strong> ${serverNumber}</p>
            <p><strong>Numéro de Table:</strong> ${tableNumber}</p>
            <p><strong>Numéro de Facture:</strong> ${invoiceNumber}</p>
        </div>
        <hr style="border-top: 2px dashed #ccc;">
    `;
    summaryDiv.innerHTML += invoiceInfo;

    // Order Table (Kitchen Orders)
    const tableRows = document.querySelectorAll("#kitchen-table tbody tr");

    if (tableRows.length > 0) {
        let orderTable = `
            <p class="ordered-items-heading" style="margin-top: 10px; font-weight: bold;">Articles commandés:</p>
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                    <tr style="background-color: #f4f4f4;">
                        <th style="border: 1px solid #000; padding: 8px;">Plat</th>
                        <th style="border: 1px solid #000; padding: 8px;">Quantité</th>
                        <th style="border: 1px solid #000; padding: 8px;">Prix Unitaire</th>
                        <th style="border: 1px solid #000; padding: 8px;">Total</th>
                    </tr>
                </thead>
                <tbody>
        `;

        tableRows.forEach(row => {
            const dishElement = row.querySelector(".kitchen-item-name");
            const quantityElement = row.querySelector(".kitchen-item-quantity");
            const unitPriceElement = row.querySelector(".kitchen-item-price");
            const totalElement = row.querySelector(".kitchen-item-total");

            // Ensure elements exist before retrieving values
            if (dishElement && quantityElement && unitPriceElement && totalElement) {
                const dish = dishElement.options[dishElement.selectedIndex]?.text || "N/A";
                const quantity = quantityElement.value;
                const unitPrice = unitPriceElement.textContent;
                const total = totalElement.textContent;

                orderTable += `
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px;">${dish}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: center;">${quantity}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right;">${unitPrice}</td>
                        <td style="border: 1px solid #000; padding: 8px; text-align: right;">${total}</td>
                    </tr>
                `;
            }
        });

        orderTable += `
                </tbody>
            </table>
            <hr style="border-top: 2px dashed #ccc; margin-top:25px;">
        `;
        summaryDiv.innerHTML += orderTable;
    } else {
        summaryDiv.innerHTML += "<p>Aucun plat sélectionné.</p>";
    }

    // Total Price
    const totalPriceElement = document.getElementById("kitchen-total-price");
    const totalPrice = totalPriceElement ? totalPriceElement.textContent : "0";
    summaryDiv.innerHTML += `<p style="font-size: 16px; font-weight: bold;">Total: ${totalPrice}</p>`;

    // Payment Status and Mode
    const paymentStatus = `
        <div class="payment-container">
            <div class="payment-status">
                <p><strong>Statut de Paiement:</strong></p>
                <select id="payment-status" onchange="updatePaymentStatusColor()">
                    <option value="pending" style="color: black;" selected>en attente</option>
                    <option value="paid">Payé</option>
                    <option value="unpaid">Non Payé</option>
                </select>
            </div>
            <div class="payment-mode">
                <p><strong>Mode de Paiement:</strong></p>
                <select id="payment-mode">
                    <option value="pending">-</option>
                    <option value="cash">Cash</option>
                    <option value="lumicash">Lumicash</option>
                    <option value="card">Carte</option>
                </select>
            </div>
        </div>
    `;
    summaryDiv.innerHTML += paymentStatus;
}

// Mock function to check if invoice number exists (replace with actual logic for real database)
function checkInvoiceExists(invoiceNumber) {
    return false;
}

// Function to update the color of the payment status
function updatePaymentStatusColor() {
    const paymentStatus = document.getElementById("payment-status");
    const status = paymentStatus.value;

    if (status === "paid") {
        paymentStatus.style.color = "green";
    } else if (status === "unpaid") {
        paymentStatus.style.color = "red";
    } else {
        paymentStatus.style.color = "black";
    }
}













async function submitKitchenOrder() {
    const serverNumber = document.getElementById("kitchen-server-number").value || "N/A";
    const tableNumber = document.getElementById("kitchen-table-number").value || "N/A";
    const paymentStatus = document.getElementById("payment-status").value || "pending";
    const paymentMode = document.getElementById("payment-mode").value || "pending";
    const totalPrice = document.querySelector("#kitchen-total-price").textContent.replace(" FBU", "").trim();
    
    // Prepare kitchen items from the order table
    const kitchenItems = [];
    const tableRows = document.querySelectorAll("#kitchen-table tbody tr");

    tableRows.forEach(row => {
        const platSelect = row.querySelector(".kitchen-item-name");
        const quantityInput = row.querySelector(".kitchen-item-quantity");
        const unitPriceElement = row.querySelector(".kitchen-item-price");
        const totalCell = row.querySelector(".kitchen-item-total");

        // Get selected plat details
        const dish = platSelect.options[platSelect.selectedIndex]?.text || "N/A";
        const quantity = quantityInput.value;
        const unitPrice = unitPriceElement.textContent;
        const total = totalCell.textContent.replace(" FBU", "").trim();

        kitchenItems.push({ dish, quantity, unitPrice, total });
    });

    // Generate unique invoice number
    let invoiceNumber = `CUIS#${Math.floor(Math.random() * 1000000)}`;
    while (await checkInvoiceExists(invoiceNumber)) {
        invoiceNumber = `CUIS#${Math.floor(Math.random() * 1000000)}`;
    }

    // Ask for WhatsApp number using SweetAlert
    const { value: whatsappNumber } = await Swal.fire({
        title: "Recevez votre facture sur WhatsApp",
        text: "Veuillez entrer votre numéro WhatsApp pour recevoir la facture détaillée (optionnel).",
        input: "text",
        inputPlaceholder: "+257XXXXXXXX",
        inputValue: "+257",  // Default value for +257
        showCancelButton: true,
        confirmButtonText: "Envoyer la facture",
        cancelButtonText: "Annuler",
        icon: "info"
    });

    let cleanedNumber = whatsappNumber ? whatsappNumber.trim() : "";
    const validPhonePattern = /^\+(\d{1,4})\d{7,15}$/;
    let sendWhatsAppMessage = validPhonePattern.test(cleanedNumber);

    // Prepare the bill message
    let billMessage = `*Voici votre facture:*\nNuméro de Facture: ${invoiceNumber}\nTable: ${tableNumber}\nServeur: ${serverNumber}\n------------------------------\n*Détails de la commande:*\n------------------------------\n`;
    kitchenItems.forEach(item => {
        billMessage += `${item.quantity}x ${item.dish} || ${item.unitPrice} || (Total ${item.total})\n`;
    });
    billMessage += `-------------------------------\n*Total général: ${totalPrice}*\nMode de Paiement: *${paymentMode}*\nStatut de Paiement: *${paymentStatus}*\n\n\`\`\`Merci pour votre commande ! Nous vous souhaitons une agréable journée et espérons vous revoir bientôt!\`\`\``;

    // Prepare the data for the backend
    const data = {
        kitchenItems,
        serverNumber,
        tableNumber,
        invoiceNumber,
        totalPrice,
        paymentStatus,
        paymentMode,
        whatsappNumber: sendWhatsAppMessage ? cleanedNumber : null  // Optional field
    };

    // Send the data to the PHP script via fetch
    try {
        const response = await fetch('G_Resto/insert_kitchen_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        if (result.success) {
            Swal.fire({
                title: "Succès",
                text: "Commande confirmée avec succès, merci !",
                icon: "success",
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // Ensure WhatsApp message opens after the alert closes, if provided
                if (sendWhatsAppMessage) {
                    let encodedMessage = encodeURIComponent(billMessage);
                    let whatsappUrl = `https://wa.me/${cleanedNumber}?text=${encodedMessage}`;
                    window.open(whatsappUrl, '_blank');  // Open in a new tab
                }

                closeKitchenOrderModal(); // Close the modal after submitting
            });
        } else {
            Swal.fire("Erreur", "Failed to submit order. Please try again.", "error");
        }
    } catch (error) {
        console.error("Error submitting order:", error);
        Swal.fire("Erreur", "An error occurred while submitting the order.", "error");
    }
}










// Function to animate counting from 0 to the final number with slower speed
function animateCounter(elementId, finalNumber) {
    let currentNumber = 0;  // Starting number
    const step = Math.ceil(finalNumber / 200);  // Smaller step for even slower animation
    const intervalTime = 15; // Increase the time to further slow down the counting

    function updateCounter() {
        currentNumber += step;
        if (currentNumber >= finalNumber) {
            currentNumber = finalNumber;  // Ensure it stops at the final value
        }

        document.getElementById(elementId).textContent = currentNumber;

        if (currentNumber < finalNumber) {
            setTimeout(updateCounter, intervalTime);  // Slower animation using setTimeout
        }
    }

    // Start the animation
    updateCounter();
}

function fetchCounterData() {
    fetch('fetch-counter-data.php')  // Make a request to the PHP file
        .then(response => response.json())  // Parse the JSON response
        .then(data => {
            // Ensure the values are numbers before passing them to animation
            const totalEmployees = parseInt(data.totalEmployees) || 0;
            const totalBottles = parseInt(data.totalBottles) || 0;
            const totalFood = parseInt(data.totalFood) || 0;
            const totalComplaints = parseInt(data.totalComplaints) || 0;

            // Animate the counter values for each section
            animateCounter('employee-count', totalEmployees);
            animateCounter('bottles-count', totalBottles);
            animateCounter('food-count', totalFood);
            animateCounter('complaints-count', totalComplaints);
        })
        .catch(error => {
            console.error('Error fetching counter data:', error);
        });
}

// Call the function to update the counters when the page loads
window.onload = function() {
    fetchCounterData();
};















document.addEventListener("DOMContentLoaded", function () {
    const notificationIcon = document.getElementById("notification-icon");
    const notificationModal = document.getElementById("notification-modal");
    const notificationList = document.getElementById("notification-list");

    // Function to fetch complaints from the database
    function fetchNotifications() {
        fetch("notifications/fetch_notifications.php")
            .then(response => response.json())
            .then(data => {
                notificationList.innerHTML = ""; // Clear existing notifications

                if (data.length > 0) {
                    data.forEach(notification => {
                        let listItem = document.createElement("li");
                        listItem.innerHTML = `<i class='bx bxs-report'></i> ${notification.message}`;
                        notificationList.appendChild(listItem);
                    });
                } else {
                    notificationList.innerHTML = "<li>Aucune nouvelle notification</li>";
                }
            })
            .catch(error => console.error("Error fetching notifications:", error));
    }

    // Toggle modal and fetch notifications when clicking the icon
    notificationIcon.addEventListener("click", function (event) {
        event.preventDefault();
        notificationModal.classList.toggle("active");

        if (notificationModal.classList.contains("active")) {
            fetchNotifications(); // Fetch new notifications when opening
        }
    });

    // Close modal when clicking outside
    document.addEventListener("click", function (event) {
        if (!notificationIcon.contains(event.target) && !notificationModal.contains(event.target)) {
            notificationModal.classList.remove("active");
        }
    });

    // Fetch notifications every 10 seconds
    setInterval(fetchNotifications, 10000);

    // Initial fetch on page load
    fetchNotifications();
});








function updateTime() {
    const now = new Date();
    const options = { day: '2-digit', month: 'short', year: 'numeric' };
    const date = now.toLocaleDateString('en-GB', options); // Format: 04 Mar, 2025
    const time = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit', 
        hour12: true 
    }); // Format: 04:26:15 PM
    document.getElementById('currentTime').textContent = `${date} | ${time}`;
}

updateTime(); // Update immediately
setInterval(updateTime, 1000); // Update every second












// Get the modal and the profile link
const profileLink = document.getElementById('profile-link');
const profileModal = document.getElementById('profile-modal');

// Toggle modal visibility on profile link click
profileLink.addEventListener('click', function (e) {
    e.preventDefault(); // Prevent the default action (e.g., navigation)
    profileModal.classList.toggle('active'); // Toggle the 'active' class to show/hide the modal
});

// Close the modal when clicking outside of the modal content
window.addEventListener('click', function (e) {
    if (!profileModal.contains(e.target) && !profileLink.contains(e.target)) {
        profileModal.classList.remove('active');
    }
});

















// Handle Sidebar Click Events
document.querySelectorAll('#sidebar .side-menu a').forEach(link => {
    link.addEventListener('click', function(event) {
        // Prevent default anchor behavior
        event.preventDefault();

        // Get the target section from data-target attribute
        const targetSection = this.getAttribute('data-target');

        // Hide all sections
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = 'none';
        });

        // Show the target section
        const targetElement = document.getElementById(targetSection);
        if (targetElement) {
            targetElement.style.display = 'block';
        }

        // Update the active link in the sidebar
        document.querySelectorAll('#sidebar .side-menu li').forEach(li => {
            li.classList.remove('active');
        });
        this.parentElement.classList.add('active');
    });
});

// Initially, show the Dashboard section
document.getElementById("dashboard-section").style.display = "block"; 












