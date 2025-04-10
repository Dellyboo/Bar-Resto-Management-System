document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('stock-modal');
    const modalUpdate = document.getElementById('stock-modal-update');
    const addStockBtn = document.getElementById('add-stock-btn');
    const closeBtn = document.querySelector('.close');
    const stockForm = document.getElementById('stock-form');
    const stockFormUpdate = document.getElementById('stock-form-update');

    const employeeModal = document.getElementById('employee-modal');
    const employeeForm = document.getElementById('employee-form');
    const addEmployeeBtn = document.getElementById('add-employee-btn');
    const closeEmployeeBtn = document.getElementById('close-employee')

    const employeModalUpdate = document.getElementById('employee-modal-update');

    const stockKitchenModalUpdate = document.getElementById('stock-kitchen-modal-update');

    const equipementModalUpdate = document.getElementById('equipement-modal-update');


    const equipementModal = document.getElementById('equipement-modal');
    const equipementForm = document.getElementById('equipement-form');

    const equipementDamagedModal = document.getElementById('equipement-damaged-modal');



    const categoryModal = document.getElementById("category-modal");
    const addCategoryBtn = document.getElementById("add-category-btn");
    const closeCategoryModal = categoryModal.querySelector(".close");
    const categoryType = document.getElementById("category-type");
    const parentCategoryGroup = document.getElementById("parent-category-group");
    const parentCategorySelect = document.getElementById("parent-category");


    const stockTable = $('#stock-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "php/get_stock_boisson.php", 
            "type": "GET",
            "data": function(d) {
                
                console.log(d); 
            },
            "dataSrc": function (json) {
                return json.data;  
            }
        },
        "columns": [
            { 
                "data": "picture",
                "render": function(data, type, row) {
                    console.log(data)
                    return data 
                        ? `<img src="pictures/${data}" alt="Produit" style="max-width: 50px; height: auto;">`
                        : 'Aucune image';
                },
                "orderable": false
            },
            { "data": "nom_boisson" },  
            { "data": "quantite_stock" },
            { "data": "prix_achat" }, 
            { 
                "data": null,  
                "render": function(data, type, row) {
                    return `
                        <div class="button-group">
                            <button class="edit-btn" data-id="${row.id}">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                            <button class="delete-btn" data-id="${row.id}">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </div>
                    `;
                },
                "orderable": false
            }
        ]
    });


    const employeeTable = $('#employee-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "php/get_employee.php",  // URL to the PHP file handling the data retrieval
            "type": "GET",
            "data": function(d) {
                console.log(d);  // Optional: log the DataTable's request data for debugging
            },
            "dataSrc": function(json) {
                return json.data;  // Return the data array from the response
            }
        },
        "columns": [
            { "data": "nom" },  
            { "data": "prenom" },
            { "data": "poste" },
            { "data": "numero_serveur" }, 
            { 
                "data": null,  
                "render": function(data, type, row) {
                    return `
                        <div class="button-group">
                            <button class="edit-btn employee-edit" data-id="${row.id}">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                            <button class="delete-btn employee-edit" data-id="${row.id}">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </div>
                    `;
                },
                "orderable": false
            }
        ]
    });
    


    const equipementTable = $('#equipement-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "php/get_equipment.php",  // URL to the PHP file handling the data retrieval
            "type": "GET",
            "data": function(d) {
                console.log(d);  // Optional: log the DataTable's request data for debugging
            },
            "dataSrc": function(json) {
                return json.data;  // Return the data array from the response
            }
        },
        "columns": [
            { "data": "name" },  
            { "data": "quantite" },
            { 
                "data": "created_at",  // Add the created_at column
                "render": function(data, type, row) {
                    // Create a new Date object from the created_at value
                    let date = new Date(data);
                    // Format the date in a custom format: "YYYY-MM-DD HH:mm"
                    let formattedDate = date.getFullYear() + '/' + 
                        ('0' + (date.getMonth() + 1)).slice(-2) + '/' + 
                        ('0' + date.getDate()).slice(-2) + ' ' + 
                        ('0' + date.getHours()).slice(-2) + ':' + 
                        ('0' + date.getMinutes()).slice(-2);
                    return formattedDate; // Return the formatted date and time
                }
            },
            { 
                "data": null,  
                "render": function(data, type, row) {
                    return `
                        <div class="button-group">
                            <button class="edit-btn employee-edit" data-id="${row.id}">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                            <button class="delete-btn employee-edit" data-id="${row.id}">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </div>
                    `;
                },
                "orderable": false
            }
        ]
    });
    
    



    const equipementEndommageTable = $('#equipement-endommage-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "php/get_equipement_endommage.php",  
            "type": "GET",
            "dataSrc": function(json) {
                return json.data;  
            }
        },
        "columns": [
            { "data": "name" },  
            { "data": "quantity" },
            { 
                "data": "created_at",
                "render": function(data, type, row) {
                    if (data) {
                        let date = new Date(data);
                        let day = date.getDate().toString().padStart(2, '0');
                        let month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-based
                        let year = date.getFullYear();
                        let hours = date.getHours().toString().padStart(2, '0');
                        let minutes = date.getMinutes().toString().padStart(2, '0');
                        return `${day}/${month}/${year} ${hours}:${minutes}`;
                    }
                    return "";
                }
            },
            { 
                "data": null,  
                "render": function(data, type, row) {
                    return `
                        <div class="button-group">
                            <button class="delete-btn" data-id="${row.id}" style="text-align: left; margin-left:50px;">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </div>
                    `;
                },
                "orderable": false
            }
        ]
    });
    
    // Handle the delete button click
    $('#equipement-endommage-table').on('click', '.delete-btn', function() {
        let id = $(this).data('id');
        if (confirm('Etes-vous sur de vouloir supprimer cet equipement?')) {
            $.ajax({
                url: 'php/delete1_equipment.php',
                type: 'GET',
                data: { id: id },
                success: function(response) {
                    if (response === 'Success') {
                        alert('Equipement a été supprimé avec succès !');
                        equipementEndommageTable.ajax.reload(); // Reload the table data after delete
                    } else {
                        alert('Error deleting the equipment.');
                    }
                },
                error: function() {
                    alert('Error deleting the equipment.');
                }
            });
        }
    });
    
    

    const stockKitchenTable = $('#stock-kitchen-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "php/get_stock_kitchen.php",  // URL to the PHP file handling the data retrieval
            "type": "GET",
            "data": function(d) {
                console.log(d);  // Log the request data sent to the server
            },
            "dataSrc": function(json) {
                console.log(json);  // Log the response from the server
                return json.data;
            },
            "error": function(xhr, error, thrown) {
                console.error("AJAX Error: ", xhr, error, thrown);
            }
        },
        "columns": [
            { 
                "data": "picture",
                "render": function(data, type, row) {
                    console.log(data)
                    return data 
                        ? `<img src="pictures/${data}" alt="Produit" style="max-width: 50px; height: auto;">`
                        : 'Aucune image';
                },
                "orderable": false
            },
            { "data": "nom_ingredient" },
            { "data": "prix_achat" },
            { "data": "cuisine_categorie" },
            { "data": "sous_categorie" },
            { "data": "disponible" },
            { 
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <div class="button-group">
                            <button class="edit-btn employee-edit" data-id="${row.id}">
                                <i class="fas fa-edit"></i> Modifier
                            </button>
                            <button class="delete-btn employee-edit" data-id="${row.id}">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </div>
                    `;
                },
                "orderable": false
            }
        ]
    });

   


    $('#stock-table').on('click', '.delete-btn', function() {
        let stockId = $(this).data('id');
    
        if (confirm('Are you sure you want to delete this stock item?')) {
            $.ajax({
                url: 'php/delete_stock_boisson.php',  // Match the correct PHP file name
                type: 'POST',  // Use POST to send data securely
                data: { id: stockId },
                dataType: 'json',  // Expect JSON response
                success: function(response) {
                    if (response.success) {
                        alert(response.message);  // Show success message
                        stockTable.ajax.reload();  // Reload the DataTable
                    } else {
                        alert('Error: ' + response.message);  // Show error message from PHP
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);  // Log error details
                    alert('Failed to delete stock item. Please try again.');
                }
            });
        }
    });


    $('#equipement-table').on('click', '.delete-btn', function() {
        let equipementId = $(this).data('id');
    
        if (confirm('Etes-vous sur de vouloir supprimer cet equipement?')) {
            $.ajax({
                url: 'php/delete_equipement.php',  // Match the correct PHP file name
                type: 'POST',  // Use POST to send data securely
                data: { id: equipementId },
                dataType: 'json',  // Expect JSON response
                success: function(response) {
                    if (response.success) {
                        alert(response.message);  // Show success message
                        equipementTable.ajax.reload();  // Reload the DataTable
                    } else {
                        alert('Error: ' + response.message);  // Show error message from PHP
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);  // Log error details
                    alert('Failed to delete equipment. Please try again.');
                }
            });
        }
    });


    $('#stock-table').on('click', '.edit-btn', function() {
        let stockId = $(this).data('id');

        // Fetch stock data from the server
        $.ajax({
            url: 'php/get_stock_details.php', // Create this PHP file to return stock details
            type: 'GET',
            data: { id: stockId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#item-name-update').val(response.data.nom_boisson);
                    $('#item-quantity-update').val(response.data.quantite_stock);
                    $('#item-price-update').val(response.data.prix_achat);
                    
                    // Store stockId in the form for updating
                    $('#stock-form-update').data('id', stockId);

                    // Show the modal
                    $('#stock-modal-update').show();

                    if (response.data.picture) {
                        $('#current-image-preview-boisson').attr('src', `pictures/${response.data.picture}`).show();
                    } else {
                        $('#current-image-preview-boisson').attr('src', '').hide();
                    }
                } else {
                    alert('Failed to fetch stock details.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Error fetching stock details.');
            }
        });
    });




    $('#employee-table').on('click', '.delete-btn', function() {
        let employeeId = $(this).data('id');
    
        if (confirm('Etes-vous sur de vouloir supprimer les details de cet employe?')) {
            $.ajax({
                url: 'php/delete_employee.php',  // Match the correct PHP file name
                type: 'POST',  // Use POST to send data securely
                data: { id: employeeId },
                dataType: 'json',  // Expect JSON response
                success: function(response) {
                    if (response.success) {
                        alert(response.message);  // Show success message
                        employeeTable.ajax.reload();  // Reload the DataTable
                    } else {
                        alert('Error: ' + response.message);  // Show error message from PHP
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);  // Log error details
                    alert("Probleme de suppression des details de 'employe.");
                }
            });
        }
    });


    $('#employee-table').on('click', '.edit-btn', function() {
        let employeeId = $(this).data('id');

        // Fetch stock data from the server
        $.ajax({
            url: 'php/get_employee_details.php', // Create this PHP file to return stock details
            type: 'GET',
            data: { id: employeeId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#nom-update').val(response.data.nom);
                    $('#prenom-update').val(response.data.prenom);
                    $('#poste-update').val(response.data.poste);
                    $('#salaire-update').val(response.data.salaire);
                    $('#numero_serveur-update').val(response.data.numero_serveur)
                    $('#date_embauche-update').val(response.data.date_embauche);
                    
                    // Store stockId in the form for updating
                    $('#employee-form-update').data('id', employeeId);

                    // Show the modal
                    $('#employee-modal-update').show();
                } else {
                    alert('Failed to fetch employee details.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Error fetching employee details.');
            }
        });
    });



    $('#equipement-table').on('click', '.edit-btn', function() {
        let equipementId = $(this).data('id');

        // Fetch stock data from the server
        $.ajax({
            url: 'php/get_equipment_details.php', // Create this PHP file to return stock details
            type: 'GET',
            data: { id: equipementId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#nom_equipement-update').val(response.data.name);
                    $('#quantite-update').val(response.data.quantite);
                    
                    // Store stockId in the form for updating
                    $('#equipement-form-update').data('id', equipementId);

                    // Show the modal
                    $('#equipement-modal-update').show();
                } else {
                    alert('Failed to fetch employee details.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Error fetching employee details.');
            }
        });
    });




    $('#stock-kitchen-table').on('click', '.delete-btn', function() {
        let employeeId = $(this).data('id');
    
        if (confirm('Etes-vous sur de vouloir supprimer les details de la cuisine?')) {
            $.ajax({
                url: 'php/delete_stock_kitchen.php',  // Match the correct PHP file name
                type: 'POST',  // Use POST to send data securely
                data: { id: employeeId },
                dataType: 'json',  // Expect JSON response
                success: function(response) {
                    if (response.success) {
                        alert(response.message);  // Show success message
                        stockKitchenTable.ajax.reload();  // Reload the DataTable
                    } else {
                        alert('Error: ' + response.message);  // Show error message from PHP
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);  // Log error details
                    alert("Probleme de suppression des details de la cuisine.");
                }
            });
        }
    });


    $('#stock-kitchen-table').on('click', '.edit-btn', function() {
        const itemId = $(this).data('id');
    
        $('#stock-kitchen-modal-update').show();
        $('#modal-title').text("Modifier l'ingredient");
    
        $.ajax({
            url: `php/get_stock_kitchen_details.php?id=${itemId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const item = response.data;
    
                    $('#ingredient-name-update').val(item.nom_ingredient);
                    $('#ingredient-price-update').val(item.prix_achat);
                    $('#item-id').val(item.id);
    
                    // Set Availability Dropdown
                    let availabilityValue = item.disponible === 'Disponible' ? '1' : '0';
                    $('#availability').val(availabilityValue);
    
                    // Load Categories
                    $.ajax({
                        url: 'php/get_all_categories.php',
                        type: 'GET',
                        dataType: 'json',
                        success: function(catResponse) {
                            if (catResponse.success) {
                                let categorySelect = $('#category-update');
                                categorySelect.empty().append('<option value="">Sélectionner une catégorie</option>');
    
                                catResponse.data.forEach(category => {
                                    categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
                                });
    
                                // Set the correct category
                                categorySelect.val(item.cuisine_categorie_id).change();
    
                                // Load Subcategories based on the selected category
                                loadSubCategories(item.cuisine_categorie_id, item.sous_categorie_id);
                            }
                        }
                    });

                    if (item.picture) {
                        $('#current-image-preview').attr('src', `pictures/${item.picture}`).show();
                    } else {
                        $('#current-image-preview').attr('src', '').hide();
                    }
                } else {
                    alert("Erreur lors de la récupération des détails.");
                }
            },
            error: function() {
                alert("Erreur serveur.");
            }
        });
    });


    function loadSubCategories(categoryId, selectedSubCategory) {
        $.ajax({
            url: `php/get_sous_categories.php?cuisine_categorie_id=${categoryId}`,
            type: 'GET',
            dataType: 'json',
            success: function(subResponse) {
                let subCategorySelect = $('#sub-category-update');
                subCategorySelect.empty().append('<option value="">Sélectionner une sous-catégorie</option>');
    
                if (subResponse.length > 0) {
                    subResponse.forEach(sub => {
                        subCategorySelect.append(`<option value="${sub.id}">${sub.name}</option>`);
                    });
    
                    // Set the correct subcategory if editing
                    if (selectedSubCategory) {
                        subCategorySelect.val(selectedSubCategory);
                    }
                }
            }
        });
    }







    $('#presence-employee-btn').on('click', function() {
        $('#attendance-modal').show();
        fetchEmployees();
    });

    // Close the modal when the "Close" button is clicked
    $('#close-modal-btn').on('click', function() {
        $('#attendance-modal').hide();
    });

    // Fetch all employees from the backend and display them in the modal
    function fetchEmployees() {
        $.ajax({
            url: 'php/fetch_employees.php', // PHP script to fetch employees
            method: 'GET',
            success: function(data) {
                const employees = JSON.parse(data);
                let employeeListHtml = '';
                employees.forEach(employee => {
                    employeeListHtml += `
                        <tr>
                            <td>${employee.nom} ${employee.prenom}</td>
                            <td>
                                <label>
                                    <input type="radio" name="attendance_${employee.id}" value="present"> Present
                                </label>
                                <label>
                                    <input type="radio" name="attendance_${employee.id}" value="absent"> Absent
                                </label>
                            </td>
                        </tr>
                    `;
                });
                $('#manage-employee-list').html(employeeListHtml);
            }
        });
    }

    // Handle the form submission to save attendance
    $('#attendance-form').on('submit', function(e) {
        e.preventDefault();

        const formData = $(this).serializeArray();
        const attendanceData = {};

        // Collect attendance data from form
        formData.forEach(function(item) {
            const employeeId = item.name.replace('attendance_', ''); // Extract employee ID
            attendanceData[employeeId] = item.value; // Save attendance status
        });

        const dataToSend = {
            attendance_date: new Date().toISOString().split('T')[0], // Today's date in YYYY-MM-DD format
            attendance: JSON.stringify(attendanceData)
        };

        $.ajax({
            url: 'php/save_attendance.php', // PHP script to save attendance data
            method: 'POST',
            data: dataToSend,
            success: function(response) {
                alert('Attendance saved successfully');
                $('#attendance-modal').hide();
            },
            error: function() {
                alert('An error occurred while saving attendance');
            }
        });
    });


    // Toggle between tabs (Make Attendance and View Attendance)
