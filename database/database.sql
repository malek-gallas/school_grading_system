CREATE DATABASE IF NOT EXISTS school;
USE school;
CREATE TABLE IF NOT EXISTS teachers (
    teacher_id INT AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (teacher_id)
);
CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (student_id)
);
CREATE TABLE IF NOT EXISTS courses (
    course_id INT AUTO_INCREMENT NOT NULL,
    course_name VARCHAR(255) NOT NULL,
    teacher_id INT NOT NULL,
    PRIMARY KEY (course_id),
    CONSTRAINT fk_teachers FOREIGN KEY (teacher_id)
    REFERENCES teachers (teacher_id)
);
CREATE TABLE IF NOT EXISTS grades (
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    tp INT NOT NULL,
    ds INT NOT NULL,
    ex INT NOT NULL,
    PRIMARY KEY (course_id, student_id),
    CONSTRAINT fk_courses FOREIGN KEY (course_id)
    REFERENCES courses (course_id)
    ON DELETE CASCADE,
    CONSTRAINT fk_students FOREIGN KEY (student_id)
    REFERENCES students (student_id)
    ON DELETE CASCADE
);