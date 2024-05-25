document.getElementById('update-wishlist-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const comments = {};

    document.querySelectorAll('input[name="comment"]').forEach(input => {
        const wid = input.dataset.wid;
        const comment = input.value;

        comments[wid] = comment;
    });
    updateComments(comments);
});

function updateComments(comments) {
    const formData = new FormData();
    formData.append('comments', JSON.stringify(comments));

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                if (xhr.responseText.trim() === 'Success') {
                    alert('Wishlist comments updated successfully.');
                } else {
                    alert('Failed to update wishlist comments.');
                }
            } else {
                alert('Error: ' + xhr.statusText);
            }
        }
    };

    xhr.open('POST', 'update_wishlist.php', true);
    xhr.send(formData);
}

function removeFromWishlist(wid) {
    if (confirm('Are you sure you want to remove this item from your wishlist?')) {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText.trim() === 'Success') {
                    const row = document.querySelector(`tr[data-wid="${wid}"]`);
                    if (row) {
                        row.remove();
                        alert('Item removed from wishlist successfully.');
                    }
                } else {
                    console.error('Failed to remove item from wishlist');
                }
            }
        };
        xhr.open('POST', 'update_wishlist.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(`remove_wid=${wid}`);
    }
}
function addToCart(wid) {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                const response = xhr.responseText;
                if (response.trim() === 'Success') {
                    alert('Item added to cart.');
                } else {
                    console.error('Error adding to cart:', response);
                }
            } else {
                console.error('Failed to add item to cart. Status:', xhr.status);
            }
        }
    };
    xhr.open('POST', 'add_to_cart.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('wid=' + encodeURIComponent(wid));
}