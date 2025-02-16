const acceptanceButton = document.querySelector('#acceptance-of-documents');
const resultButton = document.querySelector('#result');
const preRegistrationButton = document.querySelector('#pre-registration');
const popup = document.querySelector('.popup');
const closeButton = document.querySelector('.popup__close');

function getNewTicket(serviceCode) {
  // URL для обращения к PHP скрипту, который взаимодействует с базой данных
  const URL = '../scripts/get_ticket.php';

  return fetch(URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json' // Ожидаем JSON ответ от сервера
    },
    body: JSON.stringify({ serviceCode })
  })
}

acceptanceButton.addEventListener('click', () => {
  getNewTicket('Д')
  .then(res => {
    if (res.ok) {
      return res.json()
    }
    return Promise.reject(`Ошибка ${res.status}`)
  })
  .then(ticket => {
    newTicket = ticket['ticket'];
    openPopup();
    if (newTicket !== null) {
      popup.querySelector('.ticket').textContent = newTicket.slice(0, 1) + '-' + newTicket.slice(1);
    }
  })
  .catch(err => console.log(`Ошибка ${err}`))
});

resultButton.addEventListener('click', () => {
  getNewTicket('Р')
  .then(res => {
    if (res.ok) {
      return res.json()
    }
    return Promise.reject(`Ошибка ${res.status}`)
  })
  .then(ticket => {
    newTicket = ticket['ticket'];
    openPopup();
    if (newTicket !== null) {
      popup.querySelector('.ticket').textContent = newTicket.slice(0, 1) + '-' + newTicket.slice(1);
    }
  })
  .catch(err => console.log(`Ошибка ${err}`))
})

preRegistrationButton.addEventListener('click', () => {
  getNewTicket('З')
  .then(res => {
    if (res.ok) {
      return res.json()
    }
    return new Promise.reject(`Ошибка ${res.status}`)
  })
  .then(ticket => {
    newTicket = ticket['ticket'];
    openPopup();
    if (newTicket !== null) {
      popup.querySelector('.ticket').textContent = newTicket.slice(0, 1) + '-' + newTicket.slice(1);
    }
  })
  .catch(err => console.log(`Ошибка ${err}`))
})

function openPopup() {
  popup.classList.add('popup_is-opened');
}

function closeModal() { 
  popup.classList.remove('popup_is-opened');
}

closeButton.addEventListener('click', closeModal);