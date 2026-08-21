import re
import os

file_path = "resources/views/backend/pages/sms/id_cards/staff.blade.php"
with open(file_path, "r") as f:
    c = f.read()

# Remove hidden section_id inputs
c = re.sub(r'<input type="hidden" name="section_id" [^>]*>', '', c)

# Remove JS logic related to sections
c = re.sub(r'var sectionSelect = \$\(\'#sectionFilter\'\);.*?sectionSelect\.html\(\'<option value="">All Sections</option>\'\);\s*\}\s*\}\);', '', c, flags=re.DOTALL)
c = c.replace("var sectionId = $('#sectionFilter').val();", "")
c = c.replace(", section_id: sectionId", "")
c = c.replace("$('#hidden_section_id').val(sectionId);", "")
c = c.replace("Class & Section Filter", "Department Filter")
c = c.replace("var classId = $('#classFilter').val();", "var classId = $('#classFilter').val();\nif (!classId) return;") # Keep class logic working but named classId since we didn't rename the variable in JS properly if we missed it

with open(file_path, "w") as f:
    f.write(c)
