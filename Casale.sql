CREATE TABLE USERS (
    id int not null AUTO_INCREMENT,
    username varchar(20) not null,
    password varchar(20) not null, 
    firstname varchar(20) not null,
    lastname varchar(20) not null,
    email varchar(20) not null,
    phone varchar(20) not null,
    address varchar(20) null,
    CONSTRAINT User_PK PRIMARY KEY (id),
    CONSTRAINT Unique_User UNIQUE (username),
    CONSTRAINT Unique_Email UNIQUE (email),
    CONSTRAINT Unique_Phone UNIQUE (phone)
);

CREATE TABLE CASES (
    id int not null AUTO_INCREMENT,
    title varchar(50) not null,
    court varchar(50) not null,
    type varchar(20) not null,
    filing_date DATE not null,
    status varchar(20) not null,
    CONSTRAINT Case_PK PRIMARY KEY (id),
    CONSTRAINT Case_Status_Check CHECK 
        (status IN ("Open", "Closed", "Pending", "Appeal")),
);