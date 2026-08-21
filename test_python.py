import re

content = """
<div class="collapse {{ request()->is('admin/dashboard/course*') ? 'show' : '' }}" id="sbCourse">
some content
</div>
"""

def repl(m):
    return m.group(0) + " data-bs-parent=\"#sb-menu\""

new_content = re.sub(r"<div class=\"collapse [^\"]*\" id=\"sb[A-Za-z0-9]+\"", repl, content)
print(new_content)