const tabButtons = document.querySelectorAll('.tab-btn');
tabButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        const selectedTab = e.target.getAttribute('data-tab');

        // Remove active class from all tab buttons and hide all tab contents
        tabButtons.forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

        // Add active class to the clicked button and show the corresponding tab content
        e.target.classList.add('active');
        document.getElementById(selectedTab).classList.add('active');
    });
});

// Open the modal (example)
document.getElementById('add-employee-btn').addEventListener('click', () => {
    document.getElementById('attendance-modal').style.display = 'flex';
});

// Close the modal
document.getElementById('close-modal-btn').addEventListener('click', () => {
    document.getElementById('attendance-modal').style.display = 'none';
});

// Submit attendance form (Make Attendance)
document.getElementById('attendance-form').addEventListener('submit', (e) => {
    e.preventDefault();

    // Gather attendance data from radio buttons
    const attendanceData = {};
    const radios = document.querySelectorAll('input[type="radio"]:checked');
    radios.forEach(radio => {
        const employeeId = radio.name.split('_')[1];
        attendanceData[employeeId] = radio.value;
    });

    // Send the attendance data to the server (make sure to handle this properly)
    console.log(attendanceData);

    // Display a message or feedback here if needed
    alert("Attendance saved successfully!");
});

// View attendance by date
// View attendance by date
document.getElementById('view-attendance-btn').addEventListener('click', () => {
    const selectedDate = document.getElementById('attendance-date').value;
    
    if (!selectedDate) {
        alert("Please select a date.");
        return;
    }

    // Make an AJAX request to fetch attendance data for the selected date
    fetch(`php/get_attendance.php?date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate the table with attendance data
                const tbody = document.querySelector('#employee-attendance-table tbody');
                tbody.innerHTML = ''; // Clear previous data

                // Loop through the data and add rows to the table
                data.data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.nom} ${item.prenom}</td>
                        <td>${item.status}</td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                alert(data.message); // Show error message if no data found or other errors
            }
        })
        .catch(error => {
            console.error('Error fetching attendance data:', error);
            alert('Failed to fetch attendance data');
        });
});



    
    // When category changes, reload subcategories dynamically
    $('#category-update').on('change', function() {
        let selectedCategoryId = $(this).val();
        loadSubCategories(selectedCategoryId, null);
    });
    


    // Close the modal
    $('.close').on('click', function() {
        $('#stock-modal-update').hide();
    });

    $('.close').on('click', function() {
        $('#employee-modal-update').hide();
    });

    $('.close').on('click', function() {
        $('#stock-kitchen-modal-update').hide();
    });

    $('.close').on('click', function() {
        $('#equipement-modal-update').hide();
    });

    // Submit form for updating stock
    $('#stock-form-update').on('submit', function(e) {
        e.preventDefault();

        let stockId = $(this).data('id');
        let formData = new FormData(this);
        
    formData.append('id', stockId);
    formData.append('name', $('#item-name-update').val());
    formData.append('quantity', $('#item-quantity-update').val());
    formData.append('price', $('#item-price-update').val());

    // Append the picture file
    let pictureFile = $('#boisson-picture-update')[0].files[0];
    if (pictureFile) {
        formData.append('picture', pictureFile);
    }


        $.ajax({
            url: 'php/update_stock.php', // Create this PHP file to handle updates
            type: 'POST',
            data: formData,
            processData: false, // Important: Prevents jQuery from processing FormData
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#stock-modal-update').hide();
                    stockTable.ajax.reload();
                    document.getElementById('stock-form-update').reset();
                    document.getElementById('image-preview-update-boisson').style.display = 'none';
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Failed to update stock.');
            }
        });
    });

    document.getElementById('boisson-picture-update').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview-update-boisson').src = e.target.result;
                document.getElementById('image-preview-update-boisson').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });



    $('#employee-form-update').on('submit', function(e) {
        e.preventDefault();

        let employeeId = $(this).data('id');
        let formData = {
            id: employeeId,
            nom: $('#nom-update').val(),
            prenom: $('#prenom-update').val(),
            poste: $('#poste-update').val(),
            salaire: $('#salaire-update').val(),
            numero_serveur: $('#numero_serveur-update').val(),
            date_embauche: $('#date_embauche-update').val()
        };

        $.ajax({
            url: 'php/update_employee.php', // Create this PHP file to handle updates
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#employee-modal-update').hide();
                    employeeTable.ajax.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Failed to update employee.');
            }
        });
    });


    $('#equipement-form-update').on('submit', function(e) {
        e.preventDefault();

        let equipementId = $(this).data('id');
        let formData = {
            id: equipementId,
            name: $('#nom_equipement-update').val(),
            quantite: $('#quantite-update').val(),
        };

        $.ajax({
            url: 'php/update_equipement.php', // Create this PHP file to handle updates
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#equipement-modal-update').hide();
                    equipementTable.ajax.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Failed to update equipment.');
            }
        });
    });


    $('#stock-kitchen-form-update').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('id', $('#item-id').val());
        formData.append('nom_ingredient', $('#ingredient-name-update').val());
        formData.append('prix_achat', $('#ingredient-price-update').val());
        formData.append('cuisine_categorie_id', $('#category-update').val());
        formData.append('sous_categorie_id', $('#sub-category-update').val());
        formData.append('disponible', $('#availability').val());
    
        // Append the picture file
        let pictureFile = $('#ingredient-picture-update')[0].files[0];
        if (pictureFile) {
            formData.append('picture', pictureFile);
        }
    
        $.ajax({
            url: 'php/update_stock_kitchen.php',
            type: 'POST',
            data: formData,
            processData: false, // Important: Prevents jQuery from processing FormData
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Mise à jour réussie!');
                    $('#stock-kitchen-modal-update').hide();
                    document.getElementById('stock-kitchen-form-update').reset();
                    document.getElementById('image-preview-update').style.display = 'none';
                    stockKitchenTable.ajax.reload();
                } else {
                    alert('Échec de la mise à jour.');
                }
            },
            error: function() {
                alert('Erreur serveur.');
            }
        });
    });


    document.getElementById('ingredient-picture-update').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview-update').src = e.target.result;
                document.getElementById('image-preview-update').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    



    $('#add-equipment-btn').on('click', function() {
        $('#equipement-modal').show();
    });

    $('.close').on('click', function() {
        $('#equipement-modal').hide();
    });


    $('#add-equipment-damaged-btn').on('click', function() {
        $('#equipement-damaged-modal').show();
    });

    $('.close').on('click', function() {
        $('#equipement-damaged-modal').hide();
    });


    
    

   

    // Open modal
    addStockBtn.onclick = function() {
        modal.style.display = 'block';
        stockForm.style.display='block';
    }

    addEmployeeBtn.onclick = function() {
        employeeModal.style.display = 'block';
        employeeForm.style.display='block';
    }

    // Close modal
    closeBtn.onclick = function() {
        modal.style.display = 'none';
       ;
        stockForm.classList.add('hidden');
        stockForm.reset();
    }

    closeEmployeeBtn.onclick = function() {
        employeeModal.style.display = 'none';
        employeeForm.classList.add('hidden');
        employeeForm.reset();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
            
            stockForm.classList.add('hidden');
            stockForm.reset();
        } else if (event.target == modalUpdate) {
            modalUpdate.style.display = 'none';
            
            //stockFormUpdate.classList.add('hidden');
            //stockFormUpdate.reset();
        }else if (event.target == employeeModal){
            employeeModal.style.display = 'none';
        } else if (event.target == employeModalUpdate){
        employeModalUpdate.style.display='none';
    } else if (event.target==stockKitchenModalUpdate){
        stockKitchenModalUpdate.style.display='none';
    }else if (event.target == equipementModal){
        equipementModal.style.display='none';
    }else if (event.target == equipementModalUpdate){
        equipementModalUpdate.style.display='none';
    }else if (event.target == equipementDamagedModal){
        equipementDamagedModal.style.display='none';
    }
    }

 

    // Show form when category is selected
   

    // Handle form submission
    stockForm.onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);
    
        fetch('php/ajout_stock_boisson.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data)
            if (data.success) {
                alert('Stock added successfully!');
                modal.style.display = 'none';
              
                stockForm.style.display = 'none';
                stockForm.reset();
                stockTable.ajax.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            console.log(error)
            alert('An error occurred while adding the stock.');
        });
    }

    document.getElementById('boisson-picture').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });




    employeeForm.onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        console.log(formData);
    
        fetch('php/ajout_employee.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data)
            if (data.success) {
                alert('Employe ajoute avec success!');
                employeeModal.style.display = 'none';
                employeeTable.ajax.reload();
              
              //  stockForm.style.display = 'none';
              //  stockForm.reset();
              //  stockTable.ajax.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            console.log(error)
            alert('An error occurred while adding the employee.');
        });
    }

    equipementForm.onsubmit = function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        console.log(formData);
    
        fetch('php/ajout_equipement.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data)
            if (data.success) {
                alert(data.message);
                equipementModal.style.display = 'none';
                equipementTable.ajax.reload();
              
              //  stockForm.style.display = 'none';
                equipementForm.reset();
              //  stockTable.ajax.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            console.log(error)
            alert("Une erreur s'est produit en ajoutant un equipement");
        });
    }


    document.getElementById('category-form').onsubmit = function(e) {
        e.preventDefault(); // Prevent the default form submission
    
        const formData = new FormData(this); // Collect form data
    
        fetch('php/add_category.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Handle the response as JSON
        .then(data => {
            console.log(data); // Log the response data to check for success
    
            if (data.success) {
                alert('Catégorie ajoutée avec succès!'); // Success message
                // Optionally, you can close the modal or reset the form here
                document.getElementById('category-form').reset();
                categoryModal.style.display='none';
            } else {
                alert('Erreur: ' + data.message); // Error message if the action failed
            }
        })
        .catch(error => {
            console.error('Error:', error); // Log any errors that occur during the fetch
            alert('Une erreur est survenue lors de l\'ajout de la catégorie.');
        });
    };


    addCategoryBtn.addEventListener("click", function () {
        categoryModal.style.display = "block";
    });

    // Close modal
    closeCategoryModal.addEventListener("click", function () {
        categoryModal.style.display = "none";
    });

    // Hide modal when clicking outside content
    window.addEventListener("click", function (event) {
        if (event.target === categoryModal) {
            categoryModal.style.display = "none";
        }
    });

    // Show/hide parent category dropdown based on selection
    categoryType.addEventListener("change", function () {
        if (categoryType.value === "sous_categorie") {
            parentCategoryGroup.style.display = "block";
            loadCAtegories();
        } else {
            parentCategoryGroup.style.display = "none";
        }
    });

    function loadCAtegories() {
        fetch("php/get_categories.php") // Adjust PHP script name
            .then(response => response.json())
            .then(data => {
                parentCategorySelect.innerHTML = ""; // Clear the current options
                data.data.forEach(category => { // Accessing data in 'data' key
                    let option = document.createElement("option");
                    option.value = category.id;
                    option.textContent = category.name;
                    parentCategorySelect.appendChild(option);
                });
            })
            .catch(error => console.error("Error loading categories:", error));
    }

    
    document.getElementById('stock-kitchen-form').onsubmit = function(e) {
        e.preventDefault(); // Prevent default form submission
    
        const formData = new FormData(this); // Get form data
    
        fetch('php/add_kitchen_stock.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Parse response as JSON
        .then(data => {
            console.log(data); // Log the response for debugging
    
            if (data.success) {
                alert('Produit ajouté au stock de cuisine avec succès!');
                document.getElementById('stock-kitchen-form').reset();
                document.getElementById('stock-kitchen-modal').style.display = 'none'; // Close modal
                stockKitchenTable.ajax.reload();
    
                // Update image preview
                if (data.picture) {
                    document.getElementById('image-preview').src = data.picture;
                    document.getElementById('image-preview').style.display = 'block';
                }
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue lors de l\'ajout du produit.');
        });
    };
    
    // Handle image preview before upload
    document.getElementById('ingredient-picture').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Open the modal
    document.getElementById('add-stock-kitchen-btn').onclick = function() {
        document.getElementById('stock-kitchen-modal').style.display = 'block'; // Show the modal
    };
    
    // Close the modal
    document.getElementById('close-stock-kitchen-modal').onclick = function() {
        document.getElementById('stock-kitchen-modal').style.display = 'none'; // Close the modal
    };
    
    document.getElementById("equipement-damaged-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission
    
        let formData = new FormData(this);
    
        fetch('php/add_equipement_endommage.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert(data.success);
                document.getElementById("equipement-damaged-form").reset(); // Reset form
                document.getElementById('equipement-damaged-modal').style.display = 'none'; // Close modal
                equipementTable.ajax.reload();
                equipementEndommageTable.ajax.reload();
            }
        })
        .catch(error => console.error("Error submitting form:", error));
    });
    
    loadCategories();
    loadEquipements();

    function fetchComplaints() {
        fetch('php/get_complaints.php')
            .then(response => response.json())
            .then(data => {
                console.log(data)
                let complaintsGrid = document.getElementById("complaints-grid");
                complaintsGrid.innerHTML = ""; // Clear existing content
    
                if (data.length === 0) {
                    complaintsGrid.innerHTML = "<p>Aucune plainte trouvée.</p>";
                    return;
                }
    
                data.forEach(complaint => {
    // Determine the status icon
    let statusIcon = complaint.status === "consulté" 
        ? "<span class='status-icon' style='color: green;'>&#10004;</span>"  // ✅ Green Checkmark
        : "<span class='status-icon' style='color: red;'>&#10060;</span>";  // ❌ Red Cross

    let consultButton = complaint.status !== "consulté" 
        ? `<button class="consult-btn" data-id="${complaint.id}">Consulter</button>` 
        : `<button class="consult-btn" data-id="${complaint.id}">Consulter</button>`;

    let card = `<div class="complaint-card">
                    ${statusIcon}  <!-- Status icon placed inside the card -->
                    <h3>${complaint.manager_name}</h3>
                    <p><strong>${complaint.specific_item}</strong></p>
                    <p><strong>Impact:</strong> ${complaint.impact}</p>
                    <p><small>${formatDateTime(complaint.submitted_at)}</small></p>

                    ${consultButton} <!-- Button only appears if status is not 'consulté' -->
                </div>`;
    complaintsGrid.innerHTML += card;
});

            })
            .catch(error => console.error("Erreur lors du chargement:", error));
    }
    function formatDateTime(dateTime) {
        let dateObj = new Date(dateTime);
        let day = String(dateObj.getDate()).padStart(2, '0');
        let month = String(dateObj.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        let year = dateObj.getFullYear();
        let hours = String(dateObj.getHours()).padStart(2, '0');
        let minutes = String(dateObj.getMinutes()).padStart(2, '0');
        
        return `${day}/${month}/${year} a ${hours}:${minutes}`;
    }
    // Fetch complaints every 5 seconds
    setInterval(fetchComplaints, 5000);
    fetchComplaints();
    

    document.getElementById("complaints-grid").addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('consult-btn')) {
            const complaintId = event.target.getAttribute('data-id');
            
            // Fetch complaint details
            fetch(`php/get_complaint_details.php?id=${complaintId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate the modal with the complaint data
                    document.getElementById("complaint-manager").value = data.manager_name;
                    document.getElementById("complaint-item").value = data.specific_item;
                    document.getElementById("complaint-impact").value = data.impact;
                    document.getElementById("complaint-description").value = data.employee_comments;
                    document.getElementById("complaint-date").value = formatDateTime(data.submitted_at);
                    document.getElementById("complaint-status").innerHTML = getStatusHTML(data.status);
    
                    // Change the complaint status (you can change it here as per your logic)
                    // For example, setting the status to 'consulté' when opening the modal:
                    if (data.status !== "consulté") {
                        // Update the status to 'consulté'
                        document.getElementById("complaint-status").value = "consulté";
    
                        // Optionally, you can send an update to the server here if you want to
                        // persist the status change on the backend:
                        fetch(`php/update_complaint_status.php?id=${complaintId}&status=consulté`, {
                            method: 'POST'
                        }).then(response => {
                            if (!response.ok) {
                                throw new Error('Failed to update status');
                            }
                        }).catch(error => {
                            console.error('Error updating complaint status:', error);
                        });
                    }
    
                    // Show the modal
                    document.getElementById("complaint-modal").style.display = "block";
                })
                .catch(error => {
                    console.error('Error fetching complaint details:', error);
                });
        }
    });

    function getStatusHTML(status) {
        if (status === "consulté") {
            return `<span style="color: green;">✅ ${status}</span>`;
        } else {
            return `<span style="color: red;">❌ ${status}</span>`;
        }
    }
    
    
    // Close the modal when the close button is clicked
    document.getElementById("close-complaint").addEventListener('click', function() {
        document.getElementById("complaint-modal").style.display = "none";
    });
    
    // Close the modal when the user clicks outside of the modal content
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById("complaint-modal")) {
            document.getElementById("complaint-modal").style.display = "none";
        }
    });


});

