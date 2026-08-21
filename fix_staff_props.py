import os

files = [
    "resources/views/backend/pages/sms/id_cards/staff.blade.php",
    "resources/views/backend/pages/sms/id_cards/print_staff.blade.php"
]

for file_path in files:
    if not os.path.exists(file_path):
        continue
    with open(file_path, "r") as f:
        c = f.read()

    # fix guardian phone
    c = c.replace("$staff->phone ?? $staff->guardian?->guardian_phone ?? ''", "$staff->phone ?? $staff->emergency_contact ?? ''")
    
    # fix dob
    c = c.replace("$staff->date_of_birth", "$staff->dob")
    
    # fix print staff leftover syntax error just in case
    c = c.replace("str_replace('[SECTION_NAME]',  ?? '', $html);", "str_replace('[SECTION_NAME]', '', $html);")

    # fix another possible leftover guardian
    c = c.replace("$staff->guardian?->guardian_phone", "$staff->emergency_contact")

    with open(file_path, "w") as f:
        f.write(c)

