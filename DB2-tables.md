Summarized Tables Info

Account(email, password, type)
Department(dept_name, location)
Instructor(instructor_id, instructor_name, title, dept_name, email)
Student(student_id, name, email, dept_name)
PhD(student_id, qualifier, proposal_defence_date, dissertation_defence_date)
Master(student_id, total_credits)
Undergraduate(student_id, total_credits, class_standing)
Classroom(classroom_id, building, room_number, capacity)
Time Slot(time_slot_id, day, start_time, end_time)
Course(course_id, course_name, credits)
Section(course_id, section_id, semester, year, instructor_id, classroom_id, time_slot_id)
Prereq(course_id, prereq_id)
Advise(instructor_id, student_id, start_date, end_date)
TA(student_id, course_id, section_id, semester, year)
MasterGrader(student_id, course_id, section_id, semester, year)
UndergraduateGrader(student_id, course_id, section_id, semester, year)

*** THIS TABLE CHANGED Take(student_id, course_id, section_id, semester, year, grade)

Detailed Table Info

Account
email: varchar(50)
password: varchar(20) [Not Null]
type: varchar(20)
Primary Key: email

Department
dept_name: varchar(100)
location: varchar(100)
Primary Key: dept_name

Instructor
instructor_id: varchar(10)
instructor_name: varchar(50) [Not Null]
title: varchar(30)
dept_name: varchar(100)
email: varchar(50) [Not Null]
Primary Key: instructor_id

Student
student_id: varchar(10)
name: varchar(20) [Not Null]
email: varchar(50) [Not Null]
dept_name: varchar(100)
Primary Key: student_id
Foreign Key: dept_name references department(dept_name) ON DELETE SET NULL

PhD
student_id: varchar(10)
qualifier: varchar(30)
proposal_defence_date: date
dissertation_defence_date: date
Primary Key: student_id
Foreign Key: student_id references student(student_id) ON DELETE CASCADE

Master
student_id: varchar(10)
total_credits: int
Primary Key: student_id
Foreign Key: student_id references student(student_id) ON DELETE CASCADE

Undergraduate
student_id: varchar(10)
total_credits: int
class_standing: varchar(10) [Check: Freshman, Sophomore, Junior, Senior]
Primary Key: student_id
Foreign Key: student_id references student(student_id) ON DELETE CASCADE

Classroom
classroom_id: varchar(8)
building: varchar(15) [Not Null]
room_number: varchar(7) [Not Null]
capacity: numeric(4,0)
Primary Key: classroom_id

Time Slot
time_slot_id: varchar(8)
day: varchar(10) [Not Null]
start_time: time [Not Null]
end_time: time [Not Null]
Primary Key: time_slot_id

Course
course_id: varchar(20)
course_name: varchar(50) [Not Null]
credits: numeric(2,0) [Check: > 0]
Primary Key: course_id

Section
course_id: varchar(20)
section_id: varchar(10)
semester: varchar(6) [Check: Fall, Winter, Spring, Summer]
year: numeric(4,0) [Check: > 1990 and < 2100]
instructor_id: varchar(10)
classroom_id: varchar(8)
time_slot_id: varchar(8)
Primary Key: (course_id, section_id, semester, year)

Foreign Keys:
course_id references course(course_id) ON DELETE CASCADE
instructor_id references instructor(instructor_id) ON DELETE SET NULL
time_slot_id references time_slot(time_slot_id) ON DELETE SET NULL

Prereq
course_id: varchar(20)
prereq_id: varchar(20) [Not Null]
Primary Key: (course_id, prereq_id)
Foreign Keys:
course_id references course(course_id) ON DELETE CASCADE
prereq_id references course(course_id)

Advise
instructor_id: varchar(8)
student_id: varchar(10)
start_date: date [Not Null]
end_date: date
Primary Key: (instructor_id, student_id)
Foreign Keys:
instructor_id references instructor(instructor_id) ON DELETE CASCADE
student_id references PhD(student_id) ON DELETE CASCADE

TA
student_id: varchar(10)
course_id: varchar(8)
section_id: varchar(8)
semester: varchar(6)
year: numeric(4,0)
Primary Key: (student_id, course_id, section_id, semester, year)
Foreign Keys:
student_id references PhD(student_id) ON DELETE CASCADE
(course_id, section_id, semester, year) references section(course_id, section_id, semester, year) ON DELETE CASCADE

MasterGrader
student_id: varchar(10)
course_id: varchar(8)
section_id: varchar(8)
semester: varchar(6)
year: numeric(4,0)
Primary Key: (student_id, course_id, section_id, semester, year)
Foreign Keys:
student_id references master(student_id) ON DELETE CASCADE
(course_id, section_id, semester, year) references section(course_id, section_id, semester, year) ON DELETE CASCADE

UndergraduateGrader
student_id: varchar(10)
course_id: varchar(8)
section_id: varchar(8)
semester: varchar(6)
year: numeric(4,0)
Primary Key: (student_id, course_id, section_id, semester, year)
Foreign Keys:
student_id references undergraduate(student_id) ON DELETE CASCADE
(course_id, section_id, semester, year) references section(course_id, section_id, semester, year) ON DELETE CASCADE

Take
student_id: varchar(10)
course_id: varchar(8)
section_id: varchar(8)
semester: varchar(6)
year: numeric(4,0)
grade: varchar(2) [Check: A+, A, A-, B+, B, B-, C+, C, C-, D+, D, D-, F]
Primary Key: (student_id, course_id, section_id, semester, year)
Foreign Keys:
(course_id, section_id, semester, year) references section(course_id, section_id, semester, year) ON DELETE CASCADE
student_id references student(student_id) ON DELETE CASCADE








thinking notes
Section
course_id: varchar(20)
section_id: varchar(10)
semester: varchar(6) [Check: Fall, Winter, Spring, Summer]
year: numeric(4,0) [Check: > 1990 and < 2100]
instructor_id: varchar(10)
classroom_id: varchar(8)
time_slot_id: varchar(8)
Primary Key: (course_id, section_id, semester, year)