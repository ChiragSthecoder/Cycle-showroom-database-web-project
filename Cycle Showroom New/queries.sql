CREATE TABLE customer
(
c_username VARCHAR(20) PRIMARY KEY,
f_name VARCHAR(20),
l_name VARCHAR(20),
c_phone BIGINT(12),
email VARCHAR(30),
c_dob DATE,
c_password VARCHAR(20)
) ; 

CREATE TABLE admin
(
a_username VARCHAR(20) PRIMARY KEY,
f_name VARCHAR(20),
l_name VARCHAR(20),
a_phone BIGINT(12),
email VARCHAR(30),
a_dob DATE,
password VARCHAR(20)
) ;

CREATE TABLE company
 (
 cmp_logo VARCHAR(200),
 cmp_name VARCHAR(20) PRIMARY KEY,
 cmp_email VARCHAR(20),
 no_of_cycles INT(200) DEFAULT 0,
 cmp_address VARCHAR(200),
 cmp_desc VARCHAR(1000)
 ) ;


 CREATE TABLE cycles
(
cycle_photo VARCHAR(200),
cycle_id INT(20) PRIMARY KEY,
type VARCHAR(20),
cycle_name VARCHAR(20),
availability VARCHAR(20),
company VARCHAR(20),
price INT(20),
description VARCHAR(300),
FOREIGN KEY (company) REFERENCES company(cmp_name) ON DELETE 
CASCADE
) ;

CREATE TABLE bookings
(
cycle_photo VARCHAR(200),
c_username VARCHAR(20),
booking_id VARCHAR(20) PRIMARY KEY,
cycle_id INT,
cycle_name VARCHAR(20),
final_price INT(20),
FOREIGN KEY (c_username) REFERENCES customer(c_username) ON DELETE 
CASCADE,
FOREIGN KEY (cycle_id) REFERENCES cycles(cycle_id) ON DELETE CASCADE
);


-- Trigger to increment the no_of_cycles attribute of company table by 1 when a cycle information is added to the cycles table.
CREATE TRIGGER add_cycle
AFTER INSERT ON cycles
FOR EACH ROW
UPDATE company 
SET no_of_cycles = no_of_cycles + 1
WHERE cmp_name = new.company;


-- Trigger to decrement the no_of_cycles attribute of company table by 1 when a cycle information is removed from the cycles table.
CREATE TRIGGER remove_cycle
AFTER DELETE ON cycles
FOR EACH ROW
UPDATE company 
SET no_of_cycles = no_of_cycles - 1
WHERE cmp_name = old.company;

--STORED PROCEDURE.
DELIMITER //
CREATE PROCEDURE ViewBookings()
BEGIN
SELECT * FROM `bookings`;
END;
//
DELIMITER ;
