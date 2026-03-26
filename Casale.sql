
-- Creates
CREATE TABLE USERS (
    user_id int not null AUTO_INCREMENT,
    email varchar(20) not null,
    password varchar(100) not null, 
    firstname varchar(20) not null,
    lastname varchar(20) not null,
    phone varchar(20) null,
    address varchar(20) null,
    role varchar(20) default "client",
    CONSTRAINT User_PK PRIMARY KEY (user_id),
    CONSTRAINT Unique_Email UNIQUE (email),
    CONSTRAINT Unique_Phone UNIQUE (phone),
    CONSTRAINT Check_Role CHECK 
        (role IN ("client", "paralegal", "admin"))
);

CREATE TABLE ROLE_PERMISSIONS (
	rp_id int not null AUTO_INCREMENT,
	permission_id int not null,
	role varchar(20) not null,
	CONSTRAINT Role_Permissions_PK PRIMARY KEY (rp_id)
);

CREATE TABLE PERMISSIONS (
	permission_id int not null AUTO_INCREMENT,
	name VARCHAR(75) not null,
	role varchar(20) not null,
	CONSTRAINT Permission_PK PRIMARY KEY (permission_id)
);

/**************
INSERT STATEMENTS FOR PERMISSIONS
kebab case, lowercase, <75 chars
**************/
INSERT INTO PERMISSIONS (name, role) VALUES ('view-meetings', 'client');
INSERT INTO PERMISSIONS (name, role) VALUES ('view-meetings', 'admin');
INSERT INTO PERMISSIONS (name, role) VALUES ('confirm-meetings', 'admin');


CREATE TABLE MEETINGS (
  meeting_id int NOT NULL AUTO_INCREMENT,
  location varchar(255) NOT NULL DEFAULT "Zoom",
  duration int NOT NULL,           
  notes text NULL,
  status enum('pending','confirmed','cancelled', 'no_show', 'complete') NOT NULL DEFAULT 'pending',
  user_id int NOT NULL,
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT Meeting_PK PRIMARY KEY (meeting_id),
  CONSTRAINT Meeting_FK FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE MEETING_TIMES (
  meeting_time_id int NOT NULL AUTO_INCREMENT,
  meeting_id int NOT NULL,
  start_time datetime NOT NULL,
  end_time datetime NOT NULL,
  CONSTRAINT Meeting_Time_PK PRIMARY KEY (meeting_time_id),
  CONSTRAINT Meeting_Time_FK FOREIGN KEY (meeting_id) REFERENCES Meetings(meeting_id)
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

CREATE TABLE UNAVAILABLE_TIMES (
    times_id INT AUTO_INCREMENT,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    repeat_daily BOOLEAN DEFAULT FALSE,
    CONSTRAINT Times_PK Primary Key (times_id)
);
 
CREATE TABLE case_fee (
    fee_id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    fee_type VARCHAR(50),
    amount DECIMAL(10,2) NOT NULL,
    date_charged DATE,
    FOREIGN KEY (case_id) REFERENCES cases(id)
);

CREATE TABLE case_hours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    work_date DATE NOT NULL,
    hours DECIMAL(4,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (case_id) REFERENCES cases(id)
);
