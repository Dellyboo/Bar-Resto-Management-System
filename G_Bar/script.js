const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});




// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})







const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})





if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})



const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
})


document.addEventListener("DOMContentLoaded", function () {
    // Get all the sidebar links
    const sidebarLinks = document.querySelectorAll(".side-menu a");

    // Get all the content sections
    const sections = document.querySelectorAll(".content-section");

    // Function to show the selected section and hide others
    function showSection(id) {
        // Hide all sections
        sections.forEach(section => section.classList.remove("active"));

        // Show the selected section
        const selectedSection = document.getElementById(id);
        selectedSection.classList.add("active");
        
        // Highlight the active sidebar link
        sidebarLinks.forEach(link => link.classList.remove("active"));
        document.getElementById(id + "-link").classList.add("active");
    }

    // Add click event listeners to the sidebar links
    sidebarLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();  // Prevent default link behavior
            const sectionId = link.id.replace("-link", "-section");
            showSection(sectionId);
        });
    });

    // Initially, display the dashboard section
    showSection("dashboard-section");
    });


	document.addEventListener("DOMContentLoaded", function () {
    // Get the dark mode switch
    const darkModeSwitch = document.getElementById('switch-mode');

    // Add event listener to toggle dark mode
    darkModeSwitch.addEventListener('change', function () {
        document.body.classList.toggle('dark-mode', this.checked);
    });

    // Get all the sidebar links
    const sidebarLinks = document.querySelectorAll(".side-menu a");

    // Get all the content sections
    const sections = document.querySelectorAll(".content-section");

    // Function to show the selected section and hide others
    function showSection(id) {
        // Hide all sections
        sections.forEach(section => section.classList.remove("active"));

        // Show the selected section
        const selectedSection = document.getElementById(id);
        selectedSection.classList.add("active");

        // Highlight the active sidebar link
        sidebarLinks.forEach(link => link.classList.remove("active"));
        document.getElementById(id + "-link").classList.add("active");
    }

    // Add click event listeners to the sidebar links
    sidebarLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();  // Prevent default link behavior
            const sectionId = link.id.replace("-link", "-section");
            showSection(sectionId);
        });
    });

    // Initially, display the dashboard section
    showSection("dashboard-section");
    });

    function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({ behavior: 'smooth' });
}




function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.style.display = 'none';
    });

    // Show the selected section
    document.getElementById(sectionId).style.display = 'block';
}

// Hide all sections initially except the first one
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.content-section').forEach((section, index) => {
        section.style.display = index === 0 ? 'block' : 'none';
    });
});




function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.style.display = 'none';
    });

    // Show the selected section
    document.getElementById(sectionId).style.display = 'block';

    // Remove active class from all sidebar links
    document.querySelectorAll('.side-menu li').forEach(item => {
        item.classList.remove('active');
    });

    // Add active class to the clicked sidebar link
    document.querySelector(`[data-target="${sectionId}"]`)?.parentElement.classList.add('active');
}

// Ensure sidebar links trigger the function
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".side-menu a").forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent default link behavior
            const sectionId = this.getAttribute("data-target");
            if (sectionId) {
                showSection(sectionId);
            }
        });
    });

    // Ensure dashboard boxes also trigger the function
    document.querySelectorAll(".dashboard .box").forEach(box => {
        box.addEventListener("click", function() {
            const sectionId = this.getAttribute("data-target");
            if (sectionId) {
                showSection(sectionId);
            }
        });
    });

    // Show the first section by default
    document.querySelectorAll('.content-section').forEach((section, index) => {
        section.style.display = index === 0 ? 'block' : 'none';
    });
});



// This function will be called when you click the Gestion Commandes section
function showGestionCommandes() {
    document.getElementById("gestion-commandes-section").style.display = "block";
    // Optionally, hide other sections
}

// Example of showing details for Boisson or Kitchen (can be customized)
function showBoissonDetails() {
    alert("Details for Boisson");
    // You can add more logic here to load content for Boisson
}

function showKitchenDetails() {
    alert("Details for Kitchen");
    // You can add more logic here to load content for Kitchen
}










let isBoissonVisible = false; // To track the visibility state of the Boisson content
let isKitchenVisible = false; // To track the visibility state of the Kitchen content

