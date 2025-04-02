const queueList = document.querySelector('.queue');

async function getQueue() {
    const URL = '/scripts/get_queue.php';

    return fetch(URL, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
};

async function showQueue(queueElement) {
    try {
        const result = await getQueue();
        const data = await result.json();
        const tickets = Array.from(data.tickets).reverse();
        console.log(tickets)
        tickets.forEach((item) => {
            const queueListElement = document.createElement('li');
            queueListElement.classList.add('queue__element')
            queueListElement.textContent = item;
            queueElement.append(queueListElement);
        })
    } catch(err) {
        console.log(err);
    }
}

showQueue(queueList);