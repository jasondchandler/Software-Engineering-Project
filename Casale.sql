
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

CREATE TABLE PERMISSIONS (
	permission_id int not null AUTO_INCREMENT,
	name VARCHAR(75) not null,
	role varchar(20) not null,
	CONSTRAINT Permission_PK PRIMARY KEY (permission_id)
);

CREATE TABLE TASKS (
    task_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(250) NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    due_date DATE NULL,
    CONSTRAINT Task_PK PRIMARY KEY (task_id),
    CONSTRAINT Task_User_FK FOREIGN KEY (user_id) REFERENCES USERS(user_id),
    CONSTRAINT Task_Status_Check CHECK (
        status IN ('Pending', 'In Progress', 'Completed')
    )
);

/**************
INSERT STATEMENTS FOR PERMISSIONS
kebab case, lowercase, <75 chars
**************/
INSERT INTO PERMISSIONS (name, role) VALUES ('view-meetings', 'client');
INSERT INTO PERMISSIONS (name, role) VALUES ('view-meetings', 'admin');
INSERT INTO PERMISSIONS (name, role) VALUES ('view-meetings', 'paralegal');
INSERT INTO PERMISSIONS (name, role) VALUES ('change-meeting-status', 'admin');
INSERT INTO PERMISSIONS (name, role) VALUES ('view-users', 'admin');
INSERT INTO PERMISSIONS (name, role) VALUES ('set-times', 'admin');


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
    fee_type VARCHAR(50) not null,
	description varchar(200) null, 
    amount DECIMAL(10,2) NOT NULL,
    date_charged DATE,
    FOREIGN KEY (case_id) REFERENCES cases(id)
);

CREATE TABLE case_hours (
    hours_id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    work_date DATE NOT NULL,
    hours DECIMAL(4,2) NOT NULL,
    description varchar(250) not null,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT FOREIGN KEY (case_id) REFERENCES Cases(case_id)
);

CREATE TABLE CASE_USERS (
    user_id int not null,
    case_id int not null,
    CONSTRAINT CU_PK PRIMARY KEY (user_id, case_id),
    CONSTRAINT User_FK FOREIGN KEY (user_id) REFERENCES Users(user_id),
    CONSTRAINT User_FK FOREIGN KEY (case_id) REFERENCES Cases(case_id)
);


CREATE TABLE CASE_RETAINERS (
    case_id int not null,
	value int not null,
    CONSTRAINT CR_PK PRIMARY KEY (case_id),
    CONSTRAINT User_FK FOREIGN KEY (case_id) REFERENCES Cases(case_id)
);

CREATE TABLE DOCUMENTS (
    document_id int not null AUTO_INCREMENT,
	case_id int null,
	name varchar(200) not null,
	description varchar(250) null,
	path varchar(200) not null, 
    CONSTRAINT CU_PK PRIMARY KEY (document_id),
    CONSTRAINT User_FK FOREIGN KEY (case_id) REFERENCES Cases(case_id)
);

/**************
Run this to give an account admin role
change the where clause as needed
**************/
UPDATE users SET role = 'admin' WHERE user_id = 1;












SELECT case_id, 'hour logged' AS type, hours AS value, work_date AS activity_date
FROM case_hours

UNION ALL

SELECT case_id, 'fee added' AS type, amount AS value, date_charged AS activity_date
FROM case_fee

ORDER BY activity_date DESC;



