To create an instance of class, use $var = new Student/Advisor/Appointment($COMMON, [insert ID here]);
	-First parameter is an instance of the Common database class
	-Second parameter is identification for which record to pull from the table
		>For Student, this is the Student ID
		>For Advisor, this is the advisor's ID number
		>For Appointment, this is the appointment's ID number

Each class has get functions for each column in the database. They are named "get" followed by the name of the column in the database
Student and Appointment have convertMajor() functions. For student, this returns the full version of the student's major. 
For appointment, this returns a string with all of the majors that can sign up for that appointment converted to the full version.
	-It also has an optional parameter to specify the delimeter between the majors. By default, this is just a space

Advisor has a convertFullName() function. It returns the advisor's first name and last name combined with a space in-between.

Student, Advisor, and Appointment all have create methods named "create" followed by the name of the class. 
The first parameter is always instance of the Common database class. The parameters after that are the information to fill the new record's columns with.
The create functions in Advisor and Appointment return false if a similar record already existed, or true if one did not, and so the new one was created.

The Appointment class has a searchAppointments() function. The first parameter is an instance of the Common database class. 
The other parameters are as follows (passing null for any optional parameter will use it's default value):
	advisorID: 0 = group, 1+ = specific individual advisor, I = all individual advisors [can be ignored with null]
	major: acronym of major that appointment must be available for [optional; default: '']
	date: day for appointment; empty = all dates [optional; default: '']
	times: array of times for the appointments [optional; default: array()]
	futureOnly: only get appointments after the current date and time [optional; default: true]
	limit: maximum number of appointments to get; -1 = all [optional; default: 30]
	filter: '' = all appointment statuses, 0 = only open appointments, 1 = only closed appointments [optional; default: 0]
	studentID: the student ID that must be in the enrolled list; Empty = any students [optional; default: '']
It returns an array of Appointment objects that match the specified criteria

