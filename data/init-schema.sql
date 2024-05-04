USE org;

CREATE TABLE branch (
    id INT UNSIGNED AUTO_INCREMENT,
    city VARCHAR(45) NOT NULL,
    address VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE employee (
    id INT UNSIGNED AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    phone_number VARCHAR(12) NOT NULL,
    email VARCHAR(100) NOT NULL,
    gender ENUM('M', 'F') NOT NULL,
    date_of_birth DATE NOT NULL,
    hire_date DATE NOT NULL,
    comment TEXT(1000),
    path_photo VARCHAR(300),
    branch_id INT UNSIGNED,
    PRIMARY KEY (id),
    FOREIGN KEY (branch_id) REFERENCES branch(id)
);