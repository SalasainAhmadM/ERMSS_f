/*==== GOALS MODAL ===*/
const modal = document.querySelectorAll('.goals__modal'),
      modalButton = document.querySelectorAll('.goals__button'),
      modalClose = document.querySelectorAll('.goals__modal-close')

let activeModal = (modalClick) => {
    modal[modalClick].classList.add('active-modal')
}

modalButton.forEach((modalButton, i) => {
    modalButton.addEventListener('click', () => {
        activeModal(i)
    })
})

modalClose.forEach((modalClose) => {
    modalClose.addEventListener('click', () => {
        modal.forEach((modalRemove) => {
            modalRemove.classList.remove('active-modal')
        })
    })
})


/*==== SWIPER TESTIMONIAL ===*/
const swipeTeam = new Swiper('.team__swiper', {
    loop: true,
    spaceBetween: 32,
    grabCursor: true,
    
    pagination: {
      el: '.swiper-pagination',
      dynamicBullets: true,
      clikable: true,
    },
  
  });


/*=== SHOW SCROLL UP ===*/
const scrollUp = () => {
    const scrollUp = document.getElementById('scroll-up')
    //pag ang scroll ay higher sa 350 viewport mag show ang arrow
    this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
                                            :scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)