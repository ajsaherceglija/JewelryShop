document.getElementById('update-bag-form').addEventListener('change', function(event) {
    const target = event.target;
    if (target && target.matches('input[name="quantity"]')) {
        const oid = target.dataset.oid;
        const quantity = target.value;
        updateQuantity(oid, quantity);
    }
});

function updateTotalPrice() {
    let totalPrice = 0;
    document.querySelectorAll('.item-total').forEach(itemTotal => {
        const price = parseFloat(itemTotal.dataset.price);
        const quantityInput = itemTotal.parentNode.querySelector('input[name="quantity"]');
        if (quantityInput && quantityInput.value.trim() !== '') {
            const quantity = parseInt(quantityInput.value);
            const total = quantity * price;
            itemTotal.innerText = `$${total.toFixed(2)}`;
            totalPrice += total;
        }
    });
    document.getElementById('total-price').innerText = `$${totalPrice.toFixed(2)}`;
}

function updateQuantity(oid, quantity) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log('Quantity updated successfully.');
                updateTotalPrice();
            } else {
                console.error('Error updating quantity: ' + xhr.responseText);
            }
        }
    };
    xhr.open('POST', 'update_cart.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('oid=' + oid + '&quantity=' + quantity);
}
function removeFromBag(oid) {
    // Confirm removal
    if (confirm('Are you sure you want to remove this item?')) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var row = document.querySelector('.shopping-bag-item[data-oid="' + oid + '"]');
                    row.parentNode.removeChild(row);
                    updateTotalPrice();
                } else {
                    // Handle error
                    console.error('Error removing item: ' + xhr.responseText);
                }
            }
        };
        xhr.open('POST', 'update_cart.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('oid=' + oid);
    }
}