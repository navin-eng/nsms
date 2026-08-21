import re

file_path = "resources/views/backend/pages/sms/id_cards/staff.blade.php"
with open(file_path, "r") as f:
    c = f.read()

c = c.replace("your students.", "your staff.")
c = c.replace("studentSelectionCard", "staffSelectionCard")
c = c.replace("sms.id-cards.students", "sms.id-cards.staff")
c = c.replace("studentListBody", "staffListBody")
c = c.replace("request()->has('student_ids')", "request()->has('staff_ids')")
c = c.replace("request()->input('student_ids'", "request()->input('staff_ids'")
c = c.replace("id_student_", "id_staff_")
c = c.replace("student ID cards.", "staff ID cards.")
c = c.replace("api.students", "api.staff")
c = c.replace("success: function(students)", "success: function(staffMembersList)")
c = c.replace("var tbody = $('#studentListBody');", "var tbody = $('#staffListBody');")
c = c.replace("if (students.length === 0)", "if (staffMembersList.length === 0)")
c = c.replace("No students found in this class.", "No staff found in this department.")
c = c.replace("$.each(students, function(index, student)", "$.each(staffMembersList, function(index, staffMember)")
c = c.replace("var roll = student.roll_number", "var roll = staffMember.designation")
c = c.replace("var adm = student.admission_no", "var adm = staffMember.staff_id")
c = c.replace("student.id", "staffMember.id")
c = c.replace("student.name", "staffMember.name")
c = c.replace("student-checkbox", "staff-checkbox")
c = c.replace("student-name", "staff-name")
c = c.replace("loading students.", "loading staff.")
c = c.replace("$('.student-checkbox')", "$('.staff-checkbox')")
c = c.replace("Load Students", "Load Staff")

with open(file_path, "w") as f:
    f.write(c)


file_path2 = "resources/views/backend/pages/sms/id_cards/print_staff.blade.php"
with open(file_path2, "r") as f:
    c2 = f.read()

c2 = c2.replace("student-name", "staff-name")
c2 = c2.replace("id_student_", "id_staff_")

with open(file_path2, "w") as f:
    f.write(c2)
