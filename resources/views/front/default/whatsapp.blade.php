<!-- WhatsApp Floating Button -->
<a href="https://wa.me/3273546753?text=I%20need%20some%20help%20https%3A%2F%2Fwww.yourwebsite.com" 
   class="whatsapp-float" 
   target="_blank" 
   title="Chat with us on WhatsApp">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
</a>

<style>
.whatsapp-float {
    position: fixed;
    bottom: 20px; /* distance from bottom */
    right: 20px;  /* distance from right */
    background-color: #25D366;
    border-radius: 50%;
    padding: 12px;
    box-shadow: 0px 2px 8px rgba(0,0,0,0.3);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease-in-out;
}

.whatsapp-float:hover {
    transform: scale(1.1);
}

.whatsapp-float img {
    width: 40px;
    height: 40px;
}
</style>
