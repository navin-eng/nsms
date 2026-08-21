import re
import os

# 1. Update staff.blade.php
with open("resources/views/backend/pages/sms/id_cards/students.blade.php", "r") as f:
    c = f.read()

c = c.replace("Students ID Cards", "Staff ID Cards")
c = c.replace("sms.id_cards.students", "sms.id_cards.staff")
c = c.replace("Student ID Cards", "Staff ID Cards")
c = c.replace("Select Class", "Select Department")
c = c.replace("class_id", "department_id")
c = c.replace("$classes", "$departments")
c = c.replace("$class", "$department")
c = c.replace("$selectedClassId", "$selectedDepartmentId")

# Remove section dropdown
c = re.sub(r"<div class=\"col-md-3\">\s*<label class=\"form-label\">Select Section.*?</select>\s*</div>", "", c, flags=re.DOTALL)

c = c.replace("Select Students", "Select Staff")
c = c.replace("All Students in Class", "All Staff in Department")
c = c.replace("Specific Students", "Specific Staff")
c = c.replace("student_selection", "staff_selection")
c = c.replace("students_container", "staff_container")
c = c.replace("student_ids[]", "staff_ids[]")
c = c.replace("student-select", "staff-select")

c = c.replace("api/students", "api/staff")
c = c.replace("section_id: document.getElementById(\"section_id\").value", "")
c = c.replace("const classId = document.getElementById(\"class_id\").value;", "const departmentId = document.getElementById(\"department_id\").value;")
c = c.replace("if (!classId)", "if (!departmentId)")
c = c.replace("class_id: classId", "department_id: departmentId")

c = c.replace("st.roll_number", "st.designation")
c = c.replace("st.admission_no", "st.staff_id")
c = c.replace("Roll: ${st.roll_number || \"N/A\"}", "Designation: ${st.designation || \"N/A\"}")
c = c.replace("Adm: ${st.admission_no || \"N/A\"}", "ID: ${st.staff_id || \"N/A\"}")

c = c.replace("$students", "$staffMembers")
c = c.replace("$student", "$staff")
c = c.replace("$st", "$staff")
c = c.replace("student->id", "staff->id")
c = c.replace("student->full_name", "staff->full_name")
c = c.replace("student->currentEnrollment?->roll_number", "staff->designation?->name")
c = c.replace("student->admission_no", "staff->employee_id")

# Preview Section Replacements
c = c.replace("STUDENT IDENTITY CARD", "STAFF IDENTITY CARD")
c = c.replace("{{ $enrollment?->academicClass?->name ?? 'Class' }} -", "{{ $staff->department?->name ?? 'Dept' }}")
c = c.replace("{{ $enrollment?->section?->name ?? 'Sec' }}", "")
c = c.replace("Adm No:", "Staff ID:")
c = c.replace("Roll No:", "Designation:")
c = c.replace("{{ $staff->admission_no ?? '#' . $staff->id }}", "{{ $staff->employee_id ?? '#' . $staff->id }}")
c = c.replace("{{ $enrollment?->roll_number ?? '-' }}", "{{ $staff->designation?->name ?? '-' }}")
c = c.replace("DOB:", "Joined:")
c = c.replace("{{ $staff->dob ? \\Carbon\\Carbon::parse($staff->dob)->format('d M Y') : '-' }}", "{{ $staff->joining_date ? \\Carbon\\Carbon::parse($staff->joining_date)->format('d M Y') : '-' }}")
c = c.replace("Blood:", "Blood Grp:")
c = c.replace("Phone:", "Contact:")
c = c.replace("{{ $staff->guardian?->phone ?? '-' }}", "{{ $staff->phone ?? '-' }}")
c = c.replace("{{ $staff->phone ?? '-' }}", "{{ $staff->phone ?? '-' }}")
c = c.replace("uploads/students/", "uploads/staff/")

with open("resources/views/backend/pages/sms/id_cards/staff.blade.php", "w") as f:
    f.write(c)

# 2. Update print_staff.blade.php
with open("resources/views/backend/pages/sms/id_cards/print_students.blade.php", "r") as f:
    c2 = f.read()

c2 = c2.replace("$students", "$staffMembers")
c2 = c2.replace("$student", "$staff")
c2 = c2.replace("STUDENT IDENTITY CARD", "STAFF IDENTITY CARD")
c2 = c2.replace("{{ $staff->currentEnrollment?->academicClass?->name ?? 'Class' }} -", "{{ $staff->department?->name ?? 'Dept' }}")
c2 = c2.replace("{{ $staff->currentEnrollment?->section?->name ?? 'Sec' }}", "")
c2 = c2.replace("Adm No:", "Staff ID:")
c2 = c2.replace("Roll No:", "Designation:")
c2 = c2.replace("{{ $staff->admission_no ?? '#' . $staff->id }}", "{{ $staff->employee_id ?? '#' . $staff->id }}")
c2 = c2.replace("{{ $staff->currentEnrollment?->roll_number ?? '-' }}", "{{ $staff->designation?->name ?? '-' }}")
c2 = c2.replace("DOB:", "Joined:")
c2 = c2.replace("{{ $staff->dob ? \\Carbon\\Carbon::parse($staff->dob)->format('d M Y') : '-' }}", "{{ $staff->joining_date ? \\Carbon\\Carbon::parse($staff->joining_date)->format('d M Y') : '-' }}")
c2 = c2.replace("Blood:", "Blood Grp:")
c2 = c2.replace("Phone:", "Contact:")
c2 = c2.replace("{{ $staff->guardian?->phone ?? '-' }}", "{{ $staff->phone ?? '-' }}")
c2 = c2.replace("uploads/students/", "uploads/staff/")

# Remove section dropdown references if any
with open("resources/views/backend/pages/sms/id_cards/print_staff.blade.php", "w") as f:
    f.write(c2)

os.remove("resources/views/backend/pages/sms/id_cards/staff_temp.blade.php")
