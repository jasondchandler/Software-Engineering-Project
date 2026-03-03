
-- Creates
CREATE TABLE USERS (
    user_id int not null AUTO_INCREMENT,
    username varchar(20) not null,
    password varchar(20) not null, 
    firstname varchar(20) not null,
    lastname varchar(20) not null,
    email varchar(20) not null,
    phone varchar(20) not null,
    address varchar(20) null,
    CONSTRAINT User_PK PRIMARY KEY (user_id),
    CONSTRAINT Unique_User UNIQUE (username),
    CONSTRAINT Unique_Email UNIQUE (email),
    CONSTRAINT Unique_Phone UNIQUE (phone)
);

CREATE TABLE CASES (
    case_id int not null AUTO_INCREMENT,
    title varchar(50) not null,
    court varchar(50) not null,
    type varchar(20) not null,
    filing_date DATE not null,
    status varchar(20) not null,
    CONSTRAINT Case_PK PRIMARY KEY (case_id),
    CONSTRAINT Case_Status_Check CHECK 
        (status IN ("Open", "Closed", "Pending", "Appeal"))
);


CREATE TABLE MEETINGS (
  meeting_id int NOT NULL AUTO_INCREMENT,
  client_name varchar(150) NOT NULL,
  title varchar(150)  NOT NULL,
  notes text NULL,
  location varchar(255) NOT NULL,
  status enum('pending','scheduled','confirmed','completed','cancelled','no_show') NOT NULL DEFAULT 'pending',
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT Meeting_PK PRIMARY KEY (meeting_id)
);

CREATE TABLE MEETING_TIMES (
  meeting_time_id int NOT NULL,
  meeting_id int NOT NULL,
  start_time datetime NOT NULL,
  end_time datetime NOT NULL,
  CONSTRAINT Meeting_PK PRIMARY KEY (meeting_time_id)
);


-- Sample data
INSERT INTO USERS 
    VALUES ("AdminCasale", "1", "Charles", "Casale", "casale@gmail.com", "1112223456");

INSERT INTO USERS 
    VALUES ("jason12", "2", "Jason", "Chandler", "jason@gmail.com", "1234567890");

INSERT INTO USERS 
    VALUES ("Chiemela", "3", "Chiemela", "Francis", "chiemela@gmail.com", "0987654321");

INSERT INTO USERS 
    VALUES ("Stephen", "4", "Stephen", "Escalante", "stephen@gmail.com", "4561237890");

INSERT INTO USERS 
    VALUES ("William", "5", "William", "Mazal", "william@gmail.com", "789012345");


INSERT INTO CASES
    VALUES ("Chandler v. State", "New Jersey Superior Court", 
            "criminal", "2026-02-05", "open");

INSERT INTO CASES
    VALUES ("Francis v. Smith", "Philadelphia Municiple Court", 
            "negligence", "2026-01-28", "open");    

INSERT INTO CASES
    VALUES ("Mazal v. Mayo", "Central Municiple Court", 
            "matrimonial", "2026-02-09", "open");    



