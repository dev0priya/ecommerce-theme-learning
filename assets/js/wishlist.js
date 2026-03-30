$(document).ready(function() {
    // Click event ko 'on' method se bind kiya taaki dynamic elements pe bhi chale
    $(document).on('click', '.wishlist-btn', function(e) {
        e.preventDefault();
        
        const btn = $(this);
        const productId = btn.data('product-id');
        const icon = btn.find('iconify-icon');

        // Path check: Agar project subfolder mein hai to './actions/...' use karein
        $.ajax({
            url: 'actions/wishlist_add.php', 
            method: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            success: function(response) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });

                if (response.status === 'success') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Perfect!',
                        text: 'Your product is saved in wishlist'
                    });
                    // Visual changes
                    icon.attr('icon', 'solar:heart-bold');
                    btn.addClass('active').css('color', '#f43f5e');
                    
                } else if (response.status === 'exists') {
                    Toast.fire({
                        icon: 'info',
                        title: 'Note',
                        text: 'This item is already in your wishlist.'
                    });
                } else {
                    // Session not found case
                    Swal.fire({
                        icon: 'warning',
                        title: 'Login Required',
                        text: 'Please login to save items to your wishlist.',
                        confirmButtonColor: '#6366f1'
                    });
                }
            },
            error: function(xhr) {
                // Yeh tab chalega jab file path galat ho ya server error ho
                console.error("Critical Error:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'System Error',
                    text: 'Unable to reach the server. Please check the file path.'
                });
            }
        });
    });
});