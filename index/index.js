
const searchInput = document.getElementById('search-input');
const searchButton = document.getElementById('search-button');

if (searchInput && searchButton) {
    searchInput.addEventListener('input', () => {
        if (searchInput.value.trim() !== '') {
            searchButton.style.display = 'block';
        } else {
            searchButton.style.display = 'none';
        }
    });

    searchButton.addEventListener('click', () => {
        alert('Performing search: ' + searchInput.value);
    });
}

function openModal() {
    closeAllModalsAndSidebar();
    var modal = document.getElementById('signup-modal');
    if (modal) modal.style.display = 'block';
}

function closeModal() {
var modal = document.getElementById('signup-modal');
modal.style.display = 'none';
}


function openLoginModal() {
    closeAllModalsAndSidebar();
    var modal = document.getElementById('login-modal');
    if (modal) modal.style.display = 'block';
}

function closeLoginModal() {
var modal = document.getElementById('login-modal');
modal.style.display = 'none';
}


window.onclick = function (event) {
    var signupModal = document.getElementById('signup-modal');
    var loginModal = document.getElementById('login-modal');

    if (event.target == signupModal) {
        signupModal.style.display = 'none';
    }

    if (event.target == loginModal) {
        loginModal.style.display = 'none';
    }
};


function toggleFilterSection() {
    var filterOptions = document.getElementById('filterOptions');
    if (filterOptions.style.display === "none") {
        filterOptions.style.display = "block";
    } else {
        filterOptions.style.display = "none";
    }
}

function toggleFilterSortSection() {
    closeAllModalsAndSidebar();
    var button = document.querySelector('.toggle-button');
    var section = document.getElementById('filterSortSection');

    if (section.style.display === 'none' || section.style.display === '') {
        section.style.display = 'block'; 
        section.classList.add('visible'); 
        button.classList.add('expanded'); 
    } else {
        section.style.display = 'none'; 
        section.classList.remove('visible'); 
        button.classList.remove('expanded'); 
    }
}

function closeFilterSortSection() {
    var section = document.getElementById('filterSortSection');
    var button = document.querySelector('.toggle-button');

    if (section) {
        section.style.display = 'none'; 
        section.classList.remove('visible'); 
        if (button) button.classList.remove('expanded'); 
    }
}


function toggleSidebar(event) {
    event.preventDefault();
    event.stopPropagation(); 
    closeAllModalsAndSidebar();
    var sidebar = document.getElementById("sidebar");
    if (sidebar) sidebar.classList.toggle('active');
}


function closeSidebar() {
    var sidebar = document.getElementById("sidebar");
    sidebar.classList.remove('active');
}

document.addEventListener('click', function(event) {
    var sidebar = document.getElementById("sidebar");
    var isClickInsideSidebar = sidebar.contains(event.target);
    var isClickMenuIcon = event.target.closest('.menu'); 

    if (!isClickInsideSidebar && !isClickMenuIcon && sidebar.classList.contains('active')) {
        closeSidebar();
    }

    
});


function closeAllModalsAndSidebar() {
    
    var signupModal = document.getElementById('signup-modal');
    var loginModal = document.getElementById('login-modal');
    if (signupModal) signupModal.style.display = 'none';
    if (loginModal) loginModal.style.display = 'none';
    
    var sidebar = document.getElementById("sidebar");
    if (sidebar) sidebar.classList.remove('active');

    var filterSortSection = document.getElementById('filterSortSection');
    if (filterSortSection) {
        filterSortSection.style.display = 'none';
        filterSortSection.classList.remove('visible');
        var button = document.querySelector('.toggle-button');
        if (button) button.classList.remove('expanded');
    }

}

document.addEventListener("DOMContentLoaded", function() {
    
    var viewAllPluginsButton = document.getElementById("view-all-plugins");
    var viewAllArticlesButton = document.getElementById("view-all-articles");
    
    viewAllPluginsButton.addEventListener("click", function() {
      window.location.href = "catalog.php"; 
    });
  
    viewAllArticlesButton.addEventListener("click", function() {
      window.location.href = "articles.php"; 
    });

});
  