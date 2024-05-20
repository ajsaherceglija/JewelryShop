function toggleTable(tableId) {
    var table = document.getElementById(tableId);
    if (table.style.display === 'none') {
        table.style.display = 'block';
    } else {
        table.style.display = 'none';
    }
}
function deleteProduct(pid) {
    if (confirm('Are you sure you want to delete this product?')) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText.trim() === 'success') {
                    var row = document.getElementById('product-' + pid);
                    row.parentNode.removeChild(row);
                    alert('Product deleted successfully.');
                } else {
                    alert('Failed to delete product.');
                }
            }
        };
        xhr.open('POST', 'delete_product.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('pid=' + pid);
    }
}
function deleteCategory(CRID) {
    if (confirm('Are you sure you want to delete this category?')) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText.trim() === 'success') {
                    var row = document.getElementById('category-' + CRID);
                    row.parentNode.removeChild(row);
                    alert('Category deleted successfully.');
                } else {
                alert(xhr.responseText);
                }
            }
        };
        xhr.open('POST', 'delete_category.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('CRID=' + CRID);
    }
}
function deleteBrand(bid) {
    if (confirm('Are you sure you want to delete this brand?')) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText.trim() === 'success') {
                    var row = document.getElementById('brand-' + bid);
                    row.parentNode.removeChild(row);
                    alert('Brand deleted successfully.');
                } else {
                alert(xhr.responseText);
                }
            }
        };
        xhr.open('POST', 'delete_brand.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('BID=' + bid);
    }
}