function loadEquipements() {
    fetch('php/get_equipements.php')
        .then(response => response.json())
        .then(data => {
            let select = document.getElementById("nom_equipement");
            data.forEach(equipement => {
                let option = document.createElement("option");
                option.value = equipement.id;
                option.textContent = equipement.name;
                select.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching equipements:", error));

}


function loadCategories() {
    fetch("php/get_categories.php") // Ensure the correct path to your PHP script
        .then(response => response.json())
        .then(data => {
            console.log(data.data)
            const categorySelect = document.getElementById('category');
            categorySelect.innerHTML = '<option value="">Sélectionner une catégorie</option>'; // Default placeholder

            if (Array.isArray(data.data) && data.data.length > 0) {
                data.data.forEach(category => {
                    const option = document.createElement("option");
                    option.value = category.id; // Set the category ID as the value
                    option.textContent = category.name; // Set the category name as the text content
                    categorySelect.appendChild(option);
                });
            } else {
                categorySelect.innerHTML += '<option value="">Aucune catégorie disponible</option>';
            }
        })
        .catch(error => {
            console.error("Error loading categories:", error);
            alert('Une erreur s\'est produite lors du chargement des catégories.');
        });
}

// Load Sub-Categories based on selected Category
document.getElementById('category').addEventListener('change', function() {
    const categoryId = this.value;
    const subCategoryGroup = document.getElementById('sub-category-group');
    const subCategorySelect = document.getElementById('sub-category');

    if (categoryId) {
        subCategoryGroup.style.display = 'block';

        fetch(`php/get_sous_categories.php?cuisine_categorie_id=${categoryId}`) // Ensure correct file name
            .then(response => response.json())
            .then(subCategories => {
                subCategorySelect.innerHTML = '<option value="">Sélectionner une sous-catégorie</option>';

                if (Array.isArray(subCategories) && subCategories.length > 0) {
                    subCategories.forEach(subCategory => {
                        const option = document.createElement("option");
                        option.value = subCategory.id;
                        option.textContent = subCategory.name;
                        subCategorySelect.appendChild(option);
                    });
                } else {
                    subCategorySelect.innerHTML += '<option value="">Aucune sous-catégorie disponible</option>';
                }
            })
            .catch(error => {
                console.error("Error loading sub-categories:", error);
                alert('Une erreur s\'est produite lors du chargement des sous-catégories.');
            });
    } else {
        subCategoryGroup.style.display = 'none';
    }

});



function showBoissonDetails() {
    document.getElementById("gestion-stock-section").style.display = "";
    document.getElementById("gestion-boisson-section").style.display = "block";
}

function showKitchenDetails() {
    document.getElementById("gestion-stock-section").style.display = "none";
    document.getElementById("gestion-cuisine-section").style.display = "block";
    // You can add more logic here to load content for Kitchen
}

function showGestionPlaintes() {
    document.getElementById("complaints-section").style.display = "block";
    document.getElementById("gestion-equipement-section").style.display="none";
    // Optionally, hide other sections
}