CREATE TABLE ROLES (
    role_name VARCHAR(20) NOT NULL,
    CONSTRAINT ROLES_FK PRIMARY KEY (role_name)
);

-- ROLES INSERT
INSERT IGNORE INTO ROLES (role_name) VALUES
('client'),
('paralegal'),
('admin');

CREATE TABLE USERS (
    user_id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(100) NOT NULL, 
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    phone VARCHAR(20) NULL,
    address VARCHAR(255) NULL,
    role VARCHAR(20) DEFAULT "client",
    CONSTRAINT User_PK PRIMARY KEY (user_id),
    CONSTRAINT Unique_Email UNIQUE (email),
    CONSTRAINT Unique_Phone UNIQUE (phone),
    CONSTRAINT FK_User_Role 
        FOREIGN KEY (role) REFERENCES ROLES(role_name)
);

CREATE TABLE PERMISSIONS (
    permission_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(75) NOT NULL,
    CONSTRAINT Permission_PK PRIMARY KEY (permission_id)
);

-- PERMISSION INSERTS
INSERT INTO PERMISSIONS (name) VALUES 
    ('view-meetings'),
    ('change-meeting-status'),
    ('view-users'),
    ('view-cases'),
    ('set-times'),
    ('edit-task'),
    ('delete-task'),
    ('edit-case'),
    ('delete-case'),
    ('create-case'),
    ('delete-document'),
    ('edit-document-user'),
    ('view-tasks'),
    ('view-documents'),
    ('upload-document'),
    ('view-chats'),
    ('create-chat'),
    ('view-task')
    ;

CREATE TABLE ROLE_PERMISSIONS (
    permission_id INT not null,
    role_name VARCHAR(20) not null,
    CONSTRAINT RP_PK PRIMARY KEY (role_name, permission_id),
    CONSTRAINT RP_ROLE_FK FOREIGN KEY (role_name) REFERENCES ROLES(role_name),
    CONSTRAINT RP_PERMISSION_FK FOREIGN KEY (permission_id) REFERENCES PERMISSIONS(permission_id)
);

-- ROLE_PERMISSION INSERTS
INSERT INTO ROLE_PERMISSIONS (role_name, permission_id)
SELECT 'admin', permission_id FROM PERMISSIONS;

INSERT INTO ROLE_PERMISSIONS (role_name, permission_id)
SELECT 'paralegal', permission_id
FROM PERMISSIONS
WHERE name = 'view-meetings', 'view-task', 'view-chats';

INSERT INTO ROLE_PERMISSIONS (role_name, permission_id)
SELECT 'client', permission_id
FROM PERMISSIONS
WHERE name IN ('view-chats', 'view-meetings', 'view-cases', 'view-documents', 'view-task');

CREATE TABLE CHATS (
    chat_id INT NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    CONSTRAINT Chat_PK PRIMARY KEY (chat_id)
);

CREATE TABLE CHAT_USERS (
    chat_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT Chat_User_PK PRIMARY KEY (chat_id, user_id),
    CONSTRAINT Chat_User_FK_User FOREIGN KEY (user_id) REFERENCES USERS(user_id),
    CONSTRAINT Chat_User_FK_Chat FOREIGN KEY (chat_id) REFERENCES CHATS(chat_id)
);

CREATE TABLE CHAT_MESSAGES (
    message_id INT NOT NULL AUTO_INCREMENT,
    chat_id INT NOT NULL,
    sender_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT Chat_Message_PK PRIMARY KEY (message_id),
    CONSTRAINT Chat_Message_FK_Sender FOREIGN KEY (sender_id) REFERENCES USERS(user_id),
    CONSTRAINT Chat_Message_FK_Chat FOREIGN KEY (chat_id) REFERENCES CHATS(chat_id)
);

CREATE TABLE TASKS (
    task_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    description VARCHAR(250) NOT NULL,
    can_complete_digitally BOOLEAN NOT NULL DEFAULT FALSE,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',
	completion_notes VARCHAR(250) NULL,
    completion_file VARCHAR(200) NULL,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    due TIMESTAMP NOT NULL, 
    CONSTRAINT Task_PK PRIMARY KEY (task_id),
    CONSTRAINT Task_User_FK FOREIGN KEY (user_id) REFERENCES USERS(user_id),
    CONSTRAINT Task_Status_Check CHECK (
        status IN ('Pending', 'Completed')
    )
);

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
    user_id int null,
    title varchar(100) not null,
    court varchar(100) not null,
    type varchar(30) not null,
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
    fee_type VARCHAR(50) NOT NULL,
    description VARCHAR(200) NULL,
    amount DECIMAL(10,2) NOT NULL,
    date_charged DATE,
    FOREIGN KEY (case_id) REFERENCES cases(case_id)
);

CREATE TABLE case_hours (
    hours_id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    work_date DATE NOT NULL,
    hours DECIMAL(4,2) NOT NULL,
    description varchar(250) not null,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT CH_Case_FK FOREIGN KEY (case_id) REFERENCES Cases(case_id)
);

CREATE TABLE CASE_RETAINERS (
    case_id int not null,
	value int not null,
    CONSTRAINT CR_PK PRIMARY KEY (case_id),
    CONSTRAINT CR_User_FK FOREIGN KEY (case_id) REFERENCES Cases(case_id)
);

CREATE TABLE DOCUMENTS (
    document_id int not null AUTO_INCREMENT,
	case_id int null,
	name varchar(200) not null,
	description varchar(250) null,
	path varchar(200) not null, 
    CONSTRAINT D_PK PRIMARY KEY (document_id),
    CONSTRAINT D_Case_FK FOREIGN KEY (case_id) REFERENCES Cases(case_id)
);

CREATE TABLE DOCUMENT_USERS (
    document_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT Document_Users_PK PRIMARY KEY (document_id, user_id),
    CONSTRAINT Document_Users_Document_FK FOREIGN KEY (document_id) REFERENCES DOCUMENTS(document_id),
    CONSTRAINT Document_Users_User_FK FOREIGN KEY (user_id) REFERENCES USERS(user_id)
);

CREATE TABLE Cases_users (
    case_id int not null,
    user_id int not null,
    CONSTRAINT CU_PK PRIMARY KEY (case_id, user_id),
    CONSTRAINT CU_FK_CASE FOREIGN KEY (case_id) REFERENCES Cases(case_id),
    CONSTRAINT CU_FK_USER FOREIGN KEY (user_id) REFERENCES Users(user_id)
);


/**************
Run this to give an account admin role
change the where clause as needed
**************/
UPDATE USERS
SET role = 'admin'
WHERE user_id = 1;  
