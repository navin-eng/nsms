import re
import os

file_path = "resources/views/backend/pages/sms/id_cards/staff.blade.php"
with open(file_path, "r") as f:
    c = f.read()

# Fix the departmentId ReferenceError
c = c.replace("var classId = $('#classFilter').val();\nif (!classId) return;", "var departmentId = $('#classFilter').val();")
c = c.replace("var classId = $('#classFilter').val();", "var departmentId = $('#classFilter').val();")
c = c.replace("if (!departmentId) {\n                        alert('Please select a class first.');", "if (!departmentId) {\n                        alert('Please select a department first.');")
c = c.replace("data: { department_id: classId }", "data: { department_id: departmentId }")
c = c.replace("$('#hidden_department_id').val(classId);", "$('#hidden_department_id').val(departmentId);")
c = c.replace("var classId = $(this).val();", "var departmentId = $(this).val();")

# Fix the student variable ReferenceError in the AJAX loop
c = c.replace("var roll = staffMember.designation ? student.roll_number : '-';", "var roll = staffMember.designation ? staffMember.designation : '-';")
c = c.replace("var adm = staffMember.staff_id ? student.admission_no : '#'+staffMember.id;", "var adm = staffMember.staff_id ? staffMember.staff_id : '#'+staffMember.id;")

with open(file_path, "w") as f:
    f.write(c)

