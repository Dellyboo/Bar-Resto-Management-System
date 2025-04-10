let reports = [];

// Ensure #calculation-results is hidden by default
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("calculation-results").style.display = "none";
});

function syncBottleName() {
    document.getElementById("given-bottle-name").value = document.getElementById("received-bottle-name").value;
}

function generateBottleReport() {
    const bottleName = document.getElementById("received-bottle-name").value.trim().toUpperCase();
    const receivedQuantity = parseInt(document.getElementById("received-quantity").value.trim()) || 0;
    const givenQuantity = parseInt(document.getElementById("given-quantity").value.trim()) || 0;
    const receiver = document.getElementById("receiver-name").value.trim();
    const provider = document.getElementById("received-person").value.trim().toUpperCase();
    const receivedCompany = document.getElementById("received-company").value.trim();
    const providerCompany = document.getElementById("provider-company").value.trim().toUpperCase();

    if (!bottleName || !receiver || !provider || !receivedCompany || !providerCompany) {
        alert("Veuillez remplir tous les champs.");
        return;
    }

    let difference = givenQuantity - receivedQuantity;
    let message = "";

    if (difference > 0) {
        message = `<i class='bx bx-caret-down' style='color: green; font-size: 21px'></i> ${provider} de (${providerCompany}) nous doit ${difference} bouteilles de ${bottleName}.`;
    } else if (difference < 0) {
        message = `<i class='bx bx-caret-up' style='color: red; font-size: 21px; vertical-align: middle;'></i> Nous devons à ${provider} de (${providerCompany}) ${Math.abs(difference)} bouteilles de ${bottleName}.`;
    } else {
        message = `<i class='bx bx-sync' style="color: grey;"></i> Aucune différence pour les bouteilles ${bottleName}.`;
    }

    reports.push(message);
    displayReports();

    // Confirmation prompt before sending data to the database
    const confirmSubmission = confirm("Êtes-vous sûr de vouloir soumettre ce rapport ?");
    if (confirmSubmission) {
        sendDataToDatabase({
            received_bottle_name: bottleName,
            received_quantity: receivedQuantity,
            given_bottle_name: bottleName, // Assuming same bottle name
            given_quantity: givenQuantity,
            receiver_name: receiver,
            provider_name: provider,
            received_company: receivedCompany,
            provider_company: providerCompany
        });

        // Reset the forms after submission
        document.getElementById("received-bottles-form").reset();
        document.getElementById("given-bottles-form").reset();
    } else {
        console.log("Report submission cancelled. Data not sent.");
    }
}

function displayReports() {
    let resultsDiv = document.getElementById("calculation-results");

    // Show the results div
    resultsDiv.style.display = "block";

    // Insert dynamic content with styling
    resultsDiv.innerHTML = `
        <h3 style="margin-top: -20px; margin-left: 20px; color: #474747">Bouteilles Reçues/Données</h3>
        ${reports.slice(-5).map(r => `<p>${r}</p>`).join('')}
    `;
}

function sendDataToDatabase(data) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "save_bottle_report.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    let params = new URLSearchParams(data).toString();

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log("Data saved successfully");
        } else {
            console.error("Failed to save data", xhr.status, xhr.statusText);
        }
    };

    xhr.send(params);
}














function manageBottleStock() {
    const section = document.getElementById("bottle-stock-section");

    // Toggle the visibility of the section
    if (section.style.display === "none" || section.style.display === "") {
        section.style.display = "block"; // Show the section
    } else {
        section.style.display = "none"; // Hide the section
    }
}




function openBottlesModal() {
    document.getElementById("bottlesModal").style.display = "flex"; // Show modal
}

function closeBottlesModal() {
    document.getElementById("bottlesModal").style.display = "none"; // Hide modal
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    let modal = document.getElementById("bottlesModal");
    if (event.target === modal) {
        closeBottlesModal();
    }
};






