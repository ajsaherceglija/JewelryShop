const headerHeight = document.querySelector('header').offsetHeight;

document.body.style.paddingTop = headerHeight + 'px';

document.addEventListener("DOMContentLoaded", function() {
    const searchBox = document.getElementById("search-box");
    const searchInput = document.getElementById("search-input");
    const searchResults = document.getElementById("search-results");

    function performSearch(query) {
        searchResults.innerHTML = "";
        if (query.trim() === "") {
            searchResults.innerHTML = "Search any products and/or materials";
            return;
        }
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'search.php?query=' + encodeURIComponent(query), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText.trim() === "") {
                    searchResults.innerHTML = "No results found";
                } else {
                    searchResults.innerHTML = xhr.responseText;
                }
            }
        };
        xhr.send();
    }

    searchInput.addEventListener("input", function() {
        performSearch(this.value);
    });

    document.querySelector('.search-icon').addEventListener('click', function(event) {
        event.stopPropagation();
        searchBox.classList.toggle("active");
        if (searchBox.classList.contains("active")) {
            searchInput.focus();
            performSearch(searchInput.value);
        }
    });
    document.addEventListener('click', function(event) {
        if (!searchBox.contains(event.target)) {
            searchBox.classList.remove("active");
        }
    });
});