// Function to toggle visibility of the boxes and content
function toggleBox(boxType) {
    const boissonDetailsContainer = document.getElementById('boisson-details-container');
    const kitchenDetailsContainer = document.getElementById('kitchen-details-container');
    const boxBoisson = document.getElementById('box-boisson');
    const boxKitchen = document.getElementById('box-kitchen');

    // When the Boisson box is clicked
    if (boxType === 'boisson') {
        if (isBoissonVisible) {
            // Hide Boisson details and show both Boisson and Kitchen boxes
            boissonDetailsContainer.style.display = 'none';
            boxBoisson.style.display = 'inline-block';
            boxKitchen.style.display = 'inline-block';
        } else {
            // Show Boisson details and hide Kitchen box
            boissonDetailsContainer.style.display = 'block';
            kitchenDetailsContainer.style.display = 'none'; // Hide Kitchen details
            boxBoisson.style.display = 'inline-block';
            boxKitchen.style.display = 'none';
        }
        // Toggle the visibility state for Boisson
        isBoissonVisible = !isBoissonVisible;
        isKitchenVisible = false; // Reset Kitchen visibility
    }

    // When the Kitchen box is clicked
    if (boxType === 'kitchen') {
        if (isKitchenVisible) {
            // Hide Kitchen details and show both Boisson and Kitchen boxes
            kitchenDetailsContainer.style.display = 'none';
            boxBoisson.style.display = 'inline-block';
            boxKitchen.style.display = 'inline-block';
        } else {
            // Show Kitchen details and hide Boisson box
            kitchenDetailsContainer.style.display = 'block';
            boissonDetailsContainer.style.display = 'none'; // Hide Boisson details
            boxBoisson.style.display = 'none';
            boxKitchen.style.display = 'inline-block';
        }
        // Toggle the visibility state for Kitchen
        isKitchenVisible = !isKitchenVisible;
        isBoissonVisible = false; // Reset Boisson visibility
    }
}







function showOrderForm() {
    document.getElementById('order-modal').style.display = 'block';
}

function closeOrderForm() {
    document.getElementById('order-modal').style.display = 'none';
}

// Function to update total price when quantity is changed
function updatePrice() {
    let selectedDrink = document.getElementById("drink-select");
    let price = selectedDrink.options[selectedDrink.selectedIndex].getAttribute("data-price");
    let quantity = document.getElementById("quantity").value;
    let total = price * quantity;
    document.getElementById("total-price").innerText = total + " FBU";
}

function updateTotal() {
    updatePrice();
}

// Move to Step 2 (Verification)
function nextStep() {
    let drink = document.getElementById("drink-select").value;
    let quantity = document.getElementById("quantity").value;
    let table = document.getElementById("table-number").value;
    let server = document.getElementById("server-number").value;
    let total = document.getElementById("total-price").innerText;

    if (!drink || !quantity || !table || !server) {
        alert("Veuillez remplir tous les champs.");
        return;
    }

    // Populate verification step
    document.getElementById("verify-drink").innerText = drink;
    document.getElementById("verify-quantity").innerText = quantity;
    document.getElementById("verify-table").innerText = table;
    document.getElementById("verify-server").innerText = server;
    document.getElementById("verify-total").innerText = total;

    document.getElementById("order-step-1").style.display = "none";
    document.getElementById("order-step-2").style.display = "block";
}

// Go back to Step 1
function prevStep() {
    document.getElementById("order-step-2").style.display = "none";
    document.getElementById("order-step-1").style.display = "block";
}

// Submit Order to Database
function submitOrder() {
    let drink = document.getElementById("verify-drink").innerText;
    let quantity = document.getElementById("verify-quantity").innerText;
    let table = document.getElementById("verify-table").innerText;
    let server = document.getElementById("verify-server").innerText;
    let total = document.getElementById("verify-total").innerText;

    let formData = new FormData();
    formData.append("drink", drink);
    formData.append("quantity", quantity);
    formData.append("table", table);
    formData.append("server", server);
    formData.append("total", total);

    fetch("submit_order.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        closeOrderForm();
    })
    .catch(error => console.error("Error:", error));
}








// Open & Close Modal
function openOrderModal() {
    document.getElementById("drink-order-modal").style.display = "flex";
}

function closeOrderModal() {
    document.getElementById("drink-order-modal").style.display = "none";
}

// Step Navigation
function nextStep() {
    document.getElementById("drink-step-1").style.display = "none";
    document.getElementById("drink-step-2").style.display = "block";
    document.getElementById("drink-step-circle").textContent = "2";

    generateOrderSummary();
}

function prevStep() {
    document.getElementById("drink-step-2").style.display = "none";
    document.getElementById("drink-step-1").style.display = "block";
    document.getElementById("drink-step-circle").textContent = "1";
}








// Update Price Based on Selection
function updatePrice(selectElement) {
    let selectedOption = selectElement.options[selectElement.selectedIndex];
    let price = selectedOption.getAttribute("data-price");
    let row = selectElement.parentElement.parentElement;

    row.querySelector(".unit-price").textContent = price + " FBU";
    updateTotal(row.querySelector(".quantity"));
}