document.addEventListener("DOMContentLoaded", function () {
    const stockContent = document.querySelector(".stock-content");
    const buttons = document.querySelectorAll(".stock-btn");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const text = this.innerText.trim(); // Get button text
            stockContent.innerHTML = ""; // Clear previous content before inserting new content

            if (text === "Ajouter des Bouteilles") {
                stockContent.innerHTML = `
                    <h3 style="font-size: 20px; margin-top: 5px; font-weight: bold; color: white; display: flex; align-items: center; gap: 10px; justify-content: center; text-align: center; width: 100%;">
                        <i class="bx bx-cart-add" style="font-size: 25px; color: #ffbf47;"></i> Ajouter au Stock un Nouveau Type de Bouteille 
                    </h3>
                    <form id="addBottleForm" class="bottle-form-container">
                        <label class="bottle-label">Nom de la Bouteille:</label>
                        <input type="text" name="bottleName" class="bottle-input" required>
                        <label class="bottle-label">Nombre de Casiers:</label>
                        <input type="number" name="bottleQuantity" class="bottle-input" required min="1">
                        <button type="submit" class="bottle-submit-btn">Ajouter au stock</button>
                        <p id="message" class="bottle-success-message"></p>
                    </form>
                `;

                // Handle form submission
                document.getElementById("addBottleForm").addEventListener("submit", function (event) {
                    event.preventDefault(); // Prevent form from submitting normally
                    const formData = new FormData(this);

                    fetch('bottles/add_bottle.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("message").innerText = data;
                        if (data.includes('✅ Bouteille ajoutée avec succès!')) {
                            document.getElementById("addBottleForm").reset();
                        }
                    })
                    .catch(error => {
                        document.getElementById("message").innerText = "❌ Erreur lors de l'ajout!";
                        console.error("Erreur:", error);
                    });
                });
            } else if (text === "Stock Disponible") {
                fetch('bottles/get_stock.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            stockContent.innerHTML = `<p class="error-message">❌ Erreur: ${data.error}</p>`;
                            return;
                        }
                        if (data.length === 0) {
                            stockContent.innerHTML = `<p class="empty-message">📊 Aucune bouteille disponible en stock.</p>`;
                        } else {
                            let stockList = '<div class="stock-container"><p class="stock-title">Bouteilles disponibles</p><ul class="stock-list">';
                            data.forEach(bottle => {
                                stockList += `<li class="stock-item">
                                    <span class="bottle-name">${bottle.name}</span>
                                    <span class="stock-quantity">${bottle.quantity} Casiers</span>
                                </li>`;
                            });
                            stockList += '</ul></div>';
                            stockContent.innerHTML = stockList;
                        }
                    })
                    .catch(error => {
                        console.error("Erreur lors de la récupération du stock:", error);
                        stockContent.innerHTML = `<p class="error-message">❌ Erreur lors du chargement du stock.</p>`;
                    });
            } else if (text === "Consulter l'Historique") {
                fetch('bottles/fetch_history.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.length === 0) {
                            stockContent.innerHTML = "<p>Aucun historique disponible.</p>";
                            return;
                        }
            
                        const receivedNames = [...new Set(data.map(record => record.received_bottle_name))];
            
                        let filterHTML = `
                            <div class="history-header">
                                <div class="filter-container">
                                    <label>Afficher par Bouteille:</label>
                                    <select id="filterReceived">
                                        <option value="">Toutes</option>
                                        ${receivedNames.sort().map(name => `<option value="${name}">${name}</option>`).join('')}
                                    </select>
                                </div>
                                <button id="clearHistoryBtn" class="delete-history-btn">🗑️ Effacer l'historique</button>
                            </div>
                            <div id="historyData"></div>
                        `;
            
                        stockContent.innerHTML = filterHTML;
            
                        const renderHistory = (filteredData) => {
                            let historyHTML = `<div class="history-container">`;
                            filteredData.forEach(record => {
                                historyHTML += `
                                    <div class="history-entry">
                                        <div class="history-received">
                                            <h3>Bouteilles Reçues</h3>
                                            <p><strong>Nom:</strong> ${record.received_bottle_name}</p>
                                            <p><strong>Quantité:</strong> ${record.received_quantity} casiers</p>
                                            <p><strong>Réceptionné par:</strong> ${record.receiver_name}</p>
                                            <p class="history-date"><strong>Date:</strong> ${record.date_reported}</p>
                                        </div>
                                        <div class="history-given">
                                            <h3>Bouteilles Données</h3>
                                            <p><strong>Nom:</strong> ${record.given_bottle_name}</p>
                                            <p><strong>Quantité:</strong> ${record.given_quantity} casiers</p>
                                            <p><strong>Donné par:</strong> ${record.provider_name}</p>
                                            <p class="history-date"><strong>Date:</strong> ${record.date_reported}</p>
                                        </div>
                                    </div>`;
                            });
                            historyHTML += `</div>`;
                            document.getElementById("historyData").innerHTML = historyHTML;
                        };
            
                        renderHistory(data);
            
                        document.getElementById("filterReceived").addEventListener("change", function () {
                            const receivedFilter = this.value;
                            const filteredData = data.filter(record => receivedFilter === "" || record.received_bottle_name === receivedFilter);
                            renderHistory(filteredData);
                        });
            
                        document.getElementById("clearHistoryBtn").addEventListener("click", function () {
                            Swal.fire({
                                title: "Êtes-vous sûr ?",
                                text: "Toute l'historique sera supprimée, y compris le bilan des dettes !",
                                icon: "question",
                                showCancelButton: true,
                                confirmButtonColor: "#d33",
                                cancelButtonColor: "#3085d6",
                                confirmButtonText: "Oui, supprimer!",
                                cancelButtonText: "Annuler"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        title: "Confirmation Finale",
                                        text: "Cliquez sur OK supprimera définitivement l'historique de Stock des bouteilles.",
                                        icon: "warning",
                                        showCancelButton: true,
                                        confirmButtonColor: "#d33",
                                        cancelButtonColor: "#3085d6",
                                        confirmButtonText: "OK, supprimer!",
                                        cancelButtonText: "Annuler"
                                    }).then((finalResult) => {
                                        if (finalResult.isConfirmed) {
                                            fetch('bottles/delete_history.php', {
                                                method: 'POST'
                                            })
                                            .then(response => response.text())
                                            .then(data => {
                                                Swal.fire({
                                                    title: "Supprimé!",
                                                    text: "L'historique a été supprimé avec succès.",
                                                    icon: "success",
                                                    timer: 1200,  // Auto-close after 2000 milliseconds
                                                    showConfirmButton: false  // Hide the "OK" button
                                                });
                                                stockContent.innerHTML = "<p>Aucun historique disponible.</p>";
                                            })
                                            .catch(error => {
                                                console.error("Erreur lors de la suppression:", error);
                                                Swal.fire("Erreur!", "Une erreur est survenue lors de la suppression.", "error");
                                            });
                                        }
                                    });
                                    
                                }
                            });
                        });
                    })
                    .catch(error => {
                        console.error("Erreur lors du chargement de l'historique:", error);
                        stockContent.innerHTML = "<p>Erreur lors du chargement de l'historique.</p>";
                    });
            }
             else if (text === "Bilan des Dettes") {
                    stockContent.innerHTML = `<div id="debt-report-section">
                        <div class="stock-content">
                            <!-- Content will be inserted here dynamically -->
                        </div>
                    </div>`;
                
                    fetch('bottles/fetch_stock_report.php')
                        .then(response => response.json())
                        .then(data => {
                            const reportContainer = document.querySelector("#debt-report-section");
                
                            if (!data || data.length === 0) {
                                reportContainer.innerHTML = "<p>Aucune donnée disponible.</p>";
                                return;
                            }
                
                            let stockHTML = '<div class="stock-grid">';
                            data.forEach(record => {
                                let difference = record.received_quantity - record.given_quantity;
                                let message = "";
                                let messageClass = "";
                
                                if (difference > 0) {
                                    message = `Nous devons <strong>${record.provider_name}</strong> ${difference} casiers de <strong>${record.received_bottle_name}</strong>.`;
                                    messageClass = "owe-provider";
                                } else if (difference < 0) {
                                    message = `<strong>${record.provider_name}</strong> nous doit ${Math.abs(difference)} casiers de <strong>${record.received_bottle_name}</strong>.`;
                                    messageClass = "owe-us";
                                } else {
                                    message = `<strong>${record.provider_name}</strong> : Aucune dette, toutes les bouteilles sont équilibrées.`;
                                    messageClass = "neutral";
                                }
                
                                stockHTML += `<div class="stock-report ${messageClass}">
                                    <div class="report-right"><span class="provider-company">${record.provider_company}</span></div>
                                    <p>${message}</p>
                                    <p class="date-reported"><i class="bx bx-calendar"></i> ${record.date_reported}</p>
                                </div>`;
                            });
                            stockHTML += '</div>';
                            
                            reportContainer.innerHTML = stockHTML;
                
                            // Ensure scrolling works when content is loaded
                            reportContainer.scrollTop = 0;
                        })
                        .catch(error => console.error("Erreur:", error));
                }                
        });
    });
});



