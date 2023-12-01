document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('createDeveloperBtn').addEventListener('click', function() {
        document.getElementById('developerForm').style.display = 'block';
        document.getElementById('typeForm').style.display = 'none';
        document.getElementById('categoryForm').style.display = 'none';
    });

    document.getElementById('createTypeBtn').addEventListener('click', function() {
        document.getElementById('developerForm').style.display = 'none';
        document.getElementById('typeForm').style.display = 'block';
        document.getElementById('categoryForm').style.display = 'none';
    });

    document.getElementById('createCategoryBtn').addEventListener('click', function() {
        document.getElementById('developerForm').style.display = 'none';
        document.getElementById('typeForm').style.display = 'none';
        document.getElementById('categoryForm').style.display = 'block';
    });

    document.getElementById('createDeveloperForm').addEventListener('submit', function(event) {
        event.preventDefault(); 

        const newDevName = document.getElementById('newDevName').value;
        const newDevWebsite = document.getElementById('newDevWebsite').value;

        const data = {
            newDevName: newDevName,
            newDevWebsite: newDevWebsite
        };

        fetch('functions.php', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Developer created successfully!');
                // Hide the developer creation form
                document.getElementById('developerForm').style.display = 'none';
            } else {
                alert('Error creating developer. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});

function populateDeveloperInfo() {
    const developerDropdown = document.getElementById('developer');
    const selectedDeveloper = developerDropdown.options[developerDropdown.selectedIndex].value;

    if (selectedDeveloper !== '-1') {
        const developerInfo = getDeveloperInfoFromDatabase(selectedDeveloper); 
        if (developerInfo) {
            document.getElementById('newDevName').value = developerInfo.name;
            document.getElementById('newDevWebsite').value = developerInfo.website;
            alert('Developer info populated. Proceeding will overwrite existing info if different.');
        }
    } else {
        document.getElementById('newDevName').value = '';
        document.getElementById('newDevWebsite').value = '';
    }
}

function getDeveloperInfoFromDatabase(developerId) {

    const developerInfo = {
        name: 'Existing Developer Name',
        website: 'Existing Developer Website'
    };
    return developerInfo;
}
