document.getElementById('deleteCart').addEventListener('click', function () {
    document.cookie = 'cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC'

    console.log('Cookie deleted')
    location.reload()
})
