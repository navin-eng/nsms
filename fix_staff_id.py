import os

files = [
    "resources/views/backend/pages/sms/id_cards/staff.blade.php",
    "resources/views/backend/pages/sms/id_cards/print_staff.blade.php"
]

for file_path in files:
    with open(file_path, "r") as f:
        c = f.read()

    c = c.replace("$enrollment?->roll_number", "$staff->designation?->name")
    c = c.replace("STUDENT", "STAFF")
    c = c.replace("$staff->admission_no", "$staff->employee_id")
    c = c.replace("$enrollment?->academicClass?->name", "$staff->department?->name")
    c = c.replace("$enrollment?->section?->name", "")

    with open(file_path, "w") as f:
        f.write(c)
