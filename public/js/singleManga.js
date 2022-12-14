const adminEditBtn = document.getElementById('admin-edit-btn')
// Admin panel:

const adminEditMenu = document.getElementById('admin-edit-menu')
const adminMenuBackground = document.getElementById('admin-menu-background')

adminEditBtn.addEventListener('click', () => {
    adminEditMenu.classList.toggle('hidden')
})

adminMenuBackground.addEventListener('click', () => {
    adminEditMenu.classList.add('hidden')
})

// Quantity button:

const quantityMinus = document.getElementById('quantityMinus')
const quantityPlus = document.getElementById('quantityPlus')
const currentQuantity = document.getElementById('currentQuantity')

quantityMinus.addEventListener('click', () => {
    if (currentQuantity.value > 1) {
        currentQuantity.value--
    }
})

quantityPlus.addEventListener('click', () => {
    currentQuantity.value++
})
