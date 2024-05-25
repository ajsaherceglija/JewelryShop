document.getElementById('update-bag-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const quantities = {};

    document.querySelectorAll('input[name="quantity"]').forEach(input => {
        const sid = input.dataset.sid;
        const quantity = input.value;

        quantities[sid] = quantity;
    });
    updateQuantities(quantities);
});

function updateQuantities(quantities) {
    const formData = new FormData();
    formData.append('quantities', JSON.stringify(quantities));

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                if (xhr.responseText.trim() === 'Success') {
                    updateTotalPrice();
                    alert('Shopping bag updated successfully.');
                } else {
                    alert('Failed to update shopping bag.');
                }
            } else {
                alert('Error: ' + xhr.statusText);
            }
        }
    };
    xhr.open('POST', 'update_cart.php', true);
    xhr.send(formData);
}
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
function removeFromBag(sid) {
    if (confirm('Are you sure you want to remove this item from your shopping bag?')) {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText.trim() === 'Success') {
                    const row = document.querySelector(`tr[data-sid="${sid}"]`);
                    if (row) {
                        row.remove();
                        updateTotalPrice();
                        alert('Item removed from shopping bag successfully.');
                    }
                } else {
                    console.error('Failed to remove item from shopping bag');
                }
            }
        };
        xhr.open('POST', 'update_cart.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(`remove_sid=${sid}`);
    }
}