document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".stock-options button");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            // Remove 'active' class from all buttons
            buttons.forEach(btn => btn.classList.remove("active"));

            // Add 'active' class to the clicked button
            this.classList.add("active");
        });
    });
});














document.querySelector('a[data-target="gestion-conges-container"]').addEventListener("click", function(event) {
    event.preventDefault();
    
    // Hide other sections
    document.querySelectorAll(".content-container").forEach(container => {
        container.style.display = "none";
    });

    // Show leave management section
    document.getElementById("gestion-conges-container").style.display = "block";
});







document.addEventListener("DOMContentLoaded", function () {
    const leaveForm = document.getElementById("leaveRequestForm");
    const tableBody = document.getElementById("leaveRequestsTable");

    // Auto-calculate total days
    document.getElementById("startDate").addEventListener("change", calculateDays);
    document.getElementById("endDate").addEventListener("change", calculateDays);

    function calculateDays() {
        const start = new Date(document.getElementById("startDate").value);
        const end = new Date(document.getElementById("endDate").value);
        if (start && end && end >= start) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            document.getElementById("totalDays").textContent = diffDays;
        } else {
            document.getElementById("totalDays").textContent = 0;
        }
    }

    // Submit leave request
    leaveForm.addEventListener("submit", function (e) {
        e.preventDefault();

        // SweetAlert2 confirmation before submitting
        Swal.fire({
            title: "Confirmer la demande",
            text: "Voulez-vous vraiment soumettre cette demande de congé ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, envoyer",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append("employe", document.getElementById("employeeName").value);
                formData.append("type", document.getElementById("leaveType").value);
                formData.append("date_debut", document.getElementById("startDate").value);
                formData.append("date_fin", document.getElementById("endDate").value);
                formData.append("total_jours", document.getElementById("totalDays").textContent);
                formData.append("raison", document.getElementById("leaveReason").value);

                fetch("conges/submit_conge.php", { method: "POST", body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire("Envoyé !", "Votre demande a été soumise avec succès.", "success");
                            leaveForm.reset(); // ✅ Clears the form
                            document.getElementById("totalDays").textContent = "0"; // ✅ Reset total days display
                            fetchLeaveRequests(); // ✅ Refresh table
                        } else {
                            Swal.fire("Erreur", "Une erreur est survenue : " + data.message, "error");
                        }
                    });
            }
        });
    });

    // Fetch leave requests
    function fetchLeaveRequests() {
        fetch("conges/fetch_conge.php")
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = "";

                if (data.length === 0) {
                    tableBody.innerHTML = "<tr><td colspan='8'>Aucune demande trouvée.</td></tr>";
                    return;
                }

                data.forEach(request => {
                    let statutColor = '';
                
                    // Determine the color based on the statut value
                    if (request.statut === 'Approuvé') {
                        statutColor = 'color: green;';
                    }
                
                    tableBody.innerHTML += `
                        <tr>
                            <td>${request.employe}</td>
                            <td>${request.type}</td>
                            <td>${request.date_debut}</td>
                            <td>${request.date_fin}</td>
                            <td>${request.total_jours}</td>
                            <td>${request.raison}</td>
                            <td style="${statutColor}">${request.statut}</td>
                            <td style="text-align: center;">
                                <span class="telegram-icon bx bxl-telegram" data-id="${request.id}" data-status="Approuvé"></span>
                                <span class="bx bxs-trash-alt" style="font-size: 25px; color:rgba(255, 0, 0, 0.69); cursor: pointer;" data-id="${request.id}" data-status="Rejeté"></span>
                            </td>

                        </tr>
                    `;
                });

                // Add event listeners to action buttons after they are rendered
                addActionListeners();
            });
    }

    // Add event listeners to the action buttons (approve/reject)
    function addActionListeners() {
        const approveButtons = document.querySelectorAll('.bxl-telegram');
        const rejectButtons = document.querySelectorAll('.bxs-trash-alt');

        approveButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

                // SweetAlert2 confirmation before approving
                Swal.fire({
                    title: "Approuver la demande",
                    text: "Voulez-vous approuver cette demande de congé ?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Oui, approuver",
                    cancelButtonText: "Annuler"
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatus(id, 'Approuvé');
                    }
                });
            });
        });

        rejectButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

                // SweetAlert2 confirmation before rejecting
                Swal.fire({
                    title: "Supprimer la demande",
                    text: "Êtes-vous sûr de vouloir supprimer cette demande ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Oui, supprimer",
                    cancelButtonText: "Annuler"
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatus(id, 'Rejeté');
                    }
                });
            });
        });
    }

    // Update leave status or delete the request
    function updateStatus(id, statut) {
        const formData = new FormData();

        if (statut === 'Rejeté') {
            formData.append("id", id);

            fetch("conges/delete_conge.php", { method: "POST", body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("Supprimé !", "La demande a été rejetée et supprimée.", "success");
                        fetchLeaveRequests();
                    } else {
                        Swal.fire("Erreur", "Échec de la suppression: " + data.message, "error");
                    }
                });
        } else if (statut === 'Approuvé') {
            formData.append("id", id);
            formData.append("statut", statut);

            fetch("conges/update_conge.php", { method: "POST", body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("Approuvé !", "La demande de congé a été approuvée.", "success");
                        fetchLeaveRequests();
                        generatePDF(id);  // Generate the PDF after approval
                    } else {
                        Swal.fire("Erreur", "Échec de l'approbation: " + data.message, "error");
                    }
                });
        }
    }

    // Fetch the leave request details and generate the PDF
    function generatePDF(id) {
        Swal.fire({
            title: "Générer le PDF",
            text: "Générer le PDF pour cette demande de congé !",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Oui, générer",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`conges/generate_pdf.php?id=${id}`)
                    .then(response => response.blob())
                    .then(blob => {
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.download = `conge_${id}.pdf`;
                        link.click();
                    })
                    .catch(error => {
                        Swal.fire("Erreur", "Échec de la génération du PDF: " + error.message, "error");
                    });
            }
        });
    }

    // Fetch the leave requests when the page is loaded
    fetchLeaveRequests();
});




















