/* EMAIL JS */
const contactForm = document.getElementById('contact-form'),
      contactMessage = document.getElementById('contact-message')

const sendEmail = (e) => {
    e.preventDefault()

    //serviceID - templateID - #form - publicKey
    emailjs.sendForm('service_2s33886','template_6zoqx5f','#contact-form','SqLf0zyDUeq0v7Kjv') 
        .then(() => {
            // Show sent message
            contactMessage.textContent = 'Message sent successfully ✅'

            // Remove after five seconds
            setTimeout(() => {
                contactMessage.textContent = ''
            }, 5000)

            // Clear input fields
            contactForm.reset()
        }, () => {
            //Show error message 
            contactMessage.textContent = 'Message not sent (service error) ❌'
        })
}

contactForm.addEventListener('submit', sendEmail)