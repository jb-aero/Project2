To create an instance of class, use $var = new Student/Advisor/Appointment($COMMON, [insert ID here]);
	-First parameter is an instance of the Common database class
	-Second parameter is identification for which record to pull from the table
		>For Student, this is the Student ID
		>For Advisor, this is the advisor's ID number
		>For Appointment, this is the appointment's ID number

Each class has get functions for each column in the database. They are named "get" followed by the name of the column in the database
Student and Appointment have getConvertMajor() functions. For student, this returns the full version of the student's major. 
For appointment, this returns a string with all of the majors that can sign up for that appointment converted to the full version.
	-It also has an optional parameter to specify the delimeter between the majors. By default, this is just a space

Student, Advisor, and Appointment all have create methods named "create" followed by the name of the class. 
The first parameter is always instance of the Common database class. The parameters after that are the information to fill the new record's columns with.
The create functions in Advisor and Appointment return false if a similar record already existed, or true if one did not, and so the new one was created.

The Appointment class has a searchAppointments() function. The first parameter is an instance of the Common database class. 
The other parameters are as follows:
	date: day for appointment
	advisorID: 0 = group, 1+ = specific individual advisor, I = all individual advisors
	major: acronym of major that appointment must be available for
	times: array of times for the appointments [optional; default: array()]
	limit: maximum number of appointments to get; -1 = all [optional; default: 30]
	futureOnly: only get appointments after the current date and time [optional; default: true]
	filter: '' = all appointment statuses, 0 = only open appointments, 1 = only closed appointments [optional; default: 0]
	studentID: the student ID that must be in the enrolled list; Empty = any students [optional; default: '']
It returns an array of Appointment objects that match the specified criteria

