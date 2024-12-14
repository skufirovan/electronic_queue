// Получение нового номера талона

function get_new_ticket_number() {
    // URL для обращения к PHP скрипту, который взаимодействует с базой данных
    const url = './scripts/ticket.php';
  
    return fetch(url, {
      method: 'GET', 
      headers: {
        'Accept': 'application/json' // Ожидаем JSON ответ от сервера
      }
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json(); // Парсим JSON ответ
    })
    .then(data => {
      let newTicketNumber = parseInt(data.last_ticket, 10) + 1;
      return newTicketNumber; // Возвращаем новый номер талона
    })
    .catch(error => {
      console.error('Ошибка при получении номера талона:', error);
      // Обработка ошибки, например, отображение сообщения пользователю
      return null;
    });
  }
  
  
  // Пример использования:
  document.getElementById('button').addEventListener('click', async () => {
    const newTicket = await get_new_ticket_number();
  
    if (newTicket !== null) {
       //  Отображаем номер талона пользователю
      alert(`Ваш номер талона: ${newTicket}`); 
    }
  });