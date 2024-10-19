/*========= SHOW MENU ========== */
const navMenu = document.getElementById('nav-menu'), navToggle = document.getElementById('nav-toggle'), navClose = document.getElementById('nav-close')

/* ======== MENU SHOW ==== */
/* validate if constant exists */
if(navToggle) {
    navToggle.addEventListener('click', () =>{
        navMenu.classList.add('show-menu')
    })
}

/* ====== MENU HIDDEN ==== */
/* validate if constant exists */
if (navClose) {
    navClose.addEventListener('click', () =>{
        navMenu.classList.remove('show-menu')
    })
}




/* ========= CHANGE BACKGROUND HEADER ======== */
const bgHeader = () =>{
    const header = document.getElementById('header')
    // When the scroll is greater than 50 viewport height, add the scroll-header class to the header tag 
    this.scrollY >= 50 ? header.classList.add('bg-header') 
                      : header.classList.remove('bg-header')
}
window.addEventListener('scroll', bgHeader)




/* DARK LIGHT THEME */