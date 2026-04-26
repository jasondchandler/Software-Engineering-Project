CREATE TABLE roles (
    role_name VARCHAR(20) NOT NULL,
    CONSTRAINT roles_pk PRIMARY KEY (role_name)
);

-- ROLES INSERT
INSERT IGNORE INTO roles (role_name) VALUES
('client'),
('paralegal'),
('admin');

CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(100) NOT NULL,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    phone VARCHAR(20) NULL,
    address VARCHAR(255) NULL,
    role VARCHAR(20) DEFAULT "client",
    CONSTRAINT user_pk PRIMARY KEY (user_id),
    CONSTRAINT unique_email UNIQUE (email),
    CONSTRAINT unique_phone UNIQUE (phone),
    CONSTRAINT fk_user_role
        FOREIGN KEY (role) REFERENCES roles(role_name)
);

CREATE TABLE permissions (
    permission_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(75) NOT NULL,
    CONSTRAINT permission_pk PRIMARY KEY (permission_id)
);

-- PERMISSION INSERTS
INSERT INTO permissions (name) VALUES
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
('view-task');

CREATE TABLE role_permissions (
    permission_id INT NOT NULL,
    role_name VARCHAR(20) NOT NULL,
    CONSTRAINT role_permissions_pk PRIMARY KEY (role_name, permission_id),
    CONSTRAINT rp_role_fk FOREIGN KEY (role_name) REFERENCES roles(role_name),
    CONSTRAINT rp_permission_fk FOREIGN KEY (permission_id) REFERENCES permissions(permission_id)
);

-- ROLE_PERMISSION INSERTS
INSERT INTO role_permissions (role_name, permission_id)
SELECT 'admin', permission_id FROM permissions;

INSERT INTO role_permissions (role_name, permission_id)
SELECT 'paralegal', permission_id
FROM permissions
WHERE name IN ('view-meetings', 'view-tasks', 'view-chats');

INSERT INTO role_permissions (role_name, permission_id)
SELECT 'client', permission_id
FROM permissions
WHERE name IN ('view-chats', 'view-meetings', 'view-cases', 'view-documents', 'view-tasks');

CREATE TABLE conversations (
    conversation_id INT NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chat_pk PRIMARY KEY (conversation_id)
);

CREATE TABLE conversation_users (
    conversation_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT chat_user_pk PRIMARY KEY (conversation_id, user_id),
    CONSTRAINT chat_user_fk_user FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT chat_user_fk_chat FOREIGN KEY (conversation_id) REFERENCES conversations(conversation_id)
);

CREATE TABLE conversation_messages (
    message_id INT NOT NULL AUTO_INCREMENT,
    conversation_id INT NOT NULL,
    sender_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chat_message_pk PRIMARY KEY (message_id),
    CONSTRAINT chat_message_fk_sender FOREIGN KEY (sender_id) REFERENCES users(user_id),
    CONSTRAINT chat_message_fk_chat FOREIGN KEY (conversation_id) REFERENCES conversations(conversation_id)
);

CREATE TABLE tasks (
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
    CONSTRAINT task_pk PRIMARY KEY (task_id),
    CONSTRAINT task_user_fk FOREIGN KEY (user_id) REFERENCES users(user_id),
    CONSTRAINT task_status_check CHECK (status IN ('Pending', 'Completed'))
);

CREATE TABLE meetings (
  meeting_id INT NOT NULL AUTO_INCREMENT,
  location VARCHAR(255) NOT NULL DEFAULT "Zoom",
  duration INT NOT NULL,
  notes TEXT NULL,
  status ENUM('pending','confirmed','cancelled','no_show','complete') NOT NULL DEFAULT 'pending',
  user_id INT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT meeting_pk PRIMARY KEY (meeting_id),
  CONSTRAINT meeting_fk FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE meeting_times (
  meeting_time_id INT NOT NULL AUTO_INCREMENT,
  meeting_id INT NOT NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  CONSTRAINT meeting_time_pk PRIMARY KEY (meeting_time_id),
  CONSTRAINT meeting_time_fk FOREIGN KEY (meeting_id) REFERENCES meetings(meeting_id)
);

CREATE TABLE cases (
    case_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NULL,
    title VARCHAR(100) NOT NULL,
    court VARCHAR(100) NOT NULL,
    type VARCHAR(30) NOT NULL,
    filing_date DATE NOT NULL,
    status VARCHAR(20) NOT NULL,
    CONSTRAINT case_pk PRIMARY KEY (case_id),
    CONSTRAINT case_status_check CHECK (status IN ('Open', 'Closed', 'Pending', 'Appeal'))
);

CREATE TABLE unavailable_times (
    times_id INT AUTO_INCREMENT,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    repeat_daily BOOLEAN DEFAULT FALSE,
    CONSTRAINT times_pk PRIMARY KEY (times_id)
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
    description VARCHAR(250) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT ch_case_fk FOREIGN KEY (case_id) REFERENCES cases(case_id)
);

CREATE TABLE case_retainers (
    case_id INT NOT NULL,
    value INT NOT NULL,
    CONSTRAINT case_retainers_pk PRIMARY KEY (case_id),
    CONSTRAINT cr_case_fk FOREIGN KEY (case_id) REFERENCES cases(case_id)
);

CREATE TABLE documents (
    document_id INT NOT NULL AUTO_INCREMENT,
    case_id INT NULL,
    name VARCHAR(200) NOT NULL,
    description VARCHAR(250) NULL,
    path VARCHAR(200) NOT NULL,
    CONSTRAINT documents_pk PRIMARY KEY (document_id),
    CONSTRAINT d_case_fk FOREIGN KEY (case_id) REFERENCES cases(case_id)
);

CREATE TABLE document_users (
    document_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT document_users_pk PRIMARY KEY (document_id, user_id),
    CONSTRAINT du_document_fk FOREIGN KEY (document_id) REFERENCES documents(document_id),
    CONSTRAINT du_user_fk FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE cases_users (
    case_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT cases_users_pk PRIMARY KEY (case_id, user_id),
    CONSTRAINT cu_case_fk FOREIGN KEY (case_id) REFERENCES cases(case_id),
    CONSTRAINT cu_user_fk FOREIGN KEY (user_id) REFERENCES users(user_id)
);

/**************
Run this to give an account admin role
change the where clause as needed
**************/
UPDATE users
SET role = 'admin'
WHERE user_id = 1;