// Update Total Price When Quantity Changes
function updateTotal(inputElement) {
    let row = inputElement.parentElement.parentElement;
    let unitPrice = parseFloat(row.querySelector(".unit-price").textContent);
    let quantity = parseInt(inputElement.value);
    let totalPrice = unitPrice * quantity;

    row.querySelector(".total-price").textContent = totalPrice + " FBU";
    updateGrandTotal();
}

// Update Grand Total Price
function updateGrandTotal() {
    let totalPrices = document.querySelectorAll(".total-price");
    let grandTotal = 0;

    totalPrices.forEach(price => {
        grandTotal += parseFloat(price.textContent);
    });

    document.getElementById("drink-total-price").textContent = grandTotal + " FBU";
}

// Remove a Drink Row
function removeRow(button) {
    button.parentElement.parentElement.remove();
    updateGrandTotal();
}

// Generate Order Summary in Step 2
function generateOrderSummary() {
    let summaryDiv = document.getElementById("drink-order-summary");
    summaryDiv.innerHTML = "";

    // Retrieve table number and server number from Step 1
    let tableNumber = document.getElementById("drink-table-number").value;
    let serverNumber = document.getElementById("drink-server-number").value;

    // Add table and server information to the summary
    summaryDiv.innerHTML += `<p><strong>Numéro de Table:</strong> ${tableNumber}</p>`;
    summaryDiv.innerHTML += `<p><strong>Numéro du Serveur:</strong> ${serverNumber}</p>`;

    // Retrieve drink order details
    let tableRows = document.querySelectorAll("#drink-table tbody tr");
    tableRows.forEach(row => {
        let drink = row.querySelector(".drink-select").value;
        let quantity = row.querySelector(".quantity").value;
        let total = row.querySelector(".total-price").textContent;

        // Add each drink's details to the summary
        summaryDiv.innerHTML += `<p><strong>${drink}</strong>: ${quantity} unités - ${total}</p>`;
    });

    // Add total price to the summary
    summaryDiv.innerHTML += `<p><strong>Total: </strong> ${document.getElementById("drink-total-price").textContent}</p>`;
}











// Function to open the Kitchen Order Modal
function openKitchenOrderModal() {
    document.getElementById("kitchen-order-modal").style.display = "block";
}

// Function to close the Kitchen Order Modal
function closeKitchenOrderModal() {
    document.getElementById("kitchen-order-modal").style.display = "none";
}

// Function to go to the next step in the modal
function nextKitchenStep() {
    document.getElementById("kitchen-step-1").style.display = "none";
    document.getElementById("kitchen-step-2").style.display = "block";
}

// Function to go back to the previous step in the modal
function prevKitchenStep() {
    document.getElementById("kitchen-step-2").style.display = "none";
    document.getElementById("kitchen-step-1").style.display = "block";
}


function nextKitchenStep() {
    document.getElementById("kitchen-step-1").style.display = "none";
    document.getElementById("kitchen-step-2").style.display = "block";

    // Change the step indicator to 2
    document.getElementById("kitchen-step-circle").textContent = "2";
}

function prevKitchenStep() {
    document.getElementById("kitchen-step-2").style.display = "none";
    document.getElementById("kitchen-step-1").style.display = "block";

    // Change the step indicator back to 1
    document.getElementById("kitchen-step-circle").textContent = "1";
}








document.addEventListener('DOMContentLoaded', function() {
    const profileLink = document.getElementById('profile-link');
    const profileModal = document.getElementById('profile-modal');
    const closeModal = document.querySelector('.close-profile-modal');

    profileLink.addEventListener('click', function(event) {
        event.preventDefault();
        // Fetch user data from the server
        fetchUserData().then(userData => {
            // Populate modal with user data
            document.getElementById('profile-pic').src = userData.profilePic;
            document.getElementById('profile-username').textContent = userData.username;
            document.getElementById('profile-email').textContent = userData.email;
            document.getElementById('profile-role').textContent = userData.role;
            // Display the modal
            profileModal.style.display = 'block';
        });
    });

    closeModal.addEventListener('click', function() {
        profileModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === profileModal) {
            profileModal.style.display = 'none';
        }
    });

    async function fetchUserData() {
        // Replace with actual data fetching logic
        return {
            profilePic: 'users/Profiles/dellyboo.png',
            username: 'i\'am Dellyboo',
            email: 'dellyboo@example.com',
            role: 'Administrator'
        };
    }    
});


