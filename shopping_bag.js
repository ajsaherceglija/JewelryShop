document.getElementById('update-bag-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const quantities = {};

    document.querySelectorAll('input[name="quantity"]').forEach(input => {
        const oid = input.dataset.oid;
        const quantity = input.value.trim();
        quantities[oid] = quantity;
    });
    updateQuantity(quantities);
});

function updateQuantity(quantities) {
    const formData = new FormData();
    for (const oid in quantities) {
        formData.append('oid[]', oid);
        formData.append('quantity[]', quantities[oid]);
    }
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        response.items.forEach(item => {
                            const row = document.querySelector(`tr[data-oid="${item.oid}"]`);
                            if (row) {
                                row.querySelector('.item-total').textContent = `$${item.total_item_price.toFixed(2)}`;
                            }
                        });
                        alert('Quantities updated successfully.');
                    } else {
                        alert('Failed to update quantities: ' + response.error);
                    }
                } catch (e) {
                    alert('Failed to update quantities: Invalid server response.');
                }
            } else {
                alert('Error updating quantity: ' + xhr.statusText);
            }
        }
    };
    xhr.open('POST', 'update_cart.php');
    xhr.send(formData);
}


function removeFromBag(oid) {
    if (confirm('Are you sure you want to remove this item?')) {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText.trim() === 'Success') {
                    const row = document.querySelector(`tr[data-oid="${oid}"]`);
                    if (row) {
                        row.remove();
                        alert('Item removed from shopping bag successfully.');
                    }
                } else {
                    console.error('Error removing item: ' + xhr.responseText);
                }
            }
        };
        xhr.open('POST', 'update_cart.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('oid=' + oid);
    }
}