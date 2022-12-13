document.getElementsByName('addToCart').forEach((button) => {
    button.addEventListener('click', (e) => {
        e.preventDefault()

        const data = {
            idManga: button.value,
            quantity:
                document.getElementById('quantity') === null
                    ? 1
                    : document.getElementById('quantity').value,
        }

        addToCart(data)
    })
})

const addToCart = async (data) => {
    await fetch('http://localhost:9990/js/insertCart.php', {
        // On envoie la requête à addToCart.php
        method: 'POST', // On envoie les données en POST')
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data), // On envoie l'id du manga
    })
        .then((response) => {
            response.json().then((data) => {
                if (!data.inCart) {
                    alert('Le manga a bien été ajouté au panier')
                } else {
                    alert('Le manga est déjà dans le panier')
                }
            })
        })
        .catch((error) => {
            console.log(error)
        })
}
