let count = 0

function notify() {
    // Increment the count of button clicks
    count++

    // Create the popup element
    let popup = document.createElement('div')
    popup.id = 'add-to-cart-popup'
    popup.innerHTML = `Product added to cart! x${count}`

    // Style the popup

    popup.classList.add(
        'fixed',
        'bottom-2',
        'left-2',
        'p-4',
        'bg-green-500',
        'rounded',
        'text-white',
        'text-sm',
        'font-bold',
        'shadow-lg'
    )

    // Add the popup to the page
    document.body.appendChild(popup)

    // Remove the popup after 3 seconds
    setTimeout(function () {
        document.body.removeChild(popup)
    }, 3000)
}

// Add an event listener to the "Add to Cart" button
var addToCartButtons = document.querySelectorAll('.add-to-cart-button')
addToCartButtons.forEach(function (button) {
    button.addEventListener('click', notify)
})
