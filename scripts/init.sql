CREATE TABLE Tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Code CHAR(1) NOT NULL,
    Number INT NOT NULL,
    Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);