document.addEventListener("DOMContentLoaded", function () {
    // Select all sidebar links with data-target
    document.querySelectorAll("a[data-target]").forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();

            // Hide all content sections
            document.querySelectorAll(".content-container").forEach(section => {
                section.style.display = "none";
            });

            // Get the target section and display it
            const targetId = this.getAttribute("data-target");
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.style.display = "block";
            }
        });
    });

    // Handle dropdown menu toggling
    document.querySelectorAll(".toggle-submenu").forEach(toggle => {
        toggle.addEventListener("click", function (event) {
            event.preventDefault();
            const submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains("submenu")) {
                submenu.classList.toggle("active"); // Toggle visibility
            }
        });
    });
});








document.addEventListener("DOMContentLoaded", function () {
    const foodCtion = document.getElementById("food-ction");
    const restoSection = document.getElementById("resto-section");

    // Ensure the section is hidden when the page loads
    restoSection.style.display = "none";

    foodCtion.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default anchor behavior

        // Toggle section visibility
        if (restoSection.style.display === "none" || restoSection.style.display === "") {
            restoSection.style.display = "block";
            foodCtion.classList.add("menu-item-active");
        } else {
            restoSection.style.display = "none";
            foodCtion.classList.remove("menu-item-active");
        }
    });

    // Hide the section when clicking outside
    document.addEventListener("click", function (event) {
        if (!foodCtion.contains(event.target) && !restoSection.contains(event.target)) {
            restoSection.style.display = "none";
            foodCtion.classList.remove("menu-item-active");
        }
    });
});










document.getElementById("profile-link").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent default action

    Swal.fire({
        title: "Êtes-vous sûr(e) ?",
        text: "Vous allez être déconnecté.",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Oui, déconnecter",
        cancelButtonText: "Annuler"
    }).then((result) => {
        if (result.isConfirmed) {
            // Show success message
            Swal.fire({
                title: "Déconnexion réussie",
                text: "Vous allez être redirigé...",
                icon: "success",
                timer: 1500, // Auto-close after 2 seconds
                showConfirmButton: false
            }).then(() => {
                window.location.href = "logout.php"; // Redirect after message
            });
        }
    });
});















