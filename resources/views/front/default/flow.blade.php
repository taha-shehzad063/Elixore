<!-- Popup HTML -->
<a id="order-popup" href="#" style="
    position: fixed;
    bottom: 20px;
    left: 20px;
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 8px;
    padding: 10px;
    display: none;
    align-items: center;
    max-width: 320px;
    font-family: Arial, sans-serif;
    z-index: 9999;
    text-decoration: none;
    color: inherit;
">
    <img id="popup-img" src="" alt="" style="width:60px;height:60px;object-fit:cover;border-radius:6px;margin-right:10px">
    <div style="font-size:14px;line-height:1.4">
        <strong>Someone recently bought</strong><br>
        <span id="popup-product"></span><br>
        <small id="popup-time"></small>
    </div>
</a>

<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeOutDown {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(20px); }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let seenOrders = JSON.parse(localStorage.getItem('seenOrders') || '[]');
    let queue = [];
    let showing = false;

    function fetchOrders() {
        $.getJSON('/latest-orders', function(data) {
            let newOrders = data.filter(o => !seenOrders.includes(o.id));
            if (newOrders.length > 0) {
                queue.push(...newOrders);
                if (!showing) {
                    showNext();
                }
            }
        });
    }

    function showNext() {
        if (queue.length === 0) {
            showing = false;
            return;
        }
        showing = true;
        let order = queue.shift();

        // Mark as seen
        seenOrders.push(order.id);
        localStorage.setItem('seenOrders', JSON.stringify(seenOrders));

        // Set content
        $('#popup-img').attr('src', order.image);
        $('#popup-product').text(order.product);
        $('#popup-time').text(`${order.time} from ${order.city}`);
        
        // Add link using slug
        $('#order-popup').attr('href', `/product-details/${order.slug}`);

        let popup = $('#order-popup');
        popup.css('display', 'flex').css('animation', 'fadeInUp 0.5s ease-in-out');

        setTimeout(() => {
            popup.css('animation', 'fadeOutDown 0.5s ease-in-out');
            setTimeout(() => {
                popup.hide();
                setTimeout(showNext, 60000); // wait 1 minute before next product
            }, 500);
        }, 8000); // show each popup for 8 seconds
    }

    // Initial fetch
    fetchOrders();

    // Poll every 15 seconds
    setInterval(fetchOrders, 15000);
});
</